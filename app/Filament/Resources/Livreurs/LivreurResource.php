<?php

namespace App\Filament\Resources\Livreurs;

use App\Filament\Resources\Livreurs\Pages\ManageLivreurs;
use App\Models\Livreur;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class LivreurResource extends Resource
{
    protected static ?string $model = Livreur::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextInput::make('reference'),

                TextInput::make('nom')
                    ->required(),
                Select::make('type')
                    ->label('Type de livreur')
                    ->options([
                        'VELO' => 'Vélo',
                        'MOTO' => 'Moto',
                        'TRICYCLE' => 'Tricycle',
                        'VOITURE' => 'Voiture',
                        'CAMIONNETTE' => 'Camionnette',
                        'CAMION' => 'Camion',
                    ]),
                TextInput::make('adresse'),
                TextInput::make('telephone')
                    ->tel(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('contact'),
                Hidden::make('user_id')
                    ->default(fn () => Auth::id()),
                Select::make('quartier')
                    ->relationship('quartier', 'nom_quartier')
                     ->getOptionLabelFromRecordUsing(
                            fn ($record) => "{$record->commune?->ville?->nom_ville}-{$record->commune?->nom_commune} - {$record->nom_quartier}"
                        )
                    ->label('Quartier')
                    ->searchable()
                    ->preload()
                    ->required(),
                FileUpload::make('image')
                    ->label('Image')
                    ->disk('public')
                    ->directory('Livreur')
                    ->visibility('public')
                    ->image()
                    ->imagePreviewHeight('150')
                    ->enableDownload()
                    ->enableOpen()
                    ->openable()
                    ->previewable(),
                TextInput::make('compte')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('reference')
                    ->placeholder('-'),
                TextEntry::make('nom'),
                TextEntry::make('type')
                    ->placeholder('-'),
                TextEntry::make('adresse')
                    ->placeholder('-'),
                TextEntry::make('telephone')
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->label('Email address')
                    ->placeholder('-'),
                TextEntry::make('contact')
                    ->placeholder('-'),
                TextEntry::make('quartier.nom_quartier')
                    ->placeholder('-'),
                ImageEntry::make('image')
                    ->disk('public')
                    ->imageWidth(200)
                    ->imageHeight(200)
                    ->square()
                    ->url(fn ($state) => asset('storage/' . $state))
                    ->openUrlInNewTab(),
                TextEntry::make('user.name')
                    ->label('Utilisateur'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('compte')
                    ->numeric()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nom')
            ->columns([
                TextColumn::make('reference')
                    ->searchable(),
                TextColumn::make('nom')
                    ->searchable(),
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('adresse')
                    ->searchable(),
                TextColumn::make('telephone')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('contact')
                    ->searchable(),
                TextColumn::make('quartier.nom_quartier')
                    ->sortable(),
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
                TextColumn::make('compte')
                    ->numeric()
                    ->sortable(),
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
            'index' => ManageLivreurs::route('/'),
        ];
    }
}
