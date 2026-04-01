<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\ClassRoom;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\Section;
use App\Models\Club;
use App\Models\Course;
use App\Models\Department;
use App\Models\Student;
use App\Models\Faculty;
use App\Models\ClassRoomType;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index(Request $req)
    {
        $institutionId = get_logged_in_user()->institution_id;
        $data = [
            "events" => Event::where('institution_id', $institutionId)->withCount(['participants'])->with(['classrooms.block', 'externalVenues'])->get(),
            "title" => "Manage Events"
        ];
        return view("event.index", $data);
    }

    public function formView(Request $req)
    {
        $institutionId = get_logged_in_user()->institution_id;

        $eventId = $req->segment(3);
        if ($eventId) {
            $details = Event::with(['classrooms', 'externalVenues'])->where('institution_id', $institutionId)->find(Crypt::decrypt($eventId));
            if (!$details) {
                return abort(404, "Page Not Found");
            }
            $event = $details;
            $title = "Edit Event";
            $type = "EDIT";
            $action = route("institution.events.manage.update", ["id" => $eventId]);
            $selected_classrooms = $event->classrooms->pluck('id')->toArray();
            $selected_venues = $event->externalVenues->pluck('id')->toArray();
        }
        else {
            $fields = [];
            foreach (Schema::getColumnListing("institution_events") as $value) {
                $fields[$value] = null;
            }
            $event = (object)$fields;
            $title = "Create Event";
            $type = "ADD";
            $action = route("institution.events.manage.create");
            $selected_classrooms = [];
            $selected_venues = [];
        }

        $data = [
            "title" => $title,
            "type" => $type,
            "action" => $action,
            "event" => $event,
            "blocks" => Block::where('institution_id', $institutionId)->get(),
            "classrooms" => ClassRoom::where('institution_id', $institutionId)->get(),
            "classroom_types" => ClassRoomType::where('institution_id', $institutionId)->orWhereNull('institution_id')->get(),
            "all_venues" => Venue::where('institution_id', $institutionId)->get(),
            "selected_classrooms" => $selected_classrooms,
            "selected_venues" => $selected_venues,
        ];

        return view("event.form", $data);
    }

    public function form(Request $req)
    {
        $institutionId = get_logged_in_user()->institution_id;
        $data = [];
        foreach (Schema::getColumnListing('institution_events') as $value) {
            if (in_array($value, ['id', 'created_at', 'updated_at', 'deleted_at']))
                continue;
            if ($req->has($value)) {
                $data[$value] = $req->input($value);
            }
        }

        $data['is_open'] = $req->has('is_open') ? 1 : 0;

        if ($req->segment(3)) {
            $event = Event::where('institution_id', $institutionId)->find(Crypt::decrypt($req->segment(3)));
            if ($event->update($data)) {
                $event->classrooms()->sync($req->input('classroom_ids', []));
                $event->externalVenues()->sync($req->input('venue_ids', []));
                return json_encode(["msg" => "Event Updated.", "color" => "success", "icon" => "check-circle"]);
            }
        }
        else {
            $data['institution_id'] = $institutionId;
            $event = Event::create($data);
            if ($event) {
                $event->classrooms()->sync($req->input('classroom_ids', []));
                $event->externalVenues()->sync($req->input('venue_ids', []));
                return json_encode(["msg" => "Event Created.", "color" => "success", "icon" => "check-circle"]);
            }
        }
        return abort("403", json_encode(["msg" => "Something went wrong.", "color" => "danger", "icon" => "exclamation-circle"]));
    }

    public function delete(Request $req)
    {
        try {
            $institutionId = get_logged_in_user()->institution_id;
            if ($id = Crypt::decrypt($req->segment(3))) {
                Event::where('institution_id', $institutionId)->findOrFail($id)->delete();
                return json_encode(["msg" => "Event Deleted.", "color" => "success", "icon" => "check-circle"]);
            }
        }
        catch (\Throwable $th) {
            return abort(401);
        }
    }

    public function participants($id)
    {
        $institutionId = get_logged_in_user()->institution_id;
        $event = Event::where('institution_id', $institutionId)->with('participants')->findOrFail(Crypt::decrypt($id));

        $participants = EventParticipant::where('event_id', $event->id)->get();
        foreach ($participants as $p) {
            if ($p->participant_type === 'student') {
                $p->details = Student::find($p->participant_id);
            }
            else {
                $p->details = Faculty::find($p->participant_id);
            }
        }

        $data = [
            "event" => $event,
            "participants" => $participants,
            "title" => "Manage Event Participants",
            "sections" => Section::where('institution_id', '=', $institutionId)->get(),
            "clubs" => Club::where('institution_id', '=', $institutionId)->get(),
            "courses" => Course::where('institution_id', '=', $institutionId)->get(),
            "departments" => Department::where('institution_id', '=', $institutionId)->get(),
            "students" => Student::where('institution_id', '=', $institutionId)->where('status', 1)->get(),
            "faculties" => Faculty::where('institution_id', '=', $institutionId)->where('status', 1)->get(),
        ];
        return view("event.participants", $data);
    }

    public function addParticipants(Request $req, $id)
    {
        $institutionId = get_logged_in_user()->institution_id;
        $event_id = Crypt::decrypt($id);
        $type = $req->input('participant_type');

        if ($type === 'faculty') {
            $faculty_ids = $req->input('faculty_ids', []);
            foreach ($faculty_ids as $f_id) {
                EventParticipant::updateOrCreate([
                    'event_id' => $event_id,
                    'participant_type' => 'faculty',
                    'participant_id' => $f_id
                ], [
                    'can_take_attendance' => $req->has('can_take_attendance') ? 1 : 0,
                    'role' => $req->input('role', 'Organizer')
                ]);
            }
        }
        else {
            // Student Recruitment
            $query = Student::where('institution_id', $institutionId);

            $filtersApplied = false;

            if ($req->has('section_ids') && !empty($req->input('section_ids'))) {
                $query->whereIn('section_id', $req->input('section_ids'));
                $filtersApplied = true;
            }

            if ($req->has('club_ids') && !empty($req->input('club_ids'))) {
                $student_ids = DB::table('institution_club_members')
                    ->whereIn('club_id', $req->input('club_ids'))
                    ->where('member_type', 'student')
                    ->pluck('member_id');
                $query->whereIn('id', $student_ids);
                $filtersApplied = true;
            }

            if ($req->has('course_ids') && !empty($req->input('course_ids'))) {
                $query->whereIn('course_id', $req->input('course_ids'));
                $filtersApplied = true;
            }

            if ($req->has('department_ids') && !empty($req->input('department_ids'))) {
                $section_ids = Section::where('institution_id', $institutionId)
                    ->whereIn('department_id', $req->input('department_ids'))
                    ->pluck('id');
                $query->whereIn('section_id', $section_ids);
                $filtersApplied = true;
            }

            if ($req->has('student_ids') && !empty($req->input('student_ids'))) {
                if ($filtersApplied) {
                    $query->orWhere(function ($q) use ($req, $institutionId) {
                        $q->where('institution_id', $institutionId)
                            ->whereIn('id', $req->input('student_ids'));
                    });
                }
                else {
                    $query->whereIn('id', $req->input('student_ids'));
                }
                $filtersApplied = true;
            }

            if (!$filtersApplied) {
                return redirect()->back()->with(['msg' => 'Please select at least one recruitment criterion.', 'color' => 'warning']);
            }

            $students = $query->get();

            foreach ($students as $student) {
                EventParticipant::updateOrCreate([
                    'event_id' => $event_id,
                    'participant_type' => 'student',
                    'participant_id' => $student->id
                ], [
                    'can_take_attendance' => $req->has('can_take_attendance') ? 1 : 0,
                    'role' => $req->input('role', 'Attendee')
                ]);
            }
        }

        return redirect()->back()->with(['msg' => 'Participants added successfully.', 'color' => 'success']);
    }

    public function updateParticipant(Request $req, $participant_id)
    {
        $participant = EventParticipant::findOrFail(Crypt::decrypt($participant_id));

        $participant->update([
            'role' => $req->input('role'),
            'can_take_attendance' => $req->has('can_take_attendance') ? 1 : 0,
        ]);

        return redirect()->back()->with(['msg' => 'Participant details updated.', 'color' => 'success']);
    }

    public function toggleAttendancePrivilege(Request $req, $participant_id)
    {
        $participant = EventParticipant::findOrFail(Crypt::decrypt($participant_id));
        $participant->can_take_attendance = !$participant->can_take_attendance;
        $participant->save();

        return json_encode(["msg" => "Privilege updated.", "color" => "success", "icon" => "check-circle"]);
    }

    public function removeParticipant(Request $req, $participant_id)
    {
        $participant = EventParticipant::findOrFail(Crypt::decrypt($participant_id));
        $participant->delete();

        return json_encode(["msg" => "Participant removed.", "color" => "success", "icon" => "check-circle"]);
    }

    public function attendance($id)
    {
        $institutionId = get_logged_in_user()->institution_id;
        $event = Event::where('institution_id', $institutionId)->findOrFail(Crypt::decrypt($id));

        $participants = EventParticipant::where('event_id', $event->id)->get();
        foreach ($participants as $p) {
            if ($p->participant_type === 'student') {
                $p->details = Student::find($p->participant_id);
            }
            else {
                $p->details = Faculty::find($p->participant_id);
            }
        }

        $data = [
            "event" => $event,
            "participants" => $participants,
            "title" => "Take Attendance - " . $event->name
        ];
        return view("event.attendance", $data);
    }

    public function markAttendance(Request $req, $participant_id)
    {
        $participant = EventParticipant::findOrFail(Crypt::decrypt($participant_id));
        $participant->attendance_status = $req->input('status', 0);
        $participant->save();

        return json_encode(["msg" => "Attendance marked.", "color" => "success", "icon" => "check-circle"]);
    }
}