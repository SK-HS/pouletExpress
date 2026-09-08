<?php

namespace App\Filament\Resources\GestionnaireSoldes\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GestionnaireSoldeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                MorphToSelect::make('debiteur')
                    ->types([
                        MorphToSelect\Type::make(\App\Models\Livreur::class)
                            ->getOptionLabelFromRecordUsing(fn (\App\Models\Livreur $record): string => "{$record->nom} - {$record->telephone}")
                            ->titleAttribute('Livreur'),
                        MorphToSelect\Type::make(\App\Models\Fournisseur::class)
                        ->getOptionLabelFromRecordUsing(fn (\App\Models\Fournisseur $record): string => "{$record->nom} - {$record->telephone}")
                            ->titleAttribute('Fournisseur'),
                    ])
                    ->searchable()
                    ->preload()
                    ->required(),
                // TextInput::make('commande_client_id')
                //     ->numeric(),
                 Select::make('commande_client_id')
                    ->relationship('commandeClient', 'id')
                     ->getOptionLabelFromRecordUsing(
                            fn ($record) => "{$record->reference}- ({$record->quartier?->nom_quartier})"
                        )
                    ->default(request()->query('commande_client_id'))
                    ->label('Commande')
                    ->disabled() 
                   ->dehydrated()
                    ->required(),
                TextInput::make('montant')
                    ->required()
                    ->numeric(),
                Select::make('statut')
                    ->options([
                    'EN_ATTENTE' => 'EN_ATTENTE',
                    'DISPONIBLE' => 'DISPONIBLE',
                    'PAYE' => 'PAYE',
                ])
                ->required(),
                DateTimePicker::make('disponible_le'),
                DateTimePicker::make('paye_le'),
                Textarea::make('details')
                    ->columnSpanFull(),
            ]);
    }
}
