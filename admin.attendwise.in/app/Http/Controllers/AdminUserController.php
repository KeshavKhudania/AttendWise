<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use Illuminate\Http\Request;
use App\Models\AdminPermission;
use App\Http\Controllers\Controller;
use App\Models\AdminGroup;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class AdminUserController extends Controller
{
    function index(Request $req){
        $data = [
            "users"=>AdminUser::where([
                "deleted_at"=>null,
            ])->get(),
            // "hospital_id"=>Crypt::decrypt(Session::get("hospital_id")),
            "title"=>"Manage Users"
        ];
        return view("users.index", $data);
    }
    function formView(Request $req){
        if ($req->segment(3)) {
            $details = AdminUser::find(Crypt::decrypt($req->segment(3)));
            if (!$details) {
                return abort(404,"Page Not Found");
            }
            $data = [
                "title"=>"Edit User",
                "type"=>"EDIT",
                "action"=>route("hospit.users.update", ["id"=>$req->segment(3)]),
                "permissions"=>unserialize(AdminGroup::find(Crypt::decrypt(Session::get("group_id")))->permissions),
                "all_groups"=>AdminGroup::where([
                    "deleted_at"=>null,
                    "status"=>1,
                ])->get(),
                "fields"=>[
                    "name"=>$details->name,
                    "email"=>$details->email,
                    "mobile"=>$details->mobile,
                    "group_id"=>$details->group_id,
                    "password"=>Crypt::decrypt($details->password),
                    // "image"=>$details->image,
                    "status"=>$details->status,
                ],
            ];
        }else{
            $data = [
                "title"=>"Create User",
                "type"=>"ADD",
                "action"=>route("hospit.users.create"),
                "permissions"=>unserialize(AdminGroup::find(Crypt::decrypt(Session::get("group_id")))->permissions),
                "all_groups"=>AdminGroup::where([
                    "deleted_at"=>null, 
                    "status"=>"1",
                ])->get(),
                "fields"=>[
                    "name"=>"",
                    "email"=>"",
                    "mobile"=>"",
                    "password"=>"",
                    "group_id"=>"",
                    // "image"=>"",
                    "status"=>"1",
                ],
            ];
        }
        return view("users.form", $data);
    }
    function form(Request $req){
        if ($req->segment(3)) {
            if(AdminUser::where(["id"=>Crypt::decrypt($req->segment(3))])->update([
                "name"=>$req->post("name"),
                "email"=>$req->post("email"),
                "mobile"=>$req->post("mobile"),
                "password"=>Crypt::encrypt($req->post("password")),
                "group_id"=>Crypt::decrypt($req->post("group_id")),
                "status"=>$req->post("status"),
            ])){
                return json_encode(["msg"=>"User Updated.", "color"=>"success", "icon"=>"check-circle"]);
            }
            return abort("403", json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
        }else{
            if(AdminUser::insert([
                "name"=>$req->post("name"),
                "email"=>$req->post("email"),
                "password"=>Crypt::encrypt($req->post("password")),
                "mobile"=>$req->post("mobile"),
                "group_id"=>Crypt::decrypt($req->post("group_id")),
                "status"=>$req->post("status"),
                "hospital_id"=>Crypt::decrypt(Session::get("hospital_id"))
            ])){
                return json_encode(["msg"=>"User Created.", "color"=>"success", "icon"=>"check-circle"]);
            }
            return abort("403", json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
        }
    }
    function delete(Request $req){
        try {
            if ($id = Crypt::decrypt($req->segment(3))) {
                AdminUser::where("id", $id)->delete();
                return json_encode(["msg"=>"User Deleted.", "color"=>"success", "icon"=>"check-circle"]);
            }
        } catch (\Throwable $th) {
            return abort(401);
        }
    }
}
