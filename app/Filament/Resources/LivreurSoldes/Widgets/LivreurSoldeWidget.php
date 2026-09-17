<?php

namespace App\Filament\Resources\LivreurSoldes\Widgets;

use App\Models\LivreurSolde;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Database\Eloquent\Builder;

class LivreurSoldeWidget extends StatsOverviewWidget
{
use InteractsWithPageFilters;

protected function getHeading(): string
{
return 'ETAT DES SOLDES DES LIVREURS';
}



protected function getStats(): array
{
$startDate = $this->pageFilters['startDate'] ?? null;
$endDate = $this->pageFilters['endDate'] ?? null;
$livreurId = $this->pageFilters['livreur_id'] ?? null;


        $chartData = Trend::model(LivreurSolde::class)
                ->between(
                    start: now()->subDays(6)->startOfDay(),
                    end: now()->endOfDay(),
                )
                
                ->perDay()
                ->count()
                ->map(fn (TrendValue $value) => $value->aggregate)
                ->toArray();

        $nombredemande = LivreurSolde::query()
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->when($livreurId, fn (Builder $query) => $query->where('livreur_id', $livreurId))
                    ->count();
        $soldeAttente = LivreurSolde::query()
                    ->where('statut', "EN_ATTENTE")
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->when($livreurId, fn (Builder $query) => $query->where('livreur_id', $livreurId))
                    ->count();
        $soldePaye = LivreurSolde::query()
                    ->where('statut', "PAYE")
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->count();
        $soldeDisponible = LivreurSolde::query()
                    ->where('statut', "DISPONIBLE")
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->when($livreurId, fn (Builder $query) => $query->where('livreur_id', $livreurId))
                    ->count();
                    
        return [
            Stat::make('MONTANT TOTAL SOLDE', LivreurSolde::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->when($livreurId, fn (Builder $query) => $query->where('livreur_id', $livreurId))
                         ->sum('montant'))
                        ->description("Nombre Demande :" . $nombredemande)
                        ->descriptionIcon('heroicon-m-arrow-trending-up')
                        ->chart($chartData)
                        ->color('info'),

             Stat::make('SOLDE EN ATTENTE', LivreurSolde::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->when($livreurId, fn (Builder $query) => $query->where('livreur_id', $livreurId))
                        ->where('statut', "EN_ATTENTE")
                        ->sum('montant'))
                        ->description("Nombre En Attente :". $soldeAttente)
                        ->descriptionIcon('heroicon-m-arrow-trending-up')
                        ->chart($chartData)
                        ->color('primary'),
             Stat::make('SOLDE DISPONIBLE  ', LivreurSolde::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->when($livreurId, fn (Builder $query) => $query->where('livreur_id', $livreurId))
                        ->where('statut', "DISPONIBLE")
                        ->sum('montant'))
                        ->description("Nombre Disponible :". $soldeDisponible)
                        ->descriptionIcon('heroicon-m-arrow-trending-up')
                        ->chart($chartData)
                        ->color('success'),
             Stat::make('TOTAL SOLDE PAYE  ', LivreurSolde::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->when($livreurId, fn (Builder $query) => $query->where('livreur_id', $livreurId))
                        ->where('statut', "PAYE")
                        ->sum('montant'))
                        ->description("Nombre Payé :". $soldePaye)
                        ->descriptionIcon('heroicon-m-arrow-trending-down')
                        ->chart($chartData)
                        ->color('success'),
        ];
    }
}
