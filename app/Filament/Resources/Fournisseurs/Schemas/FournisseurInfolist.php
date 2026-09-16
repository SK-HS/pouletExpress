<?php

namespace App\Filament\Resources\Fournisseurs\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class FournisseurInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('reference')
                    ->placeholder('-'),
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
}
