<?php

namespace App\Filament\Resources\CampagnePromotions\Tables;

use App\Models\Fournisseur;
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

class CampagnePromotionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('fournisseur.nom')
                    ->sortable(),
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('titre')
                    ->searchable(),
                TextColumn::make('taux_remise')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('seuil_quantite')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('date_debut')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('date_fin')
                    ->dateTime()
                    ->sortable(),
                IconColumn::make('est_active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('supprimer')
                    ->boolean(),
            ])
            ->filters([
                 SelectFilter::make('fournisseur_id')
                        ->options(function () {
                            return Fournisseur::orderBy('nom')
                                ->get()
                                ->mapWithKeys(fn ($e) => [$e->id => "{$e->nom} - {$e->type} - {$e->quartier?->nom_quartier} - {$e->adresse} - ({$e->telephone})"])
                                ->toArray();
                        })
                        ->multiple()
                        ->searchable()
                        ->label('FOURNISSEURS'),
                SelectFilter::make('type')
                         ->options([
                            'TOUT'=>"TOUS LES PRODUITS (Boutique entière)",
                            'SPECIFIQUE'=>"PRODUITS SPÉCIFIQUES"
                        ])
                        ->multiple()
                        ->searchable()
                        ->label('TYPE'),

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
