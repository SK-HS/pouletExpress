<?php

namespace App\Filament\Resources\Fournisseurs\RelationManagers;

use App\Filament\Resources\FournisseurSoldes\FournisseurSoldeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class FournisseurSoldeRelationManager extends RelationManager
{
    protected static string $relationship = 'fournisseurSolde';

    protected static ?string $relatedResource = FournisseurSoldeResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
