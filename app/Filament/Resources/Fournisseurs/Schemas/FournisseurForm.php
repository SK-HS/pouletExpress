<?php

namespace App\Filament\Resources\Fournisseurs\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class FournisseurForm
{
    public static function configure(Schema $schema): Schema
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
}
