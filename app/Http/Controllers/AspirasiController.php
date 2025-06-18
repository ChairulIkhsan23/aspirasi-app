<?php

// app/Http/Controllers/AspirasiController.php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\Aspirasi;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AspirasiController extends Controller
{
   
    public function index()
    {
        $aspirasis = Aspirasi::with(['topik', 'tindakLanjut'])->withCount('votes')->get()->map(function ($aspirasi) {
        return [
            'id' => $aspirasi->id,
            'judul' => $aspirasi->judul,
            'isi' => $aspirasi->isi,
            'status' => $aspirasi->status,
            'pengirim' => $aspirasi->is_anonim ? null : $aspirasi->user->email,
            'votes_count' => $aspirasi->votes_count,
            'topik' => $aspirasi->topik,
            'komentar_tindak_lanjut' => $aspirasi->tindakLanjut ? [
                'keterangan' => $aspirasi->tindakLanjut->keterangan,
                'komentar' => $aspirasi->tindakLanjut->komentar ?? $aspirasi->tindakLanjut->tindak_lanjut,
                'tanggal' => $aspirasi->tindakLanjut->updated_at->toDateTimeString()
            ] : null
        ];
        });

        // Ambil id aspirasi yang sudah divote user saat ini
        $user = Auth::user();
        $votedAspirasiIds = $user
            ? Vote::where('user_id', $user->id)->pluck('aspirasi_id')->toArray()
            : [];

        return Inertia::render('Dashboard', [
            'aspirasis' => $aspirasis,
            'votedAspirasiIds' => $votedAspirasiIds,
        ]);
    }

    // Fungsi vote aspirasi
    public function vote(Request $request, $id)
    {
        $user = $request->user();

        // Cek apakah user sudah vote aspirasi ini
        $sudahVote = Vote::where('user_id', $user->id)
            ->where('aspirasi_id', $id)
            ->exists();

        if ($sudahVote) {
            return back()->withErrors(['message' => 'Anda sudah vote aspirasi ini.']);
        }

        // Simpan vote baru
        Vote::create([
            'user_id' => $user->id,
            'aspirasi_id' => $id,
        ]);

        Log::info('Vote disimpan untuk user ' . $user->id . ' pada aspirasi ' . $id);

        return back()->with('success', 'Vote berhasil ditambahkan.');
    }
}
