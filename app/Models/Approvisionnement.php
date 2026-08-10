<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approvisionnement extends Model
{
      protected $fillable = [
        'produit_fournisseur_id',
        'fournisseur_id',
        'quantite_ajoutee',
        'quantite_avant',
        'quantite_apres',
        'motif',
    ];
    public function produitFournisseur()
    {
        return $this->belongsTo(\App\Models\ProduitFournisseur::class);
    }
    public function fournisseur()
    {
        return $this->belongsTo(\App\Models\Fournisseur::class);
    }
}
