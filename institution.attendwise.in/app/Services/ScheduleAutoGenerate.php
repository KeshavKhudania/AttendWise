<?php

namespace App\Services;

use App\Models\{
    Section, Subject, Schedule, Classroom, SemesterSubject,
    InstitutionAcademicSetting, Faculty, Department, Course
};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScheduleAutoGenerate
{
    // Institution fallback defaults
    protected array $defaultDays  = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    protected array $defaultSlots = [
        ['09:30:00', '10:20:00'],
        ['10:20:00', '11:10:00'],
        ['11:10:00', '12:00:00'],
        ['12:00:00', '12:50:00'],
        ['13:35:00', '14:20:00'],
        ['14:20:00', '15:05:00'],
        ['15:05:00', '15:50:00'],
    ];

    /**
     * Entry point. Accepts an options array for scoped / flexible generation.
     *
     * @param  int   $institutionId
     * @param  array $options {
     *   scope_type:       all|department|course|course_semester|section
     *   scope_department: int|null
     *   scope_course:     int|null
     *   scope_semester:   int|null
     *   scope_section:    int|null
     *   working_days:     string[]|null   (run-time override, highest priority)
     *   clear_existing:   bool            (default true)
     * }
     * @return array { inserted: int, shortages: array }
     */
    public function generateForInstitution(int $institutionId, array $options = []): array
    {
        Log::info("Timetable generation START — Institution: $institutionId", $options);

        // ─── Load global settings ───────────────────────────────────────────
        $settings           = InstitutionAcademicSetting::where('institution_id', $institutionId)->first();
        $institutionDays    = $settings?->working_days  ?? $this->defaultDays;
        $slotTimings        = $this->parseSlotTimings($settings?->slot_timings) ?? $this->defaultSlots;
        $facultyDailyLimit  = $settings?->faculty_lecture_limit ?? 6;
        $theoryLimit        = $settings?->theory_slot_limit ?? 1;
        $labLimit           = $settings?->lab_slot_limit   ?? 2;

        // Run-time day override (from the config form)
        $runTimeOverride    = !empty($options['working_days']) ? $options['working_days'] : null;
        $utilizeLimit       = $options['utilize_limit'] ?? false;
        $freeLectures       = $options['free_lectures'] ?? false;

        // ─── Resolve which sections to process ─────────────────────────────
        $sections    = $this->resolveSections($institutionId, $options);
        $classrooms  = Classroom::where('institution_id', $institutionId)->where('status', 1)->get();

        // ─── Shared busy-state tables ───────────────────────────────────────
        $facultyBusy      = [];
        $sectionBusy      = [];
        $classroomBusy    = [];
        $facultyDailyLoad = [];
        $subjectSectionDailyLoad = [];
        $recordsToInsert  = [];
        $shortages        = [];

        DB::transaction(function () use (
            $institutionId, $sections, $classrooms, $institutionDays,
            $runTimeOverride, $slotTimings, $facultyDailyLimit,
            $theoryLimit, $labLimit, $options,
            &$facultyBusy, &$sectionBusy, &$classroomBusy,
            &$facultyDailyLoad, &$recordsToInsert, &$shortages,
            $utilizeLimit, $freeLectures
        ) {
            // ── Clear scope ──────────────────────────────────────────────────
            if ($options['clear_existing'] ?? true) {
                $this->clearExistingScope($institutionId, $options);
            }

            // ── Main generation loop ─────────────────────────────────────────
            foreach ($sections as $section) {

                // Resolve this section's effective working days
                $sectionDays = $this->resolveWorkingDays($section, $runTimeOverride, $institutionDays);

                // Gather all department IDs (primary + additional)
                $deptIds = array_merge(
                    [$section->department_id],
                    $section->additionalDepartments->pluck('id')->toArray()
                );

                // Fetch subject mappings for this section's course+semester
                $semesterGroups = SemesterSubject::where('institution_id', $institutionId)
                    ->whereIn('department_id', $deptIds)
                    ->where('course_id', $section->course_id)
                    ->where('semester',  $section->semester)
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

                $subjects = Subject::whereIn('id', $allMappedSubjectIds)->where('status', 1)->get();

                foreach ($subjects as $subject) {
                    $assignedFaculties = $subject->faculties()
                        ->where('institution_faculty_subject.institution_id', $institutionId)
                        ->get();

                    // ── No faculty assigned ──────────────────────────────────
                    if ($assignedFaculties->isEmpty()) {
                        $shortages[] = [
                            'section'      => $section->name,
                            'subject'      => $subject->name,
                            'faculty'      => 'Unassigned',
                            'reason'       => 'No faculty assigned to this subject.',
                            'days_needed'  => $subject->weekly_lectures ?? 3,
                        ];
                        continue;
                    }

                    // Load Balancing vs Limit Utilization
                    $sortedFaculties = $assignedFaculties->sortBy(function($fac) use ($facultyDailyLoad, $utilizeLimit) {
                        $totalLoad = 0;
                        if (!empty($facultyDailyLoad)) {
                            foreach ($facultyDailyLoad as $day => $loads) {
                                $totalLoad += $loads[$fac->id] ?? 0;
                            }
                        }
                        return $utilizeLimit ? -$totalLoad : $totalLoad;
                    });

                    $remainingHours   = $subject->weekly_lectures ?? 3;
                    $isLab            = in_array(strtolower($subject->type ?? ''), ['lab', 'practical']);
                    
                    // Daily Subject Limits
                    $defaultSessionDuration  = $isLab ? $labLimit : $theoryLimit;
                    $maxDaily = $subject->max_lectures_per_day ?: $defaultSessionDuration;
                    $minDaily = $subject->min_lectures_per_day ?: 1;
                    $isContinuous = is_null($subject->continuous_lectures) ? true : (bool) $subject->continuous_lectures;

                    $shuffledDays     = $sectionDays;
                    shuffle($shuffledDays);

                    $lastFailedReason = 'No available slot found in the working days.';
                    $lastFailedFaculty = null;
                    
                    foreach ($sortedFaculties as $faculty) {
                        while ($remainingHours > 0) {
                            $allocated      = false;
                            $sessionLen     = $isContinuous ? min($maxDaily, $remainingHours) : 1;
                            $daysTried      = 0;
                            $daysSkipped    = []; // days skipped due to faculty unavailability

                            // Try to allocate the largest block possible, fallback to smaller blocks if continuous fails
                            $currentTryLen = $sessionLen;
                            while ($currentTryLen >= $minDaily && !$allocated) {
                                foreach ($shuffledDays as $day) {
                                    $daysTried++;

                                    // ── Faculty availability check ───────────────────
                                    if (!$this->facultyAvailableOnDay($faculty, $day, $sectionDays)) {
                                        if ($currentTryLen == $sessionLen) $daysSkipped[] = $day;
                                        continue;
                                    }

                                    // ── Subject Daily Load limit ─────────────────────
                                    $currentSubjectLoad = $subjectSectionDailyLoad[$day][$section->id][$subject->id] ?? 0;
                                    $allowedToday = $maxDaily - $currentSubjectLoad;
                                    if ($allowedToday <= 0) continue;
                                    
                                    // ── Faculty daily lecture limit ──────────────────
                                    $facultyLoad = $facultyDailyLoad[$day][$faculty->id] ?? 0;
                                    $facultyAllowedToday = $facultyDailyLimit - $facultyLoad;
                                    if ($facultyAllowedToday <= 0) continue;
                                    
                                    // Effective session length for this day
                                    $actualSessionLen = min($currentTryLen, $allowedToday, $facultyAllowedToday);
                                    if ($actualSessionLen < $minDaily && $remainingHours >= $minDaily) {
                                        // Can't satisfy min limits today
                                        continue;
                                    }

                                    $numSlots = count($slotTimings);
                                    if ($numSlots < $actualSessionLen) continue;

                                    $startIndices = range(0, $numSlots - $actualSessionLen);
                                    shuffle($startIndices);

                                    foreach ($startIndices as $startIndex) {
                                        $possibleSlots   = array_slice($slotTimings, $startIndex, $actualSessionLen);
                                        $canAllocate     = true;
                                        $selectedRoom    = null;

                                        // Check faculty + section free for ALL slots in this session
                                        foreach ($possibleSlots as $slot) {
                                            $slotKey = "{$day}-{$slot[0]}";
                                            if (
                                                isset($facultyBusy[$slotKey][$faculty->id]) ||
                                                isset($sectionBusy[$slotKey][$section->id])
                                            ) {
                                                $canAllocate = false;
                                                break;
                                            }
                                        }

                                        // Apply free lectures spacing constraint
                                        if ($canAllocate && $freeLectures) {
                                            // Check if the slot immediately BEFORE the session is busy for the faculty
                                            if (isset($slotTimings[$startIndex - 1])) {
                                                $prevSlot = $slotTimings[$startIndex - 1];
                                                if (isset($facultyBusy["{$day}-{$prevSlot[0]}"][$faculty->id])) {
                                                    $canAllocate = false;
                                                }
                                            }
                                            // Check if the slot immediately AFTER the session is busy for the faculty
                                            if ($canAllocate && isset($slotTimings[$startIndex + $actualSessionLen])) {
                                                $nextSlot = $slotTimings[$startIndex + $actualSessionLen];
                                                if (isset($facultyBusy["{$day}-{$nextSlot[0]}"][$faculty->id])) {
                                                    $canAllocate = false;
                                                }
                                            }
                                        }

                                        if (!$canAllocate) continue;

                                        // Find a free classroom for ALL slots
                                        foreach ($classrooms as $classroom) {
                                            $roomFree = true;
                                            foreach ($possibleSlots as $slot) {
                                                $slotKey = "{$day}-{$slot[0]}";
                                                if (isset($classroomBusy[$slotKey][$classroom->id])) {
                                                    $roomFree = false;
                                                    break;
                                                }
                                            }
                                            if ($roomFree) {
                                                $selectedRoom = $classroom;
                                                break;
                                            }
                                        }

                                        if ($selectedRoom) {
                                            // Mark busy and queue record for each slot
                                            foreach ($possibleSlots as $slot) {
                                                $slotKey = "{$day}-{$slot[0]}";
                                                $facultyBusy[$slotKey][$faculty->id]        = true;
                                                $sectionBusy[$slotKey][$section->id]         = true;
                                                $classroomBusy[$slotKey][$selectedRoom->id]  = true;

                                                $recordsToInsert[] = [
                                                    'institution_id' => $institutionId,
                                                    'department_id'  => $section->department_id,
                                                    'course_id'      => $section->course_id,
                                                    'academic_year'  => $section->academic_year,
                                                    'semester'       => $section->semester,
                                                    'section_id'     => $section->id,
                                                    'subject_id'     => $subject->id,
                                                    'faculty_id'     => $faculty->id,
                                                    'classroom_id'   => $selectedRoom->id,
                                                    'lecture_type'   => $isLab ? 'Lab' : 'Theory',
                                                    'day_of_week'    => $day,
                                                    'start_time'     => $slot[0],
                                                    'end_time'       => $slot[1],
                                                    'status'         => 1,
                                                    'created_at'     => now(),
                                                    'updated_at'     => now(),
                                                ];
                                            }

                                            $facultyDailyLoad[$day][$faculty->id] =
                                                ($facultyDailyLoad[$day][$faculty->id] ?? 0) + $actualSessionLen;
                                                
                                            // Update subject daily load tracker
                                            $subjectSectionDailyLoad[$day][$section->id][$subject->id] = 
                                                ($subjectSectionDailyLoad[$day][$section->id][$subject->id] ?? 0) + $actualSessionLen;

                                            $remainingHours -= $actualSessionLen;
                                            $allocated       = true;
                                            break 3; // Break out of possible slots, days, and currentTryLen loop
                                        }
                                    } // end startIndices
                                } // end days
                                
                                // Fallback to smaller block size if continuous
                                $currentTryLen--;
                            } // end currentTryLen loop

                            // ── Could not allocate this session with this faculty ──────────────────
                            if (!$allocated) {
                                $lastFailedFaculty = $faculty;
                                $lastFailedReason = 'No available slot found in the working days.';
                                if (!empty($daysSkipped)) {
                                    $lastFailedReason = 'Faculty not available on: ' . implode(', ', $daysSkipped)
                                        . '. Remaining ' . $remainingHours . ' session(s) unscheduled.';
                                }
                                break; // Stop trying with THIS faculty, move to NEXT faculty in the foreach loop
                            }
                        } // end while remainingHours

                        if ($remainingHours <= 0) {
                            break; // Done with this subject entirely!
                        }
                    } // end foreach faculty

                    // If remainingHours > 0 after trying ALL faculties
                    if ($remainingHours > 0) {
                        $shortages[] = [
                            'section'     => $section->name,
                            'subject'     => $subject->name,
                            'faculty'     => $lastFailedFaculty ? $lastFailedFaculty->name : 'Multiple/Unassigned',
                            'reason'      => $lastFailedReason,
                            'days_needed' => $remainingHours,
                        ];
                        Log::warning("UNSCHEDULED: {$subject->name} | {$section->name} | Remaining: $remainingHours | {$lastFailedReason}");
                    }
                } // end subjects
            } // end sections

            // ── Bulk insert ──────────────────────────────────────────────────
            foreach (array_chunk($recordsToInsert, 500) as $chunk) {
                DB::table('institution_schedules')->insert($chunk);
            }
        });

        $inserted = count($recordsToInsert);
        Log::info("Timetable generation DONE — Inserted: $inserted | Shortages: " . count($shortages));

        return [
            'inserted'  => $inserted,
            'shortages' => $shortages,
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Resolve the subset of sections to process based on scope options.
     */
    private function resolveSections(int $institutionId, array $options): \Illuminate\Support\Collection
    {
        $query = Section::with(['additionalDepartments', 'course'])
            ->where('institution_id', $institutionId)
            ->where('status', 1);

        switch ($options['scope_type'] ?? 'all') {
            case 'department':
                if (!empty($options['scope_department'])) {
                    $query->where('department_id', $options['scope_department']);
                }
                break;

            case 'course':
                if (!empty($options['scope_course'])) {
                    $query->where('course_id', $options['scope_course']);
                }
                break;

            case 'course_semester':
                if (!empty($options['scope_course'])) {
                    $query->where('course_id', $options['scope_course']);
                }
                if (!empty($options['scope_semester'])) {
                    $query->where('semester', $options['scope_semester']);
                }
                break;

            case 'section':
                if (!empty($options['scope_section'])) {
                    $query->where('id', $options['scope_section']);
                }
                break;

            // 'all' — no additional filter
        }

        return $query->get();
    }

    /**
     * Selectively clear existing schedules matching the current scope.
     * Avoids wiping unrelated sections when doing a partial regeneration.
     */
    private function clearExistingScope(int $institutionId, array $options): void
    {
        $query = Schedule::where('institution_id', $institutionId)
                         ->where('is_temporary', 0);

        switch ($options['scope_type'] ?? 'all') {
            case 'department':
                if (!empty($options['scope_department'])) {
                    $query->where('department_id', $options['scope_department']);
                }
                break;

            case 'course':
                if (!empty($options['scope_course'])) {
                    $query->where('course_id', $options['scope_course']);
                }
                break;

            case 'course_semester':
                if (!empty($options['scope_course'])) {
                    $query->where('course_id', $options['scope_course']);
                }
                if (!empty($options['scope_semester'])) {
                    $query->where('semester', $options['scope_semester']);
                }
                break;

            case 'section':
                if (!empty($options['scope_section'])) {
                    $query->where('section_id', $options['scope_section']);
                }
                break;

            // 'all' — wipe everything for institution
        }

        $query->delete();
    }

    /**
     * Resolve effective working days for a section.
     * Priority: run-time form override > section.working_days > institution default
     */
    private function resolveWorkingDays(
        Section $section,
        ?array  $runTimeOverride,
        array   $institutionDefault
    ): array {
        if (!empty($runTimeOverride)) {
            return $runTimeOverride;
        }
        if (!empty($section->working_days)) {
            return $section->working_days;
        }
        return $institutionDefault;
    }

    /**
     * Check if a faculty member is available on a given day.
     * If the faculty has no personal working_days, their availability
     * defaults to the section's effective working days.
     */
    private function facultyAvailableOnDay(Faculty $faculty, string $day, array $sectionDays): bool
    {
        $facultyDays = !empty($faculty->working_days) ? $faculty->working_days : $sectionDays;
        return in_array($day, $facultyDays);
    }

    /**
     * Parse slot_timings from the InstitutionAcademicSetting JSON format
     * into an internal [start, end] pair array.
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