<?php

namespace App\Http\Controllers;

use App\Models\CommandeClient;
use App\Models\DetailCommandeClient;
use App\Models\Quartier;
use App\Models\Service;
use App\Models\StatutCommande;
use App\Models\Versement;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    protected CartService $cart;

    public function __construct(CartService $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Affiche la page panier complète
     */
    public function index()
    {
        $quartiers = Quartier::with('service')->get();
        return view('pages.panier', [
            'items' => $this->cart->getItemsWithDetails(),
            'total' => $this->cart->getTotal(),
             'quartiers'=>$quartiers,

        ]);
    }

    /**
     * Ajoute un produit au panier (appelé en AJAX depuis les boutons "add_shopping_cart")
     */
    public function add(Request $request)
    {
        $request->validate([
            'produit_id' => 'required|integer',
            'quantite' => 'nullable|integer|min:1',
        ]);

        $this->cart->add($request->input('produit_id'), $request->input('quantite', 1));

        return response()->json([
            'success' => true,
            'count' => $this->cart->count(),
        ]);
    }

    /**
     * Met à jour la quantité d'un produit dans le panier
     */
    public function update(Request $request)
    {
        $request->validate([
            'produit_id' => 'required|integer',
            'quantite' => 'required|integer|min:0',
        ]);

        $produitId = $request->integer('produit_id');
        $quantite = $request->float('quantite');
        $this->cart->update($produitId, $quantite);

        $items = collect($this->cart->getItemsWithDetails());
        $item = $items->firstWhere(fn ($i) => $i['produit']->id === $produitId);

        return response()->json([
            'success' => true,
            'count' => $this->cart->count(),
            'total' => $this->cart->getTotal(),
            'item' => $item ? [
            'produit_id' => $item['produit']->id,
            'quantite' => $item['quantite'],
            'sous_total' => $item['sous_total'],
            ] : null, // null = le produit a été retiré (quantité mise à 0)
        ]);
    }

    /**
     * Retire un produit du panier
     */
    public function remove(Request $request)
    {
        $request->validate([
            'produit_id' => 'required|integer',
        ]);

        $this->cart->remove($request->input('produit_id'));

        return response()->json([
            'success' => true,
            'count' => $this->cart->count(),
            'total' => $this->cart->getTotal(),
        ]);
    }

    /**
     * Vide le panier
     */
    public function clear()
    {
        $this->cart->clear();
        return redirect()->route('Panier-Produit');
    }

    
    public function finaliser_commande()
    {
       $items = $this->cart->getItemsWithDetails();

        if (empty($items)) {
            return redirect()->route('Panier-Produit')
                ->with('error', 'Votre panier est vide.');
        }

        $client = Auth::guard('client')->user();

       //dd($items);
        return view('pages.finaliser_commande1', [
                        'items' => $items,
                        'sousTotal' => $this->cart->getTotal(),
                        'quartiers' => Quartier::with(['commune.ville', 'service'])->get(),
                        'client' => $client,
                    ]);
    }

    // public function valider_commande(Request $request)
    // {
    //      $items = $this->cart->getItemsWithDetails();
    //        // dd($items);
    //     if (empty($items)) {
    //         return redirect()->route('Panier-Produit')
    //             ->with('error', 'Votre panier est vide.');
    //     }

    //     $validated = $request->validate([
    //         'longitude' => 'nullable|string|max:192',
    //         'latitude' => 'nullable|string|max:192',
    //         'telephone_livraison' => 'required|string|max:20',
    //         'quartier_id' => 'nullable|exists:quartiers,id',
    //         'creneau' => 'required|in:matin,apres_midi',
    //         'mode_paiement' => 'required|in:ESPECE,WAVE,ORANGE-MONEY,MTN-MONEY,MOOV-MONEY',
    //         'lieu_livraison' => 'nullable|string',
    //     ]);
    //        // dd($validated);
    //     $sousTotal = $this->cart->getTotal();

    //     $fraisLivraison = 0;
    //     $service = null;
    //     if (!empty($validated['quartier_id'])) {
    //         $quartier = Quartier::with('service')->find($validated['quartier_id']);
    //         $fraisLivraison = $quartier->service->prix ?? 0;
    //         $service = Service::where('quartier_id', $validated['quartier_id'])->first();
    //         //dd( $service );
    //     }

    //     $total = $sousTotal + $fraisLivraison;

    //     if($service){$type_commande = "Avec Livraison";}else{$type_commande = "Sans Livraison";}

    //     $commande = DB::transaction(function () use ($type_commande,$validated, $items, $sousTotal, $service, $total) {
    //         $commande = CommandeClient::create([
    //             'client_id' => Auth::guard('client')->id(),
    //             'quartier_id' => $validated['quartier_id'] ?? null,
    //             'creneau' => $validated['creneau'],
    //             'longitude' => $validated['longitude'] ?? null,
    //             'latitude' => $validated['latitude'] ?? null,
    //             'telephone_livraison' => $validated['telephone_livraison'],
    //             'lieu_livraison' => $validated['lieu_livraison'] ?? null,
    //             'montant_brut' => $sousTotal,
    //             'montant_hors_taxe' => $total,
    //             'montant_ttc' => $total,
    //             'avance' => $total,
    //             'solde' => $total,
    //             'type_commande' => $type_commande,
    //             'date_commande' => date('Y-m-d H:i:s'),
    //             'statut' => 'EN ATTENTE',
    //         ]);

    //         foreach ($items as $item) {
    //             DetailCommandeClient::create([
    //                 'commande_client_id' => $commande->id,
    //                 'produit_fournisseur_id' => $item['produit']->id,
    //                 'quantite' => $item['quantite'],
    //                 'prix_unitaire' => $item['produit']->prix,
    //                 'montant' => $item['sous_total'],
    //                 'type'   =>"PRODUIT",
    //             ]);
    //             }
                
    //             if($service){
    //                  DetailCommandeClient::create([
    //                 'commande_client_id' => $commande->id,
    //                 'service_id' => $service->id,
    //                 'quantite' => 1,
    //                 'prix_unitaire' => $service->prix,
    //                 'montant' => $service->prix,
    //                 'type'   =>"SERVICE",
    //             ]);
    //             }

    //             Versement::create([
    //            'commande_client_id' => $commande->id,
    //            'mode_paiement' => $validated['mode_paiement'],
    //            'date_paiement' => date('Y-m-d H:i:s'),
    //            'montant' => $total
    //        ]);

    //        $statut = StatutCommande::create([
    //                         'statut' => "Commande créée",
    //                         'commande_client_id'=>$commande->id,
    //                         'type'=>"CLIENT",
    //                         'typeId'=>Auth::guard('client')->id(),]);

    //         return $commande;
    //     });



    // //     // Le panier est vidé une fois la commande enregistrée
    //     $this->cart->clear();

    // return redirect()->route('Confirmation-Commande', $commande->reference);
    // // return view('pages.validation_commande');
    // }

    public function valider_commande(Request $request)
{
    $items = $this->cart->getItemsWithDetails();
    
    if (empty($items)) {
        return redirect()->route('Panier-Produit')
            ->with('error', 'Votre panier est vide.');
    }

    $validated = $request->validate([
        'longitude' => 'nullable|string|max:192',
        'latitude' => 'nullable|string|max:192',
        'telephone_livraison' => 'required|string|max:20',
        'quartier_id' => 'nullable|exists:quartiers,id',
        'creneau' => 'required|in:matin,apres_midi',
        'mode_paiement' => 'required|in:ESPECE,WAVE,ORANGE-MONEY,MTN-MONEY,MOOV-MONEY',
        'lieu_livraison' => 'nullable|string',
    ]);

    // Récupération du service de livraison
    $fraisLivraison = 0;
    $service = null;
    if (!empty($validated['quartier_id'])) {
        $quartier = Quartier::with('service')->find($validated['quartier_id']);
        $fraisLivraison = $quartier->service->prix ?? 0;
        $service = Service::where('quartier_id', $validated['quartier_id'])->first();
    }

    $type_commande = $service ? "Avec Livraison" : "Sans Livraison";
    
    // Identifiant unique pour regrouper visuellement les commandes de cette session (Optionnel mais utile)
    // $sessionGroupId = time() . '-' . Auth::guard('client')->id();
    $sessionGroupId = 'GRP-' . date('Ymd-Hi') . '-' . Auth::guard('client')->id();

    // Utilisation de la transaction pour garantir que soit TOUTES les commandes passent, soit AUCUNE.
    $commandes = DB::transaction(function () use ($type_commande, $validated, $items, $fraisLivraison, $service, $sessionGroupId) {
        
        $commandesCreees = [];
        
        // 1. Grouper les articles par fournisseur
        // Assurez-vous que $item['produit'] a bien une propriété fournisseur_id
        $itemsParFournisseur = collect($items)->groupBy(function ($item) {
            return $item['produit']->fournisseur_id; 
        });

        $isFirstOrder = true; // Permet de savoir où imputer les frais de livraison

        // 2. Boucler et créer une commande par fournisseur
        foreach ($itemsParFournisseur as $fournisseur_id => $articlesFournisseur) {
            
            // Calcul du sous-total pour les articles de CE fournisseur
            $sousTotalFournisseur = $articlesFournisseur->sum('sous_total');
            
            // On ajoute les frais de livraison uniquement sur la première commande pour ne pas les facturer en double au client
            // $fraisAppliques = $isFirstOrder ? $fraisLivraison : 0;
             $fraisAppliques = $fraisLivraison;
            $totalFournisseur = $sousTotalFournisseur + $fraisAppliques;

            // Création de la commande spécifique
            $commande = CommandeClient::create([
                'client_id' => Auth::guard('client')->id(),
                'fournisseur_id' => $fournisseur_id, // TRÈS IMPORTANT : Ajoutez cette colonne dans votre table CommandeClient !
                'quartier_id' => $validated['quartier_id'] ?? null,
                'creneau' => $validated['creneau'],
                'longitude' => $validated['longitude'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'telephone_livraison' => $validated['telephone_livraison'],
                'lieu_livraison' => $validated['lieu_livraison'] ?? null,
                
                'montant_brut' => $sousTotalFournisseur,
                'montant_hors_taxe' => $totalFournisseur,
                'montant_ttc' => $totalFournisseur,
                // 'avance' => $totalFournisseur,
                // 'solde' => $totalFournisseur,
                
                'type_commande' => $type_commande,
                'date_commande' => date('Y-m-d H:i:s'),
                'statut' => 'NOUVEAU',
                'groupe_commande_id' => $sessionGroupId       ///pour savoir que ces commandes ont été payées ensemble
            ]);

            $commandesCreees[] = $commande;

            // Enregistrement des produits pour cette commande
            foreach ($articlesFournisseur as $item) {
                DetailCommandeClient::create([
                    'commande_client_id' => $commande->id,
                    'produit_fournisseur_id' => $item['produit']->id,
                    'quantite' => $item['quantite'],
                    'prix_unitaire' => $item['produit']->prix,
                    'montant' => $item['sous_total'],
                    'type' => "PRODUIT",
                ]);
            }

            // Enregistrement du service (livraison) SEULEMENT sur la première commande
            // if ($service && $isFirstOrder) {
            if ($service) {
                DetailCommandeClient::create([
                    'commande_client_id' => $commande->id,
                    'service_id' => $service->id,
                    'quantite' => 1,
                    'prix_unitaire' => $service->prix,
                    'montant' => $service->prix,
                    'type' => "SERVICE",
                ]);
            }

            // Enregistrement du versement (paiement partagé par commande)
            Versement::create([
                'commande_client_id' => $commande->id,
                'mode_paiement' => $validated['mode_paiement'],
                'date_paiement' => date('Y-m-d H:i:s'),
                'montant' => $totalFournisseur
            ]);

            // Enregistrement du statut initial
            StatutCommande::create([
                'statut' => "NOUVEAU",
                'commande_client_id' => $commande->id,
                'type' => "CLIENT",
                'typeId' => Auth::guard('client')->id(),
            ]);

            //$isFirstOrder = false; // Les prochaines commandes n'auront plus les frais de livraison
        }

        // On vide le panier une fois que tout est enregistré avec succès
        $this->cart->clear();

        return  $sessionGroupId; // Retourne un tableau des commandes créées
    });

    return redirect()->route('Confirmation-Commande', $sessionGroupId);
}


     public function confirmation_commande(string $reference)
    {
        $commandes = CommandeClient::with('detailCommandeClients')
              ->where('groupe_commande_id', $reference)
              ->where('client_id', Auth::guard('client')->id())
              ->get();

         $montantTotalClient = $commandes->sum('montant_ttc');

        return view('pages.validation_commande', [
            'commandes' => $commandes,
            'montantTotalClient' => $montantTotalClient,
        ]);
    }
}