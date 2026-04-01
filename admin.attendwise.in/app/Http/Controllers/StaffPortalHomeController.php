<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Models\Opd;
use App\Models\Ipd;
use App\Models\Doctor;
use App\Models\Disease;
use App\Models\Patient;
use App\Models\Symptom;
use App\Models\TpaPanel;
use App\Models\Department;
use App\Models\Transaction;
use App\Models\StaffCategory;
use App\Http\Controllers\StaffPortalHelper;
use App\Models\DoctorScheduleSlot;
use App\Http\Controllers\Controller;
use App\Models\PatientTest;
use App\Models\PatientTestRow;
use App\Models\Test;
use Illuminate\Support\Facades\Schema;


class StaffPortalHomeController extends Controller
{
    public function index(){
        $data = [
            "hospital"=>Hospital::find(Crypt::decrypt(Session::get("hospital_id"))),
        'opdCount' => Opd::whereDate('created_at', today())->count(),
        'ipdCount' => Ipd::count(),
        'labTestCount' => Test::whereDate('created_at', today())->count(),
        'appointmentsToday' =>10,
        'newPatientsToday' => Patient::whereDate('created_at', today())->count(),
        'dischargedCount' => Ipd::whereDate('updated_at', today())->count(),
        'totalCollection' => Transaction::sum('total_amount'),
        'recentAdmissions' => Ipd::latest()->take(5)->get(),
        'opdDoctors' => 5,
        'ipdDoctors' => 3,
        'labTechs' => 2,
        'opdRevenue' => 25000,
        'ipdRevenue' => 42000,
        'labRevenue' => 15000,
        ];
        return view("home", $data);
    }
}
