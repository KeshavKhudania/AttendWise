<x-structure />
<x-header heading="{{ $title }}" />

<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">

      <form action="{{ $action }}" method="POST" class="msc-ord-form">
        @csrf

        <div class="row g-3">

          {{-- Department --}}
          <div class="col-md-4">
            <label class="form-label">
              Department <span class="text-danger">*</span>
            </label>
            <select name="department_id" class="form-select form-control msc-searchable" required>
              <option value=""></option>
              @foreach ($departments as $dept)
              <option value="{{ $dept->id }}" {{ old('department_id', $mapping->department_id ?? '') == $dept->id ?
                'selected' : '' }}>{{ $dept->name }}{{ $dept->is_additional ? ' (Additional)' : '' }}</option>
              @endforeach
            </select>
          </div>

          {{-- Course --}}
          <div class="col-md-4">
            <label class="form-label">
              Course <span class="text-danger">*</span>
            </label>
            <select name="course_id" class="form-select form-control msc-searchable" required>
              <option value=""></option>@foreach ($courses as $course)
              <option value="{{ $course->id }}" {{ old('course_id', $mapping->course_id ?? '') == $course->id ?
                'selected' : '' }}>{{ $course->name }}</option>
              @endforeach
            </select>
          </div>

          {{-- Semester --}}
          <div class="col-md-4">
            <label class="form-label">
              Semester <span class="text-danger">*</span>
            </label>
            <input type="number" name="semester" class="form-control" min="1" placeholder="e.g. 1" required
              value="{{ old('semester', $mapping->semester ?? '') }}">
          </div>

          {{-- Subjects --}}
          <div class="col-md-12">
            <label class="form-label">
              Subjects <span class="text-danger">*</span>
            </label>
            <select name="subjects[]" class="form-select form-control msc-searchable" multiple required>
              @foreach ($subjects as $subject)
              <option value="{{ $subject->id }}" {{ in_array( $subject->id,
                old(
                'subjects',
                isset($mapping)
                ? (array)$mapping->subjects
                : []
                )
                ) ? 'selected' : '' }}>
                {{ $subject->name }} ({{ $subject->code }})
              </option>
              @endforeach
            </select>
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