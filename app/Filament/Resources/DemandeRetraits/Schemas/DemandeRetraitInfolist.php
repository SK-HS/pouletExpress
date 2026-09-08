<?php

namespace App\Filament\Resources\DemandeRetraits\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DemandeRetraitInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextEntry::make('beneficiaire_type'),
                // TextEntry::make('beneficiaire_id')
                //     ->numeric(),

                TextEntry::make('beneficiaire')
                    ->label('Bénéficiaire')
                    ->getStateUsing(function ($record) {
                        if (!$record->beneficiaire) {
                            return 'Aucun';
                        }
                        $type = class_basename($record->beneficiaire_type); 
                        return "{$record->beneficiaire->nom} - {$record->beneficiaire->telephone} ({$type})";
                    })
                    ->icon('heroicon-m-user') 
                    ->weight('bold'),

                TextEntry::make('montant')
                    ->numeric()
                    ->suffix(' FCFA'),
                TextEntry::make('mode_paiement')
                    ->placeholder('-'),
                TextEntry::make('numero_paiement')
                    ->placeholder('-'),
                TextEntry::make('date_traitee')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('statut')
                    ->badge(),
                TextEntry::make('user.name')
                    ->label('utilisateur')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
