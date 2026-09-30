<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;
use App\Models\Schedule;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SyncClubAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:sync-club {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-map club attendance records to students academic schedules based on timing slots.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = $this->argument('date') ?: Carbon::today()->format('Y-m-d');
        
        $clubSessions = AttendanceSession::whereNotNull('club_id')
            ->where('date', $date)
            ->whereNotNull('timing_slot_ids')
            ->get();

        $this->info("Found {$clubSessions->count()} club sessions with timing slots for {$date}.");

        $syncedCount = 0;

        foreach ($clubSessions as $session) {
            $timingSlotIds = is_string($session->timing_slot_ids) ? json_decode($session->timing_slot_ids, true) : $session->timing_slot_ids;
            
            if (empty($timingSlotIds)) {
                continue;
            }

            // Fetch institution academic settings to resolve timing slot IDs to actual times
            $settings = DB::table('institution_academic_settings')->where('institution_id', $session->institution_id)->first();
            if (!$settings || !$settings->slot_timings) {
                continue;
            }

            $slotTimings = json_decode($settings->slot_timings, true) ?: [];
            
            // Collect the actual start and end times for the selected slots
            $matchedTimes = [];
            foreach ($slotTimings as $slot) {
                if (in_array($slot['id'], $timingSlotIds)) {
                    $matchedTimes[] = [
                        'start' => Carbon::parse($slot['start'])->format('H:i:s'),
                        'end' => Carbon::parse($slot['end'])->format('H:i:s'),
                    ];
                }
            }

            if (empty($matchedTimes)) {
                continue;
            }

            // Get all students who marked present in this club session
            $presentStudentIds = AttendanceRecord::where('attendance_session_id', $session->id)
                ->where('status', 'present')
                ->pluck('student_id');

            if ($presentStudentIds->isEmpty()) {
                continue;
            }

            $dayOfWeek = strtolower(Carbon::parse($date)->format('l'));

            foreach ($presentStudentIds as $studentId) {
                $student = Student::find($studentId);
                if (!$student || !$student->section_id) {
                    continue;
                }

                // Find all schedules for this student's section on this day
                $schedules = Schedule::where('section_id', $student->section_id)
                    ->where('day_of_week', $dayOfWeek)
                    ->get();

                foreach ($schedules as $schedule) {
                    // Check if schedule overlaps/matches any of the club slot timings
                    $isMatch = false;
                    foreach ($matchedTimes as $time) {
                        // Assuming exact match or if lecture starts within the club slot
                        if ($schedule->start_time >= $time['start'] && $schedule->start_time < $time['end']) {
                            $isMatch = true;
                            break;
                        }
                    }

                    if ($isMatch) {
                        $record = AttendanceRecord::firstOrCreate(
                            [
                                'institution_id' => $session->institution_id,
                                'student_id' => $studentId,
                                'schedule_id' => $schedule->id,
                                'date' => $date,
                            ],
                            [
                                'status' => 'club_pending',
                                'remarks' => 'Club Activity',
                            ]
                        );

                        if ($record->wasRecentlyCreated) {
                            $syncedCount++;
                        } else {
                            // If it exists but was marked absent, maybe overwrite?
                            // We should not overwrite if faculty already took attendance
                            if (is_null($record->attendance_session_id) && $record->status !== 'club_pending') {
                                $record->update([
                                    'status' => 'club_pending',
                                    'remarks' => 'Club Activity'
                                ]);
                                $syncedCount++;
                            }
                        }
                    }
                }
            }
        }

        $this->info("Successfully synced {$syncedCount} academic records from club attendance.");
        return 0;
    }
}
