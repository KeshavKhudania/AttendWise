<?php

namespace App\Http\Controllers;

use App\Models\HospitPermission;
use App\Models\Ipd;
use App\Models\Opd;
use App\Models\OperationRecord;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Picqer\Barcode\BarcodeGeneratorPNG;

class StaffPortalHelper extends Controller
{
    public static function NewOpdId(){
        return "OPD".(Opd::where([
            "hospital_id"=>Crypt::decrypt(Session::get("hospital_id")),
        ])->first() == null ? 0 + 101:Opd::where([
            "hospital_id"=>Crypt::decrypt(Session::get("hospital_id")),
        ])->count() + 101);
    }
    public static function NewOperationNumber(){
        return "OP".(OperationRecord::where([
            "hospital_id"=>Crypt::decrypt(Session::get("hospital_id")),
        ])->first() == null ? 0 + 101:OperationRecord::where([
            "hospital_id"=>Crypt::decrypt(Session::get("hospital_id")),
        ])->count() + 101);
    }
    public static function NewIpdId(){
        return "IPD".(Ipd::where([
            "hospital_id"=>Crypt::decrypt(Session::get("hospital_id")),
        ])->first() == null ? 0 + 101:Ipd::where([
            "hospital_id"=>Crypt::decrypt(Session::get("hospital_id")),
        ])->count() + 101);
    }
    public static function NewIpdCaseId(){
        return (Ipd::where([
            "hospital_id"=>Crypt::decrypt(Session::get("hospital_id")),
        ])->first() == null ? 0 + 10101:Ipd::where([
            "hospital_id"=>Crypt::decrypt(Session::get("hospital_id")),
        ])->count() + 10101);
    }
    public static function NewQueueNumber($date = null){
        return (Opd::where([
            "hospital_id"=>Crypt::decrypt(Session::get("hospital_id")),
            "appointment_date"=>$date ?? date("Y-m-d"),
        ])->first() == null ? 0 + 1:(Opd::where([
            "hospital_id"=>Crypt::decrypt(Session::get("hospital_id")),
            "appointment_date"=>$date ?? date("Y-m-d"),
        ])->orderBy("queue_number", "DESC")->first()->queue_number ?? 0) + 1);
    }
    public static function NewTransactionId(){
        return "TXN".time()."-".Transaction::where("hospital_id", Crypt::decrypt(Session::get("hospital_id")))->count() + 1;
    }
    public static function GetHospitalId(){
        return Crypt::decrypt(Session::get("hospital_id"));
    }
    public static function GetUserId(){
        return Crypt::decrypt(Session::get("user_id"));
    }
    public static function NewUHID(){
        return DB::table("patients")->where("hospital_id", StaffPortalHelper::GetHospitalId())->max("uhid")+1;
    }
    public static function GetModules($route){
        // $modules = [];
        $current_id = HospitPermission::where([
            'deleted_at' => null,
            'status'     => 1,
            'route_name'  => $route
        ])->first()->id;
        $modules = HospitPermission::select("route_name", "name")->where([
            'deleted_at' => null,
            'status'     => 1,
            'parent_id'  => $current_id
        ])->orderBy("sort_order", "ASC")->get()->pluck("name", "route_name")->toArray();
        
        return $modules;
    }
    static function GenerateBarcodePNG($code){
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($code, $generator::TYPE_CODE_39);
        header('Content-Type: image/png');
        return "data:image/png;base64,".base64_encode($barcode);
    }
}