<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
class Approvisionnement extends Model
{
    use LogsActivity;
      protected $fillable = [
        'produit_fournisseur_id',
        'fournisseur_id',
        'quantite_ajoutee',
        'quantite_avant',
        'quantite_apres',
        'motif',
    ];
    public function produitFournisseur()
    {
        return $this->belongsTo(\App\Models\ProduitFournisseur::class);
    }
    public function fournisseur()
    {
        return $this->belongsTo(\App\Models\Fournisseur::class);
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
