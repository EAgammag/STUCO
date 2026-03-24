<?php

use App\Http\Controllers\StudentChatController;
use App\Http\Controllers\StudentConductController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Language switcher
Route::get('language/{locale}', function (string $locale) {
    if (in_array($locale, ['en', 'fil'], strict: true)) {
        session(['locale' => $locale]);
    }

    return redirect()->back();
})->name('language.switch');

Route::view('/', 'welcome')
    ->middleware('guest')
    ->name('welcome');

Route::view('/landing', 'landing')
    ->name('landing');

// Role-based dashboard redirect
Route::get('dashboard', function () {
    $user = Auth::user();

    if ($user->hasRole('administrator')) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->hasRole('staff')) {
        return redirect()->route('staff.dashboard');
    }

    if ($user->hasRole('student')) {
        return redirect()->route('student.dashboard');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Student Portal Routes - Protected with Auth and Role Middleware
// CRITICAL: Students should NEVER access /admin/* routes
// These routes are STUDENT-ONLY. Staff have separate admin routes.
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentConductController::class, 'index'])->name('dashboard');
    Route::get('/records', [StudentConductController::class, 'records'])->name('records');
    Route::get('/records/{record}', [StudentConductController::class, 'show'])->name('records.show');
    Route::get('/offense-rules', [StudentConductController::class, 'offenseRules'])->name('offense-rules');
    Route::post('/chat', [StudentChatController::class, 'chat'])->name('chat');
});

// Staff Portal Routes - Incident Reporting System
// Staff members can submit incident reports but cannot access student records
Route::middleware(['auth', 'role:staff|administrator'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\StaffIncidentController::class, 'index'])->name('dashboard');

    // Reporting Routes
    Route::get('/formal-charge', fn () => view('staff.formal-charge'))->name('formal-charge');
    Route::get('/report/create', [\App\Http\Controllers\StaffIncidentController::class, 'create'])->name('report.create');
    Route::post('/report', [\App\Http\Controllers\StaffIncidentController::class, 'store'])->name('report.store');

    // Student Registration Management (Page-based with breadcrumbs)
    Route::get('/students', fn () => view('staff.students.index'))->name('students');
    Route::get('/students/create', fn () => view('staff.students.create'))->name('students.create');
    Route::get('/students/import-template', function () {
        $columns = ['student_id', 'first_name', 'last_name', 'email', 'program', 'college', 'year_level', 'section'];
        $example = ['2024-0001', 'Juan', 'dela Cruz', 'jdelacruz@csu.edu.ph', 'BS Information Technology', 'COLLEGE OF INFORMATION AND COMPUTING SCIENCES', '1st Year', 'A'];

        $csv = implode(',', $columns)."\n".implode(',', $example)."\n";

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sia-masterlist-template.csv"',
        ]);
    })->name('students.import-template');
    Route::get('/students/{student}', fn ($student) => view('staff.students.show', ['studentId' => $student]))->name('students.show');
    Route::get('/students/{student}/edit', fn ($student) => view('staff.students.edit', ['studentId' => $student]))->name('students.edit');

    // View their own submissions only
    Route::get('/report/{report}', [\App\Http\Controllers\StaffIncidentController::class, 'show'])->name('report.show')
        ->middleware('can:view,report'); // Enforces IncidentReportPolicy

    Route::get('/my-reports', [\App\Http\Controllers\StaffIncidentController::class, 'myReports'])->name('my-reports');
});

// Admin Portal Routes
Route::middleware(['auth', 'role:administrator'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
    Route::get('/users', \App\Livewire\Admin\UserManagement::class)->name('users');
    Route::get('/cases', \App\Livewire\Admin\CaseManagement::class)->name('cases');
    Route::get('/audit-logs', \App\Livewire\Admin\AuditLogs::class)->name('audit-logs');
    Route::get('/settings', \App\Livewire\Admin\SystemSettings::class)->name('settings');
});

require __DIR__.'/auth.php';
