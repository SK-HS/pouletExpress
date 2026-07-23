<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Livreur extends Model
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
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

      public function quartier()
    {
        return $this->belongsTo(Quartier::class);
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
}
