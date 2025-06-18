<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Aspirasi;
use App\Models\User;
use App\Models\Vote;

class VoteSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = User::pluck('id')->toArray();

        Aspirasi::all()->each(function ($aspirasi) use ($userIds) {
            $voters = collect($userIds)->shuffle()->take(rand(0, min(15, count($userIds))));

            foreach ($voters as $userId) {
                Vote::firstOrCreate([
                    'aspirasi_id' => $aspirasi->id,
                    'user_id' => $userId,
                ]);
            }
        });
    }
}
