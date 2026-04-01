<?php

namespace App\Http\Controllers;

use App\Models\AdminGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Picqer\Barcode\BarcodeGeneratorPNG;

class HomeController extends Controller
{
    function index(Request $req)
    {
        if ($req->has('setup_venue_perms')) {
            // Find parent of Blocks to keep Venues near it
            $blockPerm = DB::table('institution_admin_permissions')->where('route_name', 'institution.blocks.manage')->first();
            $parentId = $blockPerm ? $blockPerm->parent_id : 0;

            $this->addPerms("Venues", "fa-map-marked-alt", 1, $parentId, "child");

            // Assign to current user group
            $user = get_logged_in_user();
            $group = AdminGroup::find($user->admin_group_id);
            if ($group) {
                $perms = unserialize($group->permissions) ?: [];
                $newPerms = [
                    'institution.venues.manage',
                    'institution.venues.add.view',
                    'institution.venues.edit.view',
                    'institution.venues.create',
                    'institution.venues.update',
                    'institution.venues.delete'
                ];
                foreach ($newPerms as $np) {
                    if (!in_array($np, $perms))
                        $perms[] = $np;
                }
                $group->permissions = serialize($perms);
                $group->save();
                return "Venue permissions added (under same parent as Blocks) and assigned to your group. Visit /dashboard.";
            }
            return "Venue permissions created.";
        }

        if ($req->has('setup_event_perms')) {
            // 1. Create/Ensure Parent Permission "Event"
            $parent = DB::table('institution_admin_permissions')
                ->where('name', 'Event')
                ->where('parent_id', 0)
                ->first();

            if (!$parent) {
                // Find parent of Clubs to keep it in the same section
                $club = DB::table('institution_admin_permissions')->where('name', 'Club')->where('parent_id', 0)->first();
                $mainParent = $club ? $club->id : 0;

                $parentId = DB::table('institution_admin_permissions')->insertGetId([
                    "name" => "Event",
                    "route_name" => "institution.events.folder",
                    "action" => "R",
                    "icon" => "fa-calendar-alt",
                    "sort_order" => 30,
                    "method" => "GET",
                    "parent_id" => 0,
                    "status" => 1,
                ]);
            }
            else {
                $parentId = $parent->id;
            }

            // 2. Create Manage Events Child Permission using addPerms
            // We'll call it with "Events" so the routes match institution.events.*
            // But we'll update the name to "Manage Events" for the primary one
            $this->addPerms("Events", "fa-tasks", 1, $parentId, "child");
            DB::table('institution_admin_permissions')
                ->where('route_name', 'institution.events.manage')
                ->update(['name' => 'Manage Events']);

            // 3. Add extra permissions for participants and attendance
            $extraPerms = [
                ['name' => 'Event Participants', 'route_name' => 'institution.events.manage.participants', 'action' => 'R', 'method' => 'GET'],
                ['name' => 'Event Participants Recruit', 'route_name' => 'institution.events.manage.participants.add', 'action' => 'C', 'method' => 'POST'],
                ['name' => 'Event Participants Privilege', 'route_name' => 'institution.events.manage.participants.toggle_attendance', 'action' => 'U', 'method' => 'POST'],
                ['name' => 'Event Participants Remove', 'route_name' => 'institution.events.manage.participants.remove', 'action' => 'D', 'method' => 'POST'],
                ['name' => 'Event Attendance View', 'route_name' => 'institution.events.manage.attendance', 'action' => 'R', 'method' => 'GET'],
                ['name' => 'Event Attendance Mark', 'route_name' => 'institution.events.manage.attendance.mark', 'action' => 'U', 'method' => 'POST'],
            ];

            foreach ($extraPerms as $p) {
                if (!DB::table('institution_admin_permissions')->where('route_name', $p['route_name'])->exists()) {
                    DB::table('institution_admin_permissions')->insert(array_merge($p, [
                        'icon' => 'fa-users',
                        'sort_order' => 5,
                        'parent_id' => $parentId,
                        'status' => 1
                    ]));
                }
            }

            // 4. Assign permissions to current user's group
            $user = get_logged_in_user();
            $group = AdminGroup::find($user->admin_group_id);
            if ($group) {
                $perms = unserialize($group->permissions) ?: [];
                $newPerms = ['institution.events.folder', 'institution.events.manage', 'institution.events.manage.add.view', 'institution.events.manage.edit.view', 'institution.events.manage.create', 'institution.events.manage.update', 'institution.events.manage.delete', 'institution.events.manage.participants', 'institution.events.manage.attendance'];

                foreach ($newPerms as $np) {
                    if (!in_array($np, $perms))
                        $perms[] = $np;
                }

                $group->permissions = serialize($perms);
                $group->save();
                return "Permissions added (Parent: Event, Child: Manage Events) and assigned to your group. Visit /dashboard.";
            }
            return "Event parent/child permissions created. Visit /dashboard.";
        }

        $data = [
            "kpis" => [
                [
                    "name" => "Total Students",
                    "count" => DB::table("institution_students")->whereNull('deleted_at')->count(),
                    "icon" => "fas fa-user-graduate",
                    "color" => "primary",
                    "route" => "institution.student.manage",
                    "delta" => "+4.2%"
                ],
                [
                    "name" => "Total Faculty",
                    "count" => DB::table("institution_faculties")->whereNull('deleted_at')->count(),
                    "icon" => "fas fa-chalkboard-teacher",
                    "color" => "info",
                    "route" => "institution.faculty.manage",
                    "delta" => "+1.5%"
                ],
                [
                    "name" => "Total Courses",
                    "count" => DB::table("institution_courses")->whereNull('deleted_at')->count(),
                    "icon" => "fas fa-book",
                    "color" => "success",
                    "route" => "institution.courses.manage",
                    "delta" => "No change"
                ],
                [
                    "name" => "Active Events",
                    "count" => DB::table("institution_events")->where('status', 1)->count(),
                    "icon" => "fas fa-calendar-alt",
                    "color" => "warning",
                    "route" => "institution.events.manage",
                    "delta" => "+3 new"
                ],
            ],
            "analytics" => [
                "attendance" => [
                    "labels" => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                    "data" => [85, 88, 76, 92, 90, 82]
                ],
                "enrollment" => [
                    "labels" => ['Engineering', 'Science', 'Arts', 'Commerce', 'Law'],
                    "data" => [450, 320, 150, 280, 120]
                ],
                "event_stats" => [
                    "labels" => ['Completed', 'Upcoming', 'Draft'],
                    "data" => [
                        DB::table('institution_events')->where('status', 0)->count(),
                        DB::table('institution_events')->where('status', 1)->count(),
                        2 // static for demo
                    ]
                ]
            ],
            "allowed_permissions" => unserialize(AdminGroup::find(Crypt::decrypt(Session::get("group_id")))->permissions),
        ];
        return view("dashboard", $data);
    }
    function addPerms($perm_name = null, $icon = null, $sort_order = 0, $perm_parent = 0, $perm_type = "child")
    {
        if ($perm_name !== null) {
            $perms = [
                [
                    "name" => $perm_name,
                    "route_name" => "institution." . str_replace(" ", ".", strtolower($perm_name)) . ".manage",
                    "action" => "R",
                    "icon" => $icon,
                    "sort_order" => $sort_order,
                    "method" => "GET",
                    "parent_id" => $perm_parent,
                    "status" => 1,
                ],
                [
                    "name" => $perm_name,
                    "route_name" => "institution." . str_replace(" ", ".", strtolower($perm_name)) . ".add.view",
                    "action" => "C",
                    "icon" => $icon,
                    "sort_order" => $sort_order,
                    "method" => "GET",
                    "parent_id" => $perm_parent,
                    "status" => 1,
                ],
                [
                    "name" => $perm_name,
                    "route_name" => "institution." . str_replace(" ", ".", strtolower($perm_name)) . ".edit.view",
                    "action" => "U",
                    "icon" => $icon,
                    "sort_order" => $sort_order,
                    "method" => "GET",
                    "parent_id" => $perm_parent,
                    "status" => 1,
                ],
                [
                    "name" => $perm_name,
                    "route_name" => "institution." . str_replace(" ", ".", strtolower($perm_name)) . ".create",
                    "action" => "C",
                    "icon" => $icon,
                    "sort_order" => $sort_order,
                    "method" => "POST",
                    "parent_id" => $perm_parent,
                    "status" => 1,
                ],
                [
                    "name" => $perm_name,
                    "route_name" => "institution." . str_replace(" ", ".", strtolower($perm_name)) . ".update",
                    "action" => "U",
                    "icon" => $icon,
                    "sort_order" => $sort_order,
                    "method" => "POST",
                    "parent_id" => $perm_parent,
                    "status" => 1,
                ],
                [
                    "name" => $perm_name,
                    "route_name" => "institution." . str_replace(" ", ".", strtolower($perm_name)) . ".delete",
                    "action" => "D",
                    "icon" => $icon,
                    "sort_order" => $sort_order,
                    "method" => "POST",
                    "parent_id" => $perm_parent,
                    "status" => 1,
                ],
            ];
            if ($perm_type == "child") {
                foreach ($perms as $value) {
                    DB::table('institution_admin_permissions')->insert($value);
                }
            }
            else {
                DB::table('institution_admin_permissions')->insert($perms[0]);
            }
        }
    }
    function addFrontPerms($perm_name = null, $icon = null, $sort_order = 0, $perm_parent = 0, $perm_type = "child")
    {
        if ($perm_name !== null) {
            $perms = [
                [
                    "name" => $perm_name,
                    "route_name" => "hospit." . str_replace(" ", ".", strtolower($perm_name)) . ".manage",
                    "action" => "R",
                    "icon" => $icon,
                    "sort_order" => $sort_order,
                    "method" => "GET",
                    "parent_id" => $perm_parent,
                    "status" => 1,
                ],
                [
                    "name" => $perm_name,
                    "route_name" => "hospit." . str_replace(" ", ".", strtolower($perm_name)) . ".add.view",
                    "action" => "C",
                    "icon" => $icon,
                    "sort_order" => $sort_order,
                    "method" => "GET",
                    "parent_id" => $perm_parent,
                    "status" => 1,
                ],
                [
                    "name" => $perm_name,
                    "route_name" => "hospit." . str_replace(" ", ".", strtolower($perm_name)) . ".edit.view",
                    "action" => "U",
                    "icon" => $icon,
                    "sort_order" => $sort_order,
                    "method" => "GET",
                    "parent_id" => $perm_parent,
                    "status" => 1,
                ],
                [
                    "name" => $perm_name,
                    "route_name" => "hospit." . str_replace(" ", ".", strtolower($perm_name)) . ".create",
                    "action" => "C",
                    "icon" => $icon,
                    "sort_order" => $sort_order,
                    "method" => "POST",
                    "parent_id" => $perm_parent,
                    "status" => 1,
                ],
                [
                    "name" => $perm_name,
                    "route_name" => "hospit." . str_replace(" ", ".", strtolower($perm_name)) . ".update",
                    "action" => "U",
                    "icon" => $icon,
                    "sort_order" => $sort_order,
                    "method" => "POST",
                    "parent_id" => $perm_parent,
                    "status" => 1,
                ],
                [
                    "name" => $perm_name,
                    "route_name" => "hospit." . str_replace(" ", ".", strtolower($perm_name)) . ".delete",
                    "action" => "D",
                    "icon" => $icon,
                    "sort_order" => $sort_order,
                    "method" => "POST",
                    "parent_id" => $perm_parent,
                    "status" => 1,
                ],
            ];
            if ($perm_type == "child") {
                foreach ($perms as $value) {
                    DB::table('hospit_front_permissions')->insert($value);
                }
            }
            else {
                DB::table('hospit_front_permissions')->insert($perms[0]);
            }
        }
    }
    function generateBarCode(Request $req)
    {
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($req->code, $generator::TYPE_CODE_39);

        header('Content-Type: image/png');
        echo "data:image/png;base64," . base64_encode($barcode);
        return;
    }
    function HospitalSetting(Request $req)
    {
        $data = [
            "hospital" => Hospital::find(StaffPortalHelper::GetHospitalId()),
            "title" => "Setting",
            "action" => "hospital/setting/save",
            "type" => "EDIT"
        ];
        return view("hospital.setting", $data);
    }
    function profile_view(Request $req)
    {
        $data = [
            // "user"=>AdminUser::find(StaffPortalHelper::GetUserId()),
            "title" => "Profile",
            "action" => "profile/update",
            "type" => "EDIT"
        ];
        return view("profile", $data);
    }
}