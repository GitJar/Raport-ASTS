<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ClassModel;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Wali Kelas & Guru Mapel
        $waliKelas = User::create([
            'name' => 'SRI PRATIWI NAMPIASIH, S.Pd',
            'email' => 'sripratiwi@mtsn3purworejo.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'wali_kelas',
            'nip_nik' => '197807122005012005',
        ]);

        // 2. Buat Data Kelas VIII.A
        $kelas8a = ClassModel::create([
            'name' => 'VIIILA',
            'phase' => 'D',
            'academic_year' => '2025/2026',
            'semester' => 'Ganjil',
            'teacher_id' => $waliKelas->id,
            'is_locked' => false,
        ]);

        // 3. Buat Siswa Sampel (Sesuai Berkas Raport)
        Student::create([
            'nis' => '121133060003240001',
            'nisn' => '0123107839',
            'name' => 'ACHMAD FARKHAN NAJIHA',
            'class_id' => $kelas8a->id,
        ]);

        // Tambahan sampel siswa lain di kelas yang sama
        Student::create([
            'nis' => '121133060003240002',
            'nisn' => '0123107840',
            'name' => 'AHMAD SALAM WAHID FAIZIN',
            'class_id' => $kelas8a->id,
        ]);
    }
}