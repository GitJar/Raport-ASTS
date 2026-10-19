<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\StudentSummary;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Enumerable;

class SummariesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $classId;

    public function __construct($classId)
    {
        $this->classId = $classId;
    }

    /**
     * @return Enumerable
     */
    public function collection(): Enumerable
    {
        return Student::where('class_id', $this->classId)->get();
    }

    public function headings(): array
    {
        return [
            'student_id',
            'nis',
            'nisn',
            'nama_siswa',
            'sakit',
            'izin',
            'alfa',
            'catatan_wali_kelas',
        ];
    }

    public function map($student): array
    {
        $summary = StudentSummary::where('student_id', $student->id)->first();

        return [
            $student->id,
            $student->nis,
            $student->nisn,
            $student->name,
            $summary->sick_count ?? 0,
            $summary->permission_count ?? 0,
            $summary->unexcused_count ?? 0,
            $summary->teacher_notes ?? '',
        ];
    }
}