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
                <button type="button" onclick="submitResourceInfo()"
                    class="btn btn-action btn-outline-info px-4 rounded-pill shadow-sm text-info">
                    <i class="fa fa-users-cog me-2"></i> Resource Info
                </button>
                <button class="btn btn-action btn-upload px-4 rounded-pill shadow-sm" data-bs-toggle="modal"
                    data-bs-target="#uploadTimetableModal">
                    <i class="fa fa-upload me-2"></i> Upload
                </button>
                <a href="{{ route('institution.time.table.manage.auto_generate.view') }}"
                    class="btn btn-action btn-generate px-4 rounded-pill shadow-sm">
                    <i class="fa fa-magic me-2"></i> Auto Generate
                </a>
                <a href="{{ route('institution.time.table.manage.temporary.index') }}"
                    class="btn btn-action btn-outline-warning px-4 rounded-pill shadow-sm text-warning"
                    style="border: 2px solid coral !important; color: coral !important;">
                    <i class="fa fa-calendar-day me-2"></i> Temp Override
                </a>
            </div>
        </div>
    </div>

    {{-- Faculty Shortage Notification --}}
    @if(session('timetable_shortages'))
    <div class="alert border-0 shadow-sm rounded-4 mb-4 alert-dismissible fade show"
         role="alert"
         style="background: #fff8e1; border-left: 4px solid #f59e0b !important;">
        <div class="d-flex align-items-start gap-3">
            <i class="fas fa-exclamation-triangle text-warning fs-4 mt-1 flex-shrink-0"></i>
            <div class="flex-grow-1">
                <h6 class="fw-bold mb-2" style="color: #92400e;">
                    Faculty Shortage &mdash; {{ count(session('timetable_shortages')) }} subject(s) could not be fully scheduled
                </h6>
                <div class="table-responsive">
                    <table class="table table-sm table-borderless mb-0 small">
                        <thead style="color: #78350f;">
                            <tr>
                                <th>Section</th>
                                <th>Subject</th>
                                <th>Faculty</th>
                                <th>Reason</th>
                                <th class="text-center">Sessions Unplaced</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(session('timetable_shortages') as $shortage)
                            <tr class="border-bottom">
                                <td class="fw-bold">{{ $shortage['section'] }}</td>
                                <td>{{ $shortage['subject'] }}</td>
                                <td>{{ $shortage['faculty'] }}</td>
                                <td class="text-danger small">{{ $shortage['reason'] }}</td>
                                <td class="text-center">
                                    <span class="badge bg-danger rounded-pill">{{ $shortage['days_needed'] ?? '?' }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-2 small text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Assign faculty to the listed subjects or expand their working-day availability, then re-run Auto Generate.
                </div>
            </div>
            <button type="button" class="btn-close flex-shrink-0" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    {{-- Upload Modal --}}
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
            <form id="filterForm" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                        class="form-control border-0 bg-light rounded-3" placeholder="Search Section (e.g. CSE-A)...">
                </div>
                <div class="col-md-3">
                    <select name="course_id" id="courseSelect" class="form-select border-0 bg-light rounded-3">
                        <option value="">All Courses</option>
                        @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id')==$course->id ? 'selected' : '' }}>
                            {{ $course->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="semester" id="semesterSelect" class="form-select border-0 bg-light rounded-3">
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
                        <button type="button" id="resetFilters" class="btn btn-light w-100 rounded-3 text-muted">
                            <i class="fa fa-times me-1"></i> Clear
                        </button>
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
        <div class="row g-4" id="sectionGrid">
            @include('schedule.partials.section_list')
        </div>
        
        <!-- Loading Spinner & Sentinel -->
        <div id="loadingSentinel" class="text-center py-4" style="display: {{ $sections->hasMorePages() ? 'block' : 'none' }};">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-muted small mt-2">Loading more sections...</p>
        </div>
    </form>
</div>

<script>
    document.getElementById('selectAllSections').addEventListener('change', function () {
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
        form.action = "{{ route('institution.time.table.manage.export_bulk') }}";
        form.submit();
    }

    function submitResourceInfo() {
        const form = document.getElementById('bulkExportForm');
        const checked = form.querySelectorAll('input[name="selected_ids[]"]:checked').length;
        if (checked === 0) {
            alert('Please select at least one section to view resource info.');
            return;
        }
        form.action = "{{ route('institution.time.table.manage.resource_info') }}";
        form.submit();
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let currentPage = 1;
        let isFetching = false;
        let hasMore = {{ $sections->hasMorePages() ? 'true' : 'false' }};
        const grid = document.getElementById('sectionGrid');
        const sentinel = document.getElementById('loadingSentinel');
        const form = document.getElementById('filterForm');
        
        const fetchSections = (page, append = false) => {
            if (isFetching) return;
            isFetching = true;
            if (append) sentinel.style.display = 'block';

            const url = new URL("{{ route('institution.time.table.manage') }}");
            url.searchParams.append('page', page);
            const formData = new FormData(form);
            for (let [key, value] of formData.entries()) {
                if (value) url.searchParams.append(key, value);
            }

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                if (!append) grid.innerHTML = '';
                
                if (html.trim() === '') {
                    hasMore = false;
                    sentinel.style.display = 'none';
                } else {
                    grid.insertAdjacentHTML('beforeend', html);
                    // Check if we received less items than pagination size (5) by counting elements
                    // But simpler: just rely on the intersection observer to hit it again until it's empty
                    hasMore = true; 
                    sentinel.style.display = 'block';
                }
                isFetching = false;
            })
            .catch(err => {
                console.error(err);
                isFetching = false;
                sentinel.style.display = 'none';
            });
        };

        // AJAX Filtering (Debounced)
        let debounceTimer;
        const triggerSearch = () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                currentPage = 1;
                hasMore = true;
                fetchSections(currentPage, false);
            }, 500);
        };

        document.getElementById('searchInput').addEventListener('input', triggerSearch);
        document.getElementById('courseSelect').addEventListener('change', triggerSearch);
        document.getElementById('semesterSelect').addEventListener('change', triggerSearch);
        
        document.getElementById('resetFilters').addEventListener('click', () => {
            form.reset();
            triggerSearch();
        });

        // Intersection Observer for Infinite Scroll
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting && hasMore && !isFetching) {
                currentPage++;
                fetchSections(currentPage, true);
            }
        }, {
            rootMargin: '100px'
        });

        observer.observe(sentinel);
    });
</script>

<x-footer />