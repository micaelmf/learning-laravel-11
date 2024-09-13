<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

Route::resource('tasks', TaskController::class);
Route::put('tasks/{id}/status', [TaskController::class, 'changeStatus'])->name('tasks.changeStatus');
