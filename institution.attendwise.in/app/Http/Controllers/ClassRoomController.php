<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\ClassRoom;
use App\Models\ClassRoomType;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;

class ClassRoomController extends Controller
{
    function index(Request $req){
        
        $data = [
            "classrooms"=>ClassRoom::all(),
            // "adminal_id"=>,
            "title"=>"Manage Class Rooms"
        ];
        return view("classroom.index", $data);
    }
    function formView(Request $req){
        if ($req->segment(3)) {
            $details = ClassRoom::find(Crypt::decrypt($req->segment(3)));
            if (!$details) {
                return abort(404,"Page Not Found");
            }
            $fields = $details;
            if (!$fields->latlng && $fields->latitude && $fields->longitude) {
                $fields->latlng = json_encode([[(float)$fields->latitude, (float)$fields->longitude]]);
            }
            $data = [
                "title"=>"Edit Class Room",
                "type"=>"EDIT",
                "action"=>route("institution.class.room.update", ["id"=>$req->segment(3)]),
                "classroom"=>$fields,
                "blocks"=>Block::all(),
                "classroom_types"=>ClassRoomType::all(),
                "departments"=>Department::all(),
            ];
        }else{
            $fields = [];
            
            foreach (Schema::getColumnListing("institution_classrooms") as $value) {
                $fields[$value] = null;
            }
            $data = [
                "title"=>"Add Class Room",
                "type"=>"ADD",
                "action"=>route("institution.class.room.create"),
                "classroom"=>$fields,
                "blocks"=>Block::all(),
                "classroom_types"=>ClassRoomType::all(),
                "departments"=>Department::all(),
            ];
        }
        return view("classroom.form", $data);
    }
    function form(Request $req){
        if ($req->segment(3)) {
            $data = [];
            foreach (Schema::getColumnListing('institution_classrooms') as $value) {
                if (in_array($value, ['id','created_at','updated_at','deleted_at'])) continue;
                if ($req->has($value)) {
                    if ($value == 'latlng' && $req->post($value) != null) {
                        $points = json_decode($req->post($value), true);
                        $data[$value] = $points;
                        if (!empty($points) && isset($points[0][0]) && isset($points[0][1])) {
                            $data['latitude'] = $points[0][0];
                            $data['longitude'] = $points[0][1];
                        }
                    }else{
                        $data[$value] = $req->input($value);
                    }   
                }
            }
            if(ClassRoom::find(Crypt::decrypt($req->segment(3)))->update($data)){
                return json_encode(["msg"=>"Class Room Updated.", "color"=>"success", "icon"=>"check-circle"]);
            }
            return abort("403", json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
        }else{
            $data = [];
            // $data['ulid'] = Str::ulid();
            // return ;
            foreach (Schema::getColumnListing('institution_classrooms') as $value) {
                if (in_array($value, ['id','created_at','updated_at','deleted_at'])) continue;
                if ($req->has($value)) {
                    if ($value == 'latlng' && $req->post($value) != null) {
                        $points = json_decode($req->post($value), true);
                        $data[$value] = $points;
                        if (!empty($points) && isset($points[0][0]) && isset($points[0][1])) {
                            $data['latitude'] = $points[0][0];
                            $data['longitude'] = $points[0][1];
                        }
                    }else{
                        $data[$value] = $req->input($value);
                    }
                }
            }
            // return;

            $classroom = ClassRoom::create($data); // uses casts to encrypt
            if($classroom){
                return json_encode(["msg"=>"Class Room Created.", "color"=>"success", "icon"=>"check-circle"]);
            }
            return abort("403", json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
        }
    }
    function delete(Request $req){
        try {
            if ($id = Crypt::decrypt($req->segment(3))) {
                ClassRoom::findOrFail($id)->delete();
                return json_encode(["msg"=>"Class Room Deleted.", "color"=>"success", "icon"=>"check-circle"]);
            }
        } catch (\Throwable $th) {
            return abort(401);
        }
    }
}
