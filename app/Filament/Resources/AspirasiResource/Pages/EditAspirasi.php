<?php

namespace App\Filament\Resources\AspirasiResource\Pages;

use App\Filament\Resources\AspirasiResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;

class EditAspirasi extends EditRecord
{
    protected static string $resource = AspirasiResource::class;

    protected function getHeaderActions(): array
    {
       return [
            \Filament\Actions\Action::make('delete')
                ->label('Hapus')
                ->requiresConfirmation()
                ->color('danger')
                ->action(function () {
                    $this->record->delete();
                    return redirect($this->getResource()::getUrl('index')); // ✅ Redirect biar tidak error
                }),
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

    protected function resolveRecord($key): Model
    {
        $record = static::getResource()::getEloquentQuery()->find($key);

        if (!$record) {
            abort(404, 'Data aspirasi tidak ditemukan.');
        }

        return $record;
    }

}
