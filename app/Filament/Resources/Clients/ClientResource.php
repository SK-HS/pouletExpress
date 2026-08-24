<?php

namespace App\Filament\Resources\Clients;

use App\Filament\Exports\ClientExporter;
use App\Filament\Resources\Clients\Pages\ManageClients;
use App\Models\Client;
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
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use UnitEnum;


class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

        protected static string | UnitEnum | null $navigationGroup = 'GESTION VENTES';
    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                
                Select::make('type')
                    ->label('Type de client')
                    ->options([
                        'Particulier' => 'Particulier',
                        'Entreprise' => 'Entreprise',
                        'Restaurant' => 'Restaurant',
                        'Hôtel' => 'Hôtel',
                        'Supermarché' => 'Supermarché',
                        'Boutique' => 'Boutique',
                        'Grossiste' => 'Grossiste',
                        'Revendeur' => 'Revendeur',
                        'Administration' => 'Administration',
                        'Association' => 'Association',
                        'École' => 'École',
                        'Clinique' => 'Clinique',
                        'Autre' => 'Autre',
                    ])
                    ->preload()
                    ->searchable(),
                TextInput::make('nom')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('telephone')
                    ->label('Téléphone'),
                // TextInput::make('ville'),
                TextInput::make('adresse'),
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
                    ->directory('Client')
                    ->visibility('public')
                    ->image()
                    ->imagePreviewHeight('150')
                    ->enableDownload()
                    ->enableOpen()
                    ->openable()
                    ->previewable(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nom'),
                TextEntry::make('email')
                    ->label('Email address')
                    ->placeholder('-'),
                TextEntry::make('telephone')
                    ->label('Téléphone')
                    ->placeholder('-'),
                TextEntry::make('ville.nom_ville')
                    ->placeholder('-'),
                TextEntry::make('adresse')
                    ->placeholder('-'),
                TextEntry::make('etat')
                    ->placeholder('-'),
                TextEntry::make('statut')
                    ->placeholder('-'),
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
            ->recordTitleAttribute('nom')
            ->columns([
                TextColumn::make('nom')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('telephone')
                    ->searchable()
                    ->label('Téléphone'),
                TextColumn::make('ville.nom_ville')
                    ->searchable(),
                TextColumn::make('adresse')
                    ->searchable(),
                TextColumn::make('code_client')
                    ->searchable(),
                IconColumn::make('etat')
                    ->boolean(),
                TextColumn::make('statut')
                      ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'ACTIF' => 'success',
                    'EN_ATTENTE' => 'primary',
                    'BLOQUE' => 'danger',
                    'REJETE' => 'warning',
                }),
                TextColumn::make('user.name')
                    ->label('Utilisateur')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('agence.nom')
                //     ->label('Agence')
                //     ->searchable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('entreprise.nom')
                    ->label('Entreprise')
                    ->searchable()
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

                //  ExportBulkAction::make()
                //         ->exporter(ClientExporter::class)
                //         ->formats([
                //                 ExportFormat::Xlsx,
                //                 ExportFormat::Csv,
                //             ]),
                            
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageClients::route('/'),
        ];
    }
}
