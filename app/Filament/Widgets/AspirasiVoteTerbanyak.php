<?php

namespace App\Filament\Widgets;

use App\Models\Aspirasi;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class AspirasiVoteTerbanyak extends BaseWidget
{
    protected static ?string $heading = 'Aspirasi dengan Vote Terbanyak';

    protected static bool $isLazy = false;

    protected function getTableQuery(): Builder
    {
        return Aspirasi::withCount('votes')
            ->having('votes_count', '>=', 10);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('judul')->label('Judul'),
            Tables\Columns\TextColumn::make('votes_count')->label('Jumlah Vote'),
            Tables\Columns\TextColumn::make('status')->label('Status'),
        ];
    }
}