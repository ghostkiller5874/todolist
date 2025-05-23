<?php

use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route::middleware()->group(function(){ // ativar a autenticacao apos colocar rota de login "auth:sanctum"
    
// });
Route::apiResource("tasks", TaskController::class);
Route::get("/tasks/deleted", [TaskController::class, 'deleted']);