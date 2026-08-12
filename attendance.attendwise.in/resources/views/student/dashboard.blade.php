@extends('layouts.student')

@section('title', 'Dashboard - AttendWise PWA')

@section('content')
<!-- Student Profile Header Card -->
<div class="glass-card" style="padding: 16px; margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--accent-primary);">
    <div style="display: flex; align-items: center; gap: 14px;">
        <div style="width: 48px; height: 48px; border-radius: 14px; background: var(--accent-gradient); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.2rem; color: #fff; box-shadow: 0 4px 12px var(--accent-glow);">
            {{ strtoupper(substr($student->name ?? 'S', 0, 1)) }}
        </div>
        <div>
            <div style="font-weight: 800; font-size: 1.1rem; color: #0f172a; line-height: 1.2;">{{ $student->name }}</div>
            <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 2px;">
                Roll: <strong style="color: #334155;">{{ $student->roll_number }}</strong> • 
                <span style="color: #4f46e5; font-weight: 600;">{{ $student->institution->legal_name ?? 'AttendWise Campus' }}</span>
            </div>
        </div>
    </div>
    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; padding: 6px 10px; text-align: center;">
        <div style="font-size: 0.68rem; font-weight: 700; color: #047857; text-transform: uppercase;">Status</div>
        <div style="font-size: 0.78rem; font-weight: 700; color: #059669; display: flex; align-items: center; gap: 4px;">
            <i class="fa-solid fa-circle" style="font-size: 0.5rem;"></i> Active
        </div>
    </div>
</div>

<!-- Dynamic Hero QR Scan Action Card -->
<div class="glass-card" style="background: linear-gradient(135deg, #eef2ff 0%, #e0f2fe 100%); border: 1px solid #c7d2fe; text-align: center; padding: 22px 18px; position: relative; overflow: hidden;">
    <div style="position: absolute; right: -20px; bottom: -20px; font-size: 8rem; color: rgba(79, 70, 229, 0.06); pointer-events: none;">
        <i class="fa-solid fa-qrcode"></i>
    </div>

    <div style="display: inline-flex; align-items: center; gap: 8px; background: #ffffff; border: 1px solid #c7d2fe; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; color: #4f46e5; margin-bottom: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.03);">
        <i class="fa-solid fa-bolt" style="color: #f59e0b;"></i> Quick Attendance Marking
    </div>

    <h3 style="font-weight: 800; font-size: 1.3rem; color: #0f172a; margin-bottom: 6px;">Ready for Class?</h3>
    <p style="color: #475569; font-size: 0.82rem; margin-bottom: 18px; max-width: 320px; margin-left: auto; margin-right: auto;">
        Scan the dynamic QR code displayed on the classroom screen to automatically log your attendance.
    </p>

    <a href="{{ route('student.scanner') }}" class="btn-primary" style="text-decoration: none; padding: 16px; font-size: 1.05rem; border-radius: 16px;">
        <i class="fa-solid fa-camera" style="font-size: 1.2rem;"></i>
        <span>Scan QR Code Now</span>
    </a>
</div>

<!-- Attendance Overall Stats Ring -->
<div class="glass-card" style="margin-bottom: 16px;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
        <h4 style="font-weight: 800; font-size: 1rem; color: #0f172a;">Attendance Overview</h4>
        @if($percentage >= 75)
            <span style="background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; font-size: 0.72rem; font-weight: 700; padding: 4px 10px; border-radius: 20px;">
                <i class="fa-solid fa-shield-check"></i> On Track (&ge; 75%)
            </span>
        @else
            <span style="background: #fef2f2; color: #b91c1c; border: 1px solid #fca5a5; font-size: 0.72rem; font-weight: 700; padding: 4px 10px; border-radius: 20px;">
                <i class="fa-solid fa-triangle-exclamation"></i> Low Attendance (&lt; 75%)
            </span>
        @endif
    </div>

    <div style="display: flex; align-items: center; justify-content: space-around; gap: 16px;">
        <!-- Circular Progress Gauge -->
        <div style="position: relative; width: 110px; height: 110px; display: flex; align-items: center; justify-content: center;">
            <svg width="110" height="110" viewBox="0 0 100 100" style="transform: rotate(-90deg);">
                <circle cx="50" cy="50" r="42" stroke="#e2e8f0" stroke-width="10" fill="none" />
                <circle cx="50" cy="50" r="42" 
                        stroke="{{ $percentage >= 75 ? '#10b981' : '#ef4444' }}" 
                        stroke-width="10" 
                        stroke-dasharray="264" 
                        stroke-dashoffset="{{ 264 - (264 * $percentage / 100) }}" 
                        stroke-linecap="round" 
                        fill="none" 
                        style="transition: stroke-dashoffset 1s ease-in-out;" />
            </svg>
            <div style="position: absolute; text-align: center;">
                <div style="font-size: 1.35rem; font-weight: 800; color: #0f172a;">{{ $percentage }}%</div>
                <div style="font-size: 0.65rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Overall</div>
            </div>
        </div>

        <!-- Metric Counter Grid -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; flex: 1;">
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 10px; text-align: center;">
                <div style="font-size: 1.1rem; font-weight: 800; color: #059669;">{{ $presentCount }}</div>
                <div style="font-size: 0.7rem; color: var(--text-muted); font-weight: 600;">Attended</div>
            </div>
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 10px; text-align: center;">
                <div style="font-size: 1.1rem; font-weight: 800; color: #dc2626;">{{ $absentCount }}</div>
                <div style="font-size: 0.7rem; color: var(--text-muted); font-weight: 600;">Missed</div>
            </div>
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 10px; text-align: center;">
                <div style="font-size: 1.1rem; font-weight: 800; color: #d97706;">{{ $lateCount }}</div>
                <div style="font-size: 0.7rem; color: var(--text-muted); font-weight: 600;">Late</div>
            </div>
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 10px; text-align: center;">
                <div style="font-size: 1.1rem; font-weight: 800; color: #4f46e5;">{{ $totalClasses }}</div>
                <div style="font-size: 0.7rem; color: var(--text-muted); font-weight: 600;">Total</div>
            </div>
        </div>
    </div>
