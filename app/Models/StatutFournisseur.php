<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatutFournisseur extends Model
{
    protected $fillable = [
        'statut',
        'motif',
        'montant',
        'user_id',
        'fournisseur_id',
    ];

        public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }
        public function user()
    {
        return $this->belongsTo(User::class);
    }
}
