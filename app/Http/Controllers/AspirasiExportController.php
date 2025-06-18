<?php

namespace App\Http\Controllers;

use App\Models\Aspirasi;
use App\Exports\AspirasiExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AspirasiExportController extends Controller
{
    public function pdf(Request $request)
    {
        $aspirasis = Aspirasi::query()
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->bulan, fn ($q) => $q->whereMonth('created_at', $request->bulan))
            ->when($request->topik, fn ($q) => $q->where('topik_id', $request->topik))
            ->get();

        $pdf = Pdf::loadView('export.aspirasi-pdf', compact('aspirasis'));
        return $pdf->download('laporan-aspirasi.pdf');
    }

    public function excel(Request $request)
    {
        return Excel::download(new AspirasiExport($request->only(['status', 'bulan', 'topik'])), 'laporan-aspirasi.xlsx');
    }
}
