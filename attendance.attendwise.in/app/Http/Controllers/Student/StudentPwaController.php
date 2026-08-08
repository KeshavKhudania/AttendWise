<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Schedule;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\StudentSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentPwaController extends Controller
{
    /**
     * Show PWA Login Page.
     */
    public function showLoginForm()
    {
        if (Auth::guard('student')->check()) {
            return redirect()->route('student.dashboard');
        }

        return view('student.auth.login');
    }

    /**
     * Handle PWA Login & Device Lock.
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'roll_number' => 'required|string',
            'login'       => 'required|string',
            'password'    => 'required|string',
            'device_id'   => 'nullable|string',
        ]);

        $deviceId = $validated['device_id'] ?? Str::uuid()->toString();

        // 1. Check Static Demo Credentials
        $isDemo = ($validated['roll_number'] === '101' && 
                   $validated['login'] === 'demo@attendwise.in' && 
                   $validated['password'] === 'password');

        if ($isDemo) {
            $student = Student::first();
        } else {
            $loginHash = search_hash($validated['login']);

            $student = Student::where('roll_number', $validated['roll_number'])
                              ->where(function ($query) use ($loginHash) {
                                  $query->where('email_hash', $loginHash)
                                        ->orWhere('mobile_hash', $loginHash);
                              })
                              ->first();
        }

        if (!$student) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Invalid credentials or roll number.'], 401);
            }
            return back()->withErrors(['login' => 'Invalid roll number, email/mobile or password.'])->withInput();
        }

        // 2. Status Check
        if ($student->status != 1) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Your account is deactivated.'], 403);
            }
            return back()->withErrors(['login' => 'Your account has been deactivated by the institution.']);
        }

        // 3. Verify Password
        if (!$isDemo) {
            if ($validated['password'] !== $student->password) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Invalid credentials.'], 401);
                }
                return back()->withErrors(['login' => 'Invalid credentials.'])->withInput();
            }
        }

        // 4. Enforce Single Device Lock
        $student->enforceSingleDeviceLogin($deviceId, null);
        if ($student->session) {
            $student->session->update(['platform' => 'pwa_web']);
        }

        // Store device ID in Laravel Session
        session(['student_device_id' => $deviceId]);

        // 5. Authenticate Web Guard
        Auth::guard('student')->login($student);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'device_id' => $deviceId,
                'redirect' => route('student.dashboard')
            ]);
        }

        return redirect()->intended(route('student.dashboard'));
    }

    /**
     * Student PWA Dashboard.
     */
    public function dashboard()
    {
        $student = Auth::guard('student')->user();
        if (!$student) {
            return redirect()->route('student.login');
        }

        $student->load(['institution']);

        $today = Carbon::today()->format('Y-m-d');
        $dayOfWeek = Carbon::now()->format('l');

        // Fetch Today's Schedules for student's section or class group
        $todaySchedules = Schedule::with(['subject', 'faculty', 'classroom.block'])
            ->where('institution_id', $student->institution_id)
            ->where(function($query) use ($student) {
                if ($student->section_id) {
                    $query->where('section_id', $student->section_id);
                }
                if ($student->class_group_id) {
                    $query->orWhere('class_group_id', $student->class_group_id);
                }
            })
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('start_time')
            ->get()
            ->map(function($schedule) use ($student, $today) {
                $record = AttendanceRecord::where('student_id', $student->id)
                    ->where('schedule_id', $schedule->id)
                    ->where('date', $today)
                    ->first();

                $activeSession = AttendanceSession::where('schedule_id', $schedule->id)
                    ->where('date', $today)
                    ->where('status', 'active')
                    ->first();

                $schedule->attendance_status = $record ? $record->status : null;
                $schedule->is_session_active = (bool) $activeSession;
                $schedule->active_session_uuid = $activeSession ? $activeSession->uuid : null;
                return $schedule;
            });

        // Attendance Overall Statistics
        $allRecords = AttendanceRecord::where('student_id', $student->id)->get();
        $totalClasses = $allRecords->count();
        $presentCount = $allRecords->where('status', 'present')->count();
        $absentCount  = $allRecords->where('status', 'absent')->count();
        $lateCount    = $allRecords->where('status', 'late')->count();

        $percentage = $totalClasses > 0 ? round((($presentCount + $lateCount) / $totalClasses) * 100, 1) : 100;

        return view('student.dashboard', compact(
            'student',
            'todaySchedules',
            'totalClasses',
            'presentCount',
            'absentCount',
            'lateCount',
            'percentage'
        ));
    }

    /**
     * QR Code Scanner View.
     */
    public function scanner()
    {
        $student = Auth::guard('student')->user();
        return view('student.scanner', compact('student'));
    }

    /**
     * Mark Attendance by Scanning Faculty Dynamic QR Code.
     */
    public function markAttendance(Request $request)
    {
        $student = Auth::guard('student')->user();
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Unauthorized session.'], 401);
        }

        $validated = $request->validate([
            'payload'   => 'required|string',
            'device_id' => 'nullable|string',
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        // Validate Single-Device Policy
        $currentDeviceId = $validated['device_id'] ?? session('student_device_id');
        $activeSessionRecord = StudentSession::where('student_id', $student->id)->first();
        if ($activeSessionRecord && $currentDeviceId && $activeSessionRecord->device_id !== $currentDeviceId) {
            Auth::guard('student')->logout();
            return response()->json([
                'success' => false,
                'device_locked' => true,
                'message' => 'Account signed in on another device. Please re-login.'
            ], 403);
        }

        $payload = $validated['payload'];
        $parts = explode('|', $payload);
        if (count($parts) !== 2) {
            return response()->json(['success' => false, 'message' => 'Invalid or unrecognized QR Code.'], 400);
        }

        $sessionUuid = $parts[0];
        $timestamp = (int)$parts[1];

        // Check 15-second dynamic QR validity window
        if (abs(now()->timestamp - $timestamp) > 15) {
            return response()->json(['success' => false, 'message' => 'QR Code has expired. Please wait for the screen to refresh.'], 403);
        }

        $session = AttendanceSession::with(['schedule.subject', 'faculty'])
            ->where('uuid', $sessionUuid)
            ->first();

        if (!$session || $session->status !== 'active') {
            return response()->json(['success' => false, 'message' => 'Attendance session is closed or inactive.'], 404);
        }

        // Check if attendance already marked
        $existingRecord = AttendanceRecord::where('student_id', $student->id)
            ->where('schedule_id', $session->schedule_id)
            ->where('date', $session->date)
            ->first();

        if ($existingRecord && $existingRecord->status === 'present') {
            return response()->json([
                'success' => true,
                'already_marked' => true,
                'message' => 'Your attendance is already marked Present for this class.',
                'subject' => $session->schedule->subject->name ?? 'Class',
                'time' => Carbon::now()->format('h:i A')
            ]);
        }

        AttendanceRecord::updateOrCreate(
            [
                'institution_id' => $session->institution_id,
                'student_id'     => $student->id,
                'schedule_id'    => $session->schedule_id,
                'date'           => $session->date,
            ],
            [
                'attendance_session_id' => $session->id,
                'marked_by_faculty_id' => $session->faculty_id,
                'status'                => 'present',
                'remarks'               => 'Marked via PWA QR Scanner',
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Attendance Marked Successfully!',
            'subject' => $session->schedule->subject->name ?? 'Subject',
            'faculty' => $session->faculty->name ?? 'Faculty',
            'time'    => Carbon::now()->format('h:i A')
        ]);
    }

    /**
     * Student Attendance History Page.
     */
    public function history(Request $request)
    {
        $student = Auth::guard('student')->user();
        if (!$student) return redirect()->route('student.login');

        $query = AttendanceRecord::with(['session.schedule.subject', 'session.faculty'])
            ->where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->has('status') && in_array($request->status, ['present', 'absent', 'late'])) {
            $query->where('status', $request->status);
        }

        $records = $query->paginate(15);

        // Subject Breakdown
        $subjectStats = AttendanceRecord::where('student_id', $student->id)
            ->select('schedule_id', DB::raw('count(*) as total'), DB::raw("SUM(CASE WHEN status='present' THEN 1 ELSE 0 END) as present_count"))
            ->groupBy('schedule_id')
            ->get()
            ->map(function($stat) {
                $schedule = Schedule::with('subject')->find($stat->schedule_id);
                $stat->subject_name = $schedule->subject->name ?? 'General Class';
                $stat->percentage = $stat->total > 0 ? round(($stat->present_count / $stat->total) * 100, 1) : 0;
                return $stat;
            });

        return view('student.history', compact('student', 'records', 'subjectStats'));
    }

    /**
     * Student Schedule/Timetable Page.
     */
    public function timetable()
    {
        $student = Auth::guard('student')->user();
        if (!$student) return redirect()->route('student.login');

        $schedules = Schedule::with(['subject', 'faculty', 'classroom.block'])
            ->where('institution_id', $student->institution_id)
            ->where(function($query) use ($student) {
                if ($student->section_id) {
                    $query->where('section_id', $student->section_id);
                }
                if ($student->class_group_id) {
                    $query->orWhere('class_group_id', $student->class_group_id);
                }
            })
            ->get();

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $groupedSchedules = $schedules->groupBy('day_of_week');

        return view('student.timetable', compact('student', 'groupedSchedules', 'days'));
    }

    /**
     * Digital Student Profile / ID Card.
     */
    public function profile()
    {
        $student = Auth::guard('student')->user();
        if (!$student) return redirect()->route('student.login');

        $student->load(['institution', 'department', 'session']);
        return view('student.profile', compact('student'));
    }

    /**
     * Student Logout.
     */
    public function logout(Request $request)
    {
        $student = Auth::guard('student')->user();
        if ($student) {
            $student->session()->delete();
        }

        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'redirect' => route('student.login')]);
        }

        return redirect()->route('student.login');
    }
}
