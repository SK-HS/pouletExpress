<?php

namespace App\Filament\Resources\GestionnaireSoldes\Tables;

use App\Models\Fournisseur;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class GestionnaireSoldesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('debiteur')
                    ->label('Débiteur')
                    ->getStateUsing(function ($record) {
                        if (!$record->debiteur) {
                            return 'Aucun';
                        }
                        $type = class_basename($record->debiteur_type); 
                        return "{$record->debiteur->nom} - {$record->debiteur->telephone} ({$type})";
                    })
                    ->icon('heroicon-m-user') 
                    ->weight('bold'),
                TextColumn::make('commandeClient.reference')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('montant')
                    ->suffix(' FCFA')
                    ->sortable(),
                TextColumn::make('statut')
                    ->badge(),
                TextColumn::make('disponible_le')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('paye_le')
                    ->dateTime()
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
                 SelectFilter::make('statut')
                    ->label('STATUT COMMANDE')
                    ->options([
                       'EN_ATTENTE' => 'EN ATTENTE',
                        'DISPONIBLE' => 'DISPONIBLE',
                        'PAYE' => 'PAYE',
                        // 'REFUSE' => 'REFUSE',
                    ])
                    ->multiple()
                    ->preload(),
                 SelectFilter::make('debiteur_type')
                    ->label('DEBITEUR')
                    ->options([
                        \App\Models\Livreur::class => 'Uniquement les Livreurs',
                        \App\Models\Fournisseur::class => 'Uniquement les Fournisseurs',
                    ]),
                Filter::make('created_at')
                    ->schema([
                        DatePicker::make('created_from')->label('Debut'),
                        DatePicker::make('created_until')->label('Fin'),
                                            ])
                            ->query(function (Builder $query, array $data): Builder {
                                return $query
                                    ->when(
                                        $data['created_from'],
                                        fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                                    )
                                    ->when(
                                        $data['created_until'],
                                        fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                                    );
                                    }),

               
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                    DeleteBulkAction::make(),

                     BulkAction::make('valideCommissionCommande')
                        ->label('Valider la commission')
                        ->icon('heroicon-m-arrow-down-tray')
                        ->color('info')
                        ->modalHeading('Valider les commissions sélectionnées')
                        ->requiresConfirmation()
                       ->modalWidth(Width::ExtraLarge)
                        
                        ->action(function (Collection $records, array $data) {
                            try {
                                // Appel de la logique métier située dans le Model Livreur
                                foreach ($records as $record) {
                                    $record->valider_commission_gestionnaire($data);
                                }

                                Notification::make()
                                    ->title('Commission validée avec succès')
                                    ->body(count($records) . ' Commission(s) validée(s) avec succès.')
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
                // ]),

            ]);
    }
}
