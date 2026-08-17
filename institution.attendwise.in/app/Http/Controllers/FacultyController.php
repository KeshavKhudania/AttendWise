<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\FacultySubject;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class FacultyController extends Controller
{
    function index(Request $req)
    {

        $data = [
            "faculty" => Faculty::all(),
            // "adminal_id"=>,
            "title" => "Manage Faculties"
        ];
        return view("faculty.index", $data);
    }

    public function schedule(Request $req, $encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);
        $faculty = Faculty::findOrFail($id);

        $institutionId = get_logged_in_user()->institution_id;

        $settings = \App\Models\InstitutionAcademicSetting::where('institution_id', $institutionId)->first();
        $slotTimingsRaw = $settings?->slot_timings ?? [];
        $slotTimings = [];
        if (is_string($slotTimingsRaw)) {
            $slotTimingsRaw = json_decode($slotTimingsRaw, true) ?? [];
        }
        foreach ($slotTimingsRaw as $slot) {
            if (is_array($slot) && isset($slot['start']) && isset($slot['end'])) {
                $slotTimings[] = ['start' => $slot['start'], 'end' => $slot['end']];
            }
        }
        if (empty($slotTimings)) {
            // Default slots
            $slotTimings = [
                ['start' => '09:00:00', 'end' => '10:00:00'],
                ['start' => '10:00:00', 'end' => '11:00:00'],
                ['start' => '11:15:00', 'end' => '12:15:00'],
                ['start' => '12:15:00', 'end' => '13:15:00'],
                ['start' => '14:00:00', 'end' => '15:00:00'],
                ['start' => '15:00:00', 'end' => '16:00:00'],
                ['start' => '16:00:00', 'end' => '17:00:00'],
            ];
        }

        $schedules = \App\Models\Schedule::with(['subject', 'section', 'classroom'])
            ->where('institution_id', $institutionId)
            ->where('faculty_id', $id)
            ->orderBy('start_time')
            ->get();

        $dayOrder = $settings?->working_days ?? ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        if (is_string($dayOrder)) {
            $dayOrder = json_decode($dayOrder, true) ?? ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        }

        $holidaysRaw = $settings?->holidays ?? [];
        if (is_string($holidaysRaw)) {
            $holidaysRaw = json_decode($holidaysRaw, true) ?? [];
        }
        // $holidaysRaw might be array of dates or objects
        $holidays = collect($holidaysRaw);

        $events = \DB::table('institution_events')
            ->where('institution_id', $institutionId)
            ->where('event_date', '>=', now()->format('Y-m-d'))
            ->where('status', 1)
            ->orderBy('event_date', 'asc')
            ->limit(5)
            ->get();

        $groupedSchedules = $schedules->sortBy(function($slot) use ($dayOrder) {
            return array_search($slot->day_of_week, $dayOrder) . $slot->start_time;
        })->groupBy('day_of_week');

        $data = [
            'faculty' => $faculty,
            'groupedSchedules' => $groupedSchedules,
            'dayOrder' => $dayOrder,
            'slotTimings' => $slotTimings,
            'events' => $events,
            'holidays' => $holidays,
            'title' => $faculty->name . "'s Timetable"
        ];

        return view('faculty.schedule', $data);
    }
    function formView(Request $req)
    {
        if ($req->segment(3)) {
            $details = Faculty::find(Crypt::decrypt($req->segment(3)));
            if (!$details) {
                return abort(404, "Page Not Found");
            }
            $fields = $details;
            $data = [
                "title" => "Edit Faculty",
                "type" => "EDIT",
                "action" => route("institution.faculty.update", ["id" => $req->segment(3)]),
                "faculty" => $fields,
                // "courses"=>Course::all(),
                "departments" => Department::all(),
                "subjects" => Subject::all(),

                "selectedSubjects" => $details->subjects->pluck('id')->toArray(),
            ];
        }
        else {
            $fields = [];

            foreach (Schema::getColumnListing("institution_faculties") as $value) {
                $fields[$value] = null;
            }
            // $faculty = Faculty::with('subjects')->findOrFail();

            $selectedSubjects = [];

            $data = [
                "title" => "Add Faculty",
                "type" => "ADD",
                "action" => route("institution.faculty.create"),
                "faculty" => $fields,
                "courses" => Course::all(),
                "departments" => Department::all(),
                "subjects" => Subject::all(),
                "selectedSubjects" => $selectedSubjects,
            ];
        }
        return view("faculty.form", $data);
    }
    function form(Request $req)
    {
        if ($req->segment(3)) {
            $data = [];
            foreach (Schema::getColumnListing('institution_faculties') as $value) {
                if (in_array($value, ['id', 'created_at', 'updated_at', 'deleted_at']))
                    continue;
                if ($req->has($value)) {
                    if ($value == 'subjects' && $req->post($value) != null) {
                        foreach ($req->post($value) as $subjectId) {
                            $data['subjects'][] = [
                                'subject_id' => $subjectId,
                                'status' => 1,
                            ];
                        }
                    }
                    else {
                        $data[$value] = $req->input($value);
                    }
                }
            }
            if (Faculty::find(Crypt::decrypt($req->segment(3)))->update($data)) {
                // Persist working_days explicitly (array → JSON via model cast)
                if ($req->has('working_days')) {
                    Faculty::find(Crypt::decrypt($req->segment(3)))
                        ->update(['working_days' => $req->input('working_days')]);
                }
                FacultySubject::where('faculty_id', Crypt::decrypt($req->segment(3)))->delete();
                foreach ($req->post('subjects', []) as $subjectId) {
                    FacultySubject::create([
                        'institution_id' => get_logged_in_user()->institution_id,
                        'faculty_id'     => Crypt::decrypt($req->segment(3)),
                        'subject_id'     => Crypt::decryptString($subjectId),
                        'status'         => 1,
                    ]);
                }
                return json_encode(["msg" => "Faculty Updated.", "color" => "success", "icon" => "check-circle"]);
            }
            return abort("403", json_encode(["msg" => "Something went wrong.", "color" => "danger", "icon" => "exclamation-circle"]));
        }
        else {
            $data = [];
            // $data['ulid'] = Str::ulid();
            // return ;
            foreach (Schema::getColumnListing('institution_faculties') as $value) {
                if (in_array($value, ['id', 'created_at', 'updated_at', 'deleted_at']))
                    continue;
                if ($req->has($value)) {
                    if ($value == 'subjects' && $req->post($value) != null) {
                        // $data[$value] = serialize($req->post($value));
                        foreach ($req->post($value) as $subjectId) {
                            $data['subjects'][] = [
                                'subject_id' => $subjectId,
                                'status' => 1,
                            ];
                        }
                    }
                    else {
                        $data[$value] = $req->input($value);
                    }
                }
            }
            // return;

            if (env('APP_DEBUG') == true) {
                $data['password'] = 'password123';
            }
            else {
                $data['password'] = Str::random(12);
            }
            // set a default random password
            $faculty = Faculty::create($data); // uses casts to encrypt
            foreach ($req->post('subjects', []) as $subjectId) {
                FacultySubject::create([
                    'institution_id' => get_logged_in_user()->institution_id,
                    'faculty_id' => $faculty->id,
                    'subject_id' => Crypt::decryptString($subjectId),
                    'status' => 1,
                ]);
            }
            if ($faculty) {
                return json_encode(["msg" => "Faculty Created.", "color" => "success", "icon" => "check-circle"]);
            }
            return abort("403", json_encode(["msg" => "Something went wrong.", "color" => "danger", "icon" => "exclamation-circle"]));
        }
    }
    function delete(Request $req)
    {
        try {
            if ($id = Crypt::decrypt($req->segment(3))) {
                Faculty::findOrFail($id)->delete();
                return json_encode(["msg" => "Faculty Deleted.", "color" => "success", "icon" => "check-circle"]);
            }
        }
        catch (\Throwable $th) {
            return abort(401);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:csv,txt,xlsx,xls',
        ]);

        $path = $request->file('excel_file')->store('imports/faculty');

        \App\Jobs\ImportFacultyJob::dispatch($path, get_logged_in_user()->institution_id);

        return response()->json([
            "msg" => "Faculty import job has been queued. You will be notified once it's completed.",
            "color" => "success",
            "icon" => "check-circle"
        ]);
    }

    public function downloadSample()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="faculty_import_sample.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'name (Required)', 'email (Required)', 'mobile', 'gender', 'designation', 'department', 'employee_code', 'date_of_birth', 'blood_group', 'nationality', 'national_id', 'pan_number', 'permanent_address', 'current_address', 'emergency_contact_name', 'emergency_contact_number', 'joining_date', 'leaving_date', 'employment_type', 'working_days', 'highest_qualification', 'years_of_experience', 'bank_account_no', 'bank_name', 'bank_ifsc', 'basic_salary', 'password'
            ]);
            
            fputcsv($file, [
                'Dr. Alice Smith', 
                'alice.smith@example.com', 
                '9876543211', 
                'female', 
                'Professor', 
                'Computer Science',
                'EMP001', '1980-01-01', 'O+', 'Indian', 'ID12345', 'PAN123', 'Address 1', 'Address 2', 'Bob Smith', '9876543212', '2023-01-01', '', 'Full Time', '5', 'PhD', '10', '1234567890', 'Bank Name', 'IFSC001', '100000', 'Password123'
            ]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}