<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;

class CourseController extends Controller
{
    function index(Request $req){
        
        $data = [
            "courses"=>Course::all(),
            // "adminal_id"=>,
            "title"=>"Manage Courses"
        ];
        return view("courses.index", $data);
    }
    function formView(Request $req){
        if ($req->segment(3)) {
            $details = Course::find(Crypt::decrypt($req->segment(3)));
            if (!$details) {
                return abort(404,"Page Not Found");
            }
            $fields = $details;
            $data = [
                "title"=>"Edit Course",
                "type"=>"EDIT",
                "action"=>route("institution.courses.update", ["id"=>$req->segment(3)]),
                "course"=>$fields,
                "departments"=>Department::all(),
            ];
        }else{
            $fields = [];
            
            foreach (Schema::getColumnListing("institution_courses") as $value) {
                $fields[$value] = null;
            }
            $data = [
                "title"=>"Add Course",
                "type"=>"ADD",
                "action"=>route("institution.courses.create"),
                "course"=>$fields,
                "departments"=>Department::all(),
            ];
        }
        return view("courses.form", $data);
    }
    function form(Request $req){
        if ($req->segment(3)) {
            $data = [];
            foreach (Schema::getColumnListing('institution_courses') as $value) {
                if (in_array($value, ['id','created_at','updated_at','deleted_at'])) continue;
                if ($req->has($value)) {
                 if ($value == 'latlng' && $req->post($value) != null) {
                        $data[$value] = serialize($req->post($value));
                    }else{
                        $data[$value] = $req->input($value);
                    }   
                }
            }
            if(Course::find(Crypt::decrypt($req->segment(3)))->update($data)){
                return json_encode(["msg"=>"Course Updated.", "color"=>"success", "icon"=>"check-circle"]);
            }
            return abort("403", json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
        }else{
            $data = [];
            // $data['ulid'] = Str::ulid();
            // return ;
            foreach (Schema::getColumnListing('institution_courses') as $value) {
                if (in_array($value, ['id','created_at','updated_at','deleted_at'])) continue;
                if ($req->has($value)) {
                    if ($value == 'latlng' && $req->post($value) != null) {
                        $data[$value] = serialize($req->post($value));
                    }else{
                        $data[$value] = $req->input($value);
                    }
                }
            }
            // return;

            $course = Course::create($data); // uses casts to encrypt
            if($course){
                return json_encode(["msg"=>"Course Created.", "color"=>"success", "icon"=>"check-circle"]);
            }
            return abort("403", json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
        }
    }
    function delete(Request $req){
        try {
            if ($id = Crypt::decrypt($req->segment(3))) {
                Course
                ::findOrFail($id)->delete();
                return json_encode(["msg"=>"Course Deleted.", "color"=>"success", "icon"=>"check-circle"]);
            }
        } catch (\Throwable $th) {
            return abort(401);
        }
    }
}
