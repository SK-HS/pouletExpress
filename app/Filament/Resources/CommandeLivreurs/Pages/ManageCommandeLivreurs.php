<?php

namespace App\Filament\Resources\CommandeLivreurs\Pages;

use App\Filament\Resources\CommandeLivreurs\CommandeLivreurResource;
use App\Filament\Resources\CommandeLivreurs\Widgets\EtatCommandeWidget;
use App\Models\Livreur;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Resources\Pages\ManageRecords;

class ManageCommandeLivreurs extends ManageRecords
{
    protected static string $resource = CommandeLivreurResource::class;

      use HasFiltersForm;

    protected function getHeaderActions(): array
    {
        return [
            FilterAction::make()
                ->schema([
                    DatePicker::make('startDate'),
                    DatePicker::make('endDate'),
                    Select::make('Livreur_id')
                        ->label('Livreur')
                        ->placeholder('Tous les Livreurs')
                        ->options(Livreur::pluck('nom', 'id'))
                        ->searchable(),
                ]),
                
            CreateAction::make(),
        ];
    }
    

    protected function getHeaderWidgets(): array
        {
            return [
                EtatCommandeWidget::class,
            ];
        }
}
