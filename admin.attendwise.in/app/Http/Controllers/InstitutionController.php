<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\Hospital;
use App\Models\HospitUser;
use App\Models\AdminGroup;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\Paginator as PaginationPaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class InstitutionController extends Controller
{
    function index(Request $req){
        
        $data = [
            "institutions"=>Institution::where([
                "deleted_at"=>null,
            ])->paginate(20),
            // "adminal_id"=>,
            "title"=>"Manage Institutions"
        ];
        return view("institution.index", $data);
    }
    function formView(Request $req){
        if ($req->segment(3)) {
            $details = Institution::find(Crypt::decrypt($req->segment(3)));
            if (!$details) {
                return abort(404,"Page Not Found");
            }
            $fields = $details;
            $data = [
                "title"=>"Edit Institution",
                "type"=>"EDIT",
                "action"=>route("admin.institution.update", ["id"=>$req->segment(3)]),
                "permissions"=>unserialize(AdminGroup::find(Crypt::decrypt(Session::get("group_id")))->permissions),
                "all_groups"=>AdminGroup::where([
                    "deleted_at"=>null,
                    "status"=>1,
                ])->get(),
                "institution"=>$fields,
            ];
        }else{
            $fields = [];
            
            foreach (Schema::getColumnListing("institutions") as $value) {
                $fields[$value] = null;
            }
            $data = [
                "title"=>"Add Institution",
                "type"=>"ADD",
                "action"=>route("admin.institution.create"),
                "permissions"=>unserialize(AdminGroup::find(Crypt::decrypt(Session::get("group_id")))->permissions),
                "institution"=>$fields,
            ];
        }
        return view("institution.form", $data);
    }
    function form(Request $req){
        if ($req->segment(3)) {
            $data = [];
            foreach (Schema::getColumnListing('institutions') as $value) {
                if (in_array($value, ['id','created_at','updated_at','deleted_at'])) continue;
                if ($req->has($value) && $req->post($value) != null) {
                    $data[$value] = $req->input($value);
                }else{
                    $data[$value] = null;
                }
            }
            if(Institution::find(Crypt::decrypt($req->segment(3)))->update($data)){
                return json_encode(["msg"=>"Institution Updated.", "color"=>"success", "icon"=>"check-circle"]);
            }
            return abort("403", json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
        }else{
            $data = [];
            $data['ulid'] = Str::ulid();

            foreach (Schema::getColumnListing('institutions') as $value) {
                if (in_array($value, ['id','created_at','updated_at','deleted_at'])) continue;
                if ($req->has($value)) {
                    $data[$value] = $req->input($value);
                }
            }

            $institution = Institution::create($data); // uses casts to encrypt
            if($institution){
                return json_encode(["msg"=>"Institution Created.", "color"=>"success", "icon"=>"check-circle"]);
            }
            return abort("403", json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
        }
    }
    function delete(Request $req){
        try {
            if ($id = Crypt::decrypt($req->segment(3))) {
                Institution::where("id", $id)->delete();
                return json_encode(["msg"=>"Institution Deleted.", "color"=>"success", "icon"=>"check-circle"]);
            }
        } catch (\Throwable $th) {
            return abort(401);
        }
    }
}
