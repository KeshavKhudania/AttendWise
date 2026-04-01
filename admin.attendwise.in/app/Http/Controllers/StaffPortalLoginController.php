<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class StaffPortalLoginController extends Controller
{
    public function index(){
        $data = [];
        return view("auth.login", $data);
    }
    public function Login(Request $req){
        try {
            $email = strip_tags($req->post("email"));
            $password = strip_tags($req->post("password"));
            $oid = strip_tags($req->post("oid"));
            $keep_signed_in_status = $req->keep_signed_in;
            $query = Hospital::where(["deleted_at"=>null, "status"=>"1", "oid"=>$oid]);
            if(!$query->count() > 0){
                return json_encode(["msg"=>"Incorrect OID.", "color"=>"danger", "icon"=>"exclamation-circle"]);
            }
            $user = Staff::where(["deleted_at"=>null, "is_active"=>"1", "hospital_id"=>$query->first()->id, "email"=>$email]);
            if (!$user->count() > 0) {
                return json_encode(["msg"=>"Incorrect Email ID.", "color"=>"danger", "icon"=>"exclamation-circle"]);
            }
            if (Crypt::decrypt($user->first()->password) == $password) {
                Session::put("hospital_id", Crypt::encrypt($user->first()->hospital_id));
                Session::put("group_id", Crypt::encrypt($user->first()->role));
                Session::put("user_id", Crypt::encrypt($user->first()->id));
                return json_encode(["msg"=>"Logged in.", "color"=>"success", "icon"=>"check-circle", "redirect"=>route("dashboard.view")]);
            }
            return json_encode(["msg"=>"Password not matched.", "color"=>"danger", "icon"=>"exclamation-circle"]);
        } catch (\Throwable $th) {
            throw $th;
            // return abort("403", json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
        }
    }
    public function Logout(Request $req){
        Session::flush();
        return redirect()->route("login.view");
    }
}
