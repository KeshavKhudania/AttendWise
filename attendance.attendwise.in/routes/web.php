<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Faculty Routes
use App\Http\Controllers\Auth\FacultyLoginController;
use App\Http\Controllers\Faculty\DashboardController;

Route::prefix('faculty')->name('faculty.')->group(function () {
    Route::get('login', [FacultyLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [FacultyLoginController::class, 'login']);
    Route::post('logout', [FacultyLoginController::class, 'logout'])->name('logout');

    Route::middleware(['auth:faculty'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('profile', [DashboardController::class, 'profile'])->name('profile');
        Route::get('timetable', [DashboardController::class, 'timeTable'])->name('timetable');
        
        // Attendance routes
        Route::get('attendance/{schedule?}', [DashboardController::class, 'attendance'])->name('attendance');
        Route::post('attendance', [DashboardController::class, 'submitAttendance'])->name('attendance.submit');
        
        // QR Attendance AJAX Routes
        Route::post('attendance/qr/init', [DashboardController::class, 'qrSessionInit'])->name('attendance.qr.init');
        Route::post('attendance/qr/refresh', [DashboardController::class, 'qrRefresh'])->name('attendance.qr.refresh');
        Route::get('attendance/qr/students', [DashboardController::class, 'getSessionStudents'])->name('attendance.qr.students');
        
        Route::get('leave-request', function() { return view('faculty.leave'); })->name('leave');
        Route::get('events', function() { return view('faculty.events'); })->name('events');
    });
});

// Student App APIs
Route::prefix('api/v1')->group(function() {
    Route::post('attendance/mark-qr', [DashboardController::class, 'markAttendanceByQR']);
});

