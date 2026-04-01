<?php

namespace App\Http\Controllers;

use App\Models\AdminGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\adminal;
use App\Models\AdminUser;
use App\Models\InstitutionAdminPermission;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Picqer\Barcode\BarcodeGeneratorPNG;

class HomeController extends Controller
{
    function index(Request $req){
        $data = [
            "kpis"=>[
                [
                    "name"=>"Institutions",
                    "count"=>DB::table("institutions")->where([
                        // "deleted_at"=>null,
                    ])->count(),
                    "icon"=>"fas fa-unversity",
                    "color"=>"success",
                    "route"=>"admin.institution.manage",
                ],
                [
                    "name"=>"Admin Groups",
                    "count"=>DB::table("admin_groups")->where([
                        "deleted_at"=>null,
                    ])->count(),
                    "icon"=>"fas fa-users-cog",
                    "color"=>"warning",
                    "route"=>"admin.users.manage",
                ],
                [
                    "name"=>"Admin Users",
                    "count"=>DB::table("admin_users")->where([
                        "deleted_at"=>null,
                    ])->count(),
                    "icon"=>"fas fa-users",
                    "color"=>"danger",
                    "route"=>"admin.users.manage",
                ],
            //     [
            //         "name"=>"Patients",
            //         "count"=>DB::table("patients")->where([
            //             "deleted_at"=>null,
            //             "adminal_id"=>Crypt::decrypt(Session::get("adminal_id")),
            //         ])->count(),
            //         "icon"=>"fas fa-user-injured",
            //         "color"=>"danger",
            //         "route"=>"admin.patient.manage",
            //     ],
            //     [
            //         "name"=>"Diagnosis Tests",
            //         "count"=>DB::table("tests")->where([
            //             "deleted_at"=>null,
            //             "adminal_id"=>Crypt::decrypt(Session::get("adminal_id")),
            //         ])->count(),
            //         "icon"=>"fas fa-vial",
            //         "color"=>"primary",
            //         "route"=>"admin.test.manage",
            //     ],
            //     [
            //         "name"=>"Insurance Providers",
            //         "count"=>DB::table("insurance_providers")->where([
            //             "deleted_at"=>null,
            //             "adminal_id"=>Crypt::decrypt(Session::get("adminal_id")),
            //         ])->count(),
            //         "icon"=>"fas fa-file-medical",
            //         "color"=>"info",
            //         "route"=>"admin.insurance.provider.manage",
            //     ],
            //     [
            //         "name"=>"User Groups",
            //         "count"=>DB::table("admin_groups")->where([
            //             "deleted_at"=>null,
            //             "adminal_id"=>Crypt::decrypt(Session::get("adminal_id")),
            //         ])->count(),
            //         "icon"=>"fas fa-object-group",
            //         "color"=>"success",
            //         "route"=>"admin.users.group.manage",
            //     ],
            //     [
            //         "name"=>"Users",
            //         "count"=>DB::table("admin_users")->where([
            //             "deleted_at"=>null,
            //             "adminal_id"=>Crypt::decrypt(Session::get("adminal_id")),
            //         ])->count(),
            //         "icon"=>"fas fa-users",
            //         "color"=>"primary",
            //         "route"=>"admin.users.manage",
            //     ],
            // ],
            // "charts"=>[
            //     [
            //         "name"=>"Patients Month by Month",
            //         "route"=>"admin.patient.manage",
            //         "data"=>[
            //             "Jan"=>DB::table("patients")->where([
            //                 "deleted_at"=>null,
            //                 "adminal_id"=>Crypt::decrypt(Session::get("adminal_id")),
            //                 "status"=>1,
            //             ])->whereBetween("created_at", [date("Y-")."1-1 00:00:00", date("Y")."-1-31 23:59:59"])->count(),
            //             "Feb"=>DB::table("patients")->where([
            //                 "deleted_at"=>null,
            //                 "adminal_id"=>Crypt::decrypt(Session::get("adminal_id")),
            //                 "status"=>1,
            //             ])->whereBetween("created_at", [date("Y-")."2-1 00:00:00", date("Y")."-2-".cal_days_in_month(CAL_GREGORIAN, 2, date("Y"))." 23:59:59"])->count(),
            //             "Mar"=>DB::table("patients")->where([
            //                 "deleted_at"=>null,
            //                 "adminal_id"=>Crypt::decrypt(Session::get("adminal_id")),
            //                 "status"=>1,
            //             ])->whereBetween("created_at", [date("Y-")."3-1 00:00:00", date("Y")."-3-31 23:59:59"])->count(),
            //             "Apr"=>DB::table("patients")->where([
            //                 "deleted_at"=>null,
            //                 "adminal_id"=>Crypt::decrypt(Session::get("adminal_id")),
            //                 "status"=>1,
            //             ])->whereBetween("created_at", [date("Y-")."4-1 00:00:00", date("Y")."-4-30 23:59:59"])->count(),
            //             "May"=>DB::table("patients")->where([
            //                 "deleted_at"=>null,
            //                 "adminal_id"=>Crypt::decrypt(Session::get("adminal_id")),
            //                 "status"=>1,
            //             ])->whereBetween("created_at", [date("Y-")."5-1 00:00:00", date("Y")."-5-31 23:59:59"])->count(),
            //             "Jun"=>DB::table("patients")->where([
            //                 "deleted_at"=>null,
            //                 "adminal_id"=>Crypt::decrypt(Session::get("adminal_id")),
            //                 "status"=>1,
            //             ])->whereBetween("created_at", [date("Y-")."6-1 00:00:00", date("Y")."-6-30 23:59:59"])->count(),
            //             "Jul"=>DB::table("patients")->where([
            //                 "deleted_at"=>null,
            //                 "adminal_id"=>Crypt::decrypt(Session::get("adminal_id")),
            //                 "status"=>1,
            //             ])->whereBetween("created_at", [date("Y-")."7-1 00:00:00", date("Y")."-7-31 23:59:59"])->count(),
            //             "Aug"=>DB::table("patients")->where([
            //                 "deleted_at"=>null,
            //                 "adminal_id"=>Crypt::decrypt(Session::get("adminal_id")),
            //                 "status"=>1,
            //             ])->whereBetween("created_at", [date("Y-")."8-1 00:00:00", date("Y")."-8-31 23:59:59"])->count(),
            //             "Sep"=>DB::table("patients")->where([
            //                 "deleted_at"=>null,
            //                 "adminal_id"=>Crypt::decrypt(Session::get("adminal_id")),
            //                 "status"=>1,
            //             ])->whereBetween("created_at", [date("Y-")."9-1 00:00:00", date("Y")."-9-30 23:59:59"])->count(),
            //             "Oct"=>DB::table("patients")->where([
            //                 "deleted_at"=>null,
            //                 "adminal_id"=>Crypt::decrypt(Session::get("adminal_id")),
            //                 "status"=>1,
            //             ])->whereBetween("created_at", [date("Y-")."10-1 00:00:00", date("Y")."-10-31 23:59:59"])->count(),
            //             "Nov"=>DB::table("patients")->where([
            //                 "deleted_at"=>null,
            //                 "adminal_id"=>Crypt::decrypt(Session::get("adminal_id")),
            //                 "status"=>1,
            //             ])->whereBetween("created_at", [date("Y-")."11-1 00:00:00", date("Y")."-11-30 23:59:59"])->count(),
            //             "Dec"=>DB::table("patients")->where([
            //                 "deleted_at"=>null,
            //                 "adminal_id"=>Crypt::decrypt(Session::get("adminal_id")),
            //                 "status"=>1,
            //             ])->whereBetween("created_at", [date("Y-")."12-1 00:00:00", date("Y")."-12-31 23:59:59"])->count(),
            //         ],
            //     ],
            ],
            "allowed_permissions"=>unserialize(AdminGroup::find(Crypt::decrypt(Session::get("group_id")))->permissions),
        ];
        return view("dashboard", $data);
    }
    function addPerms($perm_name = null, $icon = null, $sort_order = 0, $perm_parent = 0, $perm_type = "child"){
        if ($perm_name !== null) {
            $perms = [
                [
                    "name"=>$perm_name,
                    "route_name"=>"admin.".str_replace(" ",".",strtolower($perm_name)).".manage",
                    "action"=>"R",
                    "icon"=>$icon,
                    "sort_order"=>$sort_order,
                    "method"=>"GET",
                    "parent_id"=>$perm_parent,
                    "status"=>1,
                ],
                [
                    "name"=>$perm_name,
                    "route_name"=>"admin.".str_replace(" ",".",strtolower($perm_name)).".add.view",
                    "action"=>"C",
                    "icon"=>$icon,
                    "sort_order"=>$sort_order,
                    "method"=>"GET",
                    "parent_id"=>$perm_parent,
                    "status"=>1,
                ],
                [
                    "name"=>$perm_name,
                    "route_name"=>"admin.".str_replace(" ",".",strtolower($perm_name)).".edit.view",
                    "action"=>"U",
                    "icon"=>$icon,
                    "sort_order"=>$sort_order,
                    "method"=>"GET",
                    "parent_id"=>$perm_parent,
                    "status"=>1,
                ],
                [
                    "name"=>$perm_name,
                    "route_name"=>"admin.".str_replace(" ",".",strtolower($perm_name)).".create",
                    "action"=>"C",
                    "icon"=>$icon,
                    "sort_order"=>$sort_order,
                    "method"=>"POST",
                    "parent_id"=>$perm_parent,
                    "status"=>1,
                ],
                [
                    "name"=>$perm_name,
                    "route_name"=>"admin.".str_replace(" ",".",strtolower($perm_name)).".update",
                    "action"=>"U",
                    "icon"=>$icon,
                    "sort_order"=>$sort_order,
                    "method"=>"POST",
                    "parent_id"=>$perm_parent,
                    "status"=>1,
                ],
                [
                    "name"=>$perm_name,
                    "route_name"=>"admin.".str_replace(" ",".",strtolower($perm_name)).".delete",
                    "action"=>"D",
                    "icon"=>$icon,
                    "sort_order"=>$sort_order,
                    "method"=>"POST",
                    "parent_id"=>$perm_parent,
                    "status"=>1,
                ],
            ];
            if ($perm_type == "child") {
                foreach ($perms as $value) {
                    DB::table('admin_permissions')->insert($value);
                }
            }else{
                DB::table('admin_permissions')->insert($perms[0]);
            }
        }
    }
    function addFrontPerms($perm_name = null, $icon = null, $sort_order = 0, $perm_parent = 0, $perm_type = "child"){
        if ($perm_name !== null) {
            $perms = [
                [
                    "name"=>$perm_name,
                    "route_name"=>"institution.".str_replace(" ",".",strtolower($perm_name)).".manage",
                    "action"=>"R",
                    "icon"=>$icon,
                    "sort_order"=>$sort_order,
                    "method"=>"GET",
                    "parent_id"=>$perm_parent,
                    "status"=>1,
                ],
                [
                    "name"=>$perm_name,
                    "route_name"=>"institution.".str_replace(" ",".",strtolower($perm_name)).".add.view",
                    "action"=>"C",
                    "icon"=>$icon,
                    "sort_order"=>$sort_order,
                    "method"=>"GET",
                    "parent_id"=>$perm_parent,
                    "status"=>1,
                ],
                [
                    "name"=>$perm_name,
                    "route_name"=>"institution.".str_replace(" ",".",strtolower($perm_name)).".edit.view",
                    "action"=>"U",
                    "icon"=>$icon,
                    "sort_order"=>$sort_order,
                    "method"=>"GET",
                    "parent_id"=>$perm_parent,
                    "status"=>1,
                ],
                [
                    "name"=>$perm_name,
                    "route_name"=>"institution.".str_replace(" ",".",strtolower($perm_name)).".create",
                    "action"=>"C",
                    "icon"=>$icon,
                    "sort_order"=>$sort_order,
                    "method"=>"POST",
                    "parent_id"=>$perm_parent,
                    "status"=>1,
                ],
                [
                    "name"=>$perm_name,
                    "route_name"=>"institution.".str_replace(" ",".",strtolower($perm_name)).".update",
                    "action"=>"U",
                    "icon"=>$icon,
                    "sort_order"=>$sort_order,
                    "method"=>"POST",
                    "parent_id"=>$perm_parent,
                    "status"=>1,
                ],
                [
                    "name"=>$perm_name,
                    "route_name"=>"institution.".str_replace(" ",".",strtolower($perm_name)).".delete",
                    "action"=>"D",
                    "icon"=>$icon,
                    "sort_order"=>$sort_order,
                    "method"=>"POST",
                    "parent_id"=>$perm_parent,
                    "status"=>1,
                ],
            ];
            if ($perm_type == "child") {
                foreach ($perms as $value) {
                    InstitutionAdminPermission::insert($value);
                }
            }else{
                InstitutionAdminPermission::insert($perms[0]);
            }
        }
    }
    function generateBarCode(Request $req){
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($req->code, $generator::TYPE_CODE_39);
        
        header('Content-Type: image/png');
        echo "data:image/png;base64,".base64_encode($barcode);        
        return;
    }
    function adminalSetting(Request $req){
        $data = [
            // "adminal"=>adminal::find(StaffPortalHelper::GetadminalId()),
            "title"=>"Setting",
            "action"=>"adminal/setting/save",
            "type"=>"EDIT"
        ];
        return view("adminal.setting", $data);
    }
    function profile_view(Request $req){
        $data = [
            // "user"=>AdminUser::find(StaffPortalHelper::GetUserId()),
            "title"=>"Profile",
            "action"=>"profile/update",
            "type"=>"EDIT"
        ];
        return view("profile", $data);
    }
}
