<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// use if with middleware
// Route::prefix('auth')->middleware('guest')->group(function () {
//     Route::post('/login', [AuthController::class, 'login']);
//     Route::apiResource('/register', AuthController::class);
// });

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::apiResource('/register', AuthController::class);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function () {
    return 'API is working';
});
