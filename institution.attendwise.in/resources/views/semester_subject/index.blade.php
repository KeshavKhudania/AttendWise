<x-structure />
<x-header heading="Curriculum Architect" />

<link rel="stylesheet" href="assets/vendors/select2/select2.min.css">

<style>
    /* =========================================
       PREMIUM CURRICULUM UI STYLES
       ========================================= */
       
    body {
        background-color: #f8fafc;
    }
    
    /* Hero Section */
    .hero-card {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 1.5rem !important;
        position: relative;
    }
    .hero-card .card-body {
        background: #ffffff !important;
    }
    .hero-card .form-label {
        color: #374151 !important;
        letter-spacing: 0.5px;
    }

    /* Matrix Semester Cards Z-index & Stacking Context fixes */
    .row.g-4 {
        position: relative;
    }
    .row.g-4 > [class*="col-"] {
        position: relative;
        z-index: 1;
    }
    .row.g-4 > [class*="col-"]:hover {
        z-index: 50;
    }
    .row.g-4 > [class*="col-"]:focus-within,
    .row.g-4 > [class*="col-"]:has(.select2-container--open) {
        z-index: 9999 !important;
    }

    .matrix-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 1.25rem;
        background: #ffffff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        position: relative;
        z-index: 1;
        overflow: hidden;
    }
    .matrix-card .card-body {
        overflow: hidden;
    }
    .matrix-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-color: rgba(99, 102, 241, 0.3);
        z-index: 50;
    }
    .matrix-card:focus-within,
    .matrix-card.select2-is-open,
    .matrix-card:has(.select2-container--open) {
        z-index: 9999 !important;
    }
    .matrix-header {
        background: linear-gradient(to bottom, #f8fafc, #ffffff);
        border-bottom: 1px solid #f1f5f9;
        border-radius: 1.25rem 1.25rem 0 0 !important;
        padding: 1.25rem;
    }
    .sem-badge {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        font-weight: 600;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 10px rgba(99, 102, 241, 0.3);
    }

    /* Select2 Multi-Select Premium Customization & Overflow Prevention */
    .matrix-card .select2-container {
        width: 100% !important;
        max-width: 100% !important;
    }
    .matrix-card .select2-container--default .select2-selection--multiple {
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.75rem !important;
        min-height: 56px;
        padding: 6px 8px;
        background-color: #f8fafc;
        transition: all 0.2s ease;
        max-width: 100% !important;
        overflow-x: hidden !important;
        height: auto !important;
    }
    .matrix-card .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #8b5cf6 !important;
        box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.15) !important;
        background-color: #ffffff;
    }
    /* Select2 Multi-Select Choice Tag (Blue Pill Box) Strict Text Containment */
    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 4px;
        width: 100% !important;
        max-width: 100% !important;
        padding: 4px !important;
        margin: 0 !important;
        box-sizing: border-box !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice,
    .matrix-card .select2-container--default .select2-selection--multiple .select2-selection__choice,
    .select2-container--default .select2-selection--multiple .select2-selection__choice:nth-child(n) {
        max-width: calc(100% - 6px) !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
        box-sizing: border-box !important;
        display: inline-flex !important;
        align-items: center !important;
        margin: 3px !important;
        padding: 4px 8px !important;
        font-size: 0.8rem !important;
        font-weight: 500 !important;
        line-height: 1.4 !important;
        border-radius: 4px !important;
        vertical-align: middle !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        flex-shrink: 0 !important;
        margin-right: 6px !important;
        font-weight: bold !important;
        display: inline-block !important;
        float: none !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice .select2-selection__choice__display,
    .select2-container--default .select2-selection--multiple .select2-selection__choice span,
    .matrix-card .select2-container--default .select2-selection--multiple .select2-selection__choice span {
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
        max-width: 100% !important;
        min-width: 0 !important;
        flex-shrink: 1 !important;
        display: inline-block !important;
        vertical-align: middle !important;
    }
    .matrix-card .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        background: transparent !important;
        color: #b91c1c !important;
    }
    .matrix-card .select2-search--inline {
        display: inline-block !important;
        max-width: 100% !important;
    }
    .matrix-card .select2-search--inline .select2-search__field {
        margin-top: 4px !important;
        max-width: 100% !important;
    }

    /* Sticky Save Button */
    .save-btn-container {
        position: sticky;
        bottom: 0;
        z-index: 100;
        padding: 1.5rem 0;
        background: linear-gradient(to top, rgba(248, 250, 252, 1) 70%, rgba(248, 250, 252, 0));
    }
    .btn-deploy {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border: none;
        transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.2s;
    }
    .btn-deploy:hover {
        transform: scale(1.03);
        box-shadow: 0 15px 30px rgba(99, 102, 241, 0.4) !important;
    }

    /* Fix dropdown text color inheritance & z-index overlay */
    .select2-container--open {
        z-index: 99999 !important;
    }
    .select2-dropdown {
        z-index: 99999 !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15) !important;
        max-width: 100% !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #1e293b !important;
    }
    .select2-container--default .select2-results__option {
        color: #1e293b !important;
        white-space: normal !important;
        word-break: break-word !important;
        padding: 8px 12px !important;
    }
    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #e2e8f0 !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #f1f5f9 !important;
        color: #4f46e5 !important;
        font-weight: 600;
    }
