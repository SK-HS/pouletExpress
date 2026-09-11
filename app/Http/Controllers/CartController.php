<?php

namespace App\Http\Controllers;

use App\Models\CommandeClient;
use App\Models\CommandeLivreur;
use App\Models\DetailCommandeClient;
use App\Models\ProduitFournisseur;
use App\Models\Quartier;
use App\Models\Service;
use App\Models\StatutCommande;
use App\Models\Versement;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        $validated = $request->validate([
            'produit_id' => 'required|integer',
            'quantite' => 'required|integer|min:1',
        ]);

        $this->cart->add($validated['produit_id'], $validated['quantite']?? 1);

        return response()->json([
            'success' => true,
            'count' => $this->cart->count(),
        ]);
    }

    /**
     * Met à jour la quantité d'un produit dans le panier
     */
    // public function update(Request $request)
    // {
    //     $request->validate([
    //         'produit_id' => 'required|integer',
    //         'quantite' => 'required|integer|min:0',
    //     ]);

    //     $produitId = $request->integer('produit_id');
    //     $quantite = $request->float('quantite');


    //     $produit = ProduitFournisseur::find($produitId);
    //     $commandeMin = $produit->commande_min ?? 1;

    //     if ($quantite > 0 && $quantite < $commandeMin) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => "La quantité minimum pour ce produit est de {$commandeMin}.",
    //         ], 422);
    //     }

      
    //     $this->cart->update($produitId, $quantite);

    //     $items = collect($this->cart->getItemsWithDetails());
    //     $item = $items->firstWhere(fn ($i) => $i['produit']->id === $produitId);

    //     return response()->json([
    //         'success' => true,
    //         'count' => $this->cart->count(),
    //         'total' => $this->cart->getTotal(),
    //         'item' => $item ? [
    //         'produit_id' => $item['produit']->id,
    //         'quantite' => $item['quantite'],
    //         'sous_total' => $item['sous_total'],
    //         ] : null, // null = le produit a été retiré (quantité mise à 0)
    //     ]);
    // }

    public function update(Request $request)
{
    $validated = $request->validate([
        'produit_id' => 'required|integer|exists:produit_fournisseurs,id',
        //Plafond serveur — jamais confiance dans le max HTML seul (contournable en désactivant JS)
        'quantite'   => 'required|integer|min:1',
    ]);
 
    $produitId =  $validated['produit_id'];
    $quantiteDemandee =  $validated['quantite'];
 
    //Vérifie que la quantité demandée ne dépasse pas le stock réel disponible
    if ($quantiteDemandee > 0) {
        $produit = ProduitFournisseur::find($produitId);
         $commandeMin = $produit->commande_min ?? 1;

        if ($produit && $quantiteDemandee > $produit->quantite) {
            return response()->json([
                'success' => false,
                'message' => "Seulement {$produit->quantite} unité(s) disponible(s).",
            ], 422);
        }

        if ($quantiteDemandee < $commandeMin) {
            return response()->json([
                'success' => false,
                'message' => "La quantité minimum pour ce produit est de {$commandeMin}.",
            ], 422);
        }

    }
 
    $this->cart->update($produitId, $quantiteDemandee);
 
    $items = collect($this->cart->getItemsWithDetails());
    // $item = $items->firstWhere(fn ($i) => $i['produit']->id === $produitId);
     $item = $items->firstWhere(fn ($i) => $i['produit']->id == $produitId);
 
    return response()->json([
        'success' => true,
        'count'   => $this->cart->count(),
        'total'   => $this->cart->getTotal(),
        'item'    => $item ? [
            'produit_id' => $item['produit']->id,
            'quantite'   => $item['quantite'],
            'sous_total' => $item['sous_total'],

            'prix_unitaire_initial'    => $item['prix_unitaire_initial'],
            'prix_unitaire_final'      => $item['prix_unitaire_final'],
            'en_promo'                 => $item['en_promo'],
            'economie_unitaire'        => $item['economie_unitaire'],
            'quantite_manquante_promo' => $item['quantite_manquante_promo'],
            'taux_promo_volume'        => $item['taux_promo_volume'],
        ] : null,
    ]);
}

    /**
     * Retire un produit du panier
     */
    public function remove(Request $request)
    {
       $validated= $request->validate([
            'produit_id' => 'required|integer',
        ]);

        $this->cart->remove($validated['produit_id']);

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
       $formToken = Str::uuid()->toString();
            session(['commande_form_token' => $formToken]);

        return view('pages.finaliser_commande1', [
                        'items' => $items,
                        'sousTotal' => $this->cart->getTotal(),
                        'quartiers' => Quartier::with(['commune.ville', 'service'])->get(),
                        'client' => $client,
                        'formToken'  => $formToken,
                    ]);
    }

 
    public function valider_commande(Request $request)
{
    $client = Auth::guard('client')->user();
 
    $validated = $request->validate([
        // Jeton anti double-soumission
        'form_token' => 'required|string',
 
        'longitude'           => 'nullable|numeric|between:-180,180',
        'latitude'            => 'nullable|numeric|between:-90,90',
        'telephone_livraison' => ['required', 'string', 'max:20', 'regex:/^[0-9+\s\-]{8,20}$/'],
        'quartier_id'         => 'nullable|integer|exists:quartiers,id',
        'creneau'             => 'required|in:matin,apres_midi',
        'mode_paiement'       => 'required|in:ESPECE,WAVE,ORANGE-MONEY,MTN-MONEY,MOOV-MONEY',
        'lieu_livraison'      => 'nullable|string|max:500',
        'ville'               => 'required|string|max:255',
        'commune_quartier'    => 'required|string|max:255',
    ]);
 
   
    $tokenAttendu = session('commande_form_token');
 
    if (!$tokenAttendu || $tokenAttendu !== $validated['form_token']) {
        return redirect()->route('panier.index')
            ->with('error', 'Cette commande a déjà été traitée ou la session a expiré. Vérifiez votre historique de commandes avant de recommencer.');
    }
 
    // Invalide immédiatement le jeton — toute soumission suivante avec le même jeton échouera
    session()->forget('commande_form_token');
 
    $items = $this->cart->getItemsWithDetails();
 
    if (empty($items)) {
        return redirect()->route('panier.index')->with('error', 'Votre panier est vide.');
    }
 
    $itemsParFournisseur = collect($items)->groupBy(fn ($item) => $item['produit']->fournisseur_id);
    $nombreFournisseurs = $itemsParFournisseur->count();
 
    // Frais de livraison de base × nombre de colis (cohérent avec l'affichage JS)
    $fraisLivraisonBase = 0;
    $service = null;
    if (!empty($validated['quartier_id'])) {
        $quartier = Quartier::with('service')->find($validated['quartier_id']);
        $fraisLivraisonBase = $quartier->service->prix ?? 0;
        $service = $quartier->service ?? null;
    }
 
      $type_commande = $service ? 'Avec Livraison' : 'Sans Livraison';
      $sessionGroupId = 'GRP-' . date('Ymd-Hi') . '-' . Auth::guard('client')->id();
 
    try {
        $commandesCreees = DB::transaction(function () use (
            $validated, $items, $itemsParFournisseur, $fraisLivraisonBase, $service, $type_commande, $client, $sessionGroupId 
        ) {
            $commandesCreees = [];
 
            foreach ($itemsParFournisseur as $fournisseur_id => $articlesFournisseur) {
 
                // Verrouillage + revérification du stock au moment exact de la commande
                // (empêche la survente si le stock a changé entre l'ajout au panier et le paiement)
                // foreach ($articlesFournisseur as $item) {
                //     $produit = ProduitFournisseur::where('id', $item['produit']->id)
                //         ->lockForUpdate()
                //         ->first();
 
                //     if (!$produit || $produit->quantite < $item['quantite']) {
                //         throw new \RuntimeException(
                //             "Stock insuffisant pour \"{$item['produit']->produit?->nom}\" "
                //             . "(disponible : " . ($produit->quantite ?? 0) . ", demandé : {$item['quantite']})."
                //         );
                //     }
 
                //     // Décrémente le stock immédiatement, dans la même transaction verrouillée
                //     $produit->decrement('quantite', $item['quantite']);
                // }
 
                $sousTotalFournisseur = $articlesFournisseur->sum('sous_total');
                $fraisAppliques = $fraisLivraisonBase; // appliqué par fournisseur (1 livraison par colis)
                $totalFournisseur = $sousTotalFournisseur + $fraisAppliques;
 
                $commande = CommandeClient::create([
                    'client_id'           => $client->id,
                    'fournisseur_id'      => $fournisseur_id,
                    'quartier_id'         => $validated['quartier_id'] ?? null,
                    'creneau'             => $validated['creneau'],
                    'longitude'           => $validated['longitude'] ?? null,
                    'latitude'            => $validated['latitude'] ?? null,
                    'telephone_livraison' => $validated['telephone_livraison'],
                    'lieu_livraison'      => $validated['lieu_livraison'] ?? null,
                    'montant_brut'        => $sousTotalFournisseur,
                    'montant_hors_taxe'   => $totalFournisseur,
                    'montant_ttc'         => $totalFournisseur,
                    'type_commande'       => $type_commande,
                    'date_commande'       => now(),
                    'statut'              => 'NOUVEAU',
                    'groupe_commande_id' => $sessionGroupId 
                ]);
 
                $commandesCreees[] = $commande;
 
                foreach ($articlesFournisseur as $item) {
                    DetailCommandeClient::create([
                        'commande_client_id'    => $commande->id,
                        'produit_fournisseur_id'=> $item['produit']->id,
                        'quantite'              => $item['quantite'],
                        'prix_unitaire'         => $item['prix_unitaire_final'],
                        'montant'               => $item['sous_total'],
                        'type'                  => 'PRODUIT',
                    ]);
                }
 
                if ($service) {
                    DetailCommandeClient::create([
                        'commande_client_id' => $commande->id,
                        'service_id'         => $service->id,
                        'quantite'           => 1,
                        'prix_unitaire'      => $service->prix,
                        'montant'            => $service->prix,
                        'type'               => 'SERVICE',
                    ]);
                }
 
                Versement::create([
                    'commande_client_id' => $commande->id,
                    'mode_paiement'      => $validated['mode_paiement'],
                    'date_paiement'      => now(),
                    'montant'            => $totalFournisseur,
                ]);
 
                StatutCommande::create([
                    'statut'              => 'NOUVEAU',
                    'commande_client_id'  => $commande->id,
                    'type'                => 'CLIENT',
                    'typeId'              => $client->id,
                ]);
 
                // Création automatique de la fiche livreur pour CE colis
                // CommandeLivreur::create([
                //     'commande_client_id' => $commande->id,
                //     'quartier_id'        => $commande->quartier_id,
                //     'statut'             => 'EN_ATTENTE',
                // ]);

        $commande->fournisseur->notify(new \App\Notifications\NouvelleCommandeFournisseurNotification($commande));
        
            }
 
            return $sessionGroupId;
        });
    } catch (\RuntimeException $e) {
        // Stock insuffisant détecté pendant la transaction → tout est annulé automatiquement (rollback)
        return redirect()->route('panier.index')->with('error', $e->getMessage());
    }
 
    $this->cart->clear();
 
    // if (count($commandesCreees) === 1) {
    //     return redirect()->route('Confirmation-Commande', $commandesCreees[0]->reference);
    // }
 
    // $references = collect($commandesCreees)->pluck('reference')->implode(',');
    // return redirect()->route('Confirmation-Commande', ['references' => $sessionGroupId]);
    

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