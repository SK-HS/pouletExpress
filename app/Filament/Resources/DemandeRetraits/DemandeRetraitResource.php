<?php

namespace App\Filament\Resources\DemandeRetraits;

use App\Filament\Resources\DemandeRetraits\Pages\CreateDemandeRetrait;
use App\Filament\Resources\DemandeRetraits\Pages\EditDemandeRetrait;
use App\Filament\Resources\DemandeRetraits\Pages\ListDemandeRetraits;
use App\Filament\Resources\DemandeRetraits\Pages\ViewDemandeRetrait;
use App\Filament\Resources\DemandeRetraits\Schemas\DemandeRetraitForm;
use App\Filament\Resources\DemandeRetraits\Schemas\DemandeRetraitInfolist;
use App\Filament\Resources\DemandeRetraits\Tables\DemandeRetraitsTable;
use App\Models\DemandeRetrait;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DemandeRetraitResource extends Resource
{
    protected static ?string $model = DemandeRetrait::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'GESTION SOLDE';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'retrait';

    public static function form(Schema $schema): Schema
    {
        return DemandeRetraitForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DemandeRetraitInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DemandeRetraitsTable::configure($table);
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
            'index' => ListDemandeRetraits::route('/'),
            'create' => CreateDemandeRetrait::route('/create'),
            'view' => ViewDemandeRetrait::route('/{record}'),
            'edit' => EditDemandeRetrait::route('/{record}/edit'),
        ];
    }
}
