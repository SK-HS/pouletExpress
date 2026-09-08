<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CommandeClient;
use App\Models\CommandeLivreur;
use App\Models\Fournisseur;
use App\Models\FournisseurSolde;
use App\Models\GestionnaireSolde;
use App\Models\Livreur;
use App\Models\LivreurSolde;
use App\Models\Quartier;
use App\Models\StatutCommande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientsController extends Controller
{
    public function espace_client_last(Request $request)
    {

    $validated = $request->validate([
    'search' => 'nullable|string|max:100',
    'status' => 'nullable',
    'page'   => 'nullable|integer|min:1',
]);

    $client = Auth::guard('client')->user();


    // Statistiques globales
    $totalCommandes = CommandeClient::where('client_id', $client->id)->count();

    $totalDepense = CommandeClient::where('client_id', $client->id)
        ->where('commande_recu', 1)
        ->sum('montant_ttc');

    $commandesLivrees = CommandeClient::where('client_id', $client->id)
        ->where('commande_recu', 1)
        ->count();

    $commandesEnCours = CommandeClient::where('client_id', $client->id)
        ->whereHas('livraison', function ($q) {
            $q->whereIn('statut', ['EN_ATTENTE', 'AFFECTEE', 'EN_ROUTE']);
        })
        ->count();

    // Commande active à mettre en avant dans la bannière (la plus récente en cours)
    $commandeActive = CommandeClient::with(['livraison'])
        ->where('client_id', $client->id)
        ->whereHas('livraison', function ($q) {
            $q->whereIn('statut', ['EN_ATTENTE', 'AFFECTEE', 'EN_ROUTE']);
        })
        ->orderBy('date_commande', 'desc')
        ->first();

 
    // 1. Requête de base
    $query = CommandeClient::where('client_id', $client->id)
                           ->with('livraison')
                           ->orderBy('created_at', 'desc');

    // 2. Filtre Recherche (Numéro de commande)
    if ($request->filled('search')) {
        $query->where('reference', 'LIKE', '%' . $request->search . '%');
    }

    // 3. Filtre par Statut
    if ($request->filled('statut') && $request->statut !== 'tous') {
        $statut = $request->statut;
        if ($statut === 'annule') {
            $query->where('statut', 'ANNULEE');
        } elseif ($statut === 'livre') {
            $query->where('commande_livree', 1);
        } elseif ($statut === 'RECEPTIONNEE') {
            $query->where('commande_recu', 1);
        } elseif ($statut === 'en_cours') {
            $query->where('statut', '!=', 'ANNULEE')->where('commande_recu', 0);
        }
    }

    // 4. Filtre par Période (Dates)
    if ($request->filled('date_debut')) {
        $query->whereDate('date_commande', '>=', $request->date_debut);
    }
    if ($request->filled('date_fin')) {
        $query->whereDate('date_commande', '<=', $request->date_fin);
    }

    // 5. Total de la période
    $totalPeriode = $query->sum('montant_ttc');

    // 6. Pagination avec conservation des filtres dans l'URL
    $commandes = $query->paginate(10)->appends($request->all());
    

       return view('clients.index', compact(
        'commandes',
        'totalCommandes',
        'totalDepense',
        'commandesLivrees',
        'commandesEnCours',
        'commandeActive',
        'totalPeriode',
    ));
}

public function espace_client(Request $request)
{
    $client = Auth::guard('client')->user();

    //  Validation stricte des filtres — jamais de valeur arbitraire
    // injectée dans la requête SQL
    $validated = $request->validate([
        'statut'     => 'nullable|in:tous,en_cours,livre,RECEPTIONNEE,annule',
        'search'     => 'nullable|string|max:50',
        'date_debut' => 'nullable|date|before_or_equal:today',
        'date_fin'   => 'nullable|date|before_or_equal:today|after_or_equal:date_debut',
    ]);

    $query = CommandeClient::with('livraison')
        ->where('client_id', $client->id); // IDOR bloqué : uniquement les commandes du client connecté

    // Filtre par statut
    switch ($validated['statut'] ?? 'tous') {
        case 'en_cours':
            $query->where('statut', '!=', 'ANNULEE')
                  ->where('commande_recu', 0);
            break;
        case 'livre':
            $query->where('commande_recu', 1);
            break;
        case 'RECEPTIONNEE':
            $query->whereHas('livraison', fn ($q) => $q->where('statut', 'RECEPTIONNEE'));
            break;
        case 'annule':
            $query->where('statut', 'ANNULEE');
            break;
        // 'tous' → pas de filtre supplémentaire
    }

    if (!empty($validated['search'])) {
        $query->where('reference', 'like', '%' . $validated['search'] . '%');
    }

    if (!empty($validated['date_debut'])) {
        $query->whereDate('date_commande', '>=', $validated['date_debut']);
    }

    if (!empty($validated['date_fin'])) {
        $query->whereDate('date_commande', '<=', $validated['date_fin']);
    }

    $commandes = (clone $query)
        ->orderBy('date_commande', 'desc')
        ->paginate(5)
        ->withQueryString();

    $totalPeriode = (clone $query)->sum('montant_ttc');

    // Requête AJAX : on ne retourne QUE le fragment nécessaire,
    // sans recalculer les stats globales de la bannière (gain de perf important
    // vu que ce chemin est appelé à chaque frappe clavier / changement de filtre)
    if ($request->ajax() || $request->wantsJson()) {
        return view('clients.historique_commande', [
            'commandes'    => $commandes,
            'totalPeriode' => $totalPeriode,
        ]);
    }

    // Chargement complet de la page (première visite) : stats globales calculées une seule fois
    $totalCommandes = CommandeClient::where('client_id', $client->id)->count();

    $totalDepense = CommandeClient::where('client_id', $client->id)
        ->where('commande_recu', 1)
        ->sum('montant_ttc');

    $commandesLivrees = CommandeClient::where('client_id', $client->id)
        ->where('commande_recu', 1)
        ->count();

    $commandesEnCours = CommandeClient::where('client_id', $client->id)
        ->where('statut', '!=', 'ANNULEE')
        ->where('commande_recu', 0)
        ->count();

    $commandeActive = CommandeClient::with('livraison')
        ->where('client_id', $client->id)
        ->whereHas('livraison', function ($q) {
            $q->whereIn('statut', ['EN_ATTENTE', 'AFFECTEE', 'RECUPEREE', 'EN_ROUTE']);
        })
        ->orderBy('date_commande', 'desc')
        ->first();

    return view('clients.index', [
        'commandes'        => $commandes,
        'totalPeriode'     => $totalPeriode,
        'totalCommandes'   => $totalCommandes,
        'totalDepense'     => $totalDepense,
        'commandesLivrees' => $commandesLivrees,
        'commandesEnCours' => $commandesEnCours,
        'commandeActive'   => $commandeActive,
    ]);
}





    //  return view('clients.index', compact('commandes'));
    // }

    public function profil_client()
    {
        $infos = Client::where('id', Auth::guard('client')->id())->first();
        $quartiers = Quartier::get();

     return view('clients.profil_page',compact('infos','quartiers'));
    }

    public function suivi_commande_client()
    {
        // $livraison =null;
        // $commandes = CommandeClient::with('detailCommandeClients')
        //              ->where('client_id', Auth::guard('client')->id())
        //              ->latest('id')
        //              ->first();
        // $livraison =$commandes?->livraison;
        
         $client = Auth::guard('client')->user();

    $commandes = CommandeClient::with([
            'client',
            'fournisseur',
            'quartier',
            'livreur',
            'livraison',
            'detailCommandeClients.produitFournisseur.produit',
            'detailCommandeClients.produitFournisseur.fournisseur',
        ])
        ->where('client_id', $client->id)
        ->latest('id')
        ->first();
        
         if (!$commandes) {
        abort(404, 'Commande introuvable.');
    }
    
        $livraison =$commandes->livraison;
        
     return view('clients.suivi_commande',compact('commandes','livraison'));
    }

    public function suivi_last_commande_client(int $id)
    {
        // $commandes = CommandeClient::with('detailCommandeClients', 'livraison')
        //              ->where('client_id', Auth::guard('client')->id())
        //              ->where('id', $id)
        //              ->first();
        // $livraison =$commandes?->livraison;
        
        $client = Auth::guard('client')->user();

    $commandes = CommandeClient::with([
            'client',
            'fournisseur',
            'quartier',
            'livreur',
            'livraison',
            'detailCommandeClients.produitFournisseur.produit',
            'detailCommandeClients.produitFournisseur.fournisseur',
        ])
        ->where('client_id', $client->id)
        ->where('id', $id)
        ->first();
        
            if (!$commandes) {
        abort(404, 'Commande introuvable.');
    }
    
        $livraison =$commandes?->livraison;
        
        //dd($commandes);
     
     return view('clients.suivi_commande',compact('commandes','livraison'));
    }


        public function suivi_statut_commande_client(int $id) {
        // Récupérer la commande
        $commande = CommandeClient::with('livreur')->findOrFail($id);
        
        // Récupérer la livraison liée
        $livraison = CommandeLivreur::where('commande_client_id', $id)->first();
        
        return response()->json([
            'statut' => $livraison ? $livraison->statut : $commande->statut,
            'heure_mise_a_jour' => now()->format('H:i'),
            'livreur' => $commande->livreur ? [
                'nom' => $commande->livreur->nom,
                'telephone' => $commande->livreur->telephone,
            ] : null
        ]);
    }

    public function suiviPolling(string $reference)
{
    
    $client = Auth::guard('client')->user();
    
    $commande = CommandeClient::with(['livraison.livreur'])
        ->where('reference', $reference)
        ->where('client_id', $client->id)
        ->firstOrFail();

  if (!$commande) {
        abort(404);
    }
    $livraison = $commande->livraison;

    return response()->json([
        'statut' => $livraison->statut ?? 'EN_ATTENTE',

        'livreur' => $livraison?->livreur ? [
            'nom' => $livraison->livreur->nom,
            'telephone' => $livraison->livreur->telephone,
            'latitude'  => $livraison->latitude,
            'longitude' => $livraison->longitude,
        ] : null,

        'dates' => [
            'date_affectation' => $livraison?->date_affectation?->format('d/m H:i'),
            'date_depart' => $livraison?->date_depart?->format('d/m H:i'),
            'date_arrivee' => $livraison?->date_arrivee?->format('d/m H:i'),
        ],
    ]);
}



   public function valider_livraison_commande(Request $request, int $id)
{
    $client = Auth::guard('client')->user();

    try {
        $resultat = DB::transaction(function () use ($id, $client) {
            
            // 1. Verrouillage : On cherche la commande et on s'assure qu'elle appartient bien à CE client
            $commande = CommandeClient::where('id', $id)
                ->where('client_id', $client->id)
                ->lockForUpdate()
                ->firstOrFail();

            // 2. ANTI DOUBLE-CLIC : Évite de créditer le fournisseur 2 fois
            if ($commande->statut === 'RECEPTIONNEE' || $commande->commande_recu == 1) {
                return [
                    'success' => false, 
                    'message' => 'Vous avez déjà validé la réception de cette commande.'
                ];
            }

            if (!in_array($commande->statut, ['RECUPEREE', 'EN_ROUTE', 'LIVREE'])) {
                return [
                    'success' => false,
                    'message' => "Impossible de valider : la commande n'est pas encore en cours de livraison (statut : {$commande->statut})."
                ];
            }
 
            // 3. Mise à jour de la commande
            $commande->update([
                'statut' => 'RECEPTIONNEE',
                'commande_recu' => 1,
                'date_commane_recu' => now(), // (Attention à la faute de frappe d'origine dans votre BDD)
            ]);

            // 4. Historique du statut
            StatutCommande::create([
                'statut' => 'RECEPTIONNEE',
                'commande_client_id' => $commande->id,
                'type' => 'CLIENT',
                'typeId' => $client->id, 
            ]);

            // ============================================================
            // COMMISSION FOURNISSEUR (10% sur le montant brut produits)
            // ============================================================

        if ($commande->montant_brut > 0) {
            $tauxCommissionF = 0.10; // 10%, configurable
            $commissionF = round($commande->montant_brut * $tauxCommissionF);
            $montantF = $commande->montant_brut - $commissionF;

        FournisseurSolde::create([
                'statut' => 'EN_ATTENTE', 
                'commande_client_id' => $id,
                'fournisseur_id' => $commande->fournisseur_id,
                'montant' => $montantF,
                'disponible_le' => now()->addHours(24),

            ]);

        GestionnaireSolde::create([
                    'debiteur_type' => Fournisseur::class,
                    'debiteur_id'   => $commande->fournisseur_id,
                    'statut' => 'EN_ATTENTE', 
                    'commande_client_id' => $id,
                    'montant' => $commissionF,
                    'disponible_le' => now()->addHours(24),
                    'details' => 'Commission sur commande fournisseur (10%)',
                ]);
            }

            // ============================================================
            // COMMISSION LIVREUR (5% sur les frais de service)
            // ============================================================

            $montant_livraison = $commande->detailCommandeClients()
                                        ->where('type', 'SERVICE') 
                                        ->sum('montant');

            $commandeLivreur = CommandeLivreur::where('commande_client_id', $id)
                                            ->lockForUpdate()
                                            ->first();

        if ($commandeLivreur && $montant_livraison > 0){
            
            $tauxCommissionL = 0.05; // 5%, configurable
            $commissionL = round($montant_livraison * $tauxCommissionL);
            $montantL = $montant_livraison - $commissionL;

            LivreurSolde::create([
                    'statut' => 'EN_ATTENTE', 
                    'commande_livreur_id' => $commandeLivreur->id,
                    'montant' => $montantL,
                    'livreur_id' => $commande->livreur_id,
                    'disponible_le' => now()->addHours(24),
                ]);

            GestionnaireSolde::create([
                    'debiteur_type' => Livreur::class,
                    'debiteur_id'   => $commande->livreur_id,
                    'statut' => 'EN_ATTENTE', 
                    'commande_client_id' => $id,
                    'montant' => $commissionL,
                    'disponible_le' => now()->addHours(24),
                    'details' => 'Commission sur livraison commande (5%)',
                ]);

            }
                

            return ['success' => true, 'message' => 'Commande reçue et validée avec succès. Merci !'];
        });

        // 7. Retours propres
        if ($request->wantsJson()) {
            if (!$resultat['success']) {
                return response()->json($resultat, 400); // 400 Bad Request
            }
            return response()->json($resultat);
        }

        if (!$resultat['success']) {
            return redirect()->back()->with('error', $resultat['message']);
        }
        return redirect()->back()->with('success', $resultat['message']);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        if ($request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Commande introuvable.'], 404);
        }
        return redirect()->back()->with('error', 'Commande introuvable.');
        
    } catch (\Exception $e) {
        // Enregistrement optionnel de l'erreur dans les logs Laravel : Log::error($e->getMessage());
        if ($request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Erreur système lors de la validation.'], 500);
        }
        return redirect()->back()->with('error', 'Une erreur inattendue est survenue.');
    }
}




}
