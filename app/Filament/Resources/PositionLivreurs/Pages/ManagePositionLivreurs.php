<?php

namespace App\Filament\Resources\PositionLivreurs\Pages;

use App\Filament\Resources\PositionLivreurs\PositionLivreurResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePositionLivreurs extends ManageRecords
{
    protected static string $resource = PositionLivreurResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
