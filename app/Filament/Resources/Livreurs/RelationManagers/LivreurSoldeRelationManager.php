<?php

namespace App\Filament\Resources\Livreurs\RelationManagers;

use App\Filament\Resources\LivreurSoldes\LivreurSoldeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class LivreurSoldeRelationManager extends RelationManager
{
    protected static string $relationship = 'livreurSolde';

    protected static ?string $relatedResource = LivreurSoldeResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
