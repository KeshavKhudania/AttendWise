<x-structure />
<x-header heading="Courses" />

<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body table-card-body">

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Courses</h5>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#importCoursesModal">
                <i class="fas fa-file-excel me-1"></i> Import Courses
            </button>
            <x-btn-add route="institution.courses.add.view" />
        </div>
      </div>

      <!-- Import Courses Modal -->
      <div class="modal fade" id="importCoursesModal" tabindex="-1">
        <div class="modal-dialog modal-md modal-dialog-scrollable">
          <div class="modal-content border-0 rounded-4 shadow-lg">

            <div class="modal-header border-0 px-4 py-3" style="background: var(--primary);">
              <div>
                <h5 class="modal-title text-white fw-semibold mb-0">Import Courses</h5>
              </div>
              <div class="ms-auto me-3">
                <a href="{{ route('institution.courses.manage.download_sample') }}" class="btn btn-light btn-sm rounded-pill px-3">
                    <i class="fas fa-download me-1"></i> Sample Excel
                </a>
              </div>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('institution.courses.manage.import') }}" method="POST" enctype="multipart/form-data" class="msc-ord-form" data-form-type="ADD">
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
                    name (Required), code, level, batch, duration_years, description, course_type, total_credits, department
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
              <th>Code</th>
              <th>Level</th>
              <th>Duration (Years)</th>
              <th>Status</th>
              <th width="120">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($courses as $course)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $course->name }}</td>
                <td>{{ $course->code }}</td>
                <td>{{ strtoupper($course->level) }}</td>
                <td>{{ $course->duration_years }}</td>
                <td>
                  <span class="badge bg-{{ $course->status == 1 ? 'success' : 'danger' }}">
                    {{ $course->status == 1 ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td>
                  <x-btn-edit
                    route="institution.courses.edit.view"
                    id="{{ Crypt::encrypt($course->id) }}"
                  />
                  <x-btn-delete
                    route="institution.courses.delete"
                    id="{{ Crypt::encrypt($course->id) }}"
                  />
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-muted">
                  No courses found
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
