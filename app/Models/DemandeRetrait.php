<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Notifications\DemandeRetraitTraiteeNotification;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class DemandeRetrait extends Model
{
    use LogsActivity;
    
     protected $fillable = [
        'beneficiaire_type',
        'beneficiaire_id',
        'montant',
        'mode_paiement',
        'numero_paiement',
        'statut',
        'date_traitee',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
     public function beneficiaire()
    {
        return $this->morphTo();
    }


public function valider_demande_retrait(array $data = [])
{
    //dd($this->id);
    DB::transaction(function () {

        $demande = static::where('id', $this->id)
                            ->lockForUpdate()
                            ->first();
        // $demande = DemandeRetrait::where('id', $this->id)
        //                             ->lockForUpdate()
        //                             ->first();

        $beneficiaire = $demande->beneficiaire()->lockForUpdate()->first();
       //$demande->beneficiaire->notify(new DemandeRetraitTraiteeNotification($demande));
        if (!$demande) {
            throw new Exception("Cette demande n'existe pas.");
        }
       if ($demande->statut === 'TRAITEE') {
                throw new Exception("Erreur : Cette demande de :( {$beneficiaire->nom}) a déjà été payée.");
            }
            if ($demande->statut === 'REJETEE') {
                throw new Exception("Erreur : Cette demande de : ( {$beneficiaire->nom}) est rejetée et ne peut plus être validée.");
            }

          

            if (!$beneficiaire) {
                throw new Exception("Erreur : Le bénéficiaire de cette demande est introuvable.");
            }

             if ($beneficiaire->compte < $demande->montant) {

                throw new Exception("Fonds insuffisants ! {$beneficiaire->nom} n'a que {$beneficiaire->compte} FCFA sur son compte.");
            }

            $beneficiaire->decrement('compte', $demande->montant);
            //met à jour la demande comme étant "TRAITÉE"
            $demande->update([
                'statut' => 'TRAITEE',
                'date_traitee' => now(),
                'user_id' =>Auth::id(),
            ]);

            $demande->beneficiaire->notify(new \App\Notifications\DemandeRetraitTraiteeNotification($demande));
        
        
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
