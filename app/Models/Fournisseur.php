<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable; 
use Illuminate\Support\Facades\Auth;


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
        'statut',
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
     public function statutFournisseur()
    {
        return $this->hasMany(StatutFournisseur::class);
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

       public function mise_a_jour_statut(array $data)
    {
        // 1. Déterminer l'état (1 si ACTIF, sinon 0)
        $etat = ($data['statut'] === 'ACTIF') ? 1 : 0;

        // 2. Mettre à jour la table 'livreurs'
        $this->update([
            'statut'      => $data['statut'],
            'etat'        => $etat,
            // 'motif_rejet' => $data['motif'] ?? null,
        ]);

        // 3. Créer l'historique dans 'statut_livreurs'
        \App\Models\StatutFournisseur::create([
            'fournisseur_id' => $this->id,
            'user_id'    => Auth::id(), // L'admin connecté
            'statut'     => $data['statut'],
            'motif'      => $data['motif'] ?? null,
            'montant'    => $data['montant'] ?? 0,
        ]);
    }
}
