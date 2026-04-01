<?php

namespace App\Http\Controllers;

use App\Models\AdminGroup;
use App\Models\AdminPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class AdminGroupController extends Controller
{
    function index(Request $req){
        $data = [
            "users"=>AdminGroup::where([
                "deleted_at"=>null,
            ])->get(),
            // "hospital_id"=>Crypt::decrypt(Session::get("hospital_id")),
            "title"=>"Manage Groups"
        ];
        return view("group.index", $data);
    }
    function formView(Request $req){
        if ($req->segment(4)) {
            $details = AdminGroup::find(Crypt::decrypt($req->segment(4)));
            if (!$details) {
                return abort(404,"Page Not Found");
            }
            $all_permissions = AdminPermission::where([
        'deleted_at' => null,
        'status'     => 1,
        'action'     => 'R',
        'parent_id'  => 0
    ])
    ->with(['children.children' => function($query) {
        $query->where('deleted_at', null)
              ->where('status', 1);
    }])
    ->get()
    ->map(function($permission) {
        // Group children by feature base name
        $grouped = $permission->children->groupBy(function($item) {
            // Extract feature name (customize regex if your naming differs)
            return preg_replace('/ (Create|Read|Update|Delete|List|View)$/', '', $item->name);
        });

        $childs = [];
        foreach ($grouped as $feature => $perms) {
            // CRUD actions for this group
            $childArr = [
                'R' => $perms->where('action', 'R')->values()->toArray(),
                'C' => $perms->where('action', 'C')->values()->toArray(),
                'U' => $perms->where('action', 'U')->values()->toArray(),
                'D' => $perms->where('action', 'D')->values()->toArray(),
            ];

            // Collect sub_childs
            $sub_childs = [];
            foreach ($perms as $perm) {
                foreach ($perm->children as $subchild) {
                    $sub_childs[$subchild->name] = $subchild->toArray();
                }
            }
            if (!empty($sub_childs)) {
                $childArr['sub_childs'] = $sub_childs;
            }

            $childs[$feature] = $childArr;
        }

        $perm = $permission->toArray();
        $perm['childs'] = $childs;
        return $perm;
    });


            $data = [
                "title"=>"Edit Group",
                "type"=>"EDIT",
                "action"=>route("admin.users.group.update", ["id"=>$req->segment(4)]),
                "fields"=>[
                    "name"=>$details->name,
                    "permissions"=>unserialize($details->permissions),
                    "status"=>unserialize($details->permissions),
                ],
                "all_permissions"=>$all_permissions,
            ];
        }else{
           $all_permissions = AdminPermission::where([
        'deleted_at' => null,
        'status'     => 1,
        'action'     => 'R',
        'parent_id'  => 0
    ])
    ->with(['children.children' => function($query) {
        $query->where('deleted_at', null)
              ->where('status', 1);
    }])
    ->get()
    ->map(function($permission) {
        // Group children by feature base name
        $grouped = $permission->children->groupBy(function($item) {
            // Extract feature name (customize regex if your naming differs)
            return preg_replace('/ (Create|Read|Update|Delete|List|View)$/', '', $item->name);
        });

        $childs = [];
        foreach ($grouped as $feature => $perms) {
            // CRUD actions for this group
            $childArr = [
                'R' => $perms->where('action', 'R')->values()->toArray(),
                'C' => $perms->where('action', 'C')->values()->toArray(),
                'U' => $perms->where('action', 'U')->values()->toArray(),
                'D' => $perms->where('action', 'D')->values()->toArray(),
            ];

            // Collect sub_childs
            $sub_childs = [];
            foreach ($perms as $perm) {
                foreach ($perm->children as $subchild) {
                    $sub_childs[$subchild->name] = $subchild->toArray();
                }
            }
            if (!empty($sub_childs)) {
                $childArr['sub_childs'] = $sub_childs;
            }

            $childs[$feature] = $childArr;
        }

        $perm = $permission->toArray();
        $perm['childs'] = $childs;
        return $perm;
    });



            $data = [
                "title"=>"Create Group",
                "type"=>"ADD",
                "action"=>route("admin.users.group.create"),
                "fields"=>[
                    "name"=>"",
                    "permissions"=>[],
                    "status"=>"1",
                ],
                "all_permissions"=>$all_permissions,
            ];
        }
        return view("group.form", $data);
    }
    function form(Request $req){
        if (null === ($req->post("allowed_permissions"))) {
            return abort("403", json_encode(["msg"=>"Please select permissions.", "color"=>"danger", "icon"=>"exclamation-circle"]));
        }
        if ($req->segment(4)) {
            $data = [];
            for ($i=0; $i < count($req->post("allowed_permissions")); $i++) { 
                $perm = AdminPermission::find(Crypt::decrypt($req->post("allowed_permissions")[$i]));
                $data[] = $perm->route_name;
                // echo $perm->route_name."<br>";
                if ($perm->action == "C") {
                    $cperm = AdminPermission::where([
                        "deleted_at"=>null,
                        "status"=>1,
                        "action"=>"C",
                        "method"=>"POST",
                        "name"=>$perm->name,
                        ])->first();
                        $data[] = $cperm->route_name;
                    }
                if ($perm->action == "U") {
                    $cperm = AdminPermission::where([
                        "deleted_at"=>null,
                        "status"=>1,
                        "action"=>"U",
                        "method"=>"POST",
                        "name"=>$perm->name,
                    ])->first();
                    $data[] = $cperm->route_name;
                }
            }
            if(AdminGroup::where(["id"=>Crypt::decrypt($req->segment(4))])->update([
                "name"=>$req->post("name"),
                "status"=>$req->post("status"),
                "permissions"=>serialize($data),
            ])){
                return json_encode(["msg"=>"Group Updated.", "color"=>"success", "icon"=>"check-circle"]);
            }
            return abort("403", json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
        }else{
            $data = [];
            for ($i=0; $i < count($req->post("allowed_permissions")); $i++) { 
                $perm = AdminPermission::find(Crypt::decrypt($req->post("allowed_permissions")[$i]));
                $data[] = $perm->route_name;
                if ($perm->action == "C") {
                    $cperm = AdminPermission::where([
                        "deleted_at"=>null,
                        "status"=>1,
                        "action"=>"C",
                        "method"=>"POST",
                        "name"=>$perm->name,
                    ])->first();
                    $data[] = $cperm->route_name;
                }
                if ($perm->action == "U") {
                    $cperm = AdminPermission::where([
                        "deleted_at"=>null,
                        "status"=>1,
                        "action"=>"U",
                        "method"=>"POST",
                        "name"=>$perm->name,
                    ])->first();
                    $data[] = $cperm->route_name;
                }
            }
            if(AdminGroup::insert([
                "name"=>$req->post("name"),
                "status"=>$req->post("status"),
                "permissions"=>serialize($data),
                "hospital_id"=>Crypt::decrypt(Session::get("hospital_id")),
            ])){
                return json_encode(["msg"=>"Group Created.", "color"=>"success", "icon"=>"check-circle"]);
            }
            return abort("403", json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
        }
    }
    function delete(Request $req){
        try {
            if ($id = Crypt::decrypt($req->segment(4))) {
                AdminGroup::where("id", $id)->delete();
                return json_encode(["msg"=>"Group Deleted.", "color"=>"success", "icon"=>"check-circle"]);
            }
        } catch (\Throwable $th) {
            return abort(401);
        }
    }
}
