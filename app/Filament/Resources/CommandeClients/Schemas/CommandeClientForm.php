<?php

namespace App\Filament\Resources\CommandeClients\Schemas;

use App\Models\Client;
use App\Models\Fournisseur;
use App\Models\Livreur;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class CommandeClientForm
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
                        ->getOptionLabelFromRecordUsing(fn (Fournisseur $record) => "{$record->nom}-{$record->type}-{$record->quartier?->nom_quartier}-{$record->adresse}-({$record->telephone})"),
                Select::make('client_id')
                        ->label('Client')
                        ->relationship('client', 'nom')
                        ->searchable()
                        ->required()
                        ->preload()
                        ->getOptionLabelFromRecordUsing(fn (Client $record) => "{$record->nom}-{$record->type}-{$record->quartier?->nom_quartier}-{$record->adresse}-({$record->telephone})")
                        ->createOptionForm([
                    Select::make('type')
                        ->label('Type de client')
                        ->options([
                            'Particulier' => 'Particulier',
                            'Entreprise' => 'Entreprise',
                            'Restaurant' => 'Restaurant',
                            'Hôtel' => 'Hôtel',
                            'Supermarché' => 'Supermarché',
                            'Boutique' => 'Boutique',
                            'Grossiste' => 'Grossiste',
                            'Revendeur' => 'Revendeur',
                            'Administration' => 'Administration',
                            'Association' => 'Association',
                            'École' => 'École',
                            'Clinique' => 'Clinique',
                            'Autre' => 'Autre',
                        ])
                        ->preload()
                        ->searchable(),
                    TextInput::make('nom')
                        ->required(),
                    TextInput::make('email')
                        ->label('Email address')
                        ->email(),
                    TextInput::make('telephone')
                        ->label('Téléphone'),
                    // TextInput::make('ville'),
                    TextInput::make('adresse'),
                    Hidden::make('user_id')
                        ->default(fn () => Auth::id()),
                    Select::make('ville_id')
                        ->relationship('ville', 'nom')
                        ->label('Ville')
                         ->getOptionLabelFromRecordUsing(
                            fn ($record) => "{$record->quartier}-{$record->commune} - {$record->nom}"
                        )
                        ->searchable()
                        ->preload()
                        ->required(),
                    FileUpload::make('image')
                        ->label('Image')
                        ->disk('public')
                        ->directory('Client')
                        ->visibility('public')
                        ->image()
                        ->imagePreviewHeight('150')
                        ->enableDownload()
                        ->enableOpen()
                        ->openable()
                        ->previewable(),
                            ])
                        ->createOptionUsing(function (array $data): int {
                            return Client::create($data)->getKey();
                        })
                        ->editOptionForm([
                    Select::make('type')
                        ->label('Type de client')
                        ->options([
                            'Particulier' => 'Particulier',
                            'Entreprise' => 'Entreprise',
                            'Restaurant' => 'Restaurant',
                            'Hôtel' => 'Hôtel',
                            'Supermarché' => 'Supermarché',
                            'Boutique' => 'Boutique',
                            'Grossiste' => 'Grossiste',
                            'Revendeur' => 'Revendeur',
                            'Administration' => 'Administration',
                            'Association' => 'Association',
                            'École' => 'École',
                            'Clinique' => 'Clinique',
                            'Autre' => 'Autre',
                        ])
                        ->preload()
                        ->searchable(),
                    TextInput::make('nom')
                        ->required(),
                    TextInput::make('email')
                        ->label('Email address')
                        ->email(),
                    TextInput::make('telephone')
                        ->label('Téléphone'),
                    // TextInput::make('ville'),
                    TextInput::make('adresse'),
                    Hidden::make('user_id')
                        ->default(fn () => Auth::id()),
                    Select::make('ville_id')
                        ->relationship('ville', 'nom')
                        ->label('Ville')
                        ->searchable()
                        ->preload()
                        ->required(),
                    FileUpload::make('image')
                        ->label('Image')
                        ->disk('public')
                        ->directory('Client')
                        ->visibility('public')
                        ->image()
                        ->imagePreviewHeight('150')
                        ->enableDownload()
                        ->enableOpen()
                        ->openable()
                        ->previewable(),
                            ])
                            ->updateOptionUsing(function (array $data, Schema $schema) {
                                $schema->getRecord()?->update($data);
                            }),
                
               
                    Select::make('livreur_id')
                        ->label('Livreur')
                        ->relationship('livreur', 'nom')
                        ->searchable()
                        ->preload()
                        ->getOptionLabelFromRecordUsing(fn (Livreur $record) => "{$record->nom}-{$record->type}-{$record->quartier?->nom_quartier}-{$record->adresse}-({$record->telephone})"),
                TextInput::make('montant_brut')
                    ->numeric()
                    ->default(0)
                    ->readOnly(),
                TextInput::make('remise')
                    ->numeric()
                    ->default(0),
                TextInput::make('montant_hors_taxe')
                    ->numeric()
                    ->default(0)
                    ->readOnly(),
                TextInput::make('tva')
                    ->numeric()
                    ->default(0),
                TextInput::make('montant_ttc')
                    ->numeric()
                    ->default(0)
                    ->readOnly(),
                TextInput::make('avance')
                    ->numeric()
                    ->default(0)
                    ->readOnly(),
                TextInput::make('solde')
                    ->numeric()
                    ->default(0)
                    ->readOnly(),
                
                DateTimePicker::make('date_commande')
                        ->columnSpan(1)
                        ->default(now())
                        ->required(),
                Hidden::make('user_id')
                        ->default(fn () => Auth::id()),
            
            Repeater::make('detailCommandeClients')
                        ->relationship()
                        ->schema([

                            Select::make('type')
                                    ->label('Type')
                                    ->options([
                                        'PRODUIT' => 'Produit',
                                        'SERVICE' => 'Service',
                                    ])
                                    ->required()
                                     ->columnSpan(1)
                                    ->reactive(),

                            Select::make('item_id')
                                    ->label(fn (callable $get) =>
                                        $get('type') === 'SERVICE' ? 'Service' : 'Produit'
                                    )
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(2)
                                     ->afterStateHydrated(function ($state, callable $set, callable $get, $record) {

                                        if ($get('type') === 'SERVICE') {
                                            $set('item_id', $record?->service_id);
                                        } else {
                                            $set('item_id', $record?->produit_fournisseur_id);
                                        }
                                    })
                                    ->options(function (callable $get) {

                                        if ($get('type') === 'SERVICE') {
                                            return \App\Models\Service::get()
                                                ->mapWithKeys(fn ($s) => [
                                                    $s->id => "{$s->designation} ({$s->prix} XOF)"
                                                ])
                                                ->toArray();
                                           
                                        }

                                        // return \App\Models\produitFourniss::where('agence_id', $get('../../agence_id'))
                                        //     ->with(['produit.categorie', 'produit.couleur', 'produit.taille'])
                                        //     ->get()
                                        //     ->mapWithKeys(fn ($r) => [
                                        //         $r->produit_id =>
                                        //             "{$r->produit->code_barre} - {$r->produit->nom_produit} - {$r->produit->categorie?->nom_categorie} - {$r->produit->couleur?->nom_couleur} - {$r->produit->taille?->nom_taille} ({$r->produit->prix_vente} XOF)"
                                        //     ])
                                        //     ->toArray();
                                                 $fournisseur = $get('../../fournisseur_id');
                                        return \App\Models\ProduitFournisseur::with(['produit', 'fournisseur'])
                                            ->where('fournisseur_id', $fournisseur)
                                            ->get()
                                            ->mapWithKeys(fn ($r) => [
                                                $r->id =>
                                                    "{$r->produit?->code_barre} - {$r->produit?->nom} - {$r->categorie?->nom} - {$r->taille?->taille} ({$r->prix} XOF)"
                                            ])
                                            ->toArray();
                                          
                                    })
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get)  {

                                        if (! $state) return;

                                        if ($get('type') === 'SERVICE') {
                                            $service = \App\Models\Service::find($state);
                                            $prix = $service?->prix ?? 0;
                                            $set('stock', null);
                                             $set('service_id', $state);
                                            $set('produit_fournisseur_id', null);
                                        } else {
                                            // $produitFourniss = \App\Models\produitFourniss::with('produit')
                                            //     ->where('produit_id', $state)
                                            //     ->where('agence_id', $get('../../agence_id'))
                                            //     ->first();

                                            $produitFourniss = \App\Models\ProduitFournisseur::with(['produit', 'fournisseur'])
                                                                ->where('id', $state)
                                                                ->first();

                                            $prix = $produitFourniss->prix ?? 0;
                                            $set('stock', $produitFourniss?->quantite ?? 0);
                                            $set('produit_fournisseur_id', $state);
                                            $set('service_id', null);
                                        }

                                        $set('prix_unitaire', $prix);

                                       self::calculTotaux($state, $set, $get);
                                    }),

                            Hidden::make('stock'),
                            Hidden::make('produit_fournisseur_id'),
                            Hidden::make('service_id'),
                            Hidden::make('user_id')
                                ->default(fn () => Auth::id()),

                            TextInput::make('quantite')
                                    ->numeric()
                                    ->default(0)
                                    ->live(onBlur: true)
                                    // ->reactive()
                                    ->minValue(1)
                                    // ->maxValue(fn (callable $get) => $get('stock')) 
                                    ->required()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $prix = floatval($get('prix_unitaire') ?? 0);
                                        $set('montant', $state * $prix);
                                          self::calculTotaux($state, $set, $get);
                                    }),


                            TextInput::make('prix_unitaire')
                                ->numeric()
                                ->live()
                                ->reactive()
                                ->readOnly()
                                ->required()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $qte = floatval($get('quantite') ?? 0);
                                    $set('montant', $qte * $state);
                                      self::calculTotaux($state, $set, $get);
                                }),


                            TextInput::make('montant')
                                ->numeric()
                                ->required()
                                ->readOnly()
                                ->reactive()
                                 ->live(),
                        ])
                        ->columns(4)
                        ->columnSpanFull()
                        ->live()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                             $details = $get('detailCommandeClients') ?? [];

                            $totalBrut = collect($details)->sum(fn ($item) => floatval($item['quantite'] ?? 0) * floatval($item['prix_unitaire'] ?? 0));
                            $set('montant_brut', round($totalBrut, 2));


                            self::calculTotaux($state, $set, $get);
                        }),


            Section::make('PAIEMENT')
                         ->schema([
                    Repeater::make('versement')
                        ->relationship('versement')
                        ->deletable(false)
                        ->schema([
                           DateTimePicker::make('date_paiement')
                        ->default(now())
                        ->required()
                        // ->columnSpan(1)
                        ,
                            TextInput::make('montant')
                                ->label('Montant')
                                ->numeric()
                                 ->reactive()
                                ->live(onBlur: true)
                                // ->columnSpan(1)
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    self::calculTotaux($state, $set, $get);
                                    }),
                            Select::make('mode_paiement')
                                ->label('Mode de Paiement')
                                // ->columnSpan(1)
                                ->options([
                                    'Especes' => 'Espèces',
                                    'Mobile Money' => 'Mobile Money',
                                    'Wave' => 'Wave',
                                    'Virement Bancaire' => 'Virement Bancaire',
                                    'Cheque' => 'Chèque',
                                    'Autre' => 'Autre',
                                    'Recouvrement' => 'Recouvrement',
                                ])
                                ->default('Especes')
                                 ->reactive()
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    self::calculTotaux($state, $set, $get);
                                    }),
                            // Select::make('caisse_id')
                            //          ->relationship('caisse', 'nom_caisse')
                            //         ->label('Caisse')
                            //         ->required()
                            //          ->preload()
                            //           ->options(function (callable $get) {
                            //             $agenceId = $get('../../agence_id'); // ou '../../agence_id' si en dehors du repeater

                            //             return \App\Models\Caisse::where('agence_id', $agenceId)
                            //                 ->pluck('nom_caisse', 'id');
                            //         }),
                            Textarea::make('detail')
                                 ->label('Detail Versement')
                                 ->columnSpan(1),
                            
                            Hidden::make('user_id')
                                ->default(fn () => Auth::id()),
                                
                    ])
                     ->columnSpan(4)
                      ->addActionLabel('Ajouter un versement')
                      ->live()
                        ->reactive()
                        ->default([]) // ← important pour éviter les erreurs si vide
                         ->dehydrated(true)
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            self::calculTotaux($state, $set, $get);
                        }),
                    ])->columnSpanFull(),
            
                    Radio::make('type_commande')
                        ->options([
                            'Avec Livraison' => 'Avec Livraison',
                            'Sans Livraison' => 'Sans Livraison',
                        ])
                          ->inline()
                          ->required()
                          ->live(),

                    Section::make('AJOUTER UN LIEU DE LIVRAISON')
                        ->visible(fn (Get $get) => $get('type_commande') === 'Avec Livraison')
                        ->schema([
                    Repeater::make('commandeLivreur')
                        ->relationship('commandeLivreur')
                        ->label("Lieu de livraison")
                        ->deletable(false)
                        ->schema([
                         Select::make('quartier')
                            ->relationship('quartier', 'nom_quartier')
                            ->getOptionLabelFromRecordUsing(
                                    fn ($record) => "{$record->commune->ville?->nom_ville}-{$record->commune?->nom_commune} - {$record->nom_quartier}"
                                )
                            ->label('Lieu de Livraison')
                            ->searchable()
                            ->preload()
                            ->required(),
                            Hidden::make('user_id')
                                ->default(fn () => Auth::id()),
                            Hidden::make('statut')
                                ->default("Recherche d'un livreur"),
                                
                    ])
                    //  ->columnSpan(4)
                    //   ->addActionLabel('Ajouter un versement')
                      ->live()
                        ->reactive()
                        ->default([]) // ← important pour éviter les erreurs si vide
                         ->dehydrated(true)
                    ])->columnSpanFull(),
            ]);
    }



public static function calculTotaux($state, callable $set, callable $get)
{
    // Total brut = somme des montants des lignes de vente
     $details = $get('detailCommandeClients') ?? [];
    $totalBrut = collect($details)->sum(fn ($item) => floatval($item['quantite'] ?? 0) * floatval($item['prix_unitaire'] ?? 0));
    $set('montant_brut', round($totalBrut, 2));
    // Remise
    $remise = floatval($get('remise') ?? 0);
    $totalHT = $totalBrut - $remise;
    $set('montant_hors_taxe', round($totalHT, 2));

    // TVA
    $tva = floatval($get('tva') ?? 0);
    $totalTTC = $totalHT + ($totalHT * $tva);
    $set('montant_ttc', round($totalTTC, 2));

    // Avance = somme des versements
    $versements = $get('versement');
    $avance = collect($versements)
        ->pluck('montant')
        ->filter(fn($v) => is_numeric($v))
        ->map(fn($v) => floatval($v))
        ->sum();
    $set('avance', round($avance, 2));

    // Solde = TTC - avance
    $set('solde', round($totalTTC - $avance, 2));
}




}

