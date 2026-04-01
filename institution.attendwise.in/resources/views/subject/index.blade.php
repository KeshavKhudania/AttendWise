<x-structure />
<x-header heading="Subjects" />

<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body table-card-body">

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Subjects</h5>

        <a href="subject-mapping" class="btn btn-outline-primary">
          <i class="fa fa-layer-group me-1"></i> Subject Mapping
        </a>
        <x-btn-add route="institution.subject.add.view" />
      </div>

      <div class="table-responsive">
        <table class="table table-hover table-bordered msc-smart-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Subject Name</th>
              <th>Code</th>
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