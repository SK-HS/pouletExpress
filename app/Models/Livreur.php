<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class Livreur extends Authenticatable
{
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
        'disponible',
        'password',
        'remember_token',
        'latitude',
        'longitude',
        'etat',
        'statut',
        'categorie',
    ];
    
       protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'disponible' => 'boolean',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

      public function quartier()
    {
        return $this->belongsTo(Quartier::class);
    }

     public function commandesLivrees()
    {
        return $this->hasMany(CommandeLivreur::class, 'livreur_id');
    }
     public function statutLivreurs()
    {
        return $this->hasMany(StatutLivreur::class, 'livreur_id');
    }

    public function demandesRetraits()
{
    return $this->morphMany(DemandeRetrait::class, 'beneficiaire');
}
    public function gestionnaireSolde()
{
    return $this->morphMany(GestionnaireSolde::class, 'debiteur');
}


    protected static function booted()
    {

        static::creating(function ($model) {
            // Récupérer le prochain ID
            $nextId = (static::max('id') ?? 0) + 1;

            // Générer le code avec padding
            $model->reference = "L".str_pad($nextId, 4, '0', STR_PAD_LEFT);
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
        StatutLivreur::create([
            'livreur_id' => $this->id,
            'user_id'    => Auth::id(), // L'admin connecté
            'statut'     => $data['statut'],
            'motif'      => $data['motif'] ?? null,
            'montant'    => $data['montant'] ?? 0,
                                ]);

        if($data['montant'] > 0)
            {
        GestionnaireSolde::create([
                    'debiteur_type' => Livreur::class,
                    'debiteur_id'   => $this->id,
                    'statut' => 'EN_ATTENTE', 
                    // 'commande_client_id' => $commandeLivreur->commande_client_id,
                    'montant' => $data['montant'] ?? 0,
                    'disponible_le' => now()->addHours(24),
                    'details' => "Statut : {$data['statut']} \n Motif : {$data['motif']}",

                ]);
            }

    }

}
