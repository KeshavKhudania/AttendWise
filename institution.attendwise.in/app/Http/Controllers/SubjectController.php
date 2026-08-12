<?php

namespace App\Http\Controllers;

use App\Models\ClassRoomType;
use App\Models\Course;
use App\Models\Department;
use App\Models\SemesterSubject;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;

class SubjectController extends Controller
{
    function index(Request $req)
    {

        $data = [
            "subjects" => Subject::all(),
            // "adminal_id"=>,

            "title" => "Manage Subjects",
            "semester_subjects" => SemesterSubject::all(),
        ];
        return view("subject.index", $data);
    }
    public function semester_subject_mapping(Request $request)
    {
        $institutionId = get_logged_in_user()->institution_id;
        $courses = Course::where('institution_id', $institutionId)->get();
        $departments = Department::where('institution_id', $institutionId)->where('is_additional', 0)->get();
        $subjects = Subject::with(['department', 'additionalDepartment'])->where('institution_id', $institutionId)->orderBy('name')->get();

        $selectedCourseId = $request->get('course_id');
        $selectedCourse = null;
        $mappings = collect();

        if ($selectedCourseId) {
            $selectedCourse = Course::where('institution_id', $institutionId)->find($selectedCourseId);
            if ($selectedCourse) {
                $mappings = SemesterSubject::where('institution_id', $institutionId)
                    ->where('course_id', $selectedCourseId)
                    ->get()
                    ->keyBy('semester');
            }
        }

        return view('semester_subject.index', compact('courses', 'departments', 'subjects', 'selectedCourse', 'mappings'));
    }

