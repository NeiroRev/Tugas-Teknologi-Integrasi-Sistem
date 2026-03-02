<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [HomeController::class, 'index']);

Route::get('/hello', function () {
    return "Hello Laravel!";
});

Route::get('/about', function () {
    return "Nama: Constanta Brian Krisna Arienta - NIM: 245150707111017"; 
});

Route::get('/home', [HomeController::class, 'index']);
