<?php

namespace App\Filament\Resources\TopikResource\Pages;

use App\Filament\Resources\TopikResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTopiks extends ListRecords
{
    protected static string $resource = TopikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Topik'),
        ];
    }
}
