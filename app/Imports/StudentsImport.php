<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow
{
    protected $classId;

    public function __construct($classId)
    {
        $this->classId = $classId;
    }

    public function model(array $row)
    {
        // Mencari atau memperbarui data siswa berdasarkan NISN
        return Student::updateOrCreate(
            [
                'nisn' => trim($row['nisn']),
            ],
            [
                'nis' => trim($row['nis']),
                'name' => trim($row['nama_siswa']),
                'class_id' => $this->classId,
            ]
        );
    }
}