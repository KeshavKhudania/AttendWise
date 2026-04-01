<x-structure />
<x-header heading="{{ $title }}" />

@if(session('msg'))
<div class="alert alert-{{ session('color', 'info') }} alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4"
    role="alert">
    <div class="d-flex align-items-center">
        <div class="bg-white rounded-circle p-2 me-3 shadow-xs">
            <i class="fa {{ session('color') == 'success' ? 'fa-check text-success' : 'fa-info text-info' }}"></i>
        </div>
        <div class="fw-bold">{{ session('msg') }}</div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if($event->is_open)
<div class="alert alert-info border-0 shadow-sm mb-4 d-flex align-items-center rounded-4 p-3 bg-opacity-25"
    style="background-color: rgba(13, 202, 240, 0.1);">
    <div class="bg-info rounded-circle p-2 me-3 shadow-sm d-flex align-items-center justify-content-center"
        style="width: 40px; height: 40px;">
        <i class="fa fa-globe text-white"></i>
    </div>
    <div>
        <h6 class="mb-0 fw-bold">Open Eligibility Enabled</h6>
        <span class="small text-dark text-opacity-75">This event is open for all students. Pre-recruitment is not
            required, but you can assign leads or organizers below.</span>
    </div>
</div>
@endif

<div class="row">
    {{-- Recruitment Sidebar --}}
    <div class="col-xl-4 col-lg-5 grid-margin stretch-card">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h5 class="fw-bold mb-0 text-dark"><i class="fa fa-user-plus text-primary me-2"></i> Recruit
                    Participants</h5>
            </div>
            <div class="card-body">
                <ul class="nav nav-pills nav-fill mb-4 bg-light rounded-pill p-1" id="recruitmentTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill fw-bold" id="students-tab" data-bs-toggle="tab"
                            data-bs-target="#students" type="button" role="tab">Students</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill fw-bold" id="faculty-tab" data-bs-toggle="tab"
                            data-bs-target="#faculty" type="button" role="tab">Faculties</button>
                    </li>
                </ul>

                <div class="tab-content border-0 p-0" id="recruitmentTabContent">
                    {{-- Students Recruitment --}}
                    <div class="tab-pane fade show active" id="students" role="tabpanel">
                        <form
                            action="{{ route('institution.events.manage.participants.add', Crypt::encrypt($event->id)) }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="participant_type" value="student">

                            <div class="mb-3">
                                <label class="form-label fw-600 small">Filter by Sections</label>
                                <select name="section_ids[]" class="form-select msc-searchable" multiple
                                    data-placeholder="Select sections...">
                                    @foreach($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->name }} ({{
                                        $section->department->name ?? 'N/A' }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-600 small">Filter by Clubs</label>
                                <select name="club_ids[]" class="form-select msc-searchable" multiple
                                    data-placeholder="Select clubs...">
                                    @foreach($clubs as $club)
                                    <option value="{{ $club->id }}">{{ $club->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-600 small">Filter by Courses</label>
                                <select name="course_ids[]" class="form-select msc-searchable" multiple
                                    data-placeholder="Select courses...">
                                    @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-600 small">Individual Students (Custom List)</label>
                                <select name="student_ids[]" class="form-select msc-searchable" multiple
                                    data-placeholder="Search students...">
                                    @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->roll_number
                                        }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-600 small">Default Role</label>
                                <input type="text" name="role" class="form-control bg-light border-0"
                                    placeholder="e.g. Attendee, Volunteer">
                            </div>

                            <div class="form-check form-check-primary mb-4 bg-light p-3 rounded-3">
                                <label class="form-check-label fw-bold">
                                    <input type="checkbox" class="form-check-input" name="can_take_attendance"
                                        value="1">
                                    Can take attendance?
                                    <i class="input-helper"></i>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 shadow-sm fw-bold">
                                <i class="fa fa-plus-circle me-1"></i> Recruit Students
                            </button>
                        </form>
                    </div>

                    {{-- Faculty Recruitment --}}
                    <div class="tab-pane fade" id="faculty" role="tabpanel">
                        <form
                            action="{{ route('institution.events.manage.participants.add', Crypt::encrypt($event->id)) }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="participant_type" value="faculty">

                            <div class="mb-3">
                                <label class="form-label fw-600 small">Select Faculties <span
                                        class="text-danger">*</span></label>
                                <select name="faculty_ids[]" class="form-select msc-searchable" multiple required
                                    data-placeholder="Select faculties...">
                                    @foreach($faculties as $faculty)
                                    <option value="{{ $faculty->id }}">{{ $faculty->name }} ({{ $faculty->employee_code
                                        }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-600 small">Role in Event</label>
                                <input type="text" name="role" class="form-control bg-light border-0"
                                    placeholder="e.g. Organizer, Coordinator, Speaker" value="Organizer">
                            </div>

                            <div class="form-check form-check-primary mb-4 bg-light p-3 rounded-3">
                                <label class="form-check-label fw-bold">
                                    <input type="checkbox" class="form-check-input" name="can_take_attendance" value="1"
                                        checked>
                                    Can take attendance?
                                    <i class="input-helper"></i>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 shadow-sm fw-bold">
                                <i class="fa fa-user-plus me-1"></i> Add Faculty
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Participant List --}}
    <div class="col-xl-8 col-lg-7 grid-margin stretch-card">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Assigned Participants <span
                            class="badge bg-primary bg-opacity-10 text-primary ms-2">{{ count($participants) }}</span>
                    </h5>
                    <a href="{{ route('institution.events.manage') }}"
                        class="btn btn-sm btn-light border rounded-pill px-3">Back to List</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle msc-datatable">
                        <thead>
                            <tr class="bg-light">
                                <th class="border-0 rounded-start">Name & ID</th>
                                <th class="border-0">Type</th>
                                <th class="border-0">Role</th>
                                <th class="border-0 text-center">Attendance Rights</th>
                                <th class="border-0 text-end rounded-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($participants as $p)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm rounded-circle d-flex align-items-center justify-content-center me-3 {{ $p->participant_type == 'student' ? 'bg-primary text-white bg-opacity-75' : 'bg-info text-white' }}"
                                            style="width: 40px; height: 40px;">
                                            <i
                                                class="fa {{ $p->participant_type == 'student' ? 'fa-user-graduate' : 'fa-chalkboard-teacher' }}"></i>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="text-dark fw-bold">{{ $p->details->name ?? 'Unknown' }}</span>
                                            <span class="text-muted small">ID: {{ $p->participant_type == 'student' ?
                                                ($p->details->roll_number ?? 'N/A') : ($p->details->employee_code ??
                                                'N/A') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="badge rounded-pill {{ $p->participant_type == 'student' ? 'bg-primary' : 'bg-info' }} bg-opacity-10 text-{{ $p->participant_type == 'student' ? 'primary' : 'info' }}">
                                        {{ ucfirst($p->participant_type) }}
                                    </span>
                                </td>
                                <td>
                                    @if($p->role)
                                    <span class="badge rounded-pill bg-dark bg-opacity-10 text-dark fw-normal px-3">{{
                                        $p->role }}</span>
                                    @else
                                    <span class="text-muted small">N/A</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="form-check form-switch d-inline-block">
                                        <input class="form-check-input attendance-toggle" type="checkbox"
                                            data-id="{{ Crypt::encrypt($p->id) }}" {{ $p->can_take_attendance ?
                                        'checked' : '' }}>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-icon btn-light-primary btn-sm rounded-circle me-1"
                                        data-bs-toggle="modal" data-bs-target="#editParticipantModal{{ $p->id }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-icon btn-light-danger btn-sm rounded-circle msc-delete"
                                        data-action="{{ route('institution.events.manage.participants.remove', Crypt::encrypt($p->id)) }}">
                                        <i class="fa fa-user-minus"></i>
                                    </button>
                                </td>
                            </tr>

                            {{-- Edit Modal --}}
                            <div class="modal fade" id="editParticipantModal{{ $p->id }}" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow rounded-4">
                                        <form
                                            action="{{ route('institution.events.manage.participants.update', Crypt::encrypt($p->id)) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="fw-bold modal-title">Edit Participant</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div
                                                    class="alert bg-light border-0 d-flex align-items-center rounded-3">
                                                    <div class="avatar-sm rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3"
                                                        style="width: 45px; height: 45px;">
                                                        <i
                                                            class="fa {{ $p->participant_type == 'student' ? 'fa-user-graduate' : 'fa-chalkboard-teacher' }}"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold">{{ $p->details?->name }}</h6>
                                                        <span class="small text-muted">{{ ucfirst($p->participant_type)
                                                            }} | ID: {{ $p->participant_type == 'student' ?
                                                            $p->details?->roll_number : $p->details?->employee_code
                                                            }}</span>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Role / Designation</label>
                                                    <input type="text" name="role"
                                                        class="form-control bg-light border-0 py-2"
                                                        value="{{ $p->role }}"
                                                        placeholder="e.g. Organizer, Volunteer, lead...">
                                                </div>

                                                <div class="form-check form-check-primary mb-3 p-3 bg-light rounded-3">
                                                    <label class="form-check-label fw-bold">
                                                        <input type="checkbox" class="form-check-input"
                                                            name="can_take_attendance" {{ $p->can_take_attendance ?
                                                        'checked' : '' }}>
                                                        Grant Attendance Rights
                                                        <i class="input-helper"></i>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-light rounded-pill px-4"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit"
                                                    class="btn btn-primary rounded-pill px-4 fw-bold">Update
                                                    Participant</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Toggle Attendance Rights via AJAX
        document.querySelectorAll('.attendance-toggle').forEach(toggle => {
            toggle.addEventListener('change', function () {
                const id = this.getAttribute('data-id');
                const url = "{{ route('institution.events.manage.participants.toggle_attendance', ':id') }}".replace(':id', id);

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        // Success
                    })
                    .catch(error => {
                        alert('Error updating status');
                        this.checked = !this.checked; // Revert
                    });
            });
        });
    });
</script>

<x-footer />