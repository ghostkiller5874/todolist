<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware("auth:sanctum")->group(function(){ 
    Route::get('/tasks/deleted', [TaskController::class, 'deleted']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::apiResource("tasks", TaskController::class);
});

Route::post('/login', [AuthController::class, 'login']); 
Route::post('/register', [AuthController::class, 'register']); 


// Route::middleware('auth:sanctum')->get('/test-auth', function() {
//     return response()->json([
//         'user_id' => auth()->id(),
//         'user' => auth()->user(),
//     ]);
// });