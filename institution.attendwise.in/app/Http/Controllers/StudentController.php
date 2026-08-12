<?php

namespace App\Http\Controllers;

use App\Jobs\ImportStudentsJob;
use App\Models\Course;
use App\Models\Department;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StudentController extends Controller
{
    function index(Request $req){
        $perPage = $req->input('per_page', 200);
        if ($perPage > 1000) $perPage = 1000;
        if ($perPage < 200) $perPage = 200;

        $data = [
            "students"=>Student::paginate($perPage)->withQueryString(),
            "title"=>"Manage Students",
            "per_page"=>$perPage
        ];
        return view("student.index", $data);
    }
    function formView(Request $req){
        if ($req->segment(3)) {
            $details = Student::find(Crypt::decrypt($req->segment(3)));
            if (!$details) {
                return abort(404,"Page Not Found");
            }
            $fields = $details;
            $data = [
                "title"=>"Edit Student",
                "type"=>"EDIT",
                "action"=>route("institution.student.update", ["id"=>$req->segment(3)]),
                "student"=>$fields,
                "courses"=>Course::all(),
                "departments"=>Department::all(),
                "sections"=>Section::where('course_id', $details->course_id)->get(),
                // "classroom_types"=>ClassRoomType::all(),
            ];
        }else{
            $fields = [];
            
            foreach (Schema::getColumnListing("institution_students") as $value) {
                $fields[$value] = null;
            }
            $data = [
                "title"=>"Add Student",
                "type"=>"ADD",
                "action"=>route("institution.student.create"),
                "student"=>$fields,
                "courses"=>Course::all(),
                "departments"=>Department::all(),
                "sections"=>[],
                // "classroom_types"=>ClassRoomType::all(),
            ];
        }
        return view("student.form", $data);
    }
    function form(Request $req){
        if ($req->segment(3)) {
            $data = [];
            foreach (Schema::getColumnListing('institution_students') as $value) {
                if (in_array($value, ['id','created_at','updated_at','deleted_at'])) continue;
                if ($value == 'password' && empty($req->input($value))) continue;
                if ($req->has($value)) {
                 if ($value == 'latlng' && $req->post($value) != null) {
                        $data[$value] = serialize($req->post($value));
                    }else{
                        $data[$value] = $req->input($value);
                    }   
                }
            }
            if(Student::find(Crypt::decrypt($req->segment(3)))->update($data)){
                return json_encode(["msg"=>"Student Updated.", "color"=>"success", "icon"=>"check-circle"]);
            }
            return abort("403", json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
        }else{
            $data = [];
            // $data['ulid'] = Str::ulid();
            // return ;
            foreach (Schema::getColumnListing('institution_students') as $value) {
                if (in_array($value, ['id','created_at','updated_at','deleted_at'])) continue;
                if ($value == 'password' && empty($req->input($value))) continue;
                if ($req->has($value)) {
                    if ($value == 'latlng' && $req->post($value) != null) {
                        $data[$value] = serialize($req->post($value));
                    }else{
                        $data[$value] = $req->input($value);
                    }
                }
            }
            // return;

            if (!isset($data['password'])) {
                if (env('APP_DEBUG') == true) {
                    $data['password'] = 'password123';
                } else {
                    $data['password'] = \Illuminate\Support\Str::random(12);
                }
            }

            $student = Student::create($data); // uses casts to encrypt
            if($student){
                return json_encode(["msg"=>"Student Created.", "color"=>"success", "icon"=>"check-circle"]);
            }
            return abort("403", json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
        }
    }
    function delete(Request $req){
        try {
            if ($id = Crypt::decrypt($req->segment(3))) {
                Student::findOrFail($id)->delete();
                return json_encode(["msg"=>"Student Deleted.", "color"=>"success", "icon"=>"check-circle"]);
            }
        } catch (\Throwable $th) {
            return abort(401);
        }
    }
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
            'avg_section_size' => 'nullable|integer|min:1',
        ]);

        $path = $request->file('csv_file')->store('imports/students');
// dd(
//     $path,
//     gettype($path)
// );
        ImportStudentsJob::dispatch(
            $path,
            get_logged_in_user()->institution_id,
            (bool) $request->assign_sections,
            (bool) $request->auto_create_sections,
            (int) ($request->avg_section_size ?? 30),
            trim($request->section_name_prefix ?? ''),
            (bool) $request->auto_create_class_groups,
            trim($request->class_group_prefix ?? '')
        );



        return response()->json([
            "msg" => "Student import job has been queued. You will be notified once it's completed.",
            "color" => "success",
            "icon" => "check-circle"
        ]);
    }

    public function downloadSample()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="student_import_sample.csv"',
        ];

        $columns = [
            'roll_number', 'name', 'email', 'mobile', 'gender',
            'academic_year', 'semester', 'department', 'course', 'section', 'class_group'
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'roll_number', 'name', 'email', 'mobile', 'gender',
                'academic_year', 'semester', 'department', 'course', 'section', 'class_group'
            ]);
            
            // Add a sample row
            fputcsv($file, [
                'CS2023001', 
                'John Doe', 
                'john@example.com', 
                '9876543210', 
                'male', 
                '2023-24',
                '1',
                'Computer Science', 
                'B.Tech', 
                'A', 
                'G1'
            ]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
