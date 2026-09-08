<?php

namespace App\Filament\Resources\FournisseurSoldes\Pages;

use App\Filament\Resources\FournisseurSoldes\FournisseurSoldeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageFournisseurSoldes extends ManageRecords
{
    protected static string $resource = FournisseurSoldeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
