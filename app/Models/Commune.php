<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Commune extends Model
{
    use LogsActivity;

      protected $fillable = [
        'nom_commune',
        'ville_id',
        'user_id',
    ];

        public function user()
    {
        return $this->belongsTo(User::class);
    }
        public function ville()
    {
        return $this->belongsTo(Ville::class);
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
