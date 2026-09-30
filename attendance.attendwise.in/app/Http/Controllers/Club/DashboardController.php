<?php

namespace App\Http\Controllers\Club;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $manager = Auth::guard('club')->user();
        $club = $manager->club;
        
        // Let's load some basic stats for the club
        $totalMembers = $club->members()->count();
        // Assuming we'll have events later, we can just pass an empty collection for now
        $events = collect(); 

        return view('club.dashboard', compact('manager', 'club', 'totalMembers', 'events'));
    }

    public function members()
    {
        $manager = Auth::guard('club')->user();
        $club = $manager->club;
        $members = $club->members()->with(['student', 'faculty', 'userGroup'])->get();

        $students = \App\Models\Student::where('institution_id', $club->institution_id)->where('status', 1)->get();
        $faculties = \App\Models\Faculty::where('institution_id', $club->institution_id)->where('status', 1)->get();
        $groups = \App\Models\ClubUserGroup::where('club_id', $club->id)->get();

        return view('club.members', compact('manager', 'club', 'members', 'students', 'faculties', 'groups'));
    }

    public function addMember(Request $request)
    {
        $request->validate([
            'member_type' => 'required|in:student,faculty',
            'member_id' => 'required|integer',
            'designation' => 'nullable|string|max:255',
            'club_user_group_id' => 'nullable|exists:club_user_groups,id',
        ]);

        $manager = Auth::guard('club')->user();
        $club = $manager->club;

        if ($request->filled('club_user_group_id')) {
            $group = \App\Models\ClubUserGroup::where('club_id', $club->id)->find($request->club_user_group_id);
            if (!$group) return redirect()->back()->with('error', 'Invalid User Group.');
        }

        $exists = \App\Models\ClubMember::where('club_id', $club->id)
            ->where('member_type', $request->member_type)
            ->where('member_id', $request->member_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Person is already a member of this club.');
        }

        \App\Models\ClubMember::create([
            'institution_id' => $club->institution_id,
            'club_id' => $club->id,
            'member_type' => $request->member_type,
            'member_id' => $request->member_id,
            'designation' => $request->designation,
            'club_user_group_id' => $request->club_user_group_id,
            'can_take_attendance' => $request->has('can_take_attendance') ? 1 : 0,
            'status' => 1,
        ]);

        return redirect()->back()->with('success', 'Member added successfully.');
    }

    public function updateMember(Request $request, $id)
    {
        $request->validate([
            'designation' => 'nullable|string|max:255',
            'club_user_group_id' => 'nullable|exists:club_user_groups,id',
            'status' => 'required|boolean',
        ]);

        $manager = Auth::guard('club')->user();
        $club = $manager->club;

        if ($request->filled('club_user_group_id')) {
            $group = \App\Models\ClubUserGroup::where('club_id', $club->id)->find($request->club_user_group_id);
            if (!$group) return redirect()->back()->with('error', 'Invalid User Group.');
        }

        $member = \App\Models\ClubMember::where('club_id', $club->id)->findOrFail($id);

        $member->update([
            'designation' => $request->designation,
            'club_user_group_id' => $request->club_user_group_id,
            'can_take_attendance' => $request->has('can_take_attendance') ? 1 : 0,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Member updated successfully.');
    }

    public function removeMember(Request $request, $id)
    {
        $manager = Auth::guard('club')->user();
        $club = $manager->club;

        $member = \App\Models\ClubMember::where('club_id', $club->id)->findOrFail($id);
        $member->delete();

        return redirect()->back()->with('success', 'Member removed successfully.');
    }

    public function groups()
    {
        $manager = Auth::guard('club')->user();
        $club = $manager->club;
        $groups = \App\Models\ClubUserGroup::where('club_id', $club->id)->withCount('members')->get();
        $permissions = \App\Models\ClubUserGroup::AVAILABLE_PERMISSIONS;

        return view('club.groups', compact('manager', 'club', 'groups', 'permissions'));
    }

    public function addGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);

        $manager = Auth::guard('club')->user();
        $club = $manager->club;

        \App\Models\ClubUserGroup::create([
            'club_id' => $club->id,
            'name' => $request->name,
            'description' => $request->description,
            'permissions' => $request->permissions ?? [],
        ]);

        return redirect()->back()->with('success', 'User Group created successfully.');
    }

    public function updateGroup(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);

        $manager = Auth::guard('club')->user();
        $club = $manager->club;

        $group = \App\Models\ClubUserGroup::where('club_id', $club->id)->findOrFail($id);
        $group->update([
            'name' => $request->name,
            'description' => $request->description,
            'permissions' => $request->permissions ?? [],
        ]);

        return redirect()->back()->with('success', 'User Group updated successfully.');
    }

    public function removeGroup(Request $request, $id)
    {
        $manager = Auth::guard('club')->user();
        $club = $manager->club;

        $group = \App\Models\ClubUserGroup::where('club_id', $club->id)->findOrFail($id);
        if ($group->members()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete a group that has members. Reassign them first.');
        }
        
        $group->delete();

        return redirect()->back()->with('success', 'User Group deleted successfully.');
    }

    public function events()
    {
        $manager = Auth::guard('club')->user();
        $club = $manager->club;
        $events = \App\Models\ClubEvent::where('club_id', $club->id)->with(['eventVenue', 'eventBlock', 'eventClassroom'])->orderBy('event_date', 'desc')->get(); 
        
        return view('club.events', compact('manager', 'club', 'events'));
    }

    public function addEvent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'location' => 'nullable|string',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
        ]);

        $manager = Auth::guard('club')->user();
        $club = $manager->club;

        $venue_id = null;
        $block_id = null;
        $classroom_id = null;
        
        if ($request->location) {
            $parts = explode('_', $request->location);
            if (count($parts) == 2) {
                if ($parts[0] == 'venue') $venue_id = $parts[1];
                elseif ($parts[0] == 'block') $block_id = $parts[1];
                elseif ($parts[0] == 'classroom') $classroom_id = $parts[1];
            }
        }

        \App\Models\ClubEvent::create([
            'club_id' => $club->id,
            'name' => $request->name,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'venue_id' => $venue_id,
            'block_id' => $block_id,
            'classroom_id' => $classroom_id,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Event created successfully.');
    }

    public function updateEvent(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'location' => 'nullable|string',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
        ]);

        $manager = Auth::guard('club')->user();
        $club = $manager->club;

        $event = \App\Models\ClubEvent::where('club_id', $club->id)->findOrFail($id);
        
        $venue_id = null;
        $block_id = null;
        $classroom_id = null;
        
        if ($request->location) {
            $parts = explode('_', $request->location);
            if (count($parts) == 2) {
                if ($parts[0] == 'venue') $venue_id = $parts[1];
                elseif ($parts[0] == 'block') $block_id = $parts[1];
                elseif ($parts[0] == 'classroom') $classroom_id = $parts[1];
            }
        }
        
        $event->update([
            'name' => $request->name,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'venue_id' => $venue_id,
            'block_id' => $block_id,
            'classroom_id' => $classroom_id,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Event updated successfully.');
    }

    public function removeEvent(Request $request, $id)
    {
        $manager = Auth::guard('club')->user();
        $club = $manager->club;

        $event = \App\Models\ClubEvent::where('club_id', $club->id)->findOrFail($id);
        $event->delete();

        return redirect()->back()->with('success', 'Event removed successfully.');
    }

    public function searchLocations(Request $request)
    {
        $manager = Auth::guard('club')->user();
        $club = $manager->club;
        $institution_id = $club->institution_id;
        
        $query = $request->input('q');
        $page = (int) $request->input('page', 1);
        $perPage = 20;

        $venues = \Illuminate\Support\Facades\DB::table('institution_venues')
            ->where('institution_id', $institution_id)
            ->whereNull('deleted_at');

        $blocks = \Illuminate\Support\Facades\DB::table('institution_blocks')
            ->where('institution_id', $institution_id)
            ->whereNull('deleted_at');

        $classrooms = \Illuminate\Support\Facades\DB::table('institution_classrooms')
            ->where('institution_id', $institution_id)
            ->whereNull('deleted_at');

        if ($query) {
            $venues->where('name', 'LIKE', "%{$query}%");
            $blocks->where('name', 'LIKE', "%{$query}%");
            $classrooms->where('name', 'LIKE', "%{$query}%");
        }

        $getCenter = function($item) {
            if ($item->latitude && $item->longitude) {
                return ['lat' => $item->latitude, 'lng' => $item->longitude];
            }
            if (!empty($item->latlng)) {
                $polygonStr = null;
                try {
                    $rawPayload = decrypt($item->latlng, false);
                    $unserialized = @unserialize($rawPayload);
                    $polygonStr = ($unserialized !== false) ? $unserialized : $rawPayload;
                } catch (\Exception $e) {}

                if ($polygonStr) {
                    try {
                        $polygon = json_decode($polygonStr, true);
                        if (is_array($polygon) && count($polygon) > 0) {
                            $latSum = 0;
                            $lngSum = 0;
                            foreach ($polygon as $point) {
                                $latSum += $point[0];
                                $lngSum += $point[1];
                            }
                            return [
                                'lat' => $latSum / count($polygon),
                                'lng' => $lngSum / count($polygon)
                            ];
                        }
                    } catch (\Exception $e) {}
                }
            }
            return ['lat' => null, 'lng' => null];
        };

        $vRes = $venues->get()->map(function($item) use ($getCenter) {
            $center = $getCenter($item);
            return (object)[
                'id' => 'venue_' . $item->id,
                'text' => $item->name . ' (' . $item->type . ')',
                'group' => 'Other Venues',
                'latitude' => $center['lat'],
                'longitude' => $center['lng']
            ];
        });

        $bRes = $blocks->get()->map(function($item) use ($getCenter) {
            $center = $getCenter($item);
            return (object)[
                'id' => 'block_' . $item->id,
                'text' => $item->name,
                'group' => 'Blocks',
                'latitude' => $center['lat'],
                'longitude' => $center['lng']
            ];
        });

        $cRes = $classrooms->get()->map(function($item) use ($getCenter) {
            $center = $getCenter($item);
            return (object)[
                'id' => 'classroom_' . $item->id,
                'text' => $item->name . ' (Capacity: ' . $item->capacity . ')',
                'group' => 'Classrooms',
                'latitude' => $center['lat'],
                'longitude' => $center['lng']
            ];
        });

        $combined = $cRes->concat($bRes)->concat($vRes)->sortBy([['group', 'asc'], ['text', 'asc']])->values();

        $total = $combined->count();
        $results = $combined->slice(($page - 1) * $perPage, $perPage)->values();

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => ($page * $perPage) < $total
            ]
        ]);
    }

    public function attendance(Request $request)
    {
        $manager = Auth::guard('club')->user();
        if ($manager->role !== 'admin' && !$manager->hasPermission('attendance.take') && !$manager->hasPermission('attendance.view')) {
            abort(403, 'Unauthorized. You do not have permission to access attendance.');
        }
        $club = $manager->club;
        $events = \App\Models\ClubEvent::where('club_id', $club->id)->orderBy('event_date', 'desc')->get();
        
        $query = \App\Models\AttendanceSession::where('club_id', $club->id)
            ->whereNull('event_id')
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc');
            
        if ($request->has('from_date') && $request->from_date) {
            $query->where('date', '>=', $request->from_date);
        }
        if ($request->has('to_date') && $request->to_date) {
            $query->where('date', '<=', $request->to_date);
        }
        
        // Show only last 4 if no filter applied
        if (!$request->has('from_date') && !$request->has('to_date')) {
            $query->limit(4);
        }
            
        $adhoc_sessions = $query->get();
            
        return view('club.attendance', compact('manager', 'club', 'events', 'adhoc_sessions'));
    }

    public function geoAttendance(Request $request)
    {
        $manager = Auth::guard('club')->user();
        if ($manager->role !== 'admin' && !$manager->hasPermission('attendance.take') && !$manager->hasPermission('attendance.view')) {
            abort(403, 'Unauthorized. You do not have permission to access attendance.');
        }
        $club = $manager->club;
        
        $query = \App\Models\AttendanceSession::where('club_id', $club->id)
            ->where('is_geofencing', 1)
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc');
            
        if ($request->has('from_date') && $request->from_date) {
            $query->where('date', '>=', $request->from_date);
        }
        if ($request->has('to_date') && $request->to_date) {
            $query->where('date', '<=', $request->to_date);
        }
            
        $geo_sessions = $query->get();
            
        return view('club.geo_attendance', compact('manager', 'club', 'geo_sessions'));
    }

    public function geoAttendanceStart(Request $request)
    {
        $manager = Auth::guard('club')->user();
        if ($manager->role !== 'admin' && !$manager->hasPermission('attendance.take')) {
            abort(403, 'Unauthorized');
        }
        
        $club = $manager->club;
        
        $session = \App\Models\AttendanceSession::create([
            'institution_id' => $club->institution_id,
            'club_id' => $club->id,
            'date' => now()->toDateString(),
            'start_time' => now()->toTimeString(),
            'end_time' => now()->addHours(2)->toTimeString(),
            'status' => 'active',
            'is_geofencing' => 1
        ]);
        
        return redirect()->route('club.attendance.geo.session', ['session_id' => $session->id]);
    }

    public function geoAttendanceSession($session_id)
    {
        $manager = Auth::guard('club')->user();
        if ($manager->role !== 'admin' && !$manager->hasPermission('attendance.take') && !$manager->hasPermission('attendance.view')) {
            abort(403, 'Unauthorized');
        }
        
        $club = $manager->club;
        $session = \App\Models\AttendanceSession::where('club_id', $club->id)->findOrFail($session_id);
        $event = $session->event_id ? \App\Models\ClubEvent::find($session->event_id) : null;
        
        $records = $session->records()->pluck('student_id')->toArray();
        $members = $club->members()->where('member_type', 'student')->with('student')->get();
        
        return view('club.attendance_geo_session', compact('manager', 'club', 'event', 'session', 'records', 'members'));
    }

    public function attendanceInitEvent(Request $request, $event_id)
    {
        $manager = Auth::guard('club')->user();
        if ($manager->role !== 'admin' && !$manager->hasPermission('attendance.take') && !$manager->hasPermission('attendance.view')) {
            abort(403, 'Unauthorized');
        }
        
        $club = $manager->club;
        $event = \App\Models\ClubEvent::where('club_id', $club->id)->findOrFail($event_id);
        
        $session = \App\Models\AttendanceSession::firstOrCreate(
            ['club_id' => $club->id, 'event_id' => $event->id, 'institution_id' => $club->institution_id],
            [
                'date' => $event->event_date,
                'start_time' => $event->start_time ?? '00:00:00',
                'end_time' => $event->end_time ?? '23:59:59',
                'status' => 'active',
                'is_geofencing' => 0
            ]
        );
        
        $method = $request->query('method', $request->input('method'));
        if ($method === 'geo') {
            return redirect()->route('club.attendance.geo.session', ['session_id' => $session->id]);
        }
        return redirect()->route('club.attendance.manage', ['session_id' => $session->id, 'method' => $method]);
    }
    
    public function attendanceStartAdhoc(Request $request)
    {
        $manager = Auth::guard('club')->user();
        if ($manager->role !== 'admin' && !$manager->hasPermission('attendance.take')) {
            abort(403, 'Unauthorized');
        }
        
        $club = $manager->club;
        
        $session = \App\Models\AttendanceSession::create([
            'institution_id' => $club->institution_id,
            'club_id' => $club->id,
            'date' => now()->toDateString(),
            'start_time' => now()->toTimeString(),
            'end_time' => now()->addHours(2)->toTimeString(), // Default 2 hours
            'status' => 'active',
            'is_geofencing' => 0
        ]);
        
        $method = $request->input('method', $request->query('method'));
        if ($method === 'geo') {
            return redirect()->route('club.attendance.geo.session', ['session_id' => $session->id]);
        }
        return redirect()->route('club.attendance.manage', ['session_id' => $session->id, 'method' => $method]);
    }

    public function attendanceManage($session_id)
    {
        $manager = Auth::guard('club')->user();
        if ($manager->role !== 'admin' && !$manager->hasPermission('attendance.take') && !$manager->hasPermission('attendance.view')) {
            abort(403, 'Unauthorized');
        }
        
        $club = $manager->club;
        $session = \App\Models\AttendanceSession::where('club_id', $club->id)->findOrFail($session_id);
        $event = $session->event_id ? \App\Models\ClubEvent::find($session->event_id) : null;
        
        $records = $session->records()->pluck('student_id')->toArray();
        $members = $club->members()->where('member_type', 'student')->with('student')->get();
        
        return view('club.attendance_manage', compact('manager', 'club', 'event', 'session', 'records', 'members'));
    }

    public function attendanceMark(Request $request, $session_id)
    {
        $manager = Auth::guard('club')->user();
        if ($manager->role !== 'admin' && !$manager->hasPermission('attendance.take')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized']);
        }
        $club = $manager->club;
        
        $session = \App\Models\AttendanceSession::where('club_id', $club->id)->findOrFail($session_id);
        
        $status = $request->status;
        $student_id = $request->student_id;
        
        if ($status == 'present') {
            \App\Models\AttendanceRecord::updateOrCreate(
                [
                    'institution_id' => $club->institution_id,
                    'attendance_session_id' => $session->id,
                    'student_id' => $student_id,
                    'club_id' => $club->id,
                    'event_id' => $session->event_id,
                ],
                [
                    'date' => $session->date,
                    'status' => 'present',
                ]
            );
        } else {
            \App\Models\AttendanceRecord::where([
                'attendance_session_id' => $session->id,
                'student_id' => $student_id,
            ])->delete();
        }
        
        return response()->json(['success' => true]);
    }

    public function qrRefresh(Request $request, $session_id)
    {
        $manager = Auth::guard('club')->user();
        if ($manager->role !== 'admin' && !$manager->hasPermission('attendance.take')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $session = \App\Models\AttendanceSession::where('club_id', $manager->club_id)->findOrFail($session_id);
        
        $timestamp = now()->timestamp;
        $payload = $session->uuid . '|' . $timestamp;
        
        $session->update(['qr_refresh_token' => $timestamp]);
        
        return response()->json([
            'success' => true,
            'payload' => $payload
        ]);
    }
    
    public function toggleGeoLocation(Request $request, $session_id)
    {
        $manager = Auth::guard('club')->user();
        if ($manager->role !== 'admin' && !$manager->hasPermission('attendance.take')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $session = \App\Models\AttendanceSession::where('club_id', $manager->club_id)->findOrFail($session_id);
        
        $session->update([
            'is_geofencing' => $request->is_geofencing ? 1 : 0,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'geo_locations' => $request->geo_locations,
        ]);
        
        if ($session->is_geofencing) {
            try {
                event(new \App\Events\ClubGeoSessionStarted(
                    $session->club_id,
                    $session->uuid,
                    $manager->club->name ?? 'Club Activity',
                    $session->venue ?? 'Multiple Locations',
                    $manager->id
                ));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Failed to broadcast geo session start: ' . $e->getMessage());
            }
        } else {
            try {
                event(new \App\Events\ClubGeoSessionClosed($session->club_id, $session->uuid));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Failed to broadcast geo session closed: ' . $e->getMessage());
            }
        }
        
        return response()->json(['success' => true]);
    }
}
