<?php

namespace App\Filament\Pages;

use App\Filament\Resources\CommandeClientChart\Widgets\CommandeClientChart;
use App\Filament\Resources\CommandeClientFournisseurChart\Widgets\CommandeClientFournisseurChart;
use App\Filament\Resources\CommandeClients\Widgets\CommandeClientPie;
use App\Filament\Resources\CommandeClients\Widgets\CommandeClientWidget;
use App\Filament\Resources\CommandeLivreurs\Widgets\EtatCommandeWidget;
use App\Filament\Resources\DemandeRetraits\Widgets\DemandeRetraitWidget;
use App\Filament\Resources\RepartitionCommandeClientChart\Widgets\RepartitionCommandeClientChart;
use App\Filament\Widgets\FournisseurSoldeWidget;
use App\Filament\Widgets\GestionnaireSoldeWidget;
use App\Filament\Widgets\LivreurSoldeWidget;
use App\Models\Fournisseur;
use App\Models\Livreur;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class Dashboard extends Page
{
    protected string $view = 'filament.pages.dashboard';

    use HasFiltersForm;

    public function filtersForm(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('period')
                ->label('Période')
                ->options([
                    'today'    => "Aujourd'hui",
                    'week'     => 'Cette semaine',
                    'month'    => 'Ce mois-ci',
                    'year'     => 'Cette année',
                    'lastyear' => "L'année dernière",
                ])
                ->default('year'),
            Select::make('fournisseur_id')
                ->label('Fournisseur')
                ->placeholder('Tous les fournisseurs')
                ->options(Fournisseur::pluck('nom', 'id'))
                ->searchable(),
        ])->columns(2);
    }

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
                    Select::make('Livreur_id')
                        ->label('Livreur')
                        ->placeholder('Tous les Livreurs')
                        ->options(Livreur::pluck('nom', 'id'))
                        ->searchable(),
                    
                ]),
                 Action::make('clearFilters')
                        ->label('Effacer les filtres')
                        ->icon('heroicon-m-x-circle')
                        ->color('gray')
                        ->action(function () {
                            if (property_exists($this, 'filters')) {
                                $this->filters = [];
                            }
                            if (method_exists($this, 'resetTableFilters')) {
                                $this->resetTableFilters();
                            }
                        }),
        ];
    }

     protected function getHeaderWidgets(): array
            {
                return [
                    EtatCommandeWidget::class,
                    CommandeClientWidget::class,
                    FournisseurSoldeWidget::class,
                    LivreurSoldeWidget::class,
                    GestionnaireSoldeWidget::class,
                    DemandeRetraitWidget::class,
                    CommandeClientChart::class,
                    CommandeClientPie::class,
                    CommandeClientFournisseurChart::class,
                    RepartitionCommandeClientChart::class,
                ];
            }

}

