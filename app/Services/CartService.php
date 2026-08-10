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
        return array_sum($this->getRawItems());
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
            $quantite = $panier[$produit->id];
            $items[] = [
                'produit' => $produit,
                'quantite' => $quantite,
                'sous_total' => $produit->prix * $quantite,
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
