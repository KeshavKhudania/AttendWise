<x-structure />
<x-header heading="{{ $title }}" />

<div class="aw-page-header mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-heading mb-1">{{ $title }}</h1>
            <p class="text-muted small mb-0">Configure timetable slot details including section, subject, faculty, classroom, and timing.</p>
        </div>
        <div>
            <a href="{{ route('institution.time.table.manage') }}" class="btn btn-light border">
                <i class="fa fa-arrow-left me-1.5 opacity-75"></i> Back to Timetable
            </a>
        </div>
    </div>
</div>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card aw-form-card shadow-sm border-0">
        <div class="card-body">

            <form action="{{ $action }}" method="POST" id="mainForm" data-form-type="{{ $type }}">
                @csrf
                @if(isset($type) && ($type === 'edit' || $type === 'EDIT'))
                    @method('PUT')
                @endif

                <div class="row g-4">
                    {{-- Section & Target Assignment --}}
                    <div class="col-md-12">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">Slot Identification & Assignment</h3>
                                <p class="aw-form-section-subtitle">Select section, group (if applicable), and day of the week</p>
                            </div>
                        </div>
                    </div>

                    {{-- Section --}}
                    <div class="col-md-6">
                        <div class="aw-field-group">
                            <label for="section_id" class="form-label aw-field-label">
                                Academic Section <span class="aw-field-required">*</span>
                            </label>
                            <select name="section_id" id="section_id" class="form-control form-select" required>
                                <option value="">Select Section</option>
                                @foreach($sections as $sec)
                                    <option value="{{ $sec->id }}" {{ old('section_id', $schedule->section_id ?? '') == $sec->id ? 'selected' : '' }}>
                                        {{ $sec->name }} ({{ $sec->course->name ?? 'Course N/A' }} - Sem {{ $sec->semester }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Class Group --}}
                    <div class="col-md-3">
                        <div class="aw-field-group">
                            <label for="class_group_id" class="form-label aw-field-label">Class Group / Batch</label>
                            <select name="class_group_id" id="class_group_id" class="form-control form-select">
                                <option value="">All Groups / Entire Class</option>
                                @foreach($classGroups as $grp)
                                    <option value="{{ $grp->id }}" {{ old('class_group_id', $schedule->class_group_id ?? '') == $grp->id ? 'selected' : '' }}>
                                        {{ $grp->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Day of Week --}}
                    <div class="col-md-3">
                        <div class="aw-field-group">
                            <label for="day_of_week" class="form-label aw-field-label">
                                Day of Week <span class="aw-field-required">*</span>
                            </label>
                            <select name="day_of_week" id="day_of_week" class="form-control form-select" required>
                                <option value="">Select Day</option>
                                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                    <option value="{{ $day }}" {{ old('day_of_week', $schedule->day_of_week ?? '') == $day ? 'selected' : '' }}>
                                        {{ $day }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Course & Resource Assignment --}}
                    <div class="col-md-12 mt-4">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">Subject, Faculty & Location</h3>
                                <p class="aw-form-section-subtitle">Configure subject taught, instructor, and classroom allocation</p>
                            </div>
                        </div>
                    </div>

                    {{-- Subject --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label for="subject_id" class="form-label aw-field-label">
                                Subject <span class="aw-field-required">*</span>
                            </label>
                            <select name="subject_id" id="subject_id" class="form-control form-select" required>
                                <option value="">Select Subject</option>
                                @foreach($subjects as $sub)
                                    <option value="{{ $sub->id }}" {{ old('subject_id', $schedule->subject_id ?? '') == $sub->id ? 'selected' : '' }}>
                                        {{ $sub->name }} ({{ $sub->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Faculty --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label for="faculty_id" class="form-label aw-field-label">
                                Faculty Instructor <span class="aw-field-required">*</span>
                            </label>
                            <select name="faculty_id" id="faculty_id" class="form-control form-select" required>
                                <option value="">Select Faculty</option>
                                @foreach($faculties as $fac)
                                    <option value="{{ $fac->id }}" {{ old('faculty_id', $schedule->faculty_id ?? '') == $fac->id ? 'selected' : '' }}>
                                        {{ $fac->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Classroom --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label for="classroom_id" class="form-label aw-field-label">
                                Classroom / Venue <span class="aw-field-required">*</span>
                            </label>
                            <select name="classroom_id" id="classroom_id" class="form-control form-select" required>
                                <option value="">Select Classroom</option>
                                @foreach($classrooms as $room)
                                    <option value="{{ $room->id }}" {{ old('classroom_id', $schedule->classroom_id ?? '') == $room->id ? 'selected' : '' }}>
                                        {{ $room->name ?? $room->room_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Timing & Session Details --}}
                    <div class="col-md-12 mt-4">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">Timing & Session Parameters</h3>
                                <p class="aw-form-section-subtitle">Set lecture start time, end time, and delivery format</p>
                            </div>
                        </div>
                    </div>

                    {{-- Start Time --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label for="start_time" class="form-label aw-field-label">
                                Start Time <span class="aw-field-required">*</span>
                            </label>
                            <input type="time" name="start_time" id="start_time" class="form-control" required
                                value="{{ old('start_time', $schedule->start_time ?? '') }}">
                        </div>
                    </div>

                    {{-- End Time --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label for="end_time" class="form-label aw-field-label">
                                End Time <span class="aw-field-required">*</span>
                            </label>
                            <input type="time" name="end_time" id="end_time" class="form-control" required
                                value="{{ old('end_time', $schedule->end_time ?? '') }}">
                        </div>
                    </div>

                    {{-- Lecture Type --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label for="lecture_type" class="form-label aw-field-label">
                                Session Format / Type <span class="aw-field-required">*</span>
                            </label>
                            <select name="lecture_type" id="lecture_type" class="form-control form-select" required>
                                <option value="Theory" {{ old('lecture_type', $schedule->lecture_type ?? '') == 'Theory' ? 'selected' : '' }}>Theory</option>
                                <option value="Lab" {{ old('lecture_type', $schedule->lecture_type ?? '') == 'Lab' ? 'selected' : '' }}>Lab / Practical</option>
                                <option value="Tutorial" {{ old('lecture_type', $schedule->lecture_type ?? '') == 'Tutorial' ? 'selected' : '' }}>Tutorial</option>
                                <option value="Seminar" {{ old('lecture_type', $schedule->lecture_type ?? '') == 'Seminar' ? 'selected' : '' }}>Seminar</option>
                            </select>
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="col-md-12 mt-4 pt-3 border-top">
                        <x-form-buttons />
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

<x-footer />
