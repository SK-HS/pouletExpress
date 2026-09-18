<?php

namespace App\Filament\Resources\AuditActivities\Pages;

use App\Filament\Resources\AuditActivities\AuditActivityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAuditActivities extends ListRecords
{
    protected static string $resource = AuditActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
