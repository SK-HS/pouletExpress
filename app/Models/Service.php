<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Service extends Model
{
    use LogsActivity;

    protected $fillable = [
        'designation',
        'prix',
        'Detail',
        'user_id',
        'quartier_id',
    ];

   
       public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    } 
       public function quartier()
    {
        return $this->belongsTo(\App\Models\Quartier::class);
    } 
       public function entreprise()
    {
        return $this->belongsTo(\App\Models\Entreprise::class);
    } 
     

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->user_id = Auth::id();
        });
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
