<x-structure />
<x-header heading="{{ $title }}" />

<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">

      <form action="{{ $action }}" method="POST" id="mainForm">
        @csrf

        <div class="row g-3">

          {{-- Course Name --}}
          <div class="col-md-6">
            <label class="form-label">Course Name <span class="text-danger">*</span></label>
            <input
              type="text"
              name="name"
              class="form-control"
              required
              placeholder="e.g. Bachelor of Computer Science"
              value="{{ old('name', $course->name ?? '') }}"
            >
          </div>

          {{-- Course Code --}}
          <div class="col-md-3">
            <label class="form-label">Course Code</label>
            <input
              type="text"
              name="code"
              class="form-control"
              placeholder="e.g. BCS"
              value="{{ old('code', $course->code ?? '') }}"
            >
          </div>

          {{-- Level --}}
          <div class="col-md-3">
            <label class="form-label">Level <span class="text-danger">*</span></label>
            <select name="level" class="form-select form-control" required>
              <option value="">Select Level</option>
              <option value="Undergraduate" {{ old('level', $course->level ?? '') == 'Undergraduate' ? 'selected' : '' }}>UG</option>
              <option value="Postgraduate" {{ old('level', $course->level ?? '') == 'Postgraduate' ? 'selected' : '' }}>PG</option>
              <option value="Diploma" {{ old('level', $course->level ?? '') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
              <option value="Certificate" {{ old('level', $course->level ?? '') == 'Certificate' ? 'selected' : '' }}>Certificate</option>
              <option value="PhD" {{ old('level', $course->level ?? '') == 'PhD' ? 'selected' : '' }}>PhD</option>
            </select>
          </div>

          {{-- Department --}}
          <div class="col-md-6">
            <label class="form-label">Department <span class="text-danger">*</span></label>
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

          {{-- Duration --}}
          <div class="col-md-3">
            <label class="form-label">Duration (Years) <span class="text-danger">*</span></label>
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
          {{-- Duration --}}
          <div class="col-md-3">
            <label class="form-label">Total Semesters <span class="text-danger">*</span></label>
            <input
              type="number"
              name="total_semesters"
              class="form-control"
              min="2"
              required
              placeholder="e.g. 4"
              value="{{ old('total_semesters', $course->total_semesters ?? '') }}"
            >
          </div>

          {{-- Status --}}
          <div class="col-md-3">
            <label class="form-label">Status <span class="text-danger">*</span></label>
            <select name="status" class="form-select form-control" required>
              <option value="1" {{ old('status', $course->status ?? 1) == 1 ? 'selected' : '' }}>
                Active
              </option>
              <option value="0" {{ old('status', $course->status ?? '') == 0 ? 'selected' : '' }}>
                Inactive
              </option>
            </select>
          </div>

          {{-- Description --}}
          <div class="col-md-12">
            <label class="form-label">Description</label>
            <textarea
              name="description"
              class="form-control"
              rows="3"
              placeholder="Optional course description"
            >{{ old('description', $course->description ?? '') }}</textarea>
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
