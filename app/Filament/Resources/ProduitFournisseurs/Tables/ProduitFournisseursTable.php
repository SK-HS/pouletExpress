<?php

namespace App\Filament\Resources\ProduitFournisseurs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                //
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
