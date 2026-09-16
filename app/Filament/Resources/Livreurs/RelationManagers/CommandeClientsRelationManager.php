<?php

namespace App\Filament\Resources\Livreurs\RelationManagers;

use App\Filament\Resources\CommandeClients\CommandeClientResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class CommandeClientsRelationManager extends RelationManager
{
    protected static string $relationship = 'commandeClents';

    protected static ?string $relatedResource = CommandeClientResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
