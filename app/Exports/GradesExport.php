<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\AstsGrade;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Enumerable;

class GradesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $classId;
    protected $subjectId;

    public function __construct($classId, $subjectId)
    {
        $this->classId = $classId;
        $this->subjectId = $subjectId;
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
            'nilai_asts',
        ];
    }

    public function map($student): array
    {
        $existingGrade = AstsGrade::where('student_id', $student->id)
            ->where('subject_id', $this->subjectId)
            ->value('score');

        return [
            $student->id,
            $student->nis,
            $student->nisn,
            $student->name,
            $existingGrade ?? '',
        ];
    }
}