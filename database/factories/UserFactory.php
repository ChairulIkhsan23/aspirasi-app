<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        $name = $this->faker->name();
        $username = strtolower(str_replace(' ', '', $name));
        $nim = $this->faker->unique()->numerify('2243410###');

        return [
            'name' => $name,
            'nim' => $nim,
            'email' => $username . '@student.polindra.ac.id',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'user',
            'remember_token' => Str::random(10),
        ];
    }
}
