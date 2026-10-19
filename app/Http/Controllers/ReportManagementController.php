<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Student;
use Illuminate\Http\Request;

class ReportManagementController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'wali_kelas') {
            $classes = ClassModel::where('teacher_id', $user->id)->orderBy('name')->get();
        } else {
            $classes = ClassModel::orderBy('name')->get();
        }

        $selectedClassId = $request->query('class_id', $classes->first()->id ?? null);
        $students = [];

        if ($selectedClassId) {
            $currentClass = ClassModel::find($selectedClassId);

            if ($currentClass && !$currentClass->canBeAccessedBy($user)) {
                abort(403, 'Anda tidak memiliki hak akses cetak raport kelas ini.');
            }

            $students = Student::where('class_id', $selectedClassId)->get();
        }

        return view('reports.index', compact('classes', 'selectedClassId', 'students'));
    }
}