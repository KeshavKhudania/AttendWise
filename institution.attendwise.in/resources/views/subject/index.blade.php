<x-structure />
<x-header heading="Subjects" />

<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body table-card-body">

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Subjects</h5>

        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#importSubjectsModal">
                <i class="fas fa-file-excel me-1"></i> Import Subjects
            </button>
            <a href="subject-mapping" class="btn btn-outline-primary btn-sm rounded-pill px-3">
              <i class="fa fa-layer-group me-1"></i> Subject Mapping
            </a>
            <x-btn-add route="institution.subject.add.view" />
        </div>
      </div>

      <!-- Import Subjects Modal -->
      <div class="modal fade" id="importSubjectsModal" tabindex="-1">
        <div class="modal-dialog modal-md modal-dialog-scrollable">
          <div class="modal-content border-0 rounded-4 shadow-lg">

            <div class="modal-header border-0 px-4 py-3" style="background: var(--primary);">
              <div>
                <h5 class="modal-title text-white fw-semibold mb-0">Import Subjects</h5>
              </div>
              <div class="ms-auto me-3">
                <a href="{{ route('institution.subject.manage.download_sample') }}" class="btn btn-light btn-sm rounded-pill px-3">
                    <i class="fas fa-download me-1"></i> Sample Excel
                </a>
              </div>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('institution.subject.manage.import') }}" method="POST" enctype="multipart/form-data" class="msc-ord-form" data-form-type="ADD">
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
                    name (Required), code (Required), department, course, semester, additional_department, type, classroom_type, credits, weekly_lectures, max_lectures_per_day, min_lectures_per_day, continuous_lectures, is_elective, syllabus_url, passing_marks, total_marks
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
              <th>Subject Name</th>
              <th>Code</th>
              <th>Course & Sem</th>
              <th>Department</th>
              <th>Type</th>
              <th>Classroom Type</th>
              <th>Credits</th>
              <th>Weekly Lectures</th>
              <th>Elective</th>
              <th>Status</th>
              <th width="120">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($subjects as $subject)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $subject->name }}</td>
              <td>{{ $subject->code }}</td>
              <td>
                @if($subject->course)
                  {{ $subject->course->name }} (Sem {{ $subject->semester }})
                @else
                  -
                @endif
              </td>
              <td>
                {{ $subject->department->name ?? '-' }}
                @if($subject->additionalDepartment)
                  <br><small class="text-muted">+ {{ $subject->additionalDepartment->name }}</small>
                @endif
              </td>
              <td class="text-capitalize">{{ $subject->type }}</td>
              <td>{{ $subject->classroom->name ?? '-' }}</td>
              <td>{{ $subject->credits ?? '-' }}</td>
              <td>{{ $subject->weekly_lectures ?? '-' }}</td>
              <td>{{ $subject->is_elective == 1 ? "Yes" : 'NO' }}</td>
              <td>
                <span class="badge bg-{{ $subject->status == 1 ? 'success' : 'danger' }}">
                  {{ $subject->status == 1 ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td>
                <x-btn-edit route="institution.subject.edit.view" id="{{ Crypt::encrypt($subject->id) }}" />
                <x-btn-delete route="institution.subject.delete" id="{{ Crypt::encrypt($subject->id) }}" />
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="10" class="text-center text-muted">
                No subjects found
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