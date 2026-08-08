@extends('layouts.student')

@section('title', 'Attendance History - AttendWise PWA')

@section('content')
<div style="margin-bottom: 16px;">
    <h3 style="font-weight: 800; font-size: 1.2rem; color: #0f172a; margin-bottom: 4px;">Attendance History</h3>
    <p style="font-size: 0.78rem; color: var(--text-muted);">Detailed breakdown of your attended and missed lectures</p>
</div>

<!-- Subject Wise Attendance Breakdown -->
@if(isset($subjectStats) && $subjectStats->count())
<div class="glass-card" style="padding: 16px; margin-bottom: 18px;">
    <div style="font-size: 0.85rem; font-weight: 800; color: #0f172a; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between;">
        <span>Subject Breakdown</span>
        <span style="font-size: 0.75rem; color: #4f46e5; font-weight: 700;">{{ $subjectStats->count() }} Subjects</span>
    </div>

    <div style="display: flex; flex-direction: column; gap: 12px;">
        @foreach($subjectStats as $stat)
        <div>
            <div style="display: flex; justify-content: space-between; font-size: 0.82rem; margin-bottom: 4px;">
                <span style="font-weight: 700; color: #0f172a;">{{ $stat->subject_name }}</span>
                <span style="font-weight: 800; color: {{ $stat->percentage >= 75 ? '#059669' : '#dc2626' }};">
                    {{ $stat->percentage }}% ({{ $stat->present_count }}/{{ $stat->total }})
                </span>
            </div>
            <div style="width: 100%; height: 7px; background: #e2e8f0; border-radius: 10px; overflow: hidden;">
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
        <span style="font-size: 0.85rem; font-weight: 800; color: #0f172a;">Recent Attendance Records</span>
    </div>

    @if($records->isEmpty())
        <div class="glass-card" style="text-align: center; padding: 24px; color: var(--text-muted);">
            <i class="fa-solid fa-folder-open" style="font-size: 2rem; color: #4f46e5; margin-bottom: 8px;"></i>
            <div style="font-weight: 600; color: #334155;">No attendance records found yet.</div>
        </div>
    @else
        @foreach($records as $record)
        <div class="glass-card" style="padding: 14px 16px; margin-bottom: 10px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-weight: 800; font-size: 0.95rem; color: #0f172a; margin-bottom: 2px;">
                    {{ $record->session->schedule->subject->name ?? 'General Class' }}
                </div>
                <div style="font-size: 0.78rem; color: var(--text-muted); display: flex; align-items: center; gap: 10px;">
                    <span><i class="fa-regular fa-calendar"></i> {{ \Carbon\Carbon::parse($record->date)->format('d M, Y') }}</span>
                    <span><i class="fa-regular fa-clock"></i> {{ \Carbon\Carbon::parse($record->created_at)->format('h:i A') }}</span>
                </div>
                @if($record->remarks)
                    <div style="font-size: 0.72rem; color: #4f46e5; font-weight: 600; margin-top: 2px;">
                        <i class="fa-solid fa-tag"></i> {{ $record->remarks }}
                    </div>
                @endif
            </div>

            <div>
                @if($record->status === 'present')
                    <span style="background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 20px;">
                        Present
                    </span>
                @elseif($record->status === 'late')
                    <span style="background: #fffbeb; color: #b45309; border: 1px solid #fde68a; font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 20px;">
                        Late
                    </span>
                @else
                    <span style="background: #fef2f2; color: #b91c1c; border: 1px solid #fca5a5; font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 20px;">
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
