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
                FacultySubject::where('faculty_id', Crypt::decrypt($req->segment(3)))->delete();
                foreach ($req->post('subjects', []) as $subjectId) {
                    FacultySubject::create([
                        'institution_id' => get_logged_in_user()->institution_id,
                        'faculty_id' => Crypt::decrypt($req->segment(3)),
                        'subject_id' => Crypt::decryptString($subjectId),
                        'status' => 1,
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
}