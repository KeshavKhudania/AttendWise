<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;

class DepartmentController extends Controller
{
    function index(Request $req)
    {

        $data = [
            "departments" => Department::all(),
            // "adminal_id"=>,
            "title" => "Manage Departments"
        ];
        return view("department.index", $data);
    }
    function formView(Request $req)
    {
        if ($req->segment(3)) {
            $details = Department::find(Crypt::decrypt($req->segment(3)), ['*']);
            if (!$details) {
                return abort(404, "Page Not Found");
            }
            $fields = $details;
            $data = [
                "title" => "Edit Department",
                "type" => "EDIT",
                "action" => route("institution.departments.update", ["id" => $req->segment(3)]),
                "department" => $fields,
            ];
        }
        else {
            $fields = [];

            foreach (Schema::getColumnListing("institution_departments") as $value) {
                $fields[$value] = null;
            }
            $data = [
                "title" => "Add Department",
                "type" => "ADD",
                "action" => route("institution.departments.create"),
                "department" => $fields,
            ];
        }
        return view("department.form", $data);
    }
    function form(Request $req)
    {
        if ($req->segment(3)) {
            $data = [];
            foreach (Schema::getColumnListing('institution_departments') as $value) {
                if (in_array($value, ['id', 'created_at', 'updated_at', 'deleted_at']))
                    continue;
                if ($req->has($value)) {
                    if ($value == 'latlng' && $req->post($value) != null) {
                        $data[$value] = serialize($req->post($value));
                    }
                    else {
                        $data[$value] = $req->input($value);
                    }
                }
                else if ($value == 'is_additional') {
                    $data[$value] = 0;
                }
            }
            if (Department::find(Crypt::decrypt($req->segment(3)), ['*'])->update($data)) {
                return json_encode(["msg" => "Department Updated.", "color" => "success", "icon" => "check-circle"]);
            }
            return abort("403", json_encode(["msg" => "Something went wrong.", "color" => "danger", "icon" => "exclamation-circle"]));
        }
        else {
            $data = [];
            // $data['ulid'] = Str::ulid();
            // return ;
            foreach (Schema::getColumnListing('institution_departments') as $value) {
                if (in_array($value, ['id', 'created_at', 'updated_at', 'deleted_at']))
                    continue;
                if ($req->has($value)) {
                    $data[$value] = $req->input($value);
                }
                else if ($value == 'is_additional') {
                    $data[$value] = 0;
                }
            }
            // return;

            $department = Department::create($data); // uses casts to encrypt
            if ($department) {
                return json_encode(["msg" => "Department Created.", "color" => "success", "icon" => "check-circle"]);
            }
            return abort("403", json_encode(["msg" => "Something went wrong.", "color" => "danger", "icon" => "exclamation-circle"]));
        }
    }
    function delete(Request $req)
    {
        try {
            if ($id = Crypt::decrypt($req->segment(3))) {
                Department::findOrFail($id)->delete();
                return json_encode(["msg" => "Department Deleted.", "color" => "success", "icon" => "check-circle"]);
            }
        }
        catch (\Throwable $th) {
            return abort(401);
        }
    }
}