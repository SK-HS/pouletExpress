<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Support\Facades\DB;

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

}
