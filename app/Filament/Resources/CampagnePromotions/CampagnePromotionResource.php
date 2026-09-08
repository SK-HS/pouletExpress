<?php

namespace App\Filament\Resources\CampagnePromotions;

use App\Filament\Resources\CampagnePromotions\Pages\CreateCampagnePromotion;
use App\Filament\Resources\CampagnePromotions\Pages\EditCampagnePromotion;
use App\Filament\Resources\CampagnePromotions\Pages\ListCampagnePromotions;
use App\Filament\Resources\CampagnePromotions\Pages\ViewCampagnePromotion;
use App\Filament\Resources\CampagnePromotions\Schemas\CampagnePromotionForm;
use App\Filament\Resources\CampagnePromotions\Schemas\CampagnePromotionInfolist;
use App\Filament\Resources\CampagnePromotions\Tables\CampagnePromotionsTable;
use App\Models\CampagnePromotion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CampagnePromotionResource extends Resource
{
    protected static ?string $model = CampagnePromotion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'GESTION FOURNISSEURS';
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'campagne promo';

    public static function form(Schema $schema): Schema
    {
        return CampagnePromotionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CampagnePromotionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CampagnePromotionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCampagnePromotions::route('/'),
            'create' => CreateCampagnePromotion::route('/create'),
            'view' => ViewCampagnePromotion::route('/{record}'),
            'edit' => EditCampagnePromotion::route('/{record}/edit'),
        ];
    }
}
