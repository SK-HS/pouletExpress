<?php

namespace App\Filament\Resources\ProduitFournisseurs\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProduitFournisseurInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('quantite')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('prix')
                    ->numeric()
                    ->placeholder('-'),
                IconEntry::make('statuts')
                    ->boolean(),
                ImageEntry::make('images')
                    ->label('Images')
                    ->disk('public')
                    ->visibility('public'),

                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('fournisseur.nom')
                        ->label('Fournisseur'),
                TextEntry::make('produit.nom')
                        ->label('Produit'),
                TextEntry::make('categorie.nom')
                        ->label('Categorie'),
                TextEntry::make('taille.taille')
                        ->label('Poids'),
                TextEntry::make('commande_min')
                        ->label('Commande Min'),
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
