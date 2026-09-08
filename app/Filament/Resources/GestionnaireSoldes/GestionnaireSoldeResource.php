<?php

namespace App\Filament\Resources\GestionnaireSoldes;

use App\Filament\Resources\GestionnaireSoldes\Pages\CreateGestionnaireSolde;
use App\Filament\Resources\GestionnaireSoldes\Pages\EditGestionnaireSolde;
use App\Filament\Resources\GestionnaireSoldes\Pages\ListGestionnaireSoldes;
use App\Filament\Resources\GestionnaireSoldes\Pages\ViewGestionnaireSolde;
use App\Filament\Resources\GestionnaireSoldes\Schemas\GestionnaireSoldeForm;
use App\Filament\Resources\GestionnaireSoldes\Schemas\GestionnaireSoldeInfolist;
use App\Filament\Resources\GestionnaireSoldes\Tables\GestionnaireSoldesTable;
use App\Models\GestionnaireSolde;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class GestionnaireSoldeResource extends Resource
{
    protected static ?string $model = GestionnaireSolde::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'GESTION SOLDE';
    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'solde gestionnaire';

    public static function form(Schema $schema): Schema
    {
        return GestionnaireSoldeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return GestionnaireSoldeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GestionnaireSoldesTable::configure($table);
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
            'index' => ListGestionnaireSoldes::route('/'),
            'create' => CreateGestionnaireSolde::route('/create'),
            'view' => ViewGestionnaireSolde::route('/{record}'),
            'edit' => EditGestionnaireSolde::route('/{record}/edit'),
        ];
    }
}
