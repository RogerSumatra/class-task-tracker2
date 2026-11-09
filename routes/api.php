<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskCompletionController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Middleware\GuruMiddleware;
use App\Http\Middleware\SiswaMiddleware;
use Illuminate\Support\Facades\Route;


// Public auth endpoints
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('api.auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('api.auth.login');
});

// Protected endpoints
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
    Route::get('/user', [AuthController::class, 'me'])->name('api.user');

    // Tasks — readable by all authenticated users
    Route::get('/tasks', [TaskController::class, 'index'])->name('api.tasks.index');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->whereNumber('task')->name('api.tasks.show');

    // Task CRUD — guru only
    Route::middleware(GuruMiddleware::class)->group(function () {
        Route::post('/tasks', [TaskController::class, 'store'])->name('api.tasks.store');
        Route::put('/tasks/{task}', [TaskController::class, 'update'])->whereNumber('task')->name('api.tasks.update');
        Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->whereNumber('task')->name('api.tasks.destroy');
    });

    // Task completion — siswa only
    Route::middleware(SiswaMiddleware::class)->group(function () {
        Route::post('/tasks/{task}/complete', [TaskCompletionController::class, 'store'])->whereNumber('task')->name('api.tasks.complete');
        Route::delete('/tasks/{task}/complete', [TaskCompletionController::class, 'destroy'])->whereNumber('task')->name('api.tasks.uncomplete');
    });
});
