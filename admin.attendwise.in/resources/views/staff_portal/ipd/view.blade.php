<x-structure />
<x-header heading="{{$title}}"/>
<div class="msc-ipd-container">
        <nav class="msc-tab-nav">
            @foreach ($modules as $k=>$module)
                <x-module-check route="{{$k}}">
                    <button class="msc-tab-btn" data-msc-tab="msc.{{$k}}">{{$module}}</button>
                </x-module-check>
            @endforeach
        </nav>

        <div class="msc-tab-content">
             <x-module-check route="hospit.ipd.manage.overview">
                   <div class="msc-tab-panel" id="msc.hospit.ipd.manage.overview">
                        {{-- <h2 class="msc-ipd-panel-title">Patient Overview</h2> --}}
                        
                        <!-- Patient Header with Image and Barcode -->
                        <div class="msc-ipd-patient-header">
                            <div class="msc-ipd-patient-photo">
                                <img src="{{$details->patient->image != null ?env("FILE_UPLOAD_PATH").$details->patient->image: env("PLACEHOLDER_IMAGE")}}" alt="Patient Photo" class="msc-patient-img">
                                <div class="msc-status-badge msc-status-active">{{$details->ipd_status}}</div>
                            </div>
                            <div class="msc-ipd-patient-basic">
                                <h3 class="msc-patient-name">{{$details->patient->first_name}} {{$details->patient->last_name}}</h3>
                                <p class="msc-patient-id">UHID: #{{$details->patient->uhid}}</p>
                                <div class="msc-patient-badges">
                                    @php
                                        $age = \Carbon\Carbon::parse($details->patient->date_of_birth)->diff(\Carbon\Carbon::now());
                                    @endphp
                                    <span class="msc-badge msc-badge-age">{{ $age->y }} years, {{ $age->m }} months, {{ $age->d }} days</span>
                                    <span class="msc-badge msc-badge-gender">{{$details->patient->gender}}</span>
                                    <span class="msc-badge msc-badge-blood">{{$details->patient->blood_group ?? "--"}}</span>
                                </div>
                            </div>
                            <div class="msc-ipd-barcode">
                                <div class="msc-barcode-container">
                                    <img src="" id="IPDnumberBarCode" alt="Barcode" class="msc-barcode-img">
                                    <p class="msc-barcode-text">{{$details->ipd_number}}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Main Information Grid -->
                        <div class="msc-ipd-overview-grid">
                            <!-- Patient Information -->
                            <div class="msc-ipd-card msc-ipd-patient-info">
                                <h3 class="msc-ipd-section-title">
                                    <i class="fas fa-user-circle"></i>
                                    Patient Information
                                </h3>
                                <div class="msc-ipd-info-row">
                                    <span class="msc-ipd-label">Full Name:</span>
                                    <span class="msc-ipd-value">{{$details->patient->first_name}} {{$details->patient->last_name}}</span>
                                </div>
                                <div class="msc-ipd-info-row">
                                    <span class="msc-ipd-label">Date of Birth:</span>
                                    <span class="msc-ipd-value">{{$details->patient->date_of_birth}}</span>
                                </div>
                                <div class="msc-ipd-info-row">
                                    <span class="msc-ipd-label">Blood Group:</span>
                                    <span class="msc-ipd-value">
                                        <span class="msc-blood-group">{{$details->patient->blood_group ?? "--"}}</span>
                                    </span>
                                </div>
                                <div class="msc-ipd-info-row">
                                    <span class="msc-ipd-label">Guardian:</span>
                                    <span class="msc-ipd-value">{{$details->guardian_name}}</span>
                                </div>
                                <div class="msc-ipd-info-row">
                                    <span class="msc-ipd-label">Guardian Contact:</span>
                                    <span class="msc-ipd-value">{{$details->guardian_mobile}}</span>
                                </div>
                                <div class="msc-ipd-info-row">
                                    <span class="msc-ipd-label">Adhaar Number:</span>
                                    <span class="msc-ipd-value">{{$details->patient->adhaar_number ?? "--"}}</span>
                                </div>
                                <div class="msc-ipd-info-row">
                                    <span class="msc-ipd-label">Address:</span>
                                    <span class="msc-ipd-value">{{$details->patient->address}}</span>
                                </div>
                            </div>

                            <!-- Admission Details -->
                            <div class="msc-ipd-card msc-ipd-admission-info">
                                <h3 class="msc-ipd-section-title">
                                    <i class="fas fa-clipboard-list"></i>
                                    Admission Details
                                </h3>
                                <div class="msc-ipd-info-row">
                                    <span class="msc-ipd-label">Case ID:</span>
                                    <span class="msc-ipd-value">#{{$details->case_id}}</span>
                                </div>
                                <div class="msc-ipd-info-row">
                                    <span class="msc-ipd-label">IPD Number:</span>
                                    <span class="msc-ipd-value">{{$details->ipd_number}}</span>
                                </div>
                                <div class="msc-ipd-info-row">
                                    <span class="msc-ipd-label">Admission Date:</span>
                                    <span class="msc-ipd-value">{{$details->arrival_date}}, {{$details->arrival_time}}</span>
                                </div>
                                <div class="msc-ipd-info-row">
                                    <span class="msc-ipd-label">Expected Discharge:</span>
                                    <span class="msc-ipd-value">{{$details->expected_discharge_date}}</span>
                                </div>
                                <div class="msc-ipd-info-row">
                                    <span class="msc-ipd-label">Current Bed:</span>
                                    <span class="msc-ipd-value">Bed Number: {{$details->bed->bed_number}}, {{$details->bed_category->category_name}} <br> Room Number: {{$details->room->room_number}} - {{$details->room_category->name}}<br> {{Number::ordinal($details->room->floor)}} Floor</span>
                                </div>
                                <div class="msc-ipd-info-row">
                                    <span class="msc-ipd-label">Department:</span>
                                    <span class="msc-ipd-value">{{$details->department->name}}</span>
                                </div>
                                <div class="msc-ipd-info-row">
                                    <span class="msc-ipd-label">Attending Doctor:</span>
                                    <span class="msc-ipd-value">Dr. {{$details->doctor->name}}</span>
                                </div>
                            </div>

                            <!-- Medical Information -->
                            <div class="msc-ipd-card msc-ipd-medical-info">
                                <h3 class="msc-ipd-section-title">
                                    <i class="fas fa-stethoscope"></i>
                                    Medical Information
                                </h3>
                                <div class="msc-ipd-info-row">
                                    <span class="msc-ipd-label">Symptoms:</span>
                                    <span class="msc-ipd-value">
                                        @if ($details->symptoms == null)
                                            --
                                        @endif
                                        @foreach (($details->symptoms != null ?  unserialize($details->symptoms):[]) as $item)
                                            <span class="badge bg-warning">{{ $item }}</span>
                                        @endforeach
                                    </span>
                                </div>
                                <div class="msc-ipd-info-row">
                                    <span class="msc-ipd-label">Diagnosis:</span>
                                    <span class="msc-ipd-value">
                                        @if ($details->provisional_diagnosis == null)
                                            --
                                        @endif
                                        @foreach (($details->provisional_diagnosis != null ? unserialize($details->provisional_diagnosis):[]) as $item)
                                            <span class="badge bg-success">{{ $item }}</span>
                                        @endforeach
                                    </span>
                                </div>
                                <div class="msc-ipd-info-row">
                                    <span class="msc-ipd-label">Allergies:</span>
                                    <span class="msc-ipd-value ">
                                        @if ($details->allergies == null)
                                            --
                                        @endif
                                        @foreach (($details->allergies != null ? unserialize($details->allergies):[]) as $item)
                                            <span class="msc-allergy">{{ $item }}</span>
                                        @endforeach
                                    </span>
                                </div>
                                <div class="msc-ipd-info-row">
                                    <span class="msc-ipd-label">Disease:</span>
                                    <span class="msc-ipd-value ">
                                        @if ($details->disease == null)
                                            --
                                        @endif
                                        @foreach (($details->disease != null ? unserialize($details->disease):[]) as $item)
                                            <span class="badge bg-danger">{{ $item }}</span>
                                        @endforeach
                                    </span>
                                </div>
                                <div class="msc-ipd-info-row">
                                    <span class="msc-ipd-label">Insurance:</span>
                                    <span class="msc-ipd-value">{{$details->has_insurance =="Yes"?App\Models\InsuranceProvider::find($details->insurance_provider)->name:"--"}}{{$details->has_insurance =="Yes"?" - Policy #"($details->policy_no):""}}</span>
                                </div>
                                <div class="msc-ipd-info-row">
                                    <span class="msc-ipd-label">Medical History:</span>
                                    <span class="msc-ipd-value"><button class="btn btn-sm btn-primary">View</button></span>
                                </div>
                            </div>

                            <!-- Current Status -->
                            <div class="msc-ipd-card msc-ipd-status-info">
                                <h3 class="msc-ipd-section-title">
                                    <i class="fas fa-heartbeat"></i>
                                    Current Status
                                </h3>
                                <div class="msc-ipd-info-row">
                                    <span class="msc-ipd-label">Condition:</span>
                                    <span class="msc-ipd-value msc-status-stable">Stable</span>
                                </div>
                                <div class="msc-ipd-info-row">
                                    <span class="msc-ipd-label">Diet:</span>
                                    <span class="msc-ipd-value">@if (($details->diet) == null)
                                            --
                                            @else
                                            {{-- {{Arr::join(unserialize($details->diet), ', ')}} --}}
                                        @endif
                                        </span>
                                </div>
                                <div class="msc-ipd-info-row">
                                    <span class="msc-ipd-label">Activity Level:</span>
                                    <span class="msc-ipd-value">Bed Rest</span>
                                </div>
                                <div class="msc-ipd-info-row">
                                    <span class="msc-ipd-label">Next Review:</span>
                                    <span class="msc-ipd-value">Tomorrow 9:00 AM</span>
                                </div>
                                <div class="msc-ipd-info-row">
                                    <span class="msc-ipd-label">Days in Hospital:</span>
                                    <span class="msc-ipd-value">{{\Carbon\Carbon::parse($details->arrival_date)->diff(\Carbon\Carbon::now())->d}} Days</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </x-module-check>
            

            <x-module-check route="hospit.ipd.manage.medicines">
                <div class="msc-tab-panel" id="msc.hospit.ipd.manage.medicines">
                {{-- <h2 class="msc-ipd-panel-title">Medicine Management</h2> --}}
                <form class="msc-ipd-medicine-form">
                    <div class="msc-ipd-form-group">
                        <label for="msc-ipd-medicine-name" class="msc-ipd-form-label">Medicine Name</label>
                        <select id="msc-ipd-medicine-name" name="medicine-name" class="msc-ipd-form-select msc-ipd-searchable">
                            <option value="">Search and select medicine</option>
                            <option value="paracetamol">Paracetamol</option>
                            <option value="ibuprofen">Ibuprofen</option>
                            <option value="aspirin">Aspirin</option>
                            <option value="amoxicillin">Amoxicillin</option>
                            <option value="metformin">Metformin</option>
                            <option value="lisinopril">Lisinopril</option>
                            <option value="atorvastatin">Atorvastatin</option>
                            <option value="wormstop">WORMSTOP</option>
                        </select>
                    </div>
                    <div class="msc-ipd-form-row">
                        <div class="msc-ipd-form-group">
                            <label for="msc-ipd-dosage" class="msc-ipd-form-label">Dosage</label>
                            <input type="text" id="msc-ipd-dosage" name="dosage" class="msc-ipd-form-input" placeholder="e.g., 500mg">
                        </div>
                        <div class="msc-ipd-form-group">
                            <label for="msc-ipd-frequency" class="msc-ipd-form-label">Frequency</label>
                            <select id="msc-ipd-frequency" name="frequency" class="msc-ipd-form-select msc-ipd-searchable">
                                <option value="">Select frequency</option>
                                <option value="once-daily">Once Daily</option>
                                <option value="twice-daily">Twice Daily</option>
                                <option value="thrice-daily">Thrice Daily</option>
                                <option value="four-times-daily">Four Times Daily</option>
                                <option value="as-needed">As Needed (PRN)</option>
                                <option value="every-4-hours">Every 4 Hours</option>
                                <option value="every-6-hours">Every 6 Hours</option>
                                <option value="every-8-hours">Every 8 Hours</option>
                                <option value="every-12-hours">Every 12 Hours</option>
                            </select>
                        </div>
                        <div class="msc-ipd-form-group">
                            <label for="msc-ipd-duration" class="msc-ipd-form-label">Duration (Days)</label>
                            <input type="number" id="msc-ipd-duration" name="duration" class="msc-ipd-form-input" placeholder="e.g., 7">
                        </div>
                    </div>
                    <div class="msc-ipd-form-group">
                        <label for="msc-ipd-instructions" class="msc-ipd-form-label">Special Instructions</label>
                        <textarea id="msc-ipd-instructions" name="instructions" class="msc-ipd-form-textarea" placeholder="After meals, with water, etc."></textarea>
                    </div>
                    <button type="submit" class="msc-ipd-btn-primary">Add Medicine</button>
                </form>
                
                <div class="msc-ipd-medicine-list">
                    <h3 class="msc-ipd-section-title">Current Medications</h3>
                    <table class="msc-ipd-data-table">
                        <thead>
                            <tr>
                                <th>Medicine</th>
                                <th>Dosage</th>
                                <th>Frequency</th>
                                <th>Duration</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>WORMSTOP</td>
                                <td>1 Tablet</td>
                                <td>Once Daily</td>
                                <td>5 Days</td>
                                <td><button class="msc-ipd-btn-danger">Remove</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                </div>
            </x-module-check>

            <x-module-check route="hospit.ipd.manage.medication">
                <div class="msc-tab-panel" id="msc.hospit.ipd.manage.medication">
                {{-- <h2 class="msc-ipd-panel-title">Medication Schedule & Administration</h2> --}}
                
                <div class="msc-ipd-medication-schedule">
                    <h3 class="msc-ipd-section-title">Today's Medication Schedule</h3>
                    <div class="msc-ipd-schedule-grid">
                        <div class="msc-ipd-time-slot">
                            <div class="msc-ipd-time-header">06:00 AM - Morning</div>
                            <div class="msc-ipd-medication-item">
                                <div class="msc-ipd-med-info">
                                    <strong>Paracetamol 500mg</strong>
                                    <span>1 Tablet - After breakfast</span>
                                </div>
                                <div class="msc-ipd-med-status">
                                    <input type="checkbox" id="med-1" class="msc-ipd-checkbox">
                                    <label for="med-1">Given</label>
                                </div>
                            </div>
                            <div class="msc-ipd-medication-item">
                                <div class="msc-ipd-med-info">
                                    <strong>Metformin 850mg</strong>
                                    <span>1 Tablet - With breakfast</span>
                                </div>
                                <div class="msc-ipd-med-status">
                                    <input type="checkbox" id="med-2" class="msc-ipd-checkbox" checked>
                                    <label for="med-2">Given</label>
                                </div>
                            </div>
                        </div>

                        <div class="msc-ipd-time-slot">
                            <div class="msc-ipd-time-header">12:00 PM - Afternoon</div>
                            <div class="msc-ipd-medication-item">
                                <div class="msc-ipd-med-info">
                                    <strong>Ibuprofen 400mg</strong>
                                    <span>1 Tablet - After lunch</span>
                                </div>
                                <div class="msc-ipd-med-status">
                                    <input type="checkbox" id="med-3" class="msc-ipd-checkbox">
                                    <label for="med-3">Given</label>
                                </div>
                            </div>
                        </div>

                        <div class="msc-ipd-time-slot">
                            <div class="msc-ipd-time-header">06:00 PM - Evening</div>
                            <div class="msc-ipd-medication-item">
                                <div class="msc-ipd-med-info">
                                    <strong>Paracetamol 500mg</strong>
                                    <span>1 Tablet - After dinner</span>
                                </div>
                                <div class="msc-ipd-med-status">
                                    <input type="checkbox" id="med-4" class="msc-ipd-checkbox">
                                    <label for="med-4">Given</label>
                                </div>
                            </div>
                            <div class="msc-ipd-medication-item">
                                <div class="msc-ipd-med-info">
                                    <strong>Atorvastatin 20mg</strong>
                                    <span>1 Tablet - Before sleep</span>
                                </div>
                                <div class="msc-ipd-med-status">
                                    <input type="checkbox" id="med-5" class="msc-ipd-checkbox">
                                    <label for="med-5">Given</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form class="msc-ipd-medication-admin-form">
                    <h3 class="msc-ipd-section-title">Record Medication Administration</h3>
                    <div class="msc-ipd-form-row">
                        <div class="msc-ipd-form-group">
                            <label for="msc-ipd-admin-medicine" class="msc-ipd-form-label">Medicine</label>
                            <select id="msc-ipd-admin-medicine" name="admin-medicine" class="msc-ipd-form-select msc-ipd-searchable">
                                <option value="">Select medicine</option>
                                <option value="paracetamol-500">Paracetamol 500mg</option>
                                <option value="metformin-850">Metformin 850mg</option>
                                <option value="ibuprofen-400">Ibuprofen 400mg</option>
                                <option value="atorvastatin-20">Atorvastatin 20mg</option>
                            </select>
                        </div>
                        <div class="msc-ipd-form-group">
                            <label for="msc-ipd-admin-time" class="msc-ipd-form-label">Administration Time</label>
                            <input type="datetime-local" id="msc-ipd-admin-time" name="admin-time" class="msc-ipd-form-input">
                        </div>
                        <div class="msc-ipd-form-group">
                            <label for="msc-ipd-admin-nurse" class="msc-ipd-form-label">Administered By</label>
                            <select id="msc-ipd-admin-nurse" name="admin-nurse" class="msc-ipd-form-select msc-ipd-searchable">
                                <option value="">Select nurse</option>
                                <option value="nurse-1">Sarah Johnson (RN)</option>
                                <option value="nurse-2">Michael Brown (RN)</option>
                                <option value="nurse-3">Emily Davis (RN)</option>
                                <option value="nurse-4">Robert Wilson (RN)</option>
                            </select>
                        </div>
                    </div>
                    <div class="msc-ipd-form-group">
                        <label for="msc-ipd-admin-notes" class="msc-ipd-form-label">Administration Notes</label>
                        <textarea id="msc-ipd-admin-notes" name="admin-notes" class="msc-ipd-form-textarea" placeholder="Patient response, side effects, etc."></textarea>
                    </div>
                    <button type="submit" class="msc-ipd-btn-primary">Record Administration</button>
                </form>

                <div class="msc-ipd-medication-history">
                    <h3 class="msc-ipd-section-title">Medication Administration History</h3>
                    <table class="msc-ipd-data-table">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Medicine</th>
                                <th>Dosage</th>
                                <th>Administered By</th>
                                <th>Status</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>05/18/2025 06:15 AM</td>
                                <td>Metformin</td>
                                <td>850mg</td>
                                <td>Sarah Johnson (RN)</td>
                                <td><span class="msc-ipd-status msc-ipd-completed">Given</span></td>
                                <td>Patient tolerated well</td>
                            </tr>
                            <tr>
                                <td>05/17/2025 06:00 PM</td>
                                <td>Paracetamol</td>
                                <td>500mg</td>
                                <td>Emily Davis (RN)</td>
                                <td><span class="msc-ipd-status msc-ipd-completed">Given</span></td>
                                <td>For fever management</td>
                            </tr>
                            <tr>
                                <td>05/17/2025 12:00 PM</td>
                                <td>Ibuprofen</td>
                                <td>400mg</td>
                                <td>Michael Brown (RN)</td>
                                <td><span class="msc-ipd-status msc-ipd-missed">Missed</span></td>
                                <td>Patient was sleeping</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            </x-module-check>

            <x-module-check route="hospit.ipd.manage.operations">
                <script>
                    function populateOperationPreview(operationData) {
                        // Default values if data is missing
                        const defaults = {
                            procedure: '-',
                            date: '-',
                            surgeon: '-',
                            type: '-',
                            room: '-',
                            cost: '-',
                            final_cost: '-',
                            discount: '-',
                            rateList: '-',
                            notes: '-'
                        };
                        
                        // Merge with defaults
                        const data = { ...defaults, ...operationData };
                        
                        // Format datetime if present
                        let formattedDate = '-';
                        if (data.date) {
                            const d = new Date(data.date);
                            if (!isNaN(d)) {
                                const options = { 
                                    year: 'numeric', 
                                    month: '2-digit', 
                                    day: '2-digit', 
                                    hour: '2-digit', 
                                    minute: '2-digit', 
                                    hour12: true 
                                };
                                formattedDate = d.toLocaleString('en-GB', options).replace(',', '');
                            }
                        }
                        // Format cost
                        let formattedCost = '-';
                        if (data.cost && !isNaN(parseFloat(data.cost))) {
                            formattedCost = `₹ ${parseFloat(data.cost).toLocaleString('en-IN', { 
                                minimumFractionDigits: 2 
                            })}`;
                        }
                        let formattedFinalCost = '-';
                        if (data.final_cost && !isNaN(Number(data.final_cost))) {
                            formattedFinalCost = `₹ ${parseFloat(data.final_cost).toLocaleString('en-IN', { 
                                minimumFractionDigits: 2 
                            })}`;
                        }
                        
                        // Update the card elements
                        const elements = {
                            'opv-procedure': data.procedure,
                            'opv-date': formattedDate,
                            'opv-surgeon': data.surgeon,
                            'opv-type': data.type,
                            'opv-room': data.room,
                            'opv-cost': formattedCost,
                            'opv-discount': (data.discount != "-"?data.discount+"%":data.discount),
                            'opv-final-cost': formattedFinalCost,
                            'opv-rate-list': data.rateList,
                            'opv-notes': data.notes
                        };
                        // console.log(operationData)
                        // console.log(elements)
                        // Populate each element
                        Object.keys(elements).forEach(id => {
                            const element = document.getElementById(id);
                            if (element) {
                                element.innerText = elements[id] === "-" ? element.innerText : elements[id];
                            }
                        });
                    }
                    // Global variable to track form mode
                    window.isIpdOperationEditMode = false;

                    // Function to edit operation - populate existing form
                    function editOperation(elem) {
                        const data = JSON.parse(elem.getAttribute("data-val"));
                        window.isIpdOperationEditMode = true;    
                        // Update form UI
                        $('#form-title').text('Edit Operation');
                        $('#submit-btn').text('Update Operation');
                        $('#cancel-edit-btn').show();
                        // $('#status-field').show();
                        
                        // Update form action and method
                        $('#operationForm').attr('action', '{{$module_actions['operation.save']}}');
                        // $('#form_method').val('PUT');
                        $('#operation_record_id').val(data.encrypted_id);
                        
                        // Populate form fields
                        $('#msc-ipd-operation-name option').each(function(){
                            if($(this).text().trim() === data.operation_name.trim()){
                                $('#msc-ipd-operation-name').val($(this).val()).trigger('change');
                                return false;
                            }
                        });
                        // $('#operation_doctor_id').on("click",)
                        // $('#msc-ipd-operation-name').trigger('change');
                        // $('#operation_doctor_id option').each(function(){
                        //     // console.log($(this).text())
                        //     // console.log(data.surgeon_name)
                        //     // console.log("")
                        //     if($(this).text().trim() === data.surgeon_name.trim()){
                        //         // console.log("Match")
                        //         $('#operation_doctor_id').val($(this).val());
                        //         $(this).prop("selected", true);
                        //         return false;
                        //     }
                        // });
                        $('#operation_doctor_id').html("<option value='"+data.surgeon_enc_id+"'>"+data.surgeon_name+"</option>")
                        $('#msc-ipd-operation-date').val(data.operation_date_raw);
                        $('#msc-ipd-operation-room option').each(function(){
                            if($(this).text().trim() === data.room_category_name.trim()){
                                $('#msc-ipd-operation-room').val($(this).val());
                                return false;
                            }
                        });
                        $('#msc-ipd-operation-room').trigger('change');
                        $('#msc-ipd-operation-type').val(data.operation_type);
                        // $('#msc-ipd-operation-room').val(data.room_category_id);
                        $('#operation_room_id option').each(function(){
                            if($(this).text().trim() === data.room_name.trim()){
                                $('#operation_room_id').val($(this).val());
                                return false;
                            }
                        });
                        $('#operation_price').val(data.final_cost);
                        $('#operation_price_discount').val(data.discount || 0);
                        $('#operation_status').val(data.status);
                        $('#msc-ipd-operation-notes').val(data.operation_notes);
                        $('#msc-ipd-operation-payment-remarks').val(data.payment_remarks);
                        
                        // Update preview
                        populateOperationPreview({
                            procedure: data.operation_name + ` (${data.operation_code})`,
                            date: data.operation_date,
                            surgeon: data.surgeon_name,
                            type: data.operation_type,
                            room: data.room_name,
                            cost: data.base_cost,
                            final_cost: data.final_cost,
                            discount: data.discount || 0,
                            notes: data.operation_notes
                        });
                        
                        // Scroll to form
                        $('html, body').animate({
                            scrollTop: $("#operationForm").offset().top - 100
                        }, 500);
                    }

                    // Function to reset form to create mode
                    function resetForm() {
                        // Reset edit mode
                        window.isIpdOperationEditMode = false;
                        
                        // Update form UI
                        $('#form-title').text('Schedule New Operation');
                        $('#submit-btn').text('Schedule Operation');
                        $('#cancel-edit-btn').hide();
                        // $('#status-field').hide();
                        $('#msc-ipd-operation-name').off("focus");
                        // Reset form action and method
                        $('#operationForm').attr('action', '{{$module_actions['operation.save']}}');
                        $('#form_method').val('POST');
                        $('#operation_record_id').val('');
                        
                        // Clear all form fields
                        $('#operationForm')[0].reset();
                        
                        // Reset select fields to first option
                        $('#msc-ipd-operation-name').prop('selectedIndex', 0);
                        $('#operation_doctor_id').empty();
                        $('#msc-ipd-operation-type').prop('selectedIndex', 0);
                        $('#operation_room_id').empty();
                        $('#operation_price_discount').val(0);
                        
                        // Reset preview
                        populateOperationPreview({
                            procedure: '-',
                            date: '-',
                            surgeon: '-',
                            type: '-',
                            room: '-',
                            cost: '-',
                            final_cost: '-',
                            discount: '0',
                            notes: '-'
                        });
                        
                        // Trigger change to reload operation details
                        setTimeout(() => {
                            fetchOpertationDetails();
                        }, 500);
                    }

                    function fetchOpertationDetails() {
                        $.ajax({
                            type: "POST",
                            url: "{{$module_actions['operation.fetch']}}",
                            data: {
                                "_token":"{{csrf_token()}}",
                                "operation_id":$("#msc-ipd-operation-name").val(),
                                "ipd_id":"{{Crypt::encrypt($details->id)}}",
                            },
                            success: function (response) {
                                const discount = $("#operation_price_discount").val() ?? 0;
                                const final_cost = Number(response.price) - (Number(discount)*Number(response.price))/100;
                                populateOperationPreview({
                                    procedure: response.name + ` (${response.code})`,
                                    cost: response.price,
                                    final_cost: final_cost,
                                });
                                populateOperationPreview({surgeon:$("#operation_doctor_id option[value='"+$("#operation_doctor_id").val()+"']").text()});
                                $("#operation_price").val(final_cost);
                                $("#operation_price").attr("max",response.price);
                                window.base_price = response.price;
                                // fetchRelatedList({
                                // 'data': {
                                //     'operation_id': $("#msc-ipd-operation-name").val(),
                                // },
                                //     'target': '#operation_doctor_id',
                                //     'url': '{{$module_actions['operation.fetch.doctor']}}',
                                // });
                            }
                        });
                    }
                    function viewOperation(elem) {
                        const data = JSON.parse(elem.getAttribute("data-val"));
                        $('#view-procedure').text(data.operation_name || '-');
                        $('#view-code').text(data.operation_code || '-');
                        $('#view-date').text(data.operation_date || '-');
                        $('#view-surgeon').text(data.surgeon_name || '-');
                        $('#view-type').text(data.operation_type || '-');
                        $('#view-room').text(data.room_name || '-');
                        $('#view-base-cost').text("₹"+data.base_cost || '-');
                        $('#view-discount').text(data.discount + '%' || '0%');
                        $('#view-final-cost').text("₹"+data.final_cost || '-');
                        $('#view-status').html(`<span class="badge bg-${getStatusColor(data.status)}">${data.status}</span>`);
                        $('#view-notes').text(data.operation_notes || '-');
                        $('#view-payment-remarks').text(data.payment_remarks || '-');
                        
                        $('#viewOperationModal').modal('show');
                    }
                    function getStatusColor(status) {
                        switch(status) {
                            case 'Completed': return 'success';
                            case 'Scheduled': return 'warning';
                            case 'In Progress': return 'primary';
                            default: return 'danger';
                        }
                    }
                    function FetchOperationRecords(){
                        $.ajax({
                            type: "POST",
                            url: "{{$module_actions['operation.records']}}",
                            data: {
                                "_token":"{{csrf_token()}}",
                                "ipd_id":"{{Crypt::encrypt($details->id)}}"
                            },
                            success: function (response) {
                                let row = "";
                                response.data.forEach((res)=>{
                                    const stringRow = JSON.stringify(res);
                                    row += `
                                    <tr>
                                        <td>${res.operation_date}</td>
                                        <td>${res.operation_name}</td>
                                        <td>${res.surgeon_name}</td>
                                        <td>
                                            <span class="badge bg-warning">
                                                ${res.status}
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-info me-1" onclick="viewOperation(this)" data-val='${stringRow}'>
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-warning" onclick="editOperation(this)" data-val='${stringRow}'>
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>`;
                                });
                                $("#operation-history-container").html(row)
                            }
                        });
                    }
                    $(document).ready(function () {
                        window.base_price = 0;
                        setTimeout(() => {
                            fetchOpertationDetails();
                            FetchOperationRecords()
                        }, 500);
                        $("#operation_doctor_id, #operation_room_id, #msc-ipd-operation-type").on("change focus",function(){
                            populateOperationPreview({surgeon:$("#operation_doctor_id option[value='"+$("#operation_doctor_id").val()+"']").text()});
                            populateOperationPreview({room:$("#operation_room_id option[value='"+$("#operation_room_id").val()+"']").text()});
                            populateOperationPreview({type:$("#msc-ipd-operation-type option[value='"+$("#msc-ipd-operation-type").val()+"']").text()});
                        })
                        $("#operation_price_discount").on("input", function(){
                            if($(this).val() > 100){
                                $(this).val(100)
                            }
                            const discount = $(this).val() ?? 0;
                            const final_cost = Number(window.base_price) - (Number(discount)*Number(window.base_price))/100;
                            $("#operation_price").val(final_cost);
                            populateOperationPreview({
                                final_cost:final_cost,
                                discount:discount,
                            })
                        })
                        $("#operation_price").on("input", function(){
                            if($(this).val() < 0){
                                $(this).val(Math.abs($(this).val()))
                            }
                            const discount = (100 - ((Number($(this).val())/Number(window.base_price))*100)).toFixed(2);
                            $("#operation_price_discount").val(discount);
                            const final_cost = $(this).val();
                            populateOperationPreview({
                                final_cost:final_cost,
                                discount:discount,
                            })
                        })
                    });
                </script>
                <div class="msc-tab-panel" id="msc.hospit.ipd.manage.operations">
                    {{-- <h2 class="msc-ipd-panel-title">Operations & Procedures</h2> --}}
                    <div class="row">
                        <div class="col-8">
                            <form class="msc-ipd-operation-form msc-ord-form" method="POST" action="{{$module_actions['operation.save']}}" id="operationForm" data-callback="resetForm(),FetchOperationRecords()">
                                @csrf
                                <input type="hidden" name="ipd_id" value="{{Crypt::encrypt($details->id)}}">
                                <input type="hidden" name="operation_record_id" id="operation_record_id" value="">
                                
                                <!-- Add form mode indicator -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h3 id="form-title">Schedule New Operation</h3>
                                    <button type="button" id="cancel-edit-btn" class="btn btn-secondary" onclick="resetForm()" style="display: none;">
                                        <i class="fas fa-times"></i> Cancel Edit
                                    </button>
                                </div>
                                
                                <!-- Procedure Name -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="msc-ipd-form-group">
                                            <label for="msc-ipd-operation-name" class="msc-ipd-form-label">Procedure Name</label>
                                            <select id="msc-ipd-operation-name" name="operation_id" class="msc-ipd-form-select msc-ipd-searchable msc-searchable" required onchange="fetchOpertationDetails();" onfocusout="fetchRelatedList({
                                                        'data': {
                                                            'operation_id': $(`#msc-ipd-operation-name`).val(),
                                                        },
                                                            'target': '#operation_doctor_id',
                                                            'url': '{{$module_actions['operation.fetch.doctor']}}',
                                                        })">
                                                @foreach ($operations as $item)
                                                    <option value="{{ Crypt::encrypt($item->id) }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Date and Surgeon -->
                                    <div class="col-md-6">
                                        <div class="msc-ipd-form-group">
                                            <label for="operation_doctor_id" class="msc-ipd-form-label">Surgeon</label>
                                            <select id="operation_doctor_id" name="doctor_id" class="msc-ipd-form-select msc-ipd-searchable" required >
                                                
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="msc-ipd-form-group">
                                            <label for="msc-ipd-operation-date" class="msc-ipd-form-label">Scheduled Date</label>
                                            <input type="datetime-local" id="msc-ipd-operation-date" name="operation_date" class="msc-ipd-form-input" required onchange="MscCheckDoctorAvail({
                                                    'data': {
                                                        'doctor_id': $('#operation_doctor_id').val(),
                                                        'date': this.value,
                                                        '_token': '{{ csrf_token() }}',
                                                    },
                                                }); populateOperationPreview({date: this.value});">
                                        </div>
                                    </div>
                                    <!-- Operation Type -->
                                    <div class="col-md-4">
                                        <div class="msc-ipd-form-group">
                                            <label for="msc-ipd-operation-type" class="msc-ipd-form-label">Operation Type</label>
                                            <select id="msc-ipd-operation-type" name="operation_type" class="msc-ipd-form-select msc-ipd-searchable" >
                                                <option disabled selected>Select type</option>
                                                <option value="minor">Minor Surgery</option>
                                                <option value="major">Major Surgery</option>
                                                <option value="emergency">Emergency</option>
                                                <option value="diagnostic">Diagnostic Procedure</option>
                                                <option value="therapeutic">Therapeutic Procedure</option>
                                                <option value="cosmetic">Cosmetic Surgery</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="msc-ipd-form-group">
                                            <label for="operation_price" class="msc-ipd-form-label">Operation Cost</label>
                                            <input type="number" id="operation_price" name="operation_price" class="msc-ipd-form-input" min="0" required readonly>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary p-3" id="submit-btn">Schedule Operation</button>
                            </form>        
                        </div>

                        <div class="col-4">
                            <div class="msc-ipd-operation-preview-card msc-ipd-form-group" style="max-width:400px; margin: 24px auto 0">
                                <div class="msc-ipd-operation-summary">
                                    <h4 class="msc-ipd-panel-title mb-3"><i class="fas fa-notes-medical text-info me-2"></i>Operation Preview</h4>
                                    <ul class="msc-ipd-operation-details list-unstyled mb-0">
                                        <li class="d-flex justify-content-between mb-2">
                                            <span class="fw-bold">Procedure:</span> <span id="opv-procedure"></span>
                                        </li>
                                        <li class="d-flex justify-content-between mb-2">
                                            <span class="fw-bold">Scheduled Date:</span> <span id="opv-date"></span>
                                        </li>
                                        <li class="d-flex justify-content-between mb-2">
                                            <span class="fw-bold">Surgeon:</span> <span id="opv-surgeon"></span>
                                        </li>
                                        <li class="d-flex justify-content-between mb-2">
                                            <span class="fw-bold">Type:</span> <span id="opv-type"></span>
                                        </li>
                                        <li class="d-flex justify-content-between mb-2">
                                            <span class="fw-bold">Operation Cost:</span> <span id="opv-final-cost" class="text-success"></span>
                                        </li>
                                    </ul>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="msc-ipd-operations-history mt-4">
                        <h3 class="msc-ipd-section-title">Operation History <button class="float-end btn btn-warning"><i class="fas fa-sync-alt" onclick="FetchOperationRecords()"></i></button></h3>
                        <table class="table msc-smart-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Procedure</th>
                                    <th>Surgeon</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="operation-history-container">
                                @forelse ($operationHistory as $entry)
                                    
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No operation records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- View Operation Modal -->
                <div class="modal fade" id="viewOperationModal" tabindex="-1" aria-labelledby="viewOperationModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewOperationModalLabel">
                                    <i class="fas fa-eye text-info me-2"></i>Operation Details
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-bordered table-sm">
                                            <tbody>
                                                <tr>
                                                    <td class="fw-bold">Procedure Name:</td>
                                                    <td id="view-procedure"></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Operation Code:</td>
                                                    <td id="view-code"></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Scheduled Date:</td>
                                                    <td id="view-date"></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Surgeon:</td>
                                                    <td id="view-surgeon"></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Operation Type:</td>
                                                    <td id="view-type"></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Room/OT:</td>
                                                    <td id="view-room"></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Base Cost:</td>
                                                    <td id="view-base-cost" class="text-primary"></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Discount:</td>
                                                    <td id="view-discount" class="text-warning"></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Final Cost:</td>
                                                    <td id="view-final-cost" class="text-success"></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Status:</td>
                                                    <td id="view-status"></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Pre-operative Notes:</td>
                                                    <td id="view-notes"></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Payment Remarks:</td>
                                                    <td id="view-payment-remarks"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

            </x-module-check>

            <x-module-check route="hospit.ipd.manage.vitals">

                <div class="msc-tab-panel" id="msc.hospit.ipd.manage.vitals">
                    {{-- <h2 class="msc-ipd-panel-title">Vital Information</h2> --}}
                    <div class="msc-n-ipd-vitals-container">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="msc-n-ipd-section-title">
                                <i class="fas fa-heartbeat text-danger me-2"></i>
                                Patient Vitals
                            </h4>
                            <div>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#vitalRecordsModal">
                                <i class="fas fa-plus me-1"></i>Add New Vital
                            </button>
                            <button type="button" class="btn btn-warning" onclick="fetchVitalRecords(true)">
                                <i class="fas fa-undo me-1"></i>Refresh
                            </button>
                            </div>
                        </div>

                        <!-- Vitals Summary Cards -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="msc-n-ipd-vital-card bg-danger-subtle">
                                    <div class="card-body text-center">
                                        <i class="fas fa-heartbeat fa-2x text-danger mb-2"></i>
                                        <h5 class="card-title">Heart Rate</h5>
                                        <p class="card-text fs-4 fw-bold"><span id="msc-vital-heartrate-display">72</span> <small>bpm</small></p>
                                        <small class="text-muted">Last: <span id="msc-vital-heartrate-display-time">2 hours ago</span></small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="msc-n-ipd-vital-card bg-primary-subtle">
                                    <div class="card-body text-center">
                                        <i class="fas fa-thermometer-half fa-2x text-primary mb-2"></i>
                                        <h5 class="card-title">Temperature</h5>
                                        <p class="card-text fs-4 fw-bold"><span id="msc-vital-temprature-display">37</span> <small>F</small></p>
                                        <small class="text-muted">Last: <span id="msc-vital-temprature-display-time"></span></small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="msc-n-ipd-vital-card bg-warning-subtle">
                                    <div class="card-body text-center">
                                        <i class="fas fa-tachometer-alt fa-2x text-warning mb-2"></i>
                                        <h5 class="card-title">Blood Pressure</h5>
                                        <p class="card-text fs-4 fw-bold"><span id="msc-vital-blood-pressure-display"></span> <small>mmHg</small></p>
                                        <small class="text-muted">Last: <span id="msc-vital-blood-pressure-display-time"></span></small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="msc-n-ipd-vital-card bg-info-subtle">
                                    <div class="card-body text-center">
                                        <i class="fas fa-wind fa-2x text-info mb-2"></i>
                                        <h5 class="card-title">Oxygen Sat.</h5>
                                        <p class="card-text fs-4 fw-bold"><span id="msc-vital-oxygen-display"></span><small>%</small></p>
                                        <small class="text-muted">Last: <span id="msc-vital-oxygen-display-time"></span></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vitals Chart -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-chart-line me-2"></i>Vitals Trend (Last 24 Hours)
                                </h5>
                            </div>
                            <div class="card-body" id="vitalsChartContainer">
                                <canvas id="vitalsChart" height="100"></canvas>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-history me-2"></i>Vitals History
                                </h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-hover msc-smart-table">
                                    <thead>
                                        <tr>
                                            <th>Date & Time</th>
                                            <th>Heart Rate</th>
                                            <th>BP (Systolic/Diastolic)</th>
                                            <th>Temperature</th>
                                            <th>O2 Sat</th>
                                            <th>Recorded By</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="vitalsHistoryTableBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="vitalRecordsModal" tabindex="-1" aria-labelledby="vitalRecordsModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header msc-bg-dark text-white">
                                <h5 class="modal-title" id="vitalRecordsModalLabel">
                                    <i class="fas fa-heartbeat me-2"></i>Record Patient Vital Signs
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            
                            <form id="vitalRecordsForm" action="{{$module_actions['vitals.save']}}" data-callback="fetchVitalRecords()" method="POST" class="msc-ord-form">
                                <div class="modal-body">
                                    @csrf
                                    <input type="hidden" name="ipd_id" value="{{ Crypt::encrypt($details->id) }}">
                                    <input type="hidden" name="vital_id" id="vital_id" value="">
                                    
                                    <!-- Basic Information Section -->
                                    <div class="row mb-4">
                                        <div class="col-md-3 ">
                                            <div class="container-fluid px-0">
                                                <div class="row mb-4">
                                                    <div class="col-12">
                                                        <h6 class="text-primary border-bottom pb-2 mb-3">
                                                            <i class="fas fa-info-circle me-2"></i>Recording Information
                                                        </h6>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label for="recorded_at" class="form-label">
                                                                <i class="fas fa-clock me-1"></i>Recorded Date & Time
                                                            </label>
                                                            <input type="datetime-local" class="form-control" id="recorded_at" name="recorded_at" 
                                                                value="{{ date('Y-m-d\TH:i') }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-9 ">
                                            <div class="container-fluid px-0">
                                                <!-- Physical Measurements Section -->
                                                <div class="row mb-4">
                                                    <div class="col-12">
                                                        <h6 class="text-primary border-bottom pb-2 mb-3">
                                                            <i class="fas fa-ruler me-2"></i>Physical Measurements
                                                        </h6>
                                                    </div>
                                                    
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="weight" class="form-label">
                                                                <i class="fas fa-weight me-1"></i>Weight <small class="text-muted">(kg)</small>
                                                            </label>
                                                            <input type="number" class="form-control" id="weight" name="weight" 
                                                                step="0.01" min="1" max="500" placeholder="70.5">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="height" class="form-label">
                                                                <i class="fas fa-arrows-alt-v me-1"></i>Height <small class="text-muted">(cm)</small>
                                                            </label>
                                                            <input type="number" class="form-control" id="height" name="height" 
                                                                step="0.01" min="50" max="250" placeholder="170">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="bmi" class="form-label">
                                                                BMI <small class="text-muted">(calculated)</small>
                                                            </label>
                                                            <input type="number" class="form-control bg-light" id="bmi" name="bmi" 
                                                                step="0.01" readonly placeholder="Auto-calculated">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="container-fluid px-0">
                                                <!-- Primary Vital Signs Section -->
                                                <div class="row mb-4">
                                                    <div class="col-12">
                                                        <h6 class="text-primary border-bottom pb-2 mb-3">
                                                            <i class="fas fa-heartbeat me-2"></i>Primary Vital Signs
                                                        </h6>
                                                    </div>
                                                    
                                                    <!-- Blood Pressure -->
                                                    <div class="col-md-8">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <div class="mb-3">
                                                                    <label for="systolic_bp" class="form-label">
                                                                        Systolic BP <small class="text-muted">(mmHg)</small>
                                                                    </label>
                                                                    <input type="number" class="form-control vital-input" id="systolic_bp" 
                                                                        name="systolic_bp" step="1" min="50" max="300" placeholder="120">
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="mb-3">
                                                                    <label for="diastolic_bp" class="form-label">
                                                                        Diastolic BP <small class="text-muted">(mmHg)</small>
                                                                    </label>
                                                                    <input type="number" class="form-control vital-input" id="diastolic_bp" 
                                                                        name="diastolic_bp" step="1" min="30" max="200" placeholder="80">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="heart_rate" class="form-label">
                                                                <i class="fas fa-heartbeat me-1 text-danger"></i>Heart Rate <small class="text-muted">(bpm)</small>
                                                            </label>
                                                            <input type="number" class="form-control vital-input" id="heart_rate" 
                                                                name="heart_rate" min="30" max="300" placeholder="72">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-5">
                                                        <div class="mb-3">
                                                            <label for="respiratory_rate" class="form-label">
                                                                <i class="fas fa-lungs me-1 text-info"></i>Respiratory Rate <small class="text-muted">(breaths/min)</small>
                                                            </label>
                                                            <input type="number" class="form-control vital-input" id="respiratory_rate" 
                                                                name="respiratory_rate" min="5" max="60" placeholder="16">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-3">
                                                        <div class="mb-3">
                                                            <label for="temperature" class="form-label">
                                                                <i class="fas fa-thermometer-half me-1 text-warning"></i>Temperature <small class="text-muted">(°F)</small>
                                                            </label>
                                                            <input type="number" class="form-control vital-input" id="temperature" 
                                                                name="temperature" step="0.1" min="30" max="200" placeholder="98.5">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="oxygen_saturation" class="form-label">
                                                                <i class="fas fa-wind me-1 text-primary"></i>Oxygen Saturation <small class="text-muted">(%)</small>
                                                            </label>
                                                            <input type="number" class="form-control vital-input" id="oxygen_saturation" 
                                                                name="oxygen_saturation" step="0.01" min="70" max="100" placeholder="98">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                            <!-- Clinical Assessment Section -->
                                        <div class="col-md-6">
                                            <div class="container-fuid px-0">
                                                <div class="row mb-4">
                                                    <div class="col-12">
                                                        <h6 class="text-primary border-bottom pb-2 mb-3">
                                                            <i class="fas fa-stethoscope me-2"></i>Clinical Assessment
                                                        </h6>
                                                    </div>
                                                    
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label for="pain_level" class="form-label">
                                                                <i class="fas fa-sad-tear me-1 text-warning"></i>Pain Level <small class="text-muted">(0-10)</small>
                                                            </label>
                                                            <select class="form-select" id="pain_level" name="pain_level">
                                                                <option value="">Select Pain Level</option>
                                                                <option value="0">0 - No Pain</option>
                                                                <option value="1">1 - Very Mild</option>
                                                                <option value="2">2 - Mild</option>
                                                                <option value="3">3 - Moderate</option>
                                                                <option value="4">4 - Moderate+</option>
                                                                <option value="5">5 - Severe</option>
                                                                <option value="6">6 - Severe+</option>
                                                                <option value="7">7 - Very Severe</option>
                                                                <option value="8">8 - Extremely Severe</option>
                                                                <option value="9">9 - Unbearable</option>
                                                                <option value="10">10 - Maximum Pain</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="glucose_level" class="form-label">
                                                                <i class="fas fa-tint me-1 text-danger"></i>Blood Glucose <small class="text-muted">(mg/dL)</small>
                                                            </label>
                                                            <input type="number" class="form-control" id="glucose_level" name="glucose_level" 
                                                                step="0.01" min="20" max="800" placeholder="100">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="urine_output" class="form-label">
                                                                <i class="fas fa-prescription-bottle me-1"></i>Urine Output <small class="text-muted">(ml)</small>
                                                            </label>
                                                            <input type="number" class="form-control" id="urine_output" name="urine_output" 
                                                                step="0.01" min="0" max="5000" placeholder="1500">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Clinical Observations Section -->
                                        <div class="col-md-4">
                                            <div class="container-fuid px-0">
                                                <div class="row mb-4">
                                                    <div class="col-12">
                                                        <h6 class="text-primary border-bottom pb-2 mb-3">
                                                            <i class="fas fa-eye me-2"></i>Clinical Observations
                                                        </h6>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="consciousness_level" class="form-label">
                                                                <i class="fas fa-brain me-1"></i>Consciousness Level
                                                            </label>
                                                            <select class="form-select" id="consciousness_level" name="consciousness_level">
                                                                <option value="">Select Level</option>
                                                                <option value="Alert" selected>Alert</option>
                                                                <option value="Verbal">Verbal Response</option>
                                                                <option value="Pain">Pain Response</option>
                                                                <option value="Unresponsive">Unresponsive</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="pupil_response" class="form-label">
                                                                <i class="fas fa-eye me-1"></i>Pupil Response
                                                            </label>
                                                            <select class="form-select" id="pupil_response" name="pupil_response">
                                                                <option value="">Select Response</option>
                                                                <option value="Normal" selected>Normal</option>
                                                                <option value="Sluggish">Sluggish</option>
                                                                <option value="Non-reactive">Non-reactive</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Notes Section -->
                                        <div class="col-md-8">
                                            <div class="container-fuid px-0">
                                                <div class="row mb-3">
                                                    <div class="col-12">
                                                        <h6 class="text-primary border-bottom pb-2 mb-3">
                                                            <i class="fas fa-notes-medical me-2"></i>Clinical Notes
                                                        </h6>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="notes" class="form-label">
                                                                <i class="fas fa-sticky-note me-1"></i>General Notes
                                                            </label>
                                                            <textarea class="form-control" id="notes" name="notes" rows="4" 
                                                                    placeholder="Enter any additional observations or notes..."></textarea>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="abnormal_findings" class="form-label">
                                                                <i class="fas fa-exclamation-triangle me-1 text-warning"></i>Abnormal Findings
                                                            </label>
                                                            <textarea class="form-control" id="abnormal_findings" name="abnormal_findings" rows="4" 
                                                                    placeholder="Record any abnormal findings or concerns..."></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" id="closeVitalodal" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-1"></i>Cancel
                                    </button>
                                    <button type="button" class="btn btn-warning me-2" onclick="clearForm()">
                                        <i class="fas fa-undo me-1"></i>Clear Form
                                    </button>
                                    <button type="submit" class="btn btn-primary" onclick="$('#closeVitalodal').click();">
                                        <i class="fas fa-save me-1"></i>Save Vitals
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="viewVitalModal" tabindex="-1" aria-labelledby="viewVitalModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header msc-bg-dark text-white">
                                <h5 class="modal-title" id="viewVitalModalLabel">
                                    <i class="fas fa-heartbeat me-2"></i>Vital Signs Record
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            
                            <div class="modal-body">
                                <!-- Vital Summary Cards -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <div class="row align-items-center">
                                                <div class="col-md-6">
                                                    <h6 class="mb-0">
                                                        <i class="fas fa-clock me-2"></i>
                                                        <span id="view-recorded-date">Loading...</span>
                                                    </h6>
                                                </div>
                                                <div class="col-md-6 text-md-end">
                                                    <small class="text-muted">
                                                        <i class="fas fa-user-md me-1"></i>
                                                        Recorded by: <span id="view-recorded-by">Loading...</span>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Primary Vitals Grid -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="text-primary border-bottom pb-2 mb-3">
                                            <i class="fas fa-heartbeat me-2"></i>Primary Vital Signs
                                        </h6>
                                    </div>
                                    
                                    <div class="col-md-6 col-lg-3">
                                        <div class="msc-n-ipd-vital-view-card text-center">
                                            <i class="fas fa-heartbeat fa-2x text-danger mb-2"></i>
                                            <h6 class="card-title text-muted">Heart Rate</h6>
                                            <div class="vital-value" id="view-heart-rate">--</div>
                                            <small class="text-muted">bpm</small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-lg-3">
                                        <div class="msc-n-ipd-vital-view-card text-center">
                                            <i class="fas fa-thermometer-half fa-2x text-warning mb-2"></i>
                                            <h6 class="card-title text-muted">Temperature</h6>
                                            <div class="vital-value" id="view-temperature">--</div>
                                            <small class="text-muted">F</small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-lg-3">
                                        <div class="msc-n-ipd-vital-view-card text-center">
                                            <i class="fas fa-tachometer-alt fa-2x text-primary mb-2"></i>
                                            <h6 class="card-title text-muted">Blood Pressure</h6>
                                            <div class="vital-value">
                                                <span id="view-systolic-bp">--</span>/<span id="view-diastolic-bp">--</span>
                                            </div>
                                            <small class="text-muted">mmHg</small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-lg-3">
                                        <div class="msc-n-ipd-vital-view-card text-center">
                                            <i class="fas fa-wind fa-2x text-info mb-2"></i>
                                            <h6 class="card-title text-muted">Oxygen Sat.</h6>
                                            <div class="vital-value" id="view-oxygen-saturation">--</div>
                                            <small class="text-muted">%</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Secondary Vitals -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="text-primary border-bottom pb-2 mb-3">
                                            <i class="fas fa-lungs me-2"></i>Additional Measurements
                                        </h6>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="msc-n-ipd-info-item">
                                            <label class="msc-n-ipd-info-label">
                                                <i class="fas fa-lungs me-1 text-info"></i>Respiratory Rate
                                            </label>
                                            <div class="msc-n-ipd-info-value">
                                                <span id="view-respiratory-rate">--</span> <small class="text-muted">breaths/min</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="msc-n-ipd-info-item">
                                            <label class="msc-n-ipd-info-label">
                                                <i class="fas fa-weight me-1"></i>Weight
                                            </label>
                                            <div class="msc-n-ipd-info-value">
                                                <span id="view-weight">--</span> <small class="text-muted">kg</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="msc-n-ipd-info-item">
                                            <label class="msc-n-ipd-info-label">
                                                <i class="fas fa-arrows-alt-v me-1"></i>Height
                                            </label>
                                            <div class="msc-n-ipd-info-value">
                                                <span id="view-height">--</span> <small class="text-muted">cm</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Clinical Assessment -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="text-primary border-bottom pb-2 mb-3">
                                            <i class="fas fa-stethoscope me-2"></i>Clinical Assessment
                                        </h6>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="msc-n-ipd-info-item">
                                            <label class="msc-n-ipd-info-label">
                                                <i class="fas fa-calculator me-1"></i>BMI
                                            </label>
                                            <div class="msc-n-ipd-info-value">
                                                <span id="view-bmi" class="bmi-value">--</span>
                                                <small id="view-bmi-category" class="text-muted d-block">--</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="msc-n-ipd-info-item">
                                            <label class="msc-n-ipd-info-label">
                                                <i class="fas fa-sad-tear me-1 text-warning"></i>Pain Level
                                            </label>
                                            <div class="msc-n-ipd-info-value">
                                                <span id="view-pain-level" class="pain-value">--</span><span class="pain-scale">/10</span>
                                                <div id="view-pain-indicator" class="pain-indicator mt-1"></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="msc-n-ipd-info-item">
                                            <label class="msc-n-ipd-info-label">
                                                <i class="fas fa-tint me-1 text-danger"></i>Blood Glucose
                                            </label>
                                            <div class="msc-n-ipd-info-value">
                                                <span id="view-glucose-level">--</span> <small class="text-muted">mg/dL</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="msc-n-ipd-info-item">
                                            <label class="msc-n-ipd-info-label">
                                                <i class="fas fa-prescription-bottle me-1"></i>Urine Output
                                            </label>
                                            <div class="msc-n-ipd-info-value">
                                                <span id="view-urine-output">--</span> <small class="text-muted">ml</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Clinical Observations -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="text-primary border-bottom pb-2 mb-3">
                                            <i class="fas fa-eye me-2"></i>Clinical Observations
                                        </h6>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="msc-n-ipd-info-item">
                                            <label class="msc-n-ipd-info-label">
                                                <i class="fas fa-brain me-1"></i>Consciousness Level
                                            </label>
                                            <div class="msc-n-ipd-info-value">
                                                <span id="view-consciousness-level" class="badge">--</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="msc-n-ipd-info-item">
                                            <label class="msc-n-ipd-info-label">
                                                <i class="fas fa-eye me-1"></i>Pupil Response
                                            </label>
                                            <div class="msc-n-ipd-info-value">
                                                <span id="view-pupil-response" class="badge">--</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Notes Section -->
                                <div class="row" id="notes-section" style="display: none;">
                                    <div class="col-12">
                                        <h6 class="text-primary border-bottom pb-2 mb-3">
                                            <i class="fas fa-notes-medical me-2"></i>Clinical Notes
                                        </h6>
                                    </div>
                                    
                                    <div class="col-md-6" id="general-notes-container" style="display: none;">
                                        <div class="msc-n-ipd-note-card">
                                            <h6><i class="fas fa-sticky-note me-1"></i>General Notes</h6>
                                            <p id="view-notes" class="mb-0"></p>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6" id="abnormal-findings-container" style="display: none;">
                                        <div class="msc-n-ipd-note-card alert-warning">
                                            <h6><i class="fas fa-exclamation-triangle me-1 text-warning"></i>Abnormal Findings</h6>
                                            <p id="view-abnormal-findings" class="mb-0"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i>Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function updateVitalsDisplay(data) {
                        for (let index = 0; index < data.length; index++) {
                            if (data[index].heart_rate != null && data[index].heart_rate != '') {
                                $('#msc-vital-heartrate-display').text(data[index].heart_rate);
                                $('#msc-vital-heartrate-display-time').text(data[index].recorded_at_human_readable);
                                break;
                            }
                        }
                        for (let index = 0; index < data.length; index++) {
                            if (data[index].temperature != null && data[index].temperature != '') {
                                $('#msc-vital-temprature-display').text(data[index].temperature);
                                $('#msc-vital-temprature-display-time').text(data[index].recorded_at_human_readable);
                                break;
                            }
                        }
                        for (let index = 0; index < data.length; index++) {
                            if (data[index].systolic_bp != null && data[index].systolic_bp != '' && data[index].diastolic_bp != null && data[index].diastolic_bp != '') {
                                $('#msc-vital-blood-pressure-display').text(data[index].systolic_bp + '/' + data[index].diastolic_bp);
                                $('#msc-vital-blood-pressure-display-time').text(data[index].recorded_at_human_readable);
                                break;
                                break;
                            }
                        }
                        for (let index = 0; index < data.length; index++) {
                            if (data[index].oxygen_saturation != null && data[index].oxygen_saturation != '') {
                                $('#msc-vital-oxygen-display').text(data[index].oxygen_saturation);
                                $('#msc-vital-oxygen-display-time').text(data[index].recorded_at_human_readable);
                                break;
                                break;
                            }
                        }

                    }
                    function fetchVitalRecords(response_status = false) {
                        $.ajax({
                            url: "{{$module_actions['vitals.latest']}}",
                            method: 'POST',
                            data: {
                                ipd_id: "{{ Crypt::encrypt($details->id) }}",
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                if (response) {
                                    updateVitalsDisplay(response.data);
                                    $("#vitalsHistoryTableBody").empty();
                                    // $("#vitalsChartContainer").html('<canvas id="vitalsChart" height="100"></canvas>');
                                    
                                    if (window.vitalsChartGraph) {
                                        window.vitalsChartGraph.data.labels = response.graph.labels;
                                        window.vitalsChartGraph.data.datasets[0].data = response.graph.HR;
                                        window.vitalsChartGraph.data.datasets[1].data = response.graph.temperature;
                                        window.vitalsChartGraph.data.datasets[2].data = response.graph.O2;
                                        window.vitalsChartGraph.update();
                                    }else{
                                        // console.log(window.vitalsChartGraph);
                                        initVitalsChart(response.graph)
                                    }
                                    response.data.forEach(function(vital) {
                                        const row_string = JSON.stringify(vital);
                                        const row = `<tr>
                                            <td>${vital.recorded_at ?? "--"}</td>
                                            <td>${vital.heart_rate ?? "--"} bpm</td>
                                            <td>${vital.systolic_bp ?? "--"}/${vital.diastolic_bp ?? "--"} mmHg</td>
                                            <td>${vital.temperature ?? "--"} F</td>
                                            <td>${vital.oxygen_saturation ?? "--"}%</td>
                                            <td>${vital.recorded_by_name}</td>
                                            <td>
                                                <button class="btn btn-sm btn-primary me-1"  data-bs-toggle="modal" data-bs-target="#viewVitalModal" onclick="viewVital(this)" data-row='${row_string}'>
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#vitalRecordsModal" onclick="editVital(this)" data-row='${row_string}'>
                                                    <i class="fas fa-edit"></i> 
                                                </button>
                                                <button class="btn btn-sm btn-danger MscDeleteRowBtn" href="{{$module_actions['vitals.delete']}}?vital=${vital.encrypted_id}" data-callback="fetchVitalRecords()">
                                                    <i class="fas fa-trash"></i>    
                                                </button>
                                                <a href="ipd/vitals/print?vital=${vital.encrypted_id}" target="_blank" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-print"></i>    
                                                </a>
                                            </td>
                                        </tr>`;
                                        $("#vitalsHistoryTableBody").append(row);
                                    });
                                    if (response_status === true) {
                                        mscToast({'msg': 'Vitals Refreshed', 'color': 'success', 'icon': 'fas fa-sync-alt'});
                                    }
                                    // Initialize DataTable
                                } else {
                                    console.error('Error fetching vital records:', response.message);
                                }
                            },
                            error: function(err) {
                                const res = JSON.parse(err.responseJSON.message);
                                mscToast({
                                    msg:res.msg,
                                    color:res.color,
                                    icon:res.icon,
                                });
                                // fetchVitalRecords();
                            }
                        });
                    }
                    $(document).ready(function() {
                    
    
                        $('#edit-from-view-btn').on('click', function() {
                            const vitalData = $('#viewVitalModal').data('vital-data');
                            if (vitalData) {
                                $('#viewVitalModal').modal('hide');
                                
                                // Wait for modal to hide, then show edit modal
                                setTimeout(() => {
                                    if (window.VitalManager && typeof window.VitalManager.editVital === 'function') {
                                        window.VitalManager.editVital(vitalData);
                                    } else {
                                        populateVitalModalForm(vitalData);
                                        $('#vitalRecordsModal').modal('show');
                                    }
                                }, 300);
                            }
                        });
                        
                        
                        $('#weight, #height').on('input', calculateBMI);
                        
                        // Vital signs validation with color coding
                        
                        
                        $('.vital-input').on('input', validateVitalSigns);
                        // Reset modal when closed
                        $('#vitalRecordsModal').on('hidden.bs.modal', function() {
                            clearForm();
                        });
                    });
                    function calculateBMI() {
                            const weight = parseFloat($('#weight').val());
                            const height = parseFloat($('#height').val());
                            
                            if (weight && height) {
                                const heightInMeters = height / 100;
                                const bmi = (weight / (heightInMeters * heightInMeters)).toFixed(2);
                                $('#bmi').val(bmi);
                                
                                // Add BMI classification styling
                                const bmiField = $('#bmi');
                                bmiField.removeClass('text-success text-warning text-danger');
                                
                                if (bmi < 18.5) {
                                    bmiField.addClass('text-info');
                                } else if (bmi >= 18.5 && bmi < 25) {
                                    bmiField.addClass('text-success');
                                } else if (bmi >= 25 && bmi < 30) {
                                    bmiField.addClass('text-warning');
                                } else {
                                    bmiField.addClass('text-danger');
                                }
                            } else {
                                $('#bmi').val('');
                            }
                        }
                    function validateVitalSigns() {
                            // Heart Rate validation
                            const heartRate = parseInt($('#heart_rate').val());
                            const hrField = $('#heart_rate');
                            
                            if (heartRate) {
                                hrField.removeClass('border-success border-warning border-danger');
                                if (heartRate >= 60 && heartRate <= 100) {
                                    hrField.addClass('border-success border-2');
                                } else if ((heartRate >= 50 && heartRate < 60) || (heartRate > 100 && heartRate <= 120)) {
                                    hrField.addClass('border-warning border-2');
                                } else {
                                    hrField.addClass('border-danger border-2');
                                }
                            }
                            
                            // Temperature validation (Celsius)
                            const temp = parseFloat($('#temperature').val());
                            const tempField = $('#temperature');
                            
                            if (temp) {
                                tempField.removeClass('border-success border-warning border-danger');
                                if (temp >= 97.5 && temp <= 99.5) {
                                    tempField.addClass('border-success border-2');
                                } else if ((temp >= 95.0 && temp < 97.5) || (temp > 99.5 && temp <= 102.0)) {
                                    tempField.addClass('border-warning border-2');
                                } else {
                                    tempField.addClass('border-danger border-2');
                                }
                            }
                            
                            // Blood Pressure validation
                            const systolic = parseInt($('#systolic_bp').val());
                            const diastolic = parseInt($('#diastolic_bp').val());
                            
                            if (systolic && diastolic) {
                                const bpFields = $('#systolic_bp, #diastolic_bp');
                                bpFields.removeClass('border-success border-warning border-danger');
                                
                                if (systolic < 120 && diastolic < 80) {
                                    bpFields.addClass('border-success border-2');
                                } else if (systolic < 140 && diastolic < 90) {
                                    bpFields.addClass('border-warning border-2');
                                } else {
                                    bpFields.addClass('border-danger border-2');
                                }
                            }
                            
                            // Oxygen Saturation validation
                            const o2Sat = parseFloat($('#oxygen_saturation').val());
                            const o2Field = $('#oxygen_saturation');
                            
                            if (o2Sat) {
                                o2Field.removeClass('border-success border-warning border-danger');
                                if (o2Sat >= 95) {
                                    o2Field.addClass('border-success border-2');
                                } else if (o2Sat >= 90) {
                                    o2Field.addClass('border-warning border-2');
                                } else {
                                    o2Field.addClass('border-danger border-2');
                                }
                            }
                        }
                    function populateViewModal(data) {
                            // Basic Information
                            $('#view-recorded-date').text(formatDateTime(data.recorded_at));
                            $('#view-recorded-by').text(data.recorded_by_name || 'Unknown');
                            $('#print-vital-btn').attr("href","ipd/vitals/print?vital=" + data.encrypted_id);

                            
                            // Primary Vitals
                            populateVitalCard('#view-heart-rate', data.heart_rate, 'vital', getHeartRateStatus(data.heart_rate));
                            populateVitalCard('#view-temperature', data.temperature, 'vital', getTemperatureStatus(data.temperature));
                            populateVitalCard('#view-systolic-bp', data.systolic_bp, 'number');
                            populateVitalCard('#view-diastolic-bp', data.diastolic_bp, 'number');
                            populateVitalCard('#view-oxygen-saturation', data.oxygen_saturation, 'vital', getOxygenStatus(data.oxygen_saturation));
                            
                            // Additional Measurements
                            populateVitalCard('#view-respiratory-rate', data.respiratory_rate, 'number');
                            populateVitalCard('#view-weight', data.weight, 'number');
                            populateVitalCard('#view-height', data.height, 'number');
                            
                            // Clinical Assessment
                            populateBMI(data.bmi);
                            populatePainLevel(data.pain_level);
                            populateVitalCard('#view-glucose-level', data.glucose_level, 'number');
                            populateVitalCard('#view-urine-output', data.urine_output, 'number');
                            
                            // Clinical Observations
                            populateConsciousnessLevel(data.consciousness_level);
                            populatePupilResponse(data.pupil_response);
                            
                            // Notes
                            populateNotes(data.notes, data.abnormal_findings);
                            
                            // Store data for edit functionality
                            $('#viewVitalModal').data('vital-data', data);
                        }
                        
                        // Helper function to populate vital cards with status
                    
                        // Fetch vital data from server
                        function fetchAndShowVital(data) {
                            // $.ajax({
                            //     url: `/vitals/${vitalId}`,
                            //     method: 'GET',
                            //     success: function(response) {
                            //         if (response.success && response.data) {
                                //             $('#viewVitalModal').modal('show');
                                //         } else {
                                    //             alert('Failed to load vital data');
                                    //         }
                                    //     },
                                    //     error: function() {
                                        //         alert('Error loading vital record');
                                        //     }
                                        // });
                            populateViewModal(data);
                        }
                    // Clear form function
                    function clearForm() {
                        $('#vitalRecordsForm')[0].reset();
                        $('#vital_id').val('');
                        $('#recorded_at').val(new Date().toISOString().slice(0, 16));
                        $('.vital-input').removeClass('border-success border-warning border-danger border-2');
                        $('#bmi').removeClass('text-success text-warning text-danger text-info').val('');
                        $('#vitalRecordsModalLabel').html('<i class="fas fa-heartbeat me-2"></i>Record Patient Vital Signs');
                    }

                    // Function to populate form for editing
                    function populateVitalForm(data) {
                        const fields = JSON.parse(data)
                        // console.log(fields)
                        $('#vital_id').val(fields.encrypted_id);
                        $('#recorded_at').val(fields.recorded_at_date);
                        $('#systolic_bp').val(fields.systolic_bp);
                        $('#diastolic_bp').val(fields.diastolic_bp);
                        $('#heart_rate').val(fields.heart_rate);
                        $('#respiratory_rate').val(fields.respiratory_rate);
                        $('#temperature').val(fields.temperature);
                        $('#oxygen_saturation').val(fields.oxygen_saturation);
                        $('#weight').val(fields.weight);
                        $('#height').val(fields.height);
                        $('#bmi').val(fields.bmi);
                        $('#pain_level').val(fields.pain_level);
                        $('#glucose_level').val(fields.glucose_level);
                        $('#consciousness_level').val(fields.consciousness_level);
                        $('#pupil_response').val(fields.pupil_response);
                        $('#urine_output').val(fields.urine_output);
                        $('#notes').val(fields.notes);
                        $('#abnormal_findings').val(fields.abnormal_findings);
                        
                        $('#vitalRecordsModalLabel').html('<i class="fas fa-edit me-2"></i>Edit Vital Signs');
                    }

                    // Show alert function
                    function showAlert(message, type) {
                        const alertHtml = `
                            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                                ${message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `;
                        
                        $('.msc-n-ipd-vitals-container').prepend(alertHtml);
                        
                        setTimeout(() => {
                            $('.alert').fadeOut(function() {
                                $(this).remove();
                            });
                        }, 5000);
                    }
                    function populateVitalCard(selector, value, type = 'number', status = 'normal') {
                            const element = $(selector);
                            if (value !== null && value !== undefined && value !== '') {
                                element.text(value).removeClass('vital-normal vital-warning vital-critical').addClass(`vital-${status}`);
                            } else {
                                element.text('--').removeClass('vital-normal vital-warning vital-critical');
                            }
                        }
                        
                        // BMI Population
                        function populateBMI(bmi) {
                            const bmiElement = $('#view-bmi');
                            const categoryElement = $('#view-bmi-category');
                            
                            if (bmi && bmi > 0) {
                                bmiElement.text(parseFloat(bmi).toFixed(1));
                                
                                let category, className;
                                if (bmi < 18.5) {
                                    category = 'Underweight';
                                    className = 'underweight';
                                } else if (bmi < 25) {
                                    category = 'Normal';
                                    className = 'normal';
                                } else if (bmi < 30) {
                                    category = 'Overweight';
                                    className = 'overweight';
                                } else {
                                    category = 'Obese';
                                    className = 'obese';
                                }
                                
                                bmiElement.removeClass('underweight normal overweight obese').addClass(className);
                                categoryElement.text(category);
                            } else {
                                bmiElement.text('--').removeClass('underweight normal overweight obese');
                                categoryElement.text('--');
                            }
                        }
                        
                        // Pain Level Population
                        function populatePainLevel(painLevel) {
                            const painElement = $('#view-pain-level');
                            const indicatorElement = $('#view-pain-indicator');
                            
                            if (painLevel !== null && painLevel !== undefined && painLevel !== '') {
                                painElement.text(painLevel);
                                
                                let className;
                                if (painLevel <= 2) {
                                    className = 'low';
                                } else if (painLevel <= 5) {
                                    className = 'moderate';
                                } else if (painLevel <= 7) {
                                    className = 'high';
                                } else {
                                    className = 'severe';
                                }
                                
                                painElement.removeClass('low moderate high severe').addClass(className);
                                indicatorElement.removeClass('low moderate high severe').addClass(className);
                            } else {
                                painElement.text('--').removeClass('low moderate high severe');
                                indicatorElement.removeClass('low moderate high severe');
                            }
                        }
                        
                        // Consciousness Level Population
                        function populateConsciousnessLevel(level) {
                            const element = $('#view-consciousness-level');
                            if (level) {
                                element.text(level).removeClass('consciousness-alert consciousness-verbal consciousness-pain consciousness-unresponsive')
                                    .addClass(`consciousness-${level.toLowerCase()}`);
                            } else {
                                element.text('--').removeClass('consciousness-alert consciousness-verbal consciousness-pain consciousness-unresponsive');
                            }
                        }
                        
                        // Pupil Response Population
                        function populatePupilResponse(response) {
                            const element = $('#view-pupil-response');
                            if (response) {
                                const className = response.toLowerCase().replace('-', '');
                                element.text(response).removeClass('pupil-normal pupil-sluggish pupil-non-reactive')
                                    .addClass(`pupil-${className}`);
                            } else {
                                element.text('--').removeClass('pupil-normal pupil-sluggish pupil-non-reactive');
                            }
                        }
                        
                        // Notes Population
                        function populateNotes(notes, abnormalFindings) {
                            const notesSection = $('#notes-section');
                            const generalNotesContainer = $('#general-notes-container');
                            const abnormalFindingsContainer = $('#abnormal-findings-container');
                            
                            let hasNotes = false;
                            
                            if (notes && notes.trim() !== '') {
                                $('#view-notes').text(notes);
                                generalNotesContainer.show();
                                hasNotes = true;
                            } else {
                                generalNotesContainer.hide();
                            }
                            
                            if (abnormalFindings && abnormalFindings.trim() !== '') {
                                $('#view-abnormal-findings').text(abnormalFindings);
                                abnormalFindingsContainer.show();
                                hasNotes = true;
                            } else {
                                abnormalFindingsContainer.hide();
                            }
                            
                            if (hasNotes) {
                                notesSection.show();
                            } else {
                                notesSection.hide();
                            }
                        }
                        
                        // Status determination functions
                        function getHeartRateStatus(hr) {
                            if (!hr) return 'normal';
                            if (hr >= 60 && hr <= 100) return 'normal';
                            if ((hr >= 50 && hr < 60) || (hr > 100 && hr <= 120)) return 'warning';
                            return 'critical';
                        }
                        
                        function getTemperatureStatus(temp) {
                            if (!temp) return 'normal';
                            if (temp >= 97.5 && temp <= 99.5) return 'normal';
                            if ((temp >= 95 && temp < 97.5) || (temp > 100.4 && temp <= 103)) return 'warning';
                            return 'critical';
                        }
                        
                        function getOxygenStatus(o2) {
                            if (!o2) return 'normal';
                            if (o2 >= 95) return 'normal';
                            if (o2 >= 90) return 'warning';
                            return 'critical';
                        }
                        
                        // Format datetime for display
                        function formatDateTime(dateTimeString) {
                            if (!dateTimeString) return '--';
                            
                            try {
                                const date = new Date(dateTimeString);
                                return date.toLocaleString('en-US', {
                                    year: 'numeric',
                                    month: '2-digit',
                                    day: '2-digit',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: true
                                });
                            } catch (error) {
                                return dateTimeString;
                            }
                        }
                        function viewVital(element) {
                        populateViewModal(JSON.parse(element.getAttribute('data-row')));
                        populateVitalForm(element.getAttribute('data-row'));
                    }
                    // Vitals Chart (using Chart.js)
                    $(document).ready(function() {
                        // Check if Chart.js is loaded
                        if (typeof Chart !== 'undefined') {
                            // initVitalsChart();
                            fetchVitalRecords();
                        } else {
                            console.error('Chart.js library is not loaded');
                            // Show a message or hide the chart container
                            $('#vitalsChart').closest('.card').hide();
                        }
                    });

                    function initVitalsChart(data) {

                        const ctx = document.getElementById('vitalsChart');

                        if (!ctx) return;
                        // if (window.vitalsChart) {
                        //     window.vitalsChart.destroy();
                        //     window.vitalsChart = null; // Optional: clear the reference
                        // }
                        window.vitalsChartGraph = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: data.labels,
                                datasets: [{
                                    label: 'Heart Rate (bpm)',
                                    data: data.HR,
                                    borderColor: '#dc3545',
                                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                                    tension: 0.4,
                                    fill: false
                                }, {
                                    label: 'Temperature (°F)',
                                    data: data.temperature,
                                    borderColor: '#0d6efd',
                                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                                    tension: 0.4,
                                    fill: false,
                                    yAxisID: 'y1'
                                }, {
                                    label: 'Oxygen Saturation (%)',
                                    data: data.O2,
                                    borderColor: '#198754',
                                    backgroundColor: 'rgba(25, 135, 84, 0.1)',
                                    tension: 0.4,
                                    fill: false,
                                    yAxisID: 'y2'
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: {
                                    mode: 'index',
                                    intersect: false,
                                },
                                plugins: {
                                    title: {
                                        display: true,
                                        text: 'Patient Vital Signs Trend'
                                    },
                                    legend: {
                                        position: 'top',
                                    }
                                },
                                scales: {
                                    x: {
                                        display: true,
                                        title: {
                                            display: true,
                                            text: 'Time'
                                        }
                                    },
                                    y: {
                                        type: 'linear',
                                        display: true,
                                        position: 'left',
                                        title: {
                                            display: true,
                                            text: 'Heart Rate (bpm)'
                                        },
                                        min: 60,
                                        max: 100
                                    },
                                    y1: {
                                        type: 'linear',
                                        display: true,
                                        position: 'right',
                                        title: {
                                            display: true,
                                            text: 'Temperature (°F)'
                                        },
                                        min: 97,
                                        max: 101,
                                        grid: {
                                            drawOnChartArea: false,
                                        },
                                    },
                                    y2: {
                                        type: 'linear',
                                        display: false,
                                        min: 95,
                                        max: 100
                                    }
                                }
                            }
                        });
                    }

                    // Alternative: Simple chart without Chart.js (CSS-only)
                    function createSimpleChart() {
                        const chartContainer = document.getElementById('vitalsChart');
                        if (!chartContainer) return;
                        
                        chartContainer.innerHTML = `
                            <div class="simple-chart">
                                <div class="chart-message">
                                    <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Chart.js library not loaded.<br>Vitals data available in table below.</p>
                                </div>
                            </div>
                        `;
                    }

                    // Vital Management Functions


                    function editVital(element) {
                        // console.log('Editing vital:', data);
                        populateVitalForm(element.getAttribute('data-row'));
                    }

                    function deleteVital(id) {
                        if (confirm('Are you sure you want to delete this vital record?')) {
                            $.ajax({
                                url: `/vitals/${id}`,
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function() {
                                    // Remove row from table or reload page
                                    $(`tr[data-vital-id="${id}"]`).remove();
                                    showAlert('Vital record deleted successfully', 'success');
                                },
                                error: function() {
                                    alert('Error deleting vital record');
                                }
                            });
                        }
                    }

                    // Helper functions
                    function validateVitalData(data) {
                        // Add validation logic here
                        if (data.heart_rate && (data.heart_rate < 30 || data.heart_rate > 300)) {
                            alert('Heart rate must be between 30 and 300 bpm');
                            return false;
                        }
                        
                        if (data.temperature && (data.temperature < 90 || data.temperature > 110)) {
                            alert('Temperature must be between 90 and 110°F');
                            return false;
                        }
                        
                        return true;
                    }

                    function showAlert(message, type) {
                        const alertHtml = `
                            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                                ${message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `;
                        
                        // Insert alert at the top of the vitals container
                        $('.msc-n-ipd-vitals-container').prepend(alertHtml);
                        
                        // Auto-remove after 5 seconds
                        setTimeout(() => {
                            $('.alert').alert('close');
                        }, 5000);
                    }



                </script>
            </x-module-check>

            <x-module-check route="hospit.ipd.manage.room_allotment">

               <div class="msc-tab-panel" id="msc.hospit.ipd.manage.room_allotment">
                    <!-- Room Status Summary Cards -->
                    
                    {{-- <div class="msc-room-allotment-summary-cards">
                        <div class="msc-room-allotment-cardbox col-md-6">
                            <span class="msc-room-allotment-label"><i class="fas fa-bed me-1"></i> Current Room</span>
                            <span class="msc-room-allotment-value current">SF-105 (ICU)</span>
                        </div>
                        <div class="msc-room-allotment-cardbox">
                            <span class="msc-room-allotment-label"><i class="fas fa-check-circle me-1"></i> Available Rooms</span>
                            <span class="msc-room-allotment-value available">12</span>
                        </div>
                        <div class="msc-room-allotment-cardbox">
                            <span class="msc-room-allotment-label"><i class="fas fa-user-injured me-1"></i> Occupied Rooms</span>
                            <span class="msc-room-allotment-value occupied">38</span>
                        </div>
                        <div class="msc-room-allotment-cardbox">
                            <span class="msc-room-allotment-label"><i class="fas fa-building me-1"></i> Total Rooms</span>
                            <span class="msc-room-allotment-value total">50</span>
                        </div>
                    </div> --}}

                    <!-- Room Allotment Form -->
                    <form class="msc-room-allotment-form msc-ord-form" method="POST" action="{{$module_actions['room-allotment.save'] ?? '#'}}" id="msc-room-allotment-form" data-callback="fetchRoomAllotmentData()">
                        @csrf
                        <input type="hidden" name="ipd_id" value="{{Crypt::encrypt($details->id)}}">
                        
                        <h3 class="msc-room-allotment-section-title">
                            <i class="fas fa-home text-primary me-2"></i>Room Assignment
                        </h3>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="msc-room-allotment-form-group">
                                    <label for="msc-room-allotment-room-type" class="msc-room-allotment-form-label">Room Type </label>
                                    <select id="msc-room-allotment-room-type" name="room_category" class="msc-room-allotment-form-select" required onfocus="fetchRelatedList({
                                        'data': {
                                                'type': [
                                                            '{{Crypt::encrypt('patient_accommodation')}}',
                                                            '{{Crypt::encrypt('critical_care')}}'
                                                        ]
                                                },
                                        'target': '#msc-room-allotment-room-type',
                                        'url': '{{$module_actions['room-allotment.fetch.room_category']}}',
                                        'showLoading': false,
                                    })">
                                        <option value="">Select room type</option>
                                        
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="msc-room-allotment-form-group">
                                    <label for="msc-room-allotment-room-number" class="msc-room-allotment-form-label">Room Number </label>
                                    <select id="msc-room-allotment-room-number" name="room_number" class="msc-room-allotment-form-select" required onfocus="fetchRelatedList({
                                        'data': {
                                                'category_id': $('#msc-room-allotment-room-type').val(),
                                                },
                                        'target': '#msc-room-allotment-room-number',
                                        'url': '{{$module_actions['room-allotment.fetch.room']}}',
                                        'showLoading': false,
                                    })">
                                        <option value="">Select room first</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="msc-room-allotment-form-group">
                                    <label for="msc-room-allotment-bed-number" class="msc-room-allotment-form-label">Bed Number </label>
                                    <select id="msc-room-allotment-bed-number" name="bed_number" class="msc-room-allotment-form-select" required onfocus="fetchRelatedList({
                                            'data': {
                                            'room_id': $('#msc-room-allotment-room-number').val(),
                                            },
                                            'target': '#msc-room-allotment-bed-number',
                                            'url': '{{ route('api.fetch.beds') }}'
                                        })">
                                        <option value="">Select bed</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="msc-room-allotment-form-group">
                                    <label for="msc-room-allotment-allotment-date" class="msc-room-allotment-form-label">Allotment Date </label>
                                    <input type="datetime-local" id="msc-room-allotment-allotment-date" name="allotment_date" class="msc-room-allotment-form-input" required value="{{date('Y-m-d\TH:i')}}">
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="msc-room-allotment-form-group">
                                    <label for="msc-room-allotment-transfer-reason" class="msc-room-allotment-form-label">Transfer Reason</label>
                                    <select id="msc-room-allotment-transfer-reason" name="transfer_reason" class="msc-room-allotment-form-select">
                                        <option value="">Select reason</option>
                                        <option value="initial_admission">Initial Admission</option>
                                        <option value="medical_requirement">Medical Requirement</option>
                                        <option value="patient_request">Patient Request</option>
                                        <option value="room_maintenance">Room Maintenance</option>
                                        <option value="isolation_required">Isolation Required</option>
                                        <option value="upgrade">Room Upgrade</option>
                                        <option value="downgrade">Room Downgrade</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="msc-room-allotment-form-group">
                                    <label for="msc-room-allotment-room-notes" class="msc-room-allotment-form-label">Special Requirements</label>
                                    <textarea id="msc-room-allotment-room-notes" name="special_requirements" class="msc-room-allotment-form-textarea" placeholder="AC, ventilator access, isolation, wheelchair access, etc." rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="msc-room-allotment-btn-primary mt-3">
                            <i class="fas fa-bed me-2"></i>Assign Room
                        </button>
                    </form>

                    <!-- Room Transfer History -->
                    <div class="msc-room-allotment-room-history">
                        <h3 class="msc-room-allotment-section-title">
                            <i class="fas fa-history text-info me-2"></i>Room Transfer History <div class="text-end mb-2 float-end">
                        <button id="mscRefreshRoomAllotment" onclick="fetchRoomAllotmentData()" class="btn btn-warning"><i class="fas fa-sync"></i> Refresh</button>
                    </div>
                        </h3>
                        <table class="msc-room-allotment-data-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Room</th>
                                    <th>Bed</th>
                                    <th>Floor</th>
                                    <th>Duration</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($roomHistory ?? [
                                    (object)['id' => 1, 'allotment_date' => '2025-01-18', 'room_number' => 'SF-105', 'room_category' => 'ICU', 'bed_number' => 'Bed 1', 'floor' => '2nd Floor', 'transfer_reason' => 'Post-operative care', 'status' => 'current'],
                                    (object)['id' => 2, 'allotment_date' => '2025-01-15', 'room_number' => 'GW-20', 'room_category' => 'General', 'bed_number' => 'Bed 3', 'floor' => '1st Floor', 'transfer_reason' => 'Initial Admission', 'status' => 'transferred']
                                ] as $room)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($room->allotment_date)->format('d/m/Y H:i') }}</td>
                                        <td>{{ $room->room_number }} ({{ ucfirst($room->room_category) }})</td>
                                        <td>{{ $room->bed_number }}</td>
                                        <td>{{ $room->floor }}</td>
                                        <td>
                                            @if($room->status == 'current')
                                                <span class="fw-bold text-success">Current</span>
                                            @else
                                                {{ \Carbon\Carbon::parse($room->allotment_date)->diffForHumans() }}
                                            @endif
                                        </td>
                                        <td>{{ $room->transfer_reason }}</td>
                                        <td>
                                            <span class="msc-room-allotment-status {{ $room->status == 'current' ? 'msc-room-allotment-current' : 'msc-room-allotment-transferred' }}">
                                                {{ ucfirst($room->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info me-1" onclick="viewRoomDetails('{{ Crypt::encrypt($room->id) }}')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($room->status == 'current')
                                                <button type="button" class="btn btn-sm btn-warning" onclick="transferRoom('{{ Crypt::encrypt($room->id) }}')">
                                                    <i class="fas fa-exchange-alt"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No room allotment history found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <script>
                    // function updateRoomAllotmentCards(data) {
                    //     $('.msc-room-allotment-value.current').text('Loading...');
                        
                    //      $('.msc-room-allotment-value.current').fadeOut(200, function() {
                    //         $(this).text(data.current_assignment).fadeIn(200);
                    //     });
                        
                    //     $('#lastUpdated').text(data.updated_at);
                    // }
                    function updateRoomAllotmentTable(rooms) {
                        const tableBody = $('.msc-room-allotment-data-table tbody');
                        tableBody.empty();
                        
                        if (!rooms || rooms.length === 0) {
                            tableBody.append('<tr><td colspan="8" class="text-center text-muted">No room allotment history found.</td></tr>');
                            return;
                        }
                        
                        rooms.forEach(room => {
                            const row = `
                                <tr>
                                    <td>${room.allotment_date}</td>
                                    <td>${room.room_name} (${room.room_category_name})</td>
                                    <td>${room.bed_number}</td>
                                    <td>${room.floor}</td>
                                    <td>${room.duration}</td>
                                    <td>${room.transfer_reason}</td>
                                    <td class="${room.to_date == null ? 'msc-room-allotment-current' : 'msc-room-allotment-transferred'}">${room.to_date == null? "Current":"Transfered"}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info me-1" onclick="viewRoomDetails('${room}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                            tableBody.append(row);
                        });
                    }
                    function fetchAvailableRooms() {
                        const roomType = $('#msc-room-allotment-room-type').val();
                        if (!roomType) return;
                        
                        $.ajax({
                            type: "POST",
                            url: "{{$module_actions['room.fetch_available'] ?? '/api/rooms/available'}}",
                            data: {
                                "_token": "{{csrf_token()}}",
                                "room_category": roomType,
                                "ipd_id": "{{Crypt::encrypt($details->id)}}"
                            },
                            success: function(response) {
                                const roomSelect = $('#msc-room-allotment-room-number');
                                roomSelect.empty().append('<option value="">Select room</option>');
                                
                                response.rooms.forEach(room => {
                                    roomSelect.append(`<option value="${room.encrypted_id}">${room.room_number} - ${room.available_beds} beds available</option>`);
                                });
                            }
                        });
                    }

                    function fetchAvailableBeds() {
                        const roomId = $('#msc-room-allotment-room-number').val();
                        if (!roomId) return;
                        
                        $.ajax({
                            type: "POST",
                            url: "{{$module_actions['bed.fetch_available'] ?? '/api/beds/available'}}",
                            data: {
                                "_token": "{{csrf_token()}}",
                                "room_id": roomId
                            },
                            success: function(response) {
                                const bedSelect = $('#msc-room-allotment-bed-number');
                                bedSelect.empty().append('<option value="">Select bed</option>');
                                
                                response.beds.forEach(bed => {
                                    bedSelect.append(`<option value="${bed.encrypted_id}">${bed.bed_number}</option>`);
                                });
                            }
                        });
                    }

                    function viewRoomDetails(roomId) {
                        // Implement room details view
                        window.open('{{url("/")}}' + '/room-details/' + roomId, '_blank');
                    }

                    function transferRoom(roomId) {
                        // Reset form for transfer
                        $('#msc-room-allotment-form')[0].reset();
                        $('#msc-room-allotment-transfer-reason').val('medical_requirement');
                        $('html, body').animate({
                            scrollTop: $("#msc-room-allotment-form").offset().top - 100
                        }, 500);
                    }
                    function fetchRoomAllotmentData() {
                        $.ajax({
                            type: "POST",
                            url: "{{$module_actions['room-allotment.fetch']}}",
                            data: {
                                "_token": "{{csrf_token()}}",
                                "ipd_id": "{{Crypt::encrypt($details->id)}}"
                            },
                            beforeSend: function() {
                                $('.msc-room-allotment-data-table tbody').html(`
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="fas fa-spinner fa-spin me-2"></i>
                                            <span>Loading room allotment data...</span>
                                        </td>
                                    </tr>
                                `);
                            },
                            success: function(response) {
                                if (response.data) {
                                    updateRoomAllotmentTable(response.data);
                                } else {
                                    mscToast({msg:'Failed to fetch room allotment data', color:'danger', icon:"exclamation-circle"});
                                }
                            },
                            error: function(xhr, status, error) {
                                const res = JSON.parse(xhr.responseJSON.message);
                                mscToast({
                                    msg:res.msg,
                                    color:res.color,
                                    icon:res.icon,
                                });
                            }
                        });
                    }
                    $(document).ready(function () {
                        fetchRoomAllotmentData()
                    });
                </script>
            </x-module-check>

            <x-module-check route="hospit.ipd.manage.balance">
                <script>
                    /**
                     * Function to refresh balance cards and payment history table with updated data
                     * @param {Object} newData - Optional new data object, if not provided fetches from server
                     * @param {boolean} showLoading - Whether to show loading state during refresh
                     */
                    function refreshBalanceCards(newData = null, showLoading = true, showRes = true) {
                        
                        // Show loading state if enabled
                        if (showLoading) {
                            $('.msc-ipd-balance-cardbox').addClass('loading-state');
                            $('.msc-ipd-balance-value').html('<i class="fas fa-spinner fa-spin"></i>');
                            
                            // Show loading state for table
                            showTableLoading();
                        }

                        // If data is provided, update cards and table directly
                        if (newData) {
                            updateBalanceCardsUI(newData.summary || newData);
                            if (newData.payment_history) {
                                updatePaymentHistoryTable(newData.payment_history);
                            }
                            $('.msc-ipd-balance-cardbox').removeClass('loading-state');
                            return;
                        }

                        // Fetch fresh data from server
                        $.ajax({
                            type: "POST",
                            url: "{{$module_actions['balance.fetch']}}",
                            data: {
                                "_token": "{{csrf_token()}}",
                                "ipd_id": "{{Crypt::encrypt($details->id)}}"
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Update balance cards
                                    updateBalanceCardsUI(response.summary);
                                    calculateBalanceWithDiscount()
                                    
                                    // Update payment history table
                                    if (response.records) {
                                        updatePaymentHistoryTable(response.records);
                                    }
                                    
                                    // Optional: Show success notification
                                    if(showRes){
                                        mscToast({
                                            msg:JSON.parse(response.response).msg,
                                            color:JSON.parse(response.response).color,
                                            icon:JSON.parse(response.response).icon,
                                        });
                                    }
                                } else {
                                    console.error('Failed to fetch balance data:', response.message);
                                    mscToast({msg:'Failed to update balance', color:'danger', icon:"exclamation-circle"});
                                }
                            },
                            error: function(xhr, status, error) {
                                const res = JSON.parse(err.responseJSON.message);
                                mscToast({
                                    msg:res.msg,
                                    color:res.color,
                                    icon:res.icon,
                                });
                                $('.msc-ipd-balance-cardbox').removeClass('loading-state');
                                hideTableLoading();
                            },
                            complete: function() {
                                // Remove loading state
                                $('.msc-ipd-balance-cardbox').removeClass('loading-state');
                                hideTableLoading();
                            }
                        });
                    }

                    /**
                     * Update payment history table with new data
                     * @param {Array} paymentHistory - Array of payment records
                     */
                    function updatePaymentHistoryTable(paymentHistory) {
                        const $tableBody = $('.msc-ipd-balance-data-table tbody');
                        
                        if (!$tableBody.length) {
                            console.warn('Payment history table not found');
                            return;
                        }

                        // Clear existing rows
                        $tableBody.empty();

                        if (!paymentHistory || paymentHistory.length === 0) {
                            $tableBody.append(`
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No payment records found.</td>
                                </tr>
                            `);
                            return;
                        }

                        // Add new rows
                        paymentHistory.forEach(payment => {
                            const formattedDate = new Date(payment.payment_date).toLocaleDateString('en-GB');
                            const formattedAmount = '₹ ' + parseFloat(payment.amount).toLocaleString('en-IN', {
                                minimumFractionDigits: 2
                            });

                            const statusClass = getPaymentStatusClass(payment.status);
                            const encryptedId = payment.id; // Adjust based on your data structure

                            const row = `
                                <tr class="payment-row-animation">
                                    <td>${formattedDate}</td>
                                    <td class="fw-bold text-success">${formattedAmount}</td>
                                    <td>
                                        <span class="badge bg-secondary text-uppercase">${payment.payment_method ? payment.payment_method.charAt(0).toUpperCase() + payment.payment_method.slice(1) : '-'}</span>
                                    </td>
                                    <td>${payment.reference || '-'}</td>
                                    <td>${payment.received_by_name || '-'}</td>
                                    <td>
                                        <span class="msc-ipd-balance-status ${statusClass}">
                                            ${payment.status ? payment.status.charAt(0).toUpperCase() + payment.status.slice(1) : 'Unknown'}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info me-1" onclick="viewBalancePayment('${encryptedId}')" title="View Payment">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="printBalanceReceipt('${encryptedId}')" title="Print Receipt">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                            $tableBody.append(row);
                        });

                        // Animate new rows
                        setTimeout(() => {
                            $('.payment-row-animation').addClass('fade-in-animation');
                        }, 100);
                    }

                    /**
                     * Get CSS class for payment status
                     * @param {string} status - Payment status
                     * @returns {string} CSS class
                     */
                    function getPaymentStatusClass(status) {
                        switch(status?.toLowerCase()) {
                            case 'completed':
                                return 'msc-ipd-balance-completed';
                            case 'pending':
                                return 'msc-ipd-balance-pending';
                            case 'failed':
                            case 'cancelled':
                                return 'msc-ipd-balance-failed';
                            default:
                                return 'msc-ipd-balance-pending';
                        }
                    }

                    /**
                     * Show loading state for payment history table
                     */
                    function showTableLoading() {
                        const $tableBody = $('.msc-ipd-balance-data-table tbody');
                        if ($tableBody.length) {
                            $tableBody.html(`
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-spinner fa-spin me-2"></i>
                                        <span>Loading payment history...</span>
                                    </td>
                                </tr>
                            `);
                        }
                    }

                    /**
                     * Hide loading state for payment history table
                     */
                    function hideTableLoading() {
                        // Loading will be replaced by actual data or empty message
                        // This function can be used for cleanup if needed
                    }

                    /**
                     * Add new payment to table without full refresh
                     * @param {Object} newPayment - New payment record
                     */
                    function addPaymentToTable(newPayment) {
                        const $tableBody = $('.msc-ipd-balance-data-table tbody');
                        
                        // Remove "no records" row if it exists
                        $tableBody.find('tr:contains("No payment records found")').remove();
                        
                        const formattedDate = new Date(newPayment.payment_date).toLocaleDateString('en-GB');
                        const formattedAmount = '₹ ' + parseFloat(newPayment.amount).toLocaleString('en-IN', {
                            minimumFractionDigits: 2
                        });

                        const statusClass = getPaymentStatusClass(newPayment.status);
                        const encryptedId = newPayment.encrypted_id;

                        const newRow = `
                            <tr class="new-payment-row">
                                <td>${formattedDate}</td>
                                <td class="fw-bold text-success">${formattedAmount}</td>
                                <td>
                                    <span class="badge bg-secondary">${newPayment.payment_method ? newPayment.payment_method.charAt(0).toUpperCase() + newPayment.payment_method.slice(1) : '-'}</span>
                                </td>
                                <td>${newPayment.reference || '-'}</td>
                                <td>${newPayment.received_by_name || '-'}</td>
                                <td>
                                    <span class="msc-ipd-balance-status ${statusClass}">
                                        ${newPayment.status ? newPayment.status.charAt(0).toUpperCase() + newPayment.status.slice(1) : 'Completed'}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info me-1" onclick="viewBalancePayment('${encryptedId}')" title="View Payment">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-primary" onclick="printBalanceReceipt('${encryptedId}')" title="Print Receipt">
                                        <i class="fas fa-print"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                        
                        // Add to top of table
                        $tableBody.prepend(newRow);
                        
                        // Highlight new row
                        $('.new-payment-row').addClass('highlight-new-payment');
                        setTimeout(() => {
                            $('.new-payment-row').removeClass('new-payment-row highlight-new-payment');
                        }, 3000);
                    }


                    /**
                     * Update the balance cards UI with new data
                     * @param {Object} data - Financial summary data
                     */
                    function updateBalanceCardsUI(data) {
                        // Format currency values
                        const formatCurrency = (amount) => {
                            return '₹ ' + parseFloat(amount || 0).toLocaleString('en-IN', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        };

                        // Update each balance card with animation
                        const updates = [
                            {
                                id: '#msc-ipd-balance-total-bill-amount',
                                value: formatCurrency(data.total_bill),
                                color: '#283747'
                            },
                            {
                                id: '#msc-ipd-balance-total-paid',
                                value: formatCurrency(data.total_paid),
                                color: '#09b66d'
                            },
                            {
                                id: '#msc-ipd-balance-outstanding-balance',
                                value: formatCurrency(data.outstanding),
                                color: '#da3148'
                            },
                            {
                                id: '#msc-ipd-balance-insurance-coverage',
                                value: formatCurrency(data.total_insurance),
                                color: '#3578e5'
                            },
                            {
                                id: '#msc-ipd-balance-advance-payment',
                                value: formatCurrency(data.advance_payment),
                                color: '#d7a305'
                            }
                        ];

                        // Animate each card update
                        updates.forEach((update, index) => {
                            setTimeout(() => {
                                const $element = $(update.id);
                                if ($element.length) {
                                    // Fade out, update, fade in with slight bounce effect
                                    $element.fadeOut(150, function() {
                                        $(this).html(`<i class="fas fa-rupee-sign"></i> ${update.value.replace('₹ ', '')}`);
                                        $(this).css('color', update.color);
                                    }).fadeIn(200).addClass('updated-animation');
                                    
                                    // Remove animation class after animation completes
                                    setTimeout(() => {
                                        $element.removeClass('updated-animation');
                                    }, 500);
                                }
                            }, index * 100); // Stagger the animations
                        });

                        // Update current outstanding in payment form if it exists
                        if ($('#msc-ipd-balance-current-outstanding').length) {
                            $('#msc-ipd-balance-current-outstanding').text(formatCurrency(data.outstanding_balance));
                        }

                        // Update any payment summary calculations if form is active
                        if ($('#msc-ipd-balance-payment-amount').val()) {
                            calculateBalanceChange();
                        }
                    }

                    /**
                     * Show notification message for balance updates
                     * @param {string} message - Notification message
                     * @param {string} type - Notification type (success, error, info)
                     */
                    function showBalanceNotification(message, type = 'info') {
                        // Create notification element if it doesn't exist
                        if (!$('#balance-notification').length) {
                            $('body').append(`
                                <div id="balance-notification" style="
                                    position: fixed;
                                    top: 20px;
                                    right: 20px;
                                    z-index: 9999;
                                    max-width: 300px;
                                    padding: 12px 20px;
                                    border-radius: 6px;
                                    font-size: 14px;
                                    font-weight: 500;
                                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                                    display: none;
                                "></div>
                            `);
                        }

                        const $notification = $('#balance-notification');
                        
                        // Set colors based on type
                        const colors = {
                            success: { bg: '#d6f7e5', text: '#09b66d', border: '#09b66d' },
                            error: { bg: '#ffe1e0', text: '#da3148', border: '#da3148' },
                            info: { bg: '#e3f2fd', text: '#1976d2', border: '#1976d2' }
                        };

                        const color = colors[type] || colors.info;
                        
                        $notification.css({
                            'background-color': color.bg,
                            'color': color.text,
                            'border-left': `4px solid ${color.border}`
                        }).html(message).fadeIn(300);

                        // Auto hide after 3 seconds
                        setTimeout(() => {
                            $notification.fadeOut(300);
                        }, 3000);
                    }

                    /**
                     * Auto-refresh balance cards at specified intervals
                     * @param {number} intervalMinutes - Refresh interval in minutes
                     */
                    function startBalanceAutoRefresh(intervalMinutes = 5) {
                        // Clear any existing interval
                        if (window.balanceRefreshInterval) {
                            clearInterval(window.balanceRefreshInterval);
                        }

                        // Set new interval
                        window.balanceRefreshInterval = setInterval(() => {
                            refreshBalanceCards(null, false); // Refresh without loading animation for auto-refresh
                        }, intervalMinutes * 60 * 1000);

                        console.log(`Balance auto-refresh started (every ${intervalMinutes} minutes)`);
                    }

                    /**
                     * Stop auto-refresh of balance cards
                     */
                    function stopBalanceAutoRefresh() {
                        if (window.balanceRefreshInterval) {
                            clearInterval(window.balanceRefreshInterval);
                            window.balanceRefreshInterval = null;
                            console.log('Balance auto-refresh stopped');
                        }
                    }

                    /**
                     * Refresh balance after payment submission
                     * Call this function after successful payment recording
                     */
                    function refreshBalanceAfterPayment() {
                        $('#msc-ipd-balance-payment-confirm-modal').modal('hide');
                        $("#msc-ipd-balance-payment-form").trigger("reset")
                        refreshBalanceCards();
                    }
                    function calculateBalanceChange() {
                        const paymentAmount = parseFloat($('#msc-ipd-balance-payment-amount').val()) || 0;
                        
                        // Fix: Replace ALL commas using global regex
                        const outstandingBalance = parseFloat(
                            $('#msc-ipd-balance-outstanding-balance')
                                .text()
                                .replace('₹ ', '')
                                .replace(/,/g, '') // Use regex with 'g' flag to replace ALL commas
                        ) || 0;
                        
                        if (paymentAmount > 0) {
                            const remaining = Math.max(0, outstandingBalance - paymentAmount);
                            
                            $('#msc-ipd-balance-payment-summary').show();
                            $('#msc-ipd-balance-summary-amount').text('₹ ' + paymentAmount.toLocaleString('en-IN', {minimumFractionDigits: 2}));
                            $('#msc-ipd-balance-summary-remaining').text('₹ ' + remaining.toLocaleString('en-IN', {minimumFractionDigits: 2}));
                            $('#msc-ipd-balance-summary-total-reduction').text('₹ ' + paymentAmount.toLocaleString('en-IN', {minimumFractionDigits: 2}));
                            
                            // Validation
                            if (paymentAmount > outstandingBalance) {
                                $('#msc-ipd-balance-amount-validation').html('<span class="text-warning">⚠️ Amount exceeds outstanding balance</span>');
                            } else {
                                $('#msc-ipd-balance-amount-validation').html('<span class="text-success">✓ Valid amount</span>');
                            }
                        } else {
                            $('#msc-ipd-balance-payment-summary').hide();
                            $('#msc-ipd-balance-amount-validation').text('');
                        }
                    }

                    function calculateBalanceWithDiscount() {
                        const paymentAmount = parseFloat($('#msc-ipd-balance-payment-amount').val()) || 0;
                        const discountAmount = parseFloat($('#msc-ipd-balance-discount-amount').val()) || 0;
                        
                        // Parse outstanding balance (fix for comma-separated values)
                        const outstandingBalance = parseCurrencyValue($('#msc-ipd-balance-outstanding-balance').text());
                        
                        if (paymentAmount > 0 || discountAmount > 0) {
                            const totalReduction = paymentAmount + discountAmount;
                            const remaining = Math.max(0, outstandingBalance - totalReduction);
                            
                            $('#msc-ipd-balance-payment-summary').show();
                            $('#msc-ipd-balance-summary-amount').text('₹ ' + paymentAmount.toLocaleString('en-IN', {minimumFractionDigits: 2}));
                            $('#msc-ipd-balance-summary-discount').text('₹ ' + discountAmount.toLocaleString('en-IN', {minimumFractionDigits: 2}));
                            $('#msc-ipd-balance-summary-remaining').text('₹ ' + remaining.toLocaleString('en-IN', {minimumFractionDigits: 2}));
                            $('#msc-ipd-balance-summary-total-reduction').text('₹ ' + totalReduction.toLocaleString('en-IN', {minimumFractionDigits: 2}));
                            
                            // Validation
                            if (totalReduction > outstandingBalance) {
                                $('#msc-ipd-balance-amount-validation').html('<span class="text-warning">⚠️ Total amount exceeds outstanding balance</span>');
                            } else if (discountAmount > outstandingBalance * 0.5) { // 50% discount limit
                                $('#msc-ipd-balance-discount-validation').html('<span class="text-warning">⚠️ Discount exceeds 50% of outstanding amount</span>');
                            } else {
                                $('#msc-ipd-balance-amount-validation').html('<span class="text-success">✓ Valid amounts</span>');
                                $('#msc-ipd-balance-discount-validation').html('');
                            }
                            
                            // Show discount reason field if discount is given
                            if (discountAmount > 0) {
                                $('#msc-ipd-balance-discount-reason').prop('required', true);
                                $('#msc-ipd-balance-discount-reason').closest('.msc-ipd-balance-form-group').find('label').html('Discount Reason <span class="text-danger">*</span>');
                            } else {
                                $('#msc-ipd-balance-discount-reason').prop('required', false);
                                $('#msc-ipd-balance-discount-reason').closest('.msc-ipd-balance-form-group').find('label').html('Discount Reason');
                            }
                        } else {
                            $('#msc-ipd-balance-payment-summary').hide();
                            $('#msc-ipd-balance-amount-validation').text('');
                            $('#msc-ipd-balance-discount-validation').text('');
                        }
                    }

                    // Helper function to parse currency (from your previous conversation)
                    function parseCurrencyValue(currencyString) {
                        return parseFloat(
                            currencyString
                                .replace(/₹\s?/g, '')  // Remove rupee symbol and optional space
                                .replace(/,/g, '')     // Remove all commas
                                .trim()
                        ) || 0;
                    }

                    function toggleBalanceReferenceField() {
                        const method = $('#msc-ipd-balance-payment-method').val();
                        const referenceField = $('#msc-ipd-balance-payment-reference');
                        
                        if (method === 'cash' || method === 'advance') {
                            referenceField.prop('required', false);
                            referenceField.closest('.msc-ipd-balance-form-group').find('label').html('Reference/Transaction ID');
                        } else {
                            referenceField.prop('required', true);
                            referenceField.closest('.msc-ipd-balance-form-group').find('label').html('Reference/Transaction ID');
                        }
                    }

                    function viewBalancePayment(paymentId) {
                        window.open('{{url("/")}}' + '/payment-receipt/' + paymentId, '_blank');
                    }

                    function printBalanceReceipt(paymentId) {
                        window.open('{{url("/")}}' + '/ipd/balance/print' + '?tid='+paymentId+'', '_blank');
                    }

                    function updateBalanceSummary(newData) {
                        $('#msc-ipd-balance-total-paid').text('₹ ' + newData.total_paid.toLocaleString('en-IN', {minimumFractionDigits: 2}));
                        $('#msc-ipd-balance-outstanding-balance').text('₹ ' + newData.outstanding_balance.toLocaleString('en-IN', {minimumFractionDigits: 2}));
                        $('#msc-ipd-balance-current-outstanding').text('₹ ' + newData.outstanding_balance.toLocaleString('en-IN', {minimumFractionDigits: 2}));
                    }

                    $(document).ready(function () {
                        // Full refresh (cards + table)
                        refreshBalanceCards(null,true, false);

                        // Add single payment to table (without full refresh)
                        
                        // showBalanceNotification({'Payment recorded successfully!', 'error'});
                        $('#msc-ipd-balance-refresh-btn').on('click', function() {
                            
                            const $btn = $(this);
                            $btn.prop('disabled', true);
                            $btn.find('i').addClass('fa-spin');
                            
                            // Call your existing refresh function
                            refreshBalanceCards();

                            // Re-enable button after refresh completes (simulate with timeout here)
                            // Ideally update this with a callback/promise from refreshBalanceCards if you adapt it
                            setTimeout(() => {
                                $btn.prop('disabled', false);
                                $btn.find('i').removeClass('fa-spin');
                            }, 1500); // Adjust timeout as per your ajax timing
                        });
                        $('#msc-ipd-balance-payment-form').on('submit', function(e) {
                            e.preventDefault();

                            if(validateForm($(this).find("input[required][type!=file], select[required], textarea[required]"))){
                                const data = {
                                    paymentAmount: $('#msc-ipd-balance-payment-amount').val(),
                                    // discountAmount: $('#msc-ipd-balance-discount-amount').val() || 0,
                                    // discountReason: $('#msc-ipd-balance-discount-reason').val() || '',
                                    // discountNotes: $('#msc-ipd-balance-discount-notes').val() || '',
                                    paymentMethod: $('#msc-ipd-balance-payment-method').val() || '',
                                    paymentDate: $('#msc-ipd-balance-payment-date').val(),
                                    paymentReference: $('#msc-ipd-balance-payment-reference').val() || '',
                                    paymentRemarks: $('#msc-ipd-balance-payment-remarks').val() || '',
                                    outstandingBalance: parseCurrencyValue($('#msc-ipd-balance-outstanding-balance').text()),
                                };

                                // Populate visible confirmation fields (text)
                                $('#confirm-payment-amount').text('₹ ' + parseFloat(data.paymentAmount).toLocaleString('en-IN', {minimumFractionDigits: 2}));
                                // $('#confirm-discount-amount').text('₹ ' + parseFloat(data.discountAmount).toLocaleString('en-IN', {minimumFractionDigits: 2}));
                                // $('#confirm-discount-reason').text($('#msc-ipd-balance-discount-reason option:selected').text() || '');
                                // $('#confirm-discount-notes').text(data.discountNotes || '-');
                                $('#confirm-payment-method').text($('#msc-ipd-balance-payment-method option:selected').text() || '');
                                $('#confirm-payment-date').text(data.paymentDate);
                                $('#confirm-payment-reference').text(data.paymentReference || '-');
                                $('#confirm-payment-remarks').text(data.paymentRemarks || '-');
                                
                                const totalReduction = parseFloat(data.paymentAmount);
                                const newOutstanding = Math.max(0, data.outstandingBalance - totalReduction);
                                $('#confirm-new-outstanding').text('₹ ' + newOutstanding.toLocaleString('en-IN', {minimumFractionDigits: 2}));

                                // Populate hidden inputs inside the modal form
                                $('#confirm-input-payment_amount').val(data.paymentAmount);
                                // $('#confirm-input-discount_amount').val(data.discountAmount);
                                // $('#confirm-input-discount_reason').val(data.discountReason);
                                // $('#confirm-input-discount_notes').val(data.discountNotes);
                                $('#confirm-input-payment_method').val(data.paymentMethod);
                                $('#confirm-input-payment_date').val(data.paymentDate);
                                $('#confirm-input-payment_reference').val(data.paymentReference);
                                $('#confirm-input-payment_remarks').val(data.paymentRemarks);

                                // Show confirmation modal (Bootstrap 5)
                                $('#msc-ipd-balance-payment-confirm-modal').modal('show');
                            }
                        });

                        $('#payment-confirm-btn').on("click", function(){
                            $("#msc-ipd-balance-payment-form").submit();
                        })
                    });
                </script>
                <div class="msc-tab-panel" id="msc.hospit.ipd.manage.balance">
                    <div class="msc-ipd-balance-refresh-wrapper" style="text-align: right; margin-bottom: 1rem;">
                        <button type="button" 
                                id="msc-ipd-balance-refresh-btn" 
                                class="msc-ipd-balance-btn-primary" 
                                title="Refresh Balance" 
                                aria-label="Refresh Balance">
                            <i class="fas fa-sync-alt fa-spin-off me-2"></i> Refresh Balance
                        </button>
                    </div>
                    <!-- Account Summary Section -->
                    
                    <div class="msc-ipd-balance-summary">
                        <div class="msc-ipd-balance-summary-cards">
                            <div class="msc-ipd-balance-cardbox">
                                <span class="msc-ipd-balance-label"><i class="fas fa-file-invoice-dollar me-1"></i> Total Bill</span>
                                <span class="msc-ipd-balance-value" id="msc-ipd-balance-total-bill-amount">₹ 2000</span>
                            </div>
                            <div class="msc-ipd-balance-cardbox">
                                <span class="msc-ipd-balance-label"><i class="fas fa-hand-holding-usd me-1"></i> Paid</span>
                                <span class="msc-ipd-balance-value msc-ipd-balance-paid" id="msc-ipd-balance-total-paid">₹ 2000</span>
                            </div>
                            <div class="msc-ipd-balance-cardbox">
                                <span class="msc-ipd-balance-label"><i class="fas fa-exclamation-triangle me-1"></i> Outstanding</span>
                                <span class="msc-ipd-balance-value msc-ipd-balance-outstanding" id="msc-ipd-balance-outstanding-balance">₹ 2000</span>
                            </div>
                            <div class="msc-ipd-balance-cardbox">
                                <span class="msc-ipd-balance-label"><i class="fas fa-shield-alt me-1"></i> Insurance</span>
                                <span class="msc-ipd-balance-value msc-ipd-balance-insurance" id="msc-ipd-balance-insurance-coverage">₹ 2000</span>
                            </div>
                            <div class="msc-ipd-balance-cardbox">
                                <span class="msc-ipd-balance-label"><i class="fas fa-piggy-bank me-1"></i> Advance</span>
                                <span class="msc-ipd-balance-value msc-ipd-balance-advance" id="msc-ipd-balance-advance-payment">₹ 2000</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Form -->
                   <form class="msc-ipd-balance-payment-form" novalidate method="POST" action="#" id="msc-ipd-balance-payment-form">
                        @csrf
                        <input type="hidden" name="ipd_id" value="{{Crypt::encrypt($details->id)}}">
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="msc-ipd-balance-section-title">
                                <i class="fas fa-credit-card text-success me-2"></i>Record Payment
                            </h3>
                            <div class="text-muted small">
                                Outstanding: <span class="msc-ipd-balance-current-outstanding" id="msc-ipd-balance-current-outstanding">₹ 0</span>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="msc-ipd-balance-form-group">
                                    <label for="msc-ipd-balance-payment-amount" class="msc-ipd-balance-form-label">Payment Amount </label>
                                    <input type="number" id="msc-ipd-balance-payment-amount" name="payment_amount" step="0.01" class="msc-ipd-balance-form-input" placeholder="0.00" required oninput="calculateBalanceChange()">
                                    <div class="small text-muted mt-1">
                                        <span id="msc-ipd-balance-amount-validation"></span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- NEW: Discount Field -->
                            <div class="col-md-4">
                                <div class="msc-ipd-balance-form-group">
                                    <label for="msc-ipd-balance-payment-method" class="msc-ipd-balance-form-label">Payment Method </label>
                                    <select id="msc-ipd-balance-payment-method" name="payment_method" class="msc-ipd-balance-form-select" required onchange="toggleBalanceReferenceField()">
                                        <option value="">Select method</option>
                                        <option value="cash">Cash</option>
                                        <option value="credit-card">Credit Card</option>
                                        <option value="debit-card">Debit Card</option>
                                        <option value="upi">UPI</option>
                                        <option value="bank-transfer">Bank Transfer</option>
                                        <option value="insurance">Insurance Claim</option>
                                        <option value="cheque">Cheque</option>
                                        <option value="online-payment">Online Payment</option>
                                        <option value="advance">Advance Adjustment</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="msc-ipd-balance-form-group">
                                    <label for="msc-ipd-balance-payment-date" class="msc-ipd-balance-form-label">Payment Date </label>
                                    <input type="date" id="msc-ipd-balance-payment-date" name="payment_date" class="msc-ipd-balance-form-input" required value="{{date('Y-m-d')}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="msc-ipd-balance-form-group">
                                    <label for="msc-ipd-balance-payment-reference" class="msc-ipd-balance-form-label">Reference/Transaction ID</label>
                                    <input type="text" id="msc-ipd-balance-payment-reference" name="payment_reference" class="msc-ipd-balance-form-input" placeholder="Transaction reference">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="msc-ipd-balance-form-group">
                                    <label for="msc-ipd-balance-payment-remarks" class="msc-ipd-balance-form-label">Remarks</label>
                                    <textarea id="msc-ipd-balance-payment-remarks" name="payment_remarks" class="msc-ipd-balance-form-textarea" placeholder="Additional notes..." rows="1"></textarea>
                                </div>
                            </div>
                            
                        </div>
                        
                        <!-- Enhanced Payment Summary -->
                        <div class="msc-ipd-balance-payment-summary" id="msc-ipd-balance-payment-summary" style="display: none;">
                            <h6 class="mb-2">Payment Summary:</h6>
                            <div class="row">
                                <div class="col-4">
                                    <small>Payment Amount: <span id="msc-ipd-balance-summary-amount" class="fw-bold"></span></small>
                                </div>
                                <div class="col-4">
                                    <small>Remaining Balance: <span id="msc-ipd-balance-summary-remaining" class="fw-bold"></span></small>
                                </div>
                                <div class="col-4">
                                    <small>Total Reduction: <span id="msc-ipd-balance-summary-total-reduction" class="fw-bold text-success"></span></small>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="msc-ipd-balance-btn-primary msc-payment-preview-modal-trigger mt-3">
                            <i class="fas fa-save me-2"></i>Record Payment
                        </button>
                    </form>


                    <!-- Payment History -->
                    <div class="msc-ipd-balance-payment-history">
                        <h3 class="msc-ipd-balance-section-title">
                            <i class="fas fa-history text-info me-2"></i>Payment History
                        </h3>
                        <table class="msc-ipd-balance-data-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Reference</th>
                                    <th>Received By</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($paymentHistory ?? [] as $payment)
                                    
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No payment records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Payment Confirmation Modal -->
                <!-- Payment Confirmation Modal -->
                <div class="modal fade" id="msc-ipd-balance-payment-confirm-modal" tabindex="-1" aria-labelledby="confirmPaymentModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                        <form id="msc-ipd-balance-payment-confirm-form" method="POST" action="{{$module_actions['balance.save'] ?? '#'}}" class="msc-ord-form" data-callback="refreshBalanceAfterPayment()">
                            @csrf
                            <input type="hidden" name="ipd_id" value="{{ Crypt::encrypt($details->id) }}">

                            <!-- Mirror all payment form fields as hidden here -->
                            <input type="hidden" name="payment_amount" id="confirm-input-payment_amount">
                            <input type="hidden" name="payment_method" id="confirm-input-payment_method">
                            <input type="hidden" name="payment_date" id="confirm-input-payment_date">
                            <input type="hidden" name="payment_reference" id="confirm-input-payment_reference">
                            <input type="hidden" name="payment_remarks" id="confirm-input-payment_remarks">

                            <!-- Visible modal content (summary table) -->
                            <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="confirmPaymentModalLabel">
                                <i class="fas fa-info-circle me-2"></i>Confirm Payment Details
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                            <table class="table table-sm align-middle">
                                <tbody>
                                <tr>
                                    <th>Payment Amount:</th>
                                    <td id="confirm-payment-amount"></td>
                                </tr>
                                <tr>
                                    <th>Payment Method:</th>
                                    <td id="confirm-payment-method"></td>
                                </tr>
                                <tr>
                                    <th>Date:</th>
                                    <td id="confirm-payment-date"></td>
                                </tr>
                                <tr>
                                    <th>Reference:</th>
                                    <td id="confirm-payment-reference"></td>
                                </tr>
                                <tr>
                                    <th>Remarks:</th>
                                    <td id="confirm-payment-remarks"></td>
                                </tr>
                                <tr>
                                    <th>New Outstanding:</th>
                                    <td id="confirm-new-outstanding"></td>
                                </tr>
                                </tbody>
                            </table>

                            <div class="alert alert-warning mt-3 mb-0">
                                Please confirm the details above before proceeding. This payment cannot be edited once saved.
                            </div>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Review Again</button>
                            <button type="submit" id="payment-confirm-btn" class="btn btn-primary">Yes, Record Payment</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>


            </x-module-check>

            <x-module-check route="hospit.ipd.manage.lab_investigation">
                <div class="msc-tab-panel" id="msc.hospit.ipd.manage.lab_investigation">
                    
                    <!-- Lab Investigation Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-12 d-flex justify-content-end mb-3">
                            <button class="btn btn-primary" onclick="refreshLabStatus()">
                                <i class="fas fa-sync-alt"></i> Refresh Status
                            </button>
                        </div>
                    </div>

                    <div class="row mb-4" id="labSummaryCards">
                        <div class="col-md-2 col-sm-6 mb-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                        <i class="fas fa-flask text-muted me-2"></i>
                                        <span class="text-muted small">Total Tests</span>
                                    </div>
                                    <h4 class="mb-0 fw-bold" id="totalTestsAmount">₹ 12,450.00</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6 mb-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span class="text-muted small">Completed</span>
                                    </div>
                                    <h4 class="mb-0 fw-bold text-success" id="completedAmount">₹ 8,200.00</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6 mb-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                        <i class="fas fa-clock text-warning me-2"></i>
                                        <span class="text-muted small">Pending</span>
                                    </div>
                                    <h4 class="mb-0 fw-bold text-warning" id="pendingAmount">₹ 4,250.00</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6 mb-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                        <i class="fas fa-shield-alt text-info me-2"></i>
                                        <span class="text-muted small">Insurance</span>
                                    </div>
                                    <h4 class="mb-0 fw-bold text-info" id="insuranceAmount">₹ 0.00</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6 mb-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                        <i class="fas fa-hand-holding-usd text-warning me-2"></i>
                                        <span class="text-muted small">Advance</span>
                                    </div>
                                    <h4 class="mb-0 fw-bold text-warning" id="advanceAmount">₹ 0.00</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6 mb-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                        <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                                        <span class="text-muted small">Outstanding</span>
                                    </div>
                                    <h4 class="mb-0 fw-bold text-danger" id="outstandingAmount">₹ 4,250.00</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order New Test -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-plus-circle text-success me-2"></i>
                                <h5 class="mb-0">Order New Test</h5>
                                <div class="ms-auto">
                                    <small class="text-muted">Outstanding: <span class="text-danger" id="headerOutstanding">₹ 4,250.00</span></small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form class="row g-3 msc-ord-form" method="POST" action="{{$module_actions['lab_investigation.save']}}">
                                @csrf
                                <input type="hidden" name="ipd_id" value="{{Crypt::encrypt($details->id)}}">
                                <div class="col-md-6">
                                    <label class="form-label">Test Name</label>
                                    <select class="form-select msc-searchable" name="test_name" id="test_name" required>
                                        @foreach ($tests as $item)
                                            <option value="{{Crypt::encrypt($item->id)}}" data-price="{{$item->rate}}" data-category="{{$item->lab_department?->name}}">{{$item->name}} - ₹{{$item->rate}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Select Laboratory</label>
                                    <select class="form-control form-select" name="lab_room_id" id="lab_room_id" onfocus="fetchRelatedList({
                                        url:'{{$module_actions['lab_investigation.labs_rooms.fetch']}}',
                                        target:'#lab_room_id',
                                        data:{'test_id': $('#test_name').val()},
                                    })" required>
                                        
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Priority</label>
                                    <select class="form-control form-select" name="priority" required>
                                        <option value="">Select priority</option>
                                        <option value="routine">Routine</option>
                                        <option value="urgent">Urgent</option>
                                        <option value="stat">STAT</option>
                                        <option value="asap">ASAP</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Quantity</label>
                                    <input type="number" class="form-control" name="qty" required placeholder="Quantity" min="1" value="1">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Scheduled Date</label>
                                    <input type="date" class="form-control" name="scheduled_date" required value="{{date("Y-m-d")}}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Scheduled Time</label>
                                    <input type="time" class="form-control" name="scheduled_time" required value="{{date("H:i")}}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Special Instructions</label>
                                    <textarea class="form-control" name="instructions" rows="1" placeholder="Fasting required, special preparation, etc."></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Order Test
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Test History -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-history text-primary me-2"></i>
                                <h5 class="mb-0">Test History</h5>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0 text-muted">Date</th>
                                            <th class="border-0 text-muted">Test Name</th>
                                            <th class="border-0 text-muted">Category</th>
                                            <th class="border-0 text-muted">Priority</th>
                                            <th class="border-0 text-muted">Amount</th>
                                            <th class="border-0 text-muted">Status</th>
                                            <th class="border-0 text-muted">Result</th>
                                            <th class="border-0 text-muted">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="testHistoryTable">
                                        <tr data-test-id="1">
                                            <td class="align-middle">
                                                <div>
                                                    <div class="fw-medium">16/05/2025</div>
                                                    <small class="text-muted">10:30 AM</small>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <div>
                                                    <div class="fw-medium">Complete Blood Count</div>
                                                    <small class="text-muted">CBC-001</small>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <span class="badge bg-primary-subtle text-primary">Blood</span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="badge bg-secondary-subtle text-secondary">Routine</span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="fw-medium">₹ 850.00</span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="badge bg-success-subtle text-success">
                                                    <i class="fas fa-check-circle me-1"></i>Completed
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="text-success fw-medium">Normal</span>
                                            </td>
                                            <td class="align-middle">
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary btn-sm" onclick="viewTestDetails(1)" title="View Report">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-outline-secondary btn-sm" onclick="downloadReport(1)" title="Download">
                                                        <i class="fas fa-download"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr data-test-id="2">
                                            <td class="align-middle">
                                                <div>
                                                    <div class="fw-medium">17/05/2025</div>
                                                    <small class="text-muted">08:15 AM</small>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <div>
                                                    <div class="fw-medium">Blood Sugar (Fasting)</div>
                                                    <small class="text-muted">BSF-002</small>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <span class="badge bg-primary-subtle text-primary">Blood</span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="badge bg-warning-subtle text-warning">Urgent</span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="fw-medium">₹ 450.00</span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="badge bg-success-subtle text-success">
                                                    <i class="fas fa-check-circle me-1"></i>Completed
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <div>
                                                    <span class="fw-medium text-success">95 mg/dL</span>
                                                    <small class="text-muted d-block">Normal: 70-100</small>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary btn-sm" onclick="viewTestDetails(2)" title="View Report">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-outline-secondary btn-sm" onclick="downloadReport(2)" title="Download">
                                                        <i class="fas fa-download"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr data-test-id="3">
                                            <td class="align-middle">
                                                <div>
                                                    <div class="fw-medium">18/05/2025</div>
                                                    <small class="text-muted">02:00 PM</small>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <div>
                                                    <div class="fw-medium">Chest X-Ray</div>
                                                    <small class="text-muted">CXR-003</small>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <span class="badge bg-info-subtle text-info">Imaging</span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="badge bg-danger-subtle text-danger">STAT</span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="fw-medium">₹ 1,200.00</span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="badge bg-warning-subtle text-warning">
                                                    <i class="fas fa-clock me-1"></i>In Progress
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="text-muted">Pending</span>
                                            </td>
                                            <td class="align-middle">
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-info btn-sm" onclick="trackStatus(3)" title="Track Status">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                    <button class="btn btn-outline-danger btn-sm" onclick="cancelTest(3)" title="Cancel">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Test Details Modal -->
                <div class="modal fade" id="testDetailsModal" tabindex="-1" aria-labelledby="testDetailsModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="testDetailsModalLabel">
                                    <i class="fas fa-flask me-2"></i>Test Details
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="testDetailsContent">
                                <!-- Content will be loaded dynamically -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" onclick="downloadCurrentReport()">
                                    <i class="fas fa-download me-1"></i>Download Report
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    // Lab Investigation Management Script
                    let labData = {
                        totalTests: 12450.00,
                        completed: 8200.00,
                        pending: 4250.00,
                        insurance: 0.00,
                        advance: 0.00,
                        outstanding: 4250.00
                    };

                    let testCounter = 4; // For generating new test IDs
                    let currentTestId = null; // For modal operations

                    // Initialize page
                    document.addEventListener('DOMContentLoaded', function() {
                        initializePage();
                        setupEventListeners();
                        fetchLabInvistigationRecords()
                    });

                    function initializePage() {
                        // Set default date to today
                        const today = new Date().toISOString().split('T')[0];
                        document.querySelector('input[name="scheduled_date"]').value = today;
                        
                        // Auto-fill category based on test selection
                        document.querySelector('select[name="test_name"]').addEventListener('change', function() {
                            const selectedOption = this.options[this.selectedIndex];
                            const category = selectedOption.getAttribute('data-category');
                            if (category) {
                                document.querySelector('select[name="category"]').value = category;
                            }
                        });
                    }

                    function setupEventListeners() {
                        // Form submission
                       
                    }

                    function refreshLabStatus() {
                        const button = event.target.closest('button');
                        const icon = button.querySelector('i');
                        
                        // Add loading state
                        icon.classList.remove('fa-sync-alt');
                        icon.classList.add('fa-spinner', 'fa-spin');
                        button.disabled = true;
                        fetchLabInvistigationRecords()
                        // Simulate API call
                        setTimeout(() => {
                            // Update cards with new data (simulate fetched data)
                            updateLabSummaryCards();
                            
                            // Remove loading state
                            icon.classList.remove('fa-spinner', 'fa-spin');
                            icon.classList.add('fa-sync-alt');
                            button.disabled = false;
                            
                            // Show success message
                            showToast('Lab status refreshed successfully', 'success');
                        }, 1500);
                    }
                    function fetchLabInvistigationRecords() {
                        $.ajax({
                            url: '{{$module_actions['lab_investigation.fetch']}}',
                            type: 'POST',
                            data: {
                                ipd_id: '{{Crypt::encrypt($details->id)}}',
                                _token: '{{csrf_token()}}'
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    labData = response.data;
                                    updateLabSummaryCards();
                                    populateTestHistoryTable(response.data);
                                } else {
                                    showToast(response.message, 'error');
                                }
                            },
                            error: function(xhr) {
                                showToast('Failed to fetch lab records', 'error');
                            }
                        });
                    }
                    function updateLabSummaryCards() {
                        document.getElementById('totalTestsAmount').textContent = `₹ ${labData.totalTests.toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
                        document.getElementById('completedAmount').textContent = `₹ ${labData.completed.toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
                        document.getElementById('pendingAmount').textContent = `₹ ${labData.pending.toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
                        document.getElementById('insuranceAmount').textContent = `₹ ${labData.insurance.toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
                        document.getElementById('advanceAmount').textContent = `₹ ${labData.advance.toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
                        document.getElementById('outstandingAmount').textContent = `₹ ${labData.outstanding.toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
                        document.getElementById('headerOutstanding').textContent = `₹ ${labData.outstanding.toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
                    }

                    function addNewTest() {
                        const form = document.getElementById('newTestForm');
                        const formData = new FormData(form);
                        
                        // Get test details
                        const testSelect = form.querySelector('select[name="test_name"]');
                        const selectedOption = testSelect.options[testSelect.selectedIndex];
                        const testPrice = parseFloat(selectedOption.getAttribute('data-price'));
                        const testName = selectedOption.text.split(' - ')[0];
                        
                        // Create new test object
                        const newTest = {
                            id: testCounter++,
                            name: testName,
                            code: `TEST-${String(testCounter).padStart(3, '0')}`,
                            category: formData.get('category'),
                            priority: formData.get('priority'),
                            amount: testPrice,
                            status: 'pending',
                            result: 'Pending',
                            date: new Date().toLocaleDateString('en-GB'),
                            time: new Date().toLocaleTimeString('en-US', {hour: '2-digit', minute: '2-digit'}),
                            instructions: formData.get('instructions'),
                            scheduledDate: formData.get('scheduled_date')
                        };
                        
                        // Add to table
                        addTestToTable(newTest);
                        
                        // Update lab data
                        labData.totalTests += testPrice;
                        labData.pending += testPrice;
                        labData.outstanding += testPrice;
                        
                        // Update cards
                        updateLabSummaryCards();
                        
                        // Reset form
                        form.reset();
                        document.querySelector('input[name="scheduled_date"]').value = new Date().toISOString().split('T')[0];
                        
                        // Show success message
                        showToast('Test ordered successfully', 'success');
                    }
                    
                    function addTestToTable(data) {
                        const test = data.test_rows;
                        const tbody = document.getElementById('testHistoryTable');
                        const row = document.createElement('tr');
                        row.setAttribute('data-test-id', test.encrypted_id);
                        
                        const categoryBadgeClass = getCategoryBadgeClass(test.category);
                        const priorityBadgeClass = getPriorityBadgeClass(test.priority);
                        const statusBadgeClass = getStatusBadgeClass(test.status);
                        
                        row.innerHTML = `
                            <td class="align-middle">
                                <div>
                                    <div class="fw-medium">${test.date}</div>
                                    <small class="text-muted">${test.time}</small>
                                </div>
                            </td>
                            <td class="align-middle">
                                <div>
                                    <div class="fw-medium">${test.name}</div>
                                    <small class="text-muted">${test.code}</small>
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="fw-medium">${test.lab_room.name}</div>
                            </td>
                            <td class="align-middle">
                                <span class="badge ${priorityBadgeClass}">${test.priority.toUpperCase()}</span>
                            </td>
                            <td class="align-middle">
                                <span class="fw-medium">₹ ${test.amount.toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                            </td>
                            <td class="align-middle">
                                <span class="badge ${statusBadgeClass}">
                                    <i class="fas ${test.status === 'completed' ? 'fa-check-circle' : 'fa-clock'} me-1"></i>${test.status === 'pending' ? 'In Progress' : 'Completed'}
                                </span>
                            </td>
                            <td class="align-middle">
                                <span class="text-muted">${test.result}</span>
                            </td>
                            <td class="align-middle">
                                <div class="btn-group btn-group-sm">
                                    ${test.status === 'completed' ? 
                                        `<button class="btn btn-outline-primary btn-sm" onclick="viewTestDetails(${test.id})" title="View Report">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm" onclick="downloadReport(${test.id})" title="Download">
                                            <i class="fas fa-download"></i>
                                        </button>` :
                                        `<button class="btn btn-outline-info btn-sm" onclick="trackStatus(${test.id})" title="Track Status">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" onclick="cancelTest(${test.id})" title="Cancel">
                                            <i class="fas fa-times"></i>
                                        </button>`
                                    }
                                </div>
                            </td>
                        `;
                        
                        // Insert at the beginning of table
                        tbody.insertBefore(row, tbody.firstChild);
                    }

                    function getCategoryBadgeClass(category) {
                        const classes = {
                            'blood': 'bg-primary-subtle text-primary',
                            'urine': 'bg-warning-subtle text-warning',
                            'imaging': 'bg-info-subtle text-info',
                            'cardiology': 'bg-danger-subtle text-danger',
                            'pathology': 'bg-success-subtle text-success',
                            'radiology': 'bg-secondary-subtle text-secondary'
                        };
                        return classes[category] || 'bg-light text-dark';
                    }

                    function getPriorityBadgeClass(priority) {
                        const classes = {
                            'routine': 'bg-secondary-subtle text-secondary',
                            'urgent': 'bg-warning-subtle text-warning',
                            'stat': 'bg-danger-subtle text-danger',
                            'asap': 'bg-danger-subtle text-danger'
                        };
                        return classes[priority] || 'bg-secondary-subtle text-secondary';
                    }

                    function getStatusBadgeClass(status) {
                        const classes = {
                            'completed': 'bg-success-subtle text-success',
                            'pending': 'bg-warning-subtle text-warning',
                            'cancelled': 'bg-danger-subtle text-danger'
                        };
                        return classes[status] || 'bg-secondary-subtle text-secondary';
                    }

                    function viewTestDetails(testId) {
                        currentTestId = testId;
                        
                        // Sample test data - in real implementation, fetch from API
                        const testDetails = {
                            1: {
                                name: 'Complete Blood Count',
                                code: 'CBC-001',
                                date: '16/05/2025',
                                time: '10:30 AM',
                                category: 'Blood Test',
                                priority: 'Routine',
                                status: 'Completed',
                                amount: '₹ 850.00',
                                doctor: 'Dr. Smith Johnson',
                                lab: 'Central Laboratory',
                                results: [
                                    {parameter: 'Hemoglobin', value: '14.2 g/dL', range: '12.0-15.5 g/dL', status: 'normal'},
                                    {parameter: 'RBC Count', value: '4.8 million/µL', range: '4.2-5.4 million/µL', status: 'normal'},
                                    {parameter: 'WBC Count', value: '7200/µL', range: '4000-11000/µL', status: 'normal'},
                                    {parameter: 'Platelet Count', value: '285000/µL', range: '150000-450000/µL', status: 'normal'}
                                ],
                                notes: 'All parameters within normal limits. Patient appears healthy.'
                            },
                            2: {
                                name: 'Blood Sugar (Fasting)',
                                code: 'BSF-002',
                                date: '17/05/2025',
                                time: '08:15 AM',
                                category: 'Blood Test',
                                priority: 'Urgent',
                                status: 'Completed',
                                amount: '₹ 450.00',
                                doctor: 'Dr. Emily Davis',
                                lab: 'Central Laboratory',
                                results: [
                                    {parameter: 'Glucose (Fasting)', value: '95 mg/dL', range: '70-100 mg/dL', status: 'normal'}
                                ],
                                notes: 'Fasting glucose level is within normal range. Continue current diet plan.'
                            }
                        };
                        
                        const test = testDetails[testId];
                        if (!test) {
                            showToast('Test details not found', 'error');
                            return;
                        }
                        
                        const modalContent = document.getElementById('testDetailsContent');
                        modalContent.innerHTML = `
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">Test Information</h6>
                                    <table class="table table-sm table-borderless">
                                        <tr><td class="text-muted">Test Name:</td><td class="fw-medium">${test.name}</td></tr>
                                        <tr><td class="text-muted">Test Code:</td><td>${test.code}</td></tr>
                                        <tr><td class="text-muted">Date & Time:</td><td>${test.date} at ${test.time}</td></tr>
                                        <tr><td class="text-muted">Category:</td><td>${test.category}</td></tr>
                                        <tr><td class="text-muted">Priority:</td><td><span class="badge ${getPriorityBadgeClass(test.priority.toLowerCase())}">${test.priority}</span></td></tr>
                                        <tr><td class="text-muted">Status:</td><td><span class="badge ${getStatusBadgeClass(test.status.toLowerCase())}">${test.status}</span></td></tr>
                                        <tr><td class="text-muted">Amount:</td><td class="fw-medium">${test.amount}</td></tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">Laboratory Information</h6>
                                    <table class="table table-sm table-borderless">
                                        <tr><td class="text-muted">Ordered by:</td><td>${test.doctor}</td></tr>
                                        <tr><td class="text-muted">Laboratory:</td><td>${test.lab}</td></tr>
                                        <tr><td class="text-muted">Report Date:</td><td>${test.date}</td></tr>
                                    </table>
                                </div>
                            </div>
                            
                            ${test.results ? `
                            <div class="mt-4">
                                <h6 class="text-muted mb-3">Test Results</h6>
                                <div class="table-responsive">
                                    <table class="table table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Parameter</th>
                                                <th>Value</th>
                                                <th>Normal Range</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${test.results.map(result => `
                                                <tr>
                                                    <td class="fw-medium">${result.parameter}</td>
                                                    <td>${result.value}</td>
                                                    <td class="text-muted">${result.range}</td>
                                                    <td>
                                                        <span class="badge ${result.status === 'normal' ? 'bg-success-subtle text-success' : result.status === 'high' ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning'}">
                                                            ${result.status.toUpperCase()}
                                                        </span>
                                                    </td>
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            ` : ''}
                            
                            ${test.notes ? `
                            <div class="mt-4">
                                <h6 class="text-muted mb-3">Clinical Notes</h6>
                                <div class="alert alert-light">
                                    <p class="mb-0">${test.notes}</p>
                                </div>
                            </div>
                            ` : ''}
                        `;
                        
                        // Show modal
                        const modal = new bootstrap.Modal(document.getElementById('testDetailsModal'));
                        modal.show();
                    }

                    function downloadReport(testId) {
                        // Show loading state
                        const button = event.target.closest('button');
                        const icon = button.querySelector('i');
                        const originalClass = icon.className;
                        
                        icon.className = 'fas fa-spinner fa-spin';
                        button.disabled = true;
                        
                        // Simulate download
                        setTimeout(() => {
                            // Reset button state
                            icon.className = originalClass;
                            button.disabled = false;
                            
                            // Show success message
                            showToast('Report downloaded successfully', 'success');
                            
                            // In real implementation, trigger actual download
                            // window.open(`/api/lab-reports/${testId}/download`, '_blank');
                        }, 2000);
                    }

                    function downloadCurrentReport() {
                        if (currentTestId) {
                            downloadReport(currentTestId);
                        }
                    }

                    function trackStatus(testId) {
                        showToast('Tracking test status...', 'info');
                        
                        // Simulate status update
                        setTimeout(() => {
                            const row = document.querySelector(`tr[data-test-id="${testId}"]`);
                            if (row) {
                                const statusCell = row.cells[5];
                                statusCell.innerHTML = `
                                    <span class="badge bg-info-subtle text-info">
                                        <i class="fas fa-flask me-1"></i>Processing
                                    </span>
                                `;
                            }
                            showToast('Test is currently being processed in the laboratory', 'info');
                        }, 1000);
                    }

                    function cancelTest(testId) {
                        if (confirm('Are you sure you want to cancel this test?')) {
                            const row = document.querySelector(`tr[data-test-id="${testId}"]`);
                            if (row) {
                                // Get test amount for updating totals
                                const amountText = row.cells[4].textContent;
                                const amount = parseFloat(amountText.replace('₹', '').replace(',', ''));
                                
                                // Update lab data
                                labData.totalTests -= amount;
                                labData.pending -= amount;
                                labData.outstanding -= amount;
                                
                                // Update cards
                                updateLabSummaryCards();
                                
                                // Remove row with animation
                                row.style.transition = 'opacity 0.3s ease';
                                row.style.opacity = '0';
                                setTimeout(() => {
                                    row.remove();
                                }, 300);
                                
                                showToast('Test cancelled successfully', 'success');
                            }
                        }
                    }

                    function showToast(message, type = 'info') {
                        // Create toast element
                        const toast = document.createElement('div');
                        toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
                        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                        toast.innerHTML = `
                            ${message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                        
                        document.body.appendChild(toast);
                        
                        // Auto remove after 3 seconds
                        setTimeout(() => {
                            if (toast.parentNode) {
                                toast.remove();
                            }
                        }, 3000);
                    }
                    </script>

                            </div>
                        </div>
                        <script>
                            // Tab switching functionality
                            document.querySelectorAll('.msc-tab-btn').forEach(button => {
                                button.addEventListener('click', () => {
                                    // Remove active class from all tabs and panels
                                    document.querySelectorAll('.msc-tab-btn').forEach(btn => btn.classList.remove('msc-ipd-active'));
                                    document.querySelectorAll('.msc-tab-panel').forEach(panel => panel.classList.remove('msc-ipd-active'));
                                    
                                    // Add active class to clicked tab
                                    button.classList.add('msc-ipd-active');
                                    
                                    // Show corresponding panel
                                    const targetPanel = document.getElementById(button.dataset.mscTab);
                                    targetPanel.classList.add('msc-ipd-active');
                                });
                            });
                    $(document).ready(function () {
                        $(".msc-tab-btn:first").addClass("msc-ipd-active");
                        $(".msc-tab-panel:first").addClass("msc-ipd-active");
                    });

                </script>
            </x-module-check>

    
  <script>
    mscGetBarcode("{{$details['ipd_number']}}", "#IPDnumberBarCode");
  </script>
<x-footer />