<?php

namespace App\Filament\Resources\Villes\Pages;

use App\Filament\Resources\Villes\VilleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageVilles extends ManageRecords
{
    protected static string $resource = VilleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
