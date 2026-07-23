<?php

namespace App\Filament\Resources\CommandeLivreurs\Pages;

use App\Filament\Resources\CommandeLivreurs\CommandeLivreurResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCommandeLivreurs extends ManageRecords
{
    protected static string $resource = CommandeLivreurResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
