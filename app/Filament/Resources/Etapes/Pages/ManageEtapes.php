<?php

namespace App\Filament\Resources\Etapes\Pages;

use App\Filament\Resources\Etapes\EtapeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageEtapes extends ManageRecords
{
    protected static string $resource = EtapeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
