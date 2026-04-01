<x-structure />
<x-header heading="Classrooms" />

<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body table-card-body">

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">All Classrooms</h5>
        <x-btn-add route="institution.class.room.add.view" />
      </div>

      <div class="table-responsive">
        <table class="table table-hover table-bordered msc-smart-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Classroom</th>
              <th>Type</th>
              <th>Department</th>
              <th>Block</th>
              <th>Capacity</th>
              <th>Status</th>
              <th >Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($classrooms as $room)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $room->name }}</td>
                <td>{{ $room->classroomType?->name ?? '-' }}</td>
                <td>{{ $room->department?->name ?? '-' }}</td>
                <td>{{ $room->block?->name ?? '-' }}</td>
                <td>{{ $room->capacity ?? '-' }}</td>
                <td>
                  <span class="badge bg-{{ $room->status ? 'success' : 'danger' }}">
                    {{ $room->status ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td>
                  <x-btn-edit
                    route="institution.class.room.edit.view"
                    id="{{ Crypt::encrypt($room->id) }}"
                  />
                  <x-btn-delete
                    route="institution.class.room.delete"
                    id="{{ Crypt::encrypt($room->id) }}"
                  />
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center text-muted">
                  No classrooms found
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
