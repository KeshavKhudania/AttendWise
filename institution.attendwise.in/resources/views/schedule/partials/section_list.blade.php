@foreach($sections as $section)
@php
$dayOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$schedules = $section->schedules->sortBy(function($slot) use ($dayOrder) {
    return array_search($slot->day_of_week, $dayOrder) . $slot->start_time;
})->groupBy('day_of_week');
@endphp

<div class="col-xxl-4 col-xl-6 section-card-item">
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
