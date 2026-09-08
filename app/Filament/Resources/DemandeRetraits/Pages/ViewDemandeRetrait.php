<?php

namespace App\Filament\Resources\DemandeRetraits\Pages;

use App\Filament\Resources\DemandeRetraits\DemandeRetraitResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDemandeRetrait extends ViewRecord
{
    protected static string $resource = DemandeRetraitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
