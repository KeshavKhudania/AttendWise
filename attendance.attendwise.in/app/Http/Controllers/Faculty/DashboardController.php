<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\Event;
use App\Models\Student;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $faculty = Auth::guard('faculty')->user();
        
        // Upcoming Lectures for today and tomorrow
        $upcomingLectures = Schedule::with(['subject', 'classroom.block', 'section'])
            ->where('faculty_id', $faculty->id)
            ->where('day_of_week', Carbon::now()->format('l'))
            ->orderBy('start_time')
            ->get()
            ->map(function($lecture) use ($faculty) {
                $lecture->attendance_taken = AttendanceSession::where('schedule_id', $lecture->id)
                    ->where('date', Carbon::today()->format('Y-m-d'))
                    ->exists();
                return $lecture;
            });
            
        // Recent Events
        $events = Event::where('institution_id', $faculty->institution_id)
            ->where('event_date', '>=', Carbon::today())
            ->orderBy('event_date')
            ->take(5)
            ->get();

        return view('faculty.dashboard', compact('faculty', 'upcomingLectures', 'events'));
    }

    public function profile()
    {
        $faculty = Auth::guard('faculty')->user();
        return view('faculty.profile', compact('faculty'));
    }

    public function timeTable()
    {
        $faculty = Auth::guard('faculty')->user();
        $schedules = Schedule::with(['subject', 'classroom.block', 'section'])
            ->where('faculty_id', $faculty->id)
            ->get();

        $times = $schedules->pluck('start_time')->unique()->sort()->values();
        $schedules = $schedules->groupBy('day_of_week');
            
        return view('faculty.timetable', compact('faculty', 'schedules', 'times'));
    }

    public function attendance(Request $request, $scheduleId = null)
    {
        $faculty = Auth::guard('faculty')->user();
        
        // Use scheduleId from URL or request
        $scheduleId = $scheduleId ?: $request->query('schedule');
        
        $selectedSchedule = null;
        $students = collect();
        $existingRecords = collect();
        $existingSession = null;
        
        if ($scheduleId) {
            $selectedSchedule = Schedule::with(['subject', 'classroom.block', 'section'])
                ->where('faculty_id', $faculty->id)
                ->findOrFail($scheduleId);
                
            if ($selectedSchedule) {
                $students = Student::where('institution_id', $faculty->institution_id)
                    ->where(function($query) use ($selectedSchedule) {
                        if ($selectedSchedule->section_id) {
                            $query->where('section_id', $selectedSchedule->section_id);
                        }
                        if ($selectedSchedule->class_group_id) {
                            $query->where('class_group_id', $selectedSchedule->class_group_id);
                        }
                    })
                    ->orderBy('roll_number')
                    ->get();

                $existingSession = AttendanceSession::where('schedule_id', $selectedSchedule->id)
                    ->where('date', Carbon::today()->format('Y-m-d'))
                    ->where('status', '!=', 'cancelled')
                    ->first();

                if ($existingSession) {
                    $existingRecords = AttendanceRecord::where('attendance_session_id', $existingSession->id)
                        ->get()
                        ->pluck('status', 'student_id');
                }
            }
        }
        
        $upcomingLectures = Schedule::with(['subject', 'classroom.block', 'section'])
            ->where('faculty_id', $faculty->id)
            ->where('day_of_week', Carbon::now()->format('l'))
            ->orderBy('start_time')
            ->get();
            
        return view('faculty.attendance', compact('faculty', 'selectedSchedule', 'students', 'upcomingLectures', 'existingRecords', 'existingSession'));
    }

    public function submitAttendance(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:institution_schedules,id',
            'attendance' => 'required|array',
            'attendance.*' => 'required|in:present,absent,late,excused',
        ]);

        $faculty = Auth::guard('faculty')->user();
        $schedule = Schedule::where('faculty_id', $faculty->id)->findOrFail($request->schedule_id);
        $date = Carbon::today()->format('Y-m-d');

        DB::beginTransaction();
        try {
            $session = AttendanceSession::updateOrCreate(
                [
                    'institution_id' => $faculty->institution_id,
                    'schedule_id' => $schedule->id,
                    'date' => $date,
                ],
                [
                    'faculty_id' => $faculty->id,
                    'start_time' => $schedule->start_time,
                    'status' => 'completed',
                ]
            );

            foreach ($request->attendance as $studentId => $status) {
                AttendanceRecord::updateOrCreate(
                    [
                        'institution_id' => $faculty->institution_id,
                        'student_id' => $studentId,
                        'schedule_id' => $schedule->id,
                        'date' => $date,
                    ],
                    [
                        'attendance_session_id' => $session->id,
                        'marked_by_faculty_id' => $faculty->id,
                        'status' => $status,
                        'remarks' => $request->remarks[$studentId] ?? null,
                    ]
                );
            }
            DB::commit();
            return redirect()->route('faculty.dashboard')->with('success', 'Attendance submitted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to save attendance: ' . $e->getMessage());
        }
    }

    public function qrSessionInit(Request $request)
    {
        $faculty = Auth::guard('faculty')->user();
        $scheduleId = $request->schedule_id;
        $schedule = Schedule::where('faculty_id', $faculty->id)->findOrFail($scheduleId);

        $session = AttendanceSession::updateOrCreate(
            [
                'institution_id' => $faculty->institution_id,
                'schedule_id' => $schedule->id,
                'date' => Carbon::today()->format('Y-m-d'),
            ],
            [
                'faculty_id' => $faculty->id,
                'start_time' => $schedule->start_time,
                'status' => 'active',
                'is_geofencing' => 1,
            ]
        );

        return response()->json([
            'success' => true,
            'uuid' => $session->uuid,
            'session_id' => $session->id
        ]);
    }

    public function qrRefresh(Request $request)
    {
        $faculty = Auth::guard('faculty')->user();
        $sessionUuid = $request->uuid;
        $session = AttendanceSession::where('uuid', $sessionUuid)
            ->where('faculty_id', $faculty->id)
            ->firstOrFail();
        
        $timestamp = now()->timestamp;
        $payload = $sessionUuid . '|' . $timestamp;
        
        $session->update(['qr_refresh_token' => $timestamp]);
        
        return response()->json([
            'success' => true,
            'payload' => $payload
        ]);
    }

    public function markAttendanceByQR(Request $request)
    {
        $payload = $request->payload; 
        $studentId = $request->student_id; 
        
        $parts = explode('|', $payload);
        if (count($parts) !== 2) {
            return response()->json(['success' => false, 'message' => 'Invalid QR Code'], 400);
        }

        $sessionUuid = $parts[0];
        $timestamp = (int)$parts[1];

        if (abs(now()->timestamp - $timestamp) > 10) {
            return response()->json(['success' => false, 'message' => 'QR Code Expired'], 403);
        }

        $session = AttendanceSession::where('uuid', $sessionUuid)->first();
        if (!$session || $session->status !== 'active') {
            return response()->json(['success' => false, 'message' => 'Invalid or Inactive Session'], 404);
        }

        if (!$studentId) {
            return response()->json(['success' => false, 'message' => 'Student ID required'], 400);
        }

        $record = AttendanceRecord::updateOrCreate(
            [
                'institution_id' => $session->institution_id,
                'student_id' => $studentId,
                'schedule_id' => $session->schedule_id,
                'date' => $session->date,
            ],
            [
                'attendance_session_id' => $session->id,
                'marked_by_faculty_id' => $session->faculty_id,
                'status' => 'present',
                'remarks' => 'Marked via QR Scan',
            ]
        );

        try {
            event(new \App\Events\LiveAttendanceAction($session->uuid, 'student_joined', [
                'student_id' => $studentId,
                'status' => 'present'
            ]));
        } catch (\Exception $e) {
            Log::warning('WebSocket broadcast failed: ' . $e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Attendance marked successfully']);
    }

    public function getSessionStudents(Request $request)
    {
        $faculty = Auth::guard('faculty')->user();
        $sessionUuid = $request->uuid;
        $session = AttendanceSession::where('uuid', $sessionUuid)
            ->where('faculty_id', $faculty->id)
            ->firstOrFail();
        
        $records = AttendanceRecord::where('attendance_session_id', $session->id)
            ->where('status', 'present')
            ->pluck('student_id')
            ->toArray();
            
        return response()->json([
            'success' => true,
            'present_student_ids' => $records
        ]);
    }

    public function qrSessionClose(Request $request)
    {
        $faculty = Auth::guard('faculty')->user();
        $sessionUuid = $request->uuid;
        
        $session = AttendanceSession::where('uuid', $sessionUuid)
            ->where('faculty_id', $faculty->id)
            ->firstOrFail();
            
        $session->update(['status' => 'completed']);
        
        try {
            event(new \App\Events\LiveAttendanceAction($session->uuid, 'session_ended', []));
        } catch (\Exception $e) {
            Log::warning('WebSocket broadcast failed: ' . $e->getMessage());
        }
        
        return response()->json(['success' => true]);
    }

    public function qrToggleGeofence(Request $request)
    {
        $faculty = Auth::guard('faculty')->user();
        $sessionUuid = $request->uuid;
        $isGeofencing = $request->input('is_geofencing') ? 1 : 0;

        $session = AttendanceSession::where('uuid', $sessionUuid)
            ->where('faculty_id', $faculty->id)
            ->firstOrFail();

        $session->update(['is_geofencing' => $isGeofencing]);

        return response()->json([
            'success' => true,
            'is_geofencing' => $session->is_geofencing
        ]);
    }

    public function resetAttendance(Request $request)
    {
        $faculty = Auth::guard('faculty')->user();
        $request->validate([
            'schedule_id' => 'required|exists:institution_schedules,id'
        ]);

        $schedule = Schedule::where('faculty_id', $faculty->id)->findOrFail($request->schedule_id);
        $date = Carbon::today()->format('Y-m-d');

        $session = AttendanceSession::where('schedule_id', $schedule->id)
            ->where('date', $date)
            ->where('faculty_id', $faculty->id)
            ->first();

        if ($session) {
            $session->update(['status' => 'cancelled']);
            
            AttendanceRecord::where('attendance_session_id', $session->id)
                ->update(['status' => 'cancelled', 'remarks' => 'Session Reset/Cancelled by Faculty']);
                
            try {
                event(new \App\Events\LiveAttendanceAction($session->uuid, 'session_ended', []));
            } catch (\Exception $e) {
                Log::warning('WebSocket broadcast failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('faculty.attendance', ['schedule' => $schedule->id])->with('success', 'Attendance session has been reset and cancelled.');
    }

    public function qrMarkStudentByRollNumber(Request $request)
    {
        $faculty = Auth::guard('faculty')->user();
        $sessionUuid = $request->uuid;
        $rollNumber = trim($request->roll_number ?? '');
        $ocrText = preg_replace('/[^A-Z0-9]/', '', strtoupper($request->ocr_text ?? ''));

        if (!$sessionUuid || (!$rollNumber && !$ocrText)) {
            return response()->json(['success' => false, 'message' => 'Session UUID and Roll Number/OCR Text are required.'], 400);
        }

        $session = AttendanceSession::where('uuid', $sessionUuid)
            ->where('faculty_id', $faculty->id)
            ->first();

        if (!$session || $session->status !== 'active') {
            return response()->json(['success' => false, 'message' => 'Active session not found.'], 404);
        }

        $schedule = Schedule::find($session->schedule_id);
        $student = null;

        if ($rollNumber) {
            // Flexible match via manual entry
            $cleanInput = preg_replace('/[^A-Z0-9]/', '', strtoupper($rollNumber));
            $students = Student::where('institution_id', $faculty->institution_id)->get();
            foreach ($students as $s) {
                if (preg_replace('/[^A-Z0-9]/', '', strtoupper($s->roll_number)) === $cleanInput) {
                    $student = $s;
                    break;
                }
            }
        } elseif ($ocrText) {
            // Smart OCR text matching against all students in the institution
            // (Class enrollment is strictly verified later)
            $enrolledStudents = Student::where('institution_id', $faculty->institution_id)->get();

            foreach ($enrolledStudents as $enrolled) {
                $cleanRoll = preg_replace('/[^A-Z0-9]/', '', strtoupper($enrolled->roll_number));
                // Ensure roll number is substantial enough to avoid false positives
                if (strlen($cleanRoll) >= 3) {
                    // Also handle common OCR mistakes
                    $ocrMistakes = ['O', 'I', 'S', 'B', 'Z'];
                    $ocrCorrections = ['0', '1', '5', '8', '2'];
                    $cleanOcrTextReplaced = str_replace($ocrMistakes, $ocrCorrections, $ocrText);
                    $cleanRollReplaced = str_replace($ocrMistakes, $ocrCorrections, $cleanRoll);

                    if (strpos($ocrText, $cleanRoll) !== false || strpos($cleanOcrTextReplaced, $cleanRollReplaced) !== false) {
                        $student = $enrolled;
                        break;
                    }
                }
            }
            
            if ($student && !$rollNumber) {
                // ANTI-SPOOFING CHECK: Real ID cards contain boilerplate text. Handwritten papers usually only contain the roll number.
                if (strlen($ocrText) < 15) {
                    return response()->json(['success' => false, 'message' => 'Anti-Spoofing: Scan lacks sufficient printed text.', 'suggested_roll' => $student->roll_number], 400);
                }

                $securityKeywords = ['ID', 'IDENTITY', 'CARD', 'STUDENT', 'VALID', 'DOB', 'BLOOD', 'GROUP', 'ISSUED', 'CONTACT', 'EMERGENCY', 'UNIVERSITY', 'COLLEGE', 'INSTITUTE', 'SCHOOL', 'ACADEMY', 'COURSE', 'PROGRAM', 'DEPARTMENT', 'SIGNATURE', 'NAME', 'FATHER'];
                $foundKeywords = 0;
                foreach ($securityKeywords as $keyword) {
                    if (strpos($ocrText, $keyword) !== false) {
                        $foundKeywords++;
                    }
                }
                
                // Require at least 1 security keyword to prove this is a printed ID card
                if ($foundKeywords < 1) {
                    return response()->json(['success' => false, 'message' => 'Anti-Spoofing: Institutional markers missing.', 'suggested_roll' => $student->roll_number], 400);
                }
            }
        }

        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found in your class.'], 404);
        }

        // Check if student belongs to the section/class group of the schedule
        if ($schedule) {
            $belongs = false;
            if ($schedule->section_id && $student->section_id == $schedule->section_id) {
                $belongs = true;
            }
            if ($schedule->class_group_id && $student->class_group_id == $schedule->class_group_id) {
                $belongs = true;
            }
            if (!$schedule->section_id && !$schedule->class_group_id) {
                $belongs = true;
            }
            if (!$belongs) {
                return response()->json(['success' => false, 'message' => 'Student is not enrolled in this section/class.'], 400);
            }
        }

        // Mark them present
        $record = AttendanceRecord::updateOrCreate(
            [
                'institution_id' => $session->institution_id,
                'student_id' => $student->id,
                'schedule_id' => $session->schedule_id,
                'date' => $session->date,
            ],
            [
                'attendance_session_id' => $session->id,
                'marked_by_faculty_id' => $session->faculty_id,
                'status' => 'present',
                'remarks' => 'Marked via OCR Scanner',
            ]
        );

        // Broadcast to LiveAttendanceAction so the UI updates in real-time
        try {
            event(new \App\Events\LiveAttendanceAction($session->uuid, 'student_joined', [
                'student_id' => $student->id,
                'status' => 'present'
            ]));
        } catch (\Exception $e) {
            Log::warning('WebSocket broadcast failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Student ' . $student->name . ' marked present.',
            'student_id' => $student->id,
            'roll_number' => $student->roll_number
        ]);
    }

    public function qrRequestFacial(Request $request)
    {
        $faculty = Auth::guard('faculty')->user();
        $sessionUuid = $request->uuid;
        
        $session = AttendanceSession::where('uuid', $sessionUuid)
            ->where('faculty_id', $faculty->id)
            ->firstOrFail();

        try {
            event(new \App\Events\LiveAttendanceAction($session->uuid, 'facial_scan_requested', []));
        } catch (\Exception $e) {
            Log::warning('WebSocket broadcast failed: ' . $e->getMessage());
        }

        return response()->json(['success' => true]);
    }
}
