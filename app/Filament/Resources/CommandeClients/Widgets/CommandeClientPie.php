<?php

namespace App\Filament\Resources\CommandeClients\Widgets;

use App\Models\Categorie;
use App\Models\CommandeClient;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;

class CommandeClientPie extends ChartWidget
{
    protected ?string $heading = ' Graphe des Repartitions des Produits Vendues Par Categories';

     use HasFiltersSchema;

    protected int | string | array $columnSpan = 'full';
     protected ?string $maxHeight = '300px';
    protected bool $hasDeferredFilters = true;
    protected bool $isCollapsible = true;

    public function filtersSchema(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('annee')
                        ->numeric()
                        ->length(4)
                        ->minValue(2020)
                        ->maxValue(date('Y') + 5)
                        ->placeholder('YYYY')
                        ->default(date('Y')),
            // DatePicker::make('endDate')
            //     ->default(now()),
            Select::make('categorie_id')
                    ->options(Categorie::query()->pluck('nom', 'id'))
                    ->multiple()
                     ->searchable()
                    ->label('CATEGORIE'),
        ]);
    }
    
    protected function getData(): array
    {
        $categorie = CommandeClient::query()
                        ->join('detail_commande_clients', 'commande_clients.id', '=', 'detail_commande_clients.commande_client_id')
                        ->join('produit_fournisseurs', 'detail_commande_clients.produit_fournisseur_id', '=', 'produit_fournisseurs.id')
                        ->join('categories', 'produit_fournisseurs.categorie_id', '=', 'categories.id')
                        ->selectRaw('
                            categories.nom as categorie,
                            SUM(montant) as montant_total,
                            SUM(detail_commande_clients.quantite) as total_quantite
                        ')
                        ->when(
                            $this->filters['annee'] ?? null,
                            fn ($query) => $query->whereYear('commande_clients.created_at', $this->filters['annee'])
                        )
                        ->when(
                            $this->filters['categorie_id'] ?? null,
                            fn ($query) => $query->whereIn('categories.id', $this->filters['categorie_id'])
                        )
                        ->groupBy('categories.id', 'categories.nom')
                        ->orderBy('categories.nom')
                        ->get();

    return [
        'datasets' => [
            [
                'label' => "Répartition du chiffre d'affaires",
                'data' => $categorie->pluck('montant_total')->toArray(),
                'backgroundColor' => [
                    '#3b82f6',
                    '#10b981',
                    '#f59e0b',
                    '#ef4444',
                    '#8b5cf6',
                    '#06b6d4',
                    '#84cc16',
                    '#ec4899',
                ],
            ],
        ],

        'labels' => $categorie->map(
            fn ($item) => "{$item->categorie} ({$item->total_quantite} vendus)"
        )->toArray(),
    ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
    
}
