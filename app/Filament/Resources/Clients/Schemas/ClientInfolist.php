<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ClientInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('code_client')
                    ->placeholder('-'),
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
}
