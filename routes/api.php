<?php
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// CRUD Student
Route::post('/students', [StudentController::class, 'store']);

Route::get('/students', [StudentController::class, 'index']); 

Route::put('/students/{nim}', [StudentController::class, 'update']);
Route::patch('/students/{nim}', [StudentController::class, 'update']);

Route::delete('/students/{nim}', [StudentController::class, 'destroy']);