<?php

namespace App\Filament\Resources\CampagnePromotions\Pages;

use App\Filament\Resources\CampagnePromotions\CampagnePromotionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCampagnePromotions extends ListRecords
{
    protected static string $resource = CampagnePromotionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
