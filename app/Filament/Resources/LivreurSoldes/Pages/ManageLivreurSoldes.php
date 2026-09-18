<?php

namespace App\Filament\Resources\LivreurSoldes\Pages;

use App\Filament\Resources\LivreurSoldes\Widgets\LivreurSoldeWidget;
use App\Filament\Resources\LivreurSoldes\LivreurSoldeResource;
use App\Models\Livreur;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Resources\Pages\ManageRecords;

class ManageLivreurSoldes extends ManageRecords
{
    protected static string $resource = LivreurSoldeResource::class;
      use HasFiltersForm;

    protected function getHeaderActions(): array
    {
        return [
            FilterAction::make()
                ->schema([
                    DatePicker::make('startDate'),
                    DatePicker::make('endDate'),
                    Select::make('livreur_id')
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
                    LivreurSoldeWidget::class,
                ];
            }
}
