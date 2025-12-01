<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mark only admin and teknisi as verified
        // Mahasiswa must verify their email
        DB::table('users')
            ->whereNull('email_verified_at')
            ->whereIn('role', ['admin', 'teknisi'])
            ->update(['email_verified_at' => now()]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally, you can clear verification status
        // DB::table('users')->update(['email_verified_at' => null]);
    }
};
