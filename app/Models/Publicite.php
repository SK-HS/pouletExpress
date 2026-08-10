<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publicite extends Model
{
      use HasFactory;
    protected $fillable = [
        'titre',
        'description',
        'image',
        'badge',
        'lien',
        'bouton_texte',
        'est_actif',
        'date_debut',
        'date_fin',
    ];
    // On s'assure que les dates sont bien traitées comme des objets Carbon (pratique pour les comparaisons)
    protected $casts = [
        'est_actif' => 'boolean',
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];
}
