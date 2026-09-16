<?php

namespace App\Filament\Resources\ProduitFournisseurs\Tables;

use App\Models\Categorie;
use App\Models\Fournisseur;
use App\Models\Produit;
use App\Models\Taille;
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

class ProduitFournisseursTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                 TextColumn::make('fournisseur.nom')
                    ->label('Fournisseur')
                    ->sortable(),
                TextColumn::make('produit.nom')
                    ->label('Produit')
                    ->sortable(),
                TextColumn::make('quantite')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('commande_min')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('prix')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('statuts')
                    ->boolean(),
                IconColumn::make('etat')
                    ->boolean(),
               
                TextColumn::make('categorie.nom')
                    ->label('Categorie')
                    ->sortable()
                      ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('taille.taille')
                    ->label('Taille')
                    ->sortable()
                      ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('user.name')
                    ->label('Utilisateur')
                    ->sortable()
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
                SelectFilter::make('produit_id')
                        ->options(function () {
                            return Produit::orderBy('nom')
                                ->get()
                                ->mapWithKeys(fn ($e) => [$e->id => "{$e->code_barre} - {$e->nom}"])
                                ->toArray();
                        })
                        ->multiple()
                        ->searchable()
                        ->label('PRODUITS'),
                SelectFilter::make('categorie_id')
                    ->options(function () {
                        return Categorie::orderBy('nom')
                            ->get()
                            ->mapWithKeys(fn ($e) => [$e->id => "{$e->nom}"])
                            ->toArray();
                    })
                    ->multiple()
                    ->searchable()
                    ->label('CATEGORIE'),
                SelectFilter::make('taille_id')
                    ->options(function () {
                        return Taille::orderBy('taille')
                            ->get()
                            ->mapWithKeys(fn ($e) => [$e->id => "{$e->taille}"])
                            ->toArray();
                    })
                    ->multiple()
                    ->searchable()
                    ->label('TAILLE'),

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
