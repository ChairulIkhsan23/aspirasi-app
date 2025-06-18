<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat 1 Admin Manual
        User::create([
            'name' => 'Admin',
            'nim' => '2243410000',
            'email' => 'admin@polindra.ac.id',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'), 
            'role' => 'admin',
            'remember_token' => Str::random(10),
        ]);

        // Buat 10 User dengan factory
        User::factory(10)->create();
    }
}
