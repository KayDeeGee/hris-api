<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobPostController;
use App\Models\JobPost;
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

Route::prefix('public')->group(function () {
    Route::apiResource('job-posts', JobPostController::class)->only(['index', 'show']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // You can add more protected routes here
    Route::apiResource('job-posts', JobPostController::class);
});


Route::get('/test', function () {
    dd('Reached');
});
