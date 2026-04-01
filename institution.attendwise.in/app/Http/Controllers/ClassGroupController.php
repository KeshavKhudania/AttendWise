<?php

namespace App\Http\Controllers;

use App\Models\ClassGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;

class ClassGroupController extends Controller
{
    function index(Request $req){
        
        $data = [
            "class_groups"=>ClassGroup::all(),
            // "adminal_id"=>,
            "title"=>"Manage ClassGroups"
        ];
        return view("class_group.index", $data);
    }
    function formView(Request $req){
        if ($req->segment(3)) {
            $details = ClassGroup::find(Crypt::decrypt($req->segment(3)));
            if (!$details) {
                return abort(404,"Page Not Found");
            }
            $fields = $details;
            $data = [
                "title"=>"Edit ClassGroup",
                "type"=>"EDIT",
                "action"=>route("institution.class.group.update", ["id"=>$req->segment(3)]),
                "class_group"=>$fields,
            ];
        }else{
            $fields = [];
            
            foreach (Schema::getColumnListing("institution_class_groups") as $value) {
                $fields[$value] = null;
            }
            $data = [
                "title"=>"Add ClassGroup",
                "type"=>"ADD",
                "action"=>route("institution.class.group.create"),
                "class_group"=>$fields,
            ];
        }
        return view("class_group.form", $data);
    }
    function form(Request $req){
        if ($req->segment(3)) {
            $data = [];
            foreach (Schema::getColumnListing('institution_class_groups') as $value) {
                if (in_array($value, ['id','created_at','updated_at','deleted_at'])) continue;
                if ($req->has($value)) {
                 if ($value == 'latlng' && $req->post($value) != null) {
                        $data[$value] = serialize($req->post($value));
                    }else{
                        $data[$value] = $req->input($value);
                    }   
                }
            }
            if(ClassGroup::find(Crypt::decrypt($req->segment(3)))->update($data)){
                return json_encode(["msg"=>"Class Group Updated.", "color"=>"success", "icon"=>"check-circle"]);
            }
            return abort("403", json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
        }else{
            $data = [];
            // $data['ulid'] = Str::ulid();
            // return ;
            foreach (Schema::getColumnListing('institution_class_groups') as $value) {
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

            $class_group = ClassGroup::create($data); // uses casts to encrypt
            if($class_group){
                return json_encode(["msg"=>"Class Group Created.", "color"=>"success", "icon"=>"check-circle"]);
            }
            return abort("403", json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
        }
    }
    function delete(Request $req){
        try {
            if ($id = Crypt::decrypt($req->segment(3))) {
                ClassGroup::findOrFail($id)->delete();
                return json_encode(["msg"=>"Class Group Deleted.", "color"=>"success", "icon"=>"check-circle"]);
            }
        } catch (\Throwable $th) {
            return abort(401);
        }
    }
}
