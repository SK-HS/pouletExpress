<?php

namespace App\Models;

use App\Models\Entreprise;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GestionnaireSolde extends Model
{
      protected $fillable = [
        'debiteur_type',
        'debiteur_id',
        'commande_client_id',
        'montant',
        'statut',
        'disponible_le',
        'paye_le',
        'details',
    ];

     public function debiteur()
    {
        return $this->morphTo();
    }

    public function commandeClient()
    {
        return $this->belongsTo(\App\Models\CommandeClient::class);
    }


        public function valider_commission_gestionnaire(array $data = [])
{
    //dd($this->id);
    DB::transaction(function () {

        $gestionnaireSolde = GestionnaireSolde::where('id', $this->id)
                                            ->lockForUpdate()
                                            ->first();

        if (!$gestionnaireSolde) {
            throw new Exception("Aucun solde trouvé pour cette commande.");
        }

        
        $etreprise = Entreprise::lockForUpdate()
                                    ->first();

        //Logique de paiement
        if ($gestionnaireSolde->statut == "EN_ATTENTE") {
            $gestionnaireSolde->update([
                'statut' => 'PAYE',    
                'disponible_le' => now(),
                'paye_le' => now(),
            ]);
        } elseif ($gestionnaireSolde->statut == "DISPONIBLE") {
            $gestionnaireSolde->update([
                'statut' => 'PAYE', 
                'paye_le' => now(),   
            ]);
        } elseif ($gestionnaireSolde->statut == "PAYE") {
            // Lève une exception que Filament va attraper pour afficher une notification rouge
            throw new Exception("Cette commission a déjà été payée au gestionnaire.");
        }

        // On verse l'argent sur le compte statique de l'entreprise
        $etreprise->increment('compte', $gestionnaireSolde->montant);
        
    }); 
}

   
}
