<?php

namespace App\Filament\Resources\Fournisseurs;

use App\Filament\Exports\FournisseurExporter;
use App\Filament\Resources\Fournisseurs\Pages\ManageFournisseurs;
use App\Models\Fournisseur;
use BackedEnum;
use Filament\Actions\BulkAction;
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
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
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
        Wizard::make([

    Step::make('INFO FOURNISSEUR')
        ->icon(Heroicon::ShoppingBag)
        ->schema([
            Select::make('type')
                ->label('Type de fournisseur')
                ->options([
                    'Éleveur'      => 'Éleveur',
                    'Grossiste'    => 'Grossiste',
                    'Transporteur' => 'Transporteur',
                    'Prestataire'  => 'Prestataire',
                    'Autre'        => 'Autre',
                ])
                ->required(),

            TextInput::make('nom')
                ->label('Nom du Fournisseur')
                ->required(),

            TextInput::make('telephone')
                ->label('Numéro de Téléphone')
                ->tel(), // Optionnel: format téléphone

            TextInput::make('email')
                ->label('Adresse Email')
                ->email(),

            TextInput::make('contact')
                ->label('Numéro Professionnel'),
                
            Select::make('quartier')
                ->label('Quartier')
                ->relationship('quartier', 'nom_quartier')
                ->getOptionLabelFromRecordUsing(
                    fn ($record) => "{$record->commune?->ville?->nom_ville} - {$record->commune?->nom_commune} - {$record->nom_quartier}"
                )
                ->searchable()
                ->preload()
                ->required(),

            TextInput::make('adresse')
                ->label('Adresse Physique'),
           
            TextInput::make('password')
                ->label('Mot de passe')
                ->password() // Masque les caractères tapés
                ->revealable(), // Permet à l'utilisateur de voir le mot de passe

            Hidden::make('user_id')
                ->default(fn () => Auth::id()),

            FileUpload::make('piece_fournisseur')
                ->label("Pièce d'identité (CNI / Passeport / Autre)")
                ->disk('public')
                ->directory('fournisseurs/documents')
                ->visibility('public')
                ->image()
                ->maxSize(1024)
                ->imagePreviewHeight('150')
                ->enableDownload()
                ->enableOpen()
                ->openable()
                ->previewable()
                ->required()
                ->columnSpanFull(), 

        ])->columns(3), 

    Step::make('INFORMATION FERME')
        ->icon(Heroicon::ShoppingBag)
        ->schema([
            TextInput::make('nom_ferme')
                ->label('Nom de la Ferme')
                ->required(),

            Select::make('type_produit')
                ->label('Type de Produit')
                ->options([
                    'OEUF'          => 'OEUF',
                    'POULET CHAIRE' => 'POULET CHAIRE',
                    'PONDEUSES'     => 'PONDEUSES',
                    'HYBRIDE'       => 'HYBRIDE',
                    'Autre'         => 'Autre',
                ])
                ->multiple(),

            

            TextInput::make('nom_gerant')
                ->label('Nom du Gérant de la Ferme')
                ->required(),

            TextInput::make('capacite_ferme')
                ->label('Capacité de la ferme')
                ->numeric() // Optionnel: force un nombre
                ->required(),

            TextInput::make('latitude')
                ->label('Latitude GPS')
                ->numeric(),

            TextInput::make('longitude')
                ->label('Longitude GPS')
                ->numeric(),

            Toggle::make('disponible')
                ->label('Ferme Disponible')
                ->default(true),

            Textarea::make('description')
                ->label('Description de la Ferme')
                ->columnSpanFull(),

            // --- Fichiers (Mis en bas sur toute la largeur ou répartis) ---
            
            FileUpload::make('image')
                ->label('Photo du Gérant')
                ->disk('public')
                ->directory('fournisseurs/profils')
                ->visibility('public')
                ->image()
                ->maxSize(1024)
                ->imagePreviewHeight('150')
                ->enableDownload()
                ->enableOpen()
                ->openable()
                ->previewable(),

            FileUpload::make('image_ferme')
                ->label('Photo de la Ferme')
                ->disk('public')
                ->directory('fournisseurs/fermes')
                ->visibility('public')
                ->image()
                ->maxSize(1024)
                ->imagePreviewHeight('150')
                ->enableDownload()
                ->enableOpen()
                ->openable()
                ->previewable(),

            FileUpload::make('certification_sanitaire')
                ->label("Certifications Sanitaires")
                ->disk('public')
                ->directory('Fournisseur')
                ->visibility('public')
                ->image()
                ->maxSize(1024)
                ->imagePreviewHeight('150')
                ->enableDownload()
                ->enableOpen()
                ->openable()
                ->previewable(),

        ])->columns(3), 

        ])->submitAction(new HtmlString('<button type="submit" class="fi-btn">ENREGISTRER</button>'))
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
                TextEntry::make('statut')
                    ->placeholder('-'),
                TextEntry::make('type_produit')
                    ->placeholder('-'),
                TextEntry::make('nom_gerant')
                    ->placeholder('-'),
                TextEntry::make('nom_ferme')
                    ->placeholder('-'),
                TextEntry::make('capacite_ferme')
                    ->placeholder('-'),
                IconEntry::make('etat')
                    ->boolean()
                    ->placeholder('-'),
                IconEntry::make('disponible')
                    ->boolean()
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
                ImageEntry::make('certification_sanitaire')
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

                RepeatableEntry::make('statutFournisseur')
                    ->label("Statut Fournisseur")
                    ->columnSpanFull()
                     ->table([
                            TableColumn::make('STATUT'),
                            TableColumn::make('MONTANT'),
                            TableColumn::make('MOTIF'),
                            TableColumn::make('UTILISATEUR'),
                            TableColumn::make('DATE'),
                        ])
                    ->schema([
                        TextEntry::make('statut'),
                        TextEntry::make('montant'),
                        TextEntry::make('motif'),
                        TextEntry::make('user.name'),
                        TextEntry::make('created_at')->dateTime('d-m-Y H:i'),
                    ])
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
                IconColumn::make('etat')
                    ->boolean()
                    ->sortable(),
                IconColumn::make('disponible')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('compte')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                    'ACTIF' => 'success',
                    'EN_ATTENTE' => 'primary',
                    'BLOQUE' => 'danger',
                    'REJETE' => 'warning',
                })
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

                        BulkAction::make('MiseAJourStatutfournisseur')
                        ->label('Mise à jour du statut')
                        ->icon('heroicon-m-arrow-down-tray')
                        ->color('info')
                        ->modalHeading('Mettre à jour le statut des fournisseurs sélectionnés')
                        ->requiresConfirmation()
                       ->modalWidth(Width::ExtraLarge)
                        ->form([
                            Section::make('Détails de la mise à jour')->schema([
                                Select::make('statut')
                                    ->label('Nouveau Statut')
                                    ->options([
                                        'EN_ATTENTE' => 'EN_ATTENTE',
                                        'ACTIF'      => 'ACTIF',
                                        'BLOQUE'     => 'BLOQUE',
                                        'REJETE'     => 'REJETE',
                                    ])
                                    ->required()
                                    ->live(), 

                                TextInput::make('montant')
                                    ->label('Montant (Pénalité / Caution)')
                                    ->numeric()
                                    ->prefix('CFA')
                                    ->nullable(),

                                Textarea::make('motif')
                                    ->label('Motif (Obligatoire si Bloqué ou Rejeté)')
                                    ->required(fn ($get) => in_array($get('statut'), ['BLOQUE', 'REJETE']))
                                    ->columnSpanFull(),
                            ])->columns(2),
                        ])
                        ->action(function (Collection $records, array $data) {
                            try {
                                // Appel de la logique métier située dans le Model fournisseur
                                foreach ($records as $record) {
                                    $record->mise_a_jour_statut($data);
                                }

                                Notification::make()
                                    ->title('Statuts mis à jour avec succès')
                                    ->body(count($records) . ' fournisseur(s) modifié(s).')
                                    ->success()
                                    ->send();

                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('Erreur lors de la mise à jour')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        })
            ->deselectRecordsAfterCompletion(),

    ])
                
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageFournisseurs::route('/'),
        ];
    }
}
