@extends('layouts.student')

@section('title', 'Weekly Schedule - AttendWise PWA')

@section('content')
<div style="margin-bottom: 16px;">
    <h3 style="font-weight: 800; font-size: 1.2rem; color: #0f172a; margin-bottom: 4px;">Class Schedule</h3>
    <p style="font-size: 0.78rem; color: var(--text-muted);">Weekly timetable matrix for your section</p>
</div>

<!-- Day Selector Tabs -->
<div style="display: flex; gap: 8px; overflow-x: auto; padding-bottom: 10px; margin-bottom: 14px; scrollbar-width: none;">
    @foreach($days as $day)
    @php $isToday = \Carbon\Carbon::now()->format('l') === $day; @endphp
    <button class="day-tab-btn {{ $isToday ? 'active' : '' }}" onclick="switchDay('{{ strtolower($day) }}')" id="tab-{{ strtolower($day) }}" style="background: {{ $isToday ? 'var(--accent-gradient)' : '#ffffff' }}; color: {{ $isToday ? '#ffffff' : '#475569' }}; border: 1px solid {{ $isToday ? 'transparent' : '#e2e8f0' }}; padding: 8px 16px; border-radius: 14px; font-size: 0.82rem; font-weight: 700; white-space: nowrap; cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.03);">
        {{ $day }}
        @if($isToday) <span style="font-size: 0.65rem; background: rgba(255,255,255,0.25); padding: 2px 6px; border-radius: 10px; margin-left: 4px;">Today</span> @endif
    </button>
    @endforeach
</div>

<!-- Timetable Day Cards -->
@foreach($days as $day)
@php 
    $dayKey = strtolower($day);
    $daySchedules = $groupedSchedules[$day] ?? collect();
    $isToday = \Carbon\Carbon::now()->format('l') === $day;
@endphp
<div class="day-schedule-pane" id="pane-{{ $dayKey }}" style="display: {{ $isToday ? 'block' : 'none' }};">
    @if($daySchedules->isEmpty())
        <div class="glass-card" style="text-align: center; padding: 28px; color: var(--text-muted);">
            <i class="fa-regular fa-calendar-xmark" style="font-size: 2rem; color: #4f46e5; margin-bottom: 8px;"></i>
            <div style="font-size: 0.9rem; font-weight: 700; color: #334155;">No Classes Scheduled for {{ $day }}</div>
        </div>
    @else
        @foreach($daySchedules as $schedule)
        <div class="glass-card" style="padding: 16px; margin-bottom: 12px; border-left: 4px solid var(--accent-primary);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div style="font-size: 0.78rem; font-weight: 700; color: #4f46e5; margin-bottom: 4px;">
                        <i class="fa-regular fa-clock"></i> {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                    </div>
                    <div style="font-weight: 800; font-size: 1.05rem; color: #0f172a; margin-bottom: 4px;">
                        {{ $schedule->subject->name ?? 'Subject' }}
                    </div>
                    <div style="font-size: 0.8rem; color: var(--text-muted); display: flex; align-items: center; gap: 12px;">
                        <span><i class="fa-solid fa-user-tie"></i> {{ $schedule->faculty->name ?? 'Faculty' }}</span>
                        <span><i class="fa-solid fa-location-dot"></i> {{ $schedule->classroom->name ?? 'Room' }}</span>
                    </div>
                </div>
                <div style="background: #eef2ff; border: 1px solid #c7d2fe; font-size: 0.72rem; font-weight: 700; color: #4f46e5; padding: 4px 10px; border-radius: 12px;">
                    Lecture
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>
@endforeach

@endsection

@section('scripts')
<script>
    function switchDay(dayKey) {
        document.querySelectorAll('.day-schedule-pane').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.day-tab-btn').forEach(btn => {
            btn.style.background = '#ffffff';
            btn.style.color = '#475569';
            btn.style.borderColor = '#e2e8f0';
            btn.classList.remove('active');
        });

        const activePane = document.getElementById('pane-' + dayKey);
        const activeTab = document.getElementById('tab-' + dayKey);
        if (activePane) activePane.style.display = 'block';
        if (activeTab) {
            activeTab.style.background = 'var(--accent-gradient)';
            activeTab.style.color = '#ffffff';
            activeTab.style.borderColor = 'transparent';
            activeTab.classList.add('active');
        }
    }
</script>
@endsection
