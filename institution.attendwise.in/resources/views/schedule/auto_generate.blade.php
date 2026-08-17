<x-structure />
<x-header heading="{{ $title }}" />

<div class="aw-page-header mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-heading mb-1">Auto-Generate Timetable</h1>
            <p class="text-muted small mb-0">Configure scope, working days, and generation settings before creating the timetable.</p>
        </div>
        <div>
            <a href="{{ route('institution.time.table.manage') }}" class="btn btn-light border">
                <i class="fa fa-arrow-left me-1"></i> Back to Timetable
            </a>
        </div>
    </div>
</div>

<form action="{{ route('institution.time.table.manage.auto_generate') }}" method="POST" id="autoGenForm">
    @csrf

    <div class="row g-4">

        {{-- ─── Card 1: Scope ─────────────────────────────────────────────────── --}}
        <div class="col-lg-12">
            <div class="card aw-form-card shadow-sm border-0">
                <div class="card-body">

                    <div class="aw-form-section-header mb-4">
                        <div class="aw-form-section-icon"><i class="fas fa-crosshairs"></i></div>
                        <div>
                            <h3 class="aw-form-section-title">Step 1 — Generation Scope</h3>
                            <p class="aw-form-section-subtitle">Choose which sections will have their timetable generated or regenerated.</p>
                        </div>
                    </div>

                    {{-- Scope type radio pills --}}
                    <div class="d-flex flex-wrap gap-2 mb-4" id="scopeTypeGroup">
                        @foreach([
                            ['all',             'fas fa-globe',         'Entire Institution'],
                            ['department',      'fas fa-sitemap',       'By Department'],
                            ['course',          'fas fa-book',          'By Course'],
                            ['course_semester', 'fas fa-layer-group',   'Course + Semester'],
                            ['section',         'fas fa-users',         'Single Section'],
                        ] as [$val, $icon, $label])
                        <div>
                            <input type="radio" name="scope_type" id="scope_{{ $val }}"
                                   value="{{ $val }}" class="btn-check"
                                   {{ $val === 'all' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary rounded-pill px-4 py-2" for="scope_{{ $val }}">
                                <i class="{{ $icon }} me-2"></i>{{ $label }}
                            </label>
                        </div>
                        @endforeach
                    </div>

                    {{-- Scope-specific selects (conditionally shown) --}}
                    <div class="row g-3">

                        <div id="wrap_dept" class="col-md-6 d-none">
                            <div class="aw-field-group">
                                <label class="form-label aw-field-label">Department <span class="aw-field-required">*</span></label>
                                <select name="scope_department" id="scope_department" class="form-control form-select">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="wrap_course" class="col-md-6 d-none">
                            <div class="aw-field-group">
                                <label class="form-label aw-field-label">Course <span class="aw-field-required">*</span></label>
                                <select name="scope_course" id="scope_course" class="form-control form-select">
                                    <option value="">Select Course</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="wrap_semester" class="col-md-4 d-none">
                            <div class="aw-field-group">
                                <label class="form-label aw-field-label">Semester <span class="aw-field-required">*</span></label>
                                <select name="scope_semester" id="scope_semester" class="form-control form-select">
                                    <option value="">Select Semester</option>
                                    @foreach($semesters as $sem)
                                        <option value="{{ $sem }}">Semester {{ $sem }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="wrap_section" class="col-md-6 d-none">
                            <div class="aw-field-group">
                                <label class="form-label aw-field-label">Section <span class="aw-field-required">*</span></label>
                                <select name="scope_section" id="scope_section" class="form-control form-select">
                                    <option value="">Select Section</option>
                                    @foreach($sections as $sec)
                                        <option value="{{ $sec->id }}">
                                            {{ $sec->name }}
                                            @if($sec->course) · {{ $sec->course->name }} @endif
                                            @if($sec->semester) · Sem {{ $sec->semester }} @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- ─── Card 2: Working Days ──────────────────────────────────────────── --}}
        <div class="col-lg-7">
            <div class="card aw-form-card shadow-sm border-0 h-100">
                <div class="card-body">

                    <div class="aw-form-section-header mb-4">
                        <div class="aw-form-section-icon"><i class="fas fa-calendar-week"></i></div>
                        <div>
                            <h3 class="aw-form-section-title">Step 2 — Working Day Window</h3>
                            <p class="aw-form-section-subtitle">
                                Override the working days for this generation run.
                                Overrides section-level and institution-level defaults.
                            </p>
                        </div>
                    </div>

                    @php
                        $institutionDays = $settings?->working_days ?? ['Monday','Tuesday','Wednesday','Thursday','Friday'];
                    @endphp

                    <div class="d-flex flex-wrap gap-3 mb-3">
                        @foreach($allDays as $day)
                        <div class="form-check p-0">
                            <input type="checkbox" name="working_days[]"
                                   value="{{ $day }}"
                                   id="wd_{{ $day }}"
                                   class="btn-check wd-checkbox"
                                   {{ in_array($day, $institutionDays) ? 'checked' : '' }}>
                            <label class="btn btn-sm rounded-pill px-3 py-2 fw-600
                                          {{ in_array($day, ['Saturday','Sunday']) ? 'btn-outline-warning' : 'btn-outline-primary' }}"
                                   for="wd_{{ $day }}">
                                {{ substr($day, 0, 3) }}
                            </label>
                        </div>
                        @endforeach
                    </div>

                    <div class="alert alert-info border-0 rounded-3 small py-2 px-3">
                        <i class="fas fa-info-circle me-1"></i>
                        <strong>Faculty Availability:</strong> Faculty members whose personal working-day contract
                        does not include a selected day will <em>never</em> be assigned on that day.
                        The system will flag any unresolvable subjects as <strong>shortages</strong>.
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill me-2" id="selWeekdays">
                            Mon–Fri
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill me-2" id="selWeekend">
                            Wed–Sun
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" id="selAll">
                            All 7 Days
                        </button>
                    </div>

                </div>
            </div>
        </div>

        {{-- ─── Card 3: Advanced Options ──────────────────────────────────────── --}}
        <div class="col-lg-5">
            <div class="card aw-form-card shadow-sm border-0 h-100">
                <div class="card-body">

                    <div class="aw-form-section-header mb-4">
                        <div class="aw-form-section-icon"><i class="fas fa-sliders-h"></i></div>
                        <div>
                            <h3 class="aw-form-section-title">Step 3 — Advanced Options</h3>
                            <p class="aw-form-section-subtitle">Additional controls for this generation run.</p>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-3">

                        <div class="p-3 rounded-3 bg-light border d-flex gap-3 align-items-start">
                            <div class="form-check form-switch mb-0 mt-1">
                                <input class="form-check-input" type="checkbox"
                                       name="clear_existing" id="clearExisting"
                                       value="1" checked>
                            </div>
                            <div>
                                <label class="form-check-label fw-600 cursor-pointer" for="clearExisting">
                                    Clear Existing Schedules
                                </label>
                                <p class="text-muted small mb-0 mt-1">
                                    Deletes existing (non-temporary) slots within the selected scope before inserting new ones.
                                    Unselected scopes are untouched.
                                </p>
                            </div>
                        </div>

                        <div class="p-3 rounded-3 bg-light border d-flex gap-3 align-items-start">
                            <div class="form-check form-switch mb-0 mt-1">
                                <input class="form-check-input" type="checkbox"
                                       name="utilize_limit" id="utilizeLimit"
                                       value="1">
                            </div>
                            <div>
                                <label class="form-check-label fw-600 cursor-pointer" for="utilizeLimit">
                                    Utilize Till Lecture Limit
                                </label>
                                <p class="text-muted small mb-0 mt-1">
                                    Attempt to schedule each faculty member up to their maximum daily lecture limit.
                                </p>
                            </div>
                        </div>

                        <div class="p-3 rounded-3 bg-light border d-flex gap-3 align-items-start">
                            <div class="form-check form-switch mb-0 mt-1">
                                <input class="form-check-input" type="checkbox"
                                       name="free_lectures" id="freeLectures"
                                       value="1">
                            </div>
                            <div>
                                <label class="form-check-label fw-600 cursor-pointer" for="freeLectures">
                                    Give Free Lectures Between
                                </label>
                                <p class="text-muted small mb-0 mt-1">
                                    Ensure that faculty get free periods between consecutive lectures.
                                </p>
                            </div>
                        </div>

                        <div class="p-3 rounded-3 bg-light border">
                            <div class="d-flex justify-content-between small text-muted mb-2">
                                <span><i class="fas fa-info-circle me-1"></i> Institution Defaults</span>
                            </div>
                            <div class="row g-2 small">
                                <div class="col-6">
                                    <span class="text-muted">Faculty lecture limit:</span>
                                    <strong class="ms-1">{{ $settings?->faculty_lecture_limit ?? 6 }}/day</strong>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted">Theory slots:</span>
                                    <strong class="ms-1">{{ $settings?->theory_slot_limit ?? 1 }}</strong>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted">Lab slots:</span>
                                    <strong class="ms-1">{{ $settings?->lab_slot_limit ?? 2 }}</strong>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted">Slot timings:</span>
                                    <strong class="ms-1">{{ count($settings?->slot_timings ?? []) ?: 7 }} slots/day</strong>
                                </div>
                            </div>
                            <a href="{{ route('institution.settings.manage') }}" class="btn btn-link btn-sm p-0 mt-2 text-decoration-none small">
                                <i class="fas fa-cog me-1"></i> Change in Settings
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- ─── Submit ─────────────────────────────────────────────────────────── --}}
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #1e3a5f 0%, #0d6efd 100%);">
                <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-3 py-4 px-4">
                    <div class="text-white">
                        <h5 class="fw-bold mb-1"><i class="fas fa-magic me-2"></i>Ready to Generate</h5>
                        <p class="mb-0 opacity-75 small">
                            The engine will allocate subjects across the selected working days,
                            respecting faculty availability and daily lecture limits.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('institution.time.table.manage') }}" class="btn btn-outline-light rounded-pill px-4">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-light text-primary fw-bold rounded-pill px-5" id="generateBtn">
                            <i class="fas fa-bolt me-2"></i> Generate Timetable
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- /row --}}
</form>

