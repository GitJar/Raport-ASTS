<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User Admin Utama
        User::create([
            'name' => 'Administrator MTsN 3',
            'email' => 'admin@mtsn3purworejo.sch.id',
            'password' => Hash::make('admin12345'), // Silakan ubah password ini nanti
            'role' => 'admin',
            'nip_nik' => '199308082025001001',
        ]);

        // 2. Data Induk Mata Pelajaran (PAI & Mulok punya Sub-Mapel)
        $pai = DB::table('subjects')->insertGetId([
            'code' => 'PAI',
            'name' => 'Pendidikan Agama Islam',
            'order_no' => 1,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // Sub Mapel PAI
        DB::table('subjects')->insert([
            ['parent_id' => $pai, 'code' => 'PAI_QH', 'name' => 'A. Al Qur\'an Hadis', 'order_no' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['parent_id' => $pai, 'code' => 'PAI_AA', 'name' => 'B. Akidah Akhlak', 'order_no' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['parent_id' => $pai, 'code' => 'PAI_FIQ', 'name' => 'C. Fikih', 'order_no' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['parent_id' => $pai, 'code' => 'PAI_SKI', 'name' => 'D. Sejarah Kebudayaan Islam', 'order_no' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Mapel Reguler
        DB::table('subjects')->insert([
            ['parent_id' => null, 'code' => 'BARAB', 'name' => 'Bahasa Arab', 'order_no' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['parent_id' => null, 'code' => 'PPKN', 'name' => 'Pendidikan Pancasila', 'order_no' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['parent_id' => null, 'code' => 'BIND', 'name' => 'Bahasa Indonesia', 'order_no' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['parent_id' => null, 'code' => 'MTK', 'name' => 'Matematika', 'order_no' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['parent_id' => null, 'code' => 'IPA', 'name' => 'Ilmu Pengetahuan Alam', 'order_no' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['parent_id' => null, 'code' => 'IPS', 'name' => 'Ilmu Pengetahuan Sosial', 'order_no' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['parent_id' => null, 'code' => 'BING', 'name' => 'Bahasa Inggris', 'order_no' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['parent_id' => null, 'code' => 'PJOK', 'name' => 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'order_no' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['parent_id' => null, 'code' => 'INF', 'name' => 'Informatika', 'order_no' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['parent_id' => null, 'code' => 'SENI', 'name' => 'Seni dan Budaya', 'order_no' => 11, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Data Mulok
        $mulok = DB::table('subjects')->insertGetId([
            'code' => 'MULOK',
            'name' => 'Muatan Lokal',
            'order_no' => 12,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // Sub Mapel Mulok
        DB::table('subjects')->insert([
            ['parent_id' => $mulok, 'code' => 'ML_BJAWA', 'name' => 'A. Bahasa Jawa', 'order_no' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['parent_id' => $mulok, 'code' => 'ML_TAHFIDZ', 'name' => 'B. Tahfidz', 'order_no' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}