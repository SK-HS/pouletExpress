<?php

namespace App\Filament\Resources\PositionLivreurs;

use App\Filament\Resources\PositionLivreurs\Pages\ManagePositionLivreurs;
use App\Models\PositionLivreur;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PositionLivreurResource extends Resource
{
    protected static ?string $model = PositionLivreur::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'date_position';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('longitude')
                    ->required(),
                TextInput::make('latitude')
                    ->required(),
                DateTimePicker::make('date_position')
                    ->required(),
                Select::make('livreur_id')
                    ->relationship('livreur', 'nom')
                     ->getOptionLabelFromRecordUsing(
                            fn ($record) => "{$record->nom}-{$record->type} - {$record->telephone}"
                        )
                    ->label('Livreur')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('longitude'),
                TextEntry::make('latitude'),
                TextEntry::make('date_position')
                    ->dateTime(),
                TextEntry::make('livreur.nom')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('date_position')
            ->columns([
                TextColumn::make('longitude')
                    ->searchable(),
                TextColumn::make('latitude')
                    ->searchable(),
                TextColumn::make('date_position')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('livreur.nom')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePositionLivreurs::route('/'),
        ];
    }
}
