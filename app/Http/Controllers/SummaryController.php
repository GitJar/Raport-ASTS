<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Student;
use App\Models\StudentSummary;
use App\Exports\SummariesExport;
use App\Imports\SummariesImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SummaryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Jika Wali Kelas, tampilkan hanya kelas yang diampunya
        if ($user->role === 'wali_kelas') {
            $classes = ClassModel::where('teacher_id', $user->id)->orderBy('name')->get();
        } else {
            $classes = ClassModel::orderBy('name')->get();
        }

        $selectedClassId = $request->query('class_id', $classes->first()->id ?? null);
        $students = [];
        $summaries = [];

        if ($selectedClassId) {
            $currentClass = ClassModel::find($selectedClassId);

            if ($currentClass && !$currentClass->canBeAccessedBy($user)) {
                abort(403, 'Anda tidak memiliki hak akses untuk presensi kelas ini.');
            }

            $students = Student::where('class_id', $selectedClassId)->get();
            $summaries = StudentSummary::whereIn('student_id', $students->pluck('id'))
                ->get()
                ->keyBy('student_id');
        }

        return view('summaries.index', compact('classes', 'selectedClassId', 'students', 'summaries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'summaries' => 'required|array',
        ]);

        foreach ($request->summaries as $studentId => $data) {
            $student = Student::findOrFail($studentId);

            if ($student->class && !$student->class->canBeAccessedBy(auth()->user())) {
                return back()->with('error', 'Akses ditolak! Anda bukan Wali Kelas di kelas ini.');
            }

            StudentSummary::updateOrCreate(
                ['student_id' => $studentId],
                [
                    'sick_count' => $data['sick'] ?? 0,
                    'permission_count' => $data['permission'] ?? 0,
                    'unexcused_count' => $data['unexcused'] ?? 0,
                    'teacher_notes' => $data['notes'] ?? null,
                ]
            );
        }

        return back()->with('success', 'Data Presensi & Catatan Wali Kelas Berhasil Disimpan!');
    }

    public function downloadTemplate(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
        ]);

        $class = ClassModel::findOrFail($request->class_id);

        if (!$class->canBeAccessedBy(auth()->user())) {
            return back()->with('error', 'Akses ditolak!');
        }

        $fileName = "Template_Presensi_Kelas_{$class->name}.xlsx";
        return Excel::download(new SummariesExport($request->class_id), $fileName);
    }

    public function import(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        $class = ClassModel::findOrFail($request->class_id);

        if (!$class->canBeAccessedBy(auth()->user())) {
            return back()->with('error', 'Akses ditolak!');
        }

        try {
            Excel::import(new SummariesImport, $request->file('file'));
            return redirect()->route('summaries.index', ['class_id' => $request->class_id])
                ->with('success', 'Berhasil mengimpor presensi & catatan dari file Excel!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses file Excel: ' . $e->getMessage());
        }
    }
}