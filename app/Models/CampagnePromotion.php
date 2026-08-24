<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampagnePromotion extends Model
{
      protected $fillable = [
        'fournisseur_id',
        'titre',
        'taux_remise',
        'seuil_quantite',
        'date_debut',
        'date_fin',
        'est_active',
        'type',
        'supprimer',
    ];

    public function fournisseur()
    {
        return $this->belongsTo(\App\Models\Fournisseur::class);
    }

       // Remplacez votre ancienne fonction par celle-ci :
    public function produits()
    {
        // belongsToMany = Relation Many-to-Many via la table pivot
        return $this->belongsToMany(ProduitFournisseur::class, 'campagne_produits');
    }


    public function campagneProduit()
    {
        return $this->hasMany(CampagneProduit::class);
    }
}
