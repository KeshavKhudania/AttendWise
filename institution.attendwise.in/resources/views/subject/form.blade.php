<x-structure />
<x-header heading="{{ $title }}" />

<div class="aw-page-header mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-heading mb-1">{{ $title }}</h1>
            <p class="text-muted small mb-0">Specify subject properties, credit points, weekly lecture count, and curriculum mapping.</p>
        </div>
        <div>
            <a href="{{ route('institution.subject.manage') }}" class="btn btn-light border">
                <i class="fa fa-arrow-left me-1.5 opacity-75"></i> Back to Subjects
            </a>
        </div>
    </div>
</div>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card aw-form-card shadow-sm border-0">
        <div class="card-body">

            <form action="{{ $action }}" method="POST" class="msc-ord-form">
                @csrf
                @if(isset($type) && ($type === 'edit' || $type === 'EDIT'))
                    @method('PUT')
                @endif

                <div class="row g-4">
                    {{-- Subject Identity & Type --}}
                    <div class="col-md-12">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-book-reader"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">Subject Identity & Delivery Format</h3>
                                <p class="aw-form-section-subtitle">Name, code, pedagogical type, and classroom requirements</p>
                            </div>
                        </div>
                    </div>

                    {{-- Subject Name --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">
                                Subject Name <span class="aw-field-required">*</span>
                            </label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g. Data Structures & Algorithms"
                                value="{{ old('name', $subject->name ?? '') }}">
                        </div>
                    </div>

                    {{-- Subject Code --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Subject Code</label>
                            <input type="text" name="code" class="form-control" placeholder="e.g. CS301"
                                value="{{ old('code', $subject->code ?? '') }}">
                        </div>
                    </div>

                    {{-- Type --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">
                                Delivery Mode <span class="aw-field-required">*</span>
                            </label>
                            <select name="type" class="form-select form-control" required>
                                <option value="">Select Type</option>
                                <option value="theory" {{ old('type', $subject->type ?? '') == 'theory' ? 'selected' : '' }}>Theory</option>
                                <option value="practical" {{ old('type', $subject->type ?? '') == 'practical' ? 'selected' : '' }}>Practical</option>
                                <option value="lab" {{ old('type', $subject->type ?? '') == 'lab' ? 'selected' : '' }}>Lab</option>
                            </select>
                        </div>
                    </div>

                    {{-- Classroom Type --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">
                                Classroom Requirement <span class="aw-field-required">*</span>
                            </label>
                            <select name="classroom_type" class="form-select form-control" required>
                                <option value="">Select Classroom Type</option>
                                @foreach ($classroom_types as $item)
                                    <option value="{{ $item->id }}" {{ old('classroom_type', $subject->classroom_type ?? '') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Credits --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Credit Value</label>
                            <input type="number" name="credits" class="form-control" min="0" max="10" placeholder="e.g. 4"
                                value="{{ old('credits', $subject->credits ?? '') }}">
                        </div>
                    </div>

                    {{-- Weekly Lectures --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Weekly Hours / Lectures</label>
                            <input type="number" name="weekly_lectures" class="form-control" min="0" max="20" placeholder="e.g. 4"
                                value="{{ old('weekly_lectures', $subject->weekly_lectures ?? '') }}">
                        </div>
                    </div>

                    {{-- Daily Lecture Constraints --}}
                    <div class="col-md-12 mt-4">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">Timetable Daily Limits</h3>
                                <p class="aw-form-section-subtitle">Define how this subject should be distributed across days during auto-generation</p>
                            </div>
                        </div>
                    </div>

                    {{-- Max Lectures per Day --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Max Lectures Per Day</label>
                            <input type="number" name="max_lectures_per_day" class="form-control" min="1" max="10" placeholder="e.g. 2"
                                value="{{ old('max_lectures_per_day', $subject->max_lectures_per_day ?? '') }}">
                            <span class="aw-field-help">Leave blank to use institution defaults</span>
                        </div>
                    </div>

                    {{-- Min Lectures per Day --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Min Lectures Per Day</label>
                            <input type="number" name="min_lectures_per_day" class="form-control" min="1" max="10" placeholder="e.g. 1"
                                value="{{ old('min_lectures_per_day', $subject->min_lectures_per_day ?? 1) }}">
                        </div>
                    </div>

                    {{-- Continuous Lectures --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Placement Pattern</label>
                            <div class="form-check form-switch mt-2">
                                <input type="hidden" name="continuous_lectures" value="0">
                                <input class="form-check-input" type="checkbox" name="continuous_lectures" value="1" id="continuous_lectures"
                                    {{ old('continuous_lectures', $subject->continuous_lectures ?? 1) ? 'checked' : '' }}>
                                <label class="form-check-label ms-2" for="continuous_lectures">Keep Continuous (Back-to-back)</label>
                            </div>
                            <span class="aw-field-help mt-2 d-block">If checked, multiple daily lectures will be placed contiguously</span>
                        </div>
                    </div>

                    {{-- Academic Curriculum Allocation --}}
                    <div class="col-md-12 mt-4">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">Academic & Department Mapping</h3>
                                <p class="aw-form-section-subtitle">Department ownership, course linkage, and elective status</p>
                            </div>
                        </div>
                    </div>

                    {{-- Primary Department --}}
                    <div class="col-md-6">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">
                                Primary Department <span class="aw-field-required">*</span>
                            </label>
                            <select name="department_id" class="form-select form-control" required>
                                <option value="">Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id', $subject->department_id ?? '') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Additional Department --}}
                    <div class="col-md-6">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Additional Department</label>
                            <select name="additional_department_id" class="form-select form-control">
                                <option value="">None (Standalone)</option>
                                @foreach ($additional_departments as $department)
                                    <option value="{{ $department->id }}" {{ old('additional_department_id', $subject->additional_department_id ?? '') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Course --}}
                    <div class="col-md-5">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Associated Course</label>
                            <select name="course_id" class="form-select form-control">
                                <option value="">Select Course</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id', $subject->course_id ?? '') == $course->id ? 'selected' : '' }}>
                                        {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Semester --}}
                    <div class="col-md-3">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Target Semester</label>
                            <input type="number" name="semester" class="form-control" min="1" max="10" placeholder="e.g. 3"
                                value="{{ old('semester', $subject->semester ?? '') }}">
                        </div>
                    </div>

                    {{-- Elective --}}
                    <div class="col-md-2">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Elective Subject</label>
                            <select name="is_elective" class="form-select form-control">
                                <option value="0" {{ old('is_elective', $subject->is_elective ?? 0) == 0 ? 'selected' : '' }}>No</option>
                                <option value="1" {{ old('is_elective', $subject->is_elective ?? '') == 1 ? 'selected' : '' }}>Yes</option>
                            </select>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-2">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">
                                Status <span class="aw-field-required">*</span>
                            </label>
                            <select name="status" class="form-select form-control" required>
                                <option value="1" {{ old('status', $subject->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', $subject->status ?? '') == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
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