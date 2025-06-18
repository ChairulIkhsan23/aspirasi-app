<?php

namespace App\Filament\Resources\TopikResource\Pages;

use App\Filament\Resources\TopikResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTopik extends EditRecord
{
    protected static string $resource = TopikResource::class;

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
