<x-structure />
<x-header heading="{{ $title }}" />

<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">

      <form action="{{ $action }}" method="POST" class="msc-ord-form">
        @csrf
        @if($type === 'edit')
        @method('PUT')
        @endif

        <div class="row g-3">

          {{-- Subject Name --}}
          <div class="col-md-3">
            <label class="form-label">
              Subject Name <span class="text-danger">*</span>
            </label>
            <input type="text" name="name" class="form-control" required placeholder="e.g. Data Structures"
              value="{{ old('name', $subject->name ?? '') }}">
          </div>

          {{-- Subject Code --}}
          <div class="col-md-3">
            <label class="form-label">Subject Code</label>
            <input type="text" name="code" class="form-control" placeholder="e.g. CS301"
              value="{{ old('code', $subject->code ?? '') }}">
          </div>
          {{-- Type --}}
          <div class="col-md-3">
            <label class="form-label">
              Type <span class="text-danger">*</span>
            </label>
            <select name="type" class="form-select form-control" required>
              <option value="">Select Type</option>
              <option value="theory" {{ old('type', $subject->type ?? '') == 'theory' ? 'selected' : '' }}>Theory
              </option>
              <option value="practical" {{ old('type', $subject->type ?? '') == 'practical' ? 'selected' : ''
                }}>Practical</option>
              <option value="lab" {{ old('type', $subject->type ?? '') == 'lab' ? 'selected' : '' }}>Lab</option>
            </select>
          </div>

          {{-- Semester --}}
          <div class="col-md-3">
            <label class="form-label">
              Classroom Type <span class="text-danger">*</span>
            </label>
            <select name="classroom_type" class="form-select form-control" required>
              <option value="">Select Classroom Type</option>
              @foreach ($classroom_types as $item)
              <option value="{{ $item->id }}" {{ old('classroom_type', $subject->classroom_type ?? '') == $item->id ?
                'selected' : '' }}>
                {{ $item->name }}
              </option>
              @endforeach
            </select>
          </div>

          {{-- Credits --}}
          <div class="col-md-3">
            <label class="form-label">Credits</label>
            <input type="number" name="credits" class="form-control" min="0" max="10" placeholder="e.g. 4"
              value="{{ old('credits', $subject->credits ?? '') }}">
          </div>

          {{-- Weekly Lectures --}}
          <div class="col-md-3">
            <label class="form-label">Weekly Lectures</label>
            <input type="number" name="weekly_lectures" class="form-control" min="0" max="20" placeholder="e.g. 4"
              value="{{ old('weekly_lectures', $subject->weekly_lectures ?? '') }}">
          </div>

          {{-- Elective --}}
          <div class="col-md-3">
            <label class="form-label">Elective</label>
            <select name="is_elective" class="form-select form-control">
              <option value="0" {{ old('is_elective', $subject->is_elective ?? 0) == 0 ? 'selected' : '' }}>
                No
              </option>
              <option value="1" {{ old('is_elective', $subject->is_elective ?? '') == 1 ? 'selected' : '' }}>
                Yes
              </option>
            </select>
          </div>

          {{-- Status --}}
          <div class="col-md-3">
            <label class="form-label">
              Status <span class="text-danger">*</span>
            </label>
            <select name="status" class="form-select form-control" required>
              <option value="1" {{ old('status', $subject->status ?? 1) == 1 ? 'selected' : '' }}>
                Active
              </option>
              <option value="0" {{ old('status', $subject->status ?? '') == 0 ? 'selected' : '' }}>
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