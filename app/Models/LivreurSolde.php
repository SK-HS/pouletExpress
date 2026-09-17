<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class LivreurSolde extends Model
{
    use LogsActivity;

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

    


public function valider_montant_livraison(array $data = [])
{
    //dd($this->id);
    DB::transaction(function () {

        $soldeLivreur = LivreurSolde::where('id', $this->id)
                                    ->lockForUpdate()
                                    ->first();

        if (!$soldeLivreur) {
            throw new Exception("Aucun solde trouvé pour cette commande.");
        }

        
        $livreur = Livreur::where('id', $soldeLivreur->livreur_id)
                                      ->lockForUpdate()
                                      ->first();

        //Logique de paiement
        if ($soldeLivreur->statut == "EN_ATTENTE") {
            $soldeLivreur->update([
                'statut' => 'PAYE',    
                'disponible_le' => now(),
                'paye_le' => now(),
            ]);
        } elseif ($soldeLivreur->statut == "DISPONIBLE") {
            $soldeLivreur->update([
                'statut' => 'PAYE', 
                'paye_le' => now(),   
            ]);
        } elseif ($soldeLivreur->statut == "PAYE") {
            // Lève une exception que Filament va attraper pour afficher une notification rouge
            throw new Exception("Cette commande a déjà été payée au livreur.");
        }

        // On verse l'argent sur le compte statique du livreur
        $livreur->increment('compte', $soldeLivreur->montant);
        
    }); 
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
