<x-structure />
<x-header heading="{{$title}}"/>

<div class="aw-page-header mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-heading mb-1">{{ $title }}</h1>
            <p class="text-muted small mb-0">Configure institutional metadata, accreditation parameters, contacts, integrations, and billing settings.</p>
        </div>
        <div>
            <a href="{{ route('institution.dashboard') }}" class="btn btn-light border">
                <i class="fa fa-arrow-left me-1.5 opacity-75"></i> Dashboard
            </a>
        </div>
    </div>
</div>

<div class="col-lg-12 grid-margin stretch-card">
  <div class="card aw-form-card shadow-sm border-0">
    <div class="card-body">
      <form action="{{$action}}" method="POST" id="mainForm" data-form-type="{{$type}}" data-enhance="true" novalidate>
        @csrf
        @if(isset($type) && ($type === 'edit' || $type === 'EDIT'))
            @method('PUT')
        @endif

        {{-- Nav tabs --}}
        <ul class="nav nav-pills border-bottom pb-3 mb-4 gap-2 flex-wrap" id="institutionTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active px-3 py-2 fw-500" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">
                <i class="fas fa-university me-1.5 opacity-75"></i> General
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link px-3 py-2 fw-500" id="ids-tab" data-bs-toggle="tab" data-bs-target="#ids" type="button" role="tab" aria-controls="ids" aria-selected="false">
                <i class="fas fa-certificate me-1.5 opacity-75"></i> Accreditation & IDs
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link px-3 py-2 fw-500" id="contacts-tab" data-bs-toggle="tab" data-bs-target="#contacts" type="button" role="tab" aria-controls="contacts" aria-selected="false">
                <i class="fas fa-address-book me-1.5 opacity-75"></i> Key Contacts
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link px-3 py-2 fw-500" id="academics-tab" data-bs-toggle="tab" data-bs-target="#academics" type="button" role="tab" aria-controls="academics" aria-selected="false">
                <i class="fas fa-graduation-cap me-1.5 opacity-75"></i> Academics & Rosters
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link px-3 py-2 fw-500" id="integrations-tab" data-bs-toggle="tab" data-bs-target="#integrations" type="button" role="tab" aria-controls="integrations" aria-selected="false">
                <i class="fas fa-plug me-1.5 opacity-75"></i> ERP Integrations
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link px-3 py-2 fw-500" id="messaging-tab" data-bs-toggle="tab" data-bs-target="#messaging" type="button" role="tab" aria-controls="messaging" aria-selected="false">
                <i class="fas fa-paper-plane me-1.5 opacity-75"></i> Messaging & Policy
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link px-3 py-2 fw-500" id="billing-tab" data-bs-toggle="tab" data-bs-target="#billing" type="button" role="tab" aria-controls="billing" aria-selected="false">
                <i class="fas fa-credit-card me-1.5 opacity-75"></i> Subscription & Billing
            </button>
          </li>
        </ul>

        {{-- Tab panes --}}
        <div class="tab-content pt-2" id="institutionTabContent">
          {{-- GENERAL --}}
          <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
            <div class="row g-4">
              <div class="col-md-6">
                <div class="aw-field-group">
                  <label for="legal_name" class="form-label aw-field-label">Legal Name <span class="aw-field-required">*</span></label>
                  <input type="text" name="legal_name" id="legal_name" class="form-control" required
                    placeholder="Enter full registered legal name of the institution"
                    value="{{ old('legal_name', $institution['legal_name'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-3">
                <div class="aw-field-group">
                  <label for="institution_type" class="form-label aw-field-label">Institution Type <span class="aw-field-required">*</span></label>
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
                <div class="aw-field-group">
                  <label for="year_of_establishment" class="form-label aw-field-label">Year Established <span class="aw-field-required">*</span></label>
                  <input type="number" name="year_of_establishment" id="year_of_establishment" class="form-control" required min="1800" max="{{ date('Y') }}"
                    placeholder="e.g. 1998"
                    value="{{ old('year_of_establishment', $institution['year_of_establishment'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-12">
                <div class="aw-field-group">
                  <label for="registered_address" class="form-label aw-field-label">Registered Campus Address <span class="aw-field-required">*</span></label>
                  <input type="text" name="registered_address" id="registered_address" class="form-control" required
                    placeholder="Street address, city, district, state, pin code"
                    value="{{ old('registered_address', $institution['registered_address'] ?? '') }}">
                </div>
              </div>
            </div>
          </div>

          {{-- IDS & ACCREDITATION --}}
          <div class="tab-pane fade" id="ids" role="tabpanel" aria-labelledby="ids-tab">
            <div class="row g-4">
              <div class="col-md-3">
                <div class="aw-field-group">
                  <label for="pan" class="form-label aw-field-label">PAN Number</label>
                  <input type="text" name="pan" id="pan" class="form-control" maxlength="10"
                    placeholder="10-digit PAN"
                    value="{{ old('pan', $institution['pan'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-3">
                <div class="aw-field-group">
                  <label for="gstin" class="form-label aw-field-label">GSTIN</label>
                  <input type="text" name="gstin" id="gstin" class="form-control" maxlength="15"
                    placeholder="15-digit GSTIN"
                    value="{{ old('gstin', $institution['gstin'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-3">
                <div class="aw-field-group">
                  <label for="aishe_code" class="form-label aw-field-label">AISHE Code</label>
                  <input type="text" name="aishe_code" id="aishe_code" class="form-control"
                    placeholder="AISHE / UGC code"
                    value="{{ old('aishe_code', $institution['aishe_code'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-3">
                <div class="aw-field-group">
                  <label for="aicte_approval_number" class="form-label aw-field-label">AICTE Approval No.</label>
                  <input type="text" name="aicte_approval_number" id="aicte_approval_number" class="form-control"
                    placeholder="AICTE reference number"
                    value="{{ old('aicte_approval_number', $institution['aicte_approval_number'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-4">
                <div class="aw-field-group">
                  <label for="naac_accreditation_grade" class="form-label aw-field-label">NAAC Accreditation Grade</label>
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
            <div class="row g-4">
              <div class="col-md-4">
                <div class="aw-field-group">
                  <label for="authorized_signatory_name" class="form-label aw-field-label">Authorized Signatory Name <span class="aw-field-required">*</span></label>
                  <input type="text" name="authorized_signatory_name" id="authorized_signatory_name" class="form-control" required
                    placeholder="Full name"
                    value="{{ old('authorized_signatory_name', $institution['authorized_signatory_name'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-4">
                <div class="aw-field-group">
                  <label for="authorized_signatory_email" class="form-label aw-field-label">Authorized Signatory Email <span class="aw-field-required">*</span></label>
                  <input type="email" name="authorized_signatory_email" id="authorized_signatory_email" class="form-control" required
                    placeholder="signatory@institution.edu"
                    value="{{ old('authorized_signatory_email', $institution['authorized_signatory_email'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-4">
                <div class="aw-field-group">
                  <label for="registrar_dean_name" class="form-label aw-field-label">Registrar / Dean Name</label>
                  <input type="text" name="registrar_dean_name" id="registrar_dean_name" class="form-control"
                    placeholder="Full name"
                    value="{{ old('registrar_dean_name', $institution['registrar_dean_name'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-4">
                <div class="aw-field-group">
                  <label for="registrar_dean_email" class="form-label aw-field-label">Registrar / Dean Email</label>
                  <input type="email" name="registrar_dean_email" id="registrar_dean_email" class="form-control"
                    placeholder="registrar@institution.edu"
                    value="{{ old('registrar_dean_email', $institution['registrar_dean_email'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-4">
                <div class="aw-field-group">
                  <label for="iterp_head_name" class="form-label aw-field-label">IT / ERP Head Name</label>
                  <input type="text" name="iterp_head_name" id="iterp_head_name" class="form-control"
                    placeholder="Full name"
                    value="{{ old('iterp_head_name', $institution['iterp_head_name'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-4">
                <div class="aw-field-group">
                  <label for="iterp_head_email" class="form-label aw-field-label">IT / ERP Head Email</label>
                  <input type="email" name="iterp_head_email" id="iterp_head_email" class="form-control"
                    placeholder="it@institution.edu"
                    value="{{ old('iterp_head_email', $institution['iterp_head_email'] ?? '') }}">
                </div>
              </div>
            </div>
          </div>

          {{-- ACADEMICS & ROSTERS --}}
          <div class="tab-pane fade" id="academics" role="tabpanel" aria-labelledby="academics-tab">
            <div class="row g-4">
              <div class="col-md-6">
                <div class="aw-field-group">
                  <label for="academic_calendar_start_date" class="form-label aw-field-label">Academic Calendar Start</label>
                  <input type="date" name="academic_calendar_start_date" id="academic_calendar_start_date" class="form-control"
                    value="{{ old('academic_calendar_start_date', $institution['academic_calendar_start_date'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-6">
                <div class="aw-field-group">
                  <label for="academic_calendar_end_date" class="form-label aw-field-label">Academic Calendar End</label>
                  <input type="date" name="academic_calendar_end_date" id="academic_calendar_end_date" class="form-control"
                    value="{{ old('academic_calendar_end_date', $institution['academic_calendar_end_date'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-12">
                <div class="aw-field-group">
                  <label for="programs_and_departments" class="form-label aw-field-label">Programs & Departments Overview</label>
                  <textarea name="programs_and_departments" id="programs_and_departments" rows="4" class="form-control"
                    placeholder="E.g. B.Tech CS, B.Tech ECE, B.Sc Physics, M.Sc Data Science">{{ old('programs_and_departments', $institution['programs_and_departments'] ?? '') }}</textarea>
                </div>
              </div>

              <div class="col-md-6">
                <div class="aw-field-group">
                  <label for="student_roster" class="form-label aw-field-label">Student Roster (JSON / Structured Text)</label>
                  <textarea name="student_roster" id="student_roster" rows="4" class="form-control font-monospace text-sm"
                    placeholder='[{"roll":"001","name":"Student Name"}, ...]'>{{ old('student_roster', $institution['student_roster'] ?? '') }}</textarea>
                </div>
              </div>

              <div class="col-md-6">
                <div class="aw-field-group">
                  <label for="faculty_roster" class="form-label aw-field-label">Faculty Roster (JSON / Structured Text)</label>
                  <textarea name="faculty_roster" id="faculty_roster" rows="4" class="form-control font-monospace text-sm"
                    placeholder='[{"faculty_id":"F01","name":"Dr. X"}, ...]'>{{ old('faculty_roster', $institution['faculty_roster'] ?? '') }}</textarea>
                </div>
              </div>
            </div>
          </div>

          {{-- INTEGRATIONS --}}
          <div class="tab-pane fade" id="integrations" role="tabpanel" aria-labelledby="integrations-tab">
            <div class="row g-4">
              <div class="col-md-4">
                <div class="aw-field-group">
                  <label for="erp_integration_method" class="form-label aw-field-label">ERP Integration Protocol</label>
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
                <div class="aw-field-group">
                  <label for="erp_base_url" class="form-label aw-field-label">ERP Production Endpoint</label>
                  <input type="url" name="erp_base_url" id="erp_base_url" class="form-control"
                    placeholder="https://erp.institution.edu/api"
                    value="{{ old('erp_base_url', $institution['erp_base_url'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-4">
                <div class="aw-field-group">
                  <label for="erp_sandbox_url" class="form-label aw-field-label">ERP Sandbox Endpoint</label>
                  <input type="url" name="erp_sandbox_url" id="erp_sandbox_url" class="form-control"
                    placeholder="https://sandbox-erp.institution.edu/api"
                    value="{{ old('erp_sandbox_url', $institution['erp_sandbox_url'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-12">
                <div class="aw-field-group">
                  <label for="api_authentication_credentials" class="form-label aw-field-label">API Authentication Credentials (JSON)</label>
                  <textarea name="api_authentication_credentials" id="api_authentication_credentials" rows="3" class="form-control font-monospace text-sm"
                    placeholder='{"key":"xxxx","secret":"yyyy"}'>{{ old('api_authentication_credentials', $institution['api_authentication_credentials'] ?? '') }}</textarea>
                </div>
              </div>
            </div>
          </div>

          {{-- MESSAGING & POLICY --}}
          <div class="tab-pane fade" id="messaging" role="tabpanel" aria-labelledby="messaging-tab">
            <div class="row g-4">
              <div class="col-md-4">
                <div class="aw-field-group">
                  <label for="sms_dlt_entity_name" class="form-label aw-field-label">SMS DLT Entity Name</label>
                  <input type="text" name="sms_dlt_entity_name" id="sms_dlt_entity_name" class="form-control"
                    placeholder="Registered DLT entity name"
                    value="{{ old('sms_dlt_entity_name', $institution['sms_dlt_entity_name'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-4">
                <div class="aw-field-group">
                  <label for="approved_sms_sender_id" class="form-label aw-field-label">Approved SMS Header / Sender ID</label>
                  <input type="text" name="approved_sms_sender_id" id="approved_sms_sender_id" class="form-control"
                    placeholder="6-Alpha Sender ID"
                    value="{{ old('approved_sms_sender_id', $institution['approved_sms_sender_id'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-4">
                <div class="aw-field-group">
                  <label for="whatsapp_business_account_status" class="form-label aw-field-label">WhatsApp Business Account Status</label>
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
                <div class="aw-field-group">
                  <label for="email_domain_authentication" class="form-label aw-field-label">Email Domain Authentication</label>
                  <input type="text" name="email_domain_authentication" id="email_domain_authentication" class="form-control"
                    placeholder="SPF / DKIM / DMARC verification status"
                    value="{{ old('email_domain_authentication', $institution['email_domain_authentication'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-6">
                <div class="aw-field-group">
                  <label for="attendance_policy_document" class="form-label aw-field-label">Attendance Policy Overview</label>
                  <textarea name="attendance_policy_document" id="attendance_policy_document" rows="3" class="form-control"
                    placeholder="Paste institutional attendance policy summary or guidelines">{{ old('attendance_policy_document', $institution['attendance_policy_document'] ?? '') }}</textarea>
                </div>
              </div>

              <div class="col-md-12">
                <div class="aw-field-group">
                  <label for="data_privacy_officer_contact" class="form-label aw-field-label">Data Privacy Officer (DPO) Contact</label>
                  <input type="text" name="data_privacy_officer_contact" id="data_privacy_officer_contact" class="form-control"
                    placeholder="Name, email, and phone number of DPO"
                    value="{{ old('data_privacy_officer_contact', $institution['data_privacy_officer_contact'] ?? '') }}">
                </div>
              </div>
            </div>
          </div>

          {{-- BILLING & ADMIN --}}
          <div class="tab-pane fade" id="billing" role="tabpanel" aria-labelledby="billing-tab">
            <div class="row g-4">
              <div class="col-md-4">
                <div class="aw-field-group">
                  <label for="plan_type" class="form-label aw-field-label">Subscription Tier</label>
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
                <div class="aw-field-group">
                  <label for="billing_contact_name" class="form-label aw-field-label">Billing Officer Name</label>
                  <input type="text" name="billing_contact_name" id="billing_contact_name" class="form-control"
                    placeholder="Full name"
                    value="{{ old('billing_contact_name', $institution['billing_contact_name'] ?? '') }}">
                </div>
              </div>

              <div class="col-md-4">
                <div class="aw-field-group">
                  <label for="billing_contact_email" class="form-label aw-field-label">Billing Officer Email <span class="aw-field-required">*</span></label>
                  <input type="email" name="billing_contact_email" id="billing_contact_email" class="form-control" required
                    placeholder="billing@institution.edu"
                    value="{{ old('billing_contact_email', $institution['billing_contact_email'] ?? '') }}">
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Form buttons --}}
        <div class="mt-4 pt-3 border-top">
          <x-form-buttons />
        </div>

      </form>
    </div>
  </div>
</div>

<x-footer />

