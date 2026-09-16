<?php

namespace App\Filament\Resources\Fournisseurs\Tables;

use App\Models\Quartier;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class FournisseursTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')
                    ->searchable(),
                TextColumn::make('type'),
                TextColumn::make('nom')
                    ->searchable(),
                TextColumn::make('adresse')
                    ->searchable(),
                TextColumn::make('telephone')
                    ->label('Téléphone')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('contact')
                    ->searchable(),
                IconColumn::make('etat')
                    ->boolean()
                    ->sortable(),
                IconColumn::make('disponible')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('compte')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                    'ACTIF' => 'success',
                    'EN_ATTENTE' => 'primary',
                    'BLOQUE' => 'danger',
                    'REJETE' => 'warning',
                })
                    ->sortable(),
                TextColumn::make('quartier.nom_quartier'),
                TextColumn::make('user.name')
                    ->label('Utilisateur')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('entreprise.nom')
                    ->label('Entreprise')
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    ->label('STATUT FOURNISSEUR')
                    ->options([
                       'ACTIF' => 'ACTIF',
                        'EN_ATTENTE' => 'EN ATTENTE',
                        'BLOQUE' => 'BLOQUE',
                        'REJETE' => 'REJETE',
                    ])
                    ->multiple()
                    ->preload(),
                 SelectFilter::make('type')
                    ->label('TYPE FOURNISSEUR')
                    ->multiple()
                    ->options([
                       'Éleveur'      => 'Éleveur',
                        'Grossiste'    => 'Grossiste',
                        'Transporteur' => 'Transporteur',
                        'Prestataire'  => 'Prestataire',
                        'Autre'        => 'Autre',
                    ]),
                 SelectFilter::make('type_produit')
                    ->label('TYPE DE PRODUIT')
                    ->multiple()
                    ->options([
                        'OEUF'          => 'OEUF',
                        'POULET CHAIRE' => 'POULET CHAIRE',
                        'PONDEUSES'     => 'PONDEUSES',
                        'HYBRIDE'       => 'HYBRIDE',
                        'Autre'         => 'Autre',
                    ]),
                    SelectFilter::make('quartier_id')
                    ->options(function () {
                        return Quartier::orderBy('nom_quartier')
                            ->get()
                            ->mapWithKeys(fn ($e) => [$e->id => "{$e->nom_quartier} - {$e->commune?->nom_commune} - {$e->commune?->ville?->nom_ville}"])
                            ->toArray();
                    })
                    ->multiple()
                    ->searchable()
                    ->label('QUARTIERS'),
                Filter::make('created_at')
                    ->label('PERIODE DE CREATION')
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
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    
                    BulkAction::make('MiseAJourStatutfournisseur')
                        ->label('Mise à jour du statut')
                        ->icon('heroicon-m-arrow-down-tray')
                        ->color('info')
                        ->modalHeading('Mettre à jour le statut des fournisseurs sélectionnés')
                        ->requiresConfirmation()
                       ->modalWidth(Width::ExtraLarge)
                        ->form([
                            Section::make('Détails de la mise à jour')->schema([
                                Select::make('statut')
                                    ->label('Nouveau Statut')
                                    ->options([
                                        'EN_ATTENTE' => 'EN_ATTENTE',
                                        'ACTIF'      => 'ACTIF',
                                        'BLOQUE'     => 'BLOQUE',
                                        'REJETE'     => 'REJETE',
                                    ])
                                    ->required()
                                    ->live(), 

                                TextInput::make('montant')
                                    ->label('Montant (Pénalité / Caution)')
                                    ->numeric()
                                    ->prefix('CFA')
                                    ->nullable(),

                                Textarea::make('motif')
                                    ->label('Motif (Obligatoire si Bloqué ou Rejeté)')
                                    ->required(fn ($get) => in_array($get('statut'), ['BLOQUE', 'REJETE']))
                                    ->columnSpanFull(),
                            ])->columns(2),
                        ])
                        ->action(function (Collection $records, array $data) {
                            try {
                                // Appel de la logique métier située dans le Model fournisseur
                                foreach ($records as $record) {
                                    $record->mise_a_jour_statut($data);
                                }

                                Notification::make()
                                    ->title('Statuts mis à jour avec succès')
                                    ->body(count($records) . ' fournisseur(s) modifié(s).')
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
            
                ]),
            ]);
    }
}
