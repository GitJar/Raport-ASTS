<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'phase',
        'academic_year',
        'semester',
        'teacher_id',
        'is_locked',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    // Method pembantu pengecekan akses Wali Kelas / Admin
    public function canBeAccessedBy($user)
    {
        if (in_array($user->role, ['admin', 'kepala_madrasah', 'guru_bk'])) {
            return true;
        }

        return $this->teacher_id === $user->id;
    }
}