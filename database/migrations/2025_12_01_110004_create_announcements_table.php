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
        Schema::create('announcements', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade'); 
        
        $table->string('title');
        $table->string('category')->after('title')->default('Umum'); 
        
        $table->text('content'); 
        $table->enum('type', ['info', 'warning', 'danger'])->default('info');
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
