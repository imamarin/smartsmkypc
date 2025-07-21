<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SiswaController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// route yang butuh token
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/siswa', [SiswaController::class, 'index']);
    Route::get('/siswa/class/{class}', [SiswaController::class, 'getSiswaByClass']);
});
