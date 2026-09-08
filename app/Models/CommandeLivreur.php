<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CommandeLivreur extends Model
{
    protected $fillable = [
        'distance',
        'statut',
        'date_affectation',
        'date_depart',
        'date_arrivee',
        'commande_client_id',
        'livreur_id',
        'user_id',
        'quartier_id',
    ];

      public function quartier()
    {
        return $this->belongsTo(Quartier::class, 'quartier_id');
    }
        public function user()
    {
        return $this->belongsTo(User::class);
    }
        public function livreur()
    {
        return $this->belongsTo(Livreur::class);
    }
        public function commandeClient()
    {
        return $this->belongsTo(CommandeClient::class, 'commande_client_id');
    }

       protected static function booted()
    {
         static::created(function ($model) {

         if($model->commande_client_id && $model->livreur_id)
            {

          $commdclient = CommandeClient::lockForUpdate()->findOrFail($model->commande_client_id);

                    $commdclient->statut = "NOUVEAU";
                    $commdclient->livreur_id = $model->livreur_id;

                    $commdclient->save();

                    StatutCommande::create([
                            'statut' => "NOUVEAU",
                            'commande_client_id'=>$model->commande_client_id,
                            'user_id'=>Auth::id(),]);
            }

         });
        //  static::saving(function ($model) {

        //  if($model->commande_client_id && $model->livreur_id)
        //     {

        //   $commdclient = CommandeClient::lockForUpdate()->findOrFail($model->commande_client_id);

        //             $commdclient->statut = "AFFECTEE";
        //             $commdclient->livreur_id = $model->livreur_id;

        //             $commdclient->save();

        //             // StatutCommande::create([
        //             //         'statut' => "AFFECTEE",
        //             //         'commande_client_id'=>$model->commande_client_id,
        //             //         'user_id'=>Auth::id(),]);
        //             StatutCommande::create([
        //                     'statut' => 'AFFECTEE',
        //                     'commande_client_id' => $model->commande_client_id,
        //                     'type' => 'LIVREUR',
        //                     'typeId' => $model->livreur_id,
        //                 ]);
        //     }

        //  });

    }
}
