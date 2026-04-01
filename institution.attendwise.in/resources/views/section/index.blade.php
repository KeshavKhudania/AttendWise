<x-structure />
<x-header heading="Sections" />

<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body table-card-body">

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Sections</h5>
        <x-btn-add route="institution.section.add.view" />
      </div>

      <div class="table-responsive">
        <table class="table table-hover table-bordered msc-smart-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Section Name</th>
              <th>Academic Year</th>
              <th>Department</th>
              <th>Additional Depts.</th>
              <th>Status</th>
              <th width="120">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($sections as $section)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $section->name }}</td>
              <td>{{ $section->academic_year }}</td>
              <td>{{ $section->department?->name ?? '-' }}</td>
              <td>
                @forelse ($section->additionalDepartments as $dept)
                <span class="badge bg-info text-dark">{{ $dept->name }}</span>
                @empty
                <span class="text-muted small">None</span>
                @endforelse
              </td>
              <td>
                <span class="badge bg-{{ $section->status == 1 ? 'success' : 'danger' }}">
                  {{ $section->status == 1 ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td>
                <x-btn-edit route="institution.section.edit.view" id="{{ Crypt::encrypt($section->id) }}" />
                <x-btn-delete route="institution.section.delete" id="{{ Crypt::encrypt($section->id) }}" />
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="text-center text-muted">
                No sections found
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