<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class StatutCommande extends Model
{
    use LogsActivity;
    
    protected $fillable = [
        'statut',
        'commande_client_id',
        'type',
        'typeId',
    ];
    // public function user()
    // {
    //     return $this->belongsTo(\App\Models\User::class);
    // }

    public function commandeClient()
    {
        return $this->belongsTo(\App\Models\CommandeClient::class, 'commande_client_id');
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
