<?php

namespace App\Exports;

use App\Models\Aspirasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AspirasiExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    protected function baseQuery()
    {
        $query = Aspirasi::query()->withCount('votes');

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['bulan'])) {
            $query->whereMonth('created_at', $this->filters['bulan']);
        }

        if (!empty($this->filters['topik'])) {
            $query->where('topik_id', $this->filters['topik']);
        }

        return $query;
    }

    public function collection()
    {
        return $this->baseQuery()->get([
            'judul',
            'isi',
            'status',
            'lampiran',
            'created_at',
        ])->map(function ($aspirasi) {
            return [
                'judul' => $aspirasi->judul,
                'isi' => $aspirasi->isi,
                'status' => $aspirasi->status,
                'lampiran' => $aspirasi->lampiran 
                    ? asset('storage/' . $aspirasi->lampiran)
                    : 'Tidak ada',
                'tanggal_dibuat' => $aspirasi->created_at->format('d-m-Y'),
                'jumlah_vote' => $aspirasi->votes_count,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Judul',
            'Isi',
            'Status',
            'Lampiran',
            'Tanggal Dibuat',
            'Jumlah Vote',
        ];
    }

    public function toPdf()
    {
        $aspirasis = $this->baseQuery()->get();

        $pdf = Pdf::loadView('exports.aspirasi_pdf', compact('aspirasis'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-aspirasi.pdf');
    }
}
