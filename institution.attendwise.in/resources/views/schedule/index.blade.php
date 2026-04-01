<x-structure />
<x-header heading="Academic Timetable" />

<style>
    .schedule-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
    }

    .schedule-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.1) !important;
    }

    .timeline-item {
        position: relative;
        padding-left: 1.5rem;
        border-left: 2px solid #e9ecef;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -7px;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #0d6efd;
    }

    .subject-tag {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
        padding: 0.25rem 0.6rem;
        border-radius: 20px;
    }

    .time-badge {
        background: #f8f9fa;
        color: #6c757d;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .day-pill {
        font-weight: 600;
        font-size: 0.8rem;
        color: #0d6efd;
        background: rgba(13, 110, 253, 0.05);
        padding: 0.3rem 0.8rem;
        border-radius: 8px;
        display: inline-block;
    }

    .btn-action {
        border: none !important;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(5px);
        color: #0d6efd;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-action:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        background: #fff;
    }

    .btn-upload {
        color: #198754;
    }

    .btn-download {
        color: #0d6efd;
    }

    .btn-generate {
        background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%);
        color: white;
    }
</style>

<div class="container-fluid py-4">

    <!-- Top Action Bar -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h4 class="fw-bold mb-1">Section Timetables</h4>
            <p class="text-muted mb-0">Manage and view weekly schedules for all academic sections.</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <div class="d-flex justify-content-md-end gap-2">
                <a href="{{ route('institution.time.table.download_sample') }}"
                    class="btn btn-action btn-download px-4 rounded-pill shadow-sm">
                    <i class="fa fa-download me-2"></i> Sample
                </a>
                <button type="button" onclick="submitBulkExport()"
                    class="btn btn-action btn-download px-4 rounded-pill shadow-sm text-success">
                    <i class="fa fa-file-excel me-2"></i> Export Selected
                </button>
                <button class="btn btn-action btn-upload px-4 rounded-pill shadow-sm" data-bs-toggle="modal"
                    data-bs-target="#uploadTimetableModal">
                    <i class="fa fa-upload me-2"></i> Upload
                </button>
                <form method="POST" action="{{ route('institution.time.table.auto_generate') }}" class="d-inline">
                    @csrf
                    <button class="btn btn-action btn-generate px-4 rounded-pill shadow-sm">
                        <i class="fa fa-magic me-2"></i> Auto Generate
                    </button>
                </form>
                <a href="{{ route('institution.time.table.manage.temporary.index') }}"
                    class="btn btn-action btn-outline-warning px-4 rounded-pill shadow-sm text-warning"
                    style="border: 2px solid coral !important; color: coral !important;">
                    <i class="fa fa-calendar-day me-2"></i> Temp Override
                </a>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadTimetableModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Upload Timetable</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('institution.time.table.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body py-4">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Select CSV File</label>
                            <input type="file" name="csv_file" class="form-control rounded-3" required accept=".csv">
                            <div class="form-text mt-2 small">
                                <i class="fa fa-info-circle me-1"></i> Please use the structure provided in the <a
                                    href="{{ route('institution.time.table.download_sample') }}">sample file</a>.
                            </div>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="clear_existing" id="clearExisting"
                                checked>
                            <label class="form-check-label small fw-bold" for="clearExisting">Clear existing schedules
                                before upload</label>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Start Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('institution.time.table.manage') }}" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control border-0 bg-light rounded-3" placeholder="Search Section (e.g. CSE-A)...">
                </div>
                <div class="col-md-3">
                    <select name="course_id" class="form-select border-0 bg-light rounded-3">
                        <option value="">All Courses</option>
                        @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id')==$course->id ? 'selected' : '' }}>
                            {{ $course->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="semester" class="form-select border-0 bg-light rounded-3">
                        <option value="">All Semesters</option>
                        @foreach($semesters as $sem)
                        <option value="{{ $sem }}" {{ request('semester')==$sem ? 'selected' : '' }}>
                            Semester {{ $sem }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100 rounded-3">
                            <i class="fa fa-filter me-1"></i> Filter
                        </button>
                        @if(request()->anyFilled(['search', 'course_id', 'semester']))
                        <a href="{{ route('institution.time.table.manage') }}" class="btn btn-light rounded-3">
                            <i class="fa fa-times"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Grid View -->
    <form id="bulkExportForm" action="{{ route('institution.time.table.manage.export_bulk') }}" method="POST">
        @csrf
        <div class="mb-3 d-flex align-items-center gap-3">
            <div class="form-check form-check-primary">
                <label class="form-check-label ms-4 fw-bold">
                    <input class="form-check-input" type="checkbox" id="selectAllSections"
                        style="width: 20px; height: 20px; margin-top: -2px;">
                    Select All Sections
                </label>
            </div>
        </div>
        <div class="row g-4">
            @foreach($sections as $section)
            @php
            $dayOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            $schedules = $section->schedules->sortBy(function($slot) use ($dayOrder) {
            return array_search($slot->day_of_week, $dayOrder) . $slot->start_time;
            })->groupBy('day_of_week');
            @endphp

            <div class="col-xxl-4 col-xl-6">
                <div class="card schedule-card border-0 shadow-sm rounded-4 h-100 overflow-hidden">

                    <!-- Card Header -->
                    <div class="card-header border-0 bg-white pt-4 px-4 pb-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="d-flex align-items-start gap-3">
                                <input type="checkbox" name="selected_ids[]" value="{{ $section->id }}"
                                    class="section-checkbox mt-1" style="width: 18px; height: 18px; cursor: pointer;">
                                <div>
                                    <span class="badge bg-soft-primary text-primary mb-2"
                                        style="background: rgba(13, 110, 253, 0.1);">
                                        {{ $section->course->name }}
                                    </span>
                                    <h5 class="fw-bold mb-0">
                                        Section {{ $section->name }}
                                        <span class="text-muted fw-normal ms-1">| Sem {{ $section->semester }}</span>
                                    </h5>
                                </div>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block">{{ $section->academic_year }}</small>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                        <li><a class="dropdown-item"
                                                href="{{ route('institution.time.table.manage.export', $section->id) }}"><i
                                                    class="fa fa-file-excel me-2 text-success"></i> Export to Excel</a>
                                        </li>
                                        <li><a class="dropdown-item" href="#"><i class="fa fa-edit me-2"></i> Edit
                                                Manually</a></li>
                                        <li><a class="dropdown-item text-danger" href="#"><i
                                                    class="fa fa-trash me-2"></i>
                                                Clear</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body p-4">
                        @forelse($dayOrder as $dayName)
                        @if(isset($schedules[$dayName]))
                        <div class="mb-4">
                            <span class="day-pill mb-3">
                                <i class="fa fa-calendar-alt me-2"></i>{{ $dayName }}
                            </span>

                            <div class="ms-1 mt-2">
                                @foreach($schedules[$dayName] as $slot)
                                <div class="timeline-item mb-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div
                                                class="badge subject-tag mb-1 {{ $slot->lecture_type == 'Lab' ? 'bg-info' : 'bg-primary' }}">
                                                {{ $slot->lecture_type }}
                                            </div>
                                            <div class="fw-bold text-dark fs-6">{{ $slot->subject->name }}</div>
                                            <div class="small text-muted mt-1">
                                                <span class="me-3"><i class="fa fa-user-circle me-1"></i>{{
                                                    $slot->faculty->name }}</span>
                                                <span><i class="fa fa-map-marker-alt me-1"></i>Room {{
                                                    $slot->classroom->name ?? $slot->classroom->room_number ?? 'N/A'
                                                    }}</span>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge time-badge rounded-pill px-3 py-2">
                                                {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}
                                            </span>
                                            <div class="mt-1 small text-muted">
                                                to {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        @empty
                        <div class="text-center py-5">
                            <i class="fa fa-calendar-times transition-3 text-light display-1 mb-3"></i>
                            <p class="text-muted">No timetable entries found for this section.</p>
                        </div>
                        @endforelse

                        @if($schedules->isEmpty())
                        <div class="text-center py-5">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 80px; height: 80px;">
                                <i class="fa fa-calendar-plus text-muted fs-2"></i>
                            </div>
                            <p class="text-muted mb-0">Timetable is empty.</p>
                            <small class="text-muted">Use 'Auto Generate' to fill slots.</small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </form>
</div>

<script>
    document.getElementById('selectAllSections').addEventListener('change', function ) {
        const checkboxes = document.querySelectorAll('.section-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    function submitBulkExport() {
        const form = document.getElementById('bulkExportForm');
        const checked = form.querySelectorAll('input[name="selected_ids[]"]:checked').length;
        if (checked === 0) {
            alert('Please select at least one section to export.');
            return;
        }
        form.submit();
    }
</script>

<x-footer />