<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\Department;
use App\Models\HospitGroup;
use Illuminate\Http\Request;
use App\Models\Qualification;
use App\Models\AnesthesiaType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\StaffPortalHelper;

class AnesthesiaTypeController extends Controller
{
    function index(Request $req){
        $data = [
            "users"=>AnesthesiaType::where([
                "deleted_at"=>null,
                "hospital_id"=>Crypt::decrypt(Session::get("hospital_id")),
            ])->get(),
            "title"=>"Manage Anesthesia Types"
        ];
        return view("anesthesia.types.index", $data);
    }
    function formView(Request $req){
        if ($req->segment(3)) {
            $details = AnesthesiaType::find(Crypt::decrypt($req->segment(3)));
            if (!$details) {
                return abort(404,"Page Not Found");
            }
            $fields = [];
            foreach (Schema::getColumnListing("anesthesia_types") as $value) {
                $fields[$value] = $details->$value;
            }
            $data = [
                "title"=>"Edit Anesthesia Type",
                "type"=>"EDIT",
                "action"=>route("hospit.anesthesia.types.update", ["id"=>$req->segment(3)]),
                "permissions"=>unserialize(HospitGroup::find(Crypt::decrypt(Session::get("group_id")))->permissions),
                "all_groups"=>HospitGroup::where([
                    "deleted_at"=>null,
                    "status"=>1,
                ])->get(),
                "qualifications"=>Qualification::where([
                    "hospital_id"=>Crypt::decrypt(Session::get("hospital_id")),
                    "deleted_at"=>null,
                    "status"=>1
                ])->get(),
                "departments"=>Department::where([
                    "hospital_id"=>Crypt::decrypt(Session::get("hospital_id")),
                    "deleted_at"=>null,
                    "status"=>1
                ])->get(),

                "fields"=>$fields,
            ];
        }else{
            $fields = [];
            foreach (Schema::getColumnListing("anesthesia_types") as $value) {
                $fields[$value] = null;
            }
            $data = [
                "title"=>"Add Anesthesia Type",
                "type"=>"ADD",
                "action"=>route("hospit.anesthesia.types.create"),
                "permissions"=>unserialize(HospitGroup::find(Crypt::decrypt(Session::get("group_id")))->permissions),
                "fields"=>$fields,
                "qualifications"=>Qualification::where([
                    "hospital_id"=>Crypt::decrypt(Session::get("hospital_id")),
                    "deleted_at"=>null,
                    "status"=>1
                ])->get(),
                "departments"=>Department::where([
                    "hospital_id"=>Crypt::decrypt(Session::get("hospital_id")),
                    "deleted_at"=>null,
                    "status"=>1
                ])->get(),

            ];
        }
        return view("anesthesia.types.form", $data);
    }
    function form(Request $req){
        if ($req->segment(3)) {
            $hospital = Hospital::find(Crypt::decrypt(Session::get("hospital_id")));
            if(AnesthesiaType::where(["id"=>Crypt::decrypt($req->segment(3))])->update([
                    "name"=>$req->name,
                    "status"=>$req->status,
                    "description"=>$req->description,
            ])){
                return json_encode(["msg"=>"Anesthesia Type Updated.", "color"=>"success", "icon"=>"check-circle"]);
            }
            return abort("403", json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
        }else{
            $hospital = Hospital::find(Crypt::decrypt(Session::get("hospital_id")));
            if(AnesthesiaType::insert([
                "name"=>$req->name,
                    "status"=>$req->status,
                    "description"=>$req->description,
                    "hospital_id"=>StaffPortalHelper::getHospitalId(),
            ])){
                return json_encode(["msg"=>"Anesthesia Type Created.", "color"=>"success", "icon"=>"check-circle"]);
            }
            return abort("403", json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
        }
    }
    function delete(Request $req){
        try {
            if ($id = Crypt::decrypt($req->segment(3))) {
                AnesthesiaType::where("id", $id)->delete();
                return json_encode(["msg"=>"Anesthesia Type Deleted.", "color"=>"success", "icon"=>"check-circle"]);
            }
        } catch (\Throwable $th) {
            return abort(401);
        }
    }
}
