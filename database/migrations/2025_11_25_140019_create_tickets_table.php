<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_code')->unique(); // 6 Huruf Acak
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Pembuat tiket
            $table->foreignId('category_id')->constrained();
            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->string('image_path')->nullable(); // Path gambar
            $table->foreignId('technician_id')->nullable()->constrained('users');
            $table->enum('status', ['Open', 'In Progress', 'Resolved', 'Closed'])->default('Open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
