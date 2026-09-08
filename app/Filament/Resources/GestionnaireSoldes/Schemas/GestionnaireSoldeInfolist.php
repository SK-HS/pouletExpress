<?php

namespace App\Filament\Resources\GestionnaireSoldes\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class GestionnaireSoldeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('debiteur')
                    ->label('Débiteur')
                    ->getStateUsing(function ($record) {
                        if (!$record->debiteur) {
                            return 'Aucun';
                        }
                        $type = class_basename($record->debiteur_type); 
                        return "{$record->debiteur->nom} - {$record->debiteur->telephone} ({$type})";
                    })
                    ->icon('heroicon-m-user') 
                    ->weight('bold'),
                TextEntry::make('commandeClient.reference')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('montant')
                    ->numeric()
                    ->suffix(' FCFA'),
                TextEntry::make('statut')
                    ->badge(),
                TextEntry::make('disponible_le')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('paye_le')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('details')
                    ->placeholder('-')
                    ->columnSpanFull(),
            ]);
    }
}
