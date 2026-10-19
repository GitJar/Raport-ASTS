<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ReportManagementController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route Profile Bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route Input Nilai ASTS
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::post('/grades', [GradeController::class, 'store'])->name('grades.store');

    // Route Cetak Raport
    Route::get('/report/print/{student}', [ReportController::class, 'printSingle'])->name('report.print');

    // Route Pengaturan Raport
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
    Route::get('/reports', [ReportManagementController::class, 'index'])->name('reports.index');
    Route::get('/classes', [ClassController::class, 'index'])->name('classes.index');
    Route::post('/classes', [ClassController::class, 'store'])->name('classes.store');
    Route::post('/classes/{id}/toggle-lock', [ClassController::class, 'toggleLock'])->name('classes.toggle-lock');
    Route::delete('/classes/{id}', [ClassController::class, 'destroy'])->name('classes.destroy');
    Route::get('/summaries', [SummaryController::class, 'index'])->name('summaries.index');
    Route::post('/summaries', [SummaryController::class, 'store'])->name('summaries.store');
    Route::get('/report/print-bulk/{class}', [ReportController::class, 'printBulk'])->name('report.print-bulk');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::post('/users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/grades/template', [GradeController::class, 'downloadTemplate'])->name('grades.template');
    Route::post('/grades/import', [GradeController::class, 'import'])->name('grades.import');
    Route::get('/summaries/template', [SummaryController::class, 'downloadTemplate'])->name('summaries.template');
    Route::post('/summaries/import', [SummaryController::class, 'import'])->name('summaries.import');
    Route::put('/classes/{id}', [ClassController::class, 'update'])->name('classes.update');
    Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::get('/reports/leger/{class}', [ReportManagementController::class, 'leger'])->name('reports.leger');
});

require __DIR__.'/auth.php';