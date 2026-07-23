<?php

namespace App\Filament\Resources\CommandeClients\Pages;

use App\Filament\Resources\CommandeClients\CommandeClientResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCommandeClients extends ListRecords
{
    protected static string $resource = CommandeClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
