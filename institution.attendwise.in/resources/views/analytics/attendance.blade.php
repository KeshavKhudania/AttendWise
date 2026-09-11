<x-structure />
<x-header />
@include('analytics.attendance-styles')

<div class="aa-page container-fluid px-4">
    {{-- Header --}}
    <div class="aa-header">
        <div>
            <h1 class="aa-title">Attendance Analytics</h1>
            <p class="aa-subtitle">Deep-dive into attendance data with powerful multi-dimensional filters.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('institution.attendance.dashboard') }}" class="aa-btn"><i class="fas fa-arrow-left"></i> Dashboard</a>
            <button class="aa-btn" onclick="exportCSV()"><i class="fas fa-file-csv"></i> Export CSV</button>
        </div>
    </div>

    {{-- Filter Panel --}}
    <div class="aa-filter-panel">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
            <div style="font-size:1rem;font-weight:700;color:#0F172A;display:flex;align-items:center;gap:.5rem">
                <i class="fas fa-sliders-h" style="color:#2563EB"></i> Advanced Filters
            </div>
            <div style="font-size:.75rem;color:#94A3B8;font-weight:500">Combine any filters for complex queries</div>
        </div>
        <div class="aa-filter-grid">
            <div class="aa-filter-group">
                <label>Department</label>
                <select id="f_department_id"><option value="">All Departments</option>@foreach($departments as $d)<option value="{{$d->id}}">{{$d->name}}</option>@endforeach</select>
            </div>
            <div class="aa-filter-group">
                <label>Course</label>
                <select id="f_course_id"><option value="">All Courses</option>@foreach($courses as $c)<option value="{{$c->id}}">{{$c->name}}</option>@endforeach</select>
            </div>
            <div class="aa-filter-group">
                <label>Section</label>
                <select id="f_section_id"><option value="">All Sections</option>@foreach($sections as $s)<option value="{{$s->id}}">{{$s->name}} (Sem {{$s->semester??'—'}})</option>@endforeach</select>
            </div>
            <div class="aa-filter-group">
                <label>Subject</label>
                <select id="f_subject_id"><option value="">All Subjects</option>@foreach($subjects as $s)<option value="{{$s->id}}">{{$s->code}} — {{$s->name}}</option>@endforeach</select>
            </div>
            <div class="aa-filter-group">
                <label>Faculty</label>
                <select id="f_faculty_id"><option value="">All Faculty</option>@foreach($faculties as $f)<option value="{{$f->id}}">{{$f->name}}</option>@endforeach</select>
            </div>
            <div class="aa-filter-group">
                <label>Semester</label>
                <select id="f_semester"><option value="">All Semesters</option>@for($i=1;$i<=8;$i++)<option value="{{$i}}">Semester {{$i}}</option>@endfor</select>
            </div>
            <div class="aa-filter-group">
                <label>Status</label>
                <select id="f_status"><option value="">All Statuses</option><option value="present">Present</option><option value="absent">Absent</option><option value="late">Late</option><option value="excused">Excused</option></select>
            </div>
            <div class="aa-filter-group">
                <label>Day of Week</label>
                <select id="f_day_of_week"><option value="">Any Day</option><option value="2">Monday</option><option value="3">Tuesday</option><option value="4">Wednesday</option><option value="5">Thursday</option><option value="6">Friday</option><option value="7">Saturday</option><option value="1">Sunday</option></select>
            </div>
            <div class="aa-filter-group">
                <label>Date From</label>
                <input type="date" id="f_date_from">
            </div>
            <div class="aa-filter-group">
                <label>Date To</label>
                <input type="date" id="f_date_to">
            </div>
            <div class="aa-filter-group" style="grid-column:span 2">
                <label>Search Student (Name / Roll / Enrollment)</label>
                <input type="text" id="f_student_search" placeholder="Type to search students...">
            </div>
        </div>
        <div class="aa-filter-actions">
            <button class="aa-btn aa-btn-primary" onclick="applyFilters()"><i class="fas fa-search"></i> Apply Filters</button>
            <button class="aa-btn aa-btn-danger" onclick="resetFilters()"><i class="fas fa-times"></i> Reset All</button>
        </div>
    </div>

    {{-- Loading Overlay --}}
    <div id="aa-loading" class="aa-loading" style="display:none">
        <i class="fas fa-circle-notch aa-spin" style="font-size:1.5rem"></i> Crunching data...
    </div>

    <div id="aa-content" style="transition:opacity .3s">
        {{-- KPI Row --}}
        <div class="row g-3 mb-4">
            <div class="col-xl-2 col-md-4 col-6"><div class="aa-kpi kpi-blue"><i class="fas fa-database aa-kpi-icon"></i><div class="aa-kpi-label">Total Records</div><div class="aa-kpi-value" id="kpi-total">—</div></div></div>
            <div class="col-xl-2 col-md-4 col-6"><div class="aa-kpi kpi-green"><i class="fas fa-user-check aa-kpi-icon"></i><div class="aa-kpi-label">Present</div><div class="aa-kpi-value" id="kpi-present">—</div></div></div>
            <div class="col-xl-2 col-md-4 col-6"><div class="aa-kpi kpi-red"><i class="fas fa-user-times aa-kpi-icon"></i><div class="aa-kpi-label">Absent</div><div class="aa-kpi-value" id="kpi-absent">—</div></div></div>
            <div class="col-xl-2 col-md-4 col-6"><div class="aa-kpi kpi-amber"><i class="fas fa-clock aa-kpi-icon"></i><div class="aa-kpi-label">Late</div><div class="aa-kpi-value" id="kpi-late">—</div></div></div>
            <div class="col-xl-2 col-md-4 col-6"><div class="aa-kpi kpi-purple"><i class="fas fa-shield-alt aa-kpi-icon"></i><div class="aa-kpi-label">Excused</div><div class="aa-kpi-value" id="kpi-excused">—</div></div></div>
            <div class="col-xl-2 col-md-4 col-6"><div class="aa-kpi kpi-teal"><i class="fas fa-percentage aa-kpi-icon"></i><div class="aa-kpi-label">Overall %</div><div class="aa-kpi-value" id="kpi-pct">—</div></div></div>
        </div>

        {{-- Charts Row --}}
        <div class="row g-4 mb-4">
            <div class="col-xl-8">
                <div class="aa-card">
                    <div class="aa-card-title"><i class="fas fa-chart-line"></i> Attendance Trend</div>
                    <div class="aa-chart-wrap"><canvas id="trendCanvas"></canvas></div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="aa-card">
                    <div class="aa-card-title"><i class="fas fa-chart-pie"></i> Subject Distribution</div>
                    <div class="aa-chart-wrap"><canvas id="subjectCanvas"></canvas></div>
                </div>
            </div>
        </div>

        {{-- Breakdowns Row --}}
        <div class="row g-4 mb-4">
            <div class="col-xl-4">
                <div class="aa-card">
                    <div class="aa-card-title"><i class="fas fa-calendar-week"></i> Day-of-Week Heatmap</div>
                    <div class="aa-dow-grid" id="dowGrid"></div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="aa-card">
                    <div class="aa-card-title"><i class="fas fa-book"></i> Subject Breakdown</div>
                    <div id="subjectBars" style="max-height:300px;overflow-y:auto"></div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="aa-card">
                    <div class="aa-card-title"><i class="fas fa-chalkboard-teacher"></i> Faculty Breakdown</div>
                    <div id="facultyBars" style="max-height:300px;overflow-y:auto"></div>
                </div>
            </div>
        </div>

        {{-- Records Table --}}
        <div class="aa-card p-0 overflow-hidden">
            <div style="padding:1.25rem 1.5rem;border-bottom:1px solid #F1F5F9;display:flex;justify-content:space-between;align-items:center">
                <div class="aa-card-title" style="margin-bottom:0"><i class="fas fa-table"></i> Detailed Records</div>
            </div>
            <div class="table-responsive" style="max-height:500px;overflow-y:auto">
                <table class="aa-table">
                    <thead><tr><th>Student</th><th>Date</th><th>Subject</th><th>Faculty</th><th>Section</th><th>Course</th><th>Status</th><th>Time</th></tr></thead>
                    <tbody id="recordsBody"></tbody>
                </table>
            </div>
            <div id="paginationWrap" class="aa-pagination" style="padding:1rem"></div>
        </div>
    </div>
</div>

@include('analytics.attendance-scripts')
<x-footer />
