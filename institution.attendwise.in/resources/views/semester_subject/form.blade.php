<x-structure />
<x-header heading="{{ $title }}" />

<div class="aw-page-header mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-heading mb-1">{{ $title }}</h1>
            <p class="text-muted small mb-0">Map subjects to specific department, course, and semester academic cycles.</p>
        </div>
        <div>
            <a href="{{ route('institution.semester_subject.manage') }}" class="btn btn-light border">
                <i class="fa fa-arrow-left me-1.5 opacity-75"></i> Back to Mapping
            </a>
        </div>
    </div>
</div>

<div class="col-lg-12 grid-margin stretch-card">
  <div class="card aw-form-card shadow-sm border-0">
    <div class="card-body">

      <form action="{{ $action }}" method="POST" class="msc-ord-form" id="mainForm">
        @csrf
        @if(isset($type) && ($type === 'edit' || $type === 'EDIT'))
            @method('PUT')
        @endif

        <div class="row g-4">
          <div class="col-md-12">
             <div class="aw-form-section-header">
                <div class="aw-form-section-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div>
                    <h3 class="aw-form-section-title">Academic Semester & Subject Mapping</h3>
                    <p class="aw-form-section-subtitle">Assign one or more subjects to an academic department and semester</p>
                </div>
             </div>
          </div>

          {{-- Department --}}
          <div class="col-md-4">
            <div class="aw-field-group">
                <label class="form-label aw-field-label">
                  Department <span class="aw-field-required">*</span>
                </label>
                <select name="department_id" class="form-select form-control msc-searchable" required>
                  <option value="">Select Department</option>
                  @foreach ($departments as $dept)
                  <option value="{{ $dept->id }}" {{ old('department_id', $mapping->department_id ?? '') == $dept->id ? 'selected' : '' }}>
                    {{ $dept->name }}{{ $dept->is_additional ? ' (Additional)' : '' }}
                  </option>
                  @endforeach
                </select>
            </div>
          </div>

          {{-- Course --}}
          <div class="col-md-4">
            <div class="aw-field-group">
                <label class="form-label aw-field-label">
                  Course <span class="aw-field-required">*</span>
                </label>
                <select name="course_id" class="form-select form-control msc-searchable" required>
                  <option value="">Select Course</option>
                  @foreach ($courses as $course)
                  <option value="{{ $course->id }}" {{ old('course_id', $mapping->course_id ?? '') == $course->id ? 'selected' : '' }}>
                    {{ $course->name }}
                  </option>
                  @endforeach
                </select>
            </div>
          </div>

          {{-- Semester --}}
          <div class="col-md-4">
            <div class="aw-field-group">
                <label class="form-label aw-field-label">
                  Semester <span class="aw-field-required">*</span>
                </label>
                <input type="number" name="semester" class="form-control" min="1" placeholder="e.g. 1" required
                  value="{{ old('semester', $mapping->semester ?? '') }}">
            </div>
          </div>

          {{-- Subjects --}}
          <div class="col-md-12">
            <div class="aw-field-group">
                <label class="form-label aw-field-label">
                  Assigned Subjects <span class="aw-field-required">*</span>
                </label>
                <select name="subjects[]" class="form-select form-control msc-searchable" multiple required data-placeholder="Choose subjects for this semester...">
                  @foreach ($subjects as $subject)
                  <option value="{{ $subject->id }}" {{ in_array( $subject->id, old('subjects', isset($mapping) ? (array)$mapping->subjects : [])) ? 'selected' : '' }}>
                    {{ $subject->name }} ({{ $subject->code }})
                  </option>
                  @endforeach
                </select>
                <span class="aw-field-help">Hold Ctrl (Cmd on Mac) to select multiple subjects for this term.</span>
            </div>
          </div>

          {{-- Buttons --}}
          <div class="col-md-12 mt-3 pt-3 border-top">
            <x-form-buttons />
          </div>

        </div>
      </form>

    </div>
  </div>
</div>

<x-footer />