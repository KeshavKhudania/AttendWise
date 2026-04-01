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
              placeholder="e.g. Dr. Rahul Sharma"
              value="{{ old('name', $faculty->name ?? '') }}"
            >
          </div>

          {{-- Employee Code --}}
          <div class="col-md-3">
            <label class="form-label">Employee Code</label>
            <input
              type="text"
              name="employee_code"
              class="form-control"
              placeholder="e.g. EMP1023"
              value="{{ old('employee_code', $faculty->employee_code ?? '') }}"
            >
          </div>

          {{-- Designation --}}
          <div class="col-md-3">
            <label class="form-label">Designation</label>
            <label for="designation">Faculty Designation:</label>
            <select name="designation" id="designation" class="form-control">
                <option value="">-- Select Designation --</option>

                <optgroup label="Administrative Leadership">
                    <option value="chancellor" {{$faculty['designation'] == "chancellor"?"selected":""}}>Chancellor</option>
                    <option value="vice_chancellor" {{$faculty['designation'] == "vice_chancellor"?"selected":""}}>Vice Chancellor</option>
                    <option value="provost" {{$faculty['designation'] == "provost"?"selected":""}}>Provost</option>
                    <option value="dean" {{$faculty['designation'] == "dean"?"selected":""}}>Dean</option>
                    <option value="associate_dean" {{$faculty['designation'] == "associate_dean"?"selected":""}}>Associate Dean</option>
                    <option value="registrar" {{$faculty['designation'] == "registrar"?"selected":""}}>Registrar</option>
                    <option value="hod" {{$faculty['designation'] == "hod"?"selected":""}}>Head of Department (HOD)</option>
                    <option value="director" {{$faculty['designation'] == "director"?"selected":""}}>Director</option>
                </optgroup>

                <optgroup label="Professorial Ranks">
                    <option value="professor_emeritus" {{$faculty['designation'] == "professor_emeritus"?"selected":""}}>Professor Emeritus</option>
                    <option value="professor" {{$faculty['designation'] == "professor"?"selected":""}}>Professor</option>
                    <option value="associate_professor" {{$faculty['designation'] == "associate_professor"?"selected":""}}>Associate Professor</option>
                    <option value="assistant_professor" {{$faculty['designation'] == "assistant_professor"?"selected":""}}>Assistant Professor</option>
                </optgroup>

                <optgroup label="Instructional Staff">
                    <option value="senior_lecturer" {{$faculty['designation'] == "senior_lecturer"?"selected":""}}>Senior Lecturer</option>
                    <option value="lecturer" {{$faculty['designation'] == "lecturer"?"selected":""}}>Lecturer</option>
                    <option value="instructor" {{$faculty['designation'] == "instructor"?"selected":""}}>Instructor</option>
                    <option value="adjunct_professor" {{$faculty['designation'] == "adjunct_professor"?"selected":""}}>Adjunct Professor</option>
                    <option value="visiting_professor" {{$faculty['designation'] == "visiting_professor"?"selected":""}}>Visiting Professor</option>
                    <option value="guest_faculty" {{$faculty['designation'] == "guest_faculty"?"selected":""}}>Guest Faculty</option>
                </optgroup>

                <optgroup label="Research & Support">
                    <option value="research_fellow" {{$faculty['designation'] == "research_fellow"?"selected":""}}>Research Fellow</option>
                    <option value="post_doc" {{$faculty['designation'] == "post_doc"?"selected":""}}>Postdoctoral Fellow</option>
                    <option value="research_associate" {{$faculty['designation'] == "research_associate"?"selected":""}}>Research Associate</option>
                    <option value="teaching_assistant" {{$faculty['designation'] == "teaching_assistant"?"selected":""}}>Teaching Assistant (TA)</option>
                    <option value="lab_instructor" {{$faculty['designation'] == "lab_instructor"?"selected":""}}>Lab Instructor/Demonstrator</option>
                    <option value="librarian" {{$faculty['designation'] == "librarian"?"selected":""}}>Librarian</option>
                </optgroup>
            </select>
          </div>

          
          {{-- Status --}}
          <div class="col-md-3">
              <label class="form-label">
                  Status <span class="text-danger">*</span>
                </label>
                <select name="status" class="form-select form-control" required>
              <option value="1" {{ old('status', $faculty->status ?? 1) == 1 ? 'selected' : '' }}>
                  Active
                </option>
                <option value="0" {{ old('status', $faculty->status ?? '') == 0 ? 'selected' : '' }}>
                    Inactive
                </option>
            </select>
        </div>
          {{-- Department --}}
          <div class="col-md-4">
              <label class="form-label">
                  Department <span class="text-danger">*</span>
            </label>
            <select name="department_id" class="form-select form-control" required>
                @foreach ($departments as $item)
                    <option value="{{ $item->id }}" {{ old('department', $faculty->department_id ?? '') == $item->id ? 'selected' : '' }}>
                        {{ $item->name }}
                @endforeach
            </select>
        </div>
        {{-- Email --}}
        <div class="col-md-4">
          <label class="form-label">
            Email <span class="text-danger">*</span>
          </label>
          <input
            type="email"
            name="email"
            class="form-control"
            required
            placeholder="faculty@institution.edu"
            value="{{ old('email', $faculty->email ?? '') }}"
          >
        </div>

        {{-- Phone --}}
        <div class="col-md-4">
          <label class="form-label">Mobile</label>
          <input
            type="number"
            name="mobile"
            class="form-control"
            min="10"
            
            placeholder="10-digit mobile number"
            value="{{ old('mobile', $faculty->mobile ?? '') }}"
          >
        </div>
        <div class="col-md-12">
            <label class="form-label">
              Subjects <span class="text-danger">*</span>
            </label>
            <select name="subjects[]" class="form-select form-control msc-searchable" multiple required>
                <option value="" disabled>Select Subjects</option>
                @foreach($subjects as $subject)
                    <option 
                    value="{{ Crypt::encryptString($subject->id) }}" 
                    {{ in_array($subject->id, $selectedSubjects ?? []) ? 'selected' : '' }}
                    >
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
