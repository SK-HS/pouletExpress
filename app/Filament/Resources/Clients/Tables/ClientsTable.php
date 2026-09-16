<?php

namespace App\Filament\Resources\Clients\Tables;

use App\Models\Quartier;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nom')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('telephone')
                    ->searchable()
                    ->label('Téléphone'),
                TextColumn::make('ville.nom_ville')
                    ->searchable(),
                TextColumn::make('adresse')
                    ->searchable(),
                TextColumn::make('code_client')
                    ->searchable(),
                IconColumn::make('etat')
                    ->boolean(),
                TextColumn::make('statut')
                      ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'NOUVEAU' => 'primary',
                    'ACTIF' => 'success',
                    'EN_ATTENTE' => 'primary',
                    'BLOQUE' => 'danger',
                    'REJETE' => 'warning',
                }),
                TextColumn::make('user.name')
                    ->label('Utilisateur')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('agence.nom')
                //     ->label('Agence')
                //     ->searchable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('entreprise.nom')
                    ->label('Entreprise')
                    ->searchable()
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
                    ->label('STATUT LIVREUR')
                    ->options([
                       'ACTIF' => 'ACTIF',
                        'EN_ATTENTE' => 'EN ATTENTE',
                        'BLOQUE' => 'BLOQUE',
                        'REJETE' => 'REJETE',
                    ])
                    ->multiple()
                    ->preload(),
                 SelectFilter::make('type')
                    ->label('TYPE LIVREUR')
                    ->multiple()
                    ->options([
                        'Particulier' => 'Particulier',
                        'Entreprise' => 'Entreprise',
                        'Restaurant' => 'Restaurant',
                        'Hôtel' => 'Hôtel',
                        'Supermarché' => 'Supermarché',
                        'Boutique' => 'Boutique',
                        'Grossiste' => 'Grossiste',
                        'Revendeur' => 'Revendeur',
                        'Administration' => 'Administration',
                        'Association' => 'Association',
                        'École' => 'École',
                        'Clinique' => 'Clinique',
                        'Autre' => 'Autre',
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
                ]),
            ]);
    }
}
