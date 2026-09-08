<?php

namespace App\Filament\Resources\CampagnePromotions\Schemas;

use App\Models\Fournisseur;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class CampagnePromotionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('fournisseur_id')
                    ->label('Fournisseur')
                    ->relationship('fournisseur', 'nom')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->getOptionLabelFromRecordUsing(fn (Fournisseur $record) => "{$record->nom}-{$record->type}-{$record->quartier?->nom_quartier}-{$record->adresse}-({$record->telephone})"),
                Select::make('type')
                    ->options([
                        'TOUT'=>"TOUS LES PRODUITS (Boutique entière)",
                        'SPECIFIQUE'=>"PRODUITS SPÉCIFIQUES"
                    ])
                    ->reactive()
                    ->required(),
                TextInput::make('titre')
                    ->required(),
                TextInput::make('taux_remise')
                    ->required()
                    ->numeric(),
                TextInput::make('seuil_quantite')
                    ->numeric(),
                DateTimePicker::make('date_debut')
                    ->required(),
                DateTimePicker::make('date_fin')
                    ->required(),
                Toggle::make('est_active')
                    ->required(),

                    Repeater::make('campagneProduit')
                    ->label('Produits en promotion')
                    ->relationship()
                    ->schema([
                    // 1. Le menu déroulant pour choisir le produit
                    Select::make('produit_fournisseur_id')
                        ->label('Produit en promotion')
                        ->options(function (Get $get) {
                            $fournisseurId = $get('../../fournisseur_id');
                            
                            if (!$fournisseurId) {
                                return []; 
                            }
                            
                            return \App\Models\ProduitFournisseur::where('fournisseur_id', $fournisseurId)
                                ->get()
                                ->mapWithKeys(fn ($r) => [
                                    $r->id =>
                                        "{$r->produit?->code_barre} - {$r->produit?->nom} - {$r->categorie?->nom} - {$r->taille?->taille} ({$r->prix} XOF)"
                                ])
                                ->toArray();
                                })
                                ->searchable() 
                                ->required()
                                ->columnSpan(1),
                            Hidden::make('fournisseur_id')
                                ->default(fn (Get $get) => $get('../../fournisseur_id'))
                        ])
                        ->columnSpanFull()
                        // empêcher de sélectionner le même produit 2 fois dans le même repeater
                        ->distinct()
                        ->visible(fn (Get $get) => $get('type') === 'SPECIFIQUE'),
                    
                        ]);
    }
}
