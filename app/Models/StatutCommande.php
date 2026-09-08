<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatutCommande extends Model
{
    protected $fillable = [
        'statut',
        'commande_client_id',
        'type',
        'typeId',
    ];
    // public function user()
    // {
    //     return $this->belongsTo(\App\Models\User::class);
    // }

    public function commandeClient()
    {
        return $this->belongsTo(\App\Models\CommandeClient::class, 'commande_client_id');
    }

   
}
