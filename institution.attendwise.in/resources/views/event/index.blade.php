<x-structure />
<x-header heading="Manage Events" />

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="card-title mb-0">Events List</h4>
                    <p class="text-muted small">Create and manage workshops, seminars, and other institutional events.
                    </p>
                </div>
                <a href="{{ route('institution.events.manage.add.view') }}"
                    class="btn btn-primary d-flex align-items-center">
                    <i class="fa fa-plus me-2"></i> Create Event
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mt-3 msc-datatable">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">S.no</th>
                            <th class="border-0">Event Name</th>
                            <th class="border-0">Type</th>
                            <th class="border-0">Venue</th>
                            <th class="border-0">Date & Time</th>
                            <th class="border-0">Status</th>
                            <th class="border-0 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-40px me-3">
                                        <div class="symbol-label bg-light-primary text-primary fw-bold">
                                            {{ substr($event->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark">{{ $event->name }}</span>
                                        <span class="text-muted small">{{ Str::limit($event->description, 30) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-opacity-10 text-primary bg-primary">{{ $event->event_type
                                    }}</span>
                            </td>
                            <td>
                                {{-- Internal Classrooms --}}
                                @if($event->classrooms->count() > 0)
                                @foreach($event->classrooms as $room)
                                <div class="mb-1">
                                    <i class="fa fa-building text-primary me-1"></i>
                                    {{ $room->name }} ({{ $room->block->name ?? 'N/A' }})
                                </div>
                                @endforeach
                                @endif

                                {{-- External Geofenced Venues (Grounds, Hostels) --}}
                                @if($event->externalVenues->count() > 0)
                                @foreach($event->externalVenues as $ev)
                                <div class="mb-1">
                                    <i class="fa fa-map-marker-alt text-danger me-1"></i>
                                    {{ $ev->name }} <small class="text-muted">({{ $ev->type }})</small>
                                </div>
                                @endforeach
                                @endif

                                {{-- Manual Venue Details --}}
                                @if($event->venue_details)
                                <div class="text-muted small italic">
                                    <i class="fa fa-info-circle me-1"></i> {{ $event->venue_details }}
                                </div>
                                @endif

                                @if($event->classrooms->count() == 0 && $event->externalVenues->count() == 0 &&
                                !$event->venue_details)
                                <span class="text-muted small">N/A</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-dark fw-bold">{{ date('D, d M Y', strtotime($event->event_date))
                                        }}</span>
                                    <span class="text-muted small">
                                        <i class="fa fa-clock me-1"></i>
                                        {{ date('h:i A', strtotime($event->start_time)) }}
                                        @if($event->end_time) - {{ date('h:i A', strtotime($event->end_time)) }} @endif
                                    </span>
                                    @if($event->is_open)
                                    <span class="badge bg-info bg-opacity-10 text-info mt-1"
                                        style="width: fit-content; font-size: 10px;">
                                        <i class="fa fa-globe me-1"></i> Open for all
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($event->status == 1)
                                <span class="badge bg-success bg-opacity-10 text-success"><i
                                        class="fa fa-check-circle me-1"></i> Active</span>
                                @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary"><i
                                        class="fa fa-times-circle me-1"></i> Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light text-primary border" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                        <li><a class="dropdown-item py-2"
                                                href="{{ route('institution.events.manage.participants', Crypt::encrypt($event->id)) }}"><i
                                                    class="fa fa-users me-2 text-info"></i> Participants</a></li>
                                        <li><a class="dropdown-item py-2"
                                                href="{{ route('institution.events.manage.attendance', Crypt::encrypt($event->id)) }}"><i
                                                    class="fa fa-calendar-check me-2 text-success"></i> Take
                                                Attendance</a></li>
                                        <li><a class="dropdown-item py-2"
                                                href="{{ route('institution.events.manage.edit.view', Crypt::encrypt($event->id)) }}"><i
                                                    class="fa fa-edit me-2 text-warning"></i> Edit Details</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><button class="dropdown-item py-2 text-danger msc-delete"
                                                data-action="{{ route('institution.events.manage.delete', Crypt::encrypt($event->id)) }}"><i
                                                    class="fa fa-trash me-2"></i> Delete Event</button></li>
                                    </ul>
                                </div>
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