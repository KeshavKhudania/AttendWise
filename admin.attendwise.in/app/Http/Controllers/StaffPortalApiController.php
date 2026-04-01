<?php

namespace App\Http\Controllers;

use App\Models\Bed;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\DoctorScheduleSlot;
use App\Models\Ipd;
use App\Models\Opd;
use App\Models\Operation;
use App\Models\Patient;
use App\Models\RoomCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Number;

class StaffPortalApiController extends Controller
{
    function FetchOpdPatient(Request $req){
        $data = [
            "uhid_results"=>[],
            "mobile_results"=>[],
        ];
        if ($req->uhid != null) {
            $data["uhid_results"] = Patient::select([
                "first_name",
                "last_name",
                'father_name',
                "email",
                "phone_number",
                "adhaar_number",
                "uhid",
                "gender",
                "date_of_birth",
                "date_of_birth",
                "image",
                "address",
                "abha_id",
                ])->where([
                    "deleted_at"=>null, 
                    "status"=>1,
                    "hospital_id"=>Crypt::decrypt(Session::get("hospital_id")),
                    "uhid"=>$req->uhid,
                    ])->get();
        }
                
        if ($req->mobile != null) {
                $data["mobile_results"]=Patient::select([
                    "first_name",
                    "last_name",
                    "email",
                    "phone_number",
                    "uhid",
                    'father_name',
                    "adhaar_number",
                    "gender",
                    "date_of_birth",
                    "image",
                    "blood_group",
                    "address",
                    "abha_id",
                ])->where([
                    "deleted_at"=>null, 
                    "status"=>1,
                    "hospital_id"=>Crypt::decrypt(Session::get("hospital_id")),
                    "phone_number"=>$req->mobile,
                ])->get();
        }
        return json_encode($data);
    }
    function FetchDoctorByDepartment(Request $req){
        try {
            if ($req->ajax()) {
                $d_id = Crypt::decrypt($req->department);
                return response()->json(Doctor::select("id", "name", "consulting_fee")->where([
                    "hospital_id"=>Crypt::decrypt(Session::get("hospital_id")),
                    "deleted_at"=>null,
                    "status"=>1,
                    "department_id"=>$d_id,
                ])->get()->map(function($vals){
                    return [
                        "name"=>$vals->name,
                        "consulting_fee"=>$vals->consulting_fee,
                        "id"=>Crypt::encrypt($vals->id),
                    ];
                }));
            }
        } catch (\Throwable $th) {
            return abort(403, $th);
        }
    }
    function FetchDoctorByOperation(Request $req){
        try {
            if ($req->ajax()) {
                $o_id = Crypt::decrypt($req->data['operation_id']);
                $d_id = Operation::findOrFail($o_id)->department_id;
                $data = Doctor::select("id", "name", "department_id")->where([
                    "hospital_id"=>Crypt::decrypt(Session::get("hospital_id")),
                    "deleted_at"=>null,
                    "status"=>1,
                    "department_id"=>$d_id,
                ])->get()->map(function($vals){
                    return [
                        "name"=>$vals->name . " (" . $vals->department->name . ")",
                        "encrypted_id"=>Crypt::encrypt($vals->id),
                    ];
                });
                return response()->json([
                    "status" => "success",
                    "data" => $data,
                    "msg" => "Doctors Fetched Successfully",
                    "color"=> "success",
                    "icon" => "check-circle",
                ])->getContent();
            }
        } catch (\Throwable $th) {
            return abort(403, $th);
        }
    }

