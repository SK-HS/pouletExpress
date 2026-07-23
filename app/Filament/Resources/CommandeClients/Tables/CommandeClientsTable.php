<?php

namespace App\Filament\Resources\CommandeClients\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
