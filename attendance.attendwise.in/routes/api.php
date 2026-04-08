<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentAuthController;

/*
|--------------------------------------------------------------------------
| Student Mobile App Auth Routes
|--------------------------------------------------------------------------
*/
Route::prefix('student/auth')->group(function () {
    // Public route — no token required
    Route::post('/login', [StudentAuthController::class, 'login']);
    Route::post('/log-error', [\App\Http\Controllers\AppErrorController::class, 'store']);

    // Protected routes — require Sanctum token
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout',     [StudentAuthController::class, 'logout']);
        Route::post('/logout-all', [StudentAuthController::class, 'logoutAll']);
    });
});

/*
|--------------------------------------------------------------------------
| Authenticated Student Routes (add future endpoints here)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->prefix('student')->group(function () {
    Route::get('/me', function (Request $request) {
        return $request->user();
    });
});
