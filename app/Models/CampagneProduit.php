<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampagneProduit extends Model
{
       protected $fillable = [
        'campagne_promotion_id',
        'produit_fournisseur_id',
        'fournisseur_id',
    ];
    public function fournisseur()
    {
        return $this->belongsTo(\App\Models\Fournisseur::class);
    }
    public function produitFournisseur()
    {
        return $this->belongsTo(ProduitFournisseur::class);
    }
    public function campagnePromotion()
    {
        return $this->belongsTo(CampagnePromotion::class);
    }
}
