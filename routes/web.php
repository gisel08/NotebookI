<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/tasks', function () {
        return view('tasks');
    })->name('tasks');

    // Rutas para la Gestión de Tareas
    Route::resource('tasks', TaskController::class);

    // TODO: Aquí añadirías las rutas para CommentController y NoteController
    // Route::resource('comments', CommentController::class);
    // Route::resource('notes', NoteController::class);
});

Route::get('/tareas', [TaskController::class, 'index'])->name('tasks');