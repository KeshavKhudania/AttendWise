<x-structure />
<x-header heading="Faculty" />

<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body table-card-body">

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Faculty Members</h5>
        <x-btn-add route="institution.faculty.add.view" />
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
                  <x-btn-edit
                    route="institution.faculty.edit.view"
                    id="{{ Crypt::encrypt($member->id) }}"
                  />
                  <x-btn-delete
                    route="institution.faculty.delete"
                    id="{{ Crypt::encrypt($member->id) }}"
                  />
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
