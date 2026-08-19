<?php

namespace App\Http\Controllers;

use App\Models\Approvisionnement;
use App\Models\Categorie;
use App\Models\CommandeClient;
use App\Models\Produit;
use App\Models\ProduitFournisseur;
use App\Models\Quartier;
use App\Models\Taille;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FournisseurController extends Controller
{
    
    public function espace_fournisseur( Request $request)
    {
       $validate= $request->validate([
             'periode' => ['nullable', 'integer', 'min:1', 'max:365'],
           ]);

         $fournisseur = Auth::guard('fournisseur')->user();
        $quartiers = Quartier::get();

   $periode = $validate['periode'] ?? 30;
    $dateDebut = now()->subDays($periode);

    // ── Ventes totales sur la période (commandes confirmées reçues) ──
    $ventesTotales = CommandeClient::where('fournisseur_id', $fournisseur->id)
        ->where('commande_recu', 1)
        ->where('date_commande', '>=', $dateDebut)
        ->sum('montant_ttc');

    // Comparaison période précédente (pour le badge +12%)
    $dateDebutPrecedente = now()->subDays($periode * 2);
    $ventesPeriodePrecedente = CommandeClient::where('fournisseur_id', $fournisseur->id)
        ->where('commande_recu', 1)
        ->whereBetween('date_commande', [$dateDebutPrecedente, $dateDebut])
        ->sum('montant_ttc');

    $evolutionVentes = $ventesPeriodePrecedente > 0
        ? round((($ventesTotales - $ventesPeriodePrecedente) / $ventesPeriodePrecedente) * 100, 1)
        : ($ventesTotales > 0 ? 100 : 0);

    // ── Commandes en attente ──
    $commandesEnAttente = CommandeClient::where('fournisseur_id', $fournisseur->id)
        ->whereHas('livraison', function ($q) {
            $q->whereIn('statut', ['EN_ATTENTE', 'AFFECTEE', 'RECUPEREE']);
        })
        ->count();

    // ── Produits actifs ──
    $produitsActifs = ProduitFournisseur::where('fournisseur_id', $fournisseur->id)
        ->where('etat', 1)
        ->count();

    // ── Note moyenne (si tu as une table d'avis, sinon placeholder) ──
    $noteMoyenne = $fournisseur->note_moyenne ?? null;
    $nbAvis = $fournisseur->nb_avis ?? 0;

    // ── Commandes récentes (10 dernières) ──
    $commandesRecentes = CommandeClient::with(['client', 'livraison'])
        ->where('fournisseur_id', $fournisseur->id)
        ->orderBy('date_commande', 'desc')
        ->limit(10)
        ->get();

    // ── Top produits vendus (sur la période) ──
    $topProduits = DB::table('detail_commande_clients as dcc')
        ->join('commande_clients as cc', 'cc.id', '=', 'dcc.commande_client_id')
        ->join('produit_fournisseurs as pf', 'pf.id', '=', 'dcc.produit_fournisseur_id')
        ->join('produits as p', 'p.id', '=', 'pf.produit_id')
        ->where('pf.fournisseur_id', $fournisseur->id)
        ->where('dcc.type', 'PRODUIT')
        ->where('cc.date_commande', '>=', $dateDebut)
        ->select(
            'p.nom',
            'p.image',
            DB::raw('SUM(dcc.quantite) as total_quantite'),
            DB::raw('SUM(dcc.montant) as total_montant')
        )
        ->groupBy('p.id', 'p.nom', 'p.image')
        ->orderByDesc('total_quantite')
        ->limit(5)
        ->get();

    $maxQuantiteTop = $topProduits->max('total_quantite') ?: 1;

    return view('fournisseurs.index', compact(
        'fournisseur',
        'periode',
        'ventesTotales',
        'evolutionVentes',
        'commandesEnAttente',
        'produitsActifs',
        'noteMoyenne',
        'nbAvis',
        'commandesRecentes',
        'topProduits',
        'maxQuantiteTop'
    ));
    }
    
    public function catalogue_fournisseur()
    {
        $fournisseur = Auth::guard('fournisseur')->user();
        $quartiers = Quartier::get();
        $produits = ProduitFournisseur::with('produit','categorie','taille')
                                ->where('fournisseur_id', $fournisseur->id)
                                ->get();
        return view('fournisseurs.catalogue', compact('quartiers','produits'));
    }
    public function approvisonnement()
    {
        $fournisseur = Auth::guard('fournisseur')->user();
        $quartiers = Quartier::get();
        $produits = ProduitFournisseur::with('produit','categorie','taille')
                                ->where('fournisseur_id', $fournisseur->id)
                                ->get();
        return view('fournisseurs.approvisionnement', compact('quartiers','produits'));
    }

       public function approvisionnement_historique()
    {
        $fournisseurId = Auth::guard('fournisseur')->id();

        $approvisionnements = Approvisionnement::with(['produitFournisseur.produit', 'produitFournisseur.categorie'])
            ->where('fournisseur_id', $fournisseurId)
            ->orderBy('created_at', 'desc')
            ->paginate(15); // On pagine par 15 lignes par page pour que ce soit propre

        return view('fournisseurs.historique_approvisionnement', compact('approvisionnements'));
    }

    public function reapprovisionner(Request $request,int $id)
    {
    // 1. Validation de la saisie
    $request->validate([
        'quantite_ajoutee' => 'required|integer|min:1'
    ]);

    // 2. On récupère le produit
    $produit = ProduitFournisseur::findOrFail($id);
    
    // On sauvegarde l'ancien stock pour l'historique
    $stockAvant = $produit->quantite;
    $quantiteAjoutee = $request->quantite_ajoutee;
    $stockApres = $stockAvant + $quantiteAjoutee;

    // 3. On enregistre l'historique dans la nouvelle table
    Approvisionnement::create([
        'produit_fournisseur_id' => $produit->id,
        'fournisseur_id'         => Auth::guard('fournisseur')->id(),
        'quantite_ajoutee'       => $quantiteAjoutee,
        'quantite_avant'         => $stockAvant,
        'quantite_apres'         => $stockApres,
        'motif'                  => 'Réapprovisionnement stock',
    ]);

    // 4. On met à jour le VRAI stock du produit
    $produit->update([
        'quantite' => $stockApres
    ]);

    // 5. On redirige avec le message de succès !
    return redirect()->back()->with('success', "Vous avez ajouté {$quantiteAjoutee} unités avec succès !");
}


    // public function commande_fournisseur(Request $request)
    // {
    //     $fournisseur = Auth::guard('fournisseur')->user();
    //     // $commandes = CommandeClient::with('client','detailCommandeClients')
    //     //                             ->where('fournisseur_id', $fournisseur->id)
    //     //                              ->get();
    //      $query = CommandeClient::where('fournisseur_id',  $fournisseur->id)
    //                        ->with('client', 'detailCommandeClients');
    // // On applique le filtre si ce n'est pas "TOUTES"
    // if ($request->filled('statut') && $request->statut !== 'TOUTES') {
    //     $query->where('statut', $request->statut);
    // }
    // $commandes = $query->orderBy('created_at', 'desc')->get();
   

    // return view('fournisseurs.commande', compact('commandes'));
    // }

    public function commande_fournisseur(Request $request)
{
    $fournisseurId = Auth::guard('fournisseur')->id();

    // 1. Initialiser la requête de base
    $query = CommandeClient::where('fournisseur_id', $fournisseurId)
                           ->with('client') // Charger la relation pour optimiser
                           ->orderBy('created_at', 'desc');

    // 2. Filtre par Statut (les onglets)
    if ($request->filled('statut')) {
        $query->where('statut', $request->statut);
    }

    // 3. Filtre de Recherche (Numéro de commande ou Nom du client)
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('reference', 'LIKE', "%{$search}%")
              ->orWhereHas('client', function($clientQuery) use ($search) {
                  $clientQuery->where('nom', 'LIKE', "%{$search}%")
                              ->orWhere('telephone', 'LIKE', "%{$search}%");
              });
        });
    }

    // 4. Filtre par Période (Date de début et Date de fin)
    if ($request->filled('date_debut')) {
        $query->whereDate('date_commande', '>=', $request->date_debut);
    }
    if ($request->filled('date_fin')) {
        $query->whereDate('date_commande', '<=', $request->date_fin);
    }

    // 5. Calculer le total pour la période filtrée (AVANT la pagination)
    $totalPeriode = $query->sum('montant_ttc');

    // 6. Paginer les résultats (15 par page)
    // append() permet de garder les filtres dans l'URL quand on change de page
    $commandes = $query->paginate(15)->appends($request->all());

    // Compteur pour la pastille "Nouvelles"
    $countNouvelles = CommandeClient::where('fournisseur_id', $fournisseurId)
                                    ->whereIn('statut', ['NOUVELLE', 'EN ATTENTE'])->count();

    return view('fournisseurs.commande', compact('commandes', 'totalPeriode', 'countNouvelles'));
}


    public function suivi_Commandes_fournisseur(Request $request, int $id)
    {
                $fournisseur = Auth::guard('fournisseur')->user();
                
            $commande = CommandeClient::with([
                    'client',
                    'livraison',
                    'detailCommandeClients.produitFournisseur.produit',
                ])
                ->where('fournisseur_id', $fournisseur->id)
                ->findOrFail($id);

            return view('fournisseurs.suivi_commande', [
                'commande'  => $commande,
                'livraison' => $commande->livraison,
                'fournisseur' => $fournisseur,
            ]);
    }

    public function suivi_position_commande(int $commandeId)
{
    $fournisseurUser = Auth::guard('fournisseur')->user();

    $commande = CommandeClient::with(['client', 'commandeLivreur.livreur', 'commandeLivreur.position'])
        ->where('fournisseur_id', $fournisseurUser->id)
        ->findOrFail($commandeId);

    $livraison = $commande->commandeLivreur;
    $position  = $livraison?->position;

    return response()->json([
        'statut' => $livraison->statut ?? 'EN_ATTENTE',

        'livreur' => $livraison?->livreur ? [
            'nom'       => $livraison->livreur->nom,
            'telephone' => $livraison->livreur->telephone,
        ] : null,

        // Position LIVE du livreur (change à chaque polling)
        'position' => $position ? [
            'lat'         => (float) $position->latitude,
            'lng'         => (float) $position->longitude,
            'capturee_at' => $position->capturee_at->diffForHumans(),
        ] : null,

        'dates' => [
            'date_affectation' => $livraison?->date_affectation?->format('d/m H:i'),
            'date_depart'      => $livraison?->date_depart?->format('d/m H:i'),
            'date_recuperation' => $livraison?->date_recuperation?->format('d/m H:i'),
            'date_arrivee'     => $livraison?->date_arrivee?->format('d/m H:i'),
        ],
    ]);
}


    public function commande_livree_fournisseur(int $id)
    {
        $fournisseur = Auth::guard('fournisseur')->user();

        $commande = CommandeClient::where('id', $id)
            ->where('fournisseur_id', $fournisseur->id)
            ->firstOrFail();

        $commande->update(['cmmd_livre_fournisseur' => 1, 'date_cmmd_livre_fournisseur'=>now()]);

        return redirect()->back()->with('success', 'Merci ! Votre commande a été marquée comme récupérée.');
    }




    public function produit_fournisseur()
    {

        $categories = Categorie::get();
        $tailles = Taille::get();
        $produits = Produit::get();
        return view('fournisseurs.produit', compact('categories','tailles','produits'));
    }
    public function edite_produit_fournisseur(int $id)
    {
        $fournisseur = Auth::guard('fournisseur')->user();
        $categories = Categorie::get();
        $tailles = Taille::get();
        $produits = Produit::get();
        $produit = ProduitFournisseur::where('fournisseur_id', $fournisseur->id)
                                        ->where('id',$id)                                
                                        ->first();

        return view('fournisseurs.edite_produit', compact('categories','tailles','produits','produit'));
    }



    
