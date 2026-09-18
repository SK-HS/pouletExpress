<?php

namespace App\Filament\Resources\AuditActivities;

use App\Filament\Resources\AuditActivities\Pages\CreateAuditActivity;
use App\Filament\Resources\AuditActivities\Pages\EditAuditActivity;
use App\Filament\Resources\AuditActivities\Pages\ListAuditActivities;
use App\Filament\Resources\AuditActivities\Pages\ViewAuditActivity;
use App\Filament\Resources\AuditActivities\Schemas\AuditActivityForm;
use App\Filament\Resources\AuditActivities\Schemas\AuditActivityInfolist;
use App\Filament\Resources\AuditActivities\Tables\AuditActivitiesTable;
use App\Models\AuditActivity;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AuditActivityResource extends Resource
{
    protected static ?string $model = AuditActivity::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Audit';

    public static function form(Schema $schema): Schema
    {
        return AuditActivityForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AuditActivityInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AuditActivitiesTable::configure($table);
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
            'index' => ListAuditActivities::route('/'),
            'create' => CreateAuditActivity::route('/create'),
            'view' => ViewAuditActivity::route('/{record}'),
            'edit' => EditAuditActivity::route('/{record}/edit'),
        ];
    }
}
