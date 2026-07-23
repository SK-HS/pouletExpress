<?php

namespace App\Filament\Resources\Tailles\Pages;

use App\Filament\Resources\Tailles\TailleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageTailles extends ManageRecords
{
    protected static string $resource = TailleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
