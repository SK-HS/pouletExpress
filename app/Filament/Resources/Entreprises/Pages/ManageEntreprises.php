<?php

namespace App\Filament\Resources\Entreprises\Pages;

use App\Filament\Resources\Entreprises\EntrepriseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageEntreprises extends ManageRecords
{
    protected static string $resource = EntrepriseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
