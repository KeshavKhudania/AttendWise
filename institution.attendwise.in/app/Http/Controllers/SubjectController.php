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
    public function semester_subject_mapping()
    {
        $groups = SemesterSubject::with(['department', 'course'])
            ->where('institution_id', get_logged_in_user()->institution_id)
            ->orderBy('department_id')
            ->orderBy('course_id')
            ->orderBy('semester')
            ->get();

        $subjectsIndex = Subject::all()->keyBy('id');
        $departments = Department::all();
        $courses = Course::all();
        $subjects = Subject::all();
        return view('semester_subject.index', compact('groups', 'subjectsIndex', 'departments', 'courses', 'subjects'));
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
                "subject" => $fields,
                "courses" => Course::all(),
                "departments" => Department::all(),
                "classroom_types" => ClassRoomType::all(),
            ];
        }
        return view("subject.form", $data);
    }
    function subjectMappingformView(Request $req)
    {
        if ($req->segment(3)) {
            $details = SemesterSubject::find(Crypt::decrypt($req->segment(3)), ['*']);
            if (!$details) {
                return abort(404, "Page Not Found");
            }
            $fields = $details;
            $data = [
                "title" => "Edit Subject Mapping",
                "type" => "EDIT",
                "action" => route("institution.subject.manage.mapping.update", ["id" => $req->segment(3)]),
                "mapping" => $fields,
                "subjects" => Subject::all(),
                "courses" => Course::all(),
                "departments" => Department::all(),
                "classroom_types" => ClassRoomType::all(),
            ];
        }
        else {
            $fields = [];

            foreach (Schema::getColumnListing("institution_semester_subjects") as $value) {
                $fields[$value] = null;
            }
            $data = [
                "title" => "Add Subject Mapping",
                "type" => "ADD",
                "action" => route("institution.subject.manage.mapping.create"),
                "mapping" => (object)$fields,
                "courses" => Course::all(),
                "departments" => Department::all(),
                "subjects" => Subject::all(),
                "classroom_types" => ClassRoomType::all(),
            ];
        }
        return view("semester_subject.form", $data);
    }
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
    function assignSubjectsToSemester(Request $req)
    {
        if ($req->segment(3)) {
            $data = [];
            foreach (Schema::getColumnListing('institution_semester_subjects') as $value) {
                if (in_array($value, ['id', 'created_at', 'updated_at', 'deleted_at']))
                    continue;
                if ($value == "subjects") {
                    $data[$value] = $req->subjects;
                    continue;
                }
                if ($req->has($value)) {
                    $data[$value] = $req->input($value);
                }
            }
            if (SemesterSubject::find(Crypt::decrypt($req->segment(3)), ['*'])->update($data)) {
                return json_encode(["msg" => "Semester Subject Mapping Updated.", "color" => "success", "icon" => "check-circle"]);
            }
            return abort("403", json_encode(["msg" => "Something went wrong.", "color" => "danger", "icon" => "exclamation-circle"]));
        }
        else {
            SemesterSubject::updateOrCreate(
            [
                'institution_id' => get_logged_in_user()->institution_id,
                'department_id' => $req->department_id,
                'course_id' => $req->course_id,
                'semester' => $req->semester,
            ],
            [
                'subjects' => $req->subjects,
            ]
            );

            return json_encode(["msg" => "Subjects assigned to semester successfully.", "color" => "success", "icon" => "check-circle"]);
        }
    }
    function DeleteSemesterSubject(Request $req)
    {
        try {
            if ($id = Crypt::decrypt($req->segment(3))) {
                SemesterSubject::findOrFail($id)->delete();
                return json_encode(["msg" => "Semester Subject Mapping Deleted.", "color" => "success", "icon" => "check-circle"]);
            }
        }
        catch (\Throwable $th) {
            return abort(401);
        }
    }
}