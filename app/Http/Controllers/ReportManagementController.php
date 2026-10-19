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
    
    // Halaman Leger Nilai & Ranking Kelas
    // Halaman Leger Nilai & Ranking Kelas
    public function leger(Request $request, $classId)
    {
        $class = \App\Models\ClassModel::with(['teacher'])->findOrFail($classId);

        // 1. Ambil Mapel Induk beserta Anak-anaknya sesuai urutan order_no (Sama persis dengan Cetak Raport)
        $parentSubjects = \App\Models\Subject::whereNull('parent_id')
            ->with(['children' => function($q) {
                $q->orderBy('order_no');
            }])
            ->orderBy('order_no')
            ->get();

        // 2. Ratakan menjadi array satu tingkat (flat) agar urutan kolom di tabel Leger sama dengan cetak raport
        $subjects = collect();
        foreach ($parentSubjects as $parent) {
            if ($parent->children->count() > 0) {
                foreach ($parent->children as $child) {
                    $subjects->push($child);
                }
            } else {
                $subjects->push($parent);
            }
        }

        $students = Student::where('class_id', $classId)->get();

        $allGrades = \App\Models\AstsGrade::whereIn('student_id', $students->pluck('id'))
            ->get()
            ->groupBy('student_id');

        // Hitung Total Nilai per Siswa untuk Menentukan Ranking
        $studentData = [];
        foreach ($students as $student) {
            $grades = isset($allGrades[$student->id]) ? $allGrades[$student->id]->pluck('score', 'subject_id')->toArray() : [];
            $totalScore = 0;
            $countScored = 0;

            foreach ($subjects as $subject) {
                if (isset($grades[$subject->id]) && $grades[$subject->id] !== null) {
                    $totalScore += $grades[$subject->id];
                    $countScored++;
                }
            }

            $averageScore = $countScored > 0 ? round($totalScore / $countScored, 2) : 0;

            $studentData[] = [
                'student' => $student,
                'grades' => $grades,
                'total_score' => $totalScore,
                'average_score' => $averageScore,
            ];
        }

        // Urutkan berdasarkan Total Nilai Tertinggi untuk menentukan Ranking
        usort($studentData, function ($a, $b) {
            return $b['total_score'] <=> $a['total_score'];
        });

        // Berikan Peringkat (Rank)
        $rank = 1;
        foreach ($studentData as $index => &$data) {
            if ($index > 0 && $data['total_score'] === $studentData[$index - 1]['total_score']) {
                $data['rank'] = $studentData[$index - 1]['rank'];
            } else {
                $data['rank'] = $rank;
            }
            $rank++;
        }
        unset($data);

        // Urutkan kembali berdasarkan Nama Siswa agar Leger rapi sesuai abjad
        usort($studentData, function ($a, $b) {
            return strcmp($a['student']->name, $b['student']->name);
        });

        // Pengaturan Cetak Dinamis
        $headmasterName = \App\Models\Setting::get('headmaster_name', 'Sugiyo, S.Pd., MM.Pd.');
        $headmasterNip = \App\Models\Setting::get('headmaster_nip', '197008252005011003');
        $reportDate = \App\Models\Setting::get('report_date', 'Purworejo, 20 Desember 2025');

        return view('reports.leger', compact('class', 'subjects', 'studentData', 'headmasterName', 'headmasterNip', 'reportDate'));
    }
}