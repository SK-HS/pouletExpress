<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class ClientForm
{
    public static function configure(Schema $schema): Schema
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
}