</style>

<div class="container-fluid pt-4 pb-5">
    
    <!-- Hero Selection Banner -->
    <div class="card hero-card shadow-sm mb-5" style="background: #ffffff !important; color: #111827 !important; border-radius: 1.5rem !important; border: 1px solid #e2e8f0 !important;">
        <div class="card-body p-4 p-md-5" style="background: #ffffff !important;">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0 text-center text-lg-start">
                    <h2 class="fw-bold mb-2 text-dark" style="color: #111827 !important;">Curriculum Architect</h2>
                    <p class="mb-0 text-muted" style="color: #4b5563 !important; font-size: 1.1rem;">Select a course to seamlessly build, organize, and deploy its semester-wise subjects.</p>
                </div>
                <div class="col-lg-6 text-start">
                    <form id="courseSelectForm" method="GET" action="{{ route('institution.subject.manage.mapping.index') }}">
                        <label class="form-label fw-semibold mb-2 ms-2 text-dark" style="color: #374151 !important;"><i class="fa fa-search me-1 text-primary"></i> Target Course</label>
                        <select id="courseSelect" name="course_id" class="form-select msc-searchable w-100">
                            <option value="">-- Search & Choose Course --</option>
                            @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->name }}
                            </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($selectedCourse)
    <form action="{{ route('institution.subject.manage.mapping.save') }}" method="POST">
        @csrf
        <input type="hidden" name="course_id" value="{{ $selectedCourse->id }}">
        <input type="hidden" name="total_semesters" id="totalSemestersInput" value="{{ $selectedCourse->total_semesters ?? 8 }}">

        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4 px-2">
            <h4 class="fw-bold text-dark mb-0 d-flex align-items-center">
                <div class="bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                    <i class="fa fa-graduation-cap text-primary fs-5"></i>
                </div>
                {{ $selectedCourse->name }}
                <span class="badge bg-secondary bg-opacity-10 text-secondary border ms-3 rounded-pill fw-normal fs-6 px-3">Curriculum Matrix</span>
            </h4>
            
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="d-flex align-items-center bg-white rounded-pill shadow-sm px-4 py-2 border">
                    <i class="fa fa-building text-primary me-2"></i>
                    <label class="form-label mb-0 fw-semibold text-muted me-3">Primary Dept:</label>
                    <select name="department_id" class="form-select form-select-sm border-0 bg-transparent fw-bold text-dark" style="min-width: 180px; box-shadow: none; cursor: pointer;">
                        <option value="">Select Department</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ ($mappings->first()->department_id ?? $selectedCourse->department_id) == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button type="button" id="addSemesterBtn" class="btn btn-primary rounded-pill px-3 py-2 fw-semibold shadow-sm text-nowrap">
                        <i class="fa fa-plus-circle me-1"></i> Add Semester
                    </button>
                    <button type="button" id="removeLastSemesterBtn" class="btn btn-outline-danger rounded-pill px-3 py-2 fw-semibold shadow-sm text-nowrap">
                        <i class="fa fa-minus-circle me-1"></i> Remove Semester
                    </button>
                </div>
            </div>
        </div>

        <div class="row g-4" id="matrixContainer">
            @for($sem = 1; $sem <= ($selectedCourse->total_semesters ?? 8); $sem++)
            <div class="col-xxl-3 col-xl-4 col-md-6 semester-col" data-sem="{{ $sem }}">
                <div class="card matrix-card h-100">
                    <div class="card-header matrix-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <h6 class="mb-0 fw-bold text-dark sem-title">Semester {{ $sem }}</h6>
                            <button type="button" class="btn btn-link text-danger p-0 ms-1 remove-sem-btn" title="Remove Semester {{ $sem }}" style="text-decoration: none;">
                                <i class="fa fa-trash-alt opacity-75"></i>
                            </button>
                        </div>
                        <span class="badge rounded-pill sem-badge px-3 py-2 sem-badge-text">Sem {{ $sem }}</span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        @php
                            $mappedSubjects = isset($mappings[$sem]) ? (array)$mappings[$sem]->subjects : [];
                        @endphp
                        
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <label class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Assign Subjects</label>
                            <i class="fa fa-book text-muted opacity-25 fs-5"></i>
                        </div>
                        
                        <div class="flex-grow-1">
                            <select name="semesters[{{ $sem }}][]" class="form-select msc-searchable custom-select2" multiple="multiple" data-placeholder="Search and add subjects..." style="width: 100%;">
                                <optgroup label="Core Subjects (Primary Dept)">
                                @foreach($subjects as $subject)
                                    @if(!$subject->additional_department_id)
                                    <option value="{{ $subject->id }}" {{ in_array($subject->id, $mappedSubjects) ? 'selected' : '' }}>
                                        {{ $subject->code }} - {{ $subject->name }} ({{ $subject->department->name ?? 'General' }})
                                    </option>
                                    @endif
                                @endforeach
                                </optgroup>

                                <optgroup label="Additional / Elective Subjects">
                                @foreach($subjects as $subject)
                                    @if($subject->additional_department_id)
                                    <option value="{{ $subject->id }}" {{ in_array($subject->id, $mappedSubjects) ? 'selected' : '' }}>
                                        {{ $subject->code }} - {{ $subject->name }} [{{ $subject->additionalDepartment->name ?? 'Additional Dept' }}]
                                    </option>
                                    @endif
                                @endforeach
                                </optgroup>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            @endfor
        </div>

        <!-- Sticky Save Button Container -->
        <div class="save-btn-container text-center mt-4">
            <button type="submit" class="btn btn-deploy btn-lg rounded-pill shadow-lg px-5 py-3 fw-bold fs-5 text-white">
                <i class="fa fa-cloud-upload-alt me-2"></i> Deploy Curriculum
            </button>
        </div>

    </form>
    <!-- Semester Card Template for Adding New Semesters dynamically -->
    <template id="semesterCardTemplate">
        <div class="col-xxl-3 col-xl-4 col-md-6 semester-col" data-sem="__SEM__">
            <div class="card matrix-card h-100">
                <div class="card-header matrix-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <h6 class="mb-0 fw-bold text-dark sem-title">Semester __SEM__</h6>
                        <button type="button" class="btn btn-link text-danger p-0 ms-1 remove-sem-btn" title="Remove Semester __SEM__" style="text-decoration: none;">
                            <i class="fa fa-trash-alt opacity-75"></i>
                        </button>
                    </div>
                    <span class="badge rounded-pill sem-badge px-3 py-2 sem-badge-text">Sem __SEM__</span>
                </div>
                <div class="card-body p-4 d-flex flex-column">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <label class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Assign Subjects</label>
                        <i class="fa fa-book text-muted opacity-25 fs-5"></i>
                    </div>
                    
                    <div class="flex-grow-1">
                        <select name="semesters[__SEM__][]" class="form-select msc-searchable custom-select2" multiple="multiple" data-placeholder="Search and add subjects..." style="width: 100%;">
                            <optgroup label="Core Subjects (Primary Dept)">
                            @foreach($subjects as $subject)
                                @if(!$subject->additional_department_id)
                                <option value="{{ $subject->id }}">
                                    {{ $subject->code }} - {{ $subject->name }} ({{ $subject->department->name ?? 'General' }})
                                </option>
                                @endif
                            @endforeach
                            </optgroup>

                            <optgroup label="Additional / Elective Subjects">
                            @foreach($subjects as $subject)
                                @if($subject->additional_department_id)
                                <option value="{{ $subject->id }}">
                                    {{ $subject->code }} - {{ $subject->name }} [{{ $subject->additionalDepartment->name ?? 'Additional Dept' }}]
                                </option>
                                @endif
                            @endforeach
                            </optgroup>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </template>

    @else
    
    <!-- Beautiful Empty State -->
    <div class="d-flex flex-column align-items-center justify-content-center py-5 mt-5">
        <div class="mb-4 position-relative" style="width: 120px; height: 120px;">
            <div class="position-absolute w-100 h-100 bg-primary rounded-circle" style="opacity: 0.05; filter: blur(20px); transform: scale(1.5);"></div>
            <div class="bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center position-relative w-100 h-100 border">
                <i class="fa fa-project-diagram text-primary" style="font-size: 3rem; opacity: 0.8;"></i>
            </div>
        </div>
        <h3 class="fw-bold text-dark mb-2">No Course Selected</h3>
        <p class="text-muted fs-5 text-center" style="max-width: 500px;">Please search and select a course from the banner above to start architecting its curriculum matrix.</p>
    </div>
    
    @endif

