<?php

namespace App\Filament\Resources\CampagnePromotions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
