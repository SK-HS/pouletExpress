<?php

namespace App\Filament\Resources\Livreurs\Pages;

use App\Filament\Resources\Livreurs\LivreurResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageLivreurs extends ManageRecords
{
    protected static string $resource = LivreurResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
