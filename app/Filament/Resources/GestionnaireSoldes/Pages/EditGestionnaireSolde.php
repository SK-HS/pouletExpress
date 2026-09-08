<?php

namespace App\Filament\Resources\GestionnaireSoldes\Pages;

use App\Filament\Resources\GestionnaireSoldes\GestionnaireSoldeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditGestionnaireSolde extends EditRecord
{
    protected static string $resource = GestionnaireSoldeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
