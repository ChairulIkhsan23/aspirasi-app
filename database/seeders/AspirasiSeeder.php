<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Aspirasi;
use App\Models\User;
use App\Models\Topik;

class AspirasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Aspirasi::factory()->count(15)->create();
    }
}
