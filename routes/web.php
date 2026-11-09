<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskWebController;
use App\Http\Middleware\SiswaMiddleware;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/tasks', [TaskWebController::class, 'index'])->name('tasks.index');

    Route::get('/tasks/{task}', [TaskWebController::class, 'show'])->whereNumber('task')->name('tasks.show');

    Route::middleware(SiswaMiddleware::class)->group(function () {
        Route::post('/tasks/{task}/toggle-complete', [TaskWebController::class, 'toggleComplete'])->whereNumber('task')->name('tasks.toggle-complete');
    });
});