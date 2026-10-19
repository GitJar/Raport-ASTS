<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Subject;
use App\Models\AstsGrade;
use App\Models\StudentSummary;
use Illuminate\Http\Request;
use App\Models\Setting;

class ReportController extends Controller
{
    public function printSingle($studentId)
    {
        $student = Student::with(['class.teacher'])->findOrFail($studentId);
        
        $subjects = Subject::whereNull('parent_id')
            ->with(['children' => function($q) {
                $q->orderBy('order_no');
            }])
            ->orderBy('order_no')
            ->get();
    
        $grades = AstsGrade::where('student_id', $studentId)
            ->pluck('score', 'subject_id')
            ->toArray();
    
        $summary = StudentSummary::firstOrCreate(
            ['student_id' => $studentId],
            ['sick_count' => 0, 'permission_count' => 0, 'unexcused_count' => 0]
        );
    
        // Ambil variabel setting dinamis
        $headmasterName = Setting::get('headmaster_name', 'Sugiyo, S.Pd., MM.Pd.');
        $headmasterNip = Setting::get('headmaster_nip', '197008252005011003');
        $reportDate = Setting::get('report_date', 'Purworejo, 20 Desember 2025');
    
        return view('reports.print', compact('student', 'subjects', 'grades', 'summary', 'headmasterName', 'headmasterNip', 'reportDate'));
    }
    
    // Cetak Massal Raport 1 Kelas
    public function printBulk($classId)
    {
        $class = \App\Models\ClassModel::findOrFail($classId);
        $students = Student::with(['class.teacher'])->where('class_id', $classId)->get();

        $subjects = Subject::whereNull('parent_id')
            ->with(['children' => function($q) {
                $q->orderBy('order_no');
            }])
            ->orderBy('order_no')
            ->get();

        $allGrades = AstsGrade::whereIn('student_id', $students->pluck('id'))
            ->get()
            ->groupBy('student_id');

        $allSummaries = StudentSummary::whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        $headmasterName = Setting::get('headmaster_name', 'Sugiyo, S.Pd., MM.Pd.');
        $headmasterNip = Setting::get('headmaster_nip', '197008252005011003');
        $reportDate = Setting::get('report_date', 'Purworejo, 20 Desember 2025');

        return view('reports.print_bulk', compact('class', 'students', 'subjects', 'allGrades', 'allSummaries', 'headmasterName', 'headmasterNip', 'reportDate'));
    }
}