</div>

<script src="assets/vendors/select2/select2.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    if (typeof jQuery !== 'undefined') {
      $('#courseSelect').on('change', function() {
        $('#courseSelectForm').submit();
      });

      // Initialize Select2 on custom-select2 elements
      function initSelect2Elements($container) {
        if (typeof $.fn !== 'undefined' && typeof $.fn.select2 === 'function') {
          var $target = $container ? $container.find('.custom-select2') : $('.custom-select2');
          $target.each(function() {
            var $select = $(this);
            if (!$select.data('select2')) {
              $select.select2({
                placeholder: $select.attr('data-placeholder') || "Search and add subjects...",
                allowClear: true,
                dropdownParent: $(document.body)
              });
            }
          });
        }
      }

      initSelect2Elements();

      // Update semester card labels & select input names sequentially
      function updateSemesterIndices() {
        var $cols = $('#matrixContainer .semester-col');
        $cols.each(function(index) {
          var semNum = index + 1;
          $(this).attr('data-sem', semNum);
          $(this).find('.sem-title').text('Semester ' + semNum);
          $(this).find('.sem-badge-text').text('Sem ' + semNum);
          $(this).find('.remove-sem-btn').attr('title', 'Remove Semester ' + semNum);
          var $select = $(this).find('select.custom-select2');
          $select.attr('name', 'semesters[' + semNum + '][]');
        });
        $('#totalSemestersInput').val($cols.length);
      }

      // Add Semester Handler
      $('#addSemesterBtn').on('click', function() {
        var newSemNum = $('#matrixContainer .semester-col').length + 1;
        var templateHtml = $('#semesterCardTemplate').html();
        if (!templateHtml) return;
        
        var cardHtml = templateHtml.replace(/__SEM__/g, newSemNum);
        var $newCol = $(cardHtml);
        $('#matrixContainer').append($newCol);
        
        initSelect2Elements($newCol);
        updateSemesterIndices();
      });

      // Remove Last Semester Handler
      $('#removeLastSemesterBtn').on('click', function() {
        var $cols = $('#matrixContainer .semester-col');
        if ($cols.length <= 1) {
          alert('A course must have at least 1 semester.');
          return;
        }
        $cols.last().remove();
        updateSemesterIndices();
      });

      // Remove Specific Semester Handler
      $(document).on('click', '.remove-sem-btn', function() {
        var $cols = $('#matrixContainer .semester-col');
        if ($cols.length <= 1) {
          alert('A course must have at least 1 semester.');
          return;
        }
        $(this).closest('.semester-col').remove();
        updateSemesterIndices();
      });

      // Manage z-index dynamically when Select2 dropdown opens/closes
      $(document).on('select2:open', '.custom-select2', function() {
        var $card = $(this).closest('.matrix-card');
        var $col = $(this).closest('[class*="col-"]');
        $card.addClass('select2-is-open').css('z-index', '99999');
        $col.css('z-index', '99999');
      });

      $(document).on('select2:close', '.custom-select2', function() {
        var $card = $(this).closest('.matrix-card');
        var $col = $(this).closest('[class*="col-"]');
        setTimeout(function() {
          $card.removeClass('select2-is-open').css('z-index', '');
          $col.css('z-index', '');
        }, 150);
      });

    } else {
      document.getElementById('courseSelect').addEventListener('change', function() {
        document.getElementById('courseSelectForm').submit();
      });
    }
  });
</script>

<x-footer />