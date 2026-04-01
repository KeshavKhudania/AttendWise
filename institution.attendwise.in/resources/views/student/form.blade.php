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

          {{-- Name --}}
          <div class="col-md-3">
            <label class="form-label">
              Full Name <span class="text-danger">*</span>
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

          
          {{-- Course --}}
          <div class="col-md-5">
            <label class="form-label">
              Course <span class="text-danger">*</span>
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
        
         {{-- Section --}}
          <div class="col-md-4">
            <label class="form-label">
              Section <span class="text-danger">*</span>
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
        {{-- Roll Number --}}
        <div class="col-md-3">
          <label class="form-label">
            Roll Number <span class="text-danger">*</span>
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
          {{-- Gender --}}
          <div class="col-md-2">
            <label class="form-label">
              Gender <span class="text-danger">*</span>
            </label>
            <select name="gender" class="form-select form-control" required>
              <option value="">Select Gender</option>
              <option value="Male" {{ old('gender', $student->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
              <option value="Female" {{ old('gender', $student->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
              <option value="Other" {{ old('gender', $student->gender ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
          </div>

          {{-- Email --}}
          <div class="col-md-5">
            <label class="form-label">
              Email <span class="text-danger">*</span>
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

          {{-- Phone --}}
          <div class="col-md-5">
            <label class="form-label">Phone</label>
            <input
              type="text"
              name="phone"
              class="form-control"
              placeholder="10-digit mobile number"
              value="{{ old('phone', $student->phone ?? '') }}"
            >
          </div>

          {{-- Date of Birth --}}
          <div class="col-md-2">
            <label class="form-label">Date of Birth</label>
            <input
              type="date"
              name="date_of_birth"
              class="form-control"
              value="{{ old('date_of_birth', $student->date_of_birth ?? '') }}"
            >
          </div>

          {{-- Status --}}
          <div class="col-md-2">
            <label class="form-label">
              Status <span class="text-danger">*</span>
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
