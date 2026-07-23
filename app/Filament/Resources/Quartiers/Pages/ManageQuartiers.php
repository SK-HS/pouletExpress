<?php

namespace App\Filament\Resources\Quartiers\Pages;

use App\Filament\Resources\Quartiers\QuartierResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageQuartiers extends ManageRecords
{
    protected static string $resource = QuartierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
