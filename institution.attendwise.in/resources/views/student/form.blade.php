<x-structure />
<x-header heading="{{ $title }}" />

<div class="aw-page-header mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-heading mb-1">{{ $title }}</h1>
            <p class="text-muted small mb-0">Fill in the required details to {{ $type === 'edit' || $type === 'EDIT' ? 'update the student profile' : 'register a new student' }}.</p>
        </div>
        <div>
            <a href="{{ route('institution.student.manage') }}" class="btn btn-light border">
                <i class="fa fa-arrow-left me-1.5 opacity-75"></i> Back to Student Roster
            </a>
        </div>
    </div>
</div>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card aw-form-card shadow-sm border-0">
        <div class="card-body">

            <form action="{{ $action }}" method="POST" id="mainForm">
                @csrf
                @if($type === 'edit' || $type === 'EDIT')
                    @method('PUT')
                @endif

                <div class="row g-4">
                    {{-- Personal Information Section --}}
                    <div class="col-md-12">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">Personal Information</h3>
                                <p class="aw-form-section-subtitle">Basic identity and contact details of the student</p>
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
                                placeholder="e.g. Aman Verma"
                                value="{{ old('name', $student->name ?? '') }}"
                            >
                        </div>
                    </div>

                    {{-- Roll Number --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">
                                Roll Number <span class="aw-field-required">*</span>
                            </label>
                            <input
                                type="text"
                                name="roll_number"
                                class="form-control"
                                required
                                placeholder="e.g. CS2023-041"
                                value="{{ old('roll_number', $student->roll_number ?? '') }}"
                            >
                        </div>
                    </div>

                    {{-- Gender --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">
                                Gender <span class="aw-field-required">*</span>
                            </label>
                            <select name="gender" class="form-select form-control" required>
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender', $student->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $student->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender', $student->gender ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="col-md-5">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">
                                Email Address <span class="aw-field-required">*</span>
                            </label>
                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                required
                                placeholder="student@college.edu"
                                value="{{ old('email', $student->email ?? '') }}"
                            >
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Phone Number</label>
                            <input
                                type="text"
                                name="phone"
                                class="form-control"
                                placeholder="10-digit mobile number"
                                value="{{ old('phone', $student->phone ?? '') }}"
                            >
                        </div>
                    </div>

                    {{-- Date of Birth --}}
                    <div class="col-md-3">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Date of Birth</label>
                            <input
                                type="date"
                                name="date_of_birth"
                                class="form-control"
                                value="{{ old('date_of_birth', $student->date_of_birth ?? '') }}"
                            >
                        </div>
                    </div>

                    {{-- Academic Allocation Section --}}
                    <div class="col-md-12 mt-4">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">Academic & Account Allocation</h3>
                                <p class="aw-form-section-subtitle">Enrollment program, section assignment, and access credentials</p>
                            </div>
                        </div>
                    </div>

                    {{-- Course --}}
                    <div class="col-md-6">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">
                                Academic Course <span class="aw-field-required">*</span>
                            </label>
                            <select name="course_id" id="course_id" class="form-select form-control" required>
                                <option value="">Select Course</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}"
                                        {{ old('course_id', $student->course_id ?? '') == $course->id ? 'selected' : '' }}>
                                        {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Section --}}
                    <div class="col-md-6">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">
                                Assigned Section <span class="aw-field-required">*</span>
                            </label>
                            <select name="section_id" id="section_id" class="form-select form-control" required onfocus="fetchRelatedList({
                                                        'data': {
                                                                'course_id': $('#course_id').val()
                                                                },
                                                        'target': '#section_id',
                                                        'url': `fetch/sections-by-course`,
                                                        'showLoading': false,
                                                    })">
                                <option value="">Select Section</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}"
                                        {{ old('section_id', $student->section_id ?? '') == $section->id ? 'selected' : '' }}>
                                        {{ $section->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">
                                Account Status <span class="aw-field-required">*</span>
                            </label>
                            <select name="status" class="form-select form-control" required>
                                <option value="1" {{ old('status', $student->status ?? 1) == 1 ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="0" {{ old('status', $student->status ?? '') == 0 ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="col-md-8">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Login Password</label>
                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                autocomplete="new-password"
                                placeholder="{{ ($type === 'edit' || $type === 'EDIT') ? 'Leave blank to keep current password' : 'Leave blank to auto-generate password' }}"
                            >
                            <span class="aw-field-help"><i class="fas fa-info-circle me-1 opacity-75"></i> {{ ($type === 'edit' || $type === 'EDIT') ? 'Only enter text if changing the password' : 'Optional. A default password will be generated if empty.' }}</span>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="col-md-12">
                        <x-form-buttons />
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>

<x-footer />

