<?php

namespace App\Filament\Resources\Publicites\Pages;

use App\Filament\Resources\Publicites\PubliciteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePublicites extends ManageRecords
{
    protected static string $resource = PubliciteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
