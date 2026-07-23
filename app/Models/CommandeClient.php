<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CommandeClient extends Model
{
     protected $fillable = [
        'reference',
        'client_id',
        'montant_brut',
        'remise',
        'montant_hors_taxe',
        'tva',
        'montant_ttc',
        'avance',
        'solde',
        'statut',
        'date_commande',
        'user_id',
        'livreur_id',
        'type_commande',
        'fournisseur_id'
    ];

        public function user()
    {
        return $this->belongsTo(User::class);
    }
        public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function detailCommandeClients()
    {
        return $this->hasMany(DetailCommandeClient::class);
    }
    public function statutCommande()
    {
        return $this->hasMany(StatutCommande::class);
    }
    public function versement()
    {
        return $this->hasMany(Versement::class);
    }
    public function commandeLivreur()
    {
        return $this->hasMany(commandeLivreur::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function livreur()
    {
        return $this->belongsTo(Livreur::class);
    }

    protected static function booted()
    {
                // a la creation
            static::created(function ($model) {


                //dd($model);
                $now = \Carbon\Carbon::now();
                $prefix = $now->format('ym');

                do {
                    $suffix = str_pad(random_int(1, 99999), 4, '0', STR_PAD_LEFT);
                    $numero = "C{$prefix}{$suffix}";
                } while (self::where('reference', $numero)->exists());

                $model->update(['reference' => $numero,'statut'=>'Commande créée']);

                $statut = StatutCommande::create([
                            'statut' => "Commande créée",
                            'commande_client_id'=>$model->id,
                            'user_id'=>Auth::id(),]);

                            if($model->type_commande == "Avec Livraison")
                                {

                            CommandeLivreur::create([
                                        'statut' => "Recherche d'un livreur",
                                        'commande_id'=>$model->id,
                                        'user_id'=>Auth::id(),]);
                                }


               

            });

            // static::deleting(function ($vente) {
            //         foreach ($vente->detailVentes as $detail) {
            //             $ligneStock = StockAgence::where('stock_entreprise_id', $detail->stock_entreprise_id)
            //                 ->where('agence_id', $detail->agence_id)
            //                 ->first();

            //             if ($ligneStock) {
            //                 $ligneStock->increment('stock', $detail->quantite);
            //             }
            //             }

            //         foreach ($vente->versements as $versements) {
            //         Caisse::where('id', $versements->caisse_id)->decrement('montant',$versements->montant);
            //             }

            //     // Supprimer les versements liés
            //     $vente->versements()->delete();
            //     $vente->detailVentes()->delete();
            // });
        }

}
