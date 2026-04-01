<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bed;
use App\Models\Ipd;
use App\Models\Opd;
use App\Models\Room;
use App\Models\Test;
use App\Models\Doctor;
use App\Models\Balance;
use App\Models\Disease;
use App\Models\Patient;
use App\Models\Symptom;
use App\Models\Hospital;
use App\Models\TpaPanel;
use App\Models\Operation;
use App\Models\Department;
use App\Models\BedCategory;
use App\Models\HospitGroup;
use App\Models\PatientTest;
use App\Models\Transaction;
use App\Models\VitalRecord;
use App\Models\RoomCategory;
use Illuminate\Http\Request;
use App\Models\BedAssignment;
use App\Models\DoctorSchedule;
use App\Models\RoomAssignment;
use Illuminate\Support\Number;
use App\Models\OperationRecord;
use App\Models\InsuranceProvider;
use App\Models\ReferenceHospital;
use App\Models\DoctorScheduleSlot;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\StaffPortalHelper;
use App\Models\PatientTestRow;

class StaffPortalIpdController extends Controller
{
    public function ipd_view(Request $req){
         $data = [
            "allowed_permissions"=>unserialize(HospitGroup::find(Crypt::decrypt(Session::get("group_id")))->permissions),
            // "rows"=>Ipd::where([
            //     "deleted_at"=>null,
            //     "hospital_id"=>StaffPortalHelper::GetHospitalId(),
            // ])->get(),
            // "rows"=>Ipd::select("opds.*","transactions.status as transaction_status",  "")->where([
            //     "opds.deleted_at"=>null,
            //     "opds.hospital_id"=>StaffPortalHelper::GetHospitalId(),,
            // ])->join("transactions", "opds.transaction_id","=","transactions.id")->get(),
            "rows"=>Ipd::with(["doctor", "patient"])->where([
                "deleted_at"=>null,
                "hospital_id"=>StaffPortalHelper::GetHospitalId(),
                // "appointment_date"=>date("Y-m-d"),
            ])->orderby("id","DESC")->get(),
            "title"=>"Manage IPD",
            
        ];
        return view("staff_portal.ipd.index", $data);
    }

    public function fetch_doctor(Request $req){
        if(!$req->ajax()){
            return response()->json(["error" => "Ajax Not Recognised Properly!!"], 403);
        }else{
            $deprtment_id = Crypt::decrypt($req->departments);
            $hospital_id = StaffPortalHelper::GetHospitalId();
            $doctor_fetch = Doctor::where("hospital_id", $hospital_id)
            ->where("department_id", $deprtment_id)
            ->where("status", 1)
            ->whereNull("deleted_at")
            ->get()
            ->map(function($doctor_fetch){
                $doctor_fetch->encrypted_id = Crypt::encrypt($doctor_fetch->id);
                return $doctor_fetch;
            });
            return response()->json(["success" => $doctor_fetch]);
        }
    }

    public function fetchSchedule(Request $req){
        if(!$req->ajax()){
            return response()->json(["error" => "Ajax Not Recognised Properly!!"], 403);
        }else{
            try{
                $doctor_id = Crypt::decrypt($req->doctors);
                $hospital_id = StaffPortalHelper::GetHospitalId();
                $doc_schedule = DoctorSchedule::where("doctor_id", $doctor_id)
                ->where("hospital_id",$hospital_id)
                ->where("status", 1)
                ->whereNull("deleted_at")
                ->first();
                if(!$doc_schedule){
                    return response()->json(["error" => "Doctor Is Not Available!!"]);
                }
            }
            catch(\Exception $e){
                return response()->json(["error" => "Something Went Wrong!!"], 403);
            }
        }
    }

    public function fetch_rooms(Request $req){
        if(!$req->ajax()){
            return response()->json(["error" => "Ajax Not Recognised Properly!!"], 403);
        }else{
            $hospital_id = StaffPortalHelper::GetHospitalId();
            $room_cat_id = Crypt::decrypt($req->room_avail);
            $rooms = Room::where("room_category_id", $room_cat_id)
            ->where("hospital_id", $hospital_id)
            ->whereNull("deleted_at")
            ->get()
            ->map(function($rooms){
                $rooms->encrypted_number = Crypt::encrypt($rooms->id);
                return $rooms;
            });
            if($rooms){
                return response()->json(["success" => $rooms]);
            }else{
                return response()->json(["error" => "No Rooms Found For this Category!!"]);
            }
        }
    }

    public function fetch_beds(Request $req){
        if(!$req->ajax()){
            return response()->json(['error' => "Ajax Is Not Recognised Properly!!"], 403);
        }else{
            $room_no = Crypt::decrypt($req->selectedRoom);
            $hospital_id = StaffPortalHelper::GetHospitalId();
            $beds = Bed::where("hospital_id", $hospital_id)
            ->where("room_id", $room_no)
            ->where("bed_status", "available")
            ->where("patient_id", null)
            ->get()
            ->map(function($beds){
                $beds->encrypt_id = $beds->id;
                $beds->bed_cat_name = BedCategory::where("id", $beds->bed_category_id)
                ->where("cat_status", "active")
                ->value("category_name");
                return $beds;
            });

            if($beds){
                return response()->json(['success' => $beds]);
            }else{
                return response()->json(['error' => "No Beds Available!!"]);
            }
        }
    }

