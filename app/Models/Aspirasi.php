<?php

namespace App\Models;

use App\Models\Vote;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Aspirasi extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    public $timestamps = true; 
    
    protected $fillable = [
        'user_id', 'judul', 'isi', 'topik_id', 'status', 'lampiran', 'flag_konten', 'status_moderasi', 'is_anonim',
    ];

    protected $casts = [
    'is_anonim' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function tindakLanjut()
    {
        return $this->hasOne(TindakLanjut::class);
    }

    public function topik()
    {
        return $this->belongsTo(Topik::class);
    }

    protected static function booted()
    {
        static::creating(function ($aspirasi) {
            $aspirasi->cekKataKasar();
        });

        static::updating(function ($aspirasi) {
            $aspirasi->cekKataKasar();
        });
    }

    public function cekKataKasar(): void
    {
        $kataKasar = config('kata_kasar');

        // UJI DULU: Apakah config kebaca
        if (!is_array($kataKasar)) {
            logger()->error('Config kata_kasar tidak terbaca.');
            $this->flag_konten = false;
            $this->status_moderasi = 'disetujui';
            return;
        }

        $isi = strtolower($this->isi);

        foreach ($kataKasar as $kata) {
            if (str_contains($isi, strtolower($kata))) {
                $this->flag_konten = true;
                $this->status_moderasi = 'perlu ditinjau';

                logger()->info("Kata kasar ditemukan: $kata");
                return;
            }
        }

        $this->flag_konten = false;
        $this->status_moderasi = 'disetujui';
    }

}
