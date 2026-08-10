<?php

namespace App\Filament\Resources\Publicites;

use App\Filament\Resources\Publicites\Pages\ManagePublicites;
use App\Models\Publicite;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PubliciteResource extends Resource
{
    protected static ?string $model = Publicite::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'titre';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('titre')
                    ->required(),
               
                FileUpload::make('image')
                    ->label('Image')
                    ->disk('public')
                    ->directory('Publicite')
                    ->visibility('public')
                    ->image()
                    ->imagePreviewHeight('150')
                    ->enableDownload()
                    ->enableOpen()
                    ->openable()
                    ->maxSize(10240)
                    ->previewable(),
                TextInput::make('badge'),
                TextInput::make('lien'),
                TextInput::make('bouton_texte')
                    ->default('En profiter'),
                Toggle::make('est_actif')
                    ->required(),
                DateTimePicker::make('date_debut'),
                DateTimePicker::make('date_fin'),
                 Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('titre'),
                TextEntry::make('description')
                    ->columnSpanFull(),
                ImageEntry::make('image')
                    ->disk('public')
                    ->imageWidth(200)
                    ->imageHeight(200)
                    ->square()
                    ->url(fn ($state) => asset('storage/' . $state))
                    ->openUrlInNewTab(),
                TextEntry::make('badge')
                    ->placeholder('-'),
                TextEntry::make('lien')
                    ->placeholder('-'),
                TextEntry::make('bouton_texte')
                    ->placeholder('-'),
                IconEntry::make('est_actif')
                    ->boolean(),
                TextEntry::make('date_debut')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('date_fin')
                    ->dateTime()
                    ->placeholder('-'),
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
            ->recordTitleAttribute('titre')
            ->columns([
                TextColumn::make('titre')
                    ->searchable(),
                TextColumn::make('badge')
                    ->searchable(),
                TextColumn::make('lien')
                    ->searchable(),
                TextColumn::make('bouton_texte')
                    ->searchable(),
                IconColumn::make('est_actif')
                    ->boolean(),
                TextColumn::make('date_debut')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('date_fin')
                    ->dateTime()
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
            'index' => ManagePublicites::route('/'),
        ];
    }
}
