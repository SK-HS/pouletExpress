<?php

namespace App\Filament\Resources\Fournisseurs;

use App\Filament\Resources\Fournisseurs\Pages\CreateFournisseur;
use App\Filament\Resources\Fournisseurs\Pages\EditFournisseur;
use App\Filament\Resources\Fournisseurs\Pages\ListFournisseurs;
use App\Filament\Resources\Fournisseurs\Pages\ViewFournisseur;
use App\Filament\Resources\Fournisseurs\Schemas\FournisseurForm;
use App\Filament\Resources\Fournisseurs\Schemas\FournisseurInfolist;
use App\Filament\Resources\Fournisseurs\Tables\FournisseursTable;
use App\Models\Fournisseur;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class FournisseurResource extends Resource
{
    protected static ?string $model = Fournisseur::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'GESTION FOURNISSEURS';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'code_founisseur';

    public static function form(Schema $schema): Schema
    {
        return FournisseurForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FournisseurInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FournisseursTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
                RelationManagers\CommandeClientsRelationManager::class,
                RelationManagers\DemandesRetraitsRelationManager::class,
                RelationManagers\FournisseurSoldeRelationManager::class,
                RelationManagers\CampagnePromotionRelationManager::class,
                RelationManagers\ProduitsFournisseurRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFournisseurs::route('/'),
            'create' => CreateFournisseur::route('/create'),
            'view' => ViewFournisseur::route('/{record}'),
            'edit' => EditFournisseur::route('/{record}/edit'),
        ];
    }
}
