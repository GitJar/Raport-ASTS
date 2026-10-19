<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AstsGrade extends Model
{
    protected $table = 'asts_grades';
    protected $fillable = ['student_id', 'subject_id', 'teacher_id', 'score'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}