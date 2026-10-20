<?php

namespace App\Imports;

use App\Models\ClassModel;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Database\Eloquent\Model;

class ClassesImport implements ToModel, WithHeadingRow
{
    public function model(array $row): Model|array|null
    {
        $name = $row['nama_kelas'] ?? null;
        if (!$name) return null;

        $teacherId = null;
        if (!empty($row['nip_wali_kelas'])) {
            $teacher = User::where('nip_nik', trim($row['nip_wali_kelas']))->first();
            if ($teacher) {
                $teacherId = $teacher->id;
            }
        }

        return ClassModel::updateOrCreate(
            ['name' => strtoupper(trim($name))],
            [
                'phase' => strtoupper(trim($row['fase'] ?? 'D')),
                'academic_year' => trim($row['tahun_ajaran'] ?? '2026/2027'),
                'semester' => ucfirst(strtolower(trim($row['semester'] ?? 'Ganjil'))),
                'teacher_id' => $teacherId,
                'is_locked' => false,
            ]
        );
    }
}