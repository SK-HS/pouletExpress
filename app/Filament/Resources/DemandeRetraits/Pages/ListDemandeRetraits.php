<?php

namespace App\Filament\Resources\DemandeRetraits\Pages;

use App\Filament\Resources\DemandeRetraits\DemandeRetraitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDemandeRetraits extends ListRecords
{
    protected static string $resource = DemandeRetraitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
