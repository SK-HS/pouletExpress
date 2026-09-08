<?php

namespace App\Filament\Resources\CampagnePromotions\Pages;

use App\Filament\Resources\CampagnePromotions\CampagnePromotionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCampagnePromotion extends ViewRecord
{
    protected static string $resource = CampagnePromotionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
