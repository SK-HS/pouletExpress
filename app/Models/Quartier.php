<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Quartier extends Model
{
    use LogsActivity;

      protected $fillable = [
        'nom_quartier',
        'commune_id',
        'user_id',
        'latitude',
        'longitude',
    ];

        public function user()
    {
        return $this->belongsTo(User::class);
    }
        public function commune()
    {
        return $this->belongsTo(Commune::class);
    }
       public function service()
    {
        return $this->hasOne(Service::class, 'quartier_id');
        // ou hasMany si plusieurs services possibles par quartier
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
