<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AspirasiResource\Pages;
use Filament\Resources\Resource;

// Relation Managers
use App\Filament\Resources\AspirasiResource\RelationManagers;
use App\Filament\Resources\AspirasiResource\RelationManagers\TindakLanjutRelationManager;

// Models
use App\Models\Aspirasi;

// Forms Component
use Filament\Forms\Form;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Livewire as LivewireComponent;

//  Tabels
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;

// Database Eloquent
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

// Livewire
use App\Livewire\PreviewLampiran;

// Carbon
use Carbon\Carbon;



class AspirasiResource extends Resource
{
    protected static ?string $model = Aspirasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';

    protected static ?string $pluralLabel = 'Aspirasi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('judul')
                ->label('Judul')
                ->disabled(),
            TextInput::make('topik_id')
                ->label('Topik')
                ->disabled(),
            Textarea::make('isi')
                ->label('Isi Aspirasi')
                ->disabled()
                ->rows(4),
            Placeholder::make('pengirim')
                ->label('Pengirim')
                ->content(fn ($record) =>
                    $record->is_anonim
                        ? 'Anonim'
                        : ($record->user ? $record->user->name . ' (' . $record->user->email . ')' : '-')
                ),
            LivewireComponent::make('preview-lampiran')
                ->label('Lampiran')
                ->columnSpanFull()
                ->dehydrated(false)
                ->reactive()
                ->visible(fn ($record) => !empty($record?->lampiran)),
            Select::make('status')
                ->options([
                    'Belum Diproses' => 'Belum Diproses',
                    'Diproses' => 'Diproses',
                    'Selesai' => 'Selesai',
                    'Ditolak' => 'Ditolak'
                ])
                ->required(),
            Select::make('status_moderasi')
                ->options([
                    'disetujui' => 'Disetujui',
                    'perlu ditinjau' => 'Perlu Ditinjau',
                ])
                ->label('Status Moderasi')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('user.name')
                ->label('Pengirim')
                ->searchable()
                ->formatStateUsing(function ($state, $record) {
                    return $record->is_anonim
                        ? 'Anonim'
                        : ($record->user ? $record->user->name . ' (' . $record->user->email . ')' : '-');
                }),
            TextColumn::make('judul')
                ->label('Judul')
                ->limit(30)
                ->searchable(),
            TextColumn::make('topik.nama')
                ->label('Topik')
                ->sortable()
                ->searchable(), 
            TextColumn::make('isi')
                ->label('Isi')
                ->limit(50),
            TextColumn::make('status')
                ->badge()
                    ->color(fn (string $state) => match($state) {
                        'Belum Diproses' => 'danger',
                        'Diproses' => 'warning',
                        'Selesai' => 'success',
                        'Ditolak' => 'danger',
                    })   
                ->searchable(), 
            TextColumn::make('lampiran')
                ->label('Lampiran')
                ->formatStateUsing(function ($state) {
                    return $state 
                        ? '<a href="' . asset('storage/' . $state) . '" target="_blank">Download</a>' 
                        : 'Tidak ada';
                })
                ->html(),
            TextColumn::make('votes_count')
                ->label('Vote')
                ->badge()
                    ->color(fn ($state) => match (true) {
                        $state >= 10 => 'success',
                        $state >= 4 => 'warning',
                        default => 'gray',
                    })
                ->counts('votes')
                ->sortable()
                ->alignCenter(),
            TextColumn::make('created_at')->dateTime('d M Y')
                ->label('Tanggal'),
            TextColumn::make('status_moderasi')
                ->label('Moderasi')
                ->badge()
                ->color(fn ($state) => match ($state) {
                    'disetujui' => 'success',
                    'perlu ditinjau' => 'warning',
    }),
  
        ])

        ->filters([
            SelectFilter::make('status')
                ->options([
                    'Belum Diproses' => 'Belum Diproses',
                    'Diproses' => 'Diproses',
                    'Selesai' => 'Selesai',
                    'Ditolak' => 'Ditolak',
                ])
                ->placeholder('Semua Status'),
            SelectFilter::make('is_anonim')
                ->label('Jenis Pengirim')
                ->options([
                    '1' => 'Anonim',
                    '0' => 'Bukan Anonim',
                ])
                ->placeholder('Semua Jenis'),
            SelectFilter::make('bulan')
                ->label('Bulan')
                ->options([
                    '01' => 'Januari',
                    '02' => 'Februari',
                    '03' => 'Maret',
                    '04' => 'April',
                    '05' => 'Mei',
                    '06' => 'Juni',
                    '07' => 'Juli',
                    '08' => 'Agustus',
                    '09' => 'September',
                    '10' => 'Oktober',
                    '11' => 'November',
                    '12' => 'Desember',
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return filled($data['value'] ?? null)
                        ? $query->whereMonth('created_at', $data['value'])
                        : $query;
                }),
                SelectFilter::make('status_moderasi')
                    ->options([
                        'disetujui' => 'Disetujui',
                        'perlu ditinjau' => 'Perlu Ditinjau',
                    ]),
             ])
             ->headerActions([
                \Filament\Tables\Actions\Action::make('export')
                    ->label('Export Excel')
                    ->action(fn () => redirect('/export/aspirasi/excel'))
                    ->icon('heroicon-o-arrow-down-tray'),

                \Filament\Tables\Actions\Action::make('export_pdf')
                    ->label('Export PDF')
                    ->url(fn () => route('aspirasi.export.pdf', request()->query())) // pastikan route-nya benar
                    ->icon('heroicon-o-document-text')
                    ->openUrlInNewTab(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TindakLanjutRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('user');
        return parent::getEloquentQuery()
            ->with('user')
            ->withCount('votes');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAspirasis::route('/'),
            'edit' => Pages\EditAspirasi::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }


}
