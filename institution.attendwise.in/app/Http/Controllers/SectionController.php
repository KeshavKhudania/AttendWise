<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;

class SectionController extends Controller
{
    function index(Request $req)
    {

        $data = [
            "sections" => Section::all(),
            // "adminal_id"=>,
            "title" => "Manage Sections"
        ];
        return view("section.index", $data);
    }
    function formView(Request $req)
    {
        if ($req->segment(3)) {
            $details = Section::with('additionalDepartments')->find(Crypt::decrypt($req->segment(3)));
            if (!$details) {
                return abort(404, "Page Not Found");
            }
            $fields = $details;
            $data = [
                "title" => "Edit Section",
                "type" => "EDIT",
                "action" => route("institution.section.update", ["id" => $req->segment(3)]),
                "section" => $fields,
                "courses" => Course::all(),
                "departments" => Department::where('is_additional', 0)->get(),
                "additional_departments" => Department::where('is_additional', 1)->get(),
                "selected_additional_departments" => $fields->additionalDepartments->pluck('id')->toArray(),
            ];
        }
        else {
            $fields = [];

            foreach (Schema::getColumnListing("institution_sections") as $value) {
                $fields[$value] = null;
            }
            $data = [
                "title" => "Add Section",
                "type" => "ADD",
                "action" => route("institution.section.create"),
                "section" => (object)$fields,
                "courses" => Course::all(),
                "departments" => Department::where('is_additional', 0)->get(),
                "additional_departments" => Department::where('is_additional', 1)->get(),
                "selected_additional_departments" => [],
            ];
        }
        return view("section.form", $data);
    }
    function form(Request $req)
    {
        if ($req->segment(3)) {
            $data = [];
            foreach (Schema::getColumnListing('institution_sections') as $value) {
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
            }
            $section = Section::find(Crypt::decrypt($req->segment(3)));
            if ($section->update($data)) {
                $section->additionalDepartments()->sync($req->input('additional_departments', []));
                return json_encode(["msg" => "Section Updated.", "color" => "success", "icon" => "check-circle"]);
            }
            return abort("403", json_encode(["msg" => "Something went wrong.", "color" => "danger", "icon" => "exclamation-circle"]));
        }
        else {
            $data = [];
            // $data['ulid'] = Str::ulid();
            // return ;
            foreach (Schema::getColumnListing('institution_sections') as $value) {
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
            }
            // return;

            $section = Section::create($data); // uses casts to encrypt
            if ($section) {
                $section->additionalDepartments()->sync($req->input('additional_departments', []));
                return json_encode(["msg" => "Section Created.", "color" => "success", "icon" => "check-circle"]);
            }
            return abort("403", json_encode(["msg" => "Something went wrong.", "color" => "danger", "icon" => "exclamation-circle"]));
        }
    }
    function delete(Request $req)
    {
        try {
            if ($id = Crypt::decrypt($req->segment(3))) {
                Section::findOrFail($id)->delete();
                return json_encode(["msg" => "Section Deleted.", "color" => "success", "icon" => "check-circle"]);
            }
        }
        catch (\Throwable $th) {
            return abort(401);
        }
    }
    function fetchSectionByCourse(Request $req)
    {
        $course_id = $req->post("data")['course_id'];
        $data = Section::where('course_id', $course_id)->get();
        // print_r($data);
        return response()->json([
            "status" => "success",
            "data" => $data,
            "msg" => "Sections fetched successfully.",
            "color" => "success",
            "icon" => "check-circle",
        ])->getContent();
    }
}