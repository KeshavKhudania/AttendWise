<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;

class VenueController extends Controller
{
    public function index()
    {
        $institutionId = get_logged_in_user()->institution_id;
        $data = [
            "venues" => Venue::where('institution_id', $institutionId)->get(),
            "title" => "Manage Venues"
        ];
        return view("venue.index", $data);
    }

    public function formView(Request $req)
    {
        $institutionId = get_logged_in_user()->institution_id;
        if ($req->segment(3)) {
            $details = Venue::where('institution_id', $institutionId)->find(Crypt::decrypt($req->segment(3)));
            if (!$details) {
                return abort(404, "Page Not Found");
            }
            $fields = $details;
            if (!$fields->latlng && $fields->latitude && $fields->longitude) {
                $fields->latlng = json_encode([[(float)$fields->latitude, (float)$fields->longitude]]);
            }
            $data = [
                "title" => "Edit Venue",
                "type" => "EDIT",
                "action" => route("institution.venues.update", ["id" => $req->segment(3)]),
                "venue" => $fields,
            ];
        }
        else {
            $fields = [];
            foreach (Schema::getColumnListing("institution_venues") as $value) {
                $fields[$value] = null;
            }
            $data = [
                "title" => "Add Venue",
                "type" => "ADD",
                "action" => route("institution.venues.create"),
                "venue" => (object)$fields,
            ];
        }
        return view("venue.form", $data);
    }

    public function form(Request $req)
    {
        $institutionId = get_logged_in_user()->institution_id;
        $data = [];
        foreach (Schema::getColumnListing('institution_venues') as $value) {
            if (in_array($value, ['id', 'created_at', 'updated_at', 'deleted_at']))
                continue;
            if ($req->has($value)) {
                if ($value == 'latlng' && $req->post($value) != null) {
                    // latlng is handled as JSON string in form, Cast handles encryption
                    $points = is_string($req->post($value)) ? json_decode($req->post($value), true) : $req->post($value);
                    $data[$value] = $points;
                    if (!empty($points) && isset($points[0][0]) && isset($points[0][1])) {
                        $data['latitude'] = $points[0][0];
                        $data['longitude'] = $points[0][1];
                    }
                }
                else {
                    $data[$value] = $req->input($value);
                }
            }
        }

        if ($req->segment(3)) {
            if (Venue::where('institution_id', $institutionId)->find(Crypt::decrypt($req->segment(3)))->update($data)) {
                return json_encode(["msg" => "Venue Updated.", "color" => "success", "icon" => "check-circle"]);
            }
        }
        else {
            $data['institution_id'] = $institutionId;
            $venue = Venue::create($data);
            if ($venue) {
                return json_encode(["msg" => "Venue Created.", "color" => "success", "icon" => "check-circle"]);
            }
        }
        return abort("403", json_encode(["msg" => "Something went wrong.", "color" => "danger", "icon" => "exclamation-circle"]));
    }

    public function delete(Request $req)
    {
        try {
            $institutionId = get_logged_in_user()->institution_id;
            if ($id = Crypt::decrypt($req->segment(3))) {
                Venue::where('institution_id', $institutionId)->findOrFail($id)->delete();
                return json_encode(["msg" => "Venue Deleted.", "color" => "success", "icon" => "check-circle"]);
            }
        }
        catch (\Throwable $th) {
            return abort(401);
        }
    }
}