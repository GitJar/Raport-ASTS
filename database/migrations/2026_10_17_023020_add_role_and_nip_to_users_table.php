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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nip_nik')->nullable()->after('email');
            $table->enum('role', ['admin', 'kepala_madrasah', 'guru_mapel', 'guru_bk', 'wali_kelas'])
                  ->default('guru_mapel')
                  ->after('password');
        });
    }
    
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nip_nik', 'role']);
        });
    }
    
};
