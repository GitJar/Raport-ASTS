<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Student;
use App\Imports\StudentsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Jika Wali Kelas, filter daftar kelas hanya kelas miliknya
        if ($user->role === 'wali_kelas') {
            $classes = ClassModel::where('teacher_id', $user->id)->orderBy('name')->get();
        } else {
            $classes = ClassModel::orderBy('name')->get();
        }

        $selectedClassId = $request->query('class_id', $classes->first()->id ?? null);
        $students = [];

        if ($selectedClassId) {
            $currentClass = ClassModel::find($selectedClassId);

            // Validasi akses kelas
            if ($currentClass && !$currentClass->canBeAccessedBy($user)) {
                abort(403, 'Anda tidak memiliki hak akses untuk mengelola data siswa di kelas ini.');
            }

            $students = Student::where('class_id', $selectedClassId)->get();
        }

        return view('students.index', compact('classes', 'selectedClassId', 'students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'name' => 'required|string|max:255',
            'nis' => 'required|string|max:50',
            'nisn' => 'required|string|max:50',
            'gender' => 'required|in:L,P',
        ]);

        $class = ClassModel::findOrFail($request->class_id);

        if (!$class->canBeAccessedBy(auth()->user())) {
            return back()->with('error', 'Akses ditolak! Anda bukan Wali Kelas di kelas ini.');
        }

        Student::create($request->all());

        return back()->with('success', 'Data siswa berhasil ditambahkan!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        $class = ClassModel::findOrFail($request->class_id);

        if (!$class->canBeAccessedBy(auth()->user())) {
            return back()->with('error', 'Akses ditolak! Anda bukan Wali Kelas di kelas ini.');
        }

        try {
            Excel::import(new StudentsImport($request->class_id), $request->file('file'));
            return back()->with('success', 'Data siswa berhasil di-import!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses file Excel: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);

        if (!$student->class->canBeAccessedBy(auth()->user())) {
            return back()->with('error', 'Akses ditolak! Anda bukan Wali Kelas dari siswa ini.');
        }

        $student->delete();
        return back()->with('success', 'Data siswa berhasil dihapus!');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|max:50',
            'nisn' => 'required|string|max:50',
            'gender' => 'required|in:L,P',
        ]);

        $student = Student::findOrFail($id);

        if (!$student->class->canBeAccessedBy(auth()->user())) {
            return back()->with('error', 'Akses ditolak! Anda bukan Wali Kelas dari siswa ini.');
        }

        $student->update([
            'name' => strtoupper($request->name),
            'nis' => $request->nis,
            'nisn' => $request->nisn,
            'gender' => $request->gender,
        ]);

        return back()->with('success', 'Data siswa berhasil diperbarui!');
    }
}