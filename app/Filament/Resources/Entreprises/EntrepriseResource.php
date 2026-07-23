<?php

namespace App\Filament\Resources\Entreprises;

use App\Filament\Exports\EmployeeExporter;
use App\Filament\Resources\Entreprises\Pages\ManageEntreprises;
use App\Models\Entreprise;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
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
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class EntrepriseResource extends Resource
{
    protected static ?string $model = Entreprise::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'GESTION';
    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nom')
                    ->label('Nom Entreprise')
                    ->required(),
                TextInput::make('adresse')
                    ->label('Adresse')
                    ->required(),
                TextInput::make('telephone')
                    ->tel()
                    ->required(),
                TextInput::make('contact')
                    ->label('Contact'),
                TextInput::make('ville')
                    ->label('Ville'),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('site_web'),
                FileUpload::make('logo')
                    ->label('Logo')
                    ->disk('public')
                    ->directory('Logo')
                    ->visibility('public')
                    ->image()
                    ->imagePreviewHeight('150')
                    ->enableDownload()
                    ->enableOpen()
                    ->openable()
                    ->previewable(),
                Textarea::make('pied_page')
                    ->columnSpanFull(),
                Toggle::make('status')
                    ->default(true),
                Hidden::make('user_id')
                    ->default(fn () => Auth::id()),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
               TextEntry::make('nom')
                    ->label('Nom Entreprise'),
                TextEntry::make('adresse')
                    ->label('Adresse'),
                TextEntry::make('telephone')
                    ->label('Téléphone'),
                TextEntry::make('contact')
                    ->label('Contact'),
                TextEntry::make('ville')
                    ->label('Ville'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('site_web')
                    ->label('Site Web'),
                ImageEntry::make('logo')
                    ->disk('public')
                    ->imageWidth(200)
                    ->imageHeight(200)
                    ->square()
                    ->url(fn ($state) => asset('storage/' . $state))
                    ->openUrlInNewTab(),
                TextEntry::make('pied_page')
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('status')
                    ->boolean(),
                // TextEntry::make('user_id')
                //     ->numeric(),
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
            ->recordTitleAttribute('nom')
            ->columns([
                TextColumn::make('nom')
                    ->label('Nom Entreprise')
                    ->searchable(),
                TextColumn::make('adresse')
                    ->label('Adresse')
                    ->searchable(),
                TextColumn::make('telephone')
                    ->label('Téléphone')
                    ->searchable(),
                TextColumn::make('contact')
                    ->label('Contact')
                    ->searchable(),
                TextColumn::make('ville')
                    ->label('Ville')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                // TextColumn::make('site_web')
                //     ->searchable(),
                IconColumn::make('status')
                    ->boolean(),
                // TextColumn::make('user_id')
                //     ->numeric()
                //     ->sortable(),
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

                    // ExportBulkAction::make()
                    //     ->exporter(EmployeeExporter::class)
                    //     ->formats([
                    //             ExportFormat::Xlsx,
                    //             ExportFormat::Csv,
                    //         ]),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageEntreprises::route('/'),
        ];
    }
}
