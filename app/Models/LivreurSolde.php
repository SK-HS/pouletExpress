<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LivreurSolde extends Model
{
     protected $fillable = [
        'commande_livreur_id',
        'livreur_id',
        'montant',
        'statut',
        'disponible_le',
        'paye_le',
    ];

    public function commandeLivreur()
    {
        return $this->belongsTo(\App\Models\CommandeLivreur::class);
    }

    public function livreur()
    {
        return $this->belongsTo(\App\Models\Livreur::class);
    }

    // Dans un Job ou Command exécuté chaque jour
// LivreurSolde::where('statut', 'EN_ATTENTE')
//     ->where('created_at', '<=', now()->subHours(48))
//     ->update(['statut' => 'DISPONIBLE', 'disponible_le' => now()]);



}
