<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class FournisseurSolde extends Model
{
    use LogsActivity;
    
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


    public function valider_montant_commande(array $data = [])
{
    //dd($this->id);
    DB::transaction(function () {

        $soldeFournisseur = FournisseurSolde::where('id', $this->id)
                                    ->lockForUpdate()
                                    ->first();

        if (!$soldeFournisseur) {
            throw new Exception("Aucun solde trouvé pour cette commande.");
        }

        
        $fournisseur = Fournisseur::where('id', $soldeFournisseur->fournisseur_id)
                                      ->lockForUpdate()
                                      ->first();

        //Logique de paiement
        if ($soldeFournisseur->statut == "EN_ATTENTE") {
            $soldeFournisseur->update([
                'statut' => 'PAYE',    
                'disponible_le' => now(),
                'paye_le' => now(),
            ]);
        } elseif ($soldeFournisseur->statut == "DISPONIBLE") {
            $soldeFournisseur->update([
                'statut' => 'PAYE', 
                'paye_le' => now(),   
            ]);
        } elseif ($soldeFournisseur->statut == "PAYE") {
            // Lève une exception que Filament va attraper pour afficher une notification rouge
            throw new Exception("Cette commande a déjà été payée au fournisseur.");
        }

        // On verse l'argent sur le compte statique du fournisseur
        $fournisseur->increment('compte', $soldeFournisseur->montant);
        
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
