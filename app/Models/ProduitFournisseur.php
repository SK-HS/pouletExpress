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

}
