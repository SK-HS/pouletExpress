<?php

namespace App\Filament\Resources\LivreurSoldes\Pages;

use App\Filament\Resources\LivreurSoldes\LivreurSoldeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageLivreurSoldes extends ManageRecords
{
    protected static string $resource = LivreurSoldeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
