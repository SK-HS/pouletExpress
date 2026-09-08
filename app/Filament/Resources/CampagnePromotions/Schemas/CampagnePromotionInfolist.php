<?php

namespace App\Filament\Resources\CampagnePromotions\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CampagnePromotionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('fournisseur.nom')
                    ->label('Fournisseur')
                    ->formatStateUsing(function ($state, $record) {
                        return "Nom : {$record->fournisseur->nom} -Contact : {$record->fournisseur?->telephone} - Adresse : {$record->fournisseur->adresse}";
                    })
                    ->placeholder('-'),
                TextEntry::make('type'),
                TextEntry::make('titre'),
                TextEntry::make('taux_remise')
                    ->numeric(),
                TextEntry::make('seuil_quantite')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('date_debut')
                    ->dateTime(),
                TextEntry::make('date_fin')
                    ->dateTime(),
                IconEntry::make('est_active')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                IconEntry::make('supprimer')
                    ->boolean(),

                RepeatableEntry::make('campagneProduit')
                    ->label("Détails Produit En Promotion")
                    ->columnSpanFull()
                     ->table([
                            TableColumn::make('PRODUIT'),
                            TableColumn::make('CATEGORIE'),
                            TableColumn::make('TAILLE'),
                            TableColumn::make('PRIX'),
                        ])
                    ->schema([
                        TextEntry::make('produitFournisseur.produit.nom'),
                        TextEntry::make('produitFournisseur.categorie.nom'),
                        TextEntry::make('produitFournisseur.taille.taille'),
                        TextEntry::make('produitFournisseur.prix'),
                    ])
                    ->columns(5),
            ]);
    }
}
 