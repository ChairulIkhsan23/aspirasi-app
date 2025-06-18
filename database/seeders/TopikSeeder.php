<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Topik;

class TopikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $topikList = [
            'Infrastruktur',
            'Keamanan',
            'Fasilitas',
            'Akademik',
            'Organisasi Mahasiswa',
            'Kebersihan',
            'Teknologi',
            'Pelayanan Administrasi',
        ];

        foreach ($topikList as $nama) {
            Topik::create(['nama' => $nama]);
        }
    }
}
