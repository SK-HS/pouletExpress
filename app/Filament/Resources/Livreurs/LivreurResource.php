<?php

namespace App\Filament\Resources\Livreurs;

use App\Filament\Resources\Livreurs\Pages\CreateLivreur;
use App\Filament\Resources\Livreurs\Pages\EditLivreur;
use App\Filament\Resources\Livreurs\Pages\ListLivreurs;
use App\Filament\Resources\Livreurs\Pages\ViewLivreur;
use App\Filament\Resources\Livreurs\Schemas\LivreurForm;
use App\Filament\Resources\Livreurs\Schemas\LivreurInfolist;
use App\Filament\Resources\Livreurs\Tables\LivreursTable;
use App\Models\Livreur;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class LivreurResource extends Resource
{
    protected static ?string $model = Livreur::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string | UnitEnum | null $navigationGroup = 'GESTION LIVREURS';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'code_livreur';

    public static function form(Schema $schema): Schema
    {
        return LivreurForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LivreurInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LivreursTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
             RelationManagers\CommandeClientsRelationManager::class,
             RelationManagers\DemandesRetraitsRelationManager::class,
             RelationManagers\LivreurSoldeRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLivreurs::route('/'),
            'create' => CreateLivreur::route('/create'),
            'view' => ViewLivreur::route('/{record}'),
            'edit' => EditLivreur::route('/{record}/edit'),
        ];
    }
}
