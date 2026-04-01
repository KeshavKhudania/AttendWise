<x-structure />
<x-header heading="{{$title}}"/>
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body" style="padding:0 !important;">
        <form action="{{$action}}" method="POST" id="mainForm" data-form-type="{{$type}}">
            <input type="hidden" name="opdid" value="{{$opdid}}">
            @csrf
            <div class="container-fluid" style="padding:0 !important;">
                <div class="row">
                    <div class="col-md-2 card-section">
                        <div class="container-fluid">
                            <div class="form-group">
                                        <label for="patient_image">Patient Image</label>
                                        <br>
                                        <label for="patient_image" class="p-2 border">
                                            <img src="assets/svg/patient.svg" src-def="assets/svg/patient.svg" alt="" width="125px" height="175px">
                                        </label>
                                        <input type="file" accept="image/*" capture="enviorment" class="form-control" hidden="" name="patient_image" id="patient_image" value="" onchange="previewImage(this)">
                                    </div>
                                </div>            
                    </div>
                    <div class="col-md-10 card-section">
                        <div class="container-fluid patientDetailContainer" >
                            <div class="row">
                            <div class="col-md-12 mb-2">
                            <h4 class="border-bottom pb-1 d-inline-block border-2 border-dark">Patient Details</h4>
                                    <button class="btn btn-sm float-end btn-primary-outlined" type="button" data-bs-toggle="collapse" data-bs-target="#patientSearchPanel" ><i class="fas fa-filter"></i> Filter</button>
                                </div>
                                <div class="col-md-12 collapse mb-2 px-0 border-0 border-bottom" id="patientSearchPanel">
                                    <div class="container-fluid py-0 px-3 border-0">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="uhid">UHID</label>
                                                    <input placeholder="UHID" type="number" class="form-control" id="search_patient_uhid">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="mobile">Mobile</label>
                                                    <input placeholder="Mobile" type="number" class="form-control" id="search_patient_mobile">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group pt-4">
                                                    <button class="btn btn-primary-outlined" type="button" id="fetchPatient"><i class="fas fa-search"></i> Search Patient</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    
                                    <div class="form-group">
                                        <label for="first_name">First Name</label>
                                        <input placeholder="First Name" type="text" class="form-control" name="first_name" id="first_name" value="{{$patient['first_name']}}" {{ $fields['ipd_status'] == "Completed" ? "readonly":"" }} required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" placeholder="Last Name" class="form-control" name="last_name" id="last_name" value="{{$patient['last_name']}}" {{ $fields['ipd_status'] == "Completed" ? "readonly":"" }}>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="father_name">Father Name</label>
                                        <input type="text" placeholder="Father Name" class="form-control" name="father_name" id="father_name" value="{{$patient['father_name']}}" {{ $fields['ipd_status'] == "Completed" ? "readonly":"" }}>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input placeholder="Email" type="email" class="form-control" name="email" id="email" value="{{$patient['email']}}" {{ $fields['ipd_status'] == "Completed" ? "readonly":"" }}>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="phone_number">Mobile</label>
                                        <input placeholder="Mobile" type="text" class="form-control" name="phone_number" id="phone_number" value="{{$patient['phone_number']}}" {{ $fields['ipd_status'] == "Completed" ? "readonly":"" }} required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="abha_id">ABHA ID</label>
                                        <input placeholder="ABHA ID" type="text" class="form-control" name="abha_id" id="abha_id" value="{{$patient['abha_id']}}" {{ $fields['ipd_status'] == "Completed" ? "readonly":"" }}>
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="adhaar_number">Adhaar Number</label>
                                        <input type="tel" pattern="[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{4}" placeholder="XXXX-XXXX-XXXX" class="form-control" name="adhaar_number" id="adhaar_number" value="{{$patient['adhaar_number']}}" {{ $fields['ipd_status'] == "Completed" ? "readonly":"" }}>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="gender">Gender</label>
                                        <select placeholder="Gender" name="gender" {{ $fields['ipd_status'] == "Completed" ? "readonly":"" }} id="gender" class="form-control form-select" required>
                                          <option value="Male">Male</option>  
                                          <option value="Female" {{$patient['gender'] == "Female" ? "selected":""}}>Female</option>  
                                          <option value="Other" {{$patient['gender'] == "Other" ? "selected":""}}>Other</option>  
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="date_of_birth">Date of birth</label>
                                        <input placeholder="Date of birth" {{ $fields['ipd_status'] == "Completed" ? "readonly":"" }} type="date" class="form-control" name="date_of_birth" id="date_of_birth" value="{{$patient['date_of_birth']}}" required>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="blood_group">Blood Group</label>
                                        <select placeholder="Gender" name="blood_group" {{ $fields['ipd_status'] == "Completed" ? "readonly":"" }} id="blood_group" class="form-control form-select">
                                            <option value="" {{$patient['blood_group'] == null?"selected":""}}>Select Blood Group</option>
                                            <option value="O+" {{$patient['blood_group'] == "O+"?"selected":""}}>O+</option>
                                            <option value="A+" {{$patient['blood_group'] == "A+"?"selected":""}}>A+</option>
                                            <option value="B+" {{$patient['blood_group'] == "B+"?"selected":""}}>B+</option>
                                            <option value="AB+ {{$patient['blood_group'] == "AB+"?"selected":""}}">AB+</option>
                                            <option value="O-" {{$patient['blood_group'] == "O-"?"selected":""}}>O-</option>
                                            <option value="A-" {{$patient['blood_group'] == "A-"?"selected":""}}>A-</option>
                                            <option value="B-" {{$patient['blood_group'] == "B-"?"selected":""}}>B-</option>
                                            <option value="AB-" {{$patient['blood_group'] == "AB-"?"selected":""}}>AB-</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" placeholder="Address" {{ $fields['ipd_status'] == "Completed" ? "readonly":"" }} class="form-control" name="address" id="address" value="{{$patient['address']}}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="tpa_panel_id">TPA/Panel</label>
                                        <select placeholder="TPA/Panel" name="tpa_panel_id" id="tpa_panel_id" class="form-control form-select">
                                            <option value="" data-panel-id="0" {{$fields['tpa_panel_id'] == null?"selected":""}}>N/A</option>
                                            @foreach ($tpa_panels as $item)
                                                <option value="{{$item->encrypted_id}}" {{$fields['tpa_panel_id'] == $item->id?"selected":""}} data-panel-id="{{ $item->panel_id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="panel_card_number">Panel Card Number</label>
                                        <input type="text" placeholder="Panel Card Number" class="form-control tpa_panel_fields" name="panel_card_number" id="panel_card_number" {{ $fields['panel_card_number'] != null ?:"readonly" }} value="{{$fields['panel_card_number']}}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="panel_service_number">Panel Service Number</label>
                                        <input type="text" placeholder="Panel Service Number" class="form-control tpa_panel_fields" name="panel_service_number" {{ $fields['panel_service_number'] != null ?:"readonly" }} id="panel_service_number" value="{{$fields['panel_service_number']}}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="panel_rank">Rank</label>
                                        <input type="text" placeholder="Rank" class="form-control tpa_panel_fields" name="panel_rank" id="panel_rank" value="{{$fields['panel_rank']}}" {{ $fields['panel_rank'] != null ?:"readonly" }}>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 card-section mt-3">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <h4 class="border-bottom pb-1 d-inline-block border-2 border-dark">IPD Details</h4>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="department_id">Department</label>
                                        <select placeholder="Department" {{ $fields['ipd_status'] == "Completed"?"disabled":"" }} name="department_id" id="department_id" class="form-control form-select" required>
                                            @if ($fields['department_id'] == null)
                                            <option disabled selected>Select Department</option>  
                                            @endif
                                          @foreach ($departments as $item)
                                            <option value="{{Crypt::encrypt($item->id)}}" {{$fields['department_id'] == $item->id ? "selected":""}}>{{$item->name}}</option>  
                                          @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="doctor_id">Doctor</label>
                                        <select placeholder="Doctor" name="doctor_id" id="doctor_id" class="form-control form-select" {{ $fields['ipd_status'] == "Completed"?"disabled":"" }} required>
                                          @foreach ($doctors as $item)
                                            <option value="{{Crypt::encrypt($item->id)}}" data-fee="{{ $item->consulting_fee }}" {{$fields['doctor_id'] == $item->id ? "selected":""}}>Dr.{{$item->name}}</option>
                                          @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="arrival_date">Arrival Date</label>
                                        <input type="date" placeholder="Arrival Date" {{ $fields['ipd_status'] == "Completed"?"disabled":"" }} class="form-control" name="arrival_date" id="arrival_date" value="{{$fields['arrival_date'] ?? date("Y-m-d")}}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="arrival_time">Arrival Time</label>
                                        <input type="time" placeholder="Arrival Time" {{ $fields['ipd_status'] == "Completed"?"disabled":"" }} class="form-control" name="arrival_time" id="arrival_time" value="{{$fields['arrival_time'] ?? date("H:i")}}" required>
                                    </div>
                                </div>
                                {{-- <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="appointment_time">Time Slot</label>
                                        <select placeholder="Time Slot" name="appointment_time" id="appointment_time" {{ $fields['ipd_status'] == "Completed"?"disabled":"" }} class="form-control form-select" required>
                                            @foreach (($time_slots) as $item)
                                                <option value="{{Crypt::encrypt($item->id)}}" {{$fields['appointment_slot_id'] == $item->id ? "selected":""}}>{{ $item->shift }} ({{ $item->start_time }} - {{ $item->end_time }})</option>
                                          @endforeach
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="reference">Reference</label>
                                        <select placeholder="Reference" name="reference" id="reference" class="form-control form-select" {{ $fields['ipd_status'] == "Completed"?"disabled":"" }} >
                                            <option value="" {{ $fields['reference'] == null ? "selected":"" }}>N/A</option>
                                            <option value="Self" {{ $fields['reference'] == "Self" ? "selected":"" }}>Self</option>
                                            <option value="Family" {{ $fields['reference'] == "Family" ? "selected":"" }}>Family</option>
                                            <option value="Friend" {{ $fields['reference'] == "Friend" ? "selected":"" }}>Friend</option>
                                            @foreach ($reference_hospitals as $item)
                                                <option value="{{Crypt::encrypt($item->id)}}" {{$fields['reference'] == $item->id ? "selected":""}}>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="ipd_status">Status</label>
                                        <select placeholder="Status" name="ipd_status" id="ipd_status" class="form-control form-select" required>
                                         <option value="Admitted">Admitted</option>
                                        <option value="Transferred" {{$fields['ipd_status'] == "Transferred"?"selected":""}}>Transferred</option>
                                        <option value="Under Observation" {{$fields['ipd_status'] == "Under Observation"?"selected":""}}>Under Observation</option>
                                        <option value="In Surgery" {{$fields['ipd_status'] == "In Surgery"?"selected":""}}>In Surgery</option>
                                        <option value="Post-Operative" {{$fields['ipd_status'] == "Post-Operative"?"selected":""}}>Post-Operative</option>
                                        <option value="ICU" {{$fields['ipd_status'] == "ICU"?"selected":""}}>ICU</option>
                                        <option value="Recovered" {{$fields['ipd_status'] == "Recovered"?"selected":""}}>Recovered</option>
                                        <option value="Discharged" {{$fields['ipd_status'] == "Discharged"?"selected":""}}>Discharged</option>
                                        <option value="Deceased" {{$fields['ipd_status'] == "Deceased"?"selected":""}}>Deceased</option>
                                        <option value="LAMA" {{$fields['ipd_status'] == "LAMA"?"selected":""}}>Left Against Medical Advice (LAMA)</option>
                                        <option value="Referred" {{$fields['ipd_status'] == "Referred"?"selected":""}}>Referred</option>
                                        <option value="Cancelled" {{$fields['ipd_status'] == "Cancelled"?"selected":""}}>Cancelled</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="source">Source</label>
                                        <select placeholder="Source" name="source" id="source" class="form-control form-select">
                                            <option selected>Select Source</option>
                                            <option value="Walk-in" {{$fields['source'] =="Walk-in"?"selected":""}}>Walk-in</option>
                                            <option value="Referred from OPD" {{$fields['source'] =="Referred from OPD"?"selected":""}}>Referred from OPD</option>
                                            <option value="Referral Hospital" {{$fields['source'] =="Referral Hospital"?"selected":""}}>Referral Hospital</option>
                                            <option value="Referred by Doctor" {{$fields['source'] =="Referred by Doctor"?"selected":""}}>Referred by Doctor</option>
                                            <option value="Brought by Ambulance" {{$fields['source'] =="Brought by Ambulance"?"selected":""}}>Brought by Ambulance</option>
                                            <option value="Transfer from ICU" {{$fields['source'] =="Transfer from ICU"?"selected":""}}>Transfer from ICU</option>
                                            <option value="Emergency Department" {{$fields['source'] =="Emergency Department"?"selected":""}}>Emergency Department</option>
                                            <option value="Online Booking" {{$fields['source'] =="Online Booking"?"selected":""}}>Online Booking</option>
                                            <option value="Corporate/TPA" {{$fields['source'] =="Corporate/TPA"?"selected":""}}>Corporate/TPA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="admission_type">Admission Type</label>
                                        <select placeholder="Admission Type" name="admission_type" id="admission_type" class="form-control form-select" required>
                                            <option value="Emergency" {{$fields['admission_type'] == "Emergency"?"selected":""}}>Emergency</option>
                                            <option value="Elective" {{$fields['admission_type'] == "Elective"?"selected":""}}>Elective</option>
                                            <option value="Daycare" {{$fields['admission_type'] == "Daycare"?"selected":""}}>Daycare</option>
                                            <option value="Observation" {{$fields['admission_type'] == "Observation"?"selected":""}}>Observation</option>
                                            <option value="Routine" {{$fields['admission_type'] == "Routine"?"selected":""}}>Routine</option>
                                            <option value="Follow-up Admission" {{$fields['admission_type'] == "Follow-up Admission"?"selected":""}}>Follow-up Admission</option>
                                            <option value="Transfer In" {{$fields['admission_type'] == "Transfer In"?"selected":""}}>Transfer In</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <h4 class="border-bottom pb-1 d-inline-block border-2 border-dark">Symptoms & Disease</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="symptoms_selector">Select Symptoms</label>
                                        <select placeholder="Select Symptoms" {{ $fields['ipd_status'] != "Completed"?:"disabled" }} id="symptoms_selector" class="form-control multi_msc_selector form-select" data-msc-name="symptoms" data-msc-target="symptoms_container">
                                            <option >Add Symptoms</option>
                                            @foreach ($symptoms as $item)
                                                <option value="{{ $item->name }}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="msc-symptoms-container" id="symptoms_container">
                                        @foreach ($fields['symptoms']?$fields['symptoms'] :[] as $item)
                                        <h5 class="badge bg-warning me-1">{{$item}} <button type="button" class='btn mscRemoveSelectedOption btn-close btn-sm p-0 ms-1'></button> <input type="hidden" name="symptoms[]" value="{{$item}}"></h5>
                                            @endforeach
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="diseases_selector">Select Disease</label>
                                        <select placeholder="Select Disease" {{ $fields['ipd_status'] != "Completed"?:"disabled" }} id="diseases_selector" class="form-control multi_msc_selector form-select"  data-msc-name="disease" data-msc-target="disease_container">
                                            <option >Add Disease</option>
                                            @foreach ($diseases as $item)
                                                <option value="{{ $item->name }}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="msc-diseases-container" id="disease_container">
                                        @foreach ($fields['disease'] ?$fields['disease']: [] as $item)
                                            <h5 class="badge bg-warning me-1">{{$item}} <button type="button" class='btn mscRemoveSelectedOption btn-close btn-sm p-0 ms-1'></button> <input type="hidden" name="disease[]" value="{{$item}}"></h5>
                                            @endforeach
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="provisional_diagnosis">Select Provisional Diagnosis</label>
                                        <select placeholder="Select Provisional Diagnosis" {{ $fields['ipd_status'] != "Completed"?:"disabled" }} id="provisional_diagnosis" class="form-control multi_msc_selector form-select" data-msc-name="provisional_diagnosis" data-msc-target="provisional_diagnosis_container">
                                            <option disabled selected>Select Provisional Treatment</option>
                                            <option value="Stroke (CVA) – Suspected Ischemic">Stroke (CVA) – Suspected Ischemic</option>
                                            <option value="Transient Ischemic Attack (TIA)">Transient Ischemic Attack (TIA)</option>
                                            <option value="Seizure Disorder">Seizure Disorder</option>
                                            <option value="Meningitis">Meningitis</option>
                                            <option value="Encephalopathy">Encephalopathy</option>
                                            {{-- @foreach ($diseases as $item)
                                                <option value="{{ $item->name }}">{{$item->name}}</option>
                                            @endforeach --}}
                                        </select>
                                    </div>
                                    <div class="msc-diseases-container" id="provisional_diagnosis_container">
                                        @foreach ($fields['provisional_diagnosis'] ?$fields['provisional_diagnosis']: [] as $item)
                                            <h5 class="badge bg-warning me-1">{{$item}} <button type="button" class='btn mscRemoveSelectedOption btn-close btn-sm p-0 ms-1'></button> <input type="hidden" name="provisional_diagnosis[]" value="{{$item}}"></h5>
                                            @endforeach
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="procedure_treatment">Treatment Procedure</label>
                                        <input type="text" placeholder="Treatment Procedure" class="form-control" name="procedure_treatment" id="procedure_treatment" value="{{$fields['procedure_treatment']}}" >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 card-section mt-3">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <h4 class="border-bottom pb-1 d-inline-block border-2 border-dark">Room & Bed Allocation</h4>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="room_category_id">Room Category</label>
                                        <select placeholder="Room Category" name="room_category_id" id="room_category_id" class="form-control form-select" required
                                        onfocus="fetchRelatedList({
                                            'data': {
                                            'category_id': this.value,
                                            },
                                            'target': '#room_number_id',
                                            'url': '{{ route('api.fetch.rooms') }}'
                                        })"
                                        onchange="fetchRelatedList({
                                            'data': {
                                            'category_id': this.value,
                                            },
                                            'target': '#room_number_id',
                                            'url': '{{ route('api.fetch.rooms') }}'
                                        }) ">
                                           @foreach ($room_categories as $item)
                                               <option value="{{Crypt::encrypt($item->id)}}" {{$item->id == $fields['room_category_id'] ? "selected":""}}>{{$item->name}}</option>
                                           @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="room_number_id">Select Room</label>
                                        <select placeholder="Room" name="room_number_id" id="room_number_id" class="form-control form-select" required 
                                        onfocus="fetchRelatedList({
                                            'data': {
                                            'room_id': this.value,
                                            'bed_type_id': $('#bed_type_id').val(),
                                            },
                                            'target': '#bed_id',
                                            'url': '{{ route('api.fetch.beds') }}'
                                        })" oninput="fetchRelatedList({
                                            'data': {
                                            'room_id': this.value,
                                            'bed_type_id': $('#bed_type_id').val(),
                                            },
                                            'target': '#bed_id',
                                            'url': '{{ route('api.fetch.beds') }}'
                                        })">
                                           @foreach ($rooms as $item)
                                               <option value="{{Crypt::encrypt($item->id)}}" {{$item->id == $fields['room_number_id'] ? "selected":""}}>{{$item->name}}</option>
                                           @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="bed_type_id">Select Bed Type</label>
                                        <select placeholder="Bed Type" name="bed_type_id" id="bed_type_id" class="form-control form-select" required onchange="fetchRelatedList({
                                            'data': {
                                            'room_id': $('#room_number_id').val(),
                                            'bed_type_id': this.value,
                                            },
                                            'target': '#bed_id',
                                            'url': '{{ route('api.fetch.beds') }}'
                                        })"
                                        onfocus="fetchRelatedList({
                                            'data': {
                                            'room_id': $('#room_number_id').val(),
                                            'bed_type_id': this.value,
                                            },
                                            'target': '#bed_id',
                                            'url': '{{ route('api.fetch.beds') }}'
                                        })">
                                           @foreach ($bed_types as $item)
                                               <option value="{{Crypt::encrypt($item->id)}}" {{$item->id == $fields['bed_type_id'] ? "selected":""}}>{{$item->category_name}}</option>
                                           @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="bed_id">Select Bed</label>
                                        <select placeholder="Bed Number" name="bed_id" id="bed_id" class="form-control form-select" required>
                                           @foreach ($beds as $item)
                                               <option value="{{Crypt::encrypt($item->id)}}" {{$item->id == $fields['bed_id'] ? "selected":""}}>{{$item->category_name}}</option>
                                           @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <h4 class="border-bottom pb-1 d-inline-block border-2 border-dark">Guardian Details</h4>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="guardian_name">Guardian Name</label>
                                        <input type="text" placeholder="Guardian Name" class="form-control" name="guardian_name" id="guardian_name" value="{{$fields['guardian_name']}}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="guardian_mobile">Guardian Mobile</label>
                                        <input type="number" placeholder="Guardian Mobile" class="form-control" name="guardian_mobile" id="guardian_mobile" value="{{$fields['guardian_mobile']}}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="guardian_address">Guardian Address</label>
                                        <input type="text" placeholder="Guardian Address" class="form-control" name="guardian_address" id="guardian_address" value="{{$fields['guardian_address']}}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="guardian_relation">Guardian Relation</label>
                                        <select placeholder="" name="guardian_relation" id="guardian_relation" class="form-control form-select" required>
                                                <option disabled selected>Select Relation</option>
                                                <option value="Father" {{$fields['guardian_relation'] == "Father"?"selected":""}}>Father</option>
                                                <option value="Mother" {{$fields['guardian_relation'] == "Mother"?"selected":""}}>Mother</option>
                                                <option value="Brother" {{$fields['guardian_relation'] == "Brother"?"selected":""}}>Brother</option>
                                                <option value="Sister" {{$fields['guardian_relation'] == "Sister"?"selected":""}}>Sister</option>
                                                <option value="Husband" {{$fields['guardian_relation'] == "Husband"?"selected":""}}>Husband</option>
                                                <option value="Wife" {{$fields['guardian_relation'] == "Wife"?"selected":""}}>Wife</option>
                                                <option value="Uncle" {{$fields['guardian_relation'] == "Uncle"?"selected":""}}>Uncle</option>
                                                <option value="Aunt" {{$fields['guardian_relation'] == "Aunt"?"selected":""}}>Aunt</option>
                                                <option value="Grandfather" {{$fields['guardian_relation'] == "Grandfather"?"selected":""}}>Grandfather</option>
                                                <option value="Grandmother" {{$fields['guardian_relation'] == "Grandmother"?"selected":""}}>Grandmother</option>
                                                <option value="Son" {{$fields['guardian_relation'] == "Son"?"selected":""}}>Son</option>
                                                <option value="Daughter" {{$fields['guardian_relation'] == "Daughter"?"selected":""}}>Daughter</option>
                                                <option value="Cousin" {{$fields['guardian_relation'] == "Cousin"?"selected":""}}>Cousin</option>
                                                <option value="Nephew" {{$fields['guardian_relation'] == "Nephew"?"selected":""}}>Nephew</option>
                                                <option value="Niece" {{$fields['guardian_relation'] == "Niece"?"selected":""}}>Niece</option>
                                                <option value="Other" {{$fields['guardian_relation'] == "Other"?"selected":""}}>Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                    
                    <div class="col-md-12 card-section mt-3">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <h4 class="border-bottom pb-1 d-inline-block border-2 border-dark">Insurance Details</h4>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="has_insurance">Has Insurance?</label>
                                        <select placeholder="Has Insurance?" name="has_insurance" id="has_insurance" class="form-control form-select" required>
                                            <option value="No" {{$fields['has_insurance'] == "No" ? "selected":""}}>No</option>  
                                            <option value="Yes" {{$fields['has_insurance'] == "Yes" ? "selected":""}}>Yes</option>  
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="insurance_company">Insurance Provider</label>
                                        <select placeholder="Insurance Provider" name="insurance_company" id="insurance_company" class="form-control form-select" >
                                            <option value="" {{$fields['insurance_company'] == null ? "selected":""}}>Select Insurance Provider</option>
                                            @foreach ($insurance_providers as $item)
                                                <option value="{{Crypt::encrypt($item->id)}}" {{$fields['insurance_company'] == $item->id ? "selected":""}}>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="policy_no">Policy Number</label>
                                        <input type="text" placeholder="Policy Number" class="form-control" name="policy_no" id="policy_no" value="{{$fields['policy_no']}}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="payer_name">Payer Name</label>
                                        <input type="text" placeholder="Payer Name" class="form-control" name="payer_name" id="payer_name" value="{{$fields['payer_name']}}">
                                    </div>
                                </div>
                                <x-form-buttons />
                            </div>  
                        </div>
                    </div>
                    
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
  <div class="modal fade" id="PatientListModel" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body py-3">
                <h4 class="modal-title">Select Patient

                    <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </h4>
                <table class="table">
                    <tbody class="patientSelectListContainer">
                        <tr></tr>
                    </tbody>
                </table>
                {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
            </div>
        </div>
    </div>
  </div>
  <script>
    $("#fetchPatient").click(function () {
    $.ajax({
        type: "POST",
        url: "staff-portal/fetch/opd/patient",
        data: {
            "_token": $("meta[name=csrf]").attr("content"),
            "uhid": $("#search_patient_uhid").val(),
            "mobile": $("#search_patient_mobile").val(),
        },
        beforeSend: function () {
            $("#fetchPatient").html("<i class='fas fa-spinner fa-spin'></i> Please Wait").attr("disabled", true);
        },
        success: function (response) {
            $("#fetchPatient").html("<i class='fas fa-search'></i> Search Patient").removeAttr("disabled");

            const result = JSON.parse(response);
            $("#PatientListModel").modal("show");
            $(".patientSelectListContainer").html("");

            if (result.uhid_results.length === 0 && result.mobile_results.length === 0) {
                $(".patientSelectListContainer").html("<tr><td class='text-danger'>No Records Found:</td></tr>");
            }

            // Render UHID Results
            if (result.uhid_results.length !== 0) {
                $(".patientSelectListContainer").append("<tr><td colspan='12' class='text-danger'>UHID Search Result:</td></tr>");
                $(".patientSelectListContainer").append(getTableHeader());

                result.uhid_results.forEach(element => {
                    $(".patientSelectListContainer").append(getPatientRow(element));
                });
            }

            // Render Mobile Results
            if (result.mobile_results.length !== 0) {
                $(".patientSelectListContainer").append("<tr><td colspan='12' class='text-danger'>Mobile Search Results:</td></tr>");
                $(".patientSelectListContainer").append(getTableHeader());

                result.mobile_results.forEach(element => {
                    $(".patientSelectListContainer").append(getPatientRow(element));
                });
            }
        }
    });
});

// Table header HTML
function getTableHeader() {
    return `<tr>
        <td>Image</td>
        <td>UHID</td>
        <td>First Name</td>
        <td>Last Name</td>
        <td>Father Name</td>
        <td>Gender</td>
        <td>Date Of Birth</td>
        <td>Email</td>
        <td>Mobile</td>
        <td>Blood Group</td>
        <td>Address</td>
        <td>Adhaar Number</td>
        <td>ABHA ID</td>
    </tr>`;
}

// Table row HTML
function getPatientRow(element) {
    return `<tr class="patientSelectListRow">
        <td><img src="../hospit_images/${element.image}" style="width: 50px !important; aspect-ratio:1/1 !important; height: auto !important;" class="rounded-circle"></td>
        <td msc-val="${element.uhid ?? ""}">${element.uhid}</td>
        <td msc-val="${element.first_name ?? ""}">${element.first_name}</td>
        <td msc-val="${element.last_name ?? ""}">${element.last_name ?? "--"}</td>
        <td msc-val="${element.father_name ?? ""}">${element.father_name ?? "--"}</td>
        <td msc-val="${element.gender ?? ""}">${element.gender}</td>
        <td msc-val="${element.date_of_birth ?? ""}">${element.date_of_birth}</td>
        <td msc-val="${element.email ?? ""}">${element.email ?? "--"}</td>
        <td msc-val="${element.phone_number ?? ""}">${element.phone_number ?? "--"}</td>
        <td msc-val="${element.blood_group ?? ""}">${element.blood_group ?? "--"}</td>
        <td msc-val="${element.address ?? ""}">${element.address ?? "--"}</td>
        <td msc-val="${element.adhaar_number ?? ""}">${element.adhaar_number ?? "--"}</td>
        <td msc-val="${element.abha_id ?? ""}">${element.abha_id}</td>
    </tr>`;
}

// On row click, populate form
$(document).on("click", ".patientSelectListRow", function () {
    const $tds = $(this).children("td");

    $(".patientDetailContainer #first_name").val($tds[2].getAttribute("msc-val"));
    $(".patientDetailContainer #last_name").val($tds[3].getAttribute("msc-val"));
    $(".patientDetailContainer #father_name").val($tds[4].getAttribute("msc-val"));

    const gender = $tds[5].getAttribute("msc-val");
    if (gender) {
        $(".patientDetailContainer #gender").val(gender);
    }

    $(".patientDetailContainer #date_of_birth").val($tds[6].getAttribute("msc-val"));
    $(".patientDetailContainer #email").val($tds[7].getAttribute("msc-val"));
    $(".patientDetailContainer #phone_number").val($tds[8].getAttribute("msc-val"));

    const bloodGroup = $tds[9].getAttribute("msc-val");
    if (bloodGroup) {
        $(".patientDetailContainer #blood_group").val(bloodGroup);
    }

    $(".patientDetailContainer #address").val($tds[10].getAttribute("msc-val"));
    $(".patientDetailContainer #adhaar_number").val($tds[11].getAttribute("msc-val"));
    $(".patientDetailContainer #abha_id").val($tds[12].getAttribute("msc-val"));

    $("#PatientListModel").modal("hide");
});

    $("#department_id").change(function(){
        const D_ID = $(this).val();
        $.ajax({
            type: "POST",
            url: "staff-portal/fetch/opd/doctor",
            data: {
                "_token":$("meta[name=csrf]").attr("content"),
                "department":D_ID,
            },
            success: function (response) {
                $("#doctor_id").html("");
                const result = (response);
                if (result.length == 0) {
                    $("#doctor_id").html(`<option disabled selected>No Doctor Found</option>`)
                }
                result.forEach((doctor)=>{
                    $("#doctor_id").append(`<option value='${doctor.id}' data-fee="${doctor.consulting_fee}">${doctor.name}</option>`)
                    CheckDoctorAvail(); 
                });
                UpdateFee();
                $("#payable_amount").val($("option[value="+$("#doctor_id").val()+"]").attr("data-fee"))
            }
        });
    });
    $("#discount").keyup(function(){
        const total = $("#payment_amount").val();
        if($(this).val() > 100){
            $(this).val("100")
        }
        if($(this).val() < 0){
            $(this).val("0")
        }
        $("#payable_amount").val(total - ($(this).val()*total)/100);
    })
    $("#payable_amount").keyup(function(){
        const total = $("#payment_amount").val();
        if($(this).val() > total){
            $(this).val(total)
        }
        if($(this).val() < 0){
            $(this).val("0")
        }
        $("#discount").val(100 - ($(this).val()/total)*100);
    })
    $(document).on("input", "#appointment_date, #doctor_id", function(){
        CheckDoctorAvail();
        UpdateFee();
    })
    function UpdateFee(){
        $("#payment_amount").val($("option[value="+$("#doctor_id").val()+"]").attr("data-fee"))
        
        }
    function CheckDoctorAvail(){
        $.ajax({
            type: "POST",
            url: "staff-portal/check/opd/date",
            data: {
                "_token":$("meta[name=csrf]").attr("content"),
                "doctor_id":$("#doctor_id").val(),
                "date":$("#appointment_date").val(),
            },
            beforeSend:function(){
                $("#appointment_time").html("");
            },
            success: function (response) {
                const result = response;
                result.forEach(element => {
                    $("#appointment_time").append(`<option value="${element.id}">${element.shift_name} (${element.start_time} - ${element.end_time})</option>`);
                });
            },
            error: function(err){
                mscToast({
                    msg:err.responseJSON.message,
                    color:"danger",
                    icon:"exclamation-circle",
                });
            }
        });   
    }
    $("#tpa_panel_id").change(function(){
        if ($("#tpa_panel_id option[value="+$(this).val()+"]").attr("data-panel-id") == 1) {
            $(".tpa_panel_fields").removeAttr("readonly")
        }else{
            $(".tpa_panel_fields").val("").attr("readonly", true);
        }
    })
    // let newSymText = "";
    // $("#symptoms_selector").mousedown(function(e){
    //     // e.preventDefault()
    // })
    
    
    
  </script>
<x-footer />