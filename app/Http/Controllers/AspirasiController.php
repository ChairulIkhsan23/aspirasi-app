<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\Aspirasi;
use App\Models\Topik;
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
        $user = Auth::user();

        $aspirasis = Aspirasi::with(['topik', 'tindakLanjut', 'user'])
            ->withCount('votes')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($aspirasi) use ($user) {
                return [
                    'id' => $aspirasi->id,
                    'judul' => TextHelper::filterKasar($aspirasi->judul),
                    'isi' => TextHelper::filterKasar($aspirasi->isi),
                    'status' => $aspirasi->status,
                    'pengirim' => $aspirasi->is_anonim ? null : optional($aspirasi->user)->email,
                    'votes_count' => $aspirasi->votes_count ?? 0,
                    'topik' => $aspirasi->topik,
                    'is_owner' => $user && $aspirasi->user_id === optional($user)->id,
                    'komentar_tindak_lanjut' => $aspirasi->tindakLanjut ? [
                        'keterangan' => TextHelper::filterKasar($aspirasi->tindakLanjut->keterangan),
                        'komentar' => $aspirasi->tindakLanjut->komentar ?? $aspirasi->tindakLanjut->tindak_lanjut,
                        'tanggal' => $aspirasi->tindakLanjut->updated_at->toDateTimeString(),
                    ] : null,
                ];
            });

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
            return redirect()->route('dashboard')->with('error', 'Anda belum login.');
        }

        $sudahVote = Vote::where('user_id', $user->id)
            ->where('aspirasi_id', $id)
            ->exists();

        if ($sudahVote) {
            return redirect()->route('dashboard')->with('error', 'Anda sudah vote aspirasi ini.');
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

    /**
     * Halaman untuk tambah aspirasi.
     */
    public function create()
    {
        Log::info('Mengakses halaman tambah aspirasi.');

        $topiks = Topik::all();

        return Inertia::render('Aspirasi/Create', [
            'topiks' => $topiks,
        ]);
    }

    /**
     * Simpan aspirasi baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'topik_id' => 'required|exists:topiks,id',
            'is_anonim' => 'boolean',
        ]);

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('dashboard')->with('error', 'Anda harus login untuk mengirim aspirasi.');
        }

        Aspirasi::create([
            'judul' => $validated['judul'],
            'isi' => $validated['isi'],
            'topik_id' => $validated['topik_id'],
            'user_id' => $user->id,
            'is_anonim' => $validated['is_anonim'] ?? false, 
        ]);

        return redirect()->route('dashboard')->with('success', 'Aspirasi berhasil dikirim!');
    }

    public function edit($id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('dashboard')->with('error', 'Anda belum login.');
        }

        $aspirasi = Aspirasi::where('id', $id)
            ->whereNotNull('user_id') // pastikan bukan anonim
            ->where('user_id', $user->id)
            ->first();

        if (!$aspirasi) {
            return redirect()->route('dashboard')->with('error', 'Aspirasi tidak ditemukan atau bukan milik Anda.');
        }

        $topiks = Topik::all();

        return Inertia::render('Aspirasi/Edit', [
            'aspirasi' => $aspirasi,
            'topiks' => $topiks,
        ]);
    }

}
