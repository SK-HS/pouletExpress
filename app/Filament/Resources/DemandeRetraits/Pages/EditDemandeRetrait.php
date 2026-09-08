<?php

namespace App\Filament\Resources\DemandeRetraits\Pages;

use App\Filament\Resources\DemandeRetraits\DemandeRetraitResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDemandeRetrait extends EditRecord
{
    protected static string $resource = DemandeRetraitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
