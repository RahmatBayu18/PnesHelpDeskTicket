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
        // 1. Buat Kategori
        $catJaringan = Category::create(['name' => 'Jaringan']);
        $catSoftware = Category::create(['name' => 'Software']);
        $catHardware = Category::create(['name' => 'Hardware']);

        // 2. Buat Users (Sesuaikan dengan kolom tabel Anda: username & nim)
        
        // ADMIN - Already verified (no email verification needed)
        $admin = User::create([
            'username' => 'admin_pens',      // Ganti name jadi username
            'nim'      => 'ADM001',          // NIM Dummy untuk Admin
            'email'    => 'admin@pens.ac.id',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'email_verified_at' => now(),    // Admin doesn't need to verify email
        ]);

        // TEKNISI - Already verified (no email verification needed)
        $teknisi = User::create([
            'username' => 'budi_teknisi',    // Ganti name jadi username
            'nim'      => 'TEK001',          // NIM Dummy untuk Teknisi
            'email'    => 'teknisi@pens.ac.id',
            'password' => Hash::make('password'),
            'role'     => 'teknisi',
            'email_verified_at' => now(),    // Teknisi doesn't need to verify email
        ]);

        // MAHASISWA - Must verify email
        $mhs = User::create([
            'username' => 'andi_mhs',        // Ganti name jadi username
            'nim'      => '3120500001',      // NIM Asli Mahasiswa
            'email'    => 'mahasiswa@pens.ac.id',
            'password' => Hash::make('password'),
            'role'     => 'mahasiswa',
            // No email_verified_at - mahasiswa must verify their email
        ]);

        // Tiket 1: Open (Belum ada teknisi)
        Ticket::create([
            'user_id'       => $mhs->id,
            'category_id'   => $catJaringan->id,
            'title'         => 'Wifi Lab Jarkom Lemot',
            'description'   => 'Koneksi putus nyambung saat praktikum.',
            'location'      => 'Gedung D4 Lt. 2',
            'status'        => 'Open',
            'technician_id' => null, 
        ]);

        // Tiket 2: In Progress (Dikerjakan Teknisi)
        Ticket::create([
            'user_id'       => $mhs->id,
            'category_id'   => $catHardware->id,
            'title'         => 'Mouse Rusak',
            'description'   => 'Klik kanan tidak berfungsi.',
            'location'      => 'Lab RPL',
            'status'        => 'In Progress',
            'technician_id' => $teknisi->id, 
        ]);
    }
}
