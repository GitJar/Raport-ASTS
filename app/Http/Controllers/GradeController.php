<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\Student;
use App\Models\AstsGrade;
use Illuminate\Http\Request;
use App\Exports\GradesExport;
use App\Imports\GradesImport;
use Maatwebsite\Excel\Facades\Excel;

class GradeController extends Controller
{
    // Halaman Form Input Nilai per Kelas & Mapel
    public function index(Request $request)
    {
        $classes = ClassModel::orderBy('name')->get();
        
        // Ambil hanya mapel yang tidak punya anak ATAU mapel turunan/sub-mapel (bukan mapel induk seperti PAI/Mulok)
        $subjects = Subject::whereNotNull('parent_id')
            ->orWhereDoesntHave('children')
            ->orderBy('order_no')
            ->get();

        $selectedClassId = $request->query('class_id', $classes->first()->id ?? null);
        $selectedSubjectId = $request->query('subject_id', $subjects->first()->id ?? null);

        $students = [];
        $grades = [];
        $currentClass = null;

        if ($selectedClassId) {
            $currentClass = ClassModel::find($selectedClassId);
            $students = Student::where('class_id', $selectedClassId)->get();

            if ($selectedSubjectId) {
                $grades = AstsGrade::whereIn('student_id', $students->pluck('id'))
                    ->where('subject_id', $selectedSubjectId)
                    ->pluck('score', 'student_id')
                    ->toArray();
            }
        }

        return view('grades.index', compact('classes', 'subjects', 'selectedClassId', 'selectedSubjectId', 'students', 'grades', 'currentClass'));
    }

    // Simpan Nilai Massal
    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'scores' => 'required|array',
        ]);

        $class = ClassModel::findOrFail($request->class_id);

        // Proteksi Kunci Nilai oleh Wali Kelas
        if ($class->is_locked && auth()->user()->role !== 'admin') {
            return back()->with('error', 'Gagal! Nilai untuk kelas ini telah dikunci oleh Wali Kelas/Admin.');
        }

        // Loop dan simpan nilai sesuai subject_id yang dipilih di form
        foreach ($request->scores as $studentId => $score) {
            if ($score !== null && $score !== '') {
                AstsGrade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'subject_id' => $request->subject_id, // Menggunakan ID Mapel yang dikirim form
                    ],
                    [
                        'teacher_id' => auth()->id(),
                        'score' => $score,
                    ]
                );
            }
        }

        return redirect()->route('grades.index', [
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
        ])->with('success', 'Nilai ASTS berhasil disimpan!');
    }
    
    public function downloadTemplate(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);
    
        $class = ClassModel::findOrFail($request->class_id);
        $subject = Subject::findOrFail($request->subject_id);
    
        $fileName = "Template_Nilai_{$class->name}_{$subject->code}.xlsx";
    
        return Excel::download(new GradesExport($request->class_id, $request->subject_id), $fileName);
    }
    
    public function import(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);
    
        $class = ClassModel::findOrFail($request->class_id);
    
        if ($class->is_locked && auth()->user()->role !== 'admin') {
            return back()->with('error', 'Gagal! Nilai untuk kelas ini telah dikunci oleh Wali Kelas/Admin.');
        }
    
        try {
            Excel::import(new GradesImport($request->subject_id, auth()->id()), $request->file('file'));
    
            return redirect()->route('grades.index', [
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
            ])->with('success', 'Berhasil mengimpor nilai dari file Excel!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses file Excel: ' . $e->getMessage());
        }
    }
}