<?php

use App\Http\Controllers\AttendanceLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobPostController;
use App\Http\Controllers\HR\EmployeeController as HR_EmployeeController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\LeaveTypeController;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// use if with middleware
// Route::prefix('auth')->middleware('guest')->group(function () {
//     Route::post('/login', [AuthController::class, 'login']);
//     Route::apiResource('/register', AuthController::class); 
// });
Route::prefix('public')->group(function () {
    Route::apiResource('job-posts', JobPostController::class)->only(['index', 'show']);
    Route::apiResource('job-applications', JobApplicationController::class)->only(['store', 'show']);

    Route::get('/attendance-logs/latest', [AttendanceLogController::class, 'latest']);
    Route::apiResource('attendance-logs', AttendanceLogController::class);
});

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::apiResource('/register', AuthController::class);
});


Route::prefix('hr')->group(function () {
    Route::post('/job-applications/{jobApplication}/status', [JobApplicationController::class, 'updateStatus']);
    Route::apiResource('leave-types', LeaveTypeController::class);
    Route::apiResource('leave-requests', LeaveRequestController::class);

    Route::apiResource('employees', HR_EmployeeController::class);
    Route::apiResource('job-applications', JobApplicationController::class);
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
