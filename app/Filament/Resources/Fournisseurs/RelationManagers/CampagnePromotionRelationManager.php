<?php

namespace App\Filament\Resources\Fournisseurs\RelationManagers;

use App\Filament\Resources\CampagnePromotions\CampagnePromotionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class CampagnePromotionRelationManager extends RelationManager
{
    protected static string $relationship = 'promoFournisseur';

    protected static ?string $relatedResource = CampagnePromotionResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
