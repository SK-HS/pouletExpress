<?php

namespace App\Models;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Versement extends Model
{
    protected $fillable = [
        'reference',
        'commande_client_id',
        'montant',
        'mode_paiement',
        'date_paiement',
        'detail',
        'user_id',
    ];

        public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function commandeClient()
    {
        return $this->belongsTo(\App\Models\CommandeClient::class, 'commande_client_id');
    }

         protected static function booted()
        {
            static::created(function ($model) {
                
                $now = \Carbon\Carbon::now();
                $prefix = $now->format('ym');

                    $countItem = Versement::count() + 1;
                    $suffix = str_pad($countItem , 5, '0', STR_PAD_LEFT); // ex: 001

                    $numero = "VS$prefix{$suffix}"; // ex: 2510001
                    $model->reference =  $numero;

                });

            static::creating(function ($model) {

                try {
                    if ($model->montant <= 0) {
                //throw new \DomainException("Le montant du versement doit être supérieur à 0.");
                Notification::make()
                        ->title('Montant Inferieur')
                        ->body("Le montant du versement doit être supérieur à 0.")
                        ->danger()
                        ->persistent()
                        ->send();
                    throw ValidationException::withMessages([
                            'montant' => "Le montant du versement doit être supérieur à 0.",
                        ]);
            }

                DB::transaction(function () use ($model) {
                    // $caisse = Caisse::lockForUpdate()->find($model->caisse_id);
                    
                //    dd($model->versementfrm);
                   
                
                    $vente = CommandeClient::lockForUpdate()->findOrFail($model->commande_client_id);

                    $vente->avance += $model->montant;
                    $vente->solde = $model->montant - $vente->avance;

                    // $vente->statut = $vente->solde <= 0
                    //     ? 'Paiement validé'
                    //     : 'Pas Soldée';

                    $vente->save();

                    $statut = StatutCommande::create([
                                'statut' => $vente->statut,
                                'commande_client_id'=>$vente->id,
                                'user_id'=>Auth::id(),]);
                    

                    // if(! $caisse) {
                    //     //throw new \DomainException("Caisse introuvable pour l'ID {$model->caisse_id}");
                    // Notification::make()
                    //     ->title('Caisse introuvable')
                    //     ->body("Caisse introuvable")
                    //     ->danger()
                    //     ->persistent()
                    //     ->send();
                    // throw ValidationException::withMessages([
                    //         'quantite' => "Caisse introuvable",
                    //     ]);
                    // }

                    // $caisse->increment('montant_caisse', $model->montant);
                });
                 } catch (ValidationException $e) {
                    throw $e; // relance pour que Filament l’affiche
                }
                        });

        static::updating(function ($versement) {
            
             try {
                 DB::transaction(function () use ($versement) {

                $ancienMontant = $versement->getOriginal('montant') ?? 0;
                $nouveauMontant = $versement->montant ?? 0;
                    //dd($versement->espace_versement);
                $delta = floatval($nouveauMontant) - floatval($ancienMontant);
                if ($delta != 0) {
                    // Caisse::where('id', $versement->caisse_id)->increment('montant_caisse', $delta);

              
                    $cmmdclient = CommandeClient::lockForUpdate()->findOrFail($versement->commande_client_id);

                    $cmmdclient->solde -= $delta;
                    $cmmdclient->avance += $delta;

                    $cmmdclient->statut = $cmmdclient->solde <= 0
                        ? 'Paiement validé'
                        : 'Pas Soldée';

                    $cmmdclient->save();

                    $statut = StatutCommande::create([
                                'statut' => $cmmdclient->statut,
                                'commande_client_id'=>$cmmdclient->id,
                                'user_id'=>Auth::id(),]);
                    }
                        });
                     } catch (ValidationException $e) {
                    throw $e; // relance pour que Filament l’affiche
                }
                
               
            });

                    
    static::deleting(function ($versement) {

         try {
        DB::transaction(function () use ($versement) {

            $montant = $versement->montant;


                $commdClient = CommandeClient::lockForUpdate()->find($versement->commande_client_id);

                if ($commdClient) {

                    $commdClient->avance = max(0, $commdClient->avance - $montant);

                    $commdClient->solde += $montant;

                    $commdClient->statut = $commdClient->solde <= 0
                        ? 'Paiement validé'
                        : 'Pas Soldée';

                    $commdClient->save();

                            StatutCommande::create([
                                'statut' => "Versement N°".$versement->reference."supprimé",
                                'commande_client_id'=>$commdClient->id,
                                'user_id'=>Auth::id(),]);
                }
            

            // $caisse = Caisse::lockForUpdate()->find($versement->caisse_id);

            // if ($caisse) {
            //     $caisse->decrement('montant_caisse', $montant);
            // }
        });
         } catch (ValidationException $e) {
                    throw $e; // relance pour que Filament l’affiche
                }
    });

        }
}
