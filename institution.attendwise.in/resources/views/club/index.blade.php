<x-structure />
<x-header heading="Manage Clubs" />

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <h4 class="card-title">Clubs List</h4>
                <a href="{{ route('institution.club.manage.add.view') }}" class="btn btn-sm btn-primary"><i
                        class="fa fa-plus"></i> Add Club</a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover mt-3 py-3 msc-datatable">
                    <thead>
                        <tr>
                            <th>S.no</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Total Members</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clubs as $club)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-bold">{{ $club->name }}</td>
                            <td>{{ Str::limit($club->description, 50) }}</td>
                            <td><span class="badge badge-info">{{ $club->members_count }}</span></td>
                            <td>
                                @if($club->status == 1)
                                <span class="badge badge-success">Active</span>
                                @else
                                <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('institution.club.manage.members', Crypt::encrypt($club->id)) }}"
                                    class="btn btn-sm btn-info text-white me-1"><i class="fa fa-users"></i> Members</a>
                                <a href="{{ route('institution.club.manage.edit.view', Crypt::encrypt($club->id)) }}"
                                    class="btn btn-sm btn-warning text-white me-1"><i class="fa fa-edit"></i> Edit</a>
                                <button class="btn btn-sm btn-danger msc-delete"
                                    data-action="{{ route('institution.club.manage.delete', Crypt::encrypt($club->id)) }}"><i
                                        class="fa fa-trash"></i> Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<x-footer />