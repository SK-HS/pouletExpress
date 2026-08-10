<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GestionnaireSolde extends Model
{
      protected $fillable = [
        'commande_client_id',
        'montant',
        'statut',
        'disponible_le',
        'paye_le',
    ];

    public function commandeClient()
    {
        return $this->belongsTo(\App\Models\CommandeClient::class);
    }

   
}
