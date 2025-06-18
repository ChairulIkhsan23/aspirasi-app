<?php

namespace App\Filament\Widgets;

use Filament\Widgets\PieChartWidget;
use App\Models\Aspirasi;

class StatistikAspirasiPerStatusPie extends PieChartWidget
{
    protected static ?string $heading = 'Statistik Aspirasi per Status (Pie)';
    
    protected static bool $isLazy = false;
    
    protected function getData(): array
    {
        $data = Aspirasi::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return [
            'datasets' => [
                [
                    'data' => array_values($data),
                    'backgroundColor' => ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'],
                ],
            ],
            'labels' => array_keys($data),
        ];
    }
}
