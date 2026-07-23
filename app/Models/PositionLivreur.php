<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PositionLivreur extends Model
{
    protected $fillable = [
        'longitude',
        'latitude',
        'date_position',
        'livreur_id',
    ];

        public function livreur()
    {
        return $this->belongsTo(Livreur::class);
    }
}
