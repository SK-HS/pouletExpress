<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
        'fournisseur_id',
        'creneau',
        'telephone_livraison',
        'quartier_id',
        'latitude',
        'longitude',
        'commande_EN_ROUTE',
        'commande_livree',
        'lieu_livraison',
        'commande_en_route',
        'date_commande',
        'commande_recu',
        'date_commande_recu',
        'cmmd_livre_fournisseur',
        'date_cmmd_livre_fournisseur',
        'groupe_commande_id',
    ];

        public function user()
    {
        return $this->belongsTo(User::class);
    }
        public function quartier()
    {
        return $this->belongsTo(Quartier::class, 'quartier_id');
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
    // public function commandeLivreur()
    // {
    //     return $this->hasMany(commandeLivreur::class);
    // }
     public function livraison()
    {
        return $this->hasOne(CommandeLivreur::class, 'commande_client_id');
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

                $model->update(['reference' => $numero,'statut'=>'NOUVEAU']);

                // $statut = StatutCommande::create([
                //             'statut' => "Commande créée",
                //             'commande_client_id'=>$model->id,
                //             'type'=>$model->type,
                //             'typeId'=>Auth::id(),]);

                            if($model->type_commande == "Avec Livraison")
                                {

                            CommandeLivreur::create([
                                        'statut' => "EN_ATTENTE",
                                        'commande_client_id'=>$model->id,
                                        'quartier_id'=>$model->quartier_id,
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

    public function affecter_commande_livreur(array $data)
    {
        $livreur_id = $data['livreur_id'];
            
        // Pas besoin de try/catch ici, l'erreur remontera automatiquement à Filament !
        DB::transaction(function () use ($livreur_id) {
            
            // 1. Verrouillage : On cherche la commande sans préciser 'livreur_id' 
            $commandeLivreur =CommandeLivreur::where('commande_client_id', $this->id)
                                            ->lockForUpdate()
                                            ->first();

            // Sécurité : au cas où l'entrée CommandeLivreur n'existe pas du tout
            if (!$commandeLivreur) {
                throw new Exception("Erreur : La logistique de cette commande n'a pas encore été initialisée.");
            }

            // 2. ANTI-COLLISION CRITIQUE : Vérifie si un autre l'a déjà prise
            if ($commandeLivreur->statut !== 'EN_ATTENTE') {
               throw new Exception("Impossible ! Cette commande a déjà été acceptée ou est en cours.");
            }

            // 3. Mise à jour de la course du livreur
            $commandeLivreur->update([
                'statut'           => 'AFFECTEE', 
                'livreur_id'       => $livreur_id,
                'date_affectation' => now(), 
            ]);
            
            // 4. Mise à jour de la commande principale
            $this->update([
                'statut'     => 'AFFECTEE', 
                'livreur_id' => $livreur_id,
            ]);

            // 5. On crée l'historique d'action
            StatutCommande::create([
                'statut'             => 'AFFECTEE',
                'commande_client_id' => $this->id,
                'type'               => 'ADMINISTRATEUR',
                'typeId'             => Auth::id(),
            ]);
            
        }); 
    }


    public function marquer_demarre_livraison(array $data)
    {
         DB::transaction(function () {
            
            
            $commandeLivreur =CommandeLivreur::where('commande_client_id', $this->id)
                                            ->lockForUpdate()
                                            ->first();
           
            if (!$commandeLivreur) {
                throw new Exception("Erreur : La logistique de cette commande n'a pas encore été initialisée.");
            }
        
            if ($commandeLivreur->statut !== 'AFFECTEE') {
               throw new Exception("Impossible ! Cette commande a déjà été récupérée par le livreur, ou bien elle n'a pas encore été affectée.");
            }
            // Mise à jour de la course
            $commandeLivreur->update([
                'statut' => 'RECUPEREE',
                'date_recuperation' => now(), 
            ]);
            
            //Mise à jour de la commande
            $this->update([
                'statut' => 'RECUPEREE',
                'commande_recuperee' => 1,
            ]);
            //On trace l'historique
            \App\Models\StatutCommande::create([
                'statut'             => 'RECUPEREE',
                'commande_client_id' => $this->id, 
                'type'               => 'ADMINISTRATEUR',
                'typeId'             => Auth::id(),
            ]);
            
        }); 
    }

    public function marquer_livraison_en_route(array $data)
    {
         DB::transaction(function () {
            
            
            $commandeLivreur =CommandeLivreur::where('commande_client_id', $this->id)
                                            ->lockForUpdate()
                                            ->first();
           
            if (!$commandeLivreur) {
                throw new Exception("Erreur : La logistique de cette commande n'a pas encore été initialisée.");
            }
        
            if ($commandeLivreur->statut !== 'RECUPEREE') {
               throw new Exception("Impossible ! Cette commande n'a pas encore été récupérée par le livreur, ou bien elle est déjà en route.");
            }
            // Mise à jour de la course
            $commandeLivreur->update([
                'statut' => 'EN_ROUTE',
                'date_depart' => now(), 
            ]);
            
            //Mise à jour de la commande
            $this->update([
                'statut' => 'EN_ROUTE',
                'commande_en_route' => 1,
            ]);
            //On trace l'historique
            StatutCommande::create([
                'statut'             => 'EN_ROUTE',
                'commande_client_id' => $this->id, 
                'type'               => 'ADMINISTRATEUR',
                'typeId'             => Auth::id(),
            ]);
            
        }); 
    }

    public function marquer_livraison_terminer(array $data)
    {
         DB::transaction(function () {
            
            
            $commandeLivreur =CommandeLivreur::where('commande_client_id', $this->id)
                                            ->lockForUpdate()
                                            ->first();
           
            if (!$commandeLivreur) {
                throw new Exception("Erreur : La logistique de cette commande n'a pas encore été initialisée.");
            }
        
            if ($commandeLivreur->statut !== 'EN_ROUTE') {
               throw new Exception("Impossible ! Cette commande n'a pas encore été récupérée par le livreur, ou le livreur n'est pas en route.");
            }
            // Mise à jour de la course
            $commandeLivreur->update([
                'statut' => 'LIVREE',
                'date_arrivee' => now(),  
            ]);
            
            //Mise à jour de la commande
            $this->update([
                'statut' => 'LIVREE',
                'commande_livree' => 1,
            ]);
            //On trace l'historique
            StatutCommande::create([
                'statut'             => 'LIVREE',
                'commande_client_id' => $this->id, 
                'type'               => 'ADMINISTRATEUR',
                'typeId'             => Auth::id(),
            ]);

            //    // 7. Calcul et Crédit du portefeuille (LivreurSolde)
            // $montant_livraison =  $this->detailCommandeClients()
            //                         ->where('type', 'SERVICE') 
            //                         ->sum('montant');
            // if($montant_livraison){
            // $tauxCommission = 0.05; // 5%, configurable
            // // $montant = $montant_livraison * (1 - $tauxCommission);
            // // $commission = $tauxCommission * $montant_livraison;
            // $commission = round($montant_livraison * $tauxCommission);
            // $montant = $montant_livraison - $commission;
            
            // LivreurSolde::create([
            //         'statut' => 'EN_ATTENTE', 
            //         'commande_livreur_id' => $commandeLivreur->id,
            //         'montant' => $montant,
            //         'livreur_id' =>  $this->livreur_id,
            //         'disponible_le' => now()->addHours(24),
            //     ]);

            // GestionnaireSolde::create([
            //         'debiteur_type' => Livreur::class,
            //         'debiteur_id'   =>  $this->livreur_id,
            //         'statut' => 'EN_ATTENTE', 
            //         'commande_client_id' => $this->id,
            //         'montant' => $commission,
            //         'disponible_le' => now()->addHours(24),
            //         'details' => 'Commission sur livraison commande (5%)',
            //     ]);
            // }
            
        }); 
    }
    public function marquer_commande_livree_fournisseur(array $data)
    {
         DB::transaction(function () {
            

            if ($this->cmmd_livre_fournisseur !=0) {
                throw new Exception("Erreur : Cette commande a déjà été marquée comme livrée par le fournisseur.");
            }
        
           
            
            //Mise à jour de la commande
            $this->update([
               'cmmd_livre_fournisseur' => 1,
               'date_cmmd_livre_fournisseur'=>now()
            ]);
            //On trace l'historique
            StatutCommande::create([
                'statut'             => 'LIVREE_PAR_FOURNISSEUR',
                'commande_client_id' => $this->id, 
                'type'               => 'ADMINISTRATEUR',
                'typeId'             => Auth::id(),
            ]);

        //     $tauxCommission = 0.10; 
        //     $commission = round($this->montant_brut * $tauxCommission);
        //     $montant = $this->montant_brut - $commission;

        // FournisseurSolde::create([
        //         'statut' => 'EN_ATTENTE', 
        //         'commande_client_id' => $this->id,
        //         'fournisseur_id' => $this->fournisseur_id,
        //         'montant' => $montant,
        //         'disponible_le' => now()->addHours(24),

        //     ]);

        // GestionnaireSolde::create([
        //             'debiteur_type' => Fournisseur::class,
        //             'debiteur_id'   => $this->fournisseur_id,
        //             'statut' => 'EN_ATTENTE', 
        //             'commande_client_id' => $this->id,
        //             'montant' => $commission,
        //             'disponible_le' => now()->addHours(24),
        //             'details' => 'Commission sur commande fournisseur (10%)',
        //         ]);

           
            
        }); 
    }

    public function marquer_commande_recu_client(array $data = [])
    {
        DB::transaction(function () {

            //Vérification du statut
            if (!in_array($this->statut, ['RECUPEREE', 'EN_ROUTE', 'LIVREE'])) {
                throw new Exception("Impossible : La commande doit être en route ou livrée avant d'être réceptionnée (statut actuel : {$this->statut}).");
            }

            if ($this->statut === 'RECEPTIONNEE' || $this->commande_recu == 1) {
                throw new Exception("Erreur : Cette commande a déjà été réceptionnée par le client.");
            }

            // Mise à jour de la commande principale
            $this->update([
                'statut'            => 'RECEPTIONNEE',
                'commande_recu'     => 1,
                'date_commande_recu' => now(),
            ]);

            //Historique
            StatutCommande::create([
                            'statut'             => 'RECEPTIONNEE',
                            'commande_client_id' => $this->id,
                            'type'               => 'ADMINISTRATEUR',
                            'typeId'             => Auth::id(),
                        ]);

            // ============================================================
            // COMMISSION LIVREUR (5% sur les frais de service)
            // ============================================================
            $commandeLivreur = CommandeLivreur::where('commande_client_id', $this->id)
                                            ->lockForUpdate()
                                            ->first();

            $montant_livraison = $this->detailCommandeClients()
                                    ->where('type', 'SERVICE')
                                    ->sum('montant');

            //On ne crédite que s'il y a un livreur ET un montant de service
            if ($commandeLivreur && $montant_livraison > 0) {
                $commission_livreur = round($montant_livraison * 0.05);
                $montant_net_livreur = $montant_livraison - $commission_livreur;

                LivreurSolde::create([
                        'statut'             => 'EN_ATTENTE',
                        'commande_livreur_id' => $commandeLivreur->id,
                        'montant'            => $montant_net_livreur,
                        'livreur_id'         => $this->livreur_id,
                        'disponible_le'      => now()->addHours(24),
                    ]);

                GestionnaireSolde::create([
                    'debiteur_type'      => Livreur::class,
                    'debiteur_id'        => $this->livreur_id,
                    'statut'             => 'EN_ATTENTE',
                    'commande_client_id' => $this->id,
                    'montant'            => $commission_livreur,
                    'disponible_le'      => now()->addHours(24),
                    'details'            => 'Commission sur livraison commande (5%)',
                ]);
            }

            // ============================================================
            // COMMISSION FOURNISSEUR (10% sur le montant brut produits)
            // ============================================================
        
            if ($this->montant_brut > 0) {
                $commission_fournisseur = round($this->montant_brut * 0.10);
                $montant_net_fournisseur = $this->montant_brut - $commission_fournisseur;

            FournisseurSolde::create([
                    'statut'            => 'EN_ATTENTE',
                    'commande_client_id' => $this->id,
                    'fournisseur_id'    => $this->fournisseur_id,
                    'montant'           => $montant_net_fournisseur,
                    'disponible_le'     => now()->addHours(24),
                ]);

            GestionnaireSolde::create([
                    'debiteur_type'      => Fournisseur::class,
                    'debiteur_id'        => $this->fournisseur_id,
                    'statut'             => 'EN_ATTENTE',
                    'commande_client_id' => $this->id,
                    'montant'            => $commission_fournisseur,
                    'disponible_le'      => now()->addHours(24),
                    'details'            => 'Commission sur commande fournisseur (10%)',
                ]);
            }

        }); 

        // Dans App\Console\Kernel.php ou un Job schedulé
        // CommandeClient::where('statut', 'LIVREE')
        //     ->where('commande_recu', 0)
        //     ->where('updated_at', '<=', now()->subHours(24))
        //     ->each(fn($commande) => $commande->marquer_commande_recu_client([]));

    }


            

    

}
