<?php

namespace App\Filament\Resources\Quartiers;

use App\Filament\Resources\Quartiers\Pages\ManageQuartiers;
use App\Models\Quartier;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class QuartierResource extends Resource
{
    protected static ?string $model = Quartier::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nom_quartier';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
            
                Select::make('commune_id')
                    ->relationship('commune', 'nom_commune')
                     ->getOptionLabelFromRecordUsing(
                            fn ($record) => "{$record->ville?->nom_ville}-{$record->nom_commune}"
                        )
                    ->label('Commune')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('nom_quartier')
                    ->required(),
                Hidden::make('user_id')
                    ->default(fn () => Auth::id()),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nom_quartier'),
                TextEntry::make('commune.nom_commune'),
                TextEntry::make('user.name')
                    ->label('Utilisateur'),
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
            ->recordTitleAttribute('nom_quartier')
            ->columns([
                TextColumn::make('commune.nom_commune')
                    ->sortable(),
                TextColumn::make('nom_quartier')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => ManageQuartiers::route('/'),
        ];
    }
}
