<?php

namespace App\Filament\Resources\ProduitFournisseurs\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class ProduitFournisseurForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                
                Select::make('fournisseur_id')
                    ->relationship('fournisseur', 'nom')
                    ->label('Fournisseur')
                    ->searchable()
                    ->preload()
                    ->required(),
                 
                Select::make('produit_id')
                    ->label('Produit')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->options(function (callable $get) {

                        return \App\Models\Produit::get()
                            ->mapWithKeys(fn ($r) => [
                                $r->id =>
                                    "{$r->code_barre} - {$r->nom}"
                            ])
                            ->toArray();}),
                Select::make('categorie_id')
                    ->relationship('categorie', 'nom')
                    ->searchable()
                    ->preload()
                    ->label('Catégorie')
                    ->required(),
                Select::make('taille_id')
                    ->relationship('taille', 'taille')
                    ->label('Poids')
                    ->searchable()
                    ->preload(),
                TextInput::make('quantite')
                    ->numeric(),
                TextInput::make('commande_min')
                    ->numeric()
                    ->label('Quantite minimum à commande'),
                TextInput::make('prix')
                    ->numeric()
                    ->label('Prix Unitaire'),
                // Toggle::make('statuts')
                //     ->required(),
                
               FileUpload::make('images')
                    ->label('Images')
                    ->disk('public')
                    ->directory('Produits')
                    ->visibility('public')
                    ->image()
                    ->multiple()
                    ->panelLayout('grid')
                    ->imagePreviewHeight('150')
                    ->openable()
                    ->previewable()
                    ->reorderable()
                    ->appendFiles(),
                
                Textarea::make('description')
                    ->columnSpanFull(),
                Hidden::make('user_id')
                    ->default(fn () => Auth::id()),
            ]);
    }
}
