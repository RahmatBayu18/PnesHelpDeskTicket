<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test users with different roles
        User::factory()->create([
            'username' => 'admin',
            'nim' => '1234567890',
            'email' => 'admin@pens.ac.id',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'username' => 'teknisi',
            'nim' => '2234567890',
            'email' => 'teknisi@pens.ac.id',
            'role' => 'teknisi',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'username' => 'mahasiswa',
            'nim' => '3234567890',
            'email' => 'mahasiswa@pens.ac.id',
            'role' => 'mahasiswa',
            'password' => bcrypt('password'),
        ]);
    }
}
