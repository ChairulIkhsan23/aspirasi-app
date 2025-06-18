<?php

namespace App\Filament\Resources\AspirasiResource\Pages;

use App\Filament\Resources\AspirasiResource;
use Filament\Resources\Pages\EditRecord;

class EditAspirasi extends EditRecord
{
    protected static string $resource = AspirasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('delete')
                ->action(fn () => $this->record->delete())
                ->label('Hapus')
                ->requiresConfirmation()
                ->color('danger'),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('save')
                ->label('Simpan')
                ->action('save'),
            \Filament\Actions\Action::make('cancel')
                ->label('Batal')
                ->url($this->previousUrl ?? $this->getResource()::getUrl('index'))
                ->color('gray'),
        ];
    }
}
