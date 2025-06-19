<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\Aspirasi;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Helpers\TextHelper;

class AspirasiController extends Controller
{
    /**
     * Tampilkan semua aspirasi dengan data votes count dan aspirasi yang sudah di-vote user.
     */
    public function index()
    {
        $aspirasis = Aspirasi::with(['topik', 'tindakLanjut', 'user'])
            ->withCount('votes')
            ->get()
            ->map(function ($aspirasi) {
                return [
                    'id' => $aspirasi->id,
                    'judul' => TextHelper::filterKasar($aspirasi->judul),
                    'isi' => TextHelper::filterKasar($aspirasi->isi),
                    'status' => $aspirasi->status,
                    'pengirim' => $aspirasi->is_anonim ? null : optional($aspirasi->user)->email,
                    'votes_count' => $aspirasi->votes_count ?? 0,
                    'topik' => $aspirasi->topik,
                    'komentar_tindak_lanjut' => $aspirasi->tindakLanjut ? [
                        'keterangan' => TextHelper::filterKasar($aspirasi->tindakLanjut->keterangan),
                        'komentar' => $aspirasi->tindakLanjut->komentar ?? $aspirasi->tindakLanjut->tindak_lanjut,
                        'tanggal' => $aspirasi->tindakLanjut->updated_at->toDateTimeString(),
                    ] : null,
                ];
            });

        $user = Auth::user();

        $votedAspirasiIds = $user
            ? Vote::where('user_id', $user->id)->pluck('aspirasi_id')->toArray()
            : [];

        // Log untuk debugging
        Log::info('User:', ['id' => optional($user)->id]);
        Log::info('Voted Aspirasi IDs:', $votedAspirasiIds);

        return Inertia::render('Dashboard', [
            'aspirasis' => $aspirasis,
            'votedAspirasiIds' => $votedAspirasiIds,
        ]);
    }

    /**
     * Proses vote oleh user terhadap aspirasi.
     */
    public function vote(Request $request, $id)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Anda belum login.'], 401);
        }

        $sudahVote = Vote::where('user_id', $user->id)
            ->where('aspirasi_id', $id)
            ->exists();

        if ($sudahVote) {
            return response()->json(['message' => 'Anda sudah vote aspirasi ini.'], 409);
        }

        // Simpan vote
        Vote::create([
            'user_id' => $user->id,
            'aspirasi_id' => $id,
        ]);

        // Hitung ulang jumlah vote terkini dari database
        $totalVotes = Vote::where('aspirasi_id', $id)->count();

        Log::info("Vote berhasil oleh user {$user->id} untuk aspirasi {$id}. Total vote sekarang: {$totalVotes}");

        return redirect()->route('dashboard')->with('success', 'Vote berhasil.');
    }
}
