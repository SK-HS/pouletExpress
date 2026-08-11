<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable; 


class Fournisseur extends Authenticatable
{
      use HasFactory, Notifiable; // Laissez vos traits actuels s'il y en a

    protected $casts = [
    'type_produit' => 'array',
     'password' => 'hashed',
     'disponible' => 'boolean',
];
        
    protected $fillable = [
        'reference',
        'nom',
        'type',
        'telephone',
        'contact',
        'email',
        'adresse',
        'quartier_id',
        'user_id',
        'image',
        'compte',
        'type_produit',
        'nom_ferme',
        'nom_gerant',
        'capacite_ferme',
        'etat',
        'description',
        'image_ferme',
         'disponible',
        'password',
        'remember_token',
        'longitude',
        'latitude',
        'certification_sanitaire',
        'piece_fournisseur',
    ];

      protected $hidden = [
        'password',
        'remember_token',
    ];

  
    public function user()
    {
        return $this->belongsTo(User::class);
    }
     public function quartier()
    {
        return $this->belongsTo(Quartier::class);
    }
     public function produits()
    {
        return $this->hasMany(ProduitFournisseur::class);
    }

    protected static function booted()
    {

        static::creating(function ($model) {
            // Récupérer le prochain ID
            $nextId = (static::max('id') ?? 0) + 1;

            // Générer le code avec padding
            $model->reference = "F".str_pad($nextId, 4, '0', STR_PAD_LEFT);
        });


    }
}
