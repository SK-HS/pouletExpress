<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Taille extends Model
{
    use LogsActivity;
        protected $fillable = [
        'taille',
        'description',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    public function produit()
    {
        return $this->HasMany(Produit::class);
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
