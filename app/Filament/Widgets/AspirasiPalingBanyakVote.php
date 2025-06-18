<?php

namespace App\Filament\Widgets;

use App\Models\Aspirasi;
use App\Models\Topik;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class AspirasiPalingBanyakVote extends BaseWidget
{
    protected static bool $isLazy = false;
    
    protected function getCards(): array
    {
        $aspirasiPopuler = Aspirasi::withCount('votes')
            ->orderByDesc('votes_count')
            ->first();
            
        $totalAspirasi = Aspirasi::count();

        $topikPopuler = Aspirasi::select('topik_id')
            ->selectRaw('COUNT(*) as jumlah')
            ->groupBy('topik_id')
            ->orderByDesc('jumlah')
            ->with('topik') // pastikan relasi ke model Topik
            ->first();

        return [
            Card::make('Total Aspirasi Masuk', $totalAspirasi)
                ->description('Seluruh aspirasi yang diterima')
                ->color('primary')
                ->icon('heroicon-o-inbox'),
                
            Card::make('Aspirasi Terpopuler', $aspirasiPopuler?->judul ?? 'Tidak ada')
                ->description($aspirasiPopuler ? $aspirasiPopuler->votes_count . ' Vote' : 'Belum ada aspirasi')
                ->color('success')
                ->icon('heroicon-o-fire'),
            
            Card::make('Topik Terpopuler', $topikPopuler?->topik->nama ?? 'Tidak ada')
                ->description($topikPopuler ? $topikPopuler->jumlah . ' Aspirasi' : 'Belum ada data')
                ->color('warning')
                ->icon('heroicon-o-rectangle-group'),
        ];
    }
}