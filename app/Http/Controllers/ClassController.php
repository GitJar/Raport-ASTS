<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Http\Request;
use App\Exports\ClassesExport;
use App\Imports\ClassesImport;
use Maatwebsite\Excel\Facades\Excel;

class ClassController extends Controller
{
    public function index()
    {
        $classes = ClassModel::with(['teacher', 'students'])->orderBy('name')->get();
        $teachers = User::whereIn('role', ['wali_kelas', 'admin', 'guru_mapel'])->orderBy('name')->get();

        return view('classes.index', compact('classes', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'phase' => 'required|string|max:10',
            'academic_year' => 'required|string|max:20',
            'semester' => 'required|in:Ganjil,Genap',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        ClassModel::create([
            'name' => strtoupper($request->name),
            'phase' => strtoupper($request->phase),
            'academic_year' => $request->academic_year,
            'semester' => $request->semester,
            'teacher_id' => $request->teacher_id,
            'is_locked' => false,
        ]);

        return back()->with('success', 'Data Kelas Berhasil Ditambahkan!');
    }

    // Toggle Kunci / Buka Kunci Nilai Kelas
    public function toggleLock($id)
    {
        $class = ClassModel::findOrFail($id);
        $class->is_locked = !$class->is_locked;
        $class->save();

        $status = $class->is_locked ? 'Dikunci' : 'Dibuka';
        return back()->with('success', "Status Nilai Kelas {$class->name} Berhasil {$status}!");
    }

    public function destroy($id)
    {
        ClassModel::findOrFail($id)->delete();
        return back()->with('success', 'Data Kelas Berhasil Dihapus!');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'phase' => 'required|string|max:10',
            'academic_year' => 'required|string|max:20',
            'semester' => 'required|in:Ganjil,Genap',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $class = ClassModel::findOrFail($id);
        $class->update([
            'name' => strtoupper($request->name),
            'phase' => strtoupper($request->phase),
            'academic_year' => $request->academic_year,
            'semester' => $request->semester,
            'teacher_id' => $request->teacher_id,
        ]);

        return back()->with('success', 'Data Kelas Berhasil Diperbarui!');
    }
    public function downloadTemplate()
{
    return Excel::download(new ClassesExport, 'Template_Import_Kelas.xlsx');
}

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);
    
        try {
            Excel::import(new ClassesImport, $request->file('file'));
            return back()->with('success', 'Data kelas berhasil di-import dari Excel!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimpor kelas: ' . $e->getMessage());
        }
    }
}