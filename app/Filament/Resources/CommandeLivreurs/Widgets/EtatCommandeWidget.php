<?php

namespace App\Filament\Resources\CommandeLivreurs\Widgets;

use App\Models\CommandeLivreur;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Database\Eloquent\Builder;

class EtatCommandeWidget extends StatsOverviewWidget
{
     use InteractsWithPageFilters;
     

     protected function getHeading(): string
    {
        return 'ETAT DES LIVRAISONS DE COMMANDES PAR LIVREURS';
    }

     public function persistsFiltersInSession(): bool
    {
        return false;
    }
    
    protected function getStats(): array
    {
        $startDate = $this->pageFilters['startDate'] ?? null;
        $endDate = $this->pageFilters['endDate'] ?? null;
        $livreurId = $this->pageFilters['Livreur_id'] ?? null;

        $chartData = Trend::model(CommandeLivreur::class)
                ->between(
                    start: now()->subDays(6)->startOfDay(),
                    end: now()->endOfDay(),
                )
                
                ->perDay()
                ->count()
                ->map(fn (TrendValue $value) => $value->aggregate)
                ->toArray();

        $nombredemande = CommandeLivreur::query()
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->when($livreurId, fn (Builder $query) => $query->where('livreur_id', $livreurId))
                    ->count();
        $demandeAttente = CommandeLivreur::query()
                    ->where('statut', "EN_ATTENTE")
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->when($livreurId, fn (Builder $query) => $query->where('livreur_id', $livreurId))
                    ->count();
        $demandeAffecte = CommandeLivreur::query()
                    ->where('statut', "AFFECTEE")
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->when($livreurId, fn (Builder $query) => $query->where('livreur_id', $livreurId))
                    ->count();
        $demandeRecupere = CommandeLivreur::query()
                    ->where('statut', "RECUPEREE")
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->when($livreurId, fn (Builder $query) => $query->where('livreur_id', $livreurId))
                    ->count();
        $demandeEnroute = CommandeLivreur::query()
                    ->where('statut', "EN_ROUTE")
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->when($livreurId, fn (Builder $query) => $query->where('livreur_id', $livreurId))
                    ->count();
        $demandeLivre = CommandeLivreur::query()
                    ->where('statut', "LIVREE")
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->when($livreurId, fn (Builder $query) => $query->where('livreur_id', $livreurId))
                    ->count();

        return [
            Stat::make('MONTANT TOTAL DEMANDE', $nombredemande)
                        ->description("Nombre Demande :" . $nombredemande)
                        ->descriptionIcon('heroicon-m-arrow-trending-up')
                        ->chart($chartData)
                        ->color('success'),

             Stat::make('TOTAL DEMANDE EN ATTENTE', $demandeAttente)
                        ->description("Nombre En Attente :". $demandeAttente)
                        ->descriptionIcon('heroicon-m-arrow-trending-up')
                        ->chart($chartData)
                        ->color('danger'),
             Stat::make('TOTAL DEMANDE AFFECTEE  ', $demandeAffecte)
                        ->description("Nombre Affectée :". $demandeAffecte)
                        ->descriptionIcon('heroicon-m-arrow-trending-up')
                        ->chart($chartData)
                        ->color('primary'),
             Stat::make('TOTAL DEMANDE RECUPEREE  ', $demandeRecupere)
                        ->description("Nombre Recuperée :". $demandeRecupere)
                        ->descriptionIcon('heroicon-m-arrow-trending-down')
                        ->chart($chartData)
                        ->color('info'),
             Stat::make('TOTAL DEMANDE EN ROUTE  ', $demandeEnroute)
                        ->description("Nombre En Route :". $demandeEnroute)
                        ->descriptionIcon('heroicon-m-arrow-trending-down')
                        ->chart($chartData)
                        ->color('info'),
             Stat::make('TOTAL DEMANDE LIVREE  ', $demandeLivre)
                        ->description("Nombre Livrée :". $demandeLivre)
                        ->descriptionIcon('heroicon-m-arrow-trending-up')
                        ->chart($chartData)
                        ->color('success'),
        ];
    }
}
