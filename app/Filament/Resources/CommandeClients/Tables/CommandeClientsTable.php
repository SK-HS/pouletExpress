<?php

namespace App\Filament\Resources\CommandeClients\Tables;

use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class CommandeClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('date_commande', 'desc')
            ->columns([
                TextColumn::make('reference')
                    ->searchable(),
                TextColumn::make('fournisseur.nom')
                    ->sortable(),
                TextColumn::make('client.nom')
                    ->sortable(),
                TextColumn::make('livreur.nom')
                    ->sortable(),
                TextColumn::make('montant_brut')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('remise')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('montant_hors_taxe')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tva')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('montant_ttc')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('avance')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('solde')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('statut')
                    ->searchable()
                     ->badge(),
                TextColumn::make('date_commande')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    

                    BulkAction::make('affecterCommandeLivreur')
                        ->label('Affecter commade au livreur')
                        ->icon('heroicon-m-truck')  
                        ->color('info')
                        ->modalHeading('Affecter les commandes sélectionnées au livreur')
                        ->modalDescription('Êtes-vous sûr de vouloir affecter les commandes sélectionnées à ce livreur ? Le statut passera à "AFFECTEE".')
                        ->requiresConfirmation()
                        ->modalWidth(Width::ExtraLarge)
                        
                        ->form([
                            Select::make('livreur_id')
                                ->relationship('livreur', 'nom')
                                ->getOptionLabelFromRecordUsing(
                                    fn ($record) => "{$record->nom} - {$record->type} - {$record->quartier?->nom_quartier} - {$record->adresse} - ({$record->telephone})"
                                )
                                ->label('Choisir un Livreur')
                                ->searchable()
                                ->preload()
                                ->required(), 
                        ])
                        
                        ->action(function (Collection $records, array $data) {
                            try {
                                foreach ($records as $record) {
                                    
                                    $record->affecter_commande_livreur($data);
                                }

                                Notification::make()
                                    ->title('Commandes affectées')
                                    ->body(count($records) . ' commande(s) affectée(s) avec succès au livreur.')
                                    ->success()
                                    ->send();

                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title("Erreur lors de l'affectation")
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),

                        BulkAction::make('marquerLivreeFournisseur')
                            ->label('Fournisseur a remis le colis')
                            ->icon('heroicon-m-hand-raised')
                            ->color('primary')
                            ->requiresConfirmation()
                            ->modalHeading('Confirmer la remise du colis')
                            ->modalDescription('Confirmez-vous que le fournisseur a bien remis les commandes sélectionnées au livreur ? Le solde du fournisseur sera crédité sous 24h.')
                            ->action(function (Collection $records, array $data) {
                                try {
                                    foreach ($records as $record) {
                                        $record->marquer_commande_livree_fournisseur($data);
                                    }

                                    Notification::make()
                                        ->title('Remise confirmée !')
                                        ->body(count($records) . ' commande(s) marquée(s) comme remises par le fournisseur.')
                                        ->success()
                                        ->send();

                                } catch (\Exception $e) {
                                    Notification::make()
                                        ->title("Erreur lors de la confirmation")
                                        ->body($e->getMessage())
                                        ->danger()
                                        ->send();
                                }
                            }),

                    BulkAction::make('marquerRecuperee')
                            ->label('Marquer comme Récupérée')
                            ->icon('heroicon-m-archive-box-arrow-down') 
                            ->color('warning')
                            ->requiresConfirmation()
                            ->modalHeading('Confirmer la récupération des colis')
                            ->modalDescription('Êtes-vous sûr de vouloir marquer les commandes sélectionnées comme récupérées chez le fournisseur ? Le statut passera à "RECUPEREE".')
                            ->action(function (Collection $records, array $data) {
                                try {
                                    foreach ($records as $record) {
                                        
                                        $record->marquer_demarre_livraison($data);
                                    }
                                    Notification::make()
                                        ->title('Commandes récupérées')
                                        ->body(count($records) . ' commande(s) marquée(s) avec succès comme récupérée(s).')
                                        ->success()
                                        ->send();
                                } catch (\Exception $e) {
                                   
                                    Notification::make()
                                        ->title("Erreur de statut")
                                        ->body($e->getMessage())
                                        ->danger()
                                        ->send();
                                }
                            }),

                    BulkAction::make('marquerEnRoute')
                            ->label('Marquer En Route')
                            ->icon('heroicon-m-truck') 
                            ->color('success') 
                            ->requiresConfirmation()
                            ->modalHeading('Départ du livreur')
                            ->modalDescription('Confirmez-vous que le livreur est actuellement en route vers le client pour livrer ces commandes ?')
                            ->action(function (Collection $records, array $data) {
                                try {
                                    foreach ($records as $record) {
                                      
                                        $record->marquer_livraison_en_route($data);
                                    }
                                    Notification::make()
                                        ->title('Livreur en route')
                                        ->body(count($records) . ' commande(s) marquée(s) comme "En Route".')
                                        ->success()
                                        ->send();
                                } catch (\Exception $e) {
                                    Notification::make()
                                        ->title("Erreur de statut")
                                        ->body($e->getMessage())
                                        ->danger()
                                        ->send();
                                }
                            }),

                    BulkAction::make('marquerLivree')
                            ->label('Marquer comme Livrée')
                            ->icon('heroicon-m-check-badge') 
                            ->color('success')
                            ->requiresConfirmation()
                            ->modalHeading('Confirmer la livraison terminée')
                            ->modalDescription('Confirmez-vous que ces commandes ont été livrées avec succès au client ? Cette action va automatiquement créditer le portefeuille du livreur (sous 24h).')
                            ->action(function (Collection $records, array $data) {
                                try {
                                    foreach ($records as $record) {
                                        $record->marquer_livraison_terminer($data);
                                    }

                                    Notification::make()
                                        ->title('Livraisons terminées !')
                                        ->body(count($records) . ' commande(s) marquée(s) comme livrées. Les livreurs seront crédités sous 24h.')
                                        ->success()
                                        ->send();

                                } catch (\Exception $e) {
                                    Notification::make()
                                        ->title("Erreur lors de la finalisation")
                                        ->body($e->getMessage())
                                        ->danger()
                                        ->send();
                                }
                            }),


                    BulkAction::make('marquerReceptionneClient')
                            ->label('Client a reçu la commande')
                            ->icon('heroicon-m-home')
                            ->color('success')
                            ->requiresConfirmation()
                            ->modalHeading('Confirmer la réception par le client')
                            ->modalDescription('Confirmez-vous que le client a bien réceptionné les commandes sélectionnées ?')
                            ->action(function (Collection $records, array $data) {
                                try {
                                    foreach ($records as $record) {
                                        $record->marquer_commande_recu_client($data);
                                    }

                                    Notification::make()
                                        ->title('Réception confirmée !')
                                        ->body(count($records) . ' commande(s) marquée(s) comme réceptionnée(s) par le client.')
                                        ->success()
                                        ->send();

                                } catch (\Exception $e) {
                                    Notification::make()
                                        ->title("Erreur lors de la confirmation")
                                        ->body($e->getMessage())
                                        ->danger()
                                        ->send();
                                }
                            }),

                            DeleteBulkAction::make()

                        ->deselectRecordsAfterCompletion(),


                 ]),
            ]);
    }
}