    function CheckDoctorByDate(Request $req){
        // return $req->post("doctor_id");
        try {
            $doctor = Doctor::where("id", Crypt::decrypt($req->doctor_id))->firstOrFail();
            $date = ($req->date);
            if (isset($date)) {
                $schedule = DoctorSchedule::where([
                    "doctor_id"=>$doctor->id,
                    "status"=>1,
                ])->first();
                if ($schedule == null) {
                    return abort(403, "Doctor is not available on selected date");
                }
                $day = date("D", strtotime($date));
                $slots = DoctorScheduleSlot::select("id","shift", "start_time", "end_time")->where([
                    "schedule_id"=>$schedule->id,
                    "day"=>$day,
                    "deleted_at"=>null,
                ])->get()->map(fn($val)=>[
                    "shift_name"=>$val->shift,
                    "start_time"=>$val->start_time,
                    "end_time"=>$val->end_time,
                    "id"=>Crypt::encrypt($val->id),
                ]);
                if ($slots->count() == 0) {
                    return abort(403, "Doctor is not available on selected date");
                }
                return response()->json($slots);
            }
        } catch (\Throwable $th) {
            // throw Crypt::decrypt($req->doctor_id);
            throw $th;
        }
    }
    function CheckDoctorStatusByDate(Request $req){
        // return $req->post("doctor_id");
        try {
            $doctor = Doctor::where("id", Crypt::decrypt($req->doctor_id))->firstOrFail();
            $date = date("Y-m-d",strtotime($req->date));
            if (isset($date)) {
                $schedule = DoctorSchedule::where([
                    "doctor_id"=>$doctor->id,
                    "status"=>1,
                ])->first();
                if ($schedule == null) {
                    return response()->json([
                            "status" => "failed",
                            // "data" => $data,
                            "msg" => "Doctor is not available on $date",
                            "color"=> "warning",
                            "icon" => "exclamation-triangle",
                        ])->getContent();
                }
                $day = date("D", strtotime($date));
                $return_date = date("d-m-Y", strtotime($date));
                $slots = DoctorScheduleSlot::select("id","shift", "start_time", "end_time")->where([
                    "schedule_id"=>$schedule->id,
                    "day"=>$day,
                    "deleted_at"=>null,
                ])->get();
                if ($slots->count() == 0) {
                    return response()->json([
                            "status" => "failed",
                            // "data" => $data,
                            "msg" => "Doctor is not available on $return_date",
                            "color"=> "danger",
                            "icon" => "exclamation-triangle",
                        ])->getContent();
                }
                // $slots = DoctorScheduleSlot::select("id","shift", "start_time", "end_time")->where([
                //     "schedule_id"=>$schedule->id,
                //     "day"=>$day,
                //     "deleted_at"=>null,
                // ])->get()->map(fn($val)=>[
                //     "shift_name"=>$val->shift,
                //     "start_time"=>$val->start_time,
                //     "end_time"=>$val->end_time,
                //     "id"=>Crypt::encrypt($val->id),
                // ]);
                // if ($slots->count() == 0) {
                //     return abort(403, "Doctor is not available on selected date");
                // }
                return response()->json([
                            "status" => "success",
                            // "data" => $data,
                            "msg" => "Beds fetched successfully",
                            "color"=> "success",
                            "icon" => "check-circle",
                        ])->getContent();;
            }
        } catch (\Throwable $th) {
            // throw Crypt::decrypt($req->doctor_id);
            throw $th;
        }
    }
    function FetchRoomsCategory(Request $req){
        if ($req->post("data")['type'] == null) {
            $typeList = ["patient_accommodation"];
        }else{
            $type = ($req->post("data")["type"]);
            $typeList = [];
            foreach ($type as $value) {
                $typeList[] = Crypt::decrypt($value);
            }
        }
        $rows = RoomCategory::select("id", "name")->when($typeList,
            function($query, $typeList){
                return $query->whereIn("type", $typeList);
            }
        )->where([
            "hospital_id" => Crypt::decrypt(Session::get("hospital_id")),
            "deleted_at" => null,
            "status" => "Active",
        ])->get();
        $data = $rows->map(function($room){
            $room->encrypted_id = Crypt::encrypt($room->id);
            return $room;
        });
        return response()->json([
            "status" => "success",
            "data" => $data,
            "msg" => "Room Categories fetched successfully",
            "color"=> "success",
            "icon" => "check-circle",
        ])->getContent();
    }
    public function FetchRooms(Request $req){
        if ($req->post("data")['category_id'] == null) {
            return response()->json([
                "status" => "error",
                // "data" => $data,
                "msg" => "Please select a room category",
                "color"=> "danger",
                "icon" => "exclamation-circle",
            ])->getContent();
        }else{
            $category_id = Crypt::decrypt($req->post("data")["category_id"]);
        }

        $rows = DB::table("rooms")->select("room_number", "floor", "id", "room_category_id", "room_status")->where([
            "hospital_id" => Crypt::decrypt(Session::get("hospital_id")),
            "deleted_at" => null,
        ])->when($category_id, function($query, $category_id){
            return $query->where("room_category_id", $category_id);
        })->orderBy("floor", "asc")->orderBy("room_number", "asc")
        ->get();
        $data = $rows->map(function($room){
            $room->encrypted_id = Crypt::encrypt($room->id);
            // $room->status = ($room->room_status == "available") ? true : false;
            $room->name = ($room->room_number . " - " . Number::ordinal($room->floor) . "" . " Floor");
            return $room;
        });
        return response()->json([
            "status" => "success",
            "data" => $data,
            "msg" => "Rooms fetched successfully",
            "color"=> "success",
            "icon" => "check-circle",
        ])->getContent();
    }
    public function FetchBedTypes(){
        return DB::table("bed_category")->where([
            "hospital_id" => Crypt::decrypt(Session::get("hospital_id")),
            "deleted_at" => null
        ])->get();
    }
    public function FetchBeds(Request $req){
        if (!isset($req->post("data")['room_id'])) {
            return response()->json([
                "status" => "error",
                // "data" => $data,
                "msg" => "Please select a room",
                "color"=> "danger",
                "icon" => "exclamation-circle",
            ])->getContent();
        }else{
            $room_id = Crypt::decrypt($req->post("data")["room_id"]);
        }
        if (!isset($req->post("data")['bed_type_id'])) {
            $bed_type_id = null;
        }else{
            $bed_type_id = Crypt::decrypt($req->post("data")["bed_type_id"]);
        }
        $rows = Bed::select("bed_number", "bed_status", "bed_category_id", "id")->where([
            "hospital_id" => Crypt::decrypt(Session::get("hospital_id")),
            "deleted_at" => null,
        ])->when($room_id, function($query, $room_id){
            return $query->where("room_id", $room_id);
        })->when($bed_type_id, function($query, $bed_type_id){
            return $query->where("bed_category_id", $bed_type_id);
        })->get();
        $data = $rows->map(function($bed){
            $bed->encrypted_id = Crypt::encrypt($bed->id);
            $bed->name = "Bed Number - ".($bed->bed_number) . " (".$bed->bed_category?->category_name.")";
            $bed->status = ($bed->bed_status == "available") ? true : false;
            return collect($bed)->except(['room', 'bed_category', "bed_category_id", "id"]);
        });
        return response()->json([
            "status" => "success",
            "data" => $data,
            "msg" => "Beds fetched successfully",
            "color"=> "success",
            "icon" => "check-circle",
        ])->getContent();
    }
    function FetchLabRooms(Request $req){
        if (!isset($req->post("data")['test_id'])) {
            return response()->json([
                "status" => "error",
                // "data" => $data,
                "msg" => "Please select a test",
                "color"=> "danger",
                "icon" => "exclamation-circle",
            ])->getContent();
        }else{
            $test_id = Crypt::decrypt($req->post("data")["test_id"]);
        }
        $rows = DB::table("lab_rooms")->select("id", "name", "lab_id")->where([
            "hospital_id" => Crypt::decrypt(Session::get("hospital_id")),
            "deleted_at" => null,
        ])->when($test_id, function($query, $test_id){
            return $query->whereExists(function ($query) use ($test_id) {
                $query->select(DB::raw(1))
                    ->from('tests')
                    ->whereRaw('tests.lab_id = lab_rooms.lab_id')
                    ->where('tests.id', $test_id);
            });
        })->get();
        $data = $rows->map(function($room){
            $room->encrypted_id = Crypt::encrypt($room->id);
            return collect($room)->except(['id', "lab_department"]);
        });
        return response()->json([
            "status" => "success",
            "data" => $data,
            "msg" => "Lab Rooms fetched successfully",
            "color"=> "success",
            "icon" => "check-circle",
        ])->getContent();
    }
    public function opdipdDetails(Request $request) {
        $number = $request->input('number');
        $prefix = strtoupper(substr($number, 0, 3));

        if ($prefix === 'IPD') {
            $patient = Ipd::where('ipd_number', $number)
                ->select('name','age','gender','arrival_date','guardian_mobile','address','outstanding')
                ->first();
        } elseif ($prefix === 'OPD') {
            $patient = Opd::where('opd_id', $number)
                ->select('name','age','gender','visit_date as admit_date','mobile','address','outstanding')
                ->first();
        } else {
            return response()->json(['status' => 'error', 'message' => 'Invalid prefix']);
        }

        if ($patient) {
            return response()->json(['status' => 'success', 'patient' => $patient]);
        }
        return response()->json(['status' => 'error', 'message' => 'Patient not found']);
    }


}
