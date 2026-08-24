<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduitFournisseur extends Model
{
     protected $fillable = [
        'quantite',
        'prix',
        'fournisseur_id',
        'produit_id',
        'statuts',
        'description',
        'user_id',
        'images',
        'categorie_id',
        'taille_id',
        'commande_min',
        'etat',
        'temps_preparation',
        'nom_produit',
    ];
    protected $casts = [
        'images' => 'array',
    ];
        public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function fournisseur()
    {
        return $this->belongsTo(\App\Models\Fournisseur::class);
    }
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
        public function categorie()
    {
        return $this->belongsTo(\App\Models\Categorie::class);
    }
    public function taille()
    {
        return $this->belongsTo(\App\Models\Taille::class);
    }

        public function campagnes()
    {
        return $this->belongsToMany(CampagnePromotion::class, 'campagne_produits');
    }


    
    // =================================================================
    // 1. Cherche la meilleure promo immédiate (Sans seuil)
    // =================================================================
    public function getPromoImmediate()
    {
        return \App\Models\CampagnePromotion::where('fournisseur_id', $this->fournisseur_id)
            ->where('est_active', true)
            ->where('supprimer', 0) // <-- Oubli corrigé ici
            ->where('date_debut', '<=', now())
            ->where('date_fin', '>=', now())
            ->whereNull('seuil_quantite') // UNIQUEMENT SANS SEUIL
            ->where(function ($query) {
                $query->doesntHave('produits')->orWhereHas('produits', function ($q) {
                    $q->where('produit_fournisseur_id', $this->id);
                });
            })
            ->orderByDesc('taux_remise')->first();
    }

    // =================================================================
    // 2. Cherche la meilleure promo grossiste (Avec seuil)
    // =================================================================
    public function getPromoVolume()
    {
        return \App\Models\CampagnePromotion::where('fournisseur_id', $this->fournisseur_id)
            ->where('est_active', true)
            ->where('supprimer', 0) // <-- Oubli corrigé ici
            ->where('date_debut', '<=', now())
            ->where('date_fin', '>=', now())
            ->whereNotNull('seuil_quantite') // UNIQUEMENT AVEC SEUIL
            ->where(function ($query) {
                $query->doesntHave('produits')->orWhereHas('produits', function ($q) {
                    $q->where('produit_fournisseur_id', $this->id);
                });
            })
            ->orderByDesc('taux_remise')->first();
    }

    // =================================================================
    // 3. Calcule le prix final en CUMULANT les promos
    // =================================================================
    public function calculerPrixFinal(int $quantite_commandee)
    {
        $prix_de_base = $this->prix;
        
        // On récupère les deux promos possibles
        $promo_immediate = $this->getPromoImmediate();
        $promo_volume = $this->getPromoVolume();

        $pourcentage_reduction_total = 0;

        // A. On additionne la promo immédiate (si elle existe)
        if ($promo_immediate) {
            $pourcentage_reduction_total += $promo_immediate->taux_remise;
        }

        // B. On additionne la promo volume UNIQUEMENT si la quantité dans le panier est suffisante
        if ($promo_volume && $quantite_commandee >= $promo_volume->seuil_quantite) {
            $pourcentage_reduction_total += $promo_volume->taux_remise;
        }

        // C. On calcule le prix final (S'il y a des réductions)
        if ($pourcentage_reduction_total > 0) {
            
            // Sécurité anti-ruine : On empêche le total de dépasser 100% de réduction
            if ($pourcentage_reduction_total > 100) {
                $pourcentage_reduction_total = 100;
            }
            
            $montant_reduction = $prix_de_base * ($pourcentage_reduction_total / 100);
            return $prix_de_base - $montant_reduction;
        }

        // Aucune promo applicable, on renvoie le prix normal
        return $prix_de_base;
    }




}
