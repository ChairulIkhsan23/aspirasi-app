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
                    'votes_count' => $aspirasi->votes_count,
                    'topik' => $aspirasi->topik,
                    'komentar_tindak_lanjut' => $aspirasi->tindakLanjut ? [
                        'keterangan' => TextHelper::filterKasar($aspirasi->tindakLanjut->keterangan),
                        'komentar' => $aspirasi->tindakLanjut->komentar ?? $aspirasi->tindakLanjut->tindak_lanjut,
                        'tanggal' => $aspirasi->tindakLanjut->updated_at->toDateTimeString(),
                    ] : null,
                ];
            });

        $user = Auth::user();
        Log::info('User:', ['id' => optional($user)->id]);  

        $votedAspirasiIds = $user
            ? Vote::where('user_id', $user->id)->pluck('aspirasi_id')->toArray()
            : [];
        Log::info('Voted Aspirasi IDs:', $votedAspirasiIds);

        return Inertia::render('Dashboard', [
            'aspirasis' => $aspirasis,
            'votedAspirasiIds' => $votedAspirasiIds,
        ]);
    }

    public function vote(Request $request, $id)
    {
        $user = $request->user();

        if (!$user) {
            return back()->withErrors(['message' => 'Anda belum login.']);
        }

        $sudahVote = Vote::where('user_id', $user->id)
            ->where('aspirasi_id', $id)
            ->exists();

        if ($sudahVote) {
            return back()->withErrors(['message' => 'Anda sudah vote aspirasi ini.']);
        }

        Vote::create([
            'user_id' => $user->id,
            'aspirasi_id' => $id,
        ]);

        return redirect()->route('dashboard');
    }

}
