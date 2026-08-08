@extends('layouts.student')

@section('title', 'Attendance History - AttendWise PWA')

@section('content')
<div style="margin-bottom: 16px;">
    <h3 style="font-weight: 800; font-size: 1.2rem; color: #fff; margin-bottom: 4px;">Attendance History</h3>
    <p style="font-size: 0.78rem; color: var(--text-muted);">Detailed breakdown of your attended and missed lectures</p>
</div>

<!-- Subject Wise Attendance Breakdown -->
@if(isset($subjectStats) && $subjectStats->count())
<div class="glass-card" style="padding: 16px; margin-bottom: 18px;">
    <div style="font-size: 0.85rem; font-weight: 800; color: #fff; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between;">
        <span>Subject Breakdown</span>
        <span style="font-size: 0.75rem; color: #818cf8; font-weight: 600;">{{ $subjectStats->count() }} Subjects</span>
    </div>

    <div style="display: flex; flex-direction: column; gap: 12px;">
        @foreach($subjectStats as $stat)
        <div>
            <div style="display: flex; justify-content: space-between; font-size: 0.82rem; margin-bottom: 4px;">
                <span style="font-weight: 700; color: #f8fafc;">{{ $stat->subject_name }}</span>
                <span style="font-weight: 800; color: {{ $stat->percentage >= 75 ? '#34d399' : '#fca5a5' }};">
                    {{ $stat->percentage }}% ({{ $stat->present_count }}/{{ $stat->total }})
                </span>
            </div>
            <div style="width: 100%; height: 6px; background: rgba(255, 255, 255, 0.08); border-radius: 10px; overflow: hidden;">
                <div style="width: {{ $stat->percentage }}%; height: 100%; background: {{ $stat->percentage >= 75 ? 'var(--success-gradient)' : 'var(--danger-gradient)' }}; border-radius: 10px;"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Attendance Logs List -->
<div style="margin-bottom: 16px;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; padding: 0 4px;">
        <span style="font-size: 0.85rem; font-weight: 800; color: #fff;">Recent Attendance Records</span>
    </div>

    @if($records->isEmpty())
        <div class="glass-card" style="text-align: center; padding: 24px; color: var(--text-muted);">
            <i class="fa-solid fa-folder-open" style="font-size: 2rem; color: #818cf8; margin-bottom: 8px;"></i>
            <div>No attendance records found yet.</div>
        </div>
    @else
        @foreach($records as $record)
        <div class="glass-card" style="padding: 14px 16px; margin-bottom: 10px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-weight: 800; font-size: 0.95rem; color: #fff; margin-bottom: 2px;">
                    {{ $record->session->schedule->subject->name ?? 'General Class' }}
                </div>
                <div style="font-size: 0.78rem; color: var(--text-muted); display: flex; align-items: center; gap: 10px;">
                    <span><i class="fa-regular fa-calendar"></i> {{ \Carbon\Carbon::parse($record->date)->format('d M, Y') }}</span>
                    <span><i class="fa-regular fa-clock"></i> {{ \Carbon\Carbon::parse($record->created_at)->format('h:i A') }}</span>
                </div>
                @if($record->remarks)
                    <div style="font-size: 0.72rem; color: #818cf8; margin-top: 2px;">
                        <i class="fa-solid fa-tag"></i> {{ $record->remarks }}
                    </div>
                @endif
            </div>

            <div>
                @if($record->status === 'present')
                    <span style="background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 20px;">
                        Present
                    </span>
                @elseif($record->status === 'late')
                    <span style="background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 20px;">
                        Late
                    </span>
                @else
                    <span style="background: rgba(239, 68, 68, 0.15); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.3); font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 20px;">
                        Absent
                    </span>
                @endif
            </div>
        </div>
        @endforeach

        <div style="margin-top: 16px;">
            {{ $records->links() }}
        </div>
    @endif
</div>
@endsection
