<?php

namespace App\Filament\Resources\CommandeClients\Pages;

use App\Filament\Resources\CommandeClients\CommandeClientResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCommandeClient extends ViewRecord
{
    protected static string $resource = CommandeClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