<x-footer />

<script>
(function () {
    const ALL_DAYS = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];

    // ── Scope toggle ─────────────────────────────────────────────────────────
    const wrapMap = {
        department:      ['wrap_dept'],
        course:          ['wrap_course'],
        course_semester: ['wrap_course', 'wrap_semester'],
        section:         ['wrap_section'],
        all:             [],
    };

    document.querySelectorAll('input[name="scope_type"]').forEach(radio => {
        radio.addEventListener('change', function () {
            // Hide all
            ['wrap_dept','wrap_course','wrap_semester','wrap_section'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.classList.add('d-none');
            });
            // Show relevant
            (wrapMap[this.value] || []).forEach(id => {
                const el = document.getElementById(id);
                if (el) el.classList.remove('d-none');
            });
        });
    });

    // ── Working day presets ───────────────────────────────────────────────────
    function setDays(days) {
        document.querySelectorAll('.wd-checkbox').forEach(cb => {
            cb.checked = days.includes(cb.value);
        });
    }

    document.getElementById('selWeekdays').addEventListener('click', () =>
        setDays(['Monday','Tuesday','Wednesday','Thursday','Friday']));

    document.getElementById('selWeekend').addEventListener('click', () =>
        setDays(['Wednesday','Thursday','Friday','Saturday','Sunday']));

    document.getElementById('selAll').addEventListener('click', () =>
        setDays(ALL_DAYS));

    // ── Submit guard ──────────────────────────────────────────────────────────
    document.getElementById('autoGenForm').addEventListener('submit', function (e) {
        const btn = document.getElementById('generateBtn');
        const days = document.querySelectorAll('.wd-checkbox:checked');
        if (days.length === 0) {
            e.preventDefault();
            alert('Please select at least one working day before generating.');
            return;
        }
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Generating…';
    });
})();
</script>
