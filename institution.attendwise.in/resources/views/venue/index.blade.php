<x-structure />
<x-header heading="{{ $title }}" />

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card shadow-sm">
        <div class="card-body table-card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title mb-0">Venues</h4>
                <x-btn-add route="institution.venues.add.view" />
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered msc-smart-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Venue Name</th>
                            <th>Type</th>
                            <th>Geofence</th>
                            <th>Created At</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($venues as $venue)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-bold">{{ $venue->name }}</div>
                                @if($venue->description)
                                <small class="text-muted">{{ Str::limit($venue->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    {{ $venue->type }}
                                </span>
                            </td>
                            <td>
                                @php
                                $pointsCount = is_array($venue->latlng) ? count($venue->latlng) : 0;
                                @endphp
                                @if($pointsCount > 0)
                                <span class="badge bg-success bg-opacity-10 text-success">
                                    <i class="fa fa-map-marked-alt me-1"></i> {{ $pointsCount }} points
                                </span>
                                @else
                                <span class="text-muted">No area defined</span>
                                @endif
                            </td>
                            <td>{{ $venue->created_at?->format('d M Y') }}</td>
                            <td>
                                <x-btn-edit route="institution.venues.edit.view"
                                    id="{{ Crypt::encrypt($venue->id) }}" />
                                <x-btn-delete route="institution.venues.delete" id="{{ Crypt::encrypt($venue->id) }}" />
                            </td>
                        </tr>
                        @empty
                        <tr class="empty">
                            <td colspan="6" class="text-center text-muted">
                                No venues found. <a href="{{ route('institution.venues.add.view') }}">Add one now</a>.
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