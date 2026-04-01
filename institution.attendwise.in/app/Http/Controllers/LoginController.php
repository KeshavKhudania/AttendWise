<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    function index(Request $req){
        if (Session::has("user_id")) {
            return redirect()->to(route("dashboard_view"));
        }
        $data = [];
        return view("auth.login", $data);
    }
    function loginCheck(Request $req){
        try {
            $email = strip_tags($req->post("email"));
            $password = strip_tags($req->post("password"));
            // $oid = strip_tags($req->post("oid"));
            $keep_signed_in_status = $req->keep_signed_in;
            $user = AdminUser::whereEncryptedEmail($email);
            if ($user->count() == 0 ) {
                return json_encode(["msg"=>"Incorrect Email ID.", "color"=>"danger", "icon"=>"exclamation-circle"]);
            }
            $user = AdminUser::find($user->first()->id);
            if (($user->password) == $password) {
                Session::put("institution_id", Crypt::encrypt($user->institution_id));
                Session::put("group_id", Crypt::encrypt($user->admin_group_id));
                Session::put("user_id", Crypt::encrypt($user->id));
                return json_encode(["msg"=>"Logged in.", "color"=>"success", "icon"=>"check-circle", "redirect"=>route("dashboard_view")]);
            }
            return json_encode(["msg"=>"Password not matched.", "color"=>"danger", "icon"=>"exclamation-circle"]);
        } catch (\Throwable $th) {
            return abort("403", json_encode(["msg"=>"Something went wrong $th.", "color"=>"danger", "icon"=>"exclamation-circle"]));
            // return $th;
            // return Crypt::encrypt("keshav");
        }
    }
    function logout(){
        Session::flush();
        return redirect(route("login_view"));
    }
}
