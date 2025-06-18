<?php

namespace App\Filament\Resources\AspirasiResource\Pages;

use App\Filament\Resources\AspirasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ListAspirasis extends ListRecords
{
    protected static string $resource = AspirasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
        ];
    }

    
}
