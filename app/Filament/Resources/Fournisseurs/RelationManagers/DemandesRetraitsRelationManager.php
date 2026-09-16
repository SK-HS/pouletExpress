<?php

namespace App\Filament\Resources\Fournisseurs\RelationManagers;

use App\Filament\Resources\DemandeRetraits\DemandeRetraitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class DemandesRetraitsRelationManager extends RelationManager
{
    protected static string $relationship = 'demandesRetraits';

    protected static ?string $relatedResource = DemandeRetraitResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
