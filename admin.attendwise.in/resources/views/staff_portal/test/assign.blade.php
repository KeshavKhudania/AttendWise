<x-structure />
<x-header heading="Assign test to Patient {{$patient->first_name}} ( {{$patient->uhid}} )" />
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card bg-white">
      <div class="card-body">

<div class="container-fluid">
    <div class="row">
        <!-- Left: Patient Profile (col-md-4) -->
        <div class="col-md-3">
            <div class="card bg-white">
                <div class="card-body patientDetailContainer">
                    <h5 class="border-bottom pb-1 border-dark">Patient Profile</h5>
                    <table class="table table-sm table-bordered">
                        <tbody>
                            <tr>
                                <th>Name</th>
                                <td>{{ $patient->first_name ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Age</th>
                                <td>{{ $patient->age ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Gender</th>
                                <td>{{ $patient->gender ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Contact</th>
                                <td>{{ $patient->contact ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $patient->address ?? '' }}</td>
                            </tr>
                            <!-- Add more fields as needed -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right: Test Details and Payment (col-md-8) -->
        <div class="col-md-9">
            <!-- Your Existing Test Table Section -->
            <div class="col-md-12">
                <h5 class="border-bottom pb-1 border-dark">Test Details</h5>
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
                                            <option value="{{$test->encrypted_id}}" @if($item->test_id == $test->id) selected @endif data-rate="{{$test->rate}}">{{$test->name}}</option>
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

            <!-- Payment Section -->
            <div class="col-md-12 card-section mt-3">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <h4 class="border-bottom pb-1 d-inline-block border-2 border-dark">Payment Details</h4>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="payment_mode">Payment Mode</label>
                                <select name="payment_mode" id="payment_mode" class="form-control form-select" required>
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
                                <input type="number" placeholder="Total Amount" class="form-control" name="payment_amount" id="payment_amount" value="{{$transaction['total_amount'] ?? '0'}}" readonly required>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="discount">Discount(%)</label>
                                <input type="number" min="0" max="100" placeholder="Discount" class="form-control" name="discount" id="discount" value="{{$transaction['discount'] ?? '0'}}" required>
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
                                <select name="payment_status" id="payment_status" class="form-control form-select" required>
                                    <option value="Paid">Paid</option>
                                    <option value="Pending" {{ $transaction['status'] == "Pending" ? "selected":"" }}>Pending</option>
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
</div>

      </div>
    </div>
</div>
<script>
    $("#fetchPatient").click(function() {
        
        $.ajax({
            type: "POST",
            url: "fetch/opd/patient",
            data: {
                "_token":$("meta[name=csrf]").attr("content"),
                "uhid":$("#search_patient_uhid").val(),
                "mobile":$("#search_patient_mobile").val(),
            },
            beforeSend: function(){
                $("#fetchPatient").html("<i class='fas fa-spinner fa-spin'></i> Please Wait").attr("disabled", true);
            },
            // dataType: "dataType",
            success: function (response) {
                $("#fetchPatient").html("<i class='fas fa-search'></i> Search Patient").removeAttr("disabled")
                const result = JSON.parse(response);
                $("#PatientListModel").modal("show")
                
                $(".patientSelectListContainer").html("");
                if (result.uhid_results.length == 0 && result.mobile_results.length == 0) {
                    $(".patientSelectListContainer").html("<tr><td class='text-danger'>No Records Found:</td></tr>");
                }
                if (result.uhid_results.length != 0) {
                    $(".patientSelectListContainer").append("<tr><td colspan='11' class='text-danger'>UHID Search Result:</td></tr>");
                $(".patientSelectListContainer").append(`<tr>
                    <td>Image</td>
                    <td>UHID</td>
                    <td>First Name</td>
                    <td>Last Name</td>
                    <td>Gender</td>
                    <td>Date Of Birth</td>
                    <td>Email</td>
                    <td>Mobile</td>
                    <td>Blood Group</td>
                    <td>Address</td>
                    <td>Adhaar Number</td>
                    </tr>`);
                        result.uhid_results.forEach(element => {
                            $(".patientSelectListContainer").append(`<tr class="patientSelectListRow">
                                <td><img src="../hospit_images/${element.image}" style="width: 50px !important; aspect-ratio:1/1 !important; height: auto !important;" class=" rounded-circle"></td>
                                <td msc-val="${element.uhid ?? ""}">${element.uhid}</td>
                                    <td msc-val="${element.first_name ?? ""}">${element.first_name}</td>
                                    <td msc-val="${element.last_name ?? ""}">${element.last_name ?? "--"}</td>
                                    <td msc-val="${element.gender ?? ""}">${element.gender}</td>
                                    <td msc-val="${element.date_of_birth ?? ""}">${element.date_of_birth}</td>
                                    <td msc-val="${element.email ?? ""}">${element.email ?? "--"}</td>
                                    <td msc-val="${element.phone_number ?? ""}">${element.phone_number ?? "--"}</td>
                                    <td msc-val="${element.blood_group ?? ""}">${element.blood_group ?? "--"}</td>
                                    <td msc-val="${element.address ?? ""}">${element.address ?? "--"}</td>
                                    <td msc-val="${element.adhaar_number ?? ""}">${element.adhaar_number ?? "--"}</td>
                                </tr>`);
                            });
                        }
                        if (result.mobile_results.length != 0) {
                            $(".patientSelectListContainer").append("<tr><td colspan='11' class='text-danger'>Mobile Search Results:</td></tr>");
                            $(".patientSelectListContainer").append(`<tr>
                                <td>Image</td>
                                <td>UHID</td>
                                <td>First Name</td>
                                <td>Last Name</td>
                                <td>Gender</td>
                                <td>Date Of Birth</td>
                                <td>Email</td>
                                <td>Mobile</td>
                                <td>Blood Group</td>
                                <td>Address</td>
                                <td>Adhaar Number</td>
                                </tr>`);
                            // $(".patientSelectListContainer").append("<tr><td colspan='8' class='text-dark'>No Record Found</td></tr>");
                            result.mobile_results.forEach(element => {
                                $(".patientSelectListContainer").append(`<tr class="patientSelectListRow">
                                    <td><img src="../hospit_images/${element.image}" style="width: 50px !important; aspect-ratio:1/1 !important; height: auto !important;" class="rounded-circle"></td>
                                    <td msc-val="${element.uhid ?? ""}">${element.uhid}</td>
                                    <td msc-val="${element.first_name ?? ""}">${element.first_name}</td>
                                    <td msc-val="${element.last_name ?? ""}">${element.last_name ?? "--"}</td>
                                    <td msc-val="${element.gender ?? ""}">${element.gender}</td>
                                    <td msc-val="${element.date_of_birth ?? ""}">${element.date_of_birth}</td>
                                    <td msc-val="${element.email ?? ""}">${element.email ?? "--"}</td>
                                    <td msc-val="${element.phone_number ?? ""}">${element.phone_number ?? "--"}</td>
                                    <td msc-val="${element.blood_group ?? ""}">${element.blood_group ?? "--"}</td>
                                    <td msc-val="${element.address ?? ""}">${element.address ?? "--"}</td>
                                    <td msc-val="${element.adhaar_number ?? ""}">${element.adhaar_number ?? "--"}</td>
                                    </tr>`);
                                });
                    }
            }
        });
    })
    $(document).on("click", ".patientSelectListRow", function(){
        $(".patientDetailContainer #first_name").val($(this).children("td")[2].getAttribute("msc-val"))
        $(".patientDetailContainer #last_name").val($(this).children("td")[3].getAttribute("msc-val"))
        $(".patientDetailContainer #gender option[value="+$(this).children("td")[4].getAttribute("msc-val")+"]").attr("selected","true")
        $(".patientDetailContainer #date_of_birth").val($(this).children("td")[5].getAttribute("msc-val"))
        $(".patientDetailContainer #email").val($(this).children("td")[6].getAttribute("msc-val"))
        $(".patientDetailContainer #phone_number").val($(this).children("td")[7].getAttribute("msc-val"))
        // $(".patientDetailContainer #blood_group").val($(this).children("td")[7].getAttribute("msc-val"))
        if ($(this).children("td")[8].getAttribute("msc-val") != "") {
            $(".patientDetailContainer #blood_group option[value="+$(this).children("td")[8].getAttribute("msc-val")+"]").attr("selected","true")
        }
        $(".patientDetailContainer #adhaar_number").val($(this).children("td")[10].getAttribute("msc-val"))
        $(".patientDetailContainer #address").val($(this).children("td")[9].getAttribute("msc-val"))
        $("#PatientListModel").modal("hide")
    })
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

