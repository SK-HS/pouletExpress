<?php

namespace App\Filament\Resources\CommandeClients\Pages;

use App\Filament\Resources\CommandeClients\CommandeClientResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCommandeClient extends EditRecord
{
    protected static string $resource = CommandeClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
