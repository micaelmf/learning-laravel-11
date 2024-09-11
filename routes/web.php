<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

Route::resource('tasks', TaskController::class);
Route::put('tasks/{id}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
Route::get('search', [TaskController::class, 'search'])->name('tasks.search');
