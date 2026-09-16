<?php

namespace App\Filament\Widgets;

use App\Models\FournisseurSolde;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Database\Eloquent\Builder;

class FournisseurSoldeWidget extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

     protected function getHeading(): string
    {
        return 'ETAT DES SOLDES DES FOURNISSEURS';
    }



    protected function getStats(): array
    {
        $startDate = $this->pageFilters['startDate'] ?? null;
        $endDate = $this->pageFilters['endDate'] ?? null;
        $fournisseurId = $this->pageFilters['fournisseur_id'] ?? null;


        $chartData = Trend::model(FournisseurSolde::class)
                ->between(
                    start: now()->subDays(6)->startOfDay(),
                    end: now()->endOfDay(),
                )
                
                ->perDay()
                ->count()
                ->map(fn (TrendValue $value) => $value->aggregate)
                ->toArray();

        $nombredemande = FournisseurSolde::query()
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->when($fournisseurId, fn (Builder $query) => $query->where('fournisseur_id', $fournisseurId))
                    ->count();
        $soldeAttente = FournisseurSolde::query()
                    ->where('statut', "EN_ATTENTE")
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->when($fournisseurId, fn (Builder $query) => $query->where('fournisseur_id', $fournisseurId))
                    ->count();
        $soldePaye = FournisseurSolde::query()
                    ->where('statut', "PAYE")
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->count();
        $soldeDisponible = FournisseurSolde::query()
                    ->where('statut', "DISPONIBLE")
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->when($fournisseurId, fn (Builder $query) => $query->where('fournisseur_id', $fournisseurId))
                    ->count();
                    
        return [
            Stat::make('MONTANT TOTAL SOLDE', FournisseurSolde::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->when($fournisseurId, fn (Builder $query) => $query->where('fournisseur_id', $fournisseurId))
                         ->sum('montant'))
                        ->description("Nombre Demande :" . $nombredemande)
                        ->descriptionIcon('heroicon-m-arrow-trending-up')
                        ->chart($chartData)
                        ->color('info'),

             Stat::make('SOLDE EN ATTENTE', FournisseurSolde::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->when($fournisseurId, fn (Builder $query) => $query->where('fournisseur_id', $fournisseurId))
                        ->where('statut', "EN_ATTENTE")
                        ->sum('montant'))
                        ->description("Nombre En Attente :". $soldeAttente)
                        ->descriptionIcon('heroicon-m-arrow-trending-up')
                        ->chart($chartData)
                        ->color('primary'),
             Stat::make('SOLDE DISPONIBLE  ', FournisseurSolde::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->when($fournisseurId, fn (Builder $query) => $query->where('fournisseur_id', $fournisseurId))
                        ->where('statut', "DISPONIBLE")
                        ->sum('montant'))
                        ->description("Nombre Disponible :". $soldeDisponible)
                        ->descriptionIcon('heroicon-m-arrow-trending-up')
                        ->chart($chartData)
                        ->color('success'),
             Stat::make('TOTAL SOLDE PAYE  ', FournisseurSolde::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->when($fournisseurId, fn (Builder $query) => $query->where('fournisseur_id', $fournisseurId))
                        ->where('statut', "PAYE")
                        ->sum('montant'))
                        ->description("Nombre Payé :". $soldePaye)
                        ->descriptionIcon('heroicon-m-arrow-trending-down')
                        ->chart($chartData)
                        ->color('success'),
        ];
    }
}
