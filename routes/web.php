<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/**
 * Dashboard routes
 */
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

/**
 * Profile routes
 */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/**
 * Task routes
 */
Route::resource('tasks', TaskController::class)->middleware(['auth', 'verified']);
Route::put('tasks/{id}/status', [TaskController::class, 'changeStatus'])->middleware(['auth', 'verified'])->name('tasks.changeStatus');

/**
 * Reminder routes
 */
Route::put('reminders/{id}/status', [ReminderController::class, 'changeStatus'])->middleware(['auth', 'verified'])->name('reminders.changeStatus');

require __DIR__ . '/auth.php';
