<?php

namespace App\Filament\Resources\AuditActivities\Pages;

use App\Filament\Resources\AuditActivities\AuditActivityResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAuditActivity extends ViewRecord
{
    protected static string $resource = AuditActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
        ];
    }
}
