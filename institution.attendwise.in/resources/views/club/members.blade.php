<x-structure />
<x-header heading="{{ $title }}" />

<div class="row">
    <!-- Add Member Form -->
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3"><i class="fa fa-user-plus text-primary me-2"></i> Add Member to {{ $club->name
                    }}</h5>
                @if(session('msg'))
                <div class="alert alert-{{ session('color', 'info') }} alert-dismissible fade show rounded-3"
                    role="alert">
                    {{ session('msg') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <form action="{{ route('institution.club.manage.members.add', Crypt::encrypt($club->id)) }}"
                    method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Member Type <span class="text-danger">*</span></label>
                        <select name="member_type" class="form-select bg-light border-0" id="memberTypeSelect" required
                            onchange="toggleMemberSelects()">
                            <option value="student">Student</option>
                            <option value="faculty">Faculty</option>
                        </select>
                    </div>

                    <div class="mb-3" id="studentSelectGroup">
                        <label class="form-label fw-bold small">Select Student <span
                                class="text-danger">*</span></label>
                        <select name="member_id" class="form-select msc-searchable bg-light border-0"
                            id="studentSelect">
                            <option value="" disabled selected>Search for student...</option>
                            @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->roll_number }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3" id="facultySelectGroup" style="display: none;">
                        <label class="form-label fw-bold small">Select Faculty <span
                                class="text-danger">*</span></label>
                        <select name="member_id_faculty" class="form-select msc-searchable bg-light border-0"
                            id="facultySelect" disabled>
                            <option value="" disabled selected>Search for faculty...</option>
                            @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}">{{ $faculty->name }} ({{ $faculty->employee_code }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Club Designation</label>
                        <input type="text" name="designation" class="form-control bg-light border-0 p-2"
                            placeholder="e.g. President, Core Member...">
                    </div>

                    <div class="form-check form-check-primary mb-3">
                        <label class="form-check-label fw-bold">
                            <input type="checkbox" class="form-check-input" name="can_take_attendance"
                                id="canTakeAttendance">
                            Grant Attendance Taking Rights?
                            <i class="input-helper"></i>
                        </label>
                        <div class="form-text small mt-1">
                            Members with this right can submit attendance for club meetings/activities.
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold py-2"><i
                            class="fa fa-user-plus me-1"></i> Add to Club</button>
                    <a href="{{ route('institution.club.manage') }}" class="btn btn-light w-100 rounded-pill mt-2">Back
                        to Clubs</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Members List -->
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Current Members <span class="badge bg-primary ms-2">{{ count($members)
                            }}</span></h5>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-hover align-middle msc-datatable">
                        <thead class="table-light">
                            <tr>
                                <th>Name & ID</th>
                                <th>Type</th>
                                <th>Designation</th>
                                <th>Attendance Rights</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($members as $member)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm rounded-circle d-flex align-items-center justify-content-center me-3 {{ $member->member_type === 'student' ? 'bg-primary text-white bg-opacity-75' : 'bg-info text-white' }}"
                                            style="width: 40px; height: 40px;">
                                            <i
                                                class="fa {{ $member->member_type === 'student' ? 'fa-user-graduate' : 'fa-chalkboard-teacher' }}"></i>
                                        </div>
                                        <div>
                                            <p class="fw-bold mb-0 text-dark">
                                                @if($member->member_type === 'student')
                                                {{ $member->details?->name }}
                                                @else
                                                {{ $member->details?->name }}
                                                @endif
                                            </p>
                                            <span class="text-muted small">
                                                ID: {{ $member->member_type === 'student' ?
                                                $member->details?->roll_number : $member->details?->employee_code }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="badge rounded-pill {{ $member->member_type === 'student' ? 'bg-primary bg-opacity-10 text-white' : 'bg-info bg-opacity-10 text-white' }}">
                                        {{ ucfirst($member->member_type) }}
                                    </span>
                                </td>
                                <td>
                                    @if($member->designation)
                                    <span class="badge rounded-pill bg-dark bg-opacity-10 text-white">{{
                                        $member->designation }}</span>
                                    @else
                                    <span class="text-muted small">General Member</span>
                                    @endif

                                    @if(!$member->status)
                                    <br><span class="badge bg-danger mt-1">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    @if($member->can_take_attendance)
                                    <span class="badge bg-success bg-opacity-10 text-success"><i
                                            class="fa fa-check-circle me-1"></i> Enabled</span>
                                    @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary"><i
                                            class="fa fa-times-circle me-1"></i> Disabled</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-light text-primary" data-bs-toggle="modal"
                                        data-bs-target="#editMemberModal{{ $member->id }}"><i class="fa fa-edit"></i>
                                        Edit</button>
                                    <button class="btn btn-sm btn-light text-danger msc-delete"
                                        data-action="{{ route('institution.club.manage.members.remove', ['club_id' => Crypt::encrypt($club->id), 'member_id' => Crypt::encrypt($member->id)]) }}"><i
                                            class="fa fa-user-minus"></i></button>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editMemberModal{{ $member->id }}" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow rounded-4">
                                        <form
                                            action="{{ route('institution.club.manage.members.update', ['club_id' => Crypt::encrypt($club->id), 'member_id' => Crypt::encrypt($member->id)]) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="fw-bold modal-title">Edit Club Member</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert bg-light border-0 d-flex align-items-center">
                                                    <i class="fa fa-user-circle fs-3 me-3 text-primary"></i>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold">{{ $member->details?->name }}
                                                        </h6>
                                                        <span class="small text-muted">{{ ucfirst($member->member_type)
                                                            }}</span>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Designation</label>
                                                    <input type="text" name="designation"
                                                        class="form-control bg-light border-0 p-2"
                                                        value="{{ $member->designation }}">
                                                </div>

                                                <div class="form-check form-check-primary mb-3 p-3 bg-light rounded-3">
                                                    <label class="form-check-label fw-bold">
                                                        <input type="checkbox" class="form-check-input"
                                                            name="can_take_attendance"
                                                            id="canTakeAttendance{{ $member->id }}" {{
                                                            $member->can_take_attendance ? 'checked' : '' }}>
                                                        Grant Attendance Rights
                                                        <i class="input-helper"></i>
                                                    </label>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Membership Status</label>
                                                    <select name="status" class="form-select bg-light border-0">
                                                        <option value="1" {{ $member->status == 1 ? 'selected' : ''
                                                            }}>Active</option>
                                                        <option value="0" {{ $member->status == 0 ? 'selected' : ''
                                                            }}>Inactive / Suspended</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-light rounded-pill px-4"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4">Update
                                                    Member</button>
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
    function toggleMemberSelects() {
        const type = document.getElementById('memberTypeSelect').value;
        const studentGroup = document.getElementById('studentSelectGroup');
        const facultyGroup = document.getElementById('facultySelectGroup');
        const studentSelect = document.getElementById('studentSelect');
        const facultySelect = document.getElementById('facultySelect');

        if (type === 'student') {
            studentGroup.style.display = 'block';
            facultyGroup.style.display = 'none';
            studentSelect.disabled = false;
            studentSelect.name = 'member_id';
            facultySelect.disabled = true;
            facultySelect.name = 'member_id_faculty';
        } else {
            studentGroup.style.display = 'none';
            facultyGroup.style.display = 'block';
            studentSelect.disabled = true;
            studentSelect.name = 'member_id_student';
            facultySelect.disabled = false;
            facultySelect.name = 'member_id';
        }

        // Re-initialize searchable dropdowns if they exist
        if (typeof mscSearchableInit === 'function') {
            mscSearchableInit();
        }
    }

    // Force initialization on boot
    document.addEventListener('DOMContentLoaded', function () {
        toggleMemberSelects();
    });
</script>

<x-footer />