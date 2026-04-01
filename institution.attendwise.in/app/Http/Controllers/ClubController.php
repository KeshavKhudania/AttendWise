<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\ClubMember;
use App\Models\Student;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;

class ClubController extends Controller
{
    public function index(Request $request)
    {
        $institutionId = get_logged_in_user()->institution_id;
        $clubs = Club::where('institution_id', $institutionId)->withCount('members')->get();

        $data = [
            'clubs' => $clubs,
            'title' => 'Manage Clubs'
        ];
        return view('club.index', $data);
    }

    public function formView(Request $request)
    {
        $institutionId = get_logged_in_user()->institution_id;
        if ($request->segment(3)) {
            $club = Club::where('institution_id', $institutionId)->find(Crypt::decrypt($request->segment(3)));
            if (!$club) {
                return abort(404, "Page Not Found");
            }
            $data = [
                'title' => 'Edit Club',
                'type' => 'EDIT',
                'action' => route('institution.club.manage.update', ['id' => $request->segment(3)]),
                'club' => $club,
            ];
        }
        else {
            $fields = [];
            foreach (Schema::getColumnListing("institution_clubs") as $value) {
                $fields[$value] = null;
            }
            $data = [
                'title' => 'Add Club',
                'type' => 'ADD',
                'action' => route('institution.club.manage.create'),
                'club' => (object)$fields,
            ];
        }
        return view('club.form', $data);
    }

    public function form(Request $request)
    {
        $institutionId = get_logged_in_user()->institution_id;

        $data = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'status' => $request->input('status', 1),
        ];

        if ($request->segment(3)) {
            $club = Club::where('institution_id', $institutionId)->find(Crypt::decrypt($request->segment(3)));
            if ($club->update($data)) {
                return json_encode(["msg" => "Club Updated.", "color" => "success", "icon" => "check-circle"]);
            }
            return abort(403, json_encode(["msg" => "Something went wrong.", "color" => "danger", "icon" => "exclamation-circle"]));
        }
        else {
            $data['institution_id'] = $institutionId;
            $club = Club::create($data);
            if ($club) {
                return json_encode(["msg" => "Club Created.", "color" => "success", "icon" => "check-circle"]);
            }
            return abort(403, json_encode(["msg" => "Something went wrong.", "color" => "danger", "icon" => "exclamation-circle"]));
        }
    }

    public function delete(Request $request)
    {
        try {
            $institutionId = get_logged_in_user()->institution_id;
            if ($id = Crypt::decrypt($request->segment(3))) {
                Club::where('institution_id', $institutionId)->findOrFail($id)->delete();
                return json_encode(["msg" => "Club Deleted.", "color" => "success", "icon" => "check-circle"]);
            }
        }
        catch (\Throwable $th) {
            return abort(401);
        }
    }

    // Members section
    public function members(Request $request, $encodedId)
    {
        $institutionId = get_logged_in_user()->institution_id;
        $clubId = Crypt::decrypt($encodedId);
        $club = Club::where('institution_id', $institutionId)->findOrFail($clubId);

        $members = ClubMember::where('club_id', $clubId)->get();

        // Manual assignment of member instances because polymorphic relations via helper functions
        foreach ($members as $m) {
            if ($m->member_type === 'student') {
                $m->details = Student::find($m->member_id);
            }
            else {
                $m->details = Faculty::find($m->member_id);
            }
        }

        $students = Student::where('institution_id', $institutionId)->where('status', 1)->get();
        $faculties = Faculty::where('institution_id', $institutionId)->where('status', 1)->get();

        $data = [
            'title' => $club->name . ' - Manage Members',
            'club' => $club,
            'members' => $members,
            'students' => $students,
            'faculties' => $faculties
        ];

        return view('club.members', $data);
    }

    public function addMember(Request $request, $encodedId)
    {
        $request->validate([
            'member_type' => 'required|in:student,faculty',
            'member_id' => 'required|integer',
            'designation' => 'nullable|string|max:255',
        ]);

        $institutionId = get_logged_in_user()->institution_id;
        $clubId = Crypt::decrypt($encodedId);

        // Check if member already exists
        $exists = ClubMember::where('club_id', $clubId)
            ->where('member_type', $request->member_type)
            ->where('member_id', $request->member_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with(['msg' => 'Person is already a member of this club.', 'color' => 'warning']);
        }

        ClubMember::create([
            'institution_id' => $institutionId,
            'club_id' => $clubId,
            'member_type' => $request->member_type,
            'member_id' => $request->member_id,
            'designation' => $request->designation,
            'can_take_attendance' => $request->has('can_take_attendance') ? 1 : 0,
            'status' => 1,
        ]);

        return redirect()->back()->with(['msg' => 'Member added successfully.', 'color' => 'success']);
    }

    public function updateMember(Request $request, $encodedClubId, $encodedMemberId)
    {
        $request->validate([
            'designation' => 'nullable|string|max:255',
        ]);

        $memberId = Crypt::decrypt($encodedMemberId);
        $clubId = Crypt::decrypt($encodedClubId);
        $institutionId = get_logged_in_user()->institution_id;

        $member = ClubMember::where('institution_id', $institutionId)
            ->where('club_id', $clubId)
            ->findOrFail($memberId);

        $member->update([
            'designation' => $request->designation,
            'can_take_attendance' => $request->has('can_take_attendance') ? 1 : 0,
            'status' => $request->input('status', 1),
        ]);

        return redirect()->back()->with(['msg' => 'Member updated successfully.', 'color' => 'success']);
    }

    public function removeMember(Request $request, $encodedClubId, $encodedMemberId)
    {
        try {
            $memberId = Crypt::decrypt($encodedMemberId);
            $clubId = Crypt::decrypt($encodedClubId);
            $institutionId = get_logged_in_user()->institution_id;

            ClubMember::where('institution_id', $institutionId)
                ->where('club_id', $clubId)
                ->findOrFail($memberId)
                ->delete();

            return json_encode(["msg" => "Member removed.", "color" => "success", "icon" => "check-circle"]);
        }
        catch (\Throwable $th) {
            return abort(401);
        }
    }
}