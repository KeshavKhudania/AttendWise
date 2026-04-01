<x-structure />
<x-header heading="{{ $title }}" />

<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">

      <form action="{{ $action }}" method="POST" id="mainForm">
        @csrf
        @if($type === 'edit')
        @method('PUT')
        @endif

        <div class="row g-3">

          {{-- Section Name --}}
          <div class="col-md-4">
            <label class="form-label">
              Section Name <span class="text-danger">*</span>
            </label>
            <input type="text" name="name" class="form-control" required placeholder="e.g. A / B / C"
              value="{{ old('name', $section->name ?? '') }}">
          </div>

          {{-- Academic Year --}}
          <div class="col-md-4">
            <label class="form-label">
              Academic Year <span class="text-danger">*</span>
            </label>
            <input type="text" name="academic_year" class="form-control" required placeholder="e.g. 2024-2025"
              value="{{ old('academic_year', $section->academic_year ?? '') }}">
          </div>
          {{-- Capacity --}}
          <div class="col-md-4">
            <label class="form-label">
              Capacity <span class="text-danger">*</span>
            </label>
            <input type="text" name="capacity" class="form-control" required placeholder="60"
              value="{{ old('capacity', $section->capacity ?? '') }}">
          </div>

          {{-- Department --}}
          <div class="col-md-4">
            <label class="form-label">
              Department <span class="text-danger">*</span>
            </label>
            <select name="department_id" class="form-select form-control" required>
              <option value="">Select Department</option>
              @foreach ($departments as $department)
              <option value="{{ $department->id }}" {{ old('department_id', $section->department_id ?? '') ==
                $department->id ? 'selected' : '' }}>
                {{ $department->name }}
              </option>
              @endforeach
            </select>
          </div>
          {{-- Course --}}
          <div class="col-md-4">
            <label class="form-label">
              Course <span class="text-danger">*</span>
            </label>
            <select name="course_id" class="form-select form-control" required>
              <option value="">Select Course</option>
              @foreach ($courses as $course)
              <option value="{{ $course->id }}" {{ old('course_id', $section->course_id ?? '') == $course->id ?
                'selected' : '' }}>
                {{ $course->name }}
              </option>
              @endforeach
            </select>
          </div>

          {{-- Status --}}
          <div class="col-md-4">
            <label class="form-label">
              Status <span class="text-danger">*</span>
            </label>
            <select name="status" class="form-select form-control" required>
              <option value="1" {{ old('status', $section->status ?? 1) == 1 ? 'selected' : '' }}>
                Active
              </option>
              <option value="0" {{ old('status', $section->status ?? '') == 0 ? 'selected' : '' }}>
                Inactive
              </option>
            </select>
          </div>

          {{-- Additional Departments --}}
          <div class="col-md-12">
            <label class="form-label">
              Additional Departments (Multiple Selection)
            </label>
            <select name="additional_departments[]" class="form-select form-control msc-searchable" multiple>
              @foreach ($additional_departments as $dept)
              <option value="{{ $dept->id }}" {{ in_array($dept->id, old('additional_departments',
                $selected_additional_departments ?? [])) ? 'selected' : '' }}>
                {{ $dept->name }}
              </option>
              @endforeach
            </select>
            <small class="text-muted">Select other departments that share this section (e.g., Elective or Common
              classes).</small>
          </div>

          {{-- Buttons --}}
          <div class="col-md-12 mt-3">
            <x-form-buttons />
          </div>

        </div>
      </form>

    </div>
  </div>
</div>

<x-footer />