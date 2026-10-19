<?php

namespace App\Imports;

use App\Models\StudentSummary;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Database\Eloquent\Model;

class SummariesImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     * @return Model|array|null
     */
    public function model(array $row): Model|array|null
    {
        $studentId = $row['student_id'] ?? null;

        if ($studentId) {
            return StudentSummary::updateOrCreate(
                ['student_id' => $studentId],
                [
                    'sick_count' => (int) ($row['sakit'] ?? 0),
                    'permission_count' => (int) ($row['izin'] ?? 0),
                    'unexcused_count' => (int) ($row['alfa'] ?? 0),
                    'teacher_notes' => $row['catatan_wali_kelas'] ?? null,
                ]
            );
        }

        return null;
    }
}