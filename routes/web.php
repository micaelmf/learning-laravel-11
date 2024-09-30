<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\TaskController;
use App\Http\Middleware\MetricsMiddleware;
use Illuminate\Support\Facades\Route;
use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Prometheus\Storage\InMemory;

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
Route::resource('tasks', TaskController::class)->middleware(['auth', 'verified', MetricsMiddleware::class]);
Route::put('tasks/{id}/status', [TaskController::class, 'changeStatus'])->middleware(['auth', 'verified'])->name('tasks.changeStatus');

/**
 * Reminder routes
 */
Route::put('reminders/{id}/status', [ReminderController::class, 'changeStatus'])->middleware(['auth', 'verified'])->name('reminders.changeStatus');

/**
 * Metrics route
 */
Route::get('/metrics', function () {
    $registry = new CollectorRegistry(new InMemory());
    $renderer = new RenderTextFormat();

    $metrics = $renderer->render($registry->getMetricFamilySamples());
    return response($metrics)->header('Content-Type', 'text/plain');
});

require __DIR__ . '/auth.php';
