<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('student.login');
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
        Route::post('attendance/reset', [DashboardController::class, 'resetAttendance'])->name('attendance.reset');
        
        // QR Attendance AJAX Routes
        Route::post('attendance/qr/init', [DashboardController::class, 'qrSessionInit'])->name('attendance.qr.init');
        Route::post('attendance/qr/refresh', [DashboardController::class, 'qrRefresh'])->name('attendance.qr.refresh');
        Route::get('attendance/qr/students', [DashboardController::class, 'getSessionStudents'])->name('attendance.qr.students');
        Route::post('attendance/qr/close', [DashboardController::class, 'qrSessionClose'])->name('attendance.qr.close');
        Route::post('attendance/qr/toggle-geofence', [DashboardController::class, 'qrToggleGeofence'])->name('attendance.qr.toggle_geofence');
        Route::post('attendance/qr/mark-ocr', [DashboardController::class, 'qrMarkStudentByRollNumber'])->name('attendance.qr.mark_ocr');
        Route::post('attendance/qr/request-facial', [DashboardController::class, 'qrRequestFacial'])->name('attendance.qr.request_facial');
        
        Route::get('leave-request', function() { return view('faculty.leave'); })->name('leave');
        Route::get('events', function() { return view('faculty.events'); })->name('events');
    });
});

// Student PWA Routes
use App\Http\Controllers\Student\StudentPwaController;

Route::prefix('student')->name('student.')->group(function () {
    Route::get('login', [StudentPwaController::class, 'showLoginForm'])->name('login');
    Route::post('login', [StudentPwaController::class, 'login'])->name('login.post');
    Route::post('logout', [StudentPwaController::class, 'logout'])->name('logout');

    Route::middleware(['auth:student'])->group(function () {
        Route::get('dashboard', [StudentPwaController::class, 'dashboard'])->name('dashboard');
        Route::get('scanner', [StudentPwaController::class, 'scanner'])->name('scanner');
        Route::post('attendance/mark', [StudentPwaController::class, 'markAttendance'])->name('attendance.mark');
        Route::get('history', [StudentPwaController::class, 'history'])->name('history');
        Route::get('timetable', [StudentPwaController::class, 'timetable'])->name('timetable');
        Route::get('profile', [StudentPwaController::class, 'profile'])->name('profile');
        
        // Face Registration
        Route::get('face-register', [StudentPwaController::class, 'faceRegisterView'])->name('face_register');
        Route::post('face-register', [StudentPwaController::class, 'storeFaceDescriptor'])->name('face_register.store');

        // Club & Event Attendance Management
        Route::get('club', [StudentPwaController::class, 'clubIndex'])->name('club.index');
        Route::get('club/session/{club_id}', [StudentPwaController::class, 'clubSession'])->name('club.session');
        Route::post('club/qr/init', [StudentPwaController::class, 'clubQrInit'])->name('club.qr.init');
        Route::post('club/qr/refresh', [StudentPwaController::class, 'clubQrRefresh'])->name('club.qr.refresh');
        Route::post('club/qr/close', [StudentPwaController::class, 'clubQrClose'])->name('club.qr.close');
        Route::get('club/qr/students', [StudentPwaController::class, 'getClubSessionStudents'])->name('club.qr.students');
        Route::post('club/attendance/submit', [StudentPwaController::class, 'clubSubmitAttendance'])->name('club.attendance.submit');
        Route::post('club/attendance/geo', [StudentPwaController::class, 'clubGeoMark'])->name('club.attendance.geo');
        Route::get('club/attendance-session/{uuid}', [StudentPwaController::class, 'clubLiveAttendanceView'])->name('club.attendance.live');
    });
});

// Student App APIs (Legacy & PWA AJAX)
Route::prefix('api/v1')->group(function() {
    Route::post('attendance/mark-qr', [DashboardController::class, 'markAttendanceByQR']);
});

// Club Dedicated Panel Routes
use App\Http\Controllers\Club\ClubLoginController;
use App\Http\Controllers\Club\DashboardController as ClubDashboardController;

Route::prefix('club')->name('club.')->group(function () {
    Route::get('login', [ClubLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [ClubLoginController::class, 'login'])->name('login.post');
    Route::post('logout', [ClubLoginController::class, 'logout'])->name('logout');

    Route::middleware(['auth:club'])->group(function () {
        Route::get('dashboard', [ClubDashboardController::class, 'index'])->name('dashboard');
        Route::get('members', [ClubDashboardController::class, 'members'])->name('members');
        Route::post('members/add', [ClubDashboardController::class, 'addMember'])->name('members.add');
        Route::post('members/{id}/update', [ClubDashboardController::class, 'updateMember'])->name('members.update');
        Route::post('members/{id}/remove', [ClubDashboardController::class, 'removeMember'])->name('members.remove');
        Route::get('groups', [ClubDashboardController::class, 'groups'])->name('groups');
        Route::post('groups/add', [ClubDashboardController::class, 'addGroup'])->name('groups.add');
        Route::post('groups/{id}/update', [ClubDashboardController::class, 'updateGroup'])->name('groups.update');
        Route::post('groups/{id}/remove', [ClubDashboardController::class, 'removeGroup'])->name('groups.remove');
        Route::get('events', [ClubDashboardController::class, 'events'])->name('events');
        Route::post('events/add', [ClubDashboardController::class, 'addEvent'])->name('events.add');
        Route::post('events/{id}/update', [ClubDashboardController::class, 'updateEvent'])->name('events.update');
        Route::post('events/{id}/remove', [ClubDashboardController::class, 'removeEvent'])->name('events.remove');
        Route::get('locations/search', [ClubDashboardController::class, 'searchLocations'])->name('locations.search');
        
        Route::get('attendance', [ClubDashboardController::class, 'attendance'])->name('attendance');
        Route::get('attendance/event/{event_id}/init', [ClubDashboardController::class, 'attendanceInitEvent'])->name('attendance.init_event');
        Route::post('attendance/adhoc/start', [ClubDashboardController::class, 'attendanceStartAdhoc'])->name('attendance.start_adhoc');
        Route::get('attendance/session/{session_id}', [ClubDashboardController::class, 'attendanceManage'])->name('attendance.manage');
        Route::post('attendance/session/{session_id}/mark', [ClubDashboardController::class, 'attendanceMark'])->name('attendance.mark');
        Route::post('attendance/session/{session_id}/qr-refresh', [ClubDashboardController::class, 'qrRefresh'])->name('attendance.qr.refresh');
        Route::post('attendance/session/{session_id}/geo-toggle', [ClubDashboardController::class, 'toggleGeoLocation'])->name('attendance.geo.toggle');
    });
});
