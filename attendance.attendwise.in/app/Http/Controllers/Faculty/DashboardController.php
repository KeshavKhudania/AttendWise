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
        
        if ($scheduleId) {
            $selectedSchedule = Schedule::with(['subject', 'classroom.block', 'section'])
                ->where('faculty_id', $faculty->id)
                ->find($scheduleId);
                
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

                $session = AttendanceSession::where('schedule_id', $selectedSchedule->id)
                    ->where('date', Carbon::today()->format('Y-m-d'))
                    ->first();

                if ($session) {
                    $existingRecords = AttendanceRecord::where('attendance_session_id', $session->id)
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
            
        return view('faculty.attendance', compact('faculty', 'selectedSchedule', 'students', 'upcomingLectures', 'existingRecords'));
    }

    public function submitAttendance(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:institution_schedules,id',
            'attendance' => 'required|array',
            'attendance.*' => 'required|in:present,absent,late,excused',
        ]);

        $faculty = Auth::guard('faculty')->user();
        $schedule = Schedule::findOrFail($request->schedule_id);
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
        $schedule = Schedule::findOrFail($scheduleId);

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
        $sessionUuid = $request->uuid;
        $session = AttendanceSession::where('uuid', $sessionUuid)->firstOrFail();
        
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

        AttendanceRecord::updateOrCreate(
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

        return response()->json(['success' => true, 'message' => 'Attendance marked successfully']);
    }

    public function getSessionStudents(Request $request)
    {
        $sessionUuid = $request->uuid;
        $session = AttendanceSession::where('uuid', $sessionUuid)->firstOrFail();
        
        $records = AttendanceRecord::where('attendance_session_id', $session->id)
            ->where('status', 'present')
            ->pluck('student_id')
            ->toArray();
            
        return response()->json([
            'success' => true,
            'present_student_ids' => $records
        ]);
    }
}
