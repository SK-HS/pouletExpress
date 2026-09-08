<?php

namespace App\Filament\Resources\DemandeRetraits\Tables;

use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class DemandeRetraitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('beneficiaire')
                    ->label('Bénéficiaire')
                    ->getStateUsing(function ($record) {
                        if (!$record->beneficiaire) {
                            return 'Aucun';
                        }
                        $type = class_basename($record->beneficiaire_type); 
                        return "{$record->beneficiaire->nom} - {$record->beneficiaire->telephone} ({$type})";
                    })
                    ->icon('heroicon-m-user') 
                    ->weight('bold'),
                TextColumn::make('montant')
                    ->numeric()
                    ->sortable()
                     ->suffix(' FCFA'),
                TextColumn::make('mode_paiement')
                    ->searchable(),
                TextColumn::make('numero_paiement')
                    ->searchable(),
                TextColumn::make('date_traitee')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('statut')
                    ->badge(),
                TextColumn::make('user.name')
                    ->label('Utilisateur')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('beneficiaire_type')
                    ->label('Type de Bénéficiaire')
                    ->options([
                        \App\Models\Livreur::class => 'Uniquement les Livreurs',
                        \App\Models\Fournisseur::class => 'Uniquement les Fournisseurs',
                    ]),

                SelectFilter::make('statut')
                    ->label('Statut de la demande')
                    ->options([
                        'EN_ATTENTE' => 'En attente',
                        'TRAITEE'    => 'Traitée (Payée)',
                        'REJETEE'    => 'Rejetée',
                    ]),
                SelectFilter::make('mode_paiement')
                    ->label('Mode Paiement')
                    ->options([
                        'espece' => 'Espèce',
                        'wave' => 'Wave',
                        'mobile' => 'Mobile Money',
                    ]),
        
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    
                    BulkAction::make('valideDemandeRetrait')
                        ->label('Valider la demande de Retrait')
                        ->icon('heroicon-m-arrow-down-tray')
                        ->color('info')
                        ->modalHeading('Valider les demandes de Retrait sélectionnées')
                        ->requiresConfirmation()
                       ->modalWidth(Width::ExtraLarge)
                        
                        ->action(function (Collection $records, array $data) {
                            try {
                                // Appel de la logique métier située dans le Model Livreur
                                foreach ($records as $record) {
                                    $record->valider_demande_retrait($data);
                                }

                                Notification::make()
                                    ->title('demande de Retrait validée avec succès')
                                    ->body(count($records) . ' demande(s)de Retrait validée(s)  avec succès.')
                                    ->success()
                                    ->send();

                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('Erreur lors de la mise à jour')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        })
            ->deselectRecordsAfterCompletion(),
            ]);
    }
}
