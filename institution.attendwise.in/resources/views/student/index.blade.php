<x-structure />
<x-header heading="Students" />

<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body table-card-body">

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Students</h5>
        <x-module-check route="institution.student.add.view">
            <button
                type="button"
                class="btn btn-outline-primary"
                data-bs-toggle="modal"
                data-bs-target="#importStudentsModal"
                >
                <i class="fas fa-file-csv me-1"></i> Import Students
            </button>
            <!-- Import Students Modal -->
<div class="modal fade" id="importStudentsModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content border-0 rounded-4 shadow-lg">

      <!-- HEADER -->
      <div class="modal-header border-0 px-4 py-3"
           style="background:  var(--primary);">
        <div>
          <h5 class="modal-title text-white fw-semibold mb-0">
            Import Students
          </h5>
          <small class="text-white-50">
            Upload spreadsheet & configure academic structure
          </small>
        </div>
        <button type="button"
                class="btn-close btn-close-white"
                data-bs-dismiss="modal"></button>
      </div>

      <form action="student/import"
            method="POST"
            enctype="multipart/form-data" class="msc-ord-form">
        @csrf

        <!-- BODY -->
        <div class="modal-body px-4 py-4">

          <!-- UPLOAD + YEAR -->
          <div class="row g-4 mb-4 align-items-center">
            <div class="col-md-12">
                <input type="file" id="csv_file" name="csv_file" class="msc-file-upload" required>
            </div>

          </div>

          <!-- SPLIT CONFIG AREA -->
          <div class="row g-4">

            <!-- LEFT: SECTIONS -->
            <div class="col-md-6">
              <div class="config-card h-100">
                <h6 class="config-title">🏫 Section Settings</h6>

                <div class="config-row">
                  <div>
                    <strong>Assign Sections</strong>
                    <small>Attach students to sections</small>
                  </div>
                    <input type="checkbox" name="assign_sections" value="1" class="msc-form-check" checked>
                </div>

                <div class="config-row">
                  <div>
                    <strong>Auto Create Sections</strong>
                    <small>Create section if missing</small>
                  </div>
                    <input type="checkbox" name="auto_create_sections" value="1" checked class="msc-form-check">
                </div>

                <div class="mt-3">
                  <label class="form-label fw-medium">Avg Section Size</label>
                  <input type="number"
                         name="avg_section_size"
                         class="form-control"
                         min="1"
                         placeholder="30">
                </div>

                <div class="mt-3">
                  <label class="form-label fw-medium">Section Prefix</label>
                  <input type="text"
                         name="section_name_prefix"
                         class="form-control"
                         placeholder="CS">
                </div>
              </div>
            </div>

            <!-- RIGHT: CLASS GROUPS -->
            <div class="col-md-6">
              <div class="config-card h-100">
                <h6 class="config-title">👥 Class Group Settings</h6>

                <div class="config-row">
                  <div>
                    <strong>Auto Create Class Groups</strong>
                    <small>Create group if missing</small>
                  </div>
                    <input type="checkbox" name="auto_create_class_groups" checked value="1" class="msc-form-check">
                </div>

                <div class="mt-3">
                  <label class="form-label fw-medium">Class Group Prefix</label>
                  <input type="text"
                         name="class_group_prefix"
                         class="form-control"
                         placeholder="G">
                </div>
              </div>
            </div>

          </div>

          <!-- CSV INFO -->
          <div class="mt-4 p-3 rounded-3 bg-light border">
            <small class="text-muted">
              <strong>CSV Columns:</strong><br>
              roll_number, name, email, phone, gender,
              department, course, section, class_group
            </small>
          </div>

        </div>

        <!-- FOOTER -->
        <div class="modal-footer border-0 px-4 py-3 bg-light rounded-bottom-4">
          <button type="button"
                  class="btn btn-outline-secondary rounded-pill px-4"
                  data-bs-dismiss="modal">
            Cancel
          </button>

          <button type="submit"
                  class="btn btn-primary rounded-pill px-4">
            Start Import
          </button>
        </div>

      </form>
    </div>
  </div>
</div>




        </x-module-check>
        <x-btn-add route="institution.student.add.view" />
      </div>

      <div class="table-responsive">
        <table class="table table-hover table-bordered msc-smart-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Roll No.</th>
              <th>Course</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Gender</th>
              <th>Status</th>
              <th width="120">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($students as $student)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $student->name }}</td>
                <td>{{ $student->roll_number }}</td>
                <td>{{ $student->course?->name ?? '-' }}</td>
                <td>{{ $student->email }}</td>
                <td>{{ $student->mobile ?? '-' }}</td>
                <td class="text-capitalize">{{ $student->gender }}</td>
                <td>
                  <span class="badge bg-{{ $student->status == 1 ? 'success' : 'danger' }}">
                    {{ $student->status == 1 ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td>
                  <x-btn-edit
                    route="institution.student.edit.view"
                    id="{{ Crypt::encrypt($student->id) }}"
                  />
                  <x-btn-delete
                    route="institution.student.delete"
                    id="{{ Crypt::encrypt($student->id) }}"
                  />
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="9" class="text-center text-muted">
                  No students found
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
