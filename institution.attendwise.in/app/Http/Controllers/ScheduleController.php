<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Section;
use App\Models\Course;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $institutionId = get_logged_in_user()->institution_id;

        $query = Section::with([
            'course',
            'classGroups',
            'schedules.subject',
            'schedules.faculty',
            'schedules.classroom'
        ])
            ->where('institution_id', $institutionId);

        // Apply Filters
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        $sections = $query->orderBy('academic_year', 'desc')
            ->orderBy('semester')
            ->orderBy('name')
            ->get();

        $courses = Course::where('institution_id', $institutionId)->get();

        // Get unique semesters for the filter dropdown
        $semesters = Section::where('institution_id', $institutionId)
            ->whereNotNull('semester')
            ->distinct()
            ->orderBy('semester')
            ->pluck('semester');

        return view('schedule.index', compact('sections', 'courses', 'semesters'));
    }

    public function temporaryIndex(Request $request)
    {
        $institutionId = get_logged_in_user()->institution_id;
        $title = "Temporary Timetable";

        // Optional: show recently created temporary overrides
        $temporaryDates = Schedule::where('institution_id', $institutionId)
            ->where('is_temporary', 1)
            ->select('schedule_date')
            ->distinct()
            ->orderBy('schedule_date', 'desc')
            ->get();

        return view('schedule.temporary', compact('title', 'temporaryDates'));
    }

    public function generateTemporary(Request $request)
    {
        $request->validate([
            'target_date' => 'required|date',
            'action_type' => 'required|in:copy,clear',
            'reference_day' => 'required_if:action_type,copy|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
        ]);

        $institutionId = get_logged_in_user()->institution_id;
        $targetDate = $request->target_date;

        // Common step: Clear any existing temporary schedule for the target date
        Schedule::where('institution_id', $institutionId)
            ->where('is_temporary', 1)
            ->where('schedule_date', $targetDate)
            ->delete();

        if ($request->action_type === 'clear') {
            return redirect()->back()->with(['msg' => "Temporary schedule for {$targetDate} cleared successfully.", 'color' => 'success']);
        }

        // Action: Copy
        $referenceDay = $request->reference_day;
        
        // Fetch all regular schedules for the reference day
        $baseSchedules = Schedule::where('institution_id', $institutionId)
            ->where('is_temporary', 0)
            ->where('day_of_week', $referenceDay)
            ->get();

        if ($baseSchedules->isEmpty()) {
            return redirect()->back()->with(['msg' => "No schedules found on {$referenceDay} to copy from.", 'color' => 'warning']);
        }

        // Duplicate them as temporary records for the target date
        $recordsToInsert = [];
        $createdAt = now();
        $targetDayName = date('l', strtotime($targetDate)); // e.g., 'Saturday'

        foreach ($baseSchedules as $schedule) {
            $recordsToInsert[] = [
                'institution_id' => $schedule->institution_id,
                'department_id' => $schedule->department_id,
                'course_id' => $schedule->course_id,
                'academic_year' => $schedule->academic_year,
                'semester' => $schedule->semester,
                'section_id' => $schedule->section_id,
                'class_group_id' => $schedule->class_group_id,
                'subject_id' => $schedule->subject_id,
                'faculty_id' => $schedule->faculty_id,
                'classroom_id' => $schedule->classroom_id,
                'lecture_type' => $schedule->lecture_type,
                'day_of_week' => $targetDayName, // Set it to target day's actual name for UI consistency
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'status' => $schedule->status,
                'is_temporary' => 1,
                'schedule_date' => $targetDate,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }

        // Chunk inserts for performance
        foreach (array_chunk($recordsToInsert, 500) as $chunk) {
            \Illuminate\Support\Facades\DB::table('institution_schedules')->insert($chunk);
        }

        return redirect()->back()->with(['msg' => "Temporary schedule generated for {$targetDate} using {$referenceDay}'s timetable.", 'color' => 'success']);
    }

    /**
     * AUTO GENERATE TIMETABLE
     */
    public function autoGenerate(Request $request)
    {
        $institutionId = get_logged_in_user()->institution_id;

        app(\App\Services\ScheduleAutoGenerate::class)
            ->generateForInstitution($institutionId);

        return redirect()
            ->back()
            ->with(['msg' => 'Timetable generated successfully.', 'color' => 'success']);
    }

    /**
     * DOWNLOAD SAMPLE TIMETABLE CSV
     */
    public function downloadSample()
    {
        $headers = [
            'Section Name',
            'Day',
            'Start Time (HH:MM:SS)',
            'End Time (HH:MM:SS)',
            'Subject Code',
            'Faculty Email',
            'Classroom Name',
            'Type (Theory/Lab)'
        ];

        $callback = function () use ($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);

            // Sample row
            fputcsv($file, ['CS-A', 'Monday', '09:00:00', '10:00:00', 'CS301', 'faculty@example.com', 'Room 101', 'Theory']);

            fclose($file);
        };

        return response()->stream($callback, 200, [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=timetable_template.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ]);
    }

    /**
     * UPLOAD TIMETABLE CSV
     */
    public function upload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $institutionId = get_logged_in_user()->institution_id;
        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        // Skip header
        fgetcsv($handle);

        $insertedCount = 0;
        $errors = [];
        $rowNum = 1;

        if ($request->has('clear_existing')) {
            \App\Models\Schedule::where('institution_id', $institutionId)->delete();
        }

        while (($data = fgetcsv($handle)) !== FALSE) {
            $rowNum++;
            if (count($data) < 7)
                continue;

            $sectionName = trim($data[0]);
            $day = trim($data[1]);
            $startTime = trim($data[2]);
            $endTime = trim($data[3]);
            $subjectCode = trim($data[4]);
            $facultyEmail = trim($data[5]);
            $roomName = trim($data[6]);
            $type = trim($data[7] ?? 'Theory');

            // 1. Lookup Section
            $section = Section::where('institution_id', $institutionId)
                ->where('name', $sectionName)
                ->first();

            if (!$section) {
                $errors[] = "Row {$rowNum}: Section '{$sectionName}' not found.";
                continue;
            }

            // 2. Lookup Subject
            $subject = \App\Models\Subject::where('institution_id', $institutionId)
                ->where('code', $subjectCode)
                ->first();

            if (!$subject) {
                $errors[] = "Row {$rowNum}: Subject code '{$subjectCode}' not found.";
                continue;
            }

            // 3. Lookup Faculty
            // Note: email is encrypted in the database. Use whereEncryptedEmail if trait is active, 
            // or just manual search_hash if we have it in helpers.
            // Based on previous logs, we have Builder::macro('whereEncryptedEmail').
            $faculty = \App\Models\Faculty::whereEncryptedEmail($facultyEmail)
                ->where('institution_id', $institutionId)
                ->first();

            if (!$faculty) {
                $errors[] = "Row {$rowNum}: Faculty with email '{$facultyEmail}' not found.";
                continue;
            }

            // 4. Lookup Classroom
            $classroom = \App\Models\Classroom::where('institution_id', $institutionId)
                ->where('name', $roomName)
                ->first();

            if (!$classroom) {
                $errors[] = "Row {$rowNum}: Classroom '{$roomName}' not found.";
                continue;
            }

            // 5. Create Schedule
            \App\Models\Schedule::create([
                'institution_id' => $institutionId,
                'department_id' => $section->department_id,
                'course_id' => $section->course_id,
                'academic_year' => $section->academic_year,
                'semester' => $section->semester,
                'section_id' => $section->id,
                'subject_id' => $subject->id,
                'faculty_id' => $faculty->id,
                'classroom_id' => $classroom->id,
                'lecture_type' => $type,
                'day_of_week' => $day,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => 1,
            ]);

            $insertedCount++;
        }

        fclose($handle);

        $msg = "Import complete. {$insertedCount} records added.";
        if (!empty($errors)) {
            $msg .= " Errors found in " . count($errors) . " rows.";
            return redirect()->back()->with(['msg' => $msg, 'color' => 'warning'])->withErrors($errors);
        }

        return redirect()->back()->with(['msg' => $msg, 'color' => 'success']);
    }

    /**
     * EXPORT TIMETABLE TO EXCEL
     */
    public function exportExcel($id)
    {
        $institutionId = get_logged_in_user()->institution_id;
        $section = Section::with([
            'course',
            'schedules.subject',
            'schedules.faculty',
            'schedules.classroom'
        ])
            ->where('institution_id', $institutionId)
            ->findOrFail($id);

        $settings = \App\Models\InstitutionAcademicSetting::where('institution_id', $institutionId)->first();

        $html = $this->getExcelWrapperStart($section->name);
        $html .= $this->getExcelStyles();
        $html .= $this->generateTimetableHtml($section, $settings);
        $html .= $this->getExcelWrapperEnd();

        $filename = "Timetable_{$section->name}.xls";

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"")
            ->header('Cache-Control', 'max-age=0');
    }

    /**
     * BULK EXPORT TIMETABLE
     */
    public function exportExcelBulk(Request $request)
    {
        $institutionId = get_logged_in_user()->institution_id;
        $ids = $request->input('selected_ids', []);

        if (empty($ids)) {
            return redirect()->back()->with(['msg' => 'No sections selected for export.', 'color' => 'danger']);
        }

        $sections = Section::with([
            'course',
            'schedules.subject',
            'schedules.faculty',
            'schedules.classroom'
        ])
            ->where('institution_id', $institutionId)
            ->whereIn('id', $ids)
            ->get();

        $settings = \App\Models\InstitutionAcademicSetting::where('institution_id', $institutionId)->first();

        $html = $this->getExcelWrapperStart('Bulk Timetable');
        $html .= $this->getExcelStyles();

        foreach ($sections as $section) {
            $html .= '<h2 style="text-align:center; margin-top: 30px;">Section: ' . $section->name . ' (' . $section->course->name . ')</h2>';
            $html .= $this->generateTimetableHtml($section, $settings);
            $html .= '<div style="page-break-after: always; height: 50px;"></div><br>';
        }

        $html .= $this->getExcelWrapperEnd();

        $filename = "Bulk_Timetable_" . date('Y-m-d') . ".xls";

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"")
            ->header('Cache-Control', 'max-age=0');
    }

    private function getExcelWrapperStart($title)
    {
        return '
        <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <!--[if gte mso 9]>
            <xml>
                <x:ExcelWorkbook>
                    <x:ExcelWorksheets>
                        <x:ExcelWorksheet>
                            <x:Name>' . $title . '</x:Name>
                            <x:WorksheetOptions>
                                <x:DisplayGridlines/>
                            </x:WorksheetOptions>
                        </x:ExcelWorksheet>
                    </x:ExcelWorksheets>
                </x:ExcelWorkbook>
            </xml>
            <![endif]-->
        </head>
        <body>';
    }

    private function getExcelWrapperEnd()
    {
        return '</body></html>';
    }

    private function getExcelStyles()
    {
        return '
        <style>
            .header { background-color: #000000; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #333; }
            .day-cell { background-color: #1a1a1a; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; width: 100px; border: 1px solid #333; }
            .slot-cell { background-color: #000000; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #333; }
            .lunch-cell { background-color: #1a1a1a; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #333; }
            .data-cell { background-color: #000000; color: #ffffff; text-align: center; vertical-align: middle; height: 100px; width: 180px; border: 1px solid #333; }
            .free-cell { background-color: #000000; color: #555; text-align: center; vertical-align: middle; height: 100px; width: 180px; border: 1px solid #333; font-style: italic; }
            br { mso-data-placement:same-cell; }
        </style>';
    }

    private function generateTimetableHtml($section, $settings = null)
    {
        $dayOrder = $settings?->working_days ?? ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        $timeSlots = [];
        if ($settings && !empty($settings->slot_timings)) {
            foreach ($settings->slot_timings as $id => $slot) {
                if (empty($slot['start']) || empty($slot['end'])) continue;
                $timeSlots[] = [
                    'start' => substr($slot['start'], 0, 5),
                    'end' => substr($slot['end'], 0, 5),
                    'label' => date("g:i", strtotime($slot['start'])) . ' - ' . date("g:i a", strtotime($slot['end'])),
                    'id' => $id
                ];
            }
        } 
        else {
            // Default Fallback
            $timeSlots = [
                ['start' => '09:30', 'end' => '10:20', 'label' => '9:30 - 10:20', 'id' => 1],
                ['start' => '10:20', 'end' => '11:10', 'label' => '10:20 - 11:10', 'id' => 2],
                ['start' => '11:10', 'end' => '12:00', 'label' => '11:10 - 12:00', 'id' => 3],
                ['start' => '12:00', 'end' => '12:50', 'label' => '12:00 - 12:50', 'id' => 4],
                // ['start' => '12:50', 'end' => '13:35', 'label' => '12:50 - 01:35', 'id' => 5, 'special' => 'Lunch Break'],
                ['start' => '13:35', 'end' => '14:20', 'label' => '01:35 - 02:20', 'id' => 6],
                ['start' => '14:20', 'end' => '15:05', 'label' => '02:20 - 03:05', 'id' => 7],
                ['start' => '15:05', 'end' => '15:50', 'label' => '03:05 - 03:50', 'id' => 8],
            ];
        }

        $schedules = $section->schedules->groupBy(function ($item) {
            return $item->day_of_week . '|' . substr($item->start_time, 0, 5);
        });

        $html = '<table border="1">';
        $html .= '<tr>
                <th class="header"></th>';

        foreach ($timeSlots as $slot) {
            $html .= '<th class="slot-cell">' . $slot['label'] . '</th>';
        }
        $html .= '</tr>';

        foreach ($dayOrder as $dayIndex => $day) {
            $html .= '<tr>
                    <td class="day-cell">' . strtoupper($day) . '</td>';

            foreach ($timeSlots as $slot) {
                if (isset($slot['special'])) {
                    if ($dayIndex === 0) {
                        $html .= '<td rowspan="' . count($dayOrder) . '" class="lunch-cell">' . strtoupper($slot['special']) . '</td>';
                    }
                    continue;
                }

                $key = $day . '|' . $slot['start'];
                $slots = $schedules->get($key);

                if ($slots) {
                    $html .= '<td class="data-cell">';
                    foreach ($slots as $idx => $s) {
                        if ($idx > 0)
                            $html .= '<br><hr style="border: 0.5px solid #333;"><br>';

                        $subjectName = $s->subject->code ?: $s->subject->name;

                        // Add Class Group if available
                        $group = \App\Models\ClassGroup::find($s->class_group_id);
                        if ($group) {
                            $subjectName .= " (" . $group->name . ")";
                        }
                        else if ($s->lecture_type == 'Lab') {
                            $subjectName .= " (Lab)";
                        }

                        $facultyName = $s->faculty->name;
                        $room = $s->classroom ? ($s->classroom->name ?? $s->classroom->room_number) : '';

                        $content = "<strong>" . $subjectName . "</strong>";
                        $content .= "<br>" . $facultyName;
                        if ($room) {
                            $content .= "_" . $room;
                        }

                        $html .= $content;
                    }
                    $html .= '</td>';
                }
                else {
                    $html .= '<td class="free-cell">FREE</td>';
                }
            }
            $html .= '</tr>';
        }
        $html .= '</table>';
        return $html;
    }
}