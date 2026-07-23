<?php

namespace App\Filament\Resources\CommandeClients;

use App\Filament\Resources\CommandeClients\Pages\CreateCommandeClient;
use App\Filament\Resources\CommandeClients\Pages\EditCommandeClient;
use App\Filament\Resources\CommandeClients\Pages\ListCommandeClients;
use App\Filament\Resources\CommandeClients\Pages\ViewCommandeClient;
use App\Filament\Resources\CommandeClients\Schemas\CommandeClientForm;
use App\Filament\Resources\CommandeClients\Schemas\CommandeClientInfolist;
use App\Filament\Resources\CommandeClients\Tables\CommandeClientsTable;
use App\Models\CommandeClient;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CommandeClientResource extends Resource
{
    protected static ?string $model = CommandeClient::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'reference';

    public static function form(Schema $schema): Schema
    {
        return CommandeClientForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CommandeClientInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CommandeClientsTable::configure($table);
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
            'index' => ListCommandeClients::route('/'),
            'create' => CreateCommandeClient::route('/create'),
            'view' => ViewCommandeClient::route('/{record}'),
            'edit' => EditCommandeClient::route('/{record}/edit'),
        ];
    }
}
