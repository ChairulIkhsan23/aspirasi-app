<?php

namespace App\Filament\Resources\AspirasiResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Database\Eloquent\Builder;

class TindakLanjutRelationManager extends RelationManager
{
    protected static string $relationship = 'tindakLanjut';
    protected static ?string $title = 'Riwayat Tindak Lanjut';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
                Textarea::make('keterangan') 
                    ->label('Catatan Tindak Lanjut')
                    ->required()
                    ->rows(3),
                DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->default(now())
                    ->required(),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('keterangan')
                    ->wrap(),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date('d M Y') 
                    ->sortable(),
                ])
                ->filters([
                    //
                ])
                ->headerActions([
                    Tables\Actions\CreateAction::make()
                        ->label('Buat Tindak Lanjut')
                        ->modalHeading('Buat Tindak Lanjut')
                        ->modalSubmitActionLabel('Simpan') 
                        ->modalCancelActionLabel('Batal') 
                        ->disableCreateAnother(),
                ])
                ->actions([
                    Tables\Actions\EditAction::make()
                        ->modalSubmitActionLabel('Simpan')
                        ->modalCancelActionLabel('Batal'),
                    Tables\Actions\DeleteAction::make(),
                ])
                ->bulkActions([
                    Tables\Actions\DeleteBulkAction::make(),
                ]);
        }


}