    function formView(Request $req)
    {
        if ($req->segment(3)) {
            $details = Subject::find(Crypt::decrypt($req->segment(3)), ['*']);
            if (!$details) {
                return abort(404, "Page Not Found");
            }
            $fields = $details;
            $data = [
                "title" => "Edit Subject",
                "type" => "EDIT",
                "action" => route("institution.subject.update", ["id" => $req->segment(3)]),
                "subject" => $fields,
                "courses" => Course::all(),
                "departments" => Department::all(),
                "additional_departments" => Department::all(),
                "classroom_types" => ClassRoomType::all(),
            ];
        }
        else {
            $fields = [];

            foreach (Schema::getColumnListing("institution_subjects") as $value) {
                $fields[$value] = null;
            }
            $data = [
                "title" => "Add Subject",
                "type" => "ADD",
                "action" => route("institution.subject.create"),
                "subject" => (object)$fields,
                "courses" => Course::all(),
                "departments" => Department::all(),
                "additional_departments" => Department::all(),
                "classroom_types" => ClassRoomType::all(),
            ];
        }
        return view("subject.form", $data);
    }
    // Old mapping form view removed for single-page curriculum builder
    function form(Request $req)
    {
        if ($req->segment(3)) {
            $data = [];
            foreach (Schema::getColumnListing('institution_subjects') as $value) {
                if (in_array($value, ['id', 'created_at', 'updated_at', 'deleted_at']))
                    continue;
                if ($req->has($value)) {
                    if ($value == 'latlng' && $req->post($value) != null) {
                        $data[$value] = serialize($req->post($value));
                    }
                    else {
                        $data[$value] = $req->input($value);
                    }
                }
            }
            if (!isset($data['institution_id'])) {
                $data['institution_id'] = get_logged_in_user()->institution_id;
            }
            if (Subject::find(Crypt::decrypt($req->segment(3)), ['*'])->update($data)) {
                return json_encode(["msg" => "Subject Updated.", "color" => "success", "icon" => "check-circle"]);
            }
            return abort("403", json_encode(["msg" => "Something went wrong.", "color" => "danger", "icon" => "exclamation-circle"]));
        }
        else {
            $data = [];
            // $data['ulid'] = Str::ulid();
            // return ;
            foreach (Schema::getColumnListing('institution_subjects') as $value) {
                if (in_array($value, ['id', 'created_at', 'updated_at', 'deleted_at']))
                    continue;
                if ($req->has($value)) {
                    if ($value == 'latlng' && $req->post($value) != null) {
                        $data[$value] = serialize($req->post($value));
                    }
                    else {
                        $data[$value] = $req->input($value);
                    }
                }
            }
            // return;

            if (!isset($data['institution_id'])) {
                $data['institution_id'] = get_logged_in_user()->institution_id;
            }

            $subject = Subject::create($data); // uses casts to encrypt
            if ($subject) {
                return json_encode(["msg" => "Subject Created.", "color" => "success", "icon" => "check-circle"]);
            }
            return abort("403", json_encode(["msg" => "Something went wrong.", "color" => "danger", "icon" => "exclamation-circle"]));
        }
    }
    function delete(Request $req)
    {
        try {
            if ($id = Crypt::decrypt($req->segment(3))) {
                Subject::findOrFail($id)->delete();
                return json_encode(["msg" => "Subject Deleted.", "color" => "success", "icon" => "check-circle"]);
            }
        }
        catch (\Throwable $th) {
            return abort(401);
        }
    }
    public function saveCurriculum(Request $request)
    {
        $institutionId = get_logged_in_user()->institution_id;
        $courseId = $request->input('course_id');
        $departmentId = $request->input('department_id');
        $semesterSubjects = $request->input('semesters', []); // format: [ semester_no => [subject_ids] ]

        if (!$courseId) {
            return redirect()->back()->with(['msg' => 'Course selection is required.', 'color' => 'danger']);
        }

        $course = Course::where('institution_id', $institutionId)->find($courseId);
        if (!$course) {
            return redirect()->back()->with(['msg' => 'Invalid course selected.', 'color' => 'danger']);
        }

        if (!$departmentId) {
            $departmentId = $course->department_id ?? Department::where('institution_id', $institutionId)->value('id');
        }

        // Update total semesters if passed
        $totalSemesters = $request->input('total_semesters', count($semesterSubjects));
        if ($totalSemesters > 0) {
            $course->total_semesters = (int)$totalSemesters;
            $course->save();
        }

        // Clean up removed semesters from database
        SemesterSubject::where('institution_id', $institutionId)
            ->where('course_id', $courseId)
            ->where('semester', '>', $course->total_semesters)
            ->delete();

        foreach ($semesterSubjects as $semester => $subjectIds) {
            if ((int)$semester > (int)$course->total_semesters) {
                continue;
            }
            if (empty($subjectIds)) {
                SemesterSubject::where('institution_id', $institutionId)
                    ->where('course_id', $courseId)
                    ->where('semester', $semester)
                    ->delete();
            } else {
                SemesterSubject::updateOrCreate(
                    [
                        'institution_id' => $institutionId,
                        'course_id' => $courseId,
                        'semester' => $semester,
                    ],
                    [
                        'department_id' => $departmentId,
                        'subjects' => $subjectIds,
                    ]
                );
            }
        }

        return redirect()->route('institution.subject.manage.mapping.index', ['course_id' => $courseId])
            ->with(['msg' => 'Curriculum saved successfully!', 'color' => 'success']);
    }

    public function fetchSubjects(Request $req)
    {
        $data = $req->post("data");
        if (!$data) {
            return response()->json(["status" => "error", "msg" => "No data provided."]);
        }

        $course_id = $data['course_id'] ?? null;
        $semester = $data['semester'] ?? null;
        $department_id = $data['department_id'] ?? null;
        $additional_department_ids = $data['additional_department_ids'] ?? []; // Expecting an array

        $institutionId = get_logged_in_user()->institution_id;

        $query = Subject::where('institution_id', $institutionId);

        if ($course_id) {
            $query->where('course_id', $course_id);
        }

        if ($semester) {
            $query->where('semester', $semester);
        }

        if ($department_id || !empty($additional_department_ids)) {
            $query->where(function($q) use ($department_id, $additional_department_ids) {
                if ($department_id) {
                    $q->orWhere('department_id', $department_id);
                }
                if (!empty($additional_department_ids)) {
                    $q->orWhereIn('additional_department_id', $additional_department_ids);
                }
            });
        }

        $subjects = $query->orderBy('name')->get();

        return response()->json([
            "status" => "success",
            "data" => $subjects,
            "msg" => "Subjects fetched successfully.",
            "color" => "success",
            "icon" => "check-circle",
        ])->getContent();
    }
}