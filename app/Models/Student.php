<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['nis', 'nisn', 'name', 'gender', 'class_id'];

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function grades()
    {
        return $this->hasMany(AstsGrade::class, 'student_id');
    }

    public function summary()
    {
        return $this->hasOne(StudentSummary::class, 'student_id');
    }
}