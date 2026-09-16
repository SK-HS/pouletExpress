<?php

namespace App\Filament\Resources\CommandeClients\Pages;

use App\Filament\Resources\CommandeClientChart\Widgets\CommandeClientChart;
use App\Filament\Resources\CommandeClientFournisseurChart\Widgets\CommandeClientFournisseurChart;
use App\Filament\Resources\CommandeClients\CommandeClientResource;
use App\Filament\Resources\CommandeClients\Widgets\CommandeClientPie;
use App\Filament\Resources\CommandeClients\Widgets\CommandeClientWidget;
use App\Filament\Resources\RepartitionCommandeClientChart\Widgets\RepartitionCommandeClientChart;
use App\Models\Fournisseur;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Resources\Pages\ListRecords;

class ListCommandeClients extends ListRecords
{
    protected static string $resource = CommandeClientResource::class;

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
                    CommandeClientWidget::class,
                    CommandeClientChart::class,
                    CommandeClientPie::class,
                    CommandeClientFournisseurChart::class,
                    RepartitionCommandeClientChart::class,
                ];
            }

}
