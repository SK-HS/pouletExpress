<?php

namespace App\Filament\Resources\CommandeClients\Widgets;

use App\Models\CommandeClient;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class CommandeClientGraph extends ChartWidget
{
    protected ?string $heading = 'Gaphe des Commande Client par Mois';
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
                $interval = 'perHour',
                $dateFormat = 'H:i',
            ],
            'week' => [
                $start = now()->startOfWeek(),
                $end = now()->endOfWeek(),
                $interval = 'perDay',
                $dateFormat = 'D d',
            ],
            'month' => [
                $start = now()->startOfMonth(),
                $end = now()->endOfMonth(),
                $interval = 'perDay',
                $dateFormat = 'd M',
            ],
            'lastyear' => [
                $start = now()->subYear()->startOfYear(),
                $end = now()->subYear()->endOfYear(),
                $interval = 'perMonth',
                $dateFormat = 'M',
            ],
            default => [ // 'year'
                $start = now()->startOfYear(),
                $end = now()->endOfYear(),
                $interval = 'perMonth',
                $dateFormat = 'M',
            ],
        };

         $trend = Trend::model(CommandeClient::class)
            ->between(start: $start, end: $end);

        $sums = match ($interval) {
            'perHour' => $trend->perHour()->sum('montant_ttc'),
            'perDay'  => $trend->perDay()->sum('montant_ttc'),
            default   => $trend->perMonth()->sum('montant_ttc'),
        };
        $counts = match ($interval) {
            'perHour' => $trend->perHour()->count(),
            'perDay'  => $trend->perDay()->count(),
            default   => $trend->perMonth()->count(),
        };

    // $counts = Trend::model(CommandeClient::class)
    //     ->between(start: $start, end: $end)
    //     ->perMonth()
    //     ->count();
    // $sums = Trend::model(CommandeClient::class)
    //     ->between(start: $start, end: $end)
    //     ->perMonth()
    //     ->sum('montant_ttc');

    return [
        'datasets' => [
            [
                'label' => 'Nombre de commandes',
                'data' => $counts->map(fn (TrendValue $value) => $value->aggregate),
            ],
            [
                'label' => 'Montant TTC',
                'data' => $sums->map(fn (TrendValue $value) => $value->aggregate),
                'backgroundColor' => '#10b981', // Vert pour la série 2
                'borderColor' => '#059669',
            ],
        ],
    //    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
     'labels' => $counts->map(fn (TrendValue $value) => \Carbon\Carbon::parse($value->date)->translatedFormat($dateFormat)),
    ];

    }

    protected function getType(): string
    {
        return 'bar';
    }
}
