<x-structure />
<x-header heading="{{ $title }}" />

<div class="aw-page-header mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-heading mb-1">{{ $title }}</h1>
            <p class="text-muted small mb-0">Configure classroom section partitions, academic batch years, and multi-department sharing.</p>
        </div>
        <div>
            <a href="{{ route('institution.section.manage') }}" class="btn btn-light border">
                <i class="fa fa-arrow-left me-1.5 opacity-75"></i> Back to Sections
            </a>
        </div>
    </div>
</div>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card aw-form-card shadow-sm border-0">
        <div class="card-body">

            <form action="{{ $action }}" method="POST" id="mainForm">
                @csrf
                @if(isset($type) && ($type === 'edit' || $type === 'EDIT'))
                    @method('PUT')
                @endif

                <div class="row g-4">
                    {{-- Section Details & Cohort --}}
                    <div class="col-md-12">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-users-cog"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">Section Details & Cohort Setup</h3>
                                <p class="aw-form-section-subtitle">Label, academic session, capacity limits, and course mapping</p>
                            </div>
                        </div>
                    </div>

                    {{-- Section Name --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">
                                Section Label / Name <span class="aw-field-required">*</span>
                            </label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g. Section A"
                                value="{{ old('name', $section->name ?? '') }}">
                        </div>
                    </div>

                    {{-- Academic Year --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">
                                Academic Year <span class="aw-field-required">*</span>
                            </label>
                            <input type="text" name="academic_year" class="form-control" required placeholder="e.g. 2024-2025"
                                value="{{ old('academic_year', $section->academic_year ?? '') }}">
                        </div>
                    </div>

                    {{-- Capacity --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">
                                Student Capacity <span class="aw-field-required">*</span>
                            </label>
                            <input type="text" name="capacity" class="form-control" required placeholder="e.g. 60"
                                value="{{ old('capacity', $section->capacity ?? '') }}">
                        </div>
                    </div>

                    {{-- Department --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">
                                Primary Department <span class="aw-field-required">*</span>
                            </label>
                            <select name="department_id" class="form-select form-control" required>
                                <option value="">Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id', $section->department_id ?? '') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Course --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">
                                Associated Course <span class="aw-field-required">*</span>
                            </label>
                            <select name="course_id" class="form-select form-control" required>
                                <option value="">Select Course</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id', $section->course_id ?? '') == $course->id ? 'selected' : '' }}>
                                        {{ $course->name }}
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
                                <option value="1" {{ old('status', $section->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', $section->status ?? '') == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    {{-- Inter-Departmental Sharing Section --}}
                    <div class="col-md-12 mt-4">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-network-wired"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">Inter-Departmental Allocation</h3>
                                <p class="aw-form-section-subtitle">Multi-department assignment for shared or elective lectures</p>
                            </div>
                        </div>
                    </div>

                    {{-- Additional Departments --}}
                    <div class="col-md-12">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">
                                Shared / Additional Departments
                            </label>
                            <select name="additional_departments[]" class="form-select form-control msc-searchable" multiple>
                                @foreach ($additional_departments as $dept)
                                    <option value="{{ $dept->id }}" {{ in_array($dept->id, old('additional_departments', $selected_additional_departments ?? [])) ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="aw-field-help">Select auxiliary departments that share this section's schedule.</span>
                        </div>
                    </div>

                    {{-- Section Working Day Override --}}
                    <div class="col-md-12 mt-4">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-calendar-week"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">Timetable Working Day Window</h3>
                                <p class="aw-form-section-subtitle">
                                    Override which days this section's timetable spans.
                                    Overrides institution defaults during auto-generation.
                                    Leave as Mon&ndash;Fri if no custom schedule is required.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Scheduled Working Days</label>
                            @php
                                $sectionWorkingDays = old('working_days',
                                    $section->working_days ?? ['Monday','Tuesday','Wednesday','Thursday','Friday']
                                );
                            @endphp
                            <div class="d-flex flex-wrap gap-3 mt-1">
                                @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                                <div class="form-check p-0">
                                    <input class="btn-check" type="checkbox"
                                           name="working_days[]" value="{{ $day }}"
                                           id="swd_{{ $day }}"
                                           {{ in_array($day, (array)$sectionWorkingDays) ? 'checked' : '' }}>
                                    <label class="btn btn-sm rounded-pill px-3 py-2
                                                  {{ in_array($day, ['Saturday','Sunday']) ? 'btn-outline-warning' : 'btn-outline-primary' }}"
                                           for="swd_{{ $day }}">
                                        {{ substr($day, 0, 3) }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            <span class="aw-field-help">
                                <i class="fas fa-info-circle me-1 opacity-75"></i>
                                Saturday/Sunday classes will only be auto-generated for this section if those days are checked here.
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