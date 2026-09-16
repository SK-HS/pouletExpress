<?php

namespace App\Filament\Resources\DemandeRetraits\Widgets;

use App\Models\DemandeRetrait;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Database\Eloquent\Builder;

class DemandeRetraitWidget extends StatsOverviewWidget
{
     use InteractsWithPageFilters;
     

     protected function getHeading(): string
    {
        return 'ETAT DES DEMANDES DE RERAITS';
    }
    
    protected function getStats(): array
    {
        $startDate = $this->pageFilters['startDate'] ?? null;
        $endDate = $this->pageFilters['endDate'] ?? null;
        $fournisseurId = $this->pageFilters['fournisseur_id'] ?? null;

        $chartData = Trend::model(DemandeRetrait::class)
                ->between(
                    start: now()->subDays(6)->startOfDay(),
                    end: now()->endOfDay(),
                )
                
                ->perDay()
                ->count()
                ->map(fn (TrendValue $value) => $value->aggregate)
                ->toArray();

        $nombredemande = DemandeRetrait::query()
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->count();
        $demandeAttente = DemandeRetrait::query()
                    ->where('statut', "EN_ATTENTE")
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->count();
        $demandeTraite = DemandeRetrait::query()
                    ->where('statut', "TRAITEE")
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->count();
        $demandeRejete = DemandeRetrait::query()
                    ->where('statut', "REJETEE")
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->count();

        return [
            Stat::make('MONTANT TOTAL DEMANDE', DemandeRetrait::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                         ->sum('montant'))
                        ->description("Nombre Demande :" . $nombredemande)
                        ->descriptionIcon('heroicon-m-arrow-trending-up')
                        ->chart($chartData)
                        ->color('success'),

             Stat::make('TOTAL DEMANDE EN ATTENTE', DemandeRetrait::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->where('statut', "EN_ATTENTE")
                        ->sum('montant'))
                        ->description("Nombre En Attente :". $demandeAttente)
                        ->descriptionIcon('heroicon-m-arrow-trending-up')
                        ->chart($chartData)
                        ->color('primary'),
             Stat::make('TOTAL DEMANDE TRAITEE  ', DemandeRetrait::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->where('statut', "TRAITEE")
                        ->sum('montant'))
                        ->description("Nombre Traité :". $demandeTraite)
                        ->descriptionIcon('heroicon-m-arrow-trending-up')
                        ->chart($chartData)
                        ->color('success'),
             Stat::make('TOTAL DEMANDE REJETEE  ', DemandeRetrait::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->where('statut', "REJETEE")
                        ->sum('montant'))
                        ->description("Nombre Rejeté :". $demandeRejete)
                        ->descriptionIcon('heroicon-m-arrow-trending-down')
                        ->chart($chartData)
                        ->color('danger'),
        ];
    }
}
