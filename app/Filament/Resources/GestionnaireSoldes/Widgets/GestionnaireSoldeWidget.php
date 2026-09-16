<?php

namespace App\Filament\Widgets;

use App\Models\GestionnaireSolde;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Database\Eloquent\Builder;

class GestionnaireSoldeWidget extends StatsOverviewWidget
{
use InteractsWithPageFilters;

protected function getHeading(): string
{
return 'ETAT DES SOLDES DES GESTIONNAIRES';
}



protected function getStats(): array
{
        $startDate = $this->pageFilters['startDate'] ?? null;
        $endDate = $this->pageFilters['endDate'] ?? null;


        $chartData = Trend::model(GestionnaireSolde::class)
                ->between(
                    start: now()->subDays(6)->startOfDay(),
                    end: now()->endOfDay(),
                )
                
                ->perDay()
                ->count()
                ->map(fn (TrendValue $value) => $value->aggregate)
                ->toArray();

        $nombredemande = GestionnaireSolde::query()
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->count();
        $soldeAttente = GestionnaireSolde::query()
                    ->where('statut', "EN_ATTENTE")
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->count();
        $soldePaye = GestionnaireSolde::query()
                    ->where('statut', "PAYE")
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->count();
        $soldeDisponible = GestionnaireSolde::query()
                    ->where('statut', "DISPONIBLE")
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->count();
                    
        return [
            Stat::make('MONTANT TOTAL SOLDE', GestionnaireSolde::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                         ->sum('montant'))
                        ->description("Nombre Demande :" . $nombredemande)
                        ->descriptionIcon('heroicon-m-arrow-trending-up')
                        ->chart($chartData)
                        ->color('info'),

             Stat::make('SOLDE EN ATTENTE', GestionnaireSolde::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->where('statut', "EN_ATTENTE")
                        ->sum('montant'))
                        ->description("Nombre En Attente :". $soldeAttente)
                        ->descriptionIcon('heroicon-m-arrow-trending-up')
                        ->chart($chartData)
                        ->color('primary'),
             Stat::make('SOLDE DISPONIBLE  ', GestionnaireSolde::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->where('statut', "DISPONIBLE")
                        ->sum('montant'))
                        ->description("Nombre Disponible :". $soldeDisponible)
                        ->descriptionIcon('heroicon-m-arrow-trending-up')
                        ->chart($chartData)
                        ->color('success'),
             Stat::make('TOTAL SOLDE PAYE  ', GestionnaireSolde::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->where('statut', "PAYE")
                        ->sum('montant'))
                        ->description("Nombre Payé :". $soldePaye)
                        ->descriptionIcon('heroicon-m-arrow-trending-down')
                        ->chart($chartData)
                        ->color('success'),
        ];
    }
}