public function save_produit_fournisseur(Request $request)
{
    // 1. Récupération du fournisseur connecté
    $fournisseur = Auth::guard('fournisseur')->user();
    // 2. Validation des données du formulaire
    $validatedData = $request->validate([
        'produit_id' => 'required|exists:produits,id', // Assurez-vous que la table s'appelle bien 'produits'
        'categorie_id' => 'required|exists:categories,id',
        'taille_id' => 'nullable|exists:tailles,id',
        'description' => 'nullable|string',
        'nom_produit' => 'nullable|string',
        'prix' => 'required|numeric|min:0',
        'quantite' => 'required|numeric|min:0',
        'commande_min' => 'nullable|numeric|min:1',
        'temps_preparation' => 'nullable|string',
        // Validation multiple pour les images
        'images' => 'nullable|array',
        'images.*' => 'image|max:5120',
        
        // Notes : 
        // - 'nom_produit' et 'temps_preparation' ne sont pas dans votre $fillable de base. 
        //   Vous pourrez les ajouter à ProduitFournisseur si nécessaire.
    ]);
    // 3. Gestion de l'upload des images
    $imagePaths = []; // Tableau qui va contenir les chemins de toutes les images uploadées
    
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            // Sauvegarde chaque image dans le dossier 'storage/app/public/produits'
            $path = $image->store('Produits', 'public');
            $imagePaths[] = $path; // On ajoute le chemin au tableau
        }
    }
    // 4. Définition de l'état (la case à cocher)
    // Si la case 'Activer immédiatement' est cochée, on met 'Actif', sinon 'Inactif'
    $etat = $request->has('etat') ? '1' : '0';
    // 5. Sauvegarde dans la base de données
    ProduitFournisseur::create([
        'fournisseur_id' => $fournisseur->id,
        'produit_id' => $validatedData['produit_id'],
        'categorie_id' => $validatedData['categorie_id'],
        'taille_id' => $validatedData['taille_id'],
        'description' => $validatedData['description'],
        'prix' => $validatedData['prix'],
        'quantite' => $validatedData['quantite'],
        'commande_min' => $validatedData['commande_min'] ?? 1,
        'nom_produit' => $validatedData['nom_produit'],
        'temps_preparation' => $validatedData['temps_preparation'],
        'etat' => $etat,
        'statuts' => $etat, // Si vous utilisez les deux colonnes
        'images' => $imagePaths, 
        // 'user_id' => null, // Optionnel (utile si c'est rattaché à un admin plus tard)
    ]);
    // 6. Redirection vers le catalogue avec un message de succès
    return redirect()->route('Fournisseur-Catalogue')->with('success', 'Votre produit a été ajouté au catalogue avec succès !');
}