</div>

<!-- Today's Schedule Timeline -->
<div style="margin-bottom: 16px;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; padding: 0 4px;">
        <h4 style="font-weight: 800; font-size: 1rem; color: #0f172a; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-calendar-day" style="color: #4f46e5;"></i> Today's Lectures
        </h4>
        <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">{{ \Carbon\Carbon::now()->format('D, d M Y') }}</span>
    </div>

    @if($todaySchedules->isEmpty())
        <div class="glass-card" style="text-align: center; padding: 28px 16px; color: var(--text-muted);">
            <i class="fa-solid fa-mug-hot" style="font-size: 2rem; color: #4f46e5; margin-bottom: 8px;"></i>
            <div style="font-size: 0.9rem; font-weight: 700; color: #334155;">No Classes Scheduled Today</div>
            <div style="font-size: 0.78rem; margin-top: 4px;">Enjoy your day or check your full schedule!</div>
        </div>
    @else
        @foreach($todaySchedules as $schedule)
        <div class="glass-card" style="padding: 16px; margin-bottom: 12px; border-left: 4px solid {{ $schedule->attendance_status === 'present' ? '#10b981' : ($schedule->is_session_active ? '#4f46e5' : '#cbd5e1') }};">
            <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 12px;">
                <div>
                    <div style="font-size: 0.75rem; font-weight: 700; color: #4f46e5; display: flex; align-items: center; gap: 6px; margin-bottom: 4px;">
                        <i class="fa-regular fa-clock"></i>
                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                    </div>
                    <div style="font-weight: 800; font-size: 1rem; color: #0f172a; margin-bottom: 4px;">
                        {{ $schedule->subject->name ?? 'Subject' }}
                    </div>
                    <div style="font-size: 0.78rem; color: var(--text-muted); display: flex; align-items: center; gap: 10px;">
                        <span><i class="fa-solid fa-user-tie"></i> {{ $schedule->faculty->name ?? 'Faculty' }}</span>
                        <span><i class="fa-solid fa-location-dot"></i> {{ $schedule->classroom->name ?? 'Classroom' }}</span>
                    </div>
                </div>

                <!-- Status Badge or Quick Scan -->
                <div>
                    @if($schedule->attendance_status === 'present')
                        <span style="background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; font-size: 0.75rem; font-weight: 700; padding: 6px 12px; border-radius: 20px; display: inline-flex; align-items: center; gap: 4px;">
                            <i class="fa-solid fa-check-circle"></i> Present
                        </span>
                    @elseif($schedule->attendance_status === 'cancelled' || ($schedule->is_cancelled && !$schedule->is_session_active))
                        <span style="background: #fef2f2; color: #b91c1c; border: 1px solid #fca5a5; font-size: 0.75rem; font-weight: 700; padding: 6px 12px; border-radius: 20px; display: inline-flex; align-items: center; gap: 4px;">
                            <i class="fa-solid fa-ban"></i> Cancelled/Suspended
                        </span>
                    @elseif($schedule->is_session_active)
                        <a href="{{ route('student.scanner') }}" style="background: var(--accent-gradient); color: #fff; text-decoration: none; font-size: 0.78rem; font-weight: 700; padding: 8px 14px; border-radius: 14px; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px var(--accent-glow);">
                            <i class="fa-solid fa-qrcode"></i> Scan QR Live!
                        </a>
                    @else
                        <span style="background: #f1f5f9; color: var(--text-muted); border: 1px solid #e2e8f0; font-size: 0.75rem; font-weight: 600; padding: 6px 10px; border-radius: 20px;">
                            Upcoming
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection
