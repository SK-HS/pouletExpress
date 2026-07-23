<?php

namespace App\Filament\Resources\ProduitFournisseurs\Pages;

use App\Filament\Resources\ProduitFournisseurs\ProduitFournisseurResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProduitFournisseur extends ViewRecord
{
    protected static string $resource = ProduitFournisseurResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
