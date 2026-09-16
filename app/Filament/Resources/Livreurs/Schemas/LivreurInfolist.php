<?php

namespace App\Filament\Resources\Livreurs\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LivreurInfolist
{
    public static function configure(Schema $schema): Schema
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
                TextEntry::make('categorie')
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
                IconEntry::make('etat')
                    ->boolean()
                    ->placeholder('-'),
                TextEntry::make('statut')
                    ->placeholder('-'),

                RepeatableEntry::make('statutLivreurs')
                    ->label("Statut Livreur")
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
