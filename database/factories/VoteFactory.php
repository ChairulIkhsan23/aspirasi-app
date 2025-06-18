<?php

namespace Database\Factories;

use App\Models\Vote;
use App\Models\User;
use App\Models\Aspirasi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vote>
 */
class VoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Vote::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? 1,
            'aspirasi_id' => Aspirasi::inRandomOrder()->first()->id ?? 1,
        ];
    }
}
