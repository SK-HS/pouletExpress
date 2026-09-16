<?php

namespace App\Filament\Resources\CommandeClients\Widgets;

use App\Models\CommandeClient;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\Concerns\InteractsWithPageFilters;


class CommandeClientWidget extends StatsOverviewWidget
{
     use InteractsWithPageFilters;


     protected function getHeading(): string
    {
        return 'ETAT DES COMMANDES CLIENTS';
    }

    protected function getStats(): array
    {
        $startDate = $this->pageFilters['startDate'] ?? null;
        $endDate = $this->pageFilters['endDate'] ?? null;
        $fournisseurId = $this->pageFilters['fournisseur_id'] ?? null;

        $chartData = Trend::model(CommandeClient::class)
                ->between(
                    start: now()->subDays(6)->startOfDay(),
                    end: now()->endOfDay(),
                )
                
                ->perDay()
                ->count()
                ->map(fn (TrendValue $value) => $value->aggregate)
                ->toArray();

        $nombrecommande = CommandeClient::query()
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->when($fournisseurId, fn (Builder $query) => $query->where('fournisseur_id', $fournisseurId))
                    ->count();
        $commandeLivre = CommandeClient::query()
                    ->where('commande_livree', 1)
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->when($fournisseurId, fn (Builder $query) => $query->where('fournisseur_id', $fournisseurId))
                    ->count();
        $commandeEnCours = CommandeClient::query()
                    ->where('commande_livree', 0)
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->when($fournisseurId, fn (Builder $query) => $query->where('fournisseur_id', $fournisseurId))
                    ->count();

        return [

             Stat::make('MONTANT TOTAL COMMANDE', CommandeClient::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->when($fournisseurId, fn (Builder $query) => $query->where('fournisseur_id', $fournisseurId))
                         ->sum('montant_ttc'))
                        ->description("Nombre Total Commande :" . $nombrecommande)
                        ->descriptionIcon('heroicon-m-arrow-trending-up')
                        ->chart($chartData)
                        ->color('success'),

             Stat::make('TOTAL COMMANDE LIVREE ', CommandeClient::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->when($fournisseurId, fn (Builder $query) => $query->where('fournisseur_id', $fournisseurId))
                        ->where('commande_livree', 1)
                        ->sum('montant_ttc'))
                        ->description("Nombre Total Livrée :". $commandeLivre)
                        ->descriptionIcon('heroicon-m-arrow-trending-up')
                        ->chart($chartData)
                        ->color('success'),
             Stat::make('TOTAL COMMANDE EN COURS ', CommandeClient::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->when($fournisseurId, fn (Builder $query) => $query->where('fournisseur_id', $fournisseurId))
                        ->where('commande_livree', 0)
                        ->sum('montant_ttc'))
                        ->description("Nombre Total Non Livrée :". $commandeEnCours)
                        ->descriptionIcon('heroicon-m-arrow-trending-down')
                        ->chart($chartData)
                        ->color('danger'),

       
    
        ];
    }
}
