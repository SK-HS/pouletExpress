<?php

namespace App\Filament\Resources\FournisseurSoldes\Pages;

use App\Filament\Resources\FournisseurSoldes\Widgets\CommissionFournisseurWidgets;
use App\Filament\Resources\FournisseurSoldes\FournisseurSoldeResource;
use App\Models\Fournisseur;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Resources\Pages\ManageRecords;

class ManageFournisseurSoldes extends ManageRecords
{
    protected static string $resource = FournisseurSoldeResource::class;
        use HasFiltersForm;
    protected function getHeaderActions(): array
    {
        return [
             FilterAction::make()
                ->schema([
                    DatePicker::make('startDate'),
                    DatePicker::make('endDate'),
                    Select::make('fournisseur_id')
                        ->label('Fournisseur')
                        ->placeholder('Tous les fournisseurs')
                        ->options(Fournisseur::pluck('nom', 'id'))
                        ->searchable(),
                ]),
                
            CreateAction::make(),
        ];
    }

      protected function getHeaderWidgets(): array
            {
                return [
                    CommissionFournisseurWidgets::class,
                ];
            }
}
