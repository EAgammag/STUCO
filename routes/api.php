<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\IncidentController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| RESTful API endpoints for external integrations. All routes are prefixed
| with /api and use Sanctum token authentication where required.
|
*/

// Public routes (no authentication required)
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

// Protected routes (require Sanctum token)
Route::middleware('auth:sanctum')->group(function () {
    // Authentication
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::get('/me', [AuthController::class, 'me'])->name('api.me');

    // Users (Administrator only)
    Route::middleware('role:administrator')->group(function () {
        Route::apiResource('users', UserController::class)->names([
            'index' => 'api.users.index',
            'store' => 'api.users.store',
            'show' => 'api.users.show',
            'update' => 'api.users.update',
            'destroy' => 'api.users.destroy',
        ]);
    });

    // Students (Staff and Administrator)
    Route::middleware('role:staff|administrator')->group(function () {
        Route::apiResource('students', StudentController::class)->names([
            'index' => 'api.students.index',
            'store' => 'api.students.store',
            'show' => 'api.students.show',
            'update' => 'api.students.update',
            'destroy' => 'api.students.destroy',
        ]);

        Route::apiResource('incidents', IncidentController::class)->names([
            'index' => 'api.incidents.index',
            'store' => 'api.incidents.store',
            'show' => 'api.incidents.show',
            'update' => 'api.incidents.update',
            'destroy' => 'api.incidents.destroy',
        ]);
    });
});
