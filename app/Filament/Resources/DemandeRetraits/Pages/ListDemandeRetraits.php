<?php

namespace App\Filament\Resources\DemandeRetraits\Pages;

use App\Filament\Resources\DemandeRetraits\DemandeRetraitResource;
use App\Filament\Resources\DemandeRetraits\Widgets\DemandeRetraitWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class ListDemandeRetraits extends ListRecords
{
    protected static string $resource = DemandeRetraitResource::class;
    use HasFiltersForm;

    protected function getHeaderActions(): array
    {
        return [

        FilterAction::make()
                ->schema([
                    DatePicker::make('startDate'),
                    DatePicker::make('endDate'),
                    // Select::make('fournisseur_id')
                    //     ->label('Fournisseur')
                    //     ->placeholder('Tous les fournisseurs')
                    //     ->options(Fournisseur::pluck('nom', 'id'))
                    //     ->searchable(),
                ]),
            CreateAction::make(),
        ];

        }

        protected function getHeaderWidgets(): array
           {
               return [
                   DemandeRetraitWidget::class,
               ];
           }
}
