<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CampagnePromotion extends Model
{
    use LogsActivity;
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

       
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Enregistre tous les champs modifiés
            ->logOnlyDirty() // N'enregistre QUE ce qui a réellement changé
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => "Commande client {$eventName}");
    }
}
