<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// Route::middleware("auth:sanctum")->group(function(){ // ativar a autenticacao apos colocar rota de login "auth:sanctum"
    // para validacao de usuario
    Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResource("tasks", TaskController::class);
    Route::get("/tasks/deleted", [TaskController::class, 'deleted']);
// });

// Route::post('/login', [AuthController::class, 'login']); 
// Route::post('/register', [AuthController::class, 'register']); 