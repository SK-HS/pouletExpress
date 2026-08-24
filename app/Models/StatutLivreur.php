<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatutLivreur extends Model
{
     protected $fillable = [
        'statut',
        'motif',
        'montant',
        'user_id',
        'livreur_id',
    ];

        public function livreur()
    {
        return $this->belongsTo(Livreur::class);
    }
        public function user()
    {
        return $this->belongsTo(User::class);
    }
}
