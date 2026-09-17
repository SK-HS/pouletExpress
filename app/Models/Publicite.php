<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Publicite extends Model
{
      use HasFactory;use LogsActivity;
    protected $fillable = [
        'titre',
        'description',
        'image',
        'badge',
        'lien',
        'bouton_texte',
        'est_actif',
        'date_debut',
        'date_fin',
    ];
    // On s'assure que les dates sont bien traitées comme des objets Carbon (pratique pour les comparaisons)
    protected $casts = [
        'est_actif' => 'boolean',
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];
public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Enregistre tous les champs modifiés
            ->logOnlyDirty() // N'enregistre QUE ce qui a réellement changé
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => "Commande client {$eventName}");
    }


}
