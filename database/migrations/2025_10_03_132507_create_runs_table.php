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
        Schema::create('runs', function (Blueprint $table) {
            $table->id();
            // Kolom untuk menghubungkan catatan lari ke pengguna
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Data Lari
            $table->date('date');           // Tanggal lari
            $table->float('distance', 8, 2); // Jarak lari (misal: 5.25 km)
            $table->unsignedInteger('duration'); // Durasi lari dalam detik
            $table->text('notes')->nullable(); // Catatan opsional
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('runs');
    }
};
