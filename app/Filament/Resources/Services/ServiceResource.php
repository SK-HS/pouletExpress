<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\Pages\ManageServices;
use App\Models\Service;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'designation';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('designation')
                    ->required(),
                TextInput::make('prix')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('quartier')
                    ->relationship('quartier', 'nom_quartier')
                     ->getOptionLabelFromRecordUsing(
                            fn ($record) => "{$record->commune->ville?->nom_ville}-{$record->commune?->nom_commune} - {$record->nom_quartier}"
                        )
                    ->label('Quartier')
                    ->searchable()
                    ->preload()
                    ->required(),
                Textarea::make('Detail')
                    ->columnSpanFull(),
                Hidden::make('user_id')
                    ->default(fn () => Auth::id()),

            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('designation'),
                TextEntry::make('prix')
                    ->numeric(),
                TextEntry::make('quartier.nom_quartier'),
                TextEntry::make('Detail')
                    ->placeholder('-'),
                TextEntry::make('user.name')
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
            ->recordTitleAttribute('designation')
            ->columns([
                TextColumn::make('designation')
                    ->searchable(),
                TextColumn::make('prix')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('quartier.nom_quartier')
                    ->searchable(),
                TextColumn::make('Detail')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Utilisateur')
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
            'index' => ManageServices::route('/'),
        ];
    }
}
