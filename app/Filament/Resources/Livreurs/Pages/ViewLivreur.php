<?php

namespace App\Filament\Resources\Livreurs\Pages;

use App\Filament\Resources\Livreurs\LivreurResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLivreur extends ViewRecord
{
    protected static string $resource = LivreurResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
