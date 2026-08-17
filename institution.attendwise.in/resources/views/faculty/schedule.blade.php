<x-structure />
<x-header heading="{{ $title ?? 'Faculty Timetable' }}" />

<style>
    body {
        background-color: #f4f7f6;
    }
    .schedule-wrapper {
        overflow-x: auto;
        padding-bottom: 1rem;
    }
    .timetable {
        min-width: 900px;
        background: #fff;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #eef2f7;
    }
    .timetable th, .timetable td {
        border: 1px solid #eef2f7;
        padding: 12px 10px;
        vertical-align: middle;
        text-align: center;
    }
    .timetable thead th {
        background: linear-gradient(180deg, #f8f9fa 0%, #f1f3f5 100%);
        color: #495057;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 15px 10px;
        border-bottom: 2px solid #e0e6ed;
    }
    .day-column {
        font-weight: 700;
        background: linear-gradient(90deg, #f8f9fa 0%, #ffffff 100%);
        color: #0d6efd;
        font-size: 0.85rem;
        width: 100px;
        text-transform: uppercase;
        border-right: 2px solid #e0e6ed !important;
    }
    .slot-cell {
        min-width: 140px;
        position: relative;
        transition: background-color 0.2s ease;
        padding: 8px !important;
    }
    .slot-cell:hover {
        background-color: #f8f9fa;
    }
    .slot-card {
        background: linear-gradient(135deg, rgba(13, 110, 253, 0.05) 0%, rgba(13, 110, 253, 0.1) 100%);
        border: 1px solid rgba(13, 110, 253, 0.2);
        border-radius: 8px;
        padding: 10px 6px;
        color: #0d6efd;
        height: 100%;
        min-height: 80px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        cursor: default;
        position: relative;
        overflow: hidden;
    }
    .slot-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 4px; height: 100%;
        background-color: #0d6efd;
        border-radius: 4px 0 0 4px;
    }
    .slot-card:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 6px 15px rgba(13, 110, 253, 0.15);
        z-index: 2;
    }
    .slot-card.lab-slot {
        background: linear-gradient(135deg, rgba(23, 162, 184, 0.05) 0%, rgba(23, 162, 184, 0.1) 100%);
        border-color: rgba(23, 162, 184, 0.2);
        color: #0c8a9e;
    }
    .slot-card.lab-slot::before {
        background-color: #17a2b8;
    }
    .slot-subject {
        font-weight: 800;
        font-size: 0.8rem;
        margin-bottom: 4px;
        line-height: 1.2;
    }
    .slot-meta {
        font-size: 0.7rem;
        font-weight: 600;
        opacity: 0.85;
    }
    .empty-slot {
        color: #dee2e6;
        font-size: 0.8rem;
    }
    
    /* Side Panel Styles */
    .side-panel-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        background: #fff;
        height: 100%;
    }
    .event-item {
        padding: 12px;
        border-radius: 10px;
        background: rgba(255, 193, 7, 0.08);
        border-left: 4px solid #ffc107;
        margin-bottom: 12px;
        transition: transform 0.2s;
    }
    .event-item:hover {
        transform: translateX(3px);
    }
    .holiday-item {
        padding: 12px;
        border-radius: 10px;
        background: rgba(220, 53, 69, 0.08);
        border-left: 4px solid #dc3545;
        margin-bottom: 12px;
        transition: transform 0.2s;
    }
    .holiday-item:hover {
        transform: translateX(3px);
    }
    .date-badge {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        background: #fff;
        padding: 3px 8px;
        border-radius: 4px;
        display: inline-block;
        margin-bottom: 6px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
</style>

<div class="container-fluid py-4">

    <!-- Top Action Bar -->
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h3 class="fw-bold mb-1 text-dark">{{ $faculty->name }}'s Timetable</h3>
            <p class="text-muted mb-0"><i class="fa fa-briefcase me-2"></i>{{ $faculty->designation }} | <i class="fa fa-building me-2"></i>{{ $faculty->department->name }}</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('institution.faculty.manage') }}" class="btn btn-white rounded-pill px-4 py-2 shadow-sm fw-bold border">
                <i class="fa fa-arrow-left me-2"></i> Back to Faculty
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Timetable Grid -->
        <div class="col-xl-9 col-lg-8">
            @if($groupedSchedules->isEmpty())
                <div class="text-center py-5 bg-white rounded-4 shadow-sm h-100 d-flex flex-column justify-content-center align-items-center" style="min-height: 400px;">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                        style="width: 100px; height: 100px;">
                        <i class="fa fa-calendar-plus text-muted fs-1"></i>
                    </div>
                    <h4 class="text-dark fw-bold">Timetable is empty</h4>
                    <p class="text-muted">No scheduled classes found for this faculty member.</p>
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0"><i class="fa fa-calendar-alt text-primary me-2"></i> Weekly Schedule</h5>
                            <div class="d-flex gap-3">
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25"><i class="fa fa-circle text-primary me-1" style="font-size:8px;"></i> Theory</span>
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25"><i class="fa fa-circle text-info me-1" style="font-size:8px;"></i> Lab</span>
                            </div>
                        </div>
                        <div class="schedule-wrapper custom-scrollbar">
                            <table class="timetable w-100">
                                <thead>
                                    <tr>
                                        <th class="text-center"><i class="fa fa-clock me-2"></i>Day \ Time</th>
                                        @foreach($slotTimings as $slot)
                                            <th>
                                                {{ \Carbon\Carbon::parse($slot['start'])->format('h:i A') }}<br>
                                                <span class="text-muted" style="font-size: 0.65rem; font-weight: 500;">
                                                    to {{ \Carbon\Carbon::parse($slot['end'])->format('h:i A') }}
                                                </span>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dayOrder as $dayName)
                                        @if(isset($groupedSchedules[$dayName]))
                                            <tr>
                                                <td class="day-column">{{ substr($dayName, 0, 3) }}</td>
                                                @php
                                                    $skipSlots = 0;
                                                @endphp
                                                @foreach($slotTimings as $index => $slot)
                                                    @if($skipSlots > 0)
                                                        @php $skipSlots--; @endphp
                                                        @continue
                                                    @endif

                                                    @php
                                                        // Find if there's a schedule in this exact slot
                                                        $scheduledItem = $groupedSchedules[$dayName]->first(function($item) use ($slot) {
                                                            return \Carbon\Carbon::parse($item->start_time)->format('H:i') === \Carbon\Carbon::parse($slot['start'])->format('H:i');
                                                        });

                                                        $colspan = 1;
                                                        if ($scheduledItem) {
                                                            // Check next slots for same subject & section
                                                            for ($i = $index + 1; $i < count($slotTimings); $i++) {
                                                                $nextSlot = $slotTimings[$i];
                                                                $nextItem = $groupedSchedules[$dayName]->first(function($item) use ($nextSlot) {
                                                                    return \Carbon\Carbon::parse($item->start_time)->format('H:i') === \Carbon\Carbon::parse($nextSlot['start'])->format('H:i');
                                                                });

                                                                if ($nextItem && 
                                                                    $nextItem->subject_id == $scheduledItem->subject_id && 
                                                                    $nextItem->section_id == $scheduledItem->section_id && 
                                                                    $nextItem->lecture_type == $scheduledItem->lecture_type) {
                                                                    $colspan++;
                                                                    $skipSlots++;
                                                                } else {
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                    
                                                    <td class="slot-cell" {!! $colspan > 1 ? 'colspan="'.$colspan.'"' : '' !!}>
                                                        @if($scheduledItem)
                                                            <div class="slot-card {{ strtolower($scheduledItem->lecture_type) == 'lab' ? 'lab-slot' : '' }}">
                                                                <div class="slot-subject">{{ $scheduledItem->subject->name }}</div>
                                                                <div class="slot-meta">
                                                                    <i class="fa fa-users text-opacity-50 me-1"></i> {{ $scheduledItem->section->name }}
                                                                </div>
                                                                <div class="slot-meta mt-1">
                                                                    <i class="fa fa-map-marker-alt text-opacity-50 me-1"></i> {{ $scheduledItem->classroom->name ?? $scheduledItem->classroom->room_number ?? 'N/A' }}
                                                                </div>
                                                            </div>
                                                        @else
                                                            <span class="empty-slot">-</span>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Side Panel: Events & Holidays -->
        <div class="col-xl-3 col-lg-4">
            <div class="side-panel-card card p-4">
                <h5 class="fw-bold mb-4 border-bottom pb-3"><i class="fa fa-bullhorn text-warning me-2"></i> Upcoming Events</h5>
                
                <div class="events-container mb-4">
                    @forelse($events as $event)
                        <div class="event-item">
                            <div class="date-badge text-warning">{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</div>
                            <h6 class="fw-bold mb-1 text-dark">{{ $event->name }}</h6>
                            <div class="text-muted small"><i class="fa fa-clock me-1"></i> {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}</div>
                        </div>
                    @empty
                        <div class="text-center py-4 opacity-50">
                            <i class="fa fa-calendar-times fs-2 mb-2"></i>
                            <p class="small mb-0">No upcoming events.</p>
                        </div>
                    @endforelse
                </div>

                <h5 class="fw-bold mb-4 border-bottom pb-3 mt-4"><i class="fa fa-tree text-danger me-2"></i> Holidays</h5>
                
                <div class="holidays-container">
                    @php
                        // Filter holidays to only show upcoming ones
                        $upcomingHolidays = $holidays->filter(function($h) {
                            $dateStr = is_array($h) ? ($h['date'] ?? null) : $h;
                            if (!$dateStr) return false;
                            return \Carbon\Carbon::parse($dateStr)->isSameDay(now()) || \Carbon\Carbon::parse($dateStr)->isFuture();
                        })->take(5);
                    @endphp

                    @forelse($upcomingHolidays as $holiday)
                        @php
                            $hDate = is_array($holiday) ? ($holiday['date'] ?? null) : $holiday;
                            $hName = is_array($holiday) ? ($holiday['name'] ?? 'Institution Holiday') : 'Institution Holiday';
                        @endphp
                        <div class="holiday-item">
                            <div class="date-badge text-danger">{{ \Carbon\Carbon::parse($hDate)->format('M d, Y') }}</div>
                            <h6 class="fw-bold mb-0 text-dark">{{ $hName }}</h6>
                        </div>
                    @empty
                        <div class="text-center py-4 opacity-50">
                            <i class="fa fa-umbrella-beach fs-2 mb-2"></i>
                            <p class="small mb-0">No upcoming holidays.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<x-footer />
