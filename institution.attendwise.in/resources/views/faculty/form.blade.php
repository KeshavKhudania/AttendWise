<x-structure />
<x-header heading="{{ $title }}" />

<div class="aw-page-header mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-heading mb-1">{{ $title }}</h1>
            <p class="text-muted small mb-0">Fill in the required details to {{ $type === 'edit' || $type === 'EDIT' ? 'update the faculty profile' : 'add a new faculty member' }}.</p>
        </div>
        <div>
            <a href="{{ route('institution.faculty.manage') }}" class="btn btn-light border">
                <i class="fa fa-arrow-left me-1.5 opacity-75"></i> Back to Faculty Directory
            </a>
        </div>
    </div>
</div>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card aw-form-card shadow-sm border-0">
        <div class="card-body">

            <form action="{{ $action }}" method="POST" class="msc-ord-form">
                @csrf
                @if($type === 'edit' || $type === 'EDIT')
                    @method('PUT')
                @endif

                <div class="row g-4">
                    {{-- Faculty Profile & Designation --}}
                    <div class="col-md-12">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">Faculty Information & Position</h3>
                                <p class="aw-form-section-subtitle">Employee details, designation hierarchy, and department assignment</p>
                            </div>
                        </div>
                    </div>

                    {{-- Full Name --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">
                                Full Name <span class="aw-field-required">*</span>
                            </label>
                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                required
                                placeholder="e.g. Dr. Rahul Sharma"
                                value="{{ old('name', $faculty->name ?? '') }}"
                            >
                        </div>
                    </div>

                    {{-- Employee Code --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Employee Code</label>
                            <input
                                type="text"
                                name="employee_code"
                                class="form-control"
                                placeholder="e.g. EMP1023"
                                value="{{ old('employee_code', $faculty->employee_code ?? '') }}"
                            >
                        </div>
                    </div>

                    {{-- Designation --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Faculty Designation</label>
                            <select name="designation" id="designation" class="form-select form-control">
                                <option value="">-- Select Designation --</option>

                                <optgroup label="Administrative Leadership">
                                    <option value="chancellor" {{ ($faculty->designation ?? '') == "chancellor" ? "selected" : "" }}>Chancellor</option>
                                    <option value="vice_chancellor" {{ ($faculty->designation ?? '') == "vice_chancellor" ? "selected" : "" }}>Vice Chancellor</option>
                                    <option value="provost" {{ ($faculty->designation ?? '') == "provost" ? "selected" : "" }}>Provost</option>
                                    <option value="dean" {{ ($faculty->designation ?? '') == "dean" ? "selected" : "" }}>Dean</option>
                                    <option value="associate_dean" {{ ($faculty->designation ?? '') == "associate_dean" ? "selected" : "" }}>Associate Dean</option>
                                    <option value="registrar" {{ ($faculty->designation ?? '') == "registrar" ? "selected" : "" }}>Registrar</option>
                                    <option value="hod" {{ ($faculty->designation ?? '') == "hod" ? "selected" : "" }}>Head of Department (HOD)</option>
                                    <option value="director" {{ ($faculty->designation ?? '') == "director" ? "selected" : "" }}>Director</option>
                                </optgroup>

                                <optgroup label="Professorial Ranks">
                                    <option value="professor_emeritus" {{ ($faculty->designation ?? '') == "professor_emeritus" ? "selected" : "" }}>Professor Emeritus</option>
                                    <option value="professor" {{ ($faculty->designation ?? '') == "professor" ? "selected" : "" }}>Professor</option>
                                    <option value="associate_professor" {{ ($faculty->designation ?? '') == "associate_professor" ? "selected" : "" }}>Associate Professor</option>
                                    <option value="assistant_professor" {{ ($faculty->designation ?? '') == "assistant_professor" ? "selected" : "" }}>Assistant Professor</option>
                                </optgroup>

                                <optgroup label="Instructional Staff">
                                    <option value="senior_lecturer" {{ ($faculty->designation ?? '') == "senior_lecturer" ? "selected" : "" }}>Senior Lecturer</option>
                                    <option value="lecturer" {{ ($faculty->designation ?? '') == "lecturer" ? "selected" : "" }}>Lecturer</option>
                                    <option value="instructor" {{ ($faculty->designation ?? '') == "instructor" ? "selected" : "" }}>Instructor</option>
                                    <option value="adjunct_professor" {{ ($faculty->designation ?? '') == "adjunct_professor" ? "selected" : "" }}>Adjunct Professor</option>
                                    <option value="visiting_professor" {{ ($faculty->designation ?? '') == "visiting_professor" ? "selected" : "" }}>Visiting Professor</option>
                                    <option value="guest_faculty" {{ ($faculty->designation ?? '') == "guest_faculty" ? "selected" : "" }}>Guest Faculty</option>
                                </optgroup>

                                <optgroup label="Research & Support">
                                    <option value="research_fellow" {{ ($faculty->designation ?? '') == "research_fellow" ? "selected" : "" }}>Research Fellow</option>
                                    <option value="post_doc" {{ ($faculty->designation ?? '') == "post_doc" ? "selected" : "" }}>Postdoctoral Fellow</option>
                                    <option value="research_associate" {{ ($faculty->designation ?? '') == "research_associate" ? "selected" : "" }}>Research Associate</option>
                                    <option value="teaching_assistant" {{ ($faculty->designation ?? '') == "teaching_assistant" ? "selected" : "" }}>Teaching Assistant (TA)</option>
                                    <option value="lab_instructor" {{ ($faculty->designation ?? '') == "lab_instructor" ? "selected" : "" }}>Lab Instructor/Demonstrator</option>
                                    <option value="librarian" {{ ($faculty->designation ?? '') == "librarian" ? "selected" : "" }}>Librarian</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>

                    {{-- Department --}}
                    <div class="col-md-8">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">
                                Primary Department <span class="aw-field-required">*</span>
                            </label>
                            <select name="department_id" class="form-select form-control" required>
                                <option value="">Select Department</option>
                                @foreach ($departments as $item)
                                    <option value="{{ $item->id }}" {{ old('department', $faculty->department_id ?? '') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">
                                Status <span class="aw-field-required">*</span>
                            </label>
                            <select name="status" class="form-select form-control" required>
                                <option value="1" {{ old('status', $faculty->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', $faculty->status ?? '') == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    {{-- Contact & Teaching Allocation Section --}}
                    <div class="col-md-12 mt-4">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">Contact & Teaching Allocations</h3>
                                <p class="aw-form-section-subtitle">Official communication details and assigned subject courses</p>
                            </div>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">
                                Email Address <span class="aw-field-required">*</span>
                            </label>
                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                required
                                placeholder="faculty@institution.edu"
                                value="{{ old('email', $faculty->email ?? '') }}"
                            >
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-6">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Mobile Number</label>
                            <input
                                type="text"
                                name="mobile"
                                class="form-control"
                                placeholder="10-digit mobile number"
                                value="{{ old('mobile', $faculty->mobile ?? '') }}"
                            >
                        </div>
                    </div>

                    {{-- Subjects --}}
                    <div class="col-md-12">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">
                                Assigned Subjects <span class="aw-field-required">*</span>
                            </label>
                            <select name="subjects[]" class="form-select form-control msc-searchable" multiple required>
                                <option value="" disabled>Select Subjects</option>
                                @foreach($subjects as $subject)
                                    <option 
                                        value="{{ Crypt::encryptString($subject->id) }}" 
                                        {{ in_array($subject->id, $selectedSubjects ?? []) ? 'selected' : '' }}
                                    >
                                        {{ $subject->name }} ({{ $subject->code }})
                                    </option>
                                @endforeach
                            </select>
                            <span class="aw-field-help"><i class="fas fa-info-circle me-1 opacity-75"></i> Select one or multiple subjects that this faculty member teaches.</span>
                        </div>
                    </div>

                    {{-- Working Day Availability --}}
                    <div class="col-md-12 mt-4">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">Timetable Working Day Availability</h3>
                                <p class="aw-form-section-subtitle">
                                    Days this faculty member is available for lecture scheduling.
                                    Leave as Mon&ndash;Fri if no custom availability is needed.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Available Working Days</label>
                            @php
                                $facultyWorkingDays = old('working_days',
                                    $faculty->working_days ?? ['Monday','Tuesday','Wednesday','Thursday','Friday']
                                );
                            @endphp
                            <div class="d-flex flex-wrap gap-3 mt-1">
                                @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                                <div class="form-check p-0">
                                    <input class="btn-check" type="checkbox"
                                           name="working_days[]" value="{{ $day }}"
                                           id="fwd_{{ $day }}"
                                           {{ in_array($day, (array)$facultyWorkingDays) ? 'checked' : '' }}>
                                    <label class="btn btn-sm rounded-pill px-3 py-2
                                                  {{ in_array($day, ['Saturday','Sunday']) ? 'btn-outline-warning' : 'btn-outline-primary' }}"
                                           for="fwd_{{ $day }}">
                                        {{ substr($day, 0, 3) }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            <span class="aw-field-help">
                                <i class="fas fa-info-circle me-1 opacity-75"></i>
                                If a day is unchecked, the auto-scheduler will <strong>never</strong> assign this faculty on that day.
                                Faculty with no selection default to the institution&rsquo;s working days.
                            </span>
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="col-md-12">
                        <x-form-buttons />
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>

<x-footer />