    public function fetch_patients(Request $req){
        if(!$req->ajax()){
            return response()->json(["error" => "Ajax Not Recognised Properly!!"]);
        }else{
            $mobile_number = $req->mobile_no;
            $uhid = $req->uhid_no;
            $hospital_id = StaffPortalHelper::GetHospitalId();
            $result = [];
            if(!empty($req->uhid_no)){
                $patients_uhid_no = Patient::where("hospital_id", $hospital_id)
                ->where("uhid", $uhid)
                ->where("status", 1)
                ->whereNull("deleted_at")
                ->get();
                if($patients_uhid_no->isNotEmpty()){
                    $result['uhid'] = $patients_uhid_no;
                }
            }

            if(!empty($req->mobile_no)){
                $patients_mob_no = Patient::where("hospital_id", $hospital_id)
                ->where("phone_number", $mobile_number)
                ->where("status", 1)
                ->whereNull("deleted_at")
                ->get();
                if($patients_mob_no->isNotEmpty()){
                    $result['patients'] = $patients_mob_no;
                }
            }
            if(!empty($result)){
                return response()->json(["result" => $result]);
            }else{
                return response()->json(["error" => "No Records Found!!", "type" => "mobile"]);
            }
        }
    }
    function formView(Request $req){
        if ($req->segment(4)) {
            $details = Ipd::find(Crypt::decrypt($req->segment(4)));
            $fields = $details;
            $patient = Patient::find($details->patient_id);
            $transaction = Transaction::find($details->transaction_id);
            // foreach (Schema::getColumnListing("opds") as $value) {
            //     $fields[$value] = null;
            // }
            // foreach (Schema::getColumnListing("patients") as $value) {
            //     $patient[$value] = null;
            // }
            // foreach (Schema::getColumnListing("transactions") as $value) {
            //     $transaction[$value] = null;
            // }
            $data = [
                "title"=>"Update IPD",
                "type"=>"EDIT",
                "action"=>route("hospit.opd.update", ["id"=>$req->segment(4)]),
                // "permissions"=>unserialize(HospitGroup::find(Crypt::decrypt(Session::get("group_id")))->permissions),
                "fields"=>$fields,
                "patient"=>$patient,
                "transaction"=>$transaction,
                "departments"=>Department::where([
                    "hospital_id"=>StaffPortalHelper::GetHospitalId(),
                    "deleted_at"=>null,
                    "status"=>1
                ])->get(),
                "doctors"=>Doctor::where([
                    "deleted_at"=>null,
                    "status"=>1,
                    "department_id"=>$details->department_id,
                ])->get(),
                "tpa_panels"=>TpaPanel::where([
                    "deleted_at"=>null,
                    "status"=>1,
                    "hospital_id"=>StaffPortalHelper::GetHospitalId(),
                ])->get(),
                "symptoms"=>Symptom::where([
                    "deleted_at"=>null,
                ])->get(),
                "diseases"=>Disease::where([
                    "deleted_at"=>null,
                ])->get(),
                "insurance_providers"=>InsuranceProvider::where([
                    "deleted_at"=>null,
                ])->get(),
                "time_slots"=>DoctorScheduleSlot::where([
                    "deleted_at"=>null,
                    "day"=>date("D",strtotime($details->appointment_date)),
                ])->get(),
                "opdid"=>Crypt::encrypt($details->opd),
            ];
        }else{
            $opdID = Crypt::decrypt($req->opd);
            $opd = Opd::find($opdID);
            if ($opd == null) {
                return abort(404, json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
            }
            $fields = [];
             $patient = Patient::find($opd->patient_id);
            $transaction = Transaction::find($opd->transaction_id);
            foreach (Schema::getColumnListing("ipd_admissions") as $value) {
                $fields[$value] = null;
            }
            foreach (Schema::getColumnListing("opds") as $value) {
                $fields[$value] = $opd->$value;
            }
            foreach (Schema::getColumnListing("tpa_panels") as $value) {
                $fields["panel"][$value] = null;
            }
            $data = [
                "title"=>"New IPD",
                "type"=>"ADD",
                "action"=>route("hospit.ipd.create"),
                // "permissions"=>unserialize(HospitGroup::find(Crypt::decrypt(Session::get("group_id")))->permissions),
                "fields"=>$fields,
                "patient"=>$patient,
                "transaction"=>$transaction,
                "departments"=>Department::where([
                    "hospital_id"=>StaffPortalHelper::GetHospitalId(),
                    "deleted_at"=>null,
                    "status"=>1
                ])->get(),
                "reference_hospitals"=>ReferenceHospital::where([
                    "deleted_at"=>null,
                    "status"=>1,
                    "hospital_id"=>StaffPortalHelper::GetHospitalId(),
                ])->get(),
                "insurance_providers"=>InsuranceProvider::where([
                    "deleted_at"=>null,
                ])->get(),
                "tpa_panels"=>TpaPanel::where([
                    "deleted_at"=>null,
                    "status"=>1,
                    "hospital_id"=>StaffPortalHelper::GetHospitalId(),
                ])->get(),
                "symptoms"=>Symptom::where([
                    "deleted_at"=>null,
                ])->get(),
                "diseases"=>Disease::where([
                    "deleted_at"=>null,
                ])->get(),
                "time_slots"=>[],
                "room_categories"=>RoomCategory::where([
                    "deleted_at"=>null,
                    "status"=>"active",
                    "hospital_id"=>StaffPortalHelper::GetHospitalId(),
                ])->get(),
                "rooms"=>[],
                "beds"=>[],
                "bed_types"=> BedCategory::where([
                    "deleted_at"=>null,
                    "cat_status"=>"active",
                    "hospital_id"=>StaffPortalHelper::GetHospitalId(),
                ])->get(),
                "doctors"=>Doctor::where([
                    "hospital_id"=>StaffPortalHelper::GetHospitalId(),
                    "deleted_at"=>null,
                    "status"=>1,
                    "department_id"=>$fields['department_id'],
                ])->get(),
                "opdid"=>($req->opd),
            ];
        }
        return view("staff_portal.ipd.form", $data);
    }
    function form(Request $req){
        if ($req->segment(4)) {
            try {
                $detail = Ipd::find(Crypt::decrypt($req->segment(4)));
            $hospital = Hospital::find(StaffPortalHelper::GetHospitalId(),);
            if ($detail->ipd_status == "Discharged") {
              $data = [
                "reference"=>strip_tags($req->reference),
                "payment_mode"=>strip_tags($req->payment_mode),
                "discount"=>strip_tags($req->discount),
                "payable_amount"=>strip_tags($req->payable_amount),
                "payment_remarks"=>strip_tags($req->payment_remarks),
                "payment_status"=>strip_tags($req->payment_status),
                "tpa_panel_id"=>$req->tpa_panel_id == null ?null:Crypt::decrypt($req->tpa_panel_id),
                "panel_card_number"=>strip_tags($req->panel_card_number),
                "panel_service_number"=>strip_tags($req->panel_service_number),
                "panel_rank"=>strip_tags($req->panel_rank),
                "reason_for_visit"=>strip_tags($req->reason_for_visit),
            ];
            Transaction::where([
                "id"=>$detail->transaction_id,
            ])->update([
                "payment_mode"=>$data['payment_mode'],
                "discount"=>$data['discount'],
                "payable_amount"=>$data['payable_amount'],
                "status"=>$data['payment_status'],
                "time"=>time(),
                "remarks"=>$data["payment_remarks"],
            ]);
            if (Ipd::where(["id"=>$detail->id])->update([
                    // "patient_id"=>$patient,
                    // "appointment_date"=>$data["appointment_date"],
                    // "appointment_time"=>$doctor_schedule_slot->start_time ."-".$doctor_schedule_slot->end_time,
                    // "appointment_slot_id"=>$doctor_schedule_slot->id,
                    // "doctor_id"=>$data['doctor_id'],
                    // "department_id"=>$data['department_id'],
                    // "queue_number"=>StaffPortalHelper::NewQueueNumber($data['appointment_date']),
                    "reference"=>$data['reference'],
                    "tpa_panel_id"=>($data['tpa_panel_id']),
                "panel_card_number"=>($data['panel_card_number']),
                "panel_service_number"=>($data['panel_service_number']),
                "panel_rank"=>($data['panel_rank']),
                "panel_rank"=>($data['panel_rank']),
                "reason_for_visit"=>($data['reason_for_visit']),
                    // "priority"=>$data['priority'],
                    // "status"=>$data['status'],
                ])) {
                    return json_encode(["msg"=>"IPD Updated.", "color"=>"success", "icon"=>"check-circle"]);
                }else{
                    return json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]);
                    // throw "Something went wrong.";
                }
            }else{

                
            $data = [
                "first_name"=>strip_tags($req->first_name) ?? "",
                "last_name"=>strip_tags($req->last_name),
                "email"=>strip_tags($req->email),
                "phone_number"=>strip_tags($req->phone_number) ?? "",
                "address"=>strip_tags($req->address) ?? "",
                "adhaar_number"=>strip_tags($req->adhaar_number),
                "gender"=>strip_tags($req->gender) ?? "",
                "date_of_birth"=>strip_tags($req->date_of_birth) ?? "",
                "blood_group"=>strip_tags($req->blood_group),
                "department_id"=>Crypt::decrypt($req->department_id),
                "doctor_id"=>Crypt::decrypt($req->doctor_id),
                "arrival_time"=>Crypt::decrypt($req->arrival_time),
                "arrival_date"=>($req->arrival_date),
                "reference"=>strip_tags($req->reference),
                "ipd_status"=>strip_tags($req->ipd_status),
                "tpa_panel_id"=>$req->tpa_panel_id == null ?null:Crypt::decrypt($req->tpa_panel_id),
                "panel_card_number"=>strip_tags($req->panel_card_number),
                "panel_service_number"=>strip_tags($req->panel_service_number),
                "panel_rank"=>strip_tags($req->panel_rank),
                "reason_for_visit"=>strip_tags($req->reason_for_visit),
                "abha_id"=>strip_tags($req->abha_id),
                "room_category_id"=>(Crypt::decrypt($req->room_category_id)),
                "room_number_id"=>(Crypt::decrypt($req->room_number_id)),
                "bed_type_id"=>(Crypt::decrypt($req->bed_type_id)),
                "bed_id"=>(Crypt::decrypt($req->bed_id)),
            ];

            $patient = Patient::where([
                "deleted_at"=>null,
                "status"=>1,
                "first_name"=>$data['first_name'],
                "phone_number"=>$data['phone_number'],
                "gender"=>$data['gender'],
                "date_of_birth"=>$data['date_of_birth'],
            ])->first();

            if ($patient == null) {
                Patient::insert([
                    "uhid"=>StaffPortalHelper::NewUHID(),
                    "first_name"=>$data['first_name'],
                    "last_name"=>$data['last_name'],
                    "hospital_id"=>StaffPortalHelper::GetHospitalId(),
                    "email"=>$data['email'],
                    "adhaar_number"=>$data['adhaar_number'],
                    "abha_id"=>$data['abha_id'],
                    "blood_group"=>$data['blood_group'],
                    "address"=>$data['address'],
                    "phone_number"=>$data['phone_number'],
                    "gender"=>$data['gender'],
                    "status"=>1,
                    "date_of_birth"=>$data['date_of_birth'],
                ]);
                $patient = Patient::max("id");
                $p_det = Patient::find($patient);
            }else{
                $p_det = $patient;
                $patient = $patient->id;
                Patient::where("id", $patient)->update([
                    "last_name"=>$data['last_name'] ?? $p_det->last_name,
                    "email"=>$data['email']  ?? $p_det->email,
                    "adhaar_number"=>$data['adhaar_number']  ?? $p_det->adhaar_number,
                    "abha_id"=>$data['abha_id']  ?? $p_det->abha_id,
                    "blood_group"=>$data['blood_group']  ?? $p_det->blood_group,
                    "address"=>$data['address'] ?? $p_det->address,
                ]);
            }
            $patient_img = $req->file("patient_image")->storeAs($hospital->oid."/"."patient", "profile_".md5($p_det->uhid.$p_det->dob).".".$req->file("patient_image")->extension(), "upload");
            $doctor = Doctor::find($data['doctor_id']);
            $department = Department::find($data['department_id']);
            if ($doctor->department_id != $department->id) {
                throw "Something went wrong";
            }
            // if ($data['payable_amount'] < 0 || $data['payable_amount'] > $doctor->consulting_fee) {
            //     throw "Something went wrong";
            // }
            // $doctor_schedule_slot = DoctorScheduleSlot::find($data["appointment_time"]);
            // Transaction::where([
            //     "id"=>$detail->transaction_id,
            // ])->update([
            //     "total_amount"=>$doctor->consulting_fee,
            //     "payment_mode"=>$data['payment_mode'],
            //     "discount"=>$data['discount'],
            //     "payable_amount"=>$data['payable_amount'],
            //     "status"=>$data['payment_status'],
            //     "time"=>time(),
            //     "remarks"=>$data["payment_remarks"],
            // ]);
            // $tid = $detail->transaction_id;
            if (Ipd::where(["id"=>$detail->id])->update([
                    "patient_id"=>$patient,
                    "appointment_date"=>$data["appointment_date"],
                    "arrival_date"=>$data["arrival_date"],
                    "arrival_time"=>$data['arrival_time'],
                    "doctor_id"=>$data['doctor_id'],
                    "department_id"=>$data['department_id'],
                    // "queue_number"=>$data['appointment_date'] != $detail->appointment_date ? StaffPortalHelper::NewQueueNumber($data['appointment_date']):$detail->queue_number,
                    "reference"=>$data['reference'],
                    // "priority"=>$data['priority'],
                    "tpa_panel_id"=>($data['tpa_panel_id']),
                    "panel_card_number"=>($data['panel_card_number']),
                    "panel_service_number"=>($data['panel_service_number']),
                    "panel_rank"=>($data['panel_rank']),
                    "reason_for_visit"=>($data['reason_for_visit']),
                    "ipd_status"=>$data['ipd_status'],
                    "symptoms"=>$req->symptoms == null ?null:serialize($req->symptoms),
                    "disease"=>$req->disease == null ?null: serialize($req->disease),
                    "provisional_diagnosis"=>$req->provisional_diagnosis == null ?null: serialize($req->provisional_diagnosis),
                    "treatment_procedure"=>$req->treatment_procedure == null ?null: serialize($req->treatment_procedure),
                ])) {
                    return json_encode(["msg"=>"IPD Updated.", "color"=>"success", "icon"=>"check-circle"]);
                }else{
                    throw "Something went wrong.";
                }
            }

            } catch (\Throwable $th) {
                throw $th;
            }
        }else{
            try {
                $hospital = Hospital::find(StaffPortalHelper::GetHospitalId());
            $data = [
                "first_name"=>strip_tags($req->first_name),
                "last_name"=>strip_tags($req->last_name),
                "email"=>strip_tags($req->email),
                "phone_number"=>strip_tags($req->phone_number),
                "adhaar_number"=>strip_tags($req->adhaar_number),
                "address"=>strip_tags($req->address) ?? null,
                "gender"=>strip_tags($req->gender),
                "date_of_birth"=>strip_tags($req->date_of_birth),
                "blood_group"=>strip_tags($req->blood_group),
                "department_id"=>Crypt::decrypt($req->department_id),
                "doctor_id"=>Crypt::decrypt($req->doctor_id),
                "arrival_time"=>strip_tags($req->arrival_time),
                "arrival_date"=>strip_tags($req->arrival_date),
                "reference"=>strip_tags($req->reference),
                "source"=>strip_tags($req->source),
                "symptoms"=>($req->symptoms == null) ?null:serialize($req->symptoms),
                "disease"=>($req->disease == null) ?null:serialize($req->disease),
                "provisional_diagnosis"=>($req->provisional_diagnosis == null) ?null:serialize($req->provisional_diagnosis),
                "treatment_procedure"=>strip_tags($req->treatment_procedure),
                // "discount"=>strip_tags($req->discount),
                // "payable_amount"=>strip_tags($req->payable_amount),
                // "payment_remarks"=>strip_tags($req->payment_remarks),
                // "priority"=>strip_tags($req->priority),
                "ipd_status"=>strip_tags($req->ipd_status),
                "tpa_panel_id"=>$req->tpa_panel_id == null ?null:Crypt::decrypt($req->tpa_panel_id),
                "panel_card_number"=>strip_tags($req->panel_card_number),
                "panel_service_number"=>strip_tags($req->panel_service_number),
                "panel_rank"=>strip_tags($req->panel_rank),
                // "payment_status"=>strip_tags($req->payment_status),
                // "reason_for_visit"=>strip_tags($req->reason_for_visit),
                "room_category_id"=>(Crypt::decrypt($req->room_category_id)),
                "room_number_id"=>(Crypt::decrypt($req->room_number_id)),
                "bed_type_id"=>(Crypt::decrypt($req->bed_type_id)),
                "bed_id"=>(Crypt::decrypt($req->bed_id)),
                "guardian_name"=>strip_tags($req->guardian_name) ?? null,
                "guardian_mobile"=>strip_tags($req->guardian_mobile) ?? null,
                "guardian_relation"=>strip_tags($req->guardian_relation) ?? null,
                "guardian_address"=>strip_tags($req->guardian_address) ?? null,
                "has_insurance"=>$req->has_insurance,
                "insurance_company"=>$req->has_insurance == "No" ?null:Crypt::decrypt($req->insurance_company),
                "policy_no"=>$req->has_insurance == "No" ?null:($req->policy_no),
                "payer_name"=>$req->has_insurance == "No" ?null:($req->payer_name),
                // "rate_list"=>Crypt::decrypt($req->rate_list),
            ];
            if ($req->file("patient_image") != null) {
                $data['patient_photo'] = $req->file("patient_image")->storeAs($hospital->oid."/"."patient", "profile_".md5($data['uhid'].$data['date_of_birth']).".".$req->file("patient_image")->extension(), "upload");
            }else{
                $data['patient_photo'] = null;
            }
            $patient = Patient::where([
                "deleted_at"=>null,
                "status"=>1,
                "first_name"=>$data['first_name'],
                "phone_number"=>$data['phone_number'],
                "gender"=>$data['gender'],
                "date_of_birth"=>$data['date_of_birth'],
            ])->first();
            if ($patient == null) {
                Patient::insert([
                    "uhid"=>StaffPortalHelper::NewUHID(),
                    "first_name"=>$data['first_name'],
                    "last_name"=>$data['last_name'],
                    "hospital_id"=>StaffPortalHelper::GetHospitalId(),
                    "email"=>$data['email'],
                    "adhaar_number"=>$data['adhaar_number'],
                    "abha_id"=>$data['abha_id'],
                    "blood_group"=>$data['blood_group'],
                    "address"=>$data['address'],
                    "phone_number"=>$data['phone_number'],
                    "gender"=>$data['gender'],
                    "status"=>1,
                    "date_of_birth"=>$data['date_of_birth'],
                    "image"=>$data['patient_photo'],
                ]);
                $patient = Patient::max("id");
            }else{
                $p_det = $patient;
                $patient = $patient->id;
                Patient::where("id", $patient)->update([
                    "last_name"=>$data['last_name'] ?? $p_det->last_name,
                    "email"=>$data['email']  ?? $p_det->email,
                    "adhaar_number"=>$data['adhaar_number']  ?? $p_det->adhaar_number,
                    "abha_id"=>$data['abha_id']  ?? $p_det->abha_id,
                    "blood_group"=>$data['blood_group']  ?? $p_det->blood_group,
                    "address"=>$data['address'] ?? $p_det->address,
                    "image"=>$data['patient_photo'] ?? $p_det->image,
                ]);
            }
            $doctor = Doctor::find($data['doctor_id']);
            $department = Department::find($data['department_id']);
            if ($doctor->department_id != $department->id) {
                throw "Something went wrong";
            }
            // if ($data['payable_amount'] < 0 || $data['payable_amount'] > $doctor->consulting_fee) {
            //     throw "Something went wrong";
            // }
            // $doctor_schedule_slot = DoctorScheduleSlot::find($data["appointment_time"]);
            // Transaction::insert([
            //     "total_amount"=>$doctor->consulting_fee,
            //     "transaction_id"=>StaffPortalHelper::NewTransactionId(),
            //     "hospital_id"=>StaffPortalHelper::GetHospitalId(),
            //     "payment_mode"=>$data['payment_mode'],
            //     "discount"=>$data['discount'],
            //     "payable_amount"=>$data['payable_amount'],
            //     "status"=>$data['payment_status'],
            //     "time"=>time(),
            //     "remarks"=>$data["payment_remarks"],
            // ]);
            // $tid = Transaction::max("id");

            if (Ipd::insert([
                "hospital_id"=>$hospital->id,
                "patient_id"=>$patient,
                "opd_id"=>Crypt::decrypt($req->opdid),
                "ipd_number"=>StaffPortalHelper::NewIpdId(),
                "case_id"=>StaffPortalHelper::NewIpdCaseId(),
                "arrival_date"=>$data["arrival_date"],
                "arrival_time"=>$data['arrival_time'],
                "doctor_id"=>$data['doctor_id'],
                "department_id"=>$data['department_id'],
                // "transaction_id"=>$tid,
                "referred_by"=>$data['reference'],
                // "priority"=>$data['priority'],
                "ipd_status"=>$data['ipd_status'],
                "guardian_name"=>$data['guardian_name'],
                "guardian_mobile"=>$data['guardian_mobile'],
                "guardian_address"=>$data['guardian_address'],
                "guardian_relation"=>$data['guardian_relation'],
                "has_insurance"=>$data['has_insurance'],
                "insurance_company"=>$data['insurance_company'],
                "payer_name"=>$data['payer_name'],
                "policy_no"=>$data['policy_no'],
                // "rate_list"=>$data['rate_list'],
                "admission_type"=>$req->admission_type,
                "room_category_id"=>$data['room_category_id'],
                "room_number_id"=>$data['room_number_id'],
                "bed_type_id"=>$data['bed_type_id'],
                "bed_id"=>$data['bed_id'],
                "source"=>$data['source'],
                "patient_photo"=>$data['patient_photo'],
                // "reason_for_visit"=>($data['reason_for_visit']),
                "symptoms"=>$req->symptoms == null ?null:serialize($req->symptoms),
                "disease"=>$req->disease == null ?null: serialize($req->disease),
                "tpa_panel_id"=>($data['tpa_panel_id']),
                "panel_card_number"=>($data['panel_card_number']),
                "panel_service_number"=>($data['panel_service_number']),
                "panel_rank"=>($data['panel_rank']),
            ])) {
                Bed::where("id", $data['bed_id'])->update([
                    "bed_status"=>"occupied",
                    "patient_id"=>$patient,
                ]);
                BedAssignment::insert([
                    "bed_id"=>$data['bed_id'],
                    "patient_id"=>$patient,
                    "ipd_id"=>Ipd::max("id"),
                    "room_id"=>$data['room_number_id'],
                    "from_date"=>time(),
                    "price_per_day"=>RoomCategory::where("id", $data['room_category_id'])->value("price_per_day"),
                    "status"=>"active",
                    "hospital_id"=>StaffPortalHelper::GetHospitalId(),
                ]);
                return json_encode(["msg"=>"IPD Created.", "color"=>"success", "icon"=>"check-circle"]);
            }else{
                throw "Something went wrong.";
            }
            } catch (\Throwable $th) {
                throw $th;
            }
            // if($req->file("image")){
            //     $img = $req->file("image")->storeAs($hospital->oid."/"."opd", "profile_".md5($req->name.$req->dob).".".$req->file("image")->extension(), "upload");
            // }else{
            //     $img = null;
            // }
            // if($req->file("signature_image")){
            //     $sign_img = $req->file("signature_image")->storeAs($hospital->oid."/"."opd", "signature_".md5($req->name.$req->dob).".".$req->file("signature_image")->extension(), "upload");
            // }else{
            //     $sign_img = null;
            // }
            // if(Ipd::insert([
            //     "name"=>$req->name,
            //         "email"=>$req->email,
            //         "phone_number"=>$req->phone_number,
            //         "qualification_id"=>Crypt::decrypt($req->qualification_id),
            //         "hospital_iaSchema"=>StaffPortalHelper::GetHospitalId(),,
            //         "department_id"=>Crypt::decrypt($req->department_id),
            //         "experience"=>$req->experience,
            //         "gender"=>$req->gender,
            //         "dob"=>$req->date_of_birth,
            //         "image"=>$img,
            //         "liscence_number"=>$req->liscence_number,
            //         "adhaar_number"=>$req->adhaar_number,
            //         "blood_group"=>$req->blood_group,
            //         "status"=>$req->status,
            //         "address"=>$req->address,
            //         "opd_room"=>$req->opd_room,
            //     "signature_image"=>$sign_img,
            // ])){
            //     return json_encode(["msg"=>"IPD Created.", "color"=>"success", "icon"=>"check-circle"]);
            // }
            return abort("403", json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
        }
    }
    function delete(Request $req){
        try {
            if ($id = Crypt::decrypt($req->segment(4))) {
                Ipd::where("id", $id)->delete();
                return json_encode(["msg"=>"IPD Deleted.", "color"=>"success", "icon"=>"check-circle"]);
            }
        } catch (\Throwable $th) {
            return abort(401);
        }
    }
    function singleView(Request $req){
        if ($req->segment(4)) {
            $id = Crypt::decrypt($req->segment(4));
            $ipd = Ipd::find($id);
            if ($ipd == null) {
                return abort(404, json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
            }
            $data = [
                "title"=>"IPD Details",
                "details"=>$ipd,
                "modules"=>StaffPortalHelper::GetModules("hospit.ipd.manage"),
                "module_actions"=>[
                    "room-allotment.save"=>"ipd/room-allotment/save",
                    "room-allotment.fetch"=>"ipd/room-allotment/fetch",
                    "room-allotment.delete"=>"ipd/room-allotment/delete",
                    "room-allotment.fetch.room_category"=>route("api.fetch.rooms.category"),
                    "room-allotment.fetch.room"=>route("api.fetch.rooms"),
                    "vitals.save"=>"ipd/vitals/save",
                    "vitals.latest"=>"ipd/vitals/latest",
                    "vitals.delete"=>"ipd/vitals/delete",
                    "operation.save"=>"ipd/operation/save",
                    "operation.fetch"=>"ipd/operation/fetch",
                    "operation.records"=>"ipd/operation/records",
                    "operation.fetch.doctor"=>route("api.fetch.operation.doctor"),
                    "operation.fetch.rooms"=>route("api.fetch.rooms"),
                    "balance.save"=>"ipd/balance/save",
                    "balance.fetch"=>"ipd/balance/fetch",
                    "lab_investigation.save"=>"ipd/lab-investigation/save",
                    "lab_investigation.fetch"=>"ipd/lab-investigation/fetch",
                    "lab_investigation.labs_rooms.fetch"=>route("api.fetch.labs.rooms"),
                    // "vitals.update"=>route("hospit.ipd.manage.vitals.update"),
                ],
                "operations"=>Operation::where([
                    "hospital_id"=>StaffPortalHelper::GetHospitalId(),
                    "deleted_at"=>null,
                    "status"=>1,
                ])->get(),
                "tests"=>Test::where([
                    "hospital_id"=>StaffPortalHelper::GetHospitalId(),
                    "deleted_at"=>null,
                    "status"=>1,
                ])->get(),
                "surgical_room_categories"=>RoomCategory::where([
                    "hospital_id"=>StaffPortalHelper::GetHospitalId(),
                    "deleted_at"=>null,
                    "status"=>"active",
                    "type"=>"surgical_rooms",
                ])->get(),
                "doctors"=>Doctor::where([
                    "hospital_id"=>StaffPortalHelper::GetHospitalId(),
                    "deleted_at"=>null,
                    "status"=>1,
                ])->get(),
                "operationHistory"=>OperationRecord::where("ipd_id", $id)
                    ->orderBy("operation_date", "desc")
                    ->get()
                    ->map(function($operation){
                        $operation->performed_at = date("d M Y h:i A", strtotime($operation->operation_date));
                        $operation->encrypted_id = Crypt::encrypt($operation->id);
                        return ($operation);
                    }),
                    "tests"=>Test::where([
                        "hospital_id"=>StaffPortalHelper::GetHospitalId(),
                        "deleted_at"=>null,
                        "status"=>1,
                    ])->get(),
                "vital_records"=>VitalRecord::where("ipd_id", $id)
                    ->orderBy("recorded_at", "desc")
                    ->get()
                    ->map(function($vitals){
                        $vitals->recorded_at = date("d M Y h:i A", strtotime($vitals->recorded_at));
                        $vitals->encrypted_id = Crypt::encrypt($vitals->id);
                        return $vitals;
                    }),
                "bed_allotments"=>BedAssignment::where("ipd_id", $id)
                    ->orderBy("from_date", "desc")
                    ->get()
                    ->map(function($bed){
                        $bed->from_date = date("d M Y h:i A", strtotime($bed->from_date));
                        $bed->to_date = $bed->to_date != null ? date("d M Y h:i A", strtotime($bed->to_date)):null;
                        $bed->encrypted_id = Crypt::encrypt($bed->id);
                        return $bed;
                    }),
                // "patient"=>Patient::find($ipd->patient_id),
                "transaction"=>Transaction::find($ipd->transaction_id),
                // "department"=>Department::find($ipd->department_id),
                // "doctor"=>Doctor::find($ipd->doctor_id),
                "tpa_panel"=>TpaPanel::find($ipd->tpa_panel_id),
                // "bed_assignment"=>BedAssignment::where("ipd_id", $id)->first(),
            ];
            return view("staff_portal.ipd.view", $data);
        }else{
            return abort(404, json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
        }
    }
    function VitalRecordFetch(Request $req){
        $last_24_records = VitalRecord::where('recorded_at', '>=', Carbon::now()->subDay())->get();
        return [
            "data"=>VitalRecord::where("ipd_id", Crypt::decrypt($req->ipd_id))
            ->orderBy("recorded_at", "desc")
            ->get()
            ->map(function($vitals){
                $vitals->recorded_at = date("d M Y h:i A", strtotime($vitals->recorded_at));
                $vitals->recorded_at_date = date("Y-m-d H:i", strtotime($vitals->recorded_at));
                $vitals->recorded_at_human_readable = Carbon::parse(date("d M Y h:i A", strtotime($vitals->recorded_at)))->diffForHumans();
                $vitals->encrypted_id = Crypt::encrypt($vitals->id);
                $vitals->recorded_by_name = $vitals->user->name ?? "Unknown";
                return collect($vitals->toArray())->except(['id', "ipd_id", "recorded_by", "user"])->all();
                // return $vitals;
            }),
            "graph"=>[
                "HR" => $last_24_records->pluck('heart_rate')->filter()->values()->all(),
                "temperature" => $last_24_records->pluck('temperature')->filter()->values()->all(),
                "O2" => $last_24_records->pluck('oxygen_saturation')->filter()->values()->all(),
                "labels" => $last_24_records->pluck('recorded_at')->map(function ($dt) {
                                return Carbon::parse($dt)->format('h:i A');
                            })->values()->all(),
            ],
        ];
    }
    function OpertationRecordFetch(Request $req){
        // $last_24_records = VitalRecord::where('recorded_at', '>=', Carbon::now()->subDay())->get();
        return [
            "data"=>OperationRecord::where("ipd_id", Crypt::decrypt($req->ipd_id))
            ->orderBy("created_at", "desc")
            ->get()
            ->map(function($operation_record){
                $operation_record->operation_date = date("d M Y h:i A", strtotime($operation_record->operation_date));
                $operation_record->operation_date_raw = date("Y-m-d H:i", strtotime($operation_record->operation_date));
                // $operation_record->recorded_at_date = date("Y-m-d H:i", strtotime($operation_record->recorded_at));
                // $operation_record->recorded_at_human_readable = Carbon::parse(date("d M Y h:i A", strtotime($operation_record->recorded_at)))->diffForHumans();
                $department_name = $operation_record->surgeon->department->name;
                $operation_record->encrypted_id = Crypt::encrypt($operation_record->id);
                $operation_record->surgeon_name = $operation_record->surgeon->name . " ($department_name)";
                $operation_record->surgeon_enc_id = Crypt::encrypt($operation_record->surgeon->id);
                $operation_record->operation_name = $operation_record->operation->name;
                $operation_record->room_category_name = $operation_record->room?->category->name;
                $operation_record->operation_code = $operation_record->operation->code;
                $operation_record->room_name = $operation_record->room?->room_number;
                $operation_record->base_cost = $operation_record->balance->amount;
                $operation_record->discount = $operation_record->balance->discount;
                $operation_record->final_cost = $operation_record->balance->payable_amount;
                $operation_record->payment_remarks = $operation_record->balance->remarks;
                $operation_record->pre_op_notes = $operation_record->balance->remarks;
                // $operation_record->recorded_by_name = $operation_record->user->name ?? "Unknown";
                return collect($operation_record)->except(['id', "ipd_id", "hospital_id", "balance_id", "operation_id", "surgeon_id", "assistant_surgeon_id", "anesthetist_id", "anesthesia_id", "ot_room_number", "created_by", "updated_by", "deleted_at", "surgeon", "operation", "balance", "room"])->all();
                // return $vitals;
            }),
        ];
    }
    function BedAllotmentRecordFetch(Request $req){
        // $last_24_records = VitalRecord::where('recorded_at', '>=', Carbon::now()->subDay())->get();
        $current_assignments = BedAssignment::where("ipd_id", Crypt::decrypt($req->ipd_id))
            ->whereNull("to_date")
            ->first();
        return [
            // "widget"=> [
            //     'current_assignment' => "Room: " . ($current_assignments->room?->room_number ?? "N/A") . ", Bed: " . ($current_assignments->bed?->bed_number ?? "N/A") . ", From: " . date("d M Y h:i A", $current_assignments->from_date) . ($current_assignments->to_date ? ", To: " . date("d M Y h:i A", $current_assignments->to_date) : ""),
            // ],
            "data"=>BedAssignment::where("ipd_id", Crypt::decrypt($req->ipd_id))
            ->orderBy("from_date", "desc")
            ->get()
            ->map(function($bed_allotment){
                $bed_allotment->allotment_date = date("d M Y h:i A", ($bed_allotment->from_date));
                // $bed_allotment->recorded_at_date = date("Y-m-d H:i", strtotime($bed_allotment->recorded_at));
                // $bed_allotment->recorded_at_human_readable = Carbon::parse(date("d M Y h:i A", strtotime($bed_allotment->recorded_at)))->diffForHumans();
                $bed_allotment->total_cost = $bed_allotment->selectRaw("
        SUM(
            (FLOOR(((CASE 
                        WHEN to_date IS NULL THEN ? 
                        ELSE to_date 
                    END) - from_date) / 86400) + 1) 
            * price_per_day
        ) as total_cost
    ", [strtotime(now())])->value('total_cost');
                $bed_allotment->encrypted_id = Crypt::encrypt($bed_allotment->id);
                $bed_allotment->room_category_name = $bed_allotment->room?->category->name;
                $bed_allotment->room_name = $bed_allotment->room?->room_number;
                $bed_allotment->transfer_reason = $bed_allotment->transfer_reason ?? "N/A";
                $bed_allotment->floor = Number::ordinal($bed_allotment->room?->floor);
                $bed_allotment->duration = floor(Carbon::createFromTimestamp($bed_allotment->from_date)->startOfDay()->diffInDays($bed_allotment->to_date ? Carbon::createFromTimestamp($bed_allotment->to_date) : now())) . " days";
                $bed_allotment->bed_number = $bed_allotment->bed?->bed_number;
                // $bed_allotment->recorded_by_name = $bed_allotment->user->name ?? "Unknown";
                return collect($bed_allotment)->except(['id', "ipd_id", "hospital_id", "room_id", "patient_id"])->all();
                // return $vitals;
            }),
        ];
    }
    function BalanceFetch(Request $req){
    // Fetch all related transactions (filter as needed)
        try {
            $ipd_id = Crypt::decrypt($req->ipd_id);
            $records = Balance::where('ipd_id', $ipd_id)
                ->orderBy('time', 'DESC')
                ->get();

            // Compute summary values
            $totalBill = $records->where('status', 'Debit')->sum('amount');
            $totalCredit = $records->where('status', 'Credit')->sum('amount');
            $totalRefund = $records->where('status', 'Refund')->sum('amount');
            $totalInsurance = $records->where('status', 'Insurance')->sum('amount');
            $totalDiscount = $records->where('status', 'Discount')->sum('amount');
            // Calculate effective payments and advance
            $totalPaymentsReceived = $totalCredit + $totalInsurance + $totalDiscount - $totalRefund;
            
            if ($totalPaymentsReceived > $totalBill) {
                // If payments exceed bill, excess becomes advance
                $totalPaid = $totalBill;
                $advancePayment = $totalPaymentsReceived - $totalBill;
                $outstanding = 0;
            } else {
                // Normal case - payments are less than or equal to bill
                $totalPaid = $totalPaymentsReceived;
                $advancePayment = 0;
                $outstanding = $totalBill - $totalPaymentsReceived;
            }

            // Format for datatable and frontend
            $data = [];
            foreach ($records->whereIn("status", ["Credit", "Refund"]) as $row) {
                $data[] = [
                    'id'            => Crypt::encrypt($row->id),
                    'reference'=> $row->transaction->transaction_id,
                    'amount'        => $row->amount,
                    'remarks'       => $row->remarks,
                    // 'time'          => $row->time,
                    'payment_date'  => date("Y-m-d h:i A",$row->time),
                    'status'        => $row->status,
                    'payment_method'=> $row->transaction->payment_mode,
                    "received_by_name" => $row->user?->name,
                    // "related_record_type"=> $row->related_record['type'],
                    // "related_record_data"=> collect($row->related_record['data'])->except("id"),
                    // "related_record_name"=> $row->related_record['name'],
                    // Add more fields if needed
                ];
            }

            return response()->json([
                'success' => true,
                'summary' => [
                    'total_bill' => $totalBill,
                    'total_paid' => $totalPaid,
                    'total_refund' => $totalRefund,
                    'total_insurance' => $totalInsurance,
                    'advance_payment' => $advancePayment,
                    'outstanding' => $outstanding,
                    'total_discount' => $totalDiscount,
                ],
                'records' => $data,
                "response"=>json_encode(["msg"=>"Balance and payment history updated successfully.", "color"=>"success", "icon"=>"check-circle"]),
            ]);
        } catch (\Throwable $th) {
            return abort(403, json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
        }
    }
    function OpertationFetch(Request $req){
        $row = Operation::select( "code", "name", "price")->where("id", Crypt::decrypt($req->operation_id))->first();
        return $row;
    }
    function VitalRecordSave(Request $req){
        if($req->ajax()){
            $data = [
                    // Basic Information
                    // 'ipd_id' => $ipd_id,
                    'recorded_at' => $req->recorded_at ?: now(),
                    'recorded_by' => StaffPortalHelper::GetUserId(),
                    
                    // Primary Vital Signs
                    'systolic_bp' => $req->systolic_bp ? (float) $req->systolic_bp : null,
                    'diastolic_bp' => $req->diastolic_bp ? (float) $req->diastolic_bp : null,
                    'heart_rate' => $req->heart_rate ? (int) $req->heart_rate : null,
                    'respiratory_rate' => $req->respiratory_rate ? (int) $req->respiratory_rate : null,
                    'temperature' => $req->temperature ? (float) $req->temperature : null,
                    'oxygen_saturation' => $req->oxygen_saturation ? (float) $req->oxygen_saturation : null,
                    
                    // Physical Measurements
                    'weight' => $req->weight ? (float) $req->weight : null,
                    'height' => $req->height ? (float) $req->height : null,
                    'bmi' => $req->bmi ? (float) $req->bmi : null,
                    
                    // Clinical Assessment
                    'pain_level' => $req->pain_level ? (int) $req->pain_level : null,
                    'glucose_level' => $req->glucose_level ? (float) $req->glucose_level : null,
                    'urine_output' => $req->urine_output ? (float) $req->urine_output : null,
                    
                    // Clinical Observations
                    'consciousness_level' => $req->consciousness_level ?: 'Alert',
                    'pupil_response' => $req->pupil_response ?: 'Normal',
                    
                    // Notes
                    'notes' => $req->notes,
                    'abnormal_findings' => $req->abnormal_findings,
                ];
            if($req->vital_id != null){
                $vital_id = Crypt::decrypt($req->vital_id);
                if(VitalRecord::where("id", $vital_id)->update($data)){
                    return json_encode(["msg"=>"Vitals Updated.", "color"=>"success", "icon"=>"check-circle"]);
                }else{
                    return abort(403, json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
                }
            }else{
                $ipd_id = Crypt::decrypt($req->ipd_id);
                $data['ipd_id'] = $ipd_id;
                if(VitalRecord::insert($data)){
                    return json_encode(["msg"=>"Vitals Saved.", "color"=>"success", "icon"=>"check-circle"]);
                }else{
                    return abort(403, json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
                }
            }
        }
    }
    function OpertationRecordSave(Request $req){
        if($req->ajax()){
            // $validated = $req->validate([
            //     'operation_type'=> ['required', 'string'],
            //     'operation_date'=> ['required'],
            //     'operation_id'=>['required'],
            //     'doctor_id'    => ['required'],
            //     'room_id'=> ['required'],
            //     'operation_cost'=> ['numeric', 'min:0'],
            // ]);
            $ipd = Ipd::findOrFail(Crypt::decrypt($req->ipd_id));
            $operation = Operation::findOrFail(Crypt::decrypt($req->operation_id));
            $data = [
                'operation_type'        => $req->operation_type ?? null, // e.g., "Surgery", "Procedure"
                'operation_date'        => $req->operation_date,              // e.g., 2025-07-24
                'surgeon_id'            => Crypt::decrypt($req->doctor_id),
                // 'operation_notes'       => $req->pre_op_notes ?? "",
                // 'ot_room_number'        => Crypt::decrypt($req->room_id),
                'operation_cost'        => $operation->price ?? 0.00,                                // optional, if not auto-managed
            ];
            if(isset($req->operation_record_id)){
                $operation_record_id = Crypt::decrypt($req->operation_record_id);
                $operation_record = OperationRecord::findOrFail($operation_record_id);
                Balance::where("id", $operation_record->balance_id)->update([
                    // "ipd_id"=>$ipd->id,
                    "amount"=>$operation->price,
                    // "discount"=>$req->operation_price_discount,
                    "payable_amount"=>$operation->price,
                    // "remarks"=>$req->payment_remarks,
                    "status"=>"Debit",
                    "updated_at"=>now(),
                    "updated_by"=>StaffPortalHelper::GetUserId(),
                    // "time"=>time(),
                ]);
                // $data['status'] = $req->status; 
                if(OperationRecord::where("id", $operation_record_id)->update($data)){
                    return json_encode(["msg"=>"Operation Updated.", "color"=>"success", "icon"=>"check-circle"]);
                }else{
                    return abort(403, json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
                }
            }else{
                
                $ipd_id = Crypt::decrypt($req->ipd_id);
                Balance::insert([
                    "ipd_id"=>$ipd->id,
                    "amount"=>$operation->price,
                    "payable_amount"=>$req->operation_price,
                    "status"=>"Debit",
                    "time"=>time(),
                    "created_by"=>StaffPortalHelper::GetUserId(),
                ]);
                $data['ipd_id'] = $ipd_id;
                $data['balance_id'] = Balance::max("id");
                $data['hospital_id'] = StaffPortalHelper::GetHospitalId();
                $data['operation_id'] = Crypt::decrypt($req->operation_id);
                $data['status'] = "Scheduled";
                $data['created_by'] = StaffPortalHelper::GetUserId();
                $data['operation_number'] = StaffPortalHelper::NewOperationNumber();
                $data['created_at'] = now();
                if(OperationRecord::insert($data)){
                    return json_encode(["msg"=>"Operation Saved.", "color"=>"success", "icon"=>"check-circle"]);
                }else{
                    return abort(403, json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
                }
            }
        }
    }
    function BedAllotmentRecordSave(Request $req){
        if($req->ajax()){
            // $validated = $req->validate([
            //     'operation_type'=> ['required', 'string'],
            //     'operation_date'=> ['required'],
            //     'operation_id'=>['required'],
            //     'doctor_id'    => ['required'],
            //     'room_id'=> ['required'],
            //     'operation_cost'=> ['numeric', 'min:0'],
            // ]);
            $ipd = Ipd::findOrFail(Crypt::decrypt($req->ipd_id));
            $data = [
                // 'room_category'        => $req->room_category != null ? Crypt::decrypt($req->room_category) : null,
                'room_id'        => $req->room_number != null ? Crypt::decrypt($req->room_number) : null,
                'bed_id'        => $req->bed_number != null ? Crypt::decrypt($req->bed_number) : null,
                'from_date'            => $req->allotment_date == null ? strtotime(now()) : strtotime($req->allotment_date),
                "transfer_reason" => $req->transfer_reason ?? null,
                "special_requirements" => $req->special_requirements ?? null,
                "price_per_day" => RoomCategory::where("id", Crypt::decrypt($req->room_category))->value("price_per_day"),
            ];
            if(isset($req->room_allotment_id)){
                $bed_assignment_id = Crypt::decrypt($req->room_allotment_id);
                $bed_assignment_record = BedAssignment::findOrFail($bed_assignment_id);
                // $data['status'] = $req->status; 
                if(BedAssignment::where("id", $bed_assignment_id)->update($data)){
                    return json_encode(["msg"=>"Record Updated.", "color"=>"success", "icon"=>"check-circle"]);
                }else{
                    return abort(403, json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
                }
            }else{
                
                $ipd_id = Crypt::decrypt($req->ipd_id);
                $prev_bed = BedAssignment::where("ipd_id", $ipd_id)
                    ->whereNull("to_date");
                Bed::where("id", $prev_bed->first()->bed_id)->update([
                        "bed_status"=>"available",
                        "patient_id"=>$ipd->patient_id,
                    ]);
                    $prev_bill = $data['price_per_day'] * floor(Carbon::createFromTimestamp($prev_bed->first()->from_date)->startOfDay()->diffInDays($req->allotment_date ? Carbon::createFromTimestamp(strtotime($req->allotment_date)) : now()));
                $prev_bed->update([
                        "to_date"=>strtotime($req->allotment_date) ?? strtotime(now()),
                        "status"=>"inactive",
                        "updated_at"=>now(),
                        "updated_by"=>StaffPortalHelper::GetUserId(),
                    ]);
                    Bed::where("id", $data['bed_id'])->update([
                        "bed_status"=>"occupied",
                        "patient_id"=>$ipd->patient_id,
                    ]);

                    Balance::insert([
                        "ipd_id"=>$ipd->id,
                        "amount"=>$prev_bill,
                        "payable_amount"=>$prev_bill,
                        "status"=>"Debit",
                        "time"=>strtotime(now()),
                        "created_by"=>StaffPortalHelper::GetUserId(),
                    ]);
                $data['ipd_id'] = $ipd_id;
                
                $data['hospital_id'] = StaffPortalHelper::GetHospitalId();
                
                $data['status'] = "active";
                $data['patient_id'] = Ipd::findOrFail($ipd_id)->patient_id;
                $data['created_by'] = StaffPortalHelper::GetUserId();
                $data['created_at'] = now();

                if(BedAssignment::insert($data)){
                    return json_encode(["msg"=>"Bed Assigned.", "color"=>"success", "icon"=>"check-circle"]);
                }else{
                    return abort(403, json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
                }
            }
        }
    }
    function BalanceSave(Request $req){
        if($req->ajax()){
            // $validated = $req->validate([
            //     'operation_type'=> ['required', 'string'],
            //     'operation_date'=> ['required'],
            //     'operation_id'=>['required'],
            //     'doctor_id'    => ['required'],
            //     'room_id'=> ['required'],
            //     'operation_cost'=> ['numeric', 'min:0'],
            // ]);
            $ipd = Ipd::findOrFail(Crypt::decrypt($req->ipd_id));
            $req->validate([
                'payment_amount' => 'required|numeric|min:0',
                'discount_amount' => 'nullable|numeric|min:0',
                'discount_reason' => 'required_if:discount_amount,>,0.1',
                'payment_method' => 'required',
                'payment_date' => 'required|date',
            ]);

            {
                Transaction::insert([
                    "total_amount"=>$req->payment_amount,
                    "payable_amount"=>$req->payment_amount,
                    "transaction_id"=>StaffPortalHelper::NewTransactionId(),
                    // "discount"=>$req->discount_amount,
                    // "discount_reason"=>$req->discount_reason,
                    // "discount_remarks"=>$req->discount_notes,
                    "payment_id"=>$req->payment_remarks,
                    "hospital_id"=>StaffPortalHelper::GetHospitalId(),
                    "payment_mode"=>$req->payment_method,
                    // "discount"=>$req->discount_amount,
                    "status"=>"Credit",
                    'time' => strtotime($req->payment_date),
                    "remarks"=>"payment_remarks",
                ]);
                Balance::insert([
                    'ipd_id' => decrypt($req->ipd_id),
                    'amount' => $req->payment_amount,
                    'status' => 'Credit',
                    "payable_amount"=>$req->payment_amount,
                    // "discount"=>$req->discount_amount,
                    'transaction_id' => Transaction::max("id"),
                    'remarks' => $req->payment_remarks,
                    'time' => strtotime($req->payment_date),
                    'created_by' => StaffPortalHelper::GetUserId(),
                ]);
            }

            return json_encode(["msg"=>"Payment Recorded Successfuly.", "color"=>"success", "icon"=>"check-circle"]);
        }
    }
    function VitalRecordPrint(Request $req){

        $vital_id = Crypt::decrypt($req->vital);
        $vital = VitalRecord::findOrFail($vital_id);
        $ipd = Ipd::findOrFail($vital_id);
        return view("print.ipd.vital", [
            "latestVital"=>$vital,
            "details"=>Ipd::find($vital->ipd_id),
            "hospital"=>Hospital::find(StaffPortalHelper::GetHospitalId()),
        ]);
    }
    function BalancePrint(Request $req){
         $balance_id = Crypt::decrypt($req->tid);
        $balance = Balance::findOrFail($balance_id);
        $ipd = Ipd::findOrFail($balance->ipd_id);
        return view("print.ipd.transaction", [
            "balance"=>$balance,
            "details"=>$ipd,
            "hospital"=>Hospital::find(StaffPortalHelper::GetHospitalId()),
            "layout"=>$req->layout ?? 1,
        ]);
    }
    function VitalRecordDelete(Request $req){
        try {
            $vital_id = Crypt::decrypt($req->vital);
            if(VitalRecord::where("id", $vital_id)->delete()){
                return json_encode(["msg"=>"Vital Record Deleted.", "color"=>"success", "icon"=>"check-circle"]);
            }else{
                return abort(403, json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
            }
        } catch (\Throwable $th) {
            return abort(403, json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
        }
    }
    function LabInvestigationSave(Request $req){
        try {
            $ipd = Ipd::findOrFail(Crypt::decrypt($req->ipd_id));
            $data = [
                'ipd_id' => $ipd->id,
                'hospital_id' => StaffPortalHelper::GetHospitalId(),
                'patient_id' => $ipd->patient_id,
                'time' => $req->time,
                'date' => $req->date,
                'priority' => $req->priority,
                'created_by' => StaffPortalHelper::GetUserId(),
                'created_at' => now(),
                'status' => 1,
            ];
            if(PatientTest::insert($data)){
                $test = Test::findOrFail(Crypt::decrypt($req->test_name));
                PatientTestRow::insert([
                        "hospital_id"=>StaffPortalHelper::GetHospitalId(),
                        "test_name"=> $test->name,
                        "test_id"=> $test->id,
                        "lab_room_id"=> Crypt::decrypt($req->lab_room_id),
                        "receipt_id"=> PatientTest::max("id"),
                        "qty"=> $req->qty,
                        "price"=> $test->rate,
                        "status"=>"Pending",
                        "created_by"=> StaffPortalHelper::GetUserId(),
                        "created_at"=> now(),
                    ]);
                    Balance::insert([
                        "ipd_id"=>$ipd->id,
                        "amount"=>$test->rate * $req->qty,
                        "payable_amount"=>$test->rate * $req->qty,
                        "status"=>"Debit",
                        "time"=>strtotime($req->date . " " . $req->time),
                        "created_by"=>StaffPortalHelper::GetUserId(),
                    ]);
                return json_encode(["msg"=>"Lab Investigation Requested.", "color"=>"success", "icon"=>"check-circle"]);
            }else{
                return abort(403, json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
            }
        } catch (\Throwable $th) {
            return abort(403, json_encode(["msg"=>"$th Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
        }
    }
     function LabInvestigationFetch(Request $req){
        try {
            $ipd_id = Crypt::decrypt($req->ipd_id);
            $tests = PatientTest::where("ipd_id", $ipd_id)
                ->orderBy("created_at", "desc")
                ->get()
                ->map(function($test){
                    $test->date = date("d M Y", strtotime($test->date));
                    $test->time = date("h:i A", strtotime($test->time));
                    $test->encrypted_id = Crypt::encrypt($test->id);
                    $test->test_rows = $test->test_rows; 
                    return collect($test)->except(['id', "ipd_id", "opd_id", "hospital_id", "patient_id"])->all();
                });
            return response()->json([
                'success' => true,
                'data' => $tests,
            ]);
        } catch (\Throwable $th) {
            return abort(403, json_encode(["msg"=>"$th Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
        }
    }
}

