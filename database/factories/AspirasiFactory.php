<?php

namespace Database\Factories;

use App\Models\Aspirasi;
use App\Models\User;
use App\Models\Topik;
use Illuminate\Database\Eloquent\Factories\Factory;

class AspirasiFactory extends Factory
{
    protected $model = Aspirasi::class;

    // Static agar tidak direset setiap generate data baru
    protected static $usedJuduls = [];
    protected static $usedIsis = [];

    public function definition(): array
    {
        $judulContoh = [
            'Perbaikan Lampu Jalan di Kampus',
            'Wifi Tidak Stabil di Gedung C',
            'Kurangnya Tempat Duduk di Taman',
            'AC di Kelas Tidak Menyala',
            'Mohon Penambahan Kegiatan UKM',
            'Tempat Parkir Sering Penuh',
            'Tidak Ada Tisu di Toilet',
            'Sampah Berserakan di Lapangan',
            'Perpustakaan Kurang Koleksi Buku',
            'Kegiatan Kampus Kurang Diminati',
            'Makanan di Kantin Mahal',
            'Sirkulasi Udara Kurang Baik',
            'Sarana Olahraga Tidak Terawat',
            'Jam Operasional Kantin Kurang Lama',
            'Koneksi Internet Bermasalah Saat Ujian',
        ];

        $isiNormal = [
            'Saya berharap ada perbaikan fasilitas belajar.',
            'Mohon ditambah tempat sampah di taman.',
            'AC di ruang kuliah tidak berfungsi.',
            'Wifi sering tidak stabil, mohon ditindak.',
            'Tolong sediakan lebih banyak parkir motor.',
            'Kebersihan toilet perlu ditingkatkan.',
            'Mohon ada penambahan jam operasional perpustakaan.',
            'Perlu pengawasan lebih di kantin.',
            'Kegiatan UKM kurang terjadwal.',
            'Lampu taman sering mati.',
            'Fasilitas olahraga sangat kurang.',
            'Butuh lebih banyak stop kontak di kelas.',
            'Lift sering rusak, tolong perbaiki.',
            'Jadwal dosen sering bentrok.',
            'Perlu petunjuk arah di kampus.',
        ];

        $isiKasar = [
            'Parkirannya sempit banget goblok!',
            'Wifi di gedung ini tai banget lambatnya.',
            'Ngentot, AC kelas nggak nyala sejak kemarin.',
            'Bangsat, dosennya sering telat mulu.',
            'Bodoh banget sistem informasi kampus ini!',
            'Goblok banget, masa aplikasi error terus.',
            'Jancok ruang kelas kek kandang ayam.',
            'Kantin kampus ini brengsek, makanannya basi.',
        ];

        // Ambil judul dan isi yang belum terpakai
        $judul = collect($judulContoh)->diff(self::$usedJuduls)->random();
        $isiPool = $this->faker->boolean(50) ? $isiKasar : $isiNormal;
        $isi = collect($isiPool)->diff(self::$usedIsis)->random();

        self::$usedJuduls[] = $judul;
        self::$usedIsis[] = $isi;

        return [
            'user_id' => User::inRandomOrder()->first()->id ?? 1,
            'judul' => $judul,
            'isi' => $isi,
            'topik_id' => Topik::inRandomOrder()->first()->id ?? 1,
            'lampiran' => 'lampiran/osprey2.jpg',
            'is_anonim' => $this->faker->boolean(70),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Aspirasi $aspirasi) {
            $userIds = User::inRandomOrder()->limit(rand(1, 10))->pluck('id');

            foreach ($userIds as $userId) {
                if (!$aspirasi->votes()->where('user_id', $userId)->exists()) {
                    $aspirasi->votes()->create([
                        'user_id' => $userId,
                    ]);
                }
            }
        });
    }
}
