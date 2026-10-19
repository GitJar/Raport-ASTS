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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: VIII.A
            $table->string('phase')->default('D'); // Fase D
            $table->string('academic_year'); // Contoh: 2025/2026
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->foreignId('teacher_id')->nullable()->constrained('users'); // Wali Kelas
            $table->boolean('is_locked')->default(false); // Fitur Kunci Nilai
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
