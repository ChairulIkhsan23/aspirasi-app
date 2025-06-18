<?php

namespace App\Filament\Widgets;

use Filament\Widgets\BarChartWidget;
use App\Models\Aspirasi;

class StatistikAspirasiPerStatusBar extends BarChartWidget
{
    protected static ?string $heading = 'Statistik Aspirasi per Status';

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
                    'label' => 'Jumlah Aspirasi',
                    'data' => array_values($data),
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => array_keys($data),
        ];
    }
}
