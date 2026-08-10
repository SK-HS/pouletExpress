<?php

namespace App\Filament\Resources\Fournisseurs;

use App\Filament\Exports\FournisseurExporter;
use App\Filament\Resources\Fournisseurs\Pages\ManageFournisseurs;
use App\Models\Fournisseur;
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
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class FournisseurResource extends Resource
{
    protected static ?string $model = Fournisseur::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'GESTION FOURNISSEURS';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->label('Type de fournisseur')
                    ->options([
                        'Éleveur' => 'Éleveur',
                        'Grossiste' => 'Grossiste',
                        'Transporteur' => 'Transporteur',
                        'Prestataire' => 'Prestataire',
                        'Autre' => 'Autre',
                    ]),
                Select::make('type_produit')
                    ->label('Type de Produit')
                    ->options([
                        'OEUF' => 'OEUF',
                        'POULET CHAIRE' => 'POULET CHAIRE',
                        'PONDEUSES' => 'PONSEUSES',
                        'HYBRIDE' => 'HYBRIDE',
                        'Autre' => 'Autre',
                    ])
                    ->multiple(),
                TextInput::make('nom')
                    ->required()
                    ->label('Nom Fournisseur'),
                TextInput::make('nom_ferme')
                    ->required()
                    ->label('Nom Ferme'),
                TextInput::make('nom_gerant')
                    ->required()
                    ->label('Nom Gerant Ferme'),
                TextInput::make('capacite_ferme')
                    ->required()
                    ->label('Capacité ferme'),
                TextInput::make('adresse'),
                TextInput::make('telephone')
                   ->label('Numéro Téléphone'),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('contact')
                    ->label('Numéro Professionel'),
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
                    ->directory('Fournisseur')
                    ->visibility('public')
                    ->image()
                    ->imagePreviewHeight('150')
                    ->enableDownload()
                    ->enableOpen()
                    ->openable()
                    ->previewable(),
                FileUpload::make('image_ferme')
                    ->label('Image')
                    ->disk('public')
                    ->directory('Fournisseur')
                    ->visibility('public')
                    ->image()
                    ->imagePreviewHeight('150')
                    ->enableDownload()
                    ->enableOpen()
                    ->openable()
                    ->previewable(),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                
                TextEntry::make('nom'),
                TextEntry::make('adresse')
                    ->placeholder('-'),
                TextEntry::make('telephone')
                    ->label('Téléphone')
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->label('Email address')
                    ->placeholder('-'),
                TextEntry::make('contact')
                    ->placeholder('-'),
                TextEntry::make('compte')
                    ->placeholder('-'),
                TextEntry::make('type_produit')
                    ->placeholder('-'),
                TextEntry::make('nom_gerant')
                    ->placeholder('-'),
                TextEntry::make('nom_ferme')
                    ->placeholder('-'),
                TextEntry::make('capacite_ferme')
                    ->placeholder('-'),
                TextEntry::make('etat')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->placeholder('-'),
                    
                ImageEntry::make('image')
                    ->disk('public')
                    ->imageWidth(200)
                    ->imageHeight(200)
                    ->square()
                    ->url(fn ($state) => asset('storage/' . $state))
                    ->openUrlInNewTab(),
                ImageEntry::make('image_ferme')
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nom')
            ->columns([
                TextColumn::make('type'),
                TextColumn::make('nom')
                    ->searchable(),
                TextColumn::make('adresse')
                    ->searchable(),
                TextColumn::make('telephone')
                    ->label('Téléphone')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('contact')
                    ->searchable(),
                TextColumn::make('etat')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('compte')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('quartier.nom_quartier'),
               TextColumn::make('user.name')
                    ->label('Utilisateur')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('entreprise.nom')
                    ->label('Entreprise')
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

                    // ExportBulkAction::make()
                    //     ->exporter(FournisseurExporter::class)
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
            'index' => ManageFournisseurs::route('/'),
        ];
    }
}
