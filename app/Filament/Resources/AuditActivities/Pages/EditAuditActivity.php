<?php

namespace App\Filament\Resources\AuditActivities\Pages;

use App\Filament\Resources\AuditActivities\AuditActivityResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAuditActivity extends EditRecord
{
    protected static string $resource = AuditActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            // DeleteAction::make(),
        ];
    }
}
