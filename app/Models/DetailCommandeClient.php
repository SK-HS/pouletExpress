<?php

namespace App\Models;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DetailCommandeClient extends Model
{
    protected $fillable = [
        'commande_client_id',
        'produit_fournisseur_id',
        'service_id',
        'quantite',
        'type',
        'prix_unitaire',
        'montant',
    ];
    public function commandeClient()
    {
        return $this->belongsTo(\App\Models\CommandeClient::class, 'commande_client_id');
    }
    public function produitFournisseur()
    {
        return $this->belongsTo(\App\Models\ProduitFournisseur::class, 'produit_fournisseur_id');
    }
    public function service()
    {
        return $this->belongsTo(\App\Models\Service::class, 'service_id');
    }

        
    protected static function booted()
{


static::creating(function ($detail) {

    if (!$detail->commandeClient) {
        return;
    }

   
    // dd($detail);
    if($detail->type === "PRODUIT")
        {
    $produit = ProduitFournisseur::find($detail->produit_fournisseur_id);

    if (!$produit) {
         Notification::make()
                ->title('Produit introuvable')
                ->body("Produit introuvable")
                ->danger()
                ->persistent()
                ->send();
               throw ValidationException::withMessages([
                    'quantite' => "Produit introuvable",
                ]);
        // throw new \Exception("Produit introuvable.");
        //  throw new \Exception("Produit introuvable (ID: {$detail->produit_id})");
    }
      try {
    DB::transaction(function () use ($detail, $produit) {
      
            $stockproduit = ProduitFournisseur::where('id', $produit->id)
                ->lockForUpdate()
                ->first();
        //lockForUpdate() verrouiller les lignes sélectionnées pendant une transaction en base de données

            if (!$stockproduit) {
                Notification::make()
                    ->title('Stock introuvable')
                    ->body("Stock introuvable pour {$produit->produit?->nom}")
                    ->danger()
                    ->persistent()
                    ->send();
               throw ValidationException::withMessages([
                    'quantite' => "Stock introuvable pour {$produit->produit?->nom}",
                ]);
            }

            if ($stockproduit->quantite < $detail->quantite) {
                Notification::make()
                    ->title('Stock insuffisant')
                    ->body("Stock insuffisant pour {$produit->produit?->nom}")
                    ->danger()
                    ->persistent()
                    ->send();
                throw ValidationException::withMessages([
                        'quantite' => "Stock insuffisant pour {$produit->produit?->nom}",
                    ]);
            }

            $stockproduit->decrement('quantite', $detail->quantite);
        
        
        
        });
        } catch (ValidationException $e) {
            throw $e; // relance pour que Filament l’affiche
        }
        }
        
        });

    static::updating(function ($detail) {


          try {
         DB::transaction(function () use ( $detail) {
        
                   // dd($detail);
                    
            if($detail->type === "PRODUIT")
        {
            $produit = ProduitFournisseur::find($detail->produit_fournisseur_id);
           // dd($produit);
                if (!$produit) {
                    return;
                }
            $stockProduit = ProduitFournisseur::where('id', $detail->produit_fournisseur_id)
                            ->lockForUpdate()
                            ->first();

            // Calculer la différence entre l'ancienne et la nouvelle quantité
            $ancienneQuantite = $detail->getOriginal('quantite');
            $nouvelleQuantite = $detail->quantite;
           
            $diff = $nouvelleQuantite - $ancienneQuantite;

            if ($diff > 0) {
                // On augmente la quantité vérifier stock disponible
              
                $stockProduit->decrement('quantite', $diff);
            } elseif ($diff < 0) {
                // On réduit la quantité remettre du stock
                $stockProduit->increment('quantite', abs($diff));
            }

                }
                  
                  });

                   } catch (ValidationException $e) {
            throw $e; // relance pour que Filament l’affiche
        }

        });

    static::deleting(function ($detail) {

              try {
            DB::transaction(function () use ($detail) {

                   // dd($detail);
                    
            if($detail->type === "PRODUIT")
        {
            $produit = ProduitFournisseur::find($detail->produit_fournisseur_id);
           // dd($produit);
                if (!$produit) {
                    return;
                }


            $stockAgence = ProduitFournisseur::where('id', $detail->produit_fournisseur_id)
                            ->lockForUpdate()
                            ->first();

                // On réduit la quantité remettre du stock
                $stockAgence->increment('quantite', $detail->quantite);
            

                  }
                  
              
            });

             } catch (ValidationException $e) {
            throw $e; // relance pour que Filament l’affiche
        }
     });



        }
}
