<x-structure />
<x-header heading="{{ $title }}" />

<div class="aw-page-header mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-heading mb-1">{{ $title }}</h1>
            <p class="text-muted small mb-0">Define academic course structure, level, duration, and department association.</p>
        </div>
        <div>
            <a href="{{ route('institution.courses.manage') }}" class="btn btn-light border">
                <i class="fa fa-arrow-left me-1.5 opacity-75"></i> Back to Courses
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
                    {{-- Course Specification Section --}}
                    <div class="col-md-12">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">Course Identification & Department</h3>
                                <p class="aw-form-section-subtitle">Basic details, course code, level, and host department</p>
                            </div>
                        </div>
                    </div>

                    {{-- Course Name --}}
                    <div class="col-md-6">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">
                                Course Name <span class="aw-field-required">*</span>
                            </label>
                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                required
                                placeholder="e.g. Bachelor of Computer Science"
                                value="{{ old('name', $course->name ?? '') }}"
                            >
                        </div>
                    </div>

                    {{-- Course Code --}}
                    <div class="col-md-3">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Course Code</label>
                            <input
                                type="text"
                                name="code"
                                class="form-control"
                                placeholder="e.g. BCS"
                                value="{{ old('code', $course->code ?? '') }}"
                            >
                        </div>
                    </div>

                    {{-- Level --}}
                    <div class="col-md-3">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Academic Level <span class="aw-field-required">*</span></label>
                            <select name="level" class="form-select form-control" required>
                                <option value="">Select Level</option>
                                <option value="Undergraduate" {{ old('level', $course->level ?? '') == 'Undergraduate' ? 'selected' : '' }}>UG (Undergraduate)</option>
                                <option value="Postgraduate" {{ old('level', $course->level ?? '') == 'Postgraduate' ? 'selected' : '' }}>PG (Postgraduate)</option>
                                <option value="Diploma" {{ old('level', $course->level ?? '') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                <option value="Certificate" {{ old('level', $course->level ?? '') == 'Certificate' ? 'selected' : '' }}>Certificate</option>
                                <option value="PhD" {{ old('level', $course->level ?? '') == 'PhD' ? 'selected' : '' }}>PhD</option>
                            </select>
                        </div>
                    </div>

                    {{-- Department --}}
                    <div class="col-md-8">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Associated Department <span class="aw-field-required">*</span></label>
                            <select name="department_id" class="form-select form-control" required>
                                <option value="">Select Department</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}"
                                        {{ old('department_id', $course->department_id ?? '') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Course Status <span class="aw-field-required">*</span></label>
                            <select name="status" class="form-select form-control" required>
                                <option value="1" {{ old('status', $course->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', $course->status ?? '') == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    {{-- Academic Duration & Description Section --}}
                    <div class="col-md-12 mt-4">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">Academic Duration & Description</h3>
                                <p class="aw-form-section-subtitle">Semester timeline configuration and course summary</p>
                            </div>
                        </div>
                    </div>

                    {{-- Duration Years --}}
                    <div class="col-md-6">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Duration (Years) <span class="aw-field-required">*</span></label>
                            <input
                                type="number"
                                name="duration_years"
                                class="form-control"
                                min="1"
                                max="10"
                                required
                                placeholder="e.g. 4"
                                value="{{ old('duration_years', $course->duration_years ?? '') }}"
                            >
                        </div>
                    </div>

                    {{-- Total Semesters --}}
                    <div class="col-md-6">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Total Semesters <span class="aw-field-required">*</span></label>
                            <input
                                type="number"
                                name="total_semesters"
                                class="form-control"
                                min="1"
                                required
                                placeholder="e.g. 8"
                                value="{{ old('total_semesters', $course->total_semesters ?? '') }}"
                            >
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="col-md-12">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Course Overview / Description</label>
                            <textarea
                                name="description"
                                class="form-control"
                                rows="3"
                                placeholder="Write a brief overview of learning objectives, prerequisites, and scope..."
                            >{{ old('description', $course->description ?? '') }}</textarea>
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

