<?php

namespace App\Filament\Resources\GestionnaireSoldes\Pages;

use App\Filament\Resources\GestionnaireSoldes\GestionnaireSoldeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewGestionnaireSolde extends ViewRecord
{
    protected static string $resource = GestionnaireSoldeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
