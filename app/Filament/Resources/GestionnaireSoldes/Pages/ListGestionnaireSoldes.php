<?php

namespace App\Filament\Resources\GestionnaireSoldes\Pages;

use App\Filament\Resources\GestionnaireSoldes\GestionnaireSoldeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGestionnaireSoldes extends ListRecords
{
    protected static string $resource = GestionnaireSoldeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
