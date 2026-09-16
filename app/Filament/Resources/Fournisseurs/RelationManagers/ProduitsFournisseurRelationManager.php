<?php

namespace App\Filament\Resources\Fournisseurs\RelationManagers;

use App\Filament\Resources\ProduitFournisseurs\ProduitFournisseurResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class ProduitsFournisseurRelationManager extends RelationManager
{
    protected static string $relationship = 'produits';

    protected static ?string $relatedResource = ProduitFournisseurResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
