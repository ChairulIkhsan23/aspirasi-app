<?php

namespace App\Filament\Widgets;

use Filament\Widgets\LineChartWidget;
use App\Models\Aspirasi;

class StatistikAspirasiPerStatusLine extends LineChartWidget
{
    protected static ?string $heading = 'Statistik Aspirasi per Status (Line Chart)';
    
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
                    'borderColor' => '#3b82f6',
                    'fill' => false,
                    'tension' => 0.4,
                ],
            ],
            'labels' => array_keys($data),
        ];
    }
}
