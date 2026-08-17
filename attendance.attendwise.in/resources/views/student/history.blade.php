@extends('layouts.student')

@section('title', 'Attendance History - AttendWise PWA')

@section('content')
<div style="margin-bottom: 16px;">
    <h3 style="font-weight: 800; font-size: 1.2rem; color: var(--text-main); margin-bottom: 4px;">Attendance History</h3>
    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 16px;">Track your detailed attendance records across all subjects.</p>
</div>

<!-- Subject Wise Attendance Breakdown -->
@if(isset($subjectStats) && $subjectStats->count())
<div class="glass-card" style="padding: 16px; margin-bottom: 20px;">
    <div style="font-size: 0.85rem; font-weight: 800; color: var(--text-main); margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between;">
        <span>Subject-wise Breakdown</span>
        <span style="font-size: 0.75rem; color: var(--text-main); font-weight: 700;">{{ $subjectStats->count() }} Subjects</span>
    </div>

    <div style="display: flex; flex-direction: column; gap: 12px;">
        @foreach($subjectStats as $stat)
        <div style="margin-bottom: 12px;">
            <div style="display: flex; justify-content: space-between; font-size: 0.78rem; margin-bottom: 6px;">
                <span style="font-weight: 700; color: var(--text-main);">{{ $stat->subject_name }}</span>
                <span style="font-weight: 800; color: {{ $stat->percentage >= 75 ? '#10b981' : '#ef4444' }};">
                    {{ $stat->percentage }}%
                </span>
            </div>
            <div style="width: 100%; height: 7px; background: #f3f4f6; border-radius: 10px; overflow: hidden;">
                <div style="width: {{ $stat->percentage }}%; height: 100%; background: {{ $stat->percentage >= 75 ? '#10b981' : '#ef4444' }}; border-radius: 10px;"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Attendance Logs List -->
<div style="margin-bottom: 16px;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; padding: 0 4px;">
        <span style="font-size: 0.85rem; font-weight: 800; color: var(--text-main);">Recent Attendance Records</span>
        <i class="fa-solid fa-list-check" style="color: var(--text-muted);"></i>
    </div>

    @if($records->isEmpty())
        <div class="glass-card" style="text-align: center; padding: 32px 16px; color: var(--text-muted);">
            <i class="fa-solid fa-folder-open" style="font-size: 2rem; color: var(--text-muted); margin-bottom: 8px;"></i>
            <div style="font-weight: 600; color: var(--text-main);">No attendance records found yet.</div>
            <div style="font-size: 0.75rem; margin-top: 4px;">Your logged sessions will appear here.</div>
        </div>
    @else
        @foreach($records as $record)
        <div class="glass-card" style="padding: 14px 16px; margin-bottom: 10px; display: flex; align-items: flex-start; justify-content: space-between; gap: 12px;">
            <div>
                <div style="font-weight: 800; font-size: 0.95rem; color: var(--text-main); margin-bottom: 2px;">
                    {{ $record->session->schedule->subject->name ?? 'General Class' }}
                </div>
                <div style="font-size: 0.72rem; color: var(--text-muted); font-weight: 600; display: flex; align-items: center; gap: 6px; margin-top: 2px;">
                    <i class="fa-regular fa-calendar"></i> {{ \Carbon\Carbon::parse($record->date)->format('d M, Y') }}
                </div>
                @if($record->remarks)
                    <div style="font-size: 0.72rem; color: var(--text-muted); font-weight: 600; margin-top: 2px;">
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
                    <span style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 20px;">
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
