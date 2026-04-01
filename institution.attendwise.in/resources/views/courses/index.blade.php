<x-structure />
<x-header heading="Courses" />

<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body table-card-body">

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Courses</h5>
        <x-btn-add route="institution.courses.add.view" />
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
