<?php

namespace App\Filament\Resources\Fournisseurs\Pages;

use App\Filament\Resources\Fournisseurs\FournisseurResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageFournisseurs extends ManageRecords
{
    protected static string $resource = FournisseurResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
