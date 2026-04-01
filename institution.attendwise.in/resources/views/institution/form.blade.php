<x-structure />
<x-header heading="{{$title}}"/>
<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <form action="{{$action}}" method="POST" id="mainForm" data-form-type="{{$type}}" data-enhance="true" novalidate>
        @csrf

        {{-- Nav tabs --}}
        <ul class="nav nav-tabs mb-3" id="institutionTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">General</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="ids-tab" data-bs-toggle="tab" data-bs-target="#ids" type="button" role="tab" aria-controls="ids" aria-selected="false">IDs & Accreditation</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="contacts-tab" data-bs-toggle="tab" data-bs-target="#contacts" type="button" role="tab" aria-controls="contacts" aria-selected="false">Contacts</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="academics-tab" data-bs-toggle="tab" data-bs-target="#academics" type="button" role="tab" aria-controls="academics" aria-selected="false">Academics & Rosters</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="integrations-tab" data-bs-toggle="tab" data-bs-target="#integrations" type="button" role="tab" aria-controls="integrations" aria-selected="false">Integrations</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="messaging-tab" data-bs-toggle="tab" data-bs-target="#messaging" type="button" role="tab" aria-controls="messaging" aria-selected="false">Messaging & Policy</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="billing-tab" data-bs-toggle="tab" data-bs-target="#billing" type="button" role="tab" aria-controls="billing" aria-selected="false">Billing & Admin</button>
          </li>
        </ul>

        {{-- Tab panes --}}
        <div class="tab-content" id="institutionTabContent">
          {{-- GENERAL --}}
          <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="legal_name">Legal Name</label>
                  <input type="text" name="legal_name" id="legal_name" class="form-control" required
                    placeholder="Enter full legal name of the institution"
                    value="{{ old('legal_name', $institution['legal_name'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group mb-3">
                  <label for="institution_type">Institution Type</label>
                  <select name="institution_type" id="institution_type" class="form-control form-select" required>
                    <option value="">Select institution type</option>
                    <option value="School" {{ old('institution_type', $institution['institution_type'] ?? '') == 'School' ? 'selected':'' }}>School</option>
                    <option value="College" {{ old('institution_type', $institution['institution_type'] ?? '') == 'College' ? 'selected':'' }}>College</option>
                    <option value="University" {{ old('institution_type', $institution['institution_type'] ?? '') == 'University' ? 'selected':'' }}>University</option>
                    <option value="Training Center" {{ old('institution_type', $institution['institution_type'] ?? '') == 'Training Center' ? 'selected':'' }}>Training Center</option>
                    <option value="Other" {{ old('institution_type', $institution['institution_type'] ?? '') == 'Other' ? 'selected':'' }}>Other</option>
                  </select>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group mb-3">
                  <label for="year_of_establishment">Year of Establishment</label>
                  <input type="number" name="year_of_establishment" id="year_of_establishment" class="form-control" required min="1800" max="{{ date('Y') }}"
                    placeholder="e.g. 1998"
                    value="{{ old('year_of_establishment', $institution['year_of_establishment'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group mb-3">
                  <label for="registered_address">Registered Address</label>
                  <input type="text" name="registered_address" id="registered_address" class="form-control" required
                    placeholder="Street, locality, landmark, postal area"
                    value="{{ old('registered_address', $institution['registered_address'] ?? '') }}">
                </div>
              </div>
            </div>
          </div>

          {{-- IDS & ACCREDITATION --}}
          <div class="tab-pane fade" id="ids" role="tabpanel" aria-labelledby="ids-tab">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group mb-3">
                  <label for="pan">PAN</label>
                  <input type="text" name="pan" id="pan" class="form-control" maxlength="10"
                    placeholder="Permanent Account Number (10 chars)"
                    value="{{ old('pan', $institution['pan'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group mb-3">
                  <label for="gstin">GSTIN</label>
                  <input type="text" name="gstin" id="gstin" class="form-control" maxlength="15"
                    placeholder="GSTIN (15 chars)"
                    value="{{ old('gstin', $institution['gstin'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group mb-3">
                  <label for="aishe_code">AISHE Code</label>
                  <input type="text" name="aishe_code" id="aishe_code" class="form-control"
                    placeholder="AISHE / UGC code (if any)"
                    value="{{ old('aishe_code', $institution['aishe_code'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group mb-3">
                  <label for="aicte_approval_number">AICTE Approval No.</label>
                  <input type="text" name="aicte_approval_number" id="aicte_approval_number" class="form-control"
                    placeholder="AICTE approval number (if applicable)"
                    value="{{ old('aicte_approval_number', $institution['aicte_approval_number'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label for="naac_accreditation_grade">NAAC Grade</label>
                  <select name="naac_accreditation_grade" id="naac_accreditation_grade" class="form-control form-select">
                    <option value="">Select NAAC grade</option>
                    <option value="A++" {{ old('naac_accreditation_grade', $institution['naac_accreditation_grade'] ?? '') == 'A++' ? 'selected':'' }}>A++</option>
                    <option value="A+" {{ old('naac_accreditation_grade', $institution['naac_accreditation_grade'] ?? '') == 'A+' ? 'selected':'' }}>A+</option>
                    <option value="A" {{ old('naac_accreditation_grade', $institution['naac_accreditation_grade'] ?? '') == 'A' ? 'selected':'' }}>A</option>
                    <option value="B++" {{ old('naac_accreditation_grade', $institution['naac_accreditation_grade'] ?? '') == 'B++' ? 'selected':'' }}>B++</option>
                    <option value="B+" {{ old('naac_accreditation_grade', $institution['naac_accreditation_grade'] ?? '') == 'B+' ? 'selected':'' }}>B+</option>
                    <option value="B" {{ old('naac_accreditation_grade', $institution['naac_accreditation_grade'] ?? '') == 'B' ? 'selected':'' }}>B</option>
                    <option value="C" {{ old('naac_accreditation_grade', $institution['naac_accreditation_grade'] ?? '') == 'C' ? 'selected':'' }}>C</option>
                    <option value="Not Accredited" {{ old('naac_accreditation_grade', $institution['naac_accreditation_grade'] ?? '') == 'Not Accredited' ? 'selected':'' }}>Not Accredited</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          {{-- CONTACTS --}}
          <div class="tab-pane fade" id="contacts" role="tabpanel" aria-labelledby="contacts-tab">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label for="authorized_signatory_name">Authorized Signatory Name</label>
                  <input type="text" name="authorized_signatory_name" id="authorized_signatory_name" class="form-control" required
                    placeholder="Name of authorized signatory"
                    value="{{ old('authorized_signatory_name', $institution['authorized_signatory_name'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label for="authorized_signatory_email">Authorized Signatory Email</label>
                  <input type="email" name="authorized_signatory_email" id="authorized_signatory_email" class="form-control" required
                    placeholder="email@institution.example"
                    value="{{ old('authorized_signatory_email', $institution['authorized_signatory_email'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label for="registrar_dean_name">Registrar / Dean Name</label>
                  <input type="text" name="registrar_dean_name" id="registrar_dean_name" class="form-control"
                    placeholder="Registrar / Dean full name"
                    value="{{ old('registrar_dean_name', $institution['registrar_dean_name'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label for="registrar_dean_email">Registrar / Dean Email</label>
                  <input type="email" name="registrar_dean_email" id="registrar_dean_email" class="form-control"
                    placeholder="registrar@example.com"
                    value="{{ old('registrar_dean_email', $institution['registrar_dean_email'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label for="iterp_head_name">ITeRP Head Name</label>
                  <input type="text" name="iterp_head_name" id="iterp_head_name" class="form-control"
                    placeholder="IT/ERP head name"
                    value="{{ old('iterp_head_name', $institution['iterp_head_name'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label for="iterp_head_email">ITeRP Head Email</label>
                  <input type="email" name="iterp_head_email" id="iterp_head_email" class="form-control"
                    placeholder="it@example.com"
                    value="{{ old('iterp_head_email', $institution['iterp_head_email'] ?? '') }}">
                </div>
              </div>
            </div>
          </div>

          {{-- ACADEMICS & ROSTERS --}}
          <div class="tab-pane fade" id="academics" role="tabpanel" aria-labelledby="academics-tab">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="academic_calendar_start_date">Academic Calendar Start</label>
                  <input type="date" name="academic_calendar_start_date" id="academic_calendar_start_date" class="form-control"
                    value="{{ old('academic_calendar_start_date', $institution['academic_calendar_start_date'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="academic_calendar_end_date">Academic Calendar End</label>
                  <input type="date" name="academic_calendar_end_date" id="academic_calendar_end_date" class="form-control"
                    value="{{ old('academic_calendar_end_date', $institution['academic_calendar_end_date'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group mb-3">
                  <label for="programs_and_departments">Programs & Departments</label>
                  <textarea name="programs_and_departments" id="programs_and_departments" rows="4" class="form-control"
                    placeholder="E.g. B.Sc - Physics; B.Com; M.Sc - CS">{{ old('programs_and_departments', $institution['programs_and_departments'] ?? '') }}</textarea>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="student_roster">Student Roster (JSON/Text)</label>
                  <textarea name="student_roster" id="student_roster" rows="4" class="form-control"
                    placeholder='[{"roll":"001","name":"Student Name"}, ...]'>{{ old('student_roster', $institution['student_roster'] ?? '') }}</textarea>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="faculty_roster">Faculty Roster (JSON/Text)</label>
                  <textarea name="faculty_roster" id="faculty_roster" rows="4" class="form-control"
                    placeholder='[{"faculty_id":"F01","name":"Dr. X"}, ...]'>{{ old('faculty_roster', $institution['faculty_roster'] ?? '') }}</textarea>
                </div>
              </div>
            </div>
          </div>

          {{-- INTEGRATIONS --}}
          <div class="tab-pane fade" id="integrations" role="tabpanel" aria-labelledby="integrations-tab">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label for="erp_integration_method">ERP Integration Method</label>
                  <select name="erp_integration_method" id="erp_integration_method" class="form-control form-select">
                    <option value="">Select integration method</option>
                    <option value="None" {{ old('erp_integration_method', $institution['erp_integration_method'] ?? '') == 'None' ? 'selected':'' }}>None</option>
                    <option value="API" {{ old('erp_integration_method', $institution['erp_integration_method'] ?? '') == 'API' ? 'selected':'' }}>API</option>
                    <option value="SFTP" {{ old('erp_integration_method', $institution['erp_integration_method'] ?? '') == 'SFTP' ? 'selected':'' }}>SFTP</option>
                    <option value="Manual CSV" {{ old('erp_integration_method', $institution['erp_integration_method'] ?? '') == 'Manual CSV' ? 'selected':'' }}>Manual CSV</option>
                    <option value="Other" {{ old('erp_integration_method', $institution['erp_integration_method'] ?? '') == 'Other' ? 'selected':'' }}>Other</option>
                  </select>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label for="erp_base_url">ERP Base URL</label>
                  <input type="url" name="erp_base_url" id="erp_base_url" class="form-control"
                    placeholder="https://erp.example.com/api"
                    value="{{ old('erp_base_url', $institution['erp_base_url'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label for="erp_sandbox_url">ERP Sandbox URL</label>
                  <input type="url" name="erp_sandbox_url" id="erp_sandbox_url" class="form-control"
                    placeholder="https://sandbox.erp.example.com/api"
                    value="{{ old('erp_sandbox_url', $institution['erp_sandbox_url'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group mb-3">
                  <label for="api_authentication_credentials">API Authentication Credentials</label>
                  <textarea name="api_authentication_credentials" id="api_authentication_credentials" rows="3" class="form-control"
                    placeholder='{"key":"xxxx","secret":"yyyy"}'>{{ old('api_authentication_credentials', $institution['api_authentication_credentials'] ?? '') }}</textarea>
                </div>
              </div>
            </div>
          </div>

          {{-- MESSAGING & POLICY --}}
          <div class="tab-pane fade" id="messaging" role="tabpanel" aria-labelledby="messaging-tab">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label for="sms_dlt_entity_name">SMS DLT Entity Name</label>
                  <input type="text" name="sms_dlt_entity_name" id="sms_dlt_entity_name" class="form-control"
                    placeholder="Registered DLT entity name"
                    value="{{ old('sms_dlt_entity_name', $institution['sms_dlt_entity_name'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label for="approved_sms_sender_id">Approved SMS Sender ID</label>
                  <input type="text" name="approved_sms_sender_id" id="approved_sms_sender_id" class="form-control"
                    placeholder="6-Alpha sender id"
                    value="{{ old('approved_sms_sender_id', $institution['approved_sms_sender_id'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label for="whatsapp_business_account_status">WhatsApp Business Account Status</label>
                  <select name="whatsapp_business_account_status" id="whatsapp_business_account_status" class="form-control form-select">
                    <option value="">Select status</option>
                    <option value="Not Registered" {{ old('whatsapp_business_account_status', $institution['whatsapp_business_account_status'] ?? '') == 'Not Registered' ? 'selected':'' }}>Not Registered</option>
                    <option value="Pending" {{ old('whatsapp_business_account_status', $institution['whatsapp_business_account_status'] ?? '') == 'Pending' ? 'selected':'' }}>Pending</option>
                    <option value="Active" {{ old('whatsapp_business_account_status', $institution['whatsapp_business_account_status'] ?? '') == 'Active' ? 'selected':'' }}>Active</option>
                    <option value="Inactive" {{ old('whatsapp_business_account_status', $institution['whatsapp_business_account_status'] ?? '') == 'Inactive' ? 'selected':'' }}>Inactive</option>
                  </select>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="email_domain_authentication">Email Domain Authentication</label>
                  <input type="text" name="email_domain_authentication" id="email_domain_authentication" class="form-control"
                    placeholder="SPF/DKIM status or notes"
                    value="{{ old('email_domain_authentication', $institution['email_domain_authentication'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="attendance_policy_document">Attendance Policy Document (text)</label>
                  <textarea name="attendance_policy_document" id="attendance_policy_document" rows="3" class="form-control"
                    placeholder="Paste attendance policy or short summary">{{ old('attendance_policy_document', $institution['attendance_policy_document'] ?? '') }}</textarea>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group mb-3">
                  <label for="data_privacy_officer_contact">Data Privacy Officer Contact</label>
                  <input type="text" name="data_privacy_officer_contact" id="data_privacy_officer_contact" class="form-control"
                    placeholder="Name / email / phone for DPO"
                    value="{{ old('data_privacy_officer_contact', $institution['data_privacy_officer_contact'] ?? '') }}">
                </div>
              </div>
            </div>
          </div>

          {{-- BILLING & ADMIN --}}
          <div class="tab-pane fade" id="billing" role="tabpanel" aria-labelledby="billing-tab">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label for="plan_type">Plan Type</label>
                  <select name="plan_type" id="plan_type" class="form-control form-select">
                    <option value="">Select plan</option>
                    <option value="Free" {{ old('plan_type', $institution['plan_type'] ?? '') == 'Free' ? 'selected':'' }}>Free</option>
                    <option value="Starter" {{ old('plan_type', $institution['plan_type'] ?? '') == 'Starter' ? 'selected':'' }}>Starter</option>
                    <option value="Pro" {{ old('plan_type', $institution['plan_type'] ?? '') == 'Pro' ? 'selected':'' }}>Pro</option>
                    <option value="Enterprise" {{ old('plan_type', $institution['plan_type'] ?? '') == 'Enterprise' ? 'selected':'' }}>Enterprise</option>
                  </select>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label for="billing_contact_name">Billing Contact Name</label>
                  <input type="text" name="billing_contact_name" id="billing_contact_name" class="form-control"
                    placeholder="Name for billing contact"
                    value="{{ old('billing_contact_name', $institution['billing_contact_name'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label for="billing_contact_email">Billing Contact Email</label>
                  <input type="email" name="billing_contact_email" id="billing_contact_email" class="form-control" required
                    placeholder="billing@institution.example"
                    value="{{ old('billing_contact_email', $institution['billing_contact_email'] ?? '') }}">
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Form buttons (keeps your component) --}}
        <div class="mt-3">
          <x-form-buttons />
        </div>

      </form>
    </div>
  </div>
</div>

{{-- GlobalDialog modal + toast markup (required) --}}



{{Str::ulid()}}
<x-footer />
