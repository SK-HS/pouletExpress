<?php

namespace App\Filament\Resources\GestionnaireSoldes\Pages;

use App\Filament\Resources\GestionnaireSoldes\GestionnaireSoldeResource;
use App\Filament\Widgets\GestionnaireSoldeWidget;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Resources\Pages\ListRecords;

class ListGestionnaireSoldes extends ListRecords
{
    protected static string $resource = GestionnaireSoldeResource::class;
    
    use HasFiltersForm;

    protected function getHeaderActions(): array
    {
        return [

            FilterAction::make()
                ->schema([
                    DatePicker::make('startDate'),
                    DatePicker::make('endDate'),
                ]),
            CreateAction::make(),
        ];
    }

       protected function getHeaderWidgets(): array
            {
                return [
                    GestionnaireSoldeWidget::class,
                ];
            }
}
