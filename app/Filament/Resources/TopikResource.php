<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TopikResource\Pages;
use Filament\Resources\Resource;

// Relation Managers
use App\Filament\Resources\TopikResource\RelationManagers;

// Models
use App\Models\Topik;

// Forms Component
use Filament\Forms;
use Filament\Forms\Form;

// Tables
use Filament\Tables;
use Filament\Tables\Table;

// Database Eloquent
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TopikResource extends Resource
{
    protected static ?string $model = Topik::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?string $pluralLabel = 'Topik';

    public static function form(Form $form): Form
    {
        return $form->schema([
                    Forms\Components\TextInput::make('nama')
                        ->label('Nama Topik')
                        ->required()
                        ->unique()
                        ->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Topik')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('aspirasis_count') // nama relasi + _count
                    ->label('Jumlah Aspirasi')
                    ->counts('aspirasis')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('aspirasis_count', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTopiks::route('/'),
            'create' => Pages\CreateTopik::route('/create'),
            'edit' => Pages\EditTopik::route('/{record}/edit'),
        ];
    }
}
