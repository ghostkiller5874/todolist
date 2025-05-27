<?php

use App\Http\Controllers\AuthControllerWeb;
use App\Http\Controllers\TaskControllerWeb;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() 
        ? redirect()->route('tasks.index') 
        : redirect()->route('login');
});

Route::get('/login', [AuthControllerWeb::class, 'showLogin'])->name('login');
Route::post('/login', [AuthControllerWeb::class, 'login']);

Route::get('/register', [AuthControllerWeb::class, 'showRegister']);
Route::post('/register', [AuthControllerWeb::class, 'register'])->name('register');

Route::post('/logout', [AuthControllerWeb::class, 'logout'])->name('logout');


Route::middleware('auth:web')->group(function () {
    Route::get('/tasks', [TaskControllerWeb::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TaskControllerWeb::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskControllerWeb::class, 'store'])->name('tasks.store');
    
    Route::get('/tasks/{id}', [TaskControllerWeb::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{id}', [TaskControllerWeb::class, 'update'])->name('tasks.update');

    Route::delete('/tasks/{id}', [TaskControllerWeb::class, 'destroy'])->name('tasks.destroy');

    Route::get('/tasks-deleted', [TaskControllerWeb::class, 'deleted'])->name('tasks.deleted');
    Route::post('/tasks/{id}/restore', [TaskControllerWeb::class, 'restore'])->name('tasks.restore');
});
