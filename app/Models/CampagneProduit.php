<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CampagneProduit extends Model
{
    use LogsActivity;

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

       
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Enregistre tous les champs modifiés
            ->logOnlyDirty() // N'enregistre QUE ce qui a réellement changé
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => "Commande client {$eventName}");
    }
}
