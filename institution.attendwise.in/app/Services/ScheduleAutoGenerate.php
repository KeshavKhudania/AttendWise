<?php

namespace App\Services;

use App\Models\{
    Section, Subject, Schedule, Classroom, SemesterSubject, InstitutionAcademicSetting
};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScheduleAutoGenerate
{
    // These will be used if institution settings are missing
    protected array $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    
    protected array $slots = [
        ['09:30:00', '10:20:00'],
        ['10:20:00', '11:10:00'],
        ['11:10:00', '12:00:00'],
        ['12:00:00', '12:50:00'],
        ['13:35:00', '14:20:00'],
        ['14:20:00', '15:05:00'],
        ['15:05:00', '15:50:00'],
    ];

    public function generateForInstitution(int $institutionId): void
    {
        Log::info("Starting HIGH-PERFORMANCE timetable generation for Institution: $institutionId");

        // Fetch Institution Settings
        $settings = InstitutionAcademicSetting::where('institution_id', $institutionId)->first();
        
        $workingDays = $settings?->working_days ?? $this->days;
        $slotTimings = $this->parseSlotTimings($settings?->slot_timings) ?? $this->slots;
        $facultyDailyLimit = $settings?->faculty_lecture_limit ?? 6;
        $theoryLimit = $settings?->theory_slot_limit ?? 1;
        $labLimit = $settings?->lab_slot_limit ?? 2;

        // 1. Fetch data into memory for extreme speed
        $sections = Section::with('additionalDepartments')
            ->where('institution_id', $institutionId)
            ->where('status', 1)
            ->get();
        $classrooms = Classroom::where('institution_id', $institutionId)->where('status', 1)->get();

        // Busy state collectors
        $facultyBusy = [];
        $sectionBusy = [];
        $classroomBusy = [];
        $facultyDailyLoad = []; // Track faculty load per day
        $recordsToInsert = [];

        DB::transaction(function () use (
            $institutionId, $sections, $classrooms, $workingDays, $slotTimings, 
            $facultyDailyLimit, $theoryLimit, $labLimit, &$recordsToInsert
        ) {
            
            // Clear existing
            Schedule::where('institution_id', $institutionId)->delete();

            // Internal busy states
            $facultyBusy = [];
            $sectionBusy = [];
            $classroomBusy = [];
            $facultyDailyLoad = [];

            foreach ($sections as $section) {
                // Get all associated departments (primary + additional)
                $deptIds = array_merge([$section->department_id], $section->additionalDepartments->pluck('id')->toArray());

                // Fetch all subject mappings for this section across all associated departments
                $semesterGroups = SemesterSubject::where('institution_id', $institutionId)
                    ->whereIn('department_id', $deptIds)
                    ->where('course_id', $section->course_id)
                    ->where('semester', $section->semester)
                    ->get();

                if ($semesterGroups->isEmpty()) continue;

                $allMappedSubjectIds = [];
                foreach ($semesterGroups as $group) {
                    if (!empty($group->subjects)) {
                        $allMappedSubjectIds = array_merge($allMappedSubjectIds, (array)$group->subjects);
                    }
                }
                $allMappedSubjectIds = array_unique($allMappedSubjectIds);

                if (empty($allMappedSubjectIds)) continue;

                $subjects = Subject::whereIn('id', $allMappedSubjectIds)
                    ->where('status', 1)
                    ->get();

                foreach ($subjects as $subject) {
                    $faculty = $subject->faculties()
                        ->where('institution_faculty_subject.institution_id', $institutionId)
                        ->first();
                    
                    if (!$faculty) continue;

                    $remainingHours = $subject->weekly_lectures ?? 3;
                    $isLab = in_array(strtolower($subject->type), ['lab', 'practical']);
                    $duration = $isLab ? $labLimit : $theoryLimit;
                    
                    $shuffledDays = $workingDays;
                    shuffle($shuffledDays);

                    while ($remainingHours > 0) {
                        $allocated = false;
                        $sessionDuration = min($duration, $remainingHours);
                        
                        foreach ($shuffledDays as $day) {
                            // Check Faculty Daily Limit
                            if (($facultyDailyLoad[$day][$faculty->id] ?? 0) >= $facultyDailyLimit) continue;

                            // For consecutive slots, we try each possible starting slot
                            $numSlots = count($slotTimings);
                            if ($numSlots < $sessionDuration) continue;

                            $startIndices = range(0, $numSlots - $sessionDuration);
                            shuffle($startIndices);

                            foreach ($startIndices as $startIndex) {
                                $possibleSlots = array_slice($slotTimings, $startIndex, $sessionDuration);
                                $canAllocateSession = true;
                                $selectedClassroom = null;

                                // 1. Check if Teacher & Student are free for ALL slots in this session
                                foreach ($possibleSlots as $slot) {
                                    $slotKey = "{$day}-{$slot[0]}";
                                    if (isset($facultyBusy[$slotKey][$faculty->id]) || isset($sectionBusy[$slotKey][$section->id])) {
                                        $canAllocateSession = false;
                                        break;
                                    }
                                }

                                if (!$canAllocateSession) continue;

                                // 2. Find a classroom that is free for ALL slots in this session
                                foreach ($classrooms as $classroom) {
                                    $classroomFree = true;
                                    foreach ($possibleSlots as $slot) {
                                        $slotKey = "{$day}-{$slot[0]}";
                                        if (isset($classroomBusy[$slotKey][$classroom->id])) {
                                            $classroomFree = false;
                                            break;
                                        }
                                    }
                                    if ($classroomFree) {
                                        $selectedClassroom = $classroom;
                                        break;
                                    }
                                }

                                if ($selectedClassroom) {
                                    // 3. Mark as busy and create records
                                    foreach ($possibleSlots as $slot) {
                                        $slotKey = "{$day}-{$slot[0]}";
                                        $facultyBusy[$slotKey][$faculty->id] = true;
                                        $sectionBusy[$slotKey][$section->id] = true;
                                        $classroomBusy[$slotKey][$selectedClassroom->id] = true;
                                        
                                        $recordsToInsert[] = [
                                            'institution_id' => $institutionId,
                                            'department_id'  => $section->department_id,
                                            'course_id'      => $section->course_id,
                                            'academic_year'  => $section->academic_year,
                                            'semester'       => $section->semester,
                                            'section_id'     => $section->id,
                                            'subject_id'     => $subject->id,
                                            'faculty_id'     => $faculty->id,
                                            'classroom_id'   => $selectedClassroom->id,
                                            'lecture_type'   => $isLab ? 'Lab' : 'Theory',
                                            'day_of_week'    => $day,
                                            'start_time'     => $slot[0],
                                            'end_time'       => $slot[1],
                                            'status'         => 1,
                                            'created_at'     => now(),
                                            'updated_at'     => now(),
                                        ];
                                    }

                                    $facultyDailyLoad[$day][$faculty->id] = ($facultyDailyLoad[$day][$faculty->id] ?? 0) + $sessionDuration;
                                    $remainingHours -= $sessionDuration;
                                    $allocated = true;
                                    break 2; // Next session
                                }
                            }
                        }

                        if (!$allocated) {
                            Log::warning("No slot for {$subject->name} in {$section->name} (Remaining: $remainingHours)");
                            break; // Stop trying for this subject
                        }
                    }
                }
            }

            // Perform Bulk Insert
            foreach (array_chunk($recordsToInsert, 500) as $chunk) {
                DB::table('institution_schedules')->insert($chunk);
            }
        });

        Log::info("Generation complete. Inserted " . count($recordsToInsert) . " records.");
    }

    /**
     * Parse slot timings from settings format to internal format [start, end]
     */
    protected function parseSlotTimings(?array $rawTimings): ?array
    {
        if (empty($rawTimings)) return null;

        $parsed = [];
        foreach ($rawTimings as $slot) {
            if (!empty($slot['start']) && !empty($slot['end'])) {
                $parsed[] = [$slot['start'], $slot['end']];
            }
        }

        return count($parsed) > 0 ? $parsed : null;
    }
}