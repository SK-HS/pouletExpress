<?php

namespace App\Filament\Resources\Livreurs\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class LivreurForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                Select::make('categorie')
                    ->label('Categorie du livreur')
                    ->options([
                        'STANDARD' => 'STANDARD',
                        'VIP' => 'VIP',
                        'VVIP' => 'VVIP',
                    ])->default("STANDARD"),
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
                    ->previewable()
                    ->maxSize(10240),
                TextInput::make('compte')
                    ->numeric()
                    ->default(0),
            ]);
    }
}
