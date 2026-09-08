<?php

namespace App\Filament\Resources\ProduitFournisseurs;

use App\Filament\Resources\ProduitFournisseurs\Pages\CreateProduitFournisseur;
use App\Filament\Resources\ProduitFournisseurs\Pages\EditProduitFournisseur;
use App\Filament\Resources\ProduitFournisseurs\Pages\ListProduitFournisseurs;
use App\Filament\Resources\ProduitFournisseurs\Pages\ViewProduitFournisseur;
use App\Filament\Resources\ProduitFournisseurs\Schemas\ProduitFournisseurForm;
use App\Filament\Resources\ProduitFournisseurs\Schemas\ProduitFournisseurInfolist;
use App\Filament\Resources\ProduitFournisseurs\Tables\ProduitFournisseursTable;
use App\Models\ProduitFournisseur;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ProduitFournisseurResource extends Resource
{
    protected static ?string $model = ProduitFournisseur::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'GESTION PRODUITS';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'prix';

    public static function form(Schema $schema): Schema
    {
        return ProduitFournisseurForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProduitFournisseurInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProduitFournisseursTable::configure($table);
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
            'index' => ListProduitFournisseurs::route('/'),
            'create' => CreateProduitFournisseur::route('/create'),
            'view' => ViewProduitFournisseur::route('/{record}'),
            'edit' => EditProduitFournisseur::route('/{record}/edit'),
        ];
    }
}
