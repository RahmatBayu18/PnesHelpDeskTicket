<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\Comment;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat Kategori (Diperbarui + Tambahan)
        $catAlat        = Category::create(['name' => 'Alat']);
        $catSarpras     = Category::create(['name' => 'Sarana Prasarana']);
        $catLainnya     = Category::create(['name' => 'Lainnya']);
        $catJaringan    = Category::create(['name' => 'Jaringan']);
        $catHardware    = Category::create(['name' => 'Hardware']);
        $catSoftware    = Category::create(['name' => 'Software']);

        // Simpan ID kategori ke array
        $categories = [
            $catAlat->id,
            $catSarpras->id,
            $catLainnya->id,
            $catJaringan->id,
            $catHardware->id,
            $catSoftware->id
        ];

        // 2. Buat Admin
        User::create([
            'username' => 'admin_pens',
            'nim'      => 'ADM001',
            'email'    => 'admin@pens.ac.id',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'email_verified_at' => now(),
        ]);

        // 3. Buat Teknisi
        $teknisi = User::create([
            'username' => 'budi_teknisi',
            'nim'      => 'TEK001',
            'email'    => 'teknisi@pens.ac.id',
            'password' => Hash::make('password'),
            'role'     => 'teknisi',
            'email_verified_at' => now(),
        ]);

        // 4. Buat 6 Mahasiswa + 6 Tiket/mahasiswa
        for ($i = 1; $i <= 6; $i++) {
            // Buat User Mahasiswa
            $mhs = User::create([
                'username' => "mahasiswa_$i",
                'nim'      => "312050000$i",
                'email'    => "mahasiswa$i@pens.ac.id",
                'password' => Hash::make('password'),
                'role'     => 'mahasiswa',
                'email_verified_at' => now(),
            ]);

            // Tiket
            for ($j = 1; $j <= 6; $j++) {

                $statusList = ['Open', 'In Progress', 'Resolved', 'Closed'];
                $status = $statusList[array_rand($statusList)];

                $assignedTechnician = ($status === 'Open') ? null : $teknisi->id;

                $hasImage = rand(0, 1);
                $imagePath = $hasImage ? 'ticket-images/contoh_error.jpg' : null;

                Ticket::create([
                    'user_id'       => $mhs->id,
                    'category_id'   => $categories[array_rand($categories)],
                    'title'         => "Keluhan Mahasiswa $i - Masalah $j",
                    'description'   => "Ini adalah deskripsi keluhan nomor $j dari mahasiswa $i. Mohon segera diperbaiki.",
                    'location'      => "Gedung D4 Lt. " . rand(1, 3),
                    'status'        => $status,
                    'technician_id' => $assignedTechnician,
                    'image_path'    => $imagePath,
                ]);
            }
        }
    }
}
