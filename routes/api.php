<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/ping', function () {
return response()->json(['message' => 'pong']);
});

Route::get('/status', function () {
    return response()->json([
        "app" => "Todo API",
        "status" => "running"
    ]);
});

Route::get('/greet/{name}', function ($name) {
    return response()->json([
        "message" => "Hello, " . $name . "!"
    ]);
});