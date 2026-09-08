<?php

namespace App\Filament\Resources\DemandeRetraits\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class DemandeRetraitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                MorphToSelect::make('beneficiaire')
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
                // TextInput::make('beneficiaire_id')
                //     ->required()
                //     ->numeric(),
                TextInput::make('montant')
                    ->required()
                    ->numeric(),
                // TextInput::make('mode_paiement'),
                TextInput::make('numero_paiement'),
                DateTimePicker::make('date_traitee'),
                Select::make('mode_paiement')
                    ->options([
                        'espece' => 'Espèce',
                        'wave' => 'Wave',
                        'mobile' => 'Mobile Money',
                        ])
                    ->required(),
                Select::make('statut')
                    ->options([
                            'EN_ATTENTE' => 'EN_ATTENTE',
                            'TRAITEE' => 'TRAITEE',
                            'REJETEE' => 'REJETEE',
                        ])
                    ->required(),
                Hidden::make('user_id')
                    ->default(fn () => Auth::id()),
            ]);
    }
}
