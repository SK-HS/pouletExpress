<?php

namespace App\Filament\Resources\Communes;

use App\Filament\Resources\Communes\Pages\ManageCommunes;
use App\Models\Commune;
use App\Models\Ville;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class CommuneResource extends Resource
{
    protected static ?string $model = Commune::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'ADMINISTRATION';
    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'nom_commune';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('ville_id')
                    ->relationship('ville', 'nom_ville')
                    //  ->getOptionLabelFromRecordUsing(
                    //         fn ($record) => "{$record->ville?->nom_ville}}"
                    //     )
                    ->label('Ville')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('nom_commune')
                    ->required(),
                
                Hidden::make('user_id')
                    ->default(fn () => Auth::id()),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('ville.nom_ville'),
                TextEntry::make('nom_commune'),
                TextEntry::make('user.nme')
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
            ->recordTitleAttribute('nom_commune')
            ->columns([
                TextColumn::make('ville.nom_ville')
                    ->sortable(),
                TextColumn::make('nom_commune')
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
                 SelectFilter::make('ville_id')
                    ->options(function () {
                        return Ville::orderBy('nom_ville')
                            ->get()
                            ->mapWithKeys(fn ($e) => [$e->id => "{$e->nom_ville}"])
                            ->toArray();
                    })
                    ->multiple()
                    ->searchable()
                    ->label('VILLES'),

                Filter::make('created_at')
                    ->label('PERIODE DE CREATION')
                    ->schema([
                        DatePicker::make('created_from')->label('Debut'),
                        DatePicker::make('created_until')->label('Fin'),
                                            ])
                            ->query(function (Builder $query, array $data): Builder {
                                return $query
                                    ->when(
                                        $data['created_from'],
                                        fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                                    )
                                    ->when(
                                        $data['created_until'],
                                        fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                                    );
                                    }),
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
            'index' => ManageCommunes::route('/'),
        ];
    }
}
