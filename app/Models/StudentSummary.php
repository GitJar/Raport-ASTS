<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentSummary extends Model
{
    protected $table = 'student_summaries';
    protected $fillable = ['student_id', 'sick_count', 'permission_count', 'unexcused_count', 'teacher_notes'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}