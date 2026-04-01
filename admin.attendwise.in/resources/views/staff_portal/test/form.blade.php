<x-structure />
<x-header heading="{{$title}}"/>
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <form action="{{$action}}" method="POST" id="mainForm" data-form-type="{{$type}}">
            @csrf
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 card-section">
                        <div class="container-fluid patientDetailContainer">
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
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="first_name">First Name</label>
                                        <input placeholder="First Name" type="text" class="form-control" name="first_name" id="first_name" value="{{$patient['first_name']}}" {{ $fields['status'] == "Completed" ? "readonly":"" }} required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" placeholder="Last Name" class="form-control" name="last_name" id="last_name" value="{{$patient['last_name']}}" {{ $fields['status'] == "Completed" ? "readonly":"" }}>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="father_name">Father Name</label>
                                        <input type="text" placeholder="Father Name" class="form-control" name="father_name" id="father_name" value="{{$patient['father_name']}}" {{ $fields['status'] == "Completed" ? "readonly":"" }}>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input placeholder="Email" type="email" class="form-control" name="email" id="email" value="{{$patient['email']}}" {{ $fields['status'] == "Completed" ? "readonly":"" }}>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="phone_number">Mobile</label>
                                        <input placeholder="Mobile" type="text" class="form-control" name="phone_number" id="phone_number" value="{{$patient['phone_number']}}" {{ $fields['status'] == "Completed" ? "readonly":"" }} required>
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="adhaar_number">Adhaar Number</label>
                                        <input type="tel" pattern="[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{4}" placeholder="XXXX-XXXX-XXXX" class="form-control" name="adhaar_number" id="adhaar_number" value="{{$patient['adhaar_number']}}" {{ $fields['status'] == "Completed" ? "readonly":"" }}>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="gender">Gender</label>
                                        <select placeholder="Gender" name="gender" {{ $fields['status'] == "Completed" ? "readonly":"" }} id="gender" class="form-control form-select" required>
                                          <option value="Male">Male</option>  
                                          <option value="Female" {{$patient['gender'] == "Female" ? "selected":""}}>Female</option>  
                                          <option value="Other" {{$patient['gender'] == "Other" ? "selected":""}}>Other</option>  
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="date_of_birth">Date of birth</label>
                                        <input placeholder="Date of birth" {{ $fields['status'] == "Completed" ? "readonly":"" }} type="date" class="form-control" name="date_of_birth" id="date_of_birth" value="{{$patient['date_of_birth']}}" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="blood_group">Blood Group</label>
                                        <select placeholder="Gender" name="blood_group" {{ $fields['status'] == "Completed" ? "readonly":"" }} id="blood_group" class="form-control form-select">
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" placeholder="Address" {{ $fields['status'] == "Completed" ? "readonly":"" }} class="form-control" name="address" id="address" value="{{$patient['address']}}">
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
                                    <h4 class="border-bottom pb-1 d-inline-block border-2 border-dark">Test Details</h4>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="date">Date</label>
                                        <input type="date" placeholder="Date" {{ $fields['status'] == "Completed"?"disabled":"" }} class="form-control" name="date" id="date" value="{{$fields['date'] ?? date("Y-m-d")}}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="time">Time</label>
                                        <input type="time" placeholder="Time" {{ $fields['status'] == "Completed"?"disabled":"" }} class="form-control" name="time" id="time" value="{{$fields['time'] ?? date("H:i:s")}}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="reference">Reference</label>
                                        <input type="text" placeholder="Reference" name="reference" id="reference" class="form-control" value="{{ $fields['reference'] }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="priority">Priority</label>
                                        <select placeholder="Priority" name="priority" {{ $fields['status'] == "Completed"?"disabled":"" }} id="priority" class="form-control form-select" required>
                                          <option value="Normal">Normal</option>
                                          <option value="Urgent" {{ $fields['priority'] == "Urgent" ? "selected":"" }}>Urgent</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <table class="table table-bordered border-dark table-sm">
                                        <thead>
                                            <tr>
                                                <td>Test Name</td>
                                                <td>Qty</td>
                                                <td>Rate</td>
                                                <td>Action</td>
                                            </tr>
                                        </thead>
                                        <tbody id="TestRowContainer">
                                            <?php $count = 0;?>
                                            @if(!empty($fields['test_rows']))
    @foreach ($fields['test_rows'] as $item)
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="u_test[]" value="{{$item->encrypted_id}}">
                                                    <select name="u_test_row[{{$count}}][name]" id="u_test_row_{{$count}}_name" class="form-control form-select TestSelector" required data-target="u_test_row[{{$count}}][rate]">
                                                        @foreach ($tests as $test)
                                                            <option value="{{$test->encrypted_id}}"
                                                                    @if($item->test_id == $test->id) selected @endif
                                                                    data-rate="{{$test->rate}}">{{$test->name}}</option>
                                                        @endforeach
                                                    </select>    
                                                </td>
                                                <td>
                                                    <input type="number" placeholder="Quantity" required class="form-control TestSelectorQty" min="1" value="{{$item->qty}}" name="u_test_row[{{$count}}][qty]" id="u_test_row[{{$count}}][qty]">
                                                </td>
                                                <td>
                                                    <input type="number" placeholder="Rate" required class="form-control TestSelectorRate" min="0" name="u_test_row[{{$count}}][rate]" id="test[{{$count++}}][rate]" value="{{$item->price}}">
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger btnRemoveTestRow" type="button">Remove</button>    
                                                </td>
                                            </tr>
                                               @endforeach
@endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4">
                                                    <button class="btn btn-success" id="btnAppendTestRow" type="button"><i class="fas fa-plus"></i> Add Row</button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 card-section mt-3">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <h4 class="border-bottom pb-1 d-inline-block border-2 border-dark">Payment Details</h4>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="payment_mode">Payment Mode</label>
                                        <select placeholder="Payment Mode" name="payment_mode" id="payment_mode" class="form-control form-select" required>
                                            <option value="Cash" {{$transaction['payment_mode'] == "Cash" ? "selected":""}}>Cash</option>  
                                            <option value="UPI" {{$transaction['payment_mode'] == "UPI" ? "selected":""}}>UPI</option>  
                                            <option value="Bank Transfer" {{$transaction['payment_mode'] == "Bank Transfer" ? "selected":""}}>Bank Transfer</option>  
                                            <option value="Cheque" {{$transaction['payment_mode'] == "Cheque" ? "selected":""}}>Cheque</option>  
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="payment_amount">Total Amount</label>
                                        <input type="number" placeholder="Total Amount" class="form-control" name="payment_amount" id="payment_amount" value="{{$transaction['total_amount'] ?? "0"}}" readonly required>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="discount">Discount(%)</label>
                                        <input type="number" min="0" max="100" placeholder="Discount" class="form-control" name="discount" id="discount" value="{{$transaction['discount'] ?? "0"}}" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="payable_amount">Payable Amount</label>
                                        <input type="number" placeholder="Payable Amount" class="form-control" name="payable_amount" id="payable_amount" value="{{$transaction['payable_amount']}}" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="payment_status">Payment Status</label>
                                        <select placeholder="Payment Status" name="payment_status" id="payment_status" class="form-control form-select" required>
                                          <option value="Paid">Paid</option>
                                          <option value="Pending" {{ $transaction['status'] == "Pending" ? "selected":"" }}>Pending</option>
                                          {{-- <option value="Refund" {{ $transaction['status'] == "Refund" ? "selected":"" }}>Refund</option> --}}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="payment_remarks">Payment Remarks</label>
                                        <input type="text" placeholder="Payment Remarks" name="payment_remarks" id="payment_remarks" class="form-control" value="{{ $transaction['remarks'] }}">
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
        url: "fetch/opd/patient",
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

    $("#PatientListModel").modal("hide");
});

    $("#department_id").change(function(){
        const D_ID = $(this).val();
        $.ajax({
            type: "POST",
            url: "fetch/opd/doctor",
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
            url: "check/opd/date",
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
    $("#btnAppendTestRow").click(function(){
        const count = $("#TestRowContainer").children("tr").length;
        $("#TestRowContainer").append(`<tr>
                <td>
                    <select placeholder="Test Name" name="test[${count}][name]" id="test[${count}][name]" class="form-control form-select TestSelector" required data-target="test[${count}][rate]">
                        @foreach ($tests as $test)
                            <option value="{{$test->encrypted_id}}" data-rate="{{$test->rate}}">{{$test->name}}</option>
                        @endforeach
                    </select>    
                </td>
                <td>
                    <input type="number" placeholder="Quantity" required class="form-control TestSelectorQty" min="1" value="1" name="test[${count}][qty]" id="test[${count}][qty]">
                </td>
                <td>
                    <input type="number" placeholder="Rate" required value="{{$tests[0]->rate}}" class="form-control TestSelectorRate" min="0" name="test[${count}][rate]" id="test[${count}][rate]">
                </td>
                <td>
                    <button class="btn btn-sm btn-danger btnRemoveTestRow" type="button">Remove</button>    
                </td>
            </tr>`);
            calcTotalBill();
    });
    $(document).on("input", ".TestSelectorQty, .TestSelectorRate", function(){
        calcTotalBill();
    });
    $(document).on("change", ".TestSelector", function(){
        $("input[name='"+$(this).attr("data-target")+"']").val($("option[value='"+$(this).val()+"']").attr("data-rate"));
        calcTotalBill();
    });
    $(document).on("click", '.btnRemoveTestRow', function(){
        $(this).parent().parent().hide(200);
        setTimeout(() => {
            $(this).parent().parent().remove();
            calcTotalBill();
        }, 210);
    });
    function calcTotalBill(){
        let total_amount = 0;
        $("#TestRowContainer").children("tr").each(function(){
            total_amount += $(this).find(".TestSelectorQty").val() * $(this).find(".TestSelectorRate").val();
        });
        $("#payment_amount").val(total_amount);
        $("#payable_amount").val(total_amount - ($("#discount").val()*total_amount)/100);
    }
    $("#mainForm").on("reset", function(){
        $("#TestRowContainer").html("");
    });
  </script>
<x-footer />