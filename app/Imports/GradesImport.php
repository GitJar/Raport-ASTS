<?php

namespace App\Imports;

use App\Models\AstsGrade;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Database\Eloquent\Model;

class GradesImport implements ToModel, WithHeadingRow
{
    protected $subjectId;
    protected $teacherId;

    public function __construct($subjectId, $teacherId)
    {
        $this->subjectId = $subjectId;
        $this->teacherId = $teacherId;
    }

    /**
     * @param array $row
     * @return Model|array|null
     */
    public function model(array $row): Model|array|null
    {
        $studentId = $row['student_id'] ?? null;
        $score = $row['nilai_asts'] ?? null;

        if ($studentId && $score !== null && $score !== '') {
            return AstsGrade::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'subject_id' => $this->subjectId,
                ],
                [
                    'teacher_id' => $this->teacherId,
                    'score' => (int) $score,
                ]
            );
        }

        return null;
    }
}