public function save_edite_produit_fournisseur(Request $request,int $id)
{
    // 1. Récupération du fournisseur connecté
    $fournisseur = Auth::guard('fournisseur')->user();
    // 2. Récupérer le produit (et s'assurer par sécurité qu'il appartient bien à ce fournisseur)
    $produit = ProduitFournisseur::where('id', $id)
                                 ->where('fournisseur_id', $fournisseur->id)
                                 ->firstOrFail();
    // 3. Validation des données
    $validatedData = $request->validate([
        'produit_id' => 'required|exists:produits,id',
        'categorie_id' => 'required|exists:categories,id',
        'taille_id' => 'nullable|exists:tailles,id',
        'description' => 'nullable|string',
        'prix' => 'required|numeric|min:0',
        'quantite' => 'required|numeric|min:0',
        'commande_min' => 'nullable|numeric|min:1',
         'nom_produit' => 'nullable|string',
         'temps_preparation' => 'nullable|string',
        // Images optionnelles pour la mise à jour
        'images' => 'nullable|array',
        'images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
    ]);
    // L'état de la case à cocher
    $etat = $request->has('etat') ? '1' : '0';
    // 4. Préparation des données à mettre à jour
    $donneesMaj = [
        'produit_id' => $validatedData['produit_id'],
        'categorie_id' => $validatedData['categorie_id'],
        'taille_id' => $validatedData['taille_id'],
        'description' => $validatedData['description'],
        'prix' => $validatedData['prix'],
        'quantite' => $validatedData['quantite'],
        'commande_min' => $validatedData['commande_min'] ?? 1,
        'nom_produit' => $validatedData['nom_produit'],
        'temps_preparation' => $validatedData['temps_preparation'],
        'etat' => $etat,
        'statuts' => $etat,
    ];
    // 5. Gestion des images : Seulement si de NOUVELLES images sont uploadées
    if ($request->hasFile('images')) {
        
        // A. Supprimer les anciennes images du disque pour libérer de l'espace
        $anciennesImages = $produit->images;
        if (is_array($anciennesImages)) {
            foreach ($anciennesImages as $oldImg) {
                if (Storage::disk('public')->exists($oldImg)) {
                    Storage::disk('public')->delete($oldImg);
                }
            }
        } elseif ($produit->images && Storage::disk('public')->exists($produit->images)) {
            // Au cas où c'était stocké en tant que chaîne simple avant
            Storage::disk('public')->delete($produit->images);
        }
        // B. Enregistrer les nouvelles images
        $imagePaths = [];
        foreach ($request->file('images') as $image) {
            $imagePaths[] = $image->store('produits', 'public');
        }
        
        // C. Ajouter le nouveau JSON des images aux données à mettre à jour
        $donneesMaj['images'] = $imagePaths;
    }
    // 6. Mise à jour dans la base de données
    $produit->update($donneesMaj);
    // 7. Redirection avec message
    return redirect()->route('Fournisseur-Catalogue')
                     ->with('success', 'Les informations du produit ont été modifiées avec succès !');
}


public function export_Commandes_fournisseur(Request $request)
{
    $validate = $request->validate([
    'periode' => ['nullable', 'integer', 'min:1', 'max:365'],
       ]);

    $fournisseur = Auth::guard('fournisseur')->user();
    $periode = $validate['periode'] ?? 30;
    $dateDebut = now()->subDays($periode);

    $commandes = CommandeClient::with(['client'])
        ->where('fournisseur_id', $fournisseur->id)
        ->where('date_commande', '>=', $dateDebut)
        ->orderBy('date_commande', 'desc')
        ->get();

    $filename = 'commandes_' . now()->format('Y-m-d') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    $callback = function () use ($commandes) {
        $file = fopen('php://output', 'w');
        // BOM UTF-8 pour Excel
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($file, ['Référence', 'Client', 'Date', 'Montant TTC', 'Statut'], ';');

        foreach ($commandes as $cmd) {
            fputcsv($file, [
                $cmd->reference,
                $cmd->client?->nom ?? '—',
                $cmd->date_commande,
                $cmd->montant_ttc,
                $cmd->statut,
            ], ';');
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

}
