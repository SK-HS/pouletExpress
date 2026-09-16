<?php

namespace App\Filament\Resources\CommandeClientFournisseurChart\Widgets;

use App\Models\CommandeClient;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class CommandeClientFournisseurChart extends ChartWidget
{
    protected ?string $heading = 'Gaphe des Repartitions des Produits Commandés par Fournisseurs';
    protected int | string | array $columnSpan = 'full';
    protected ?string $maxHeight = '300px';
    protected bool $isCollapsible = true;
    public ?string $filter = 'year';
     use InteractsWithPageFilters; 
     
        protected function getFilters(): ?array
    {
         return [
            'today'    => "Aujourd'hui",
            'week'     => 'Cette semaine',
            'month'    => 'Ce mois-ci',
            'year'     => 'Cette année',
            'lastyear' => "L'année dernière",
        ];

    }
   protected function getData(): array
{
     $activeFilter = $this->filter;
        
    match ($activeFilter) {
            'today' => [
                $start = now()->startOfDay(),
                $end = now()->endOfDay(),
            ],
            'week' => [
                $start = now()->startOfWeek(),
                $end = now()->endOfWeek(),
            ],
            'month' => [
                $start = now()->startOfMonth(),
                $end = now()->endOfMonth(),
            ],
            'lastyear' => [
                $start = now()->subYear()->startOfYear(),
                $end = now()->subYear()->endOfYear(),
            ],
            default => [ // 'year'
                $start = now()->startOfYear(),
                $end = now()->endOfYear(),
            ],
        };

    // 1. Récupérer tous les fournisseurs triés par CA décroissant
    $ventes = CommandeClient::query()
        ->join('detail_commande_clients', 'commande_clients.id', '=', 'detail_commande_clients.commande_client_id')
        ->join('produit_fournisseurs', 'detail_commande_clients.produit_fournisseur_id', '=', 'produit_fournisseurs.id')
        ->join('fournisseurs', 'produit_fournisseurs.fournisseur_id', '=', 'fournisseurs.id')
        ->selectRaw('fournisseurs.nom,
         SUM(detail_commande_clients.montant) as total,
         SUM(detail_commande_clients.quantite) as nombre_ventes'
           )
        ->whereBetween('commande_clients.created_at', [$start, $end])
        ->groupBy('fournisseurs.id', 'fournisseurs.nom')
        ->orderByDesc('total')
        ->get();

        $top10 = $ventes->take(10);
        $autres = $ventes->slice(10);
        
        $labels = $top10->pluck('nom')->toArray();
        $montants = $top10->pluck('total')->map(fn ($v) => (float) $v)->toArray();
        $quantites = $top10->pluck('nombre_ventes')->map(fn ($v) => (int) $v)->toArray();
       
        if ($autres->count() > 0) {
            $labels[] = 'Autres (' . $autres->count() . ' fournisseurs)';
            $montants[] = (float) $autres->sum('total');
            $quantites[] = (int) $autres->sum('nombre_ventes');
        }

    return [
        'datasets' => [
            [
                'label' => 'Ventes (FCFA)',
                'data' => $montants,
                 'backgroundColor' => '#10b981', // Vert
                    'borderRadius' => 4,
            ],
            [
                'label' => 'Quantité vendue',
                'data' => $quantites,
                'backgroundColor' => '#3b82f6',
                'borderRadius' => 4,
            ],
        ],
        'labels' => $labels,
    ];
}



    protected function getType(): string
    {
        return 'bar';
    }
}
