<?php

namespace App\Http\Controllers;

use App\Models\InstitutionAdmin;
use Illuminate\Http\Request;
use App\Models\InstitutionAdminPermission;
use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\InstitutionAdminGroup;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class InstitutionAdminController extends Controller
{
    function index(Request $req){
        $data = [
            "admins"=>InstitutionAdmin::where([
                "deleted_at"=>null,
            ])->get(),
            // "hospital_id"=>Crypt::decrypt(Session::get("hospital_id")),
            "title"=>"Manage Users"
        ];
        return view("institution-admin.index", $data);
    }
    function formView(Request $req){
        if ($req->segment(3)) {
            // return Crypt::decrypt($req->segment(3));
            $details = InstitutionAdmin::find(Crypt::decrypt($req->segment(3)));
            if (!$details) {
                return abort(404,"Page Not Found");
            }
            $data = [
                "title"=>"Edit User",
                "type"=>"EDIT",
                "action"=>route("admin.institution.users.update", ["id"=>$req->segment(3)]),
                "institution_list"=>Institution::where([
                    "deleted_at"=>null,
                    // "status"=>1,
                ])->get(),
                "group_list"=>InstitutionAdminGroup::where([
                    "deleted_at"=>null,
                    "institution_id"=>$details->institution_id,
                ])->get(),
                "fields"=>[
                    "name"=>$details->name,
                    "email"=>$details->email,
                    "mobile"=>$details->mobile,
                    "group_id"=>$details->group_id,
                    "password"=>($details->password),
                    "institution_id"=>$details->institution_id,
                    "status"=>$details->status,
                ],
            ];
        }else{
            $data = [
                "title"=>"Create User",
                "type"=>"ADD",
                "action"=>route("admin.institution.users.create"),
                "permissions"=>unserialize(InstitutionAdminGroup::find(Crypt::decrypt(Session::get("group_id")))->permissions),
                "institution_list"=>Institution::where([
                    "deleted_at"=>null,
                ])->get(),
                "group_list"=>InstitutionAdminGroup::where([
                    "deleted_at"=>null,
                    "institution_id"=>Institution::where([
                    "deleted_at"=>null,
                ])->first()->id,
                ])->get(),
                "fields"=>[
                    "name"=>"",
                    "email"=>"",
                    "mobile"=>"",
                    "password"=>"",
                    "group_id"=>"",
                    // "image"=>"",
                    "institution_id"=>"",
                ],
            ];
        }
        return view("institution-admin.form", $data);
    }
    function form(Request $req){
        if ($req->segment(3)) {
            $institutionAdmin = InstitutionAdmin::find(Crypt::decrypt($req->segment(3)));
            $institutionAdmin->name = $req->post("name");
            $institutionAdmin->email = $req->post("email");
            $institutionAdmin->mobile = $req->post("mobile");
            $institutionAdmin->password = ($req->post("password"));
            $institutionAdmin->admin_group_id = Crypt::decryptString($req->post("group_id"));
            // $institutionAdmin->status = $req->post("status");
            $institutionAdmin->institution_id = Crypt::decryptString($req->post("institution_id"));
            if($institutionAdmin->save()){
                return json_encode(["msg"=>"User Updated.", "color"=>"success", "icon"=>"check-circle"]);
            }
            return abort("403", json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
        }else{
            // echo Crypt::decryptString($req->group_id);
            if(InstitutionAdmin::create([
                "name"=>$req->post("name"),
                "email"=>$req->post("email"),
                "password"=>($req->post("password")),
                "mobile"=>$req->post("mobile"),
                "admin_group_id"=>Crypt::decryptString($req->post("group_id")),
                // "status"=>$req->post("status"),
                "institution_id"=>Crypt::decryptString($req->post("institution_id"))
            ])){
                return json_encode(["msg"=>"User Created.", "color"=>"success", "icon"=>"check-circle"]);
            }
            return abort("403", json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
        }
    }
    function delete(Request $req){
        try {
            if ($id = Crypt::decrypt($req->segment(3))) {
                InstitutionAdmin::where("id", $id)->delete();
                return json_encode(["msg"=>"User Deleted.", "color"=>"success", "icon"=>"check-circle"]);
            }
        } catch (\Throwable $th) {
            return abort(401);
        }
    }
}
