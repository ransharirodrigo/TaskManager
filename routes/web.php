<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TaskController::class,"index"])->name("tasks.index");
Route::get('/tasks', [TaskController::class, 'getAllTasks'])->name("tasks.fetchAllTasks");
Route::get('/tasks/{id}/update-task-status', [TaskController::class, 'updateTaskStatus'])->name('tasks.updateTaskStatus');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
