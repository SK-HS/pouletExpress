<?php

namespace App\Services;

use App\Models\ProduitFournisseur;

class CartService
{
    protected string $sessionKey = 'panier';

    /**
     * Récupère le contenu brut du panier depuis la session
     * Format : [produit_id => quantite]
     */
    public function getRawItems(): array
    {
        return session()->get($this->sessionKey, []);
    }

    /**
     * Ajoute un produit au panier (ou incrémente sa quantité)
     */
    public function add(int $produitId, int $quantite = 1): void
    {
        $panier = $this->getRawItems();

        if (isset($panier[$produitId])) {
            $panier[$produitId] += $quantite;
        } else {
            $panier[$produitId] = $quantite;
        }

        session()->put($this->sessionKey, $panier);
    }

    /**
     * Met à jour la quantité exacte d'un produit
     */
    public function update(int $produitId, int $quantite): void
    {
        $panier = $this->getRawItems();

        if ($quantite <= 0) {
            unset($panier[$produitId]);
        } else {
            $panier[$produitId] = $quantite;
        }

        session()->put($this->sessionKey, $panier);
    }

    /**
     * Retire un produit du panier
     */
    public function remove(int $produitId): void
    {
        $panier = $this->getRawItems();
        unset($panier[$produitId]);
        session()->put($this->sessionKey, $panier);
    }

    /**
     * Vide complètement le panier
     */
    public function clear(): void
    {
        session()->forget($this->sessionKey);
    }

    /**
     * Retourne le nombre total d'articles (somme des quantités)
     */
    public function count(): int
    {
        // return array_sum($this->getRawItems());
          return count($this->getRawItems());
    }

    /**
     * Retourne les produits du panier avec leurs infos complètes (nom, prix, image...)
     * et le sous-total calculé pour chacun
     */
    public function getItemsWithDetails(): array
    {
        $panier = $this->getRawItems();

        if (empty($panier)) {
            return [];
        }

        $produits = ProduitFournisseur::with(['produit', 'fournisseur', 'taille'])
            ->whereIn('id', array_keys($panier))
            ->get();

        $items = [];
        foreach ($produits as $produit) {
            // $quantite = $panier[$produit->id];
            // $items[] = [
            //     'produit' => $produit,
            //     'quantite' => $quantite,
            //     'sous_total' => $produit->prix * $quantite,
            // ];

            $quantite = $panier[$produit->id];
            // 1. On récupère le prix normal
            $prix_initial = $produit->prix;
            
            // 2. On calcule le prix final en fonction de la quantité !
            $prix_final = $produit->calculerPrixFinal($quantite);

            // On récupère la promo volume pour voir si le client pourrait en profiter
                $promo_volume = $produit->getPromoVolume();
                $manquant_promo_volume = null;
                $taux_promo_volume = null;
                
                if ($promo_volume && $quantite < $promo_volume->seuil_quantite) {
                    $manquant_promo_volume = $promo_volume->seuil_quantite - $quantite;
                    $taux_promo_volume = $promo_volume->taux_remise;
                }
        
            $items[] = [
                'produit' => $produit,
                'quantite' => $quantite,
                
                // On garde une trace des prix pour l'affichage visuel (barré / rouge)
                'prix_unitaire_initial' => $prix_initial,
                'prix_unitaire_final'   => $prix_final,
                'en_promo'              => $prix_final < $prix_initial,
                'economie_unitaire'     => $prix_initial - $prix_final,
                
                // 3. Le VRAI sous-total calculé avec le prix remisé
                'sous_total' => $prix_final * $quantite,

                // Les infos pour afficher un message d'encouragement
                'quantite_manquante_promo' => $manquant_promo_volume,
                'taux_promo_volume' => $taux_promo_volume,
            ];
        }

        return $items;
    }

    /**
     * Calcule le total général du panier
     */
    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->getItemsWithDetails() as $item) {
            $total += $item['sous_total'];
        }
        return $total;
    }
}
