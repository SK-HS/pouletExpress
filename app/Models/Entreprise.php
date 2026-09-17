<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Entreprise extends Model
{
    use LogsActivity;
     protected $fillable = [
        'nom',
        'adresse',
        'telephone',
        'contact',
        'email',
        'site_web',
        'logo',
        'pied_page',
        'status',
        'user_id',
        'ville',
        'compte',
        'taux_commission_livreur',
        'taux_commission_fournisseur',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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
