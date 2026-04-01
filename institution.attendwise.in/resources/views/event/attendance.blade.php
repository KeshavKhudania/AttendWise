<x-structure />
<x-header heading="{{ $title }}" />

<div class="row">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-body p-4 bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold mb-1">{{ $event->name }}</h3>
                        <p class="mb-0 opacity-75">
                            <i class="fa fa-calendar-alt me-2"></i> {{ date('D, d M Y', strtotime($event->event_date))
                            }} |
                            <i class="fa fa-clock ms-2 me-2"></i> {{ date('h:i A', strtotime($event->start_time)) }}
                        </p>
                    </div>
                    <div class="text-end">
                        <span class="fs-4 fw-bold block">{{ count($participants) }}</span>
                        <div class="small opacity-75">Total Participants</div>
                    </div>
                </div>
            </div>
            <div class="card-body bg-light py-2 px-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center small">
                    <span class="text-muted"><i class="fa fa-map-marker-alt me-1"></i> {{ $event->classroom->name ??
                        $event->venue_details }}</span>
                    <a href="{{ route('institution.events.manage') }}"
                        class="text-primary fw-bold text-decoration-none"><i class="fa fa-arrow-left me-1"></i> Back to
                        Events</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Participant List</h5>
                    <div class="input-group search-box" style="width: 250px;">
                        <span class="input-group-text bg-light border-0"><i class="fa fa-search text-muted"></i></span>
                        <input type="text" id="participantSearch" class="form-control bg-light border-0"
                            placeholder="Search name or ID...">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="attendanceTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0">Name & ID</th>
                                <th class="border-0">Type / Role</th>
                                <th class="border-0 text-center">Status</th>
                                <th class="border-0 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($participants as $p)
                            <tr class="participant-row">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm rounded-circle d-flex align-items-center justify-content-center me-3 {{ $p->participant_type == 'student' ? 'bg-primary text-white bg-opacity-75' : 'bg-info text-white' }}"
                                            style="width: 40px; height: 40px;">
                                            <i
                                                class="fa {{ $p->participant_type == 'student' ? 'fa-user-graduate' : 'fa-chalkboard-teacher' }}"></i>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="text-dark fw-bold search-target">{{ $p->details->name ??
                                                'Unknown' }}</span>
                                            <span class="text-muted small search-target">ID: {{ $p->participant_type ==
                                                'student' ?
                                                ($p->details->roll_number ?? 'N/A') : ($p->details->employee_code ??
                                                'N/A') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="small d-block text-dark opacity-75">{{ ucfirst($p->participant_type)
                                        }}</span>
                                    <span class="badge rounded-pill bg-dark bg-opacity-5 text-dark fw-normal px-2"
                                        style="font-size: 10px;">{{ $p->role ?? 'N/A' }}</span>
                                </td>
                                <td class="text-center">
                                    <div id="status-badge-{{ $p->id }}">
                                        @if($p->attendance_status)
                                        <span class="badge bg-success rounded-pill px-3 py-2"><i
                                                class="fa fa-check me-1"></i> Present</span>
                                        @else
                                        <span class="badge bg-light text-muted border rounded-pill px-3 py-2">Mark
                                            Attendance</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm rounded-pill overflow-hidden border shadow-sm"
                                        role="group">
                                        <button type="button"
                                            class="btn btn-white mark-attendance {{ $p->attendance_status == 1 ? 'active bg-success text-white' : '' }}"
                                            data-id="{{ Crypt::encrypt($p->id) }}" data-status="1">
                                            Present
                                        </button>
                                        <button type="button"
                                            class="btn btn-white mark-attendance {{ $p->attendance_status == 0 ? 'active bg-danger text-white' : '' }}"
                                            data-id="{{ Crypt::encrypt($p->id) }}" data-status="0">
                                            Absent
                                        </button>
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
</div>

<style>
    .mark-attendance.active {
        margin: 0 !important;
    }

    .search-box:focus-within {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        border-radius: 0.375rem;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Attendance Toggle Logic
        document.querySelectorAll('.mark-attendance').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const status = this.getAttribute('data-status');
                const parentRow = this.closest('tr');
                const pId = {{ $p-> id ?? '0'
            }}; // This is problematic in a loop, but we can pass real ID in data-attr

        // Get real primary key for status update
        // We'll update the whole group
        const group = this.closest('.btn-group').querySelectorAll('.mark-attendance');

        const url = "{{ route('institution.events.manage.attendance.mark', ':id') }}".replace(':id', id);

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ status: status })
        })
            .then(response => response.json())
            .then(data => {
                // Update UI
                group.forEach(btn => btn.classList.remove('active', 'bg-success', 'bg-danger', 'text-white'));
                if (status == 1) {
                    this.classList.add('active', 'bg-success', 'text-white');
                } else {
                    this.classList.add('active', 'bg-danger', 'text-white');
                }

                // Update status badge (we need a way to target it correctly)
                const statusBadgeContainer = parentRow.querySelector('td:nth-child(3) div');
                if (status == 1) {
                    statusBadgeContainer.innerHTML = '<span class="badge bg-success rounded-pill px-3 py-2"><i class="fa fa-check me-1"></i> Present</span>';
                } else {
                    statusBadgeContainer.innerHTML = '<span class="badge bg-danger rounded-pill px-3 py-2"><i class="fa fa-times me-1"></i> Absent</span>';
                }
            })
            .catch(error => {
                alert('Error marking attendance');
            });
    });
        });

    // Search Logic
    document.getElementById('participantSearch').addEventListener('keyup', function () {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.participant-row').forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    });
    });
</script>

<x-footer />