<?php

namespace App\Filament\Resources\TopikResource\Pages;

use App\Filament\Resources\TopikResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTopik extends CreateRecord
{
    protected static string $resource = TopikResource::class;

    public function getTitle(): string
    {
        return 'Buat Topik Baru';
    }

    public function getHeading(): string
    {
        return 'Buat Topik';
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('Simpan'),
            $this->getCancelFormAction()
                ->label('Batal'),
        ];
    }
}
