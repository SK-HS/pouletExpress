<?php

namespace App\Filament\Resources\CommandeClients\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CommandeClientInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('reference')
                    ->placeholder('-'),
                TextEntry::make('fournisseur.nom')
                    ->label('Fournisseur')
                    ->formatStateUsing(function ($state, $record) {
                        return "Nom : {$record->fournisseur->nom} -Contact : {$record->fournisseur?->telephone} - Adresse : {$record->fournisseur->adresse}";
                    })
                    ->placeholder('-'),
                TextEntry::make('client.nom'),
                TextEntry::make('livreur.nom')
                    ->placeholder('-'),
                TextEntry::make('montant_brut')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('remise')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('montant_hors_taxe')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('tva')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('montant_ttc')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('avance')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('solde')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('statut')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('date_commande')
                    ->dateTime(),
                TextEntry::make('user.name'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->since()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    
                    ->placeholder('-'),

                RepeatableEntry::make('detailCommandeClients')
                    ->label("Détails Bon Commande")
                    ->columnSpanFull()
                     ->table([
                            TableColumn::make('PRODUIT'),
                            TableColumn::make('QTE COMMANDE'),
                            TableColumn::make('PRIX'),
                            TableColumn::make('TOTAL'),
                        ])
                    
                    ->schema([
                        TextEntry::make('id')
                            ->label('Désignation')
                            ->formatStateUsing(function ($state, $record) {
                                if ($record->type === "PRODUIT" && $record->produitFournisseur) {
                                    return "{$record->produitFournisseur->produit?->nom} {$record->produitFournisseur->categorie?->nom} ({$record->produitFournisseur->taille?->taille})";
                                }

                                if ($record->type === "SERVICE" && $record->service) {
                                    return "{$record->service->designation}";
                                }

                                return '-';
                            }),
                        TextEntry::make('quantite')->label('Quantité'),
                        TextEntry::make('prix_unitaire')->label('Prix unitaire'),
                        TextEntry::make('montant')->label('Total'),
                    ])
                    ->columns(5),

                RepeatableEntry::make('statutCommande')
                    ->label("Etapes Bon Commande")
                    ->columnSpanFull()
                     ->table([
                            TableColumn::make('DATE'),
                            TableColumn::make('STATUT'),
                            TableColumn::make('OPERATEUR'),
                            TableColumn::make('OPERATEUR NOM'),
                        ])
                    ->schema([
                        TextEntry::make('created_at')
                                ->dateTime('j M ,Y à H:i:s'),
                        TextEntry::make('statut')
                         ->badge() 
                        ->color(fn ($state) => match($state) {
                            'RECEPTIONNEE'  => 'success',
                            'LIVREE_PAR_FOURNISSEUR'    => 'success',
                            'EN_ROUTE'      => 'info',
                            'RECUPEREE'     => 'warning',
                            'AFFECTEE'      => 'warning',
                            'EN_ATTENTE'    => 'gray',
                            default         => 'gray',
                        }),
                        TextEntry::make('type'),
                        TextEntry::make('typeId')
                            ->label('Opérateur')
                            ->formatStateUsing(function ($state, $record) {
                                return match($record->type) {
                                  'CLIENT' => (function() use ($record) {
                                        $p = \App\Models\Client::find($record->typeId);
                                      return $p ? "{$p->nom} - ({$p->telephone})" : 'Client introuvable'; })(),
                                  'LIVREUR' => (function() use ($record) {
                                        $p = \App\Models\Livreur::find($record->typeId);
                                      return $p ? "{$p->nom} - ({$p->telephone})" : 'Livreur introuvable'; })(),

                                    'FOURNISSEUR' => (function() use ($record) {
                                        $p = \App\Models\Fournisseur::find($record->typeId);
                                        return $p ? "{$p->nom} - ({$p->telephone})" : 'Fournisseur introuvable';  })(),

                                
                                    'ADMINISTRATEUR' => \App\Models\User::find($record->typeId)?->name ?? 'Admin introuvable',
                                      default          => $record->type,
                                };
                            }),

                    ])
                    ->columns(2)
            ]);
    }
}
