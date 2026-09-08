<?php

namespace App\Filament\Resources\CampagnePromotions\Pages;

use App\Filament\Resources\CampagnePromotions\CampagnePromotionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCampagnePromotion extends EditRecord
{
    protected static string $resource = CampagnePromotionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
