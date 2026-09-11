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
            "app_errors" => \App\Models\AppErrorLog::with('student')
                ->where('is_resolved', false)
                ->orderBy('created_at', 'desc')
                ->get(),
            "analytics" => [
                "attendance" => (function() {
                    $labels = [];
                    $data = [];
                    for ($i = 6; $i >= 0; $i--) {
                        $date = date('Y-m-d', strtotime("-$i days"));
                        $labels[] = date('D', strtotime($date));
                        $total = DB::table('institution_attendance_records')->where('date', $date)->whereNull('deleted_at')->count();
                        $present = DB::table('institution_attendance_records')->where('date', $date)->where('status', 'present')->whereNull('deleted_at')->count();
                        $data[] = $total > 0 ? round(($present / $total) * 100) : 0;
                    }
                    return ['labels' => $labels, 'data' => $data];
                })(),
                "enrollment" => (function() {
                    $labels = [];
                    $data = [];
                    $departments = DB::table('institution_departments')
                        ->whereNull('deleted_at')
                        ->where('status', 1)
                        ->select('id', 'name')
                        ->get();
                    
                    foreach ($departments as $dept) {
                        $count = DB::table('institution_faculties')
                            ->where('department_id', $dept->id)
                            ->whereNull('deleted_at')
                            ->count();
                        if ($count > 0) {
                            $labels[] = $dept->name;
                            $data[] = $count;
                        }
                    }
                    
                    if (empty($labels)) {
                        $labels = ['Pending Data'];
                        $data = [1];
                    }
                    
                    return ['labels' => $labels, 'data' => $data];
                })(),
                "event_stats" => [
                    "labels" => ['Completed', 'Upcoming'],
                    "data" => [
                        DB::table('institution_events')->where('status', 0)->count(),
                        DB::table('institution_events')->where('status', 1)->count()
                    ]
                ]
            ],
            "allowed_permissions" => unserialize(AdminGroup::find(Crypt::decrypt(Session::get("group_id")))->permissions),
        ];
        return view("dashboard", $data);
    }

    function attendanceDashboard(Request $req)
    {
        $recentSessions = DB::table('institution_attendance_session')
            ->leftJoin('institution_faculties', 'institution_attendance_session.faculty_id', '=', 'institution_faculties.id')
            ->leftJoin('institution_courses', 'institution_attendance_session.schedule_id', '=', 'institution_courses.id') // Adjust this join based on schedule relation
            ->select('institution_attendance_session.*', 'institution_faculties.name as faculty_name')
            ->orderBy('institution_attendance_session.created_at', 'desc')
            ->take(10)
            ->get();

        $data = [
            "title" => "Attendance System",
            "recent_sessions" => $recentSessions,
            "today_attendance" => DB::table('institution_attendance_records')->where('date', date('Y-m-d'))->where('status', 'present')->count(),
            "today_absent" => DB::table('institution_attendance_records')->where('date', date('Y-m-d'))->where('status', 'absent')->count(),
            "total_sessions_today" => DB::table('institution_attendance_session')->where('date', date('Y-m-d'))->count(),
            "allowed_permissions" => unserialize(AdminGroup::find(Crypt::decrypt(Session::get("group_id")))->permissions),
        ];

        return view("attendance.dashboard", $data);
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

    /**
     * Attendance Analytics & Stats page (view)
     */
    function attendanceAnalytics(Request $req)
    {
        $data = [
            "title" => "Attendance Analytics",
            "departments" => DB::table('institution_departments')->whereNull('deleted_at')->where('status', 1)->select('id', 'name')->orderBy('name')->get(),
            "courses" => DB::table('institution_courses')->whereNull('deleted_at')->where('status', 1)->select('id', 'name')->orderBy('name')->get(),
            "sections" => DB::table('institution_sections')->whereNull('deleted_at')->where('status', 1)->select('id', 'name', 'course_id', 'semester')->orderBy('name')->get(),
            "subjects" => DB::table('institution_subjects')->whereNull('deleted_at')->where('status', 1)->select('id', 'name', 'code')->orderBy('name')->get(),
            "faculties" => DB::table('institution_faculties')->whereNull('deleted_at')->where('status', 1)->select('id', 'name', 'employee_code')->orderBy('name')->get(),
            "allowed_permissions" => unserialize(AdminGroup::find(Crypt::decrypt(Session::get("group_id")))->permissions),
        ];
        return view("analytics.attendance", $data);
    }

    /**
     * Attendance Analytics API - returns JSON for AJAX filtering
     */
    function attendanceAnalyticsApi(Request $req)
    {
        $query = DB::table('institution_attendance_records as ar')
            ->leftJoin('institution_students as s', 'ar.student_id', '=', 's.id')
            ->leftJoin('institution_schedules as sch', 'ar.schedule_id', '=', 'sch.id')
            ->leftJoin('institution_subjects as sub', 'sch.subject_id', '=', 'sub.id')
            ->leftJoin('institution_faculties as f', 'ar.marked_by_faculty_id', '=', 'f.id')
            ->leftJoin('institution_sections as sec', 'sch.section_id', '=', 'sec.id')
            ->leftJoin('institution_courses as c', 'sec.course_id', '=', 'c.id')
            ->leftJoin('institution_departments as d', 'c.department_id', '=', 'd.id')
            ->whereNull('ar.deleted_at');

        // --- Apply Filters ---
        if ($req->filled('department_id')) {
            $query->where('d.id', $req->department_id);
        }
        if ($req->filled('course_id')) {
            $query->where('c.id', $req->course_id);
        }
        if ($req->filled('section_id')) {
            $query->where('sec.id', $req->section_id);
        }
        if ($req->filled('subject_id')) {
            $query->where('sub.id', $req->subject_id);
        }
        if ($req->filled('faculty_id')) {
            $query->where('f.id', $req->faculty_id);
        }
        if ($req->filled('student_id')) {
            $query->where('s.id', $req->student_id);
        }
        if ($req->filled('semester')) {
            $query->where('sec.semester', $req->semester);
        }
        if ($req->filled('status')) {
            $query->where('ar.status', $req->status);
        }
        if ($req->filled('date_from')) {
            $query->where('ar.date', '>=', $req->date_from);
        }
        if ($req->filled('date_to')) {
            $query->where('ar.date', '<=', $req->date_to);
        }
        if ($req->filled('day_of_week')) {
            $query->whereRaw('DAYOFWEEK(ar.date) = ?', [$req->day_of_week]);
        }
        if ($req->filled('student_search')) {
            $query->where(function($q) use ($req) {
                $q->where('s.name', 'like', '%'.$req->student_search.'%')
                  ->orWhere('s.roll_number', 'like', '%'.$req->student_search.'%')
                  ->orWhere('s.enrollment_number', 'like', '%'.$req->student_search.'%');
            });
        }

        // Clone for aggregation
        $totalRecords = (clone $query)->count();
        $presentCount = (clone $query)->where('ar.status', 'present')->count();
        $absentCount = (clone $query)->where('ar.status', 'absent')->count();
        $lateCount = (clone $query)->where('ar.status', 'late')->count();
        $excusedCount = (clone $query)->where('ar.status', 'excused')->count();
        $overallPct = $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 1) : 0;

        // Daily trend (last 30 days or within date range)
        $trendFrom = $req->filled('date_from') ? $req->date_from : date('Y-m-d', strtotime('-30 days'));
        $trendTo = $req->filled('date_to') ? $req->date_to : date('Y-m-d');
        $dailyTrend = (clone $query)
            ->whereBetween('ar.date', [$trendFrom, $trendTo])
            ->select(
                DB::raw('ar.date as dt'),
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN ar.status = 'present' THEN 1 ELSE 0 END) as present_count")
            )
            ->groupBy('ar.date')
            ->orderBy('ar.date')
            ->get()
            ->map(function($row) {
                return [
                    'date' => $row->dt,
                    'label' => date('M d', strtotime($row->dt)),
                    'total' => (int)$row->total,
                    'present' => (int)$row->present_count,
                    'pct' => $row->total > 0 ? round(($row->present_count / $row->total) * 100, 1) : 0,
                ];
            });

        // Subject-wise breakdown
        $subjectBreakdown = (clone $query)
            ->select(
                'sub.name as subject_name',
                'sub.code as subject_code',
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN ar.status = 'present' THEN 1 ELSE 0 END) as present_count")
            )
            ->whereNotNull('sub.id')
            ->groupBy('sub.id', 'sub.name', 'sub.code')
            ->orderByDesc('total')
            ->limit(15)
            ->get()
            ->map(function($row) {
                return [
                    'name' => $row->subject_name ?? 'Unknown',
                    'code' => $row->subject_code ?? '',
                    'total' => (int)$row->total,
                    'present' => (int)$row->present_count,
                    'pct' => $row->total > 0 ? round(($row->present_count / $row->total) * 100, 1) : 0,
                ];
            });

        // Faculty-wise breakdown
        $facultyBreakdown = (clone $query)
            ->select(
                'f.name as faculty_name',
                'f.employee_code',
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN ar.status = 'present' THEN 1 ELSE 0 END) as present_count")
            )
            ->whereNotNull('f.id')
            ->groupBy('f.id', 'f.name', 'f.employee_code')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(function($row) {
                return [
                    'name' => $row->faculty_name ?? 'Unknown',
                    'code' => $row->employee_code ?? '',
                    'total' => (int)$row->total,
                    'present' => (int)$row->present_count,
                    'pct' => $row->total > 0 ? round(($row->present_count / $row->total) * 100, 1) : 0,
                ];
            });

        // Student-level records (paginated)
        $page = $req->input('page', 1);
        $perPage = $req->input('per_page', 25);
        $studentRecords = (clone $query)
            ->select(
                's.id as student_id', 's.name as student_name', 's.roll_number',
                'ar.date', 'ar.status', 'ar.remarks',
                'sub.name as subject_name', 'sub.code as subject_code',
                'f.name as faculty_name',
                'sec.name as section_name', 'c.name as course_name',
                'sch.day', 'sch.start_time', 'sch.end_time'
            )
            ->orderByDesc('ar.date')
            ->orderBy('s.name')
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();

        // Day-of-week heatmap
        $dowHeatmap = (clone $query)
            ->select(
                DB::raw('DAYOFWEEK(ar.date) as dow'),
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN ar.status = 'present' THEN 1 ELSE 0 END) as present_count")
            )
            ->groupBy(DB::raw('DAYOFWEEK(ar.date)'))
            ->get()
            ->map(function($row) {
                $days = ['', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                return [
                    'day' => $days[$row->dow] ?? 'N/A',
                    'total' => (int)$row->total,
                    'present' => (int)$row->present_count,
                    'pct' => $row->total > 0 ? round(($row->present_count / $row->total) * 100, 1) : 0,
                ];
            });

        return response()->json([
            'summary' => [
                'total' => $totalRecords,
                'present' => $presentCount,
                'absent' => $absentCount,
                'late' => $lateCount,
                'excused' => $excusedCount,
                'overall_pct' => $overallPct,
            ],
            'daily_trend' => $dailyTrend,
            'subject_breakdown' => $subjectBreakdown,
            'faculty_breakdown' => $facultyBreakdown,
            'dow_heatmap' => $dowHeatmap,
            'records' => $studentRecords,
            'pagination' => [
                'page' => (int)$page,
                'per_page' => (int)$perPage,
                'total' => $totalRecords,
                'total_pages' => ceil($totalRecords / $perPage),
            ],
        ]);
    }
}