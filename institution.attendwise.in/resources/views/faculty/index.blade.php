<x-structure />
<x-header heading="Faculty" />

<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body table-card-body">

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Faculty Members</h5>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#importFacultyModal">
                <i class="fas fa-file-excel me-1"></i> Import Faculty
            </button>
            <x-btn-add route="institution.faculty.add.view" />
        </div>
      </div>

      <!-- Import Faculty Modal -->
      <div class="modal fade" id="importFacultyModal" tabindex="-1">
        <div class="modal-dialog modal-md modal-dialog-scrollable">
          <div class="modal-content border-0 rounded-4 shadow-lg">

            <div class="modal-header border-0 px-4 py-3" style="background: var(--primary);">
              <div>
                <h5 class="modal-title text-white fw-semibold mb-0">Import Faculty</h5>
              </div>
              <div class="ms-auto me-3">
                <a href="{{ route('institution.faculty.manage.download_sample') }}" class="btn btn-light btn-sm rounded-pill px-3">
                    <i class="fas fa-download me-1"></i> Sample Excel
                </a>
              </div>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('institution.faculty.manage.import') }}" method="POST" enctype="multipart/form-data" class="msc-ord-form" data-form-type="ADD">
              @csrf
              <div class="modal-body px-4 py-4">
                <div class="row g-4 mb-4 align-items-center">
                  <div class="col-md-12">
                      <input type="file" id="excel_file" name="excel_file" class="msc-file-upload" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                  </div>
                </div>
                <div class="mt-4 p-3 rounded-3 bg-light border">
                  <small class="text-muted">
                    <strong>Excel Columns:</strong><br>
                    name (Required), email (Required), mobile, gender, designation, department, employee_code, date_of_birth, blood_group, nationality, national_id, pan_number, permanent_address, current_address, emergency_contact_name, emergency_contact_number, joining_date, leaving_date, employment_type, working_days, highest_qualification, years_of_experience, bank_account_no, bank_name, bank_ifsc, basic_salary, password
                  </small>
                </div>
              </div>

              <div class="modal-footer border-0 px-4 py-3 bg-light rounded-bottom-4">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4">Start Import</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover table-bordered msc-smart-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Employee Code</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Designation</th>
              <th>Status</th>
              <th width="120">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($faculty as $member)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $member->name }}</td>
                <td>{{ $member->employee_code ?? '-' }}</td>
                <td>{{ $member->email }}</td>
                <td>{{ $member->mobile ?? '-' }}</td>
                <td class="text-capitalize">{{ str_replace("_", " ", $member->designation) ?? '-' }}</td>
                <td>
                  <span class="badge bg-{{ $member->status == 1 ? 'success' : 'danger' }}">
                    {{ $member->status == 1 ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td>
                  <div class="d-flex gap-2">
                    <a href="{{ route('institution.faculty.manage.schedule', Crypt::encrypt($member->id)) }}" 
                       class="btn btn-sm btn-info text-white shadow-sm" 
                       title="View Timetable">
                      <i class="fa fa-calendar-alt"></i>
                    </a>
                    <x-btn-edit
                      route="institution.faculty.edit.view"
                      id="{{ Crypt::encrypt($member->id) }}"
                    />
                    <x-btn-delete
                      route="institution.faculty.delete"
                      id="{{ Crypt::encrypt($member->id) }}"
                    />
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center text-muted">
                  No faculty found
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>


    </div>
  </div>
</div>

<x-footer />
