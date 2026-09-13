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
            'login'       => 'required',
            'password'    => 'required|string',
            'device_id'   => 'nullable|string',
        ]);
        
        $validated['login'] = (string) $validated['login'];

        $deviceId = $validated['device_id'] ?? Str::uuid()->toString();

        // 1. Check Static Demo Credentials
        $isDemo = ($validated['roll_number'] === '101' && 
                   $validated['login'] === 'demo@attendwise.in' && 
                   $validated['password'] === 'password');

        if ($isDemo) {
            $student = Student::first();
            session(['is_demo_account' => true]);
        } else {
            $loginHash = search_hash($validated['login']);

            $student = Student::where('roll_number', $validated['roll_number'])
                              ->where(function ($query) use ($loginHash) {
                                  $query->where('email_hash', $loginHash)
                                        ->orWhere('mobile_hash', $loginHash);
                              })
                              ->first();
            session()->forget('is_demo_account');
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

        $student->load(['institution', 'clubMemberships' => function($q) {
            $q->where('can_take_attendance', 1)->with('club');
        }]);

        $today = Carbon::today()->format('Y-m-d');
        $dayOfWeek = Carbon::now()->format('l');

        $allClubIds = \App\Models\ClubMember::where('member_id', $student->id)
            ->where('member_type', 'student')
            ->pluck('club_id');

        $activeClubSessions = AttendanceSession::whereIn('club_id', $allClubIds)
            ->where('date', $today)
            ->where(function($q) {
                $q->where('status', 'active')
                  ->orWhere(function($sq) {
                      $sq->where('status', 'scheduled')
                         ->where('start_time', '<=', Carbon::now()->format('H:i:s'))
                         ->where('end_time', '>=', Carbon::now()->format('H:i:s'));
                  });
            })
            ->where('is_geofencing', 1)
            ->with('club')
            ->get()->map(function($session) use ($student, $today) {
                $record = AttendanceRecord::where('student_id', $student->id)
                    ->where('attendance_session_id', $session->id)
                    ->first();
                $session->already_marked = (bool)$record;
                return $session;
            });

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
                    
                $cancelledSession = AttendanceSession::where('schedule_id', $schedule->id)
                    ->where('date', $today)
                    ->where('status', 'cancelled')
                    ->first();

                $schedule->attendance_status = $record ? $record->status : null;
                $schedule->is_session_active = (bool) $activeSession;
                $schedule->active_session_uuid = $activeSession ? $activeSession->uuid : null;
                $schedule->is_cancelled = (bool) $cancelledSession;
                
                return $schedule;
            });

        // Attendance Overall Statistics
        $allRecords = AttendanceRecord::where('student_id', $student->id)
            ->where('status', '!=', 'cancelled')
            ->get();
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

        $isDemoAccount = session('is_demo_account', false);

        // Validate Single-Device Policy
        $currentDeviceId = $validated['device_id'] ?? session('student_device_id');
        $activeSessionRecord = StudentSession::where('student_id', $student->id)->first();
        if (!$isDemoAccount && $activeSessionRecord && $currentDeviceId && $activeSessionRecord->device_id !== $currentDeviceId) {
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
        if (!$isDemoAccount && abs(now()->timestamp - $timestamp) > 15) {
            return response()->json(['success' => false, 'message' => 'QR Code has expired. Please wait for the screen to refresh.'], 403);
        }

        $session = AttendanceSession::with(['schedule.subject', 'schedule.classroom', 'schedule.section', 'faculty'])
            ->where('uuid', $sessionUuid)
            ->first();

        // 1. Session Status Check: Ensure session is explicitly active (not completed/closed)
        if (!$session || $session->status !== 'active') {
            return response()->json(['success' => false, 'message' => 'Attendance session is already closed or inactive. You can no longer mark attendance.'], 403);
        }

        // 2. Strict Date Check: Prevent marking attendance for past or future sessions
        if ($session->date !== \Carbon\Carbon::today()->toDateString()) {
            return response()->json(['success' => false, 'message' => 'Cannot mark attendance for a past or future date.'], 403);
        }

        $isClubSession = !empty($session->club_id);

        // 3. Section Verification: Ensure student belongs to the respective section of this schedule
        $scheduleSectionId = $session->schedule->section_id ?? null;
        if (!$isClubSession && !$isDemoAccount && $scheduleSectionId && (int)$student->section_id !== (int)$scheduleSectionId) {
            $sectionName = $session->schedule->section->name ?? 'another section';
            return response()->json([
                'success' => false, 
                'message' => 'Section Mismatch: This attendance session is specifically for Section "' . $sectionName . '". You are assigned to a different section.'
            ], 403);
        }

        // --- Geolocation Security Verification ---
        $classroom = $session->schedule->classroom ?? null;
        if (!$isClubSession && !$isDemoAccount && $session->is_geofencing && $classroom && $classroom->latitude && $classroom->longitude) {
            $studentLat = $validated['latitude'] ?? null;
            $studentLng = $validated['longitude'] ?? null;

            if (!$studentLat || !$studentLng) {
                return response()->json([
                    'success' => false, 
                    'message' => 'GPS location is required for this class. Please enable location permissions.'
                ], 403);
            }

            // Haversine Formula for distance in meters
            $earthRadius = 6371000; 
            $latFrom = deg2rad($classroom->latitude);
            $lonFrom = deg2rad($classroom->longitude);
            $latTo = deg2rad($studentLat);
            $lonTo = deg2rad($studentLng);

            $latDelta = $latTo - $latFrom;
            $lonDelta = $lonTo - $lonFrom;

            $a = sin($latDelta / 2) * sin($latDelta / 2) +
                 cos($latFrom) * cos($latTo) *
                 sin($lonDelta / 2) * sin($lonDelta / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            $distance = $earthRadius * $c;

            $allowedRadius = 50; // 50 meters strict limit

            if ($distance > $allowedRadius) {
                return response()->json([
                    'success' => false, 
                    'message' => 'You are ' . round($distance) . ' meters away from the classroom. You must be within ' . $allowedRadius . ' meters to mark attendance.'
                ], 403);
            }
        }

        // Check if attendance already marked
        $existingRecordQuery = AttendanceRecord::where('student_id', $student->id)->where('date', $session->date);
        if ($isClubSession) {
            $existingRecordQuery->where('club_id', $session->club_id)->where('attendance_session_id', $session->id);
        } else {
            $existingRecordQuery->where('schedule_id', $session->schedule_id);
        }
        $existingRecord = $existingRecordQuery->first();

        if ($existingRecord && $existingRecord->status === 'present') {
            return response()->json([
                'success' => true,
                'already_marked' => true,
                'message' => 'Your attendance is already marked Present.',
                'subject' => $isClubSession ? ($session->club->name ?? 'Club Session') : ($session->schedule->subject->name ?? 'Class'),
                'time' => Carbon::now()->format('h:i A'),
                'session_uuid' => $session->uuid
            ]);
        }

        $recordData = [
            'attendance_session_id' => $session->id,
            'status'                => 'present',
            'remarks'               => 'Marked via PWA QR Scanner',
        ];

        $matchData = [
            'institution_id' => $session->institution_id,
            'student_id'     => $student->id,
            'date'           => $session->date,
        ];

        if ($isClubSession) {
            $matchData['club_id'] = $session->club_id;
            $matchData['attendance_session_id'] = $session->id;
            $recordData['marked_by_student_id'] = $session->started_by_student_id;
        } else {
            $matchData['schedule_id'] = $session->schedule_id;
            $recordData['marked_by_faculty_id'] = $session->faculty_id;
        }

        AttendanceRecord::updateOrCreate($matchData, $recordData);

        return response()->json([
            'success' => true,
            'message' => 'Attendance Marked Successfully!',
            'subject' => $isClubSession ? ($session->club->name ?? 'Club Session') : ($session->schedule->subject->name ?? 'Subject'),
            'faculty' => $isClubSession ? 'Club Leader' : ($session->faculty->name ?? 'Faculty'),
            'time'    => Carbon::now()->format('h:i A'),
            'session_uuid' => $session->uuid
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
            ->where('status', '!=', 'cancelled')
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

    /**
     * Show Facial Registration View
     */
    public function faceRegisterView()
    {
        $student = Auth::guard('student')->user();
        if (!$student) return redirect()->route('student.login');
        if ($student->face_descriptor) {
            return redirect()->route('student.profile')->with('success', 'Face already registered.');
        }

        return view('student.face_register', compact('student'));
    }

    /**
     * Store Face Descriptor JSON
     */
    public function storeFaceDescriptor(Request $request)
    {
        $student = Auth::guard('student')->user();
        if (!$student) return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);

        $request->validate([
            'descriptors' => 'required|string', // Expecting JSON array string
        ]);

        $student->face_descriptor = $request->descriptors;
        $student->save();

        return response()->json(['success' => true, 'message' => 'Face registered successfully!']);
    }

    public function clubIndex()
    {
        $student = Auth::guard('student')->user();
        $student->load(['clubMemberships' => function($q) {
            $q->where('can_take_attendance', 1)->with('club');
        }]);

        if ($student->clubMemberships->isEmpty()) {
            return redirect()->route('student.dashboard')->with('error', 'Unauthorized access.');
        }

        return view('student.club.index', compact('student'));
    }

    public function clubSession($club_id)
    {
        $student = Auth::guard('student')->user();
        $membership = \App\Models\ClubMember::where('club_id', $club_id)
            ->where('member_id', $student->id)
            ->where('member_type', 'student')
            ->where('can_take_attendance', 1)
            ->with('club')
            ->firstOrFail();

        $club = $membership->club;

        $existingSession = AttendanceSession::where('club_id', $club->id)
            ->where('date', Carbon::today()->format('Y-m-d'))
            ->where('status', 'active')
            ->first();

        $existingRecords = collect();
        if ($existingSession) {
            $existingRecords = AttendanceRecord::where('attendance_session_id', $existingSession->id)
                ->get()
                ->pluck('status', 'student_id');
        }

        $clubMembers = \App\Models\ClubMember::with('member')->where('club_id', $club->id)->get();

        $dayOfWeek = Carbon::now()->format('l');
        $periods = \App\Models\Schedule::where('institution_id', $student->institution_id)
            ->where('day_of_week', $dayOfWeek)
            ->select('start_time', 'end_time')
            ->distinct()
            ->orderBy('start_time')
            ->get();

        $blocks = \App\Models\Block::where('institution_id', $student->institution_id)
            ->where(function($q) { $q->whereNull('status')->orWhere('status', 1)->orWhere('status', 'active'); })
            ->orderBy('name')
            ->get(['id', 'name', 'latitude', 'longitude', 'radius']);

        $venues = \App\Models\Venue::where('institution_id', $student->institution_id)
            ->where(function($q) { $q->whereNull('status')->orWhere('status', 1)->orWhere('status', 'active'); })
            ->orderBy('name')
            ->get(['id', 'name', 'type', 'latitude', 'longitude', 'radius', 'description']);

        $classrooms = \App\Models\Classroom::where('institution_id', $student->institution_id)
            ->where(function($q) { $q->whereNull('status')->orWhere('status', 1)->orWhere('status', 'active'); })
            ->with('block:id,name')
            ->orderBy('name')
            ->get(['id', 'block_id', 'name', 'floor_number', 'latitude', 'longitude']);

        return view('student.club.qr', compact('student', 'club', 'existingSession', 'existingRecords', 'clubMembers', 'periods', 'blocks', 'venues', 'classrooms'));
    }

    public function clubQrInit(Request $request)
    {
        $student = Auth::guard('student')->user();
        $clubId = $request->club_id;
        
        $membership = \App\Models\ClubMember::where('club_id', $clubId)
            ->where('member_id', $student->id)
            ->where('member_type', 'student')
            ->where('can_take_attendance', 1)
            ->with('club')
            ->firstOrFail();

        $status = $request->status ?? 'active';

        $session = AttendanceSession::updateOrCreate(
            [
                'institution_id' => $student->institution_id,
                'club_id' => $clubId,
                'date' => Carbon::today()->format('Y-m-d'),
                'status' => $status
            ],
            [
                'started_by_student_id' => $student->id,
                'start_time' => $request->event_start ?? Carbon::now()->format('H:i:s'),
                'end_time' => $request->event_end ?? Carbon::now()->addHours(1)->format('H:i:s'),
                'is_geofencing' => $request->is_geofencing ?? 0,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'venue' => $request->venue,
            ]
        );

        if ($session->is_geofencing == 1) {
            try {
                event(new \App\Events\ClubGeoSessionStarted(
                    $clubId,
                    $session->uuid,
                    $membership->club->name ?? 'Club Activity',
                    $session->venue,
                    $student->id
                ));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Failed to broadcast geo session start: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'uuid' => $session->uuid,
            'session_id' => $session->id
        ]);
    }

    public function clubQrRefresh(Request $request)
    {
        $student = Auth::guard('student')->user();
        $sessionUuid = $request->uuid;
        
        $session = AttendanceSession::where('uuid', $sessionUuid)
            ->where('started_by_student_id', $student->id)
            ->firstOrFail();
        
        $timestamp = now()->timestamp;
        $payload = $sessionUuid . '|' . $timestamp;
        
        $session->update(['qr_refresh_token' => $timestamp]);
        
        return response()->json([
            'success' => true,
            'payload' => $payload
        ]);
    }

    public function getClubSessionStudents(Request $request)
    {
        $student = Auth::guard('student')->user();
        $sessionUuid = $request->uuid;
        $session = AttendanceSession::where('uuid', $sessionUuid)
            ->where('started_by_student_id', $student->id)
            ->firstOrFail();
        
        $records = AttendanceRecord::where('attendance_session_id', $session->id)
            ->where('status', 'present')
            ->with('student')
            ->get();
            
        return response()->json([
            'success' => true,
            'present_student_ids' => $records->pluck('student_id')->toArray(),
            'students' => $records->map(function($record) {
                return [
                    'id' => $record->student->id ?? '',
                    'name' => $record->student->name ?? 'Unknown',
                    'roll_number' => $record->student->roll_number ?? '',
                ];
            })->toArray()
        ]);
    }

    public function clubQrClose(Request $request)
    {
        $student = Auth::guard('student')->user();
        $sessionUuid = $request->uuid;
        
        $session = AttendanceSession::where('uuid', $sessionUuid)
            ->where('started_by_student_id', $student->id)
            ->firstOrFail();
            
        $session->update(['status' => 'completed', 'end_time' => Carbon::now()->format('H:i:s')]);
        
        try {
            event(new \App\Events\LiveAttendanceAction($session->uuid, 'session_ended', []));
            if ($session->is_geofencing == 1) {
                event(new \App\Events\ClubGeoSessionClosed($session->club_id, $session->uuid));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('WebSocket broadcast failed: ' . $e->getMessage());
        }
        
        return response()->json(['success' => true]);
    }

    public function clubSubmitAttendance(Request $request)
    {
        $student = Auth::guard('student')->user();
        $club_id = $request->club_id;
        
        $membership = \App\Models\ClubMember::where('club_id', $club_id)
            ->where('member_id', $student->id)
            ->where('member_type', 'student')
            ->where('can_take_attendance', 1)
            ->firstOrFail();

        $request->validate([
            'attendance' => 'required|array',
            'attendance.*' => 'required|in:present,absent,late,excused',
        ]);

        $date = Carbon::today()->format('Y-m-d');

        DB::beginTransaction();
        try {
            $session = AttendanceSession::updateOrCreate(
                [
                    'institution_id' => $student->institution_id,
                    'club_id' => $club_id,
                    'date' => $date,
                ],
                [
                    'started_by_student_id' => $student->id,
                    'start_time' => $request->event_start ?? Carbon::now()->format('H:i:s'),
                    'end_time' => $request->event_end ?? Carbon::now()->addHours(1)->format('H:i:s'),
                    'status' => 'completed',
                    'venue' => $request->venue,
                ]
            );

            foreach ($request->attendance as $studentId => $status) {
                AttendanceRecord::updateOrCreate(
                    [
                        'institution_id' => $student->institution_id,
                        'student_id' => $studentId,
                        'club_id' => $club_id,
                        'date' => $date,
                    ],
                    [
                        'attendance_session_id' => $session->id,
                        'marked_by_student_id' => $student->id,
                        'status' => $status,
                        'remarks' => $request->remarks[$studentId] ?? null,
                    ]
                );

                if ($status === 'present' || $status === 'excused') {
                    $targetStudent = \App\Models\Student::find($studentId);
                    if ($targetStudent) {
                        $schedules = \App\Models\Schedule::where('institution_id', $targetStudent->institution_id)
                            ->where('day_of_week', Carbon::parse($session->date)->format('l'))
                            ->where(function($query) use ($targetStudent) {
                                if ($targetStudent->section_id) {
                                    $query->where('section_id', $targetStudent->section_id);
                                }
                                if ($targetStudent->class_group_id) {
                                    $query->orWhere('class_group_id', $targetStudent->class_group_id);
                                }
                            })
                            ->where('start_time', '<=', $session->end_time)
                            ->where('end_time', '>=', $session->start_time)
                            ->get();
                            
                        foreach ($schedules as $schedule) {
                            AttendanceRecord::updateOrCreate(
                                [
                                    'institution_id' => $session->institution_id,
                                    'student_id'     => $studentId,
                                    'schedule_id'    => $schedule->id,
                                    'date'           => $session->date,
                                ],
                                [
                                    'status'                => 'present',
                                    'remarks'               => 'Club Activity (Verified)',
                                ]
                            );
                        }
                    }
                }
            }
            DB::commit();
            return redirect()->route('student.club.index')->with('success', 'Attendance submitted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to save attendance: ' . $e->getMessage());
        }
    }

    public function clubGeoMark(Request $request)
    {
        $student = Auth::guard('student')->user();
        $session = AttendanceSession::with('club')->where('uuid', $request->uuid)->first();
        
        if (!$session) {
            return response()->json(['success' => false, 'message' => 'Session not found.']);
        }

        if ($session->status === 'scheduled') {
            $now = Carbon::now()->format('H:i:s');
            if ($now < $session->start_time || $now > $session->end_time) {
                return response()->json(['success' => false, 'message' => 'You can only mark attendance during the scheduled time slot.']);
            }
        } elseif ($session->status !== 'active') {
            return response()->json(['success' => false, 'message' => 'Session is not active.']);
        }

        if (!$request->latitude || !$request->longitude) {
            return response()->json(['success' => false, 'message' => 'GPS coordinates are required.']);
        }

        if ($session->latitude && $session->longitude) {
            $earthRadius = 6371000; 
            $latFrom = deg2rad($session->latitude);
            $lonFrom = deg2rad($session->longitude);
            $latTo = deg2rad($request->latitude);
            $lonTo = deg2rad($request->longitude);

            $latDelta = $latTo - $latFrom;
            $lonDelta = $lonTo - $lonFrom;

            $a = sin($latDelta / 2) * sin($latDelta / 2) +
                 cos($latFrom) * cos($latTo) *
                 sin($lonDelta / 2) * sin($lonDelta / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            $distance = $earthRadius * $c;

            $allowedRadius = 150; 

            if ($distance > $allowedRadius) {
                return response()->json([
                    'success' => false, 
                    'message' => 'You are ' . round($distance) . ' meters away. You must be within ' . $allowedRadius . ' meters.'
                ]);
            }
        }

        DB::beginTransaction();
        try {
            // Mark for club
            AttendanceRecord::updateOrCreate(
                [
                    'institution_id' => $session->institution_id,
                    'student_id'     => $student->id,
                    'club_id'        => $session->club_id,
                    'date'           => $session->date,
                    'attendance_session_id' => $session->id,
                ],
                [
                    'status'                => 'present',
                    'remarks'               => 'Marked via Club Geo-Location',
                    'marked_by_student_id'  => $student->id,
                ]
            );

            // Fetch schedules overlapping with club session window
            // Overlap check: schedule starts before now AND ends after session started.
            $schedules = Schedule::where('institution_id', $student->institution_id)
                ->where('day_of_week', Carbon::parse($session->date)->format('l'))
                ->where(function($query) use ($student) {
                    if ($student->section_id) {
                        $query->where('section_id', $student->section_id);
                    }
                    if ($student->class_group_id) {
                        $query->orWhere('class_group_id', $student->class_group_id);
                    }
                })
                ->where('start_time', '<=', $session->end_time)
                ->where('end_time', '>=', $session->start_time)
                ->get();
                
            foreach ($schedules as $schedule) {
                AttendanceRecord::updateOrCreate(
                    [
                        'institution_id' => $session->institution_id,
                        'student_id'     => $student->id,
                        'schedule_id'    => $schedule->id,
                        'date'           => $session->date,
                    ],
                    [
                        'status'                => 'present',
                        'remarks'               => 'Club Activity (Verified)',
                    ]
                );
            }
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Attendance marked successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }

    public function clubLiveAttendanceView($uuid)
    {
        $student = Auth::guard('student')->user();
        
        $session = AttendanceSession::with('club')
            ->where('uuid', $uuid)
            ->where('is_geofencing', 1)
            ->firstOrFail();

        // Check if student is part of the club
        $isMember = \App\Models\ClubMember::where('member_id', $student->id)
            ->where('member_type', 'student')
            ->where('club_id', $session->club_id)
            ->exists();

        if (!$isMember) {
            abort(403, 'You are not a member of this club.');
        }

        // Check if student has already marked attendance
        $alreadyMarked = AttendanceRecord::where('attendance_session_id', $session->id)
            ->where('student_id', $student->id)
            ->exists();

        return view('student.club.attendance_live', compact('session', 'alreadyMarked'));
    }
}
