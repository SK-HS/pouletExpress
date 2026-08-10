<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FournisseurSolde extends Model
{
      protected $fillable = [
        'commande_client_id',
        'fournisseur_id',
        'montant',
        'statut',
        'disponible_le',
        'paye_le',
    ];

    public function commandeClient()
    {
        return $this->belongsTo(\App\Models\CommandeClient::class);
    }

    public function fournisseur()
    {
        return $this->belongsTo(\App\Models\Fournisseur::class);
    }
}
