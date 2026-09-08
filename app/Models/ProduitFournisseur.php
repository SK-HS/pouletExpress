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



protected static array $campagnesCacheParFournisseur = [];
 
/**
 * Récupère (et met en cache pour la durée de la requête) toutes les campagnes
 * actives du fournisseur de ce produit
 */
protected function getCampagnesActivesDuFournisseur()
{
    if (!$this->fournisseur_id) {
        return collect();
    }
 
    // Si déjà chargées pour ce fournisseur durant cette requête, on réutilise
    if (isset(static::$campagnesCacheParFournisseur[$this->fournisseur_id])) {
        return static::$campagnesCacheParFournisseur[$this->fournisseur_id];
    }
 
    $campagnes = CampagnePromotion::where('fournisseur_id', $this->fournisseur_id)
        ->where('est_active', true)
        ->where('supprimer', 0)
        ->where('date_debut', '<=', now())
        ->where('date_fin', '>=', now())
        ->where('taux_remise', '>', 0) // ignore toute campagne mal configurée (remise négative/nulle)
        ->with('produits:id') // évite un N+1 supplémentaire sur la relation
        ->get();
 
    static::$campagnesCacheParFournisseur[$this->fournisseur_id] = $campagnes;
 
    return $campagnes;
}
 

/**
 * Vérifie si une campagne s'applique à ce produit précis
 * (campagne globale sans produits listés, OU ce produit est explicitement listé)
 */
protected function campagneApplicableAuProduit($campagne): bool
{
    if ($campagne->produits->isEmpty()) {
        return true; // campagne globale, s'applique à tous les produits du fournisseur
    }
 
    return $campagne->produits->contains('produit_fournisseur_id', $this->id);
}
 
/**
 * 1. Meilleure promo immédiate (sans seuil de quantité)
 * Filtre désormais en mémoire (sur la collection déjà chargée), pas en SQL
 */
public function getPromoImmediate()
{
    return $this->getCampagnesActivesDuFournisseur()
        ->whereNull('seuil_quantite')
        ->filter(fn ($c) => $this->campagneApplicableAuProduit($c))
        ->sortByDesc('taux_remise')
        ->first();
}
 
/**
 * 2. Meilleure promo grossiste (avec seuil de quantité)
 */
public function getPromoVolume()
{
    return $this->getCampagnesActivesDuFournisseur()
        ->whereNotNull('seuil_quantite')
        ->filter(fn ($c) => $this->campagneApplicableAuProduit($c))
        ->sortByDesc('taux_remise')
        ->first();
}
 
/**
 * 3. Calcule le prix final en cumulant les promos applicables
 */
public function calculerPrixFinal(int $quantite_commandee)
{
    $prix_de_base = (float) $this->prix;
 
    if ($prix_de_base <= 0) {
        return 0; // sécurité : jamais de prix négatif à partir d'une base invalide
    }
 
    $promo_immediate = $this->getPromoImmediate();
    $promo_volume = $this->getPromoVolume();
 
    $pourcentage_reduction_total = 0;
 
    if ($promo_immediate) {
        $pourcentage_reduction_total += max(0, (float) $promo_immediate->taux_remise);
    }
 
    if ($promo_volume && $quantite_commandee >= $promo_volume->seuil_quantite) {
        $pourcentage_reduction_total += max(0, (float) $promo_volume->taux_remise);
    }
 
    if ($pourcentage_reduction_total > 0) {
        // Sécurité anti-ruine : jamais plus de 100% de réduction
        $pourcentage_reduction_total = min(100, $pourcentage_reduction_total);
 
        $montant_reduction = $prix_de_base * ($pourcentage_reduction_total / 100);
 
        // Arrondi — évite les prix avec décimales bizarres (FCFA n'a pas de centimes)
        return round($prix_de_base - $montant_reduction);
    }
 
    return round($prix_de_base);
}
    
   




}
