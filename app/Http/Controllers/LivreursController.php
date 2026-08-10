<?php

namespace App\Http\Controllers;

use App\Models\CommandeClient;
use App\Models\CommandeLivreur;
use App\Models\DetailCommandeClient;
use App\Models\LivreurSolde;
use App\Models\Quartier;
use App\Models\StatutCommande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class LivreursController extends Controller
{
    /**
     * Rayon maximum (en km) pour qu'une commande soit visible par un livreur
     */
     protected float $rayonKm = 10;

  
    public function espace_livreur()
    {

    //     return view('livreur.index', [
    //         'commandes' => $this->getCommandesDisponibles(),
    //     ]);
    // }

    $livreur = Auth::guard('livreur')->user();
    $today   = now()->toDateString();
 
    // ── Commandes disponibles (non encore acceptées, dans la zone) ──
    $commandesDisponibles = $this->getCommandesDisponibles();
 
    // ── Commandes en cours du livreur (AFFECTEE ou EN_ROUTE) ──
    $commandesEnCours = CommandeClient::with([
            'client', 'fournisseur', 'quartier', 'livraison'
        ])
        ->whereHas('livraison', function ($q) use ($livreur) {
            $q->where('livreur_id', $livreur->id)
              ->whereIn('statut', ['AFFECTEE', 'EN_ROUTE','RECUPEREE']);
        })
        ->orderBy('created_at', 'desc')
        ->get();
 
    // ── Statistiques du jour ──
    $livraisonsJour = CommandeLivreur::where('livreur_id', $livreur->id)
        ->where('statut', 'LIVREE')
        ->whereDate('date_arrivee', $today)
        ->count();
 
    $livraisonsTotal = CommandeLivreur::where('livreur_id', $livreur->id)
        ->where('statut', 'LIVREE')
        ->count();
 
    // Distance du jour (somme des distances des livraisons terminées aujourd'hui)
    $distanceJour = CommandeLivreur::where('livreur_id', $livreur->id)
        ->where('statut', 'LIVREE')
        ->whereDate('date_arrivee', $today)
        ->sum('distance');
 
    // Gains du jour (montant_ttc des commandes livrées aujourd'hui)
    $gainJour = CommandeClient::whereHas('livraison', function ($q) use ($livreur, $today) {
            $q->where('livreur_id', $livreur->id)
              ->where('statut', 'LIVREE')
              ->whereDate('date_arrivee', $today);
        })
        ->sum('montant_ttc');
 
    // Gains totaux
    $gainTotal = CommandeClient::whereHas('livraison', function ($q) use ($livreur) {
            $q->where('livreur_id', $livreur->id)->where('statut', 'LIVREE');
        })
        ->sum('montant_ttc');
  
    // Note moyenne (à adapter si tu as une table d'avis)
    $note = '—'; // remplace par la vraie note si tu as un système d'avis
 
    return view('livreur.index', compact(
        'commandesDisponibles',
        'commandesEnCours',
        'livraisonsJour',
        'livraisonsTotal',
        'distanceJour',
        'gainJour',
        'gainTotal',
        'note'
    ));
}


    public function profil_livreur()
    {
         $livreur = Auth::guard('livreur')->user();
         $quartiers = Quartier::get();

        return view('livreur.profil_livreur',compact('livreur','quartiers'));
    }

    

    public function localisation_produit()
    {
    //     $commandes= CommandeClient::all();
    //     return view('livreur.localisation_produit', compact('commandes'));
    // }

    $livreur = Auth::guard('livreur')->user();

    $commandes = CommandeClient::with(['client', 'quartier'])
        // ->whereHas('commandeLivreur', function ($q) use ($livreur) {
        //     $q->where('livreur_id', $livreur->id)
        //       ->whereIn('statut', ['AFFECTEE', 'EN_ROUTE']);
        // })
        ->get();

    $destinations = [];
    foreach ($commandes as $c) {
        $destinations[] = [
            'ref'      => $c->reference,
            'lat'      => $c->latitude  ? (float) $c->latitude  : null,
            'lng'      => $c->longitude ? (float) $c->longitude : null,
            'client'   => $c->client?->nom ?? 'Client',
            'quartier' => $c->quartier?->nom_quartier ?? '',
        ];
    }

    return view('livreur.localisation_produit', [
        'commandes'    => $commandes,
        'destinations' => $destinations, // ← transmis à la vue
    ]);
}

    // public function livreur_commandes()
    // {   
        
    //    $livreur = Auth::guard('livreur')->user();
 
    // $commandes = CommandeClient::with([
    //         'client',
    //         'fournisseur',
    //         'quartier',
    //         'livraison',
    //         'detailCommandeClients',
            
    //     ])
    //     ->whereHas('livraison', function ($q) use ($livreur) {
    //         $q->where('livreur_id', $livreur->id);
    //     })
    //     ->orderBy('created_at', 'desc')
    //     ->paginate(20);
 
    // // Ajoute le statut de livraison comme attribut accessible dans la vue
    // $commandes->each(function ($cmd) {
    //     $cmd->statut_livraison = $cmd->livraison?->statut ?? 'AFFECTEE';
    // });
    //     return view('livreur.commande_livreur', [
    //         'commandes' => $commandes,
    //     ]);
    // }

    public function livreur_commandes(Request $request)
{   
    $livreur = Auth::guard('livreur')->user();

    // 1. Requête de base : On récupère les commandes affectées à CE livreur
    $commande = CommandeClient::where('livreur_id', $livreur->id)
                                ->get();

    $query = CommandeClient::with([
        'client', 'fournisseur', 'quartier', 'livraison', 'detailCommandeClients'
    ])
    ->whereHas('livraison', function ($q) use ($livreur) {
        $q->where('livreur_id', $livreur->id);
    })
    ->orderBy('created_at', 'desc');

    // 2. Filtre Recherche (Numéro de commande ou nom du client)
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('reference', 'LIKE', '%' . $search . '%')
              ->orWhereHas('client', function($clientQuery) use ($search) {
                  $clientQuery->where('nom', 'LIKE', '%' . $search . '%');
              });
        });
    }

    // 3. Filtre par Statut (Les valeurs envoyées par les boutons du Blade)
    if ($request->filled('statut') && $request->statut !== 'all') {
        $statut = $request->statut; // Ex: "AFFECTEE", "RECUPEREE", etc.
        
        // Comme le statut concerne la "Livraison", on filtre sur la relation !
        $query->whereHas('livraison', function($q) use ($statut) {
            $q->where('statut', $statut);
        });
    }

    // 4. Filtre par Période (Dates)
    if ($request->filled('date_debut')) {
        $query->whereDate('date_commande', '>=', $request->date_debut);
    }
    if ($request->filled('date_fin')) {
        $query->whereDate('date_commande', '<=', $request->date_fin);
    }

    // 5. Total de la période (pour le tableau de bord)
    $totalPeriode = $query->sum('montant_ttc');

    // 6. Pagination avec conservation des filtres dans l'URL
    $commandes = $query->paginate(15)->appends($request->all());

    // N'oubliez pas de passer les totaux à la vue pour les parenthèses de vos onglets !
    return view('livreur.commande_livreur', [
        'commande' => $commande,
        'commandes' => $commandes,
        'totalPeriode' => $totalPeriode,
    ]);
}

    public function detail_commande_livreur(int $commandeId)
{
    $livreur = Auth::guard('livreur')->user();

    $commande = CommandeClient::with([
            'client',
            'quartier',
            'livraison',
            'detailCommandeClients.produitFournisseur.produit',
        ])
        ->whereHas('livraison', function ($q) use ($livreur) {
            $q->where('livreur_id', $livreur->id);
        })
        ->findOrFail($commandeId);

    $articles = $commande->detailCommandeClients
        ->where('type', 'PRODUIT')
        ->map(fn ($d) => [
            'nom'     => $d->produitFournisseur?->produit?->nom ?? '—',
            'quantite'=> $d->quantite,
            'montant' => $d->montant,
        ]);

    return response()->json([
        'reference'     => $commande->reference,
        'client'        => $commande->client?->nom ?? '—',
        'telephone'     => $commande->telephone_livraison,
        'quartier'      => $commande->quartier?->nom_quartier ?? '—',
        'lieu_livraison'=> $commande->lieu_livraison,
        'creneau'       => $commande->creneau,
        'mode_paiement' => $commande->mode_paiement ?? null,
        'total'         => $commande->montant_ttc,
        'latitude'      => $commande->latitude,
        'longitude'     => $commande->longitude,
        'articles'      => $articles,
    ]);
}

    //  Mes livraisons en cours (affectées à moi, pas encore livrées)
     
      public function mesLivraisons()
    {
        $livreur = Auth::guard('livreur')->user();

        $livraisons = CommandeLivreur::where('livreur_id', $livreur->id)
            ->whereIn('statut', ['AFFECTEE', 'EN_ROUTE'])
            ->with(['commande.details.produit.produit', 'quartier'])
            ->orderBy('date_affectation', 'desc')
            ->get();

        return view('livreur.mes-livraisons', ['livraisons' => $livraisons]);
    }



     public function pollingCommandes()
    {
        
        return response()->json([
            'commandes' => $this->getCommandesDisponibles(),
        ]);
    }
    //   public function livreur_commandes_disponibles($lat, $lon)
    // {
    //      $latLivreur = request('lat');
    //     $lngLivreur = request('lng');
    //      return response()->json([
    //         'commandes' => $this->getCommandesDisponibles(),
    //     ]);
    // }

  public function Livreur_accepter_commande(Request $request, int $id)
{
    $livreur = Auth::guard('livreur')->user();

    try {
        $resultat = DB::transaction(function () use ($id, $livreur) {
            
            // 1. Verrouillage : On cherche la commande sans préciser 'livreur_id' 
            // car elle n'appartient encore à personne (ou elle est diffusée)
            $commandeLivreur = CommandeLivreur::where('id', $id)
                ->lockForUpdate()
                ->firstOrFail();

            // 2. ANTI-COLLISION CRITIQUE : Vérifie si un autre l'a déjà prise
            if ($commandeLivreur->statut !== 'EN_ATTENTE') {
                return [
                    'success' => false, 
                    'message' => 'Trop tard ! Cette commande a déjà été acceptée par un autre livreur.'
                ];
            }

            // 3. Verrouillage de la commande client
            $commande = CommandeClient::where('id', $commandeLivreur->commande_client_id)
                ->lockForUpdate()
                ->firstOrFail();

            // 4. Mises à jour : On affecte officiellement la commande à CE livreur
            $commandeLivreur->update([
                'statut' => 'AFFECTEE', // Ou 'ACCEPTEE' selon le terme que vous utilisez
                'livreur_id' => $livreur->id,
                // 'date_acceptation' => now(), // (Optionnel si vous avez cette colonne)
            ]);
            
            $commande->update([
                'statut' => 'AFFECTEE', // Pour que le client sache qu'un livreur a été trouvé
                'livreur_id' => $livreur->id,
            ]);

            // 5. Historique
            StatutCommande::create([
                'statut' => 'AFFECTEE',
                'commande_client_id' => $commande->id,
                'type' => 'LIVREUR',
                'typeId' => $livreur->id,
            ]);

            return ['success' => true, 'message' => 'Commande acceptée avec succès !'];
        });

        // 6. Retours propres
        if ($request->wantsJson() || $request->ajax()) {
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
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Commande introuvable.'], 404);
        }
        return redirect()->back()->with('error', 'Commande introuvable.');
        
    } catch (\Exception $e) {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Erreur système lors de l\'acceptation.'], 500);
        }
        return redirect()->back()->with('error', 'Une erreur inattendue est survenue.');
    }
}



    
 public function demarre_livraison(Request $request, int $id)
{
    $livreur = Auth::guard('livreur')->user();

    try {
        $resultat = DB::transaction(function () use ($id, $livreur) {
            
            // 1. Verrouillage & Sécurité : On force 'livreur_id' pour être sûr 
            // qu'un livreur ne démarre pas la commande de quelqu'un d'autre !
            $commandeLivreur = CommandeLivreur::where('commande_client_id', $id)
                ->where('livreur_id', $livreur->id)
                ->lockForUpdate()
                ->firstOrFail();

            // 2. ANTI DOUBLE-CLIC : Si le livreur a déjà cliqué
            if ($commandeLivreur->statut === 'RECUPEREE') {
                return [
                    'success' => false, 
                    'message' => 'Vous avez déjà récupéré cette commande.'
                ];
            }

            // (Optionnel) Vérifier que la commande a le bon statut pour être démarrée
            /* 
            if (!in_array($commandeLivreur->statut, ['AFFECTEE', 'ACCEPTEE'])) {
                return ['success' => false, 'message' => 'Le statut de cette commande ne permet pas de la démarrer.'];
            }
            */

            // 3. Verrouillage de la commande client
            $commande = CommandeClient::where('id', $commandeLivreur->commande_client_id)
                ->lockForUpdate()
                ->firstOrFail();

            // 4. Mise à jour de la table CommandeLivreur
            $commandeLivreur->update([
                'statut' => 'RECUPEREE',
                'date_depart' => now(), // Ici, "date_depart" est parfaitement logique (il quitte le fournisseur)
            ]);
            
            // 5. Mise à jour de la table CommandeClient
            $commande->update([
                'statut' => 'RECUPEREE',
                'commande_recuperee' => 1,
            ]);

            // 6. Enregistrement de l'historique
            StatutCommande::create([
                'statut' => 'RECUPEREE',
                'commande_client_id' => $commande->id,
                'type' => 'LIVREUR',
                'typeId' => $livreur->id, // Plus rapide que de rappeler Auth()
            ]);

            return ['success' => true, 'message' => 'Commande récupérée avec succès. En route !'];
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
        // La commande n'existe pas ou n'appartient pas à ce livreur
        if ($request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Commande introuvable ou accès refusé.'], 404);
        }
        return redirect()->back()->with('error', 'Commande introuvable.');
        
    } catch (\Exception $e) {
        // En cas de gros plantage de la base de données
        if ($request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Erreur système lors de la récupération.'], 500);
        }
        return redirect()->back()->with('error', 'Une erreur inattendue est survenue.');
    }
}

 
       public function en_route_livraison(Request $request, int $id)
    {
    $livreur = Auth::guard('livreur')->user();

    try {
        $resultat = DB::transaction(function () use ($id, $livreur) {
            
            // 1. Verrouillage de la commande du livreur
            $commandeLivreur = CommandeLivreur::where('commande_client_id', $id)
                ->where('livreur_id', $livreur->id)
                ->lockForUpdate()
                ->firstOrFail();
           

            // 2. ANTI DOUBLE-CLIC : Si le livreur a déjà cliqué sur ce bouton
            if ($commandeLivreur->statut === 'EN_ROUTE') {
                return [
                    'success' => false, 
                    'message' => 'Cette commande est déjà en route.'
                ];
            }

            // Vérification logique (optionnelle mais recommandée)
            /*
            if ($commandeLivreur->statut !== 'RECUPEREE') {
                return ['success' => false, 'message' => 'Vous devez d\'abord récupérer la commande.'];
            }
            */

            // 3. Verrouillage de la commande client
            $commande = CommandeClient::where('id', $id)
                ->lockForUpdate()
                ->firstOrFail();

            // 4. Mise à jour de la table CommandeLivreur
            $commandeLivreur->update([
                'statut' => 'EN_ROUTE',
                'date_recuperation' => now(), // Date exacte de la prise en charge
            ]);
            
            // 5. Mise à jour de la table CommandeClient
            $commande->update([
                'statut' => 'EN_ROUTE',
                'commande_en_route' => 1,
            ]);

            // 6. Enregistrement de l'historique
            StatutCommande::create([
                'statut' => 'EN_ROUTE',
                'commande_client_id' => $id,
                'type' => 'LIVREUR',
                'typeId' => $livreur->id, // Optimisé
            ]);

            // Correction du message de retour
            return ['success' => true, 'message' => 'Commande en route vers le client !'];
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
            return response()->json(['success' => false, 'message' => 'Commande introuvable ou accès refusé.'], 404);
        }
        return redirect()->back()->with('error', 'Commande introuvable.');
        
    } catch (\Exception $e) {
        if ($request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Erreur système lors de la mise en route.'], 500);
        }
        return redirect()->back()->with('error', 'Une erreur inattendue est survenue.');
    }
}


     public function terminer_livraison(Request $request, int $id)
{
    // 1. Récupération de l'utilisateur (une seule fois)
    $livreur = Auth::guard('livreur')->user();

    try {
        $resultat = DB::transaction(function () use ($id, $livreur) {
            
            // 2. Verrouillage de la commande du livreur
            $commandeLivreur = CommandeLivreur::where('commande_client_id', $id)
                ->where('livreur_id', $livreur->id)
                ->lockForUpdate()
                ->firstOrFail();

            // 3. VÉRIFICATION CRITIQUE : Empêcher le double-clic / double-paiement
            if ($commandeLivreur->statut === 'LIVREE') {
                return [
                    'success' => false, 
                    'message' => 'Cette commande a déjà été marquée comme livrée.'
                ];
            }
            
            // Assurez-vous qu'il ne peut livrer qu'une commande "EN_ROUTE" ou "RECUPEREE"
            if (!in_array($commandeLivreur->statut, ['EN_ROUTE', 'RECUPEREE'])) {
                return [
                    'success' => false, 
                    'message' => 'Statut invalide pour terminer cette livraison.'
                ];
            }

            // 4. Verrouillage de la commande globale
            $commande = CommandeClient::where('id', $commandeLivreur->commande_client_id)
                ->lockForUpdate()
                ->firstOrFail();

            // 5. Mises à jour des statuts
            $commandeLivreur->update([
                'statut' => 'LIVREE',
                'date_arrivee' => now(), 
            ]);

            $commande->update([
                'statut' => 'LIVREE',
                'commande_livree' => 1,
                // 'livreur_id' => $livreur->id, // Seulement si pas déjà renseigné avant
            ]);

            // 6. Historique du statut
            StatutCommande::create([
                'statut' => 'LIVREE',
                'commande_client_id' => $id,
                'type' => 'LIVREUR',
                'typeId' => $livreur->id, // Plus performant que de rappeler Auth()
            ]);

            // 7. Calcul et Crédit du portefeuille (LivreurSolde)
            $montant_livraison = $commande->detailCommandeClients()
                ->where('type', 'SERVICE') 
                ->sum('montant');

            LivreurSolde::create([
                'statut' => 'EN_ATTENTE', // Correction de la virgule en flèche (=>)
                'commande_livreur_id' => $commandeLivreur->id,
                'montant' => $montant_livraison,
                'livreur_id' => $livreur->id,
            ]);
            
            return ['success' => true, 'message' => 'Commande Livrée avec succès.'];
        });

        // 8. Retourner la réponse proprement
        if ($request->wantsJson()) {
            // Si erreur logique (ex: déjà livrée)
            if (!$resultat['success']) {
                return response()->json($resultat, 400); // 400 Bad Request
            }
            return response()->json($resultat);
        }

        // Si ce n'est pas de l'AJAX
        if (!$resultat['success']) {
            return redirect()->back()->with('error', $resultat['message']);
        }
        return redirect()->back()->with('success', $resultat['message']);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        // Gère proprement le cas où firstOrFail() ne trouve rien
        if ($request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Commande introuvable ou accès refusé.'], 404);
        }
        return redirect()->back()->with('error', 'Commande introuvable.');
        
    } catch (\Exception $e) {
        // Sécurité supplémentaire en cas de crash (base de données déconnectée, etc.)
        if ($request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Erreur système lors de la validation.'], 500);
        }
        return redirect()->back()->with('error', 'Une erreur inattendue est survenue.');
    }
}

    

protected function getCommandesDisponibles(): array
{
    $livreur = Auth::guard('livreur')->user();
    
    // 1. Si le livreur n'a pas encore de GPS actif, on ne retourne rien
    if (!$livreur || !$livreur->latitude || !$livreur->longitude) {
        return []; 
    }

    $livreurLat = $livreur->latitude;
    $livreurLng = $livreur->longitude;
    $rayonKm = 15; // Vous pouvez ajuster ce rayon (ex: 10 ou 20 km)

    // 2. Formule de Haversine basée sur la table "fournisseurs" (Point de collecte)
    $distanceSql = "( 6371 * acos( cos( radians($livreurLat) ) * cos( radians( fournisseurs.latitude ) ) * cos( radians( fournisseurs.longitude ) - radians($livreurLng) ) + sin( radians($livreurLat) ) * sin( radians( fournisseurs.latitude ) ) ) )";

    // 3. Requête SQL
    $commandes = CommandeLivreur::select('commande_livreurs.*')
        ->selectRaw("{$distanceSql} AS distance_km")
        
        // Jointure avec la commande client
        ->join('commande_clients', 'commande_clients.id', '=', 'commande_livreurs.commande_client_id')
        
        // Jointure PRINCIPALE : on lie le fournisseur à la commande
        ->join('fournisseurs', 'fournisseurs.id', '=', 'commande_clients.fournisseur_id')
        
        // Jointure secondaire : juste pour récupérer le nom du quartier du client pour l'affichage
        ->leftJoin('quartiers', 'quartiers.id', '=', 'commande_clients.quartier_id')
        
        ->where('commande_livreurs.statut', "EN_ATTENTE")
        
        // On s'assure que le fournisseur a bien renseigné sa position GPS
        ->whereNotNull('fournisseurs.latitude')
        ->whereNotNull('fournisseurs.longitude')
        
        // On filtre et on trie par les fournisseurs les plus proches
        ->having('distance_km', '<=', $rayonKm)
        ->orderBy('distance_km', 'asc')
        ->with(['commandeClient', 'commandeClient.quartier', 'commandeClient.fournisseur'])
        ->get();

        //dd( $commandes);
    // 4. On formate les données pour la vue Blade (le Javascript)
    return $commandes->map(function ($cl) {
        return [
            'id' => $cl->id,
            'commande' => $cl->commandeClient,
            'reference' => $cl->commandeClient->reference,
            'fournisseur' => $cl->commandeClient->fournisseur->nom_fournisseur ?? 'Inconnu',
            'fournisseur_adresse' => $cl->commandeClient->fournisseur->adresse ?? 'Inconnu',
            'fournisseur_quartier' => $cl->commandeClient->fournisseur->quartier->nom_quartier ?? 'Inconnu',
            'quartier' => $cl->commandeClient->quartier->nom_quartier ?? 'Inconnu',
            // Distance entre le livreur et le point de retrait (Fournisseur)
            'distance_km' => round($cl->distance_km, 1),
            'total' => $cl->commandeClient->montant_ttc,
            'nb_articles' => $cl->commandeClient->detailCommandeClients->sum('quantite'),
            'client_telephone' => $cl->commandeClient->telephone_livraison,
            'creneau' => $cl->commandeClient->creneau,
            'created_at' => $cl->created_at->diffForHumans(),
        ];
    })->toArray();
}



        public function actualiser_position_gps(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        // On récupère le livreur connecté
        $livreur = Auth::guard('livreur')->user();
        
        // On met à jour sa position
        $livreur->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json(['success' => true]);
    }




}
