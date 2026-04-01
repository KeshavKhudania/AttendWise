@extends('layouts.faculty')

@section('header-title', 'Overview')
@section('header-subtitle', 'Track your performance and manage class schedules.')

@section('content')
<div class="grid" style="grid-template-columns: repeat(3, 1fr);">
    <!-- Stats Cards -->
    <div class="card">
        <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted); font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">
            <i data-lucide="calendar" style="width: 14px;"></i>
            Lectures Today
        </div>
        <div style="font-size: 2.25rem; font-weight: 700; color: var(--text-main); margin-top: 1rem;">{{ count($upcomingLectures) }}</div>
        <div style="background: rgba(16, 185, 129, 0.1); color: #10b981; font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 99px; display: inline-block; margin-top: 1rem;">Active Sessions</div>
    </div>

    <div class="card">
        <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted); font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">
            <i data-lucide="check-circle" style="width: 14px;"></i>
            Attendance Rate
        </div>
        <div style="font-size: 2.25rem; font-weight: 700; color: var(--text-main); margin-top: 1rem;">85%</div>
        <div style="color: var(--text-muted); font-size: 0.75rem; margin-top: 1rem;">+2.4% from last week</div>
    </div>

    <div class="card">
        <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted); font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">
            <i data-lucide="message-square" style="width: 14px;"></i>
            Pending Leaves
        </div>
        <div style="font-size: 2.25rem; font-weight: 700; color: var(--text-main); margin-top: 1rem;">0</div>
        <div style="color: var(--text-muted); font-size: 0.75rem; margin-top: 1rem;">No active requests</div>
    </div>
</div>

<div class="grid" style="margin-top: 2rem; grid-template-columns: 1.8fr 1.2fr;">
    <!-- Classes List -->
    <div class="card" style="padding: 0;">
        <div style="padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1rem; font-weight: 600;">Upcoming Lectures</h3>
            <a href="{{ route('faculty.timetable') }}" style="color: var(--accent); font-size: 0.8rem; text-decoration: none; font-weight: 600;">View full schedule</a>
        </div>
        <div class="list" style="padding: 0.75rem;">
            @forelse($upcomingLectures as $lecture)
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 0.75rem; border-radius: 0.5rem; transition: background 0.2s; margin-bottom: 0.25rem;">
                <div style="display: flex; align-items: center; gap: 1.25rem;">
                    <div style="width: 48px; height: 48px; background: var(--subtle-bg); border: 1px solid var(--border); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.7rem; color: var(--text-muted);">
                        CODE
                    </div>
                    <div>
                        <div style="font-size: 0.95rem; font-weight: 600; color: var(--text-main);">{{ $lecture->subject->name ?? 'Course Title' }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.2rem;">
                            Time: {{ date('h:i A', strtotime($lecture->start_time)) }} — Room {{ $lecture->classroom->name ?? '00' }} ({{ $lecture->classroom->block->name ?? 'Main' }} Block)
                        </div>
                        <div style="display: flex; gap: 0.5rem; margin-top: 0.4rem;">
                            <span style="font-size: 0.7rem; background: var(--subtle-bg); border: 1px solid var(--border); padding: 0.1rem 0.4rem; border-radius: 4px; color: var(--accent);">{{ $lecture->lecture_type }}</span>
                            <span style="font-size: 0.7rem; background: var(--subtle-bg); border: 1px solid var(--border); padding: 0.1rem 0.4rem; border-radius: 4px; color: var(--text-muted);">Sec: {{ $lecture->section->name ?? 'A' }}</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('faculty.attendance', ['schedule' => $lecture->id]) }}" class="btn-primary" style="background: var(--text-main); color: var(--bg); font-size: 0.75rem; padding: 0.5rem 1rem;">
                    {{ $lecture->attendance_taken ? 'Update Attendance' : 'Mark Attendance' }}
                </a>
            </div>
            @empty
            <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
                <i data-lucide="book-open" style="width: 32px; height: 32px; opacity: 0.2; margin-bottom: 1rem;"></i>
                <p style="font-size: 0.85rem;">No lectures scheduled for today.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Events Section -->
    <div class="card" style="padding: 0;">
        <div style="padding: 1.5rem; border-bottom: 1px solid var(--border);">
            <h3 style="font-size: 1rem; font-weight: 600;">Campus Activity</h3>
        </div>
        <div style="padding: 1.5rem;">
            @forelse($events ?? [] as $event)
            <div style="position: relative; padding-left: 1.5rem; margin-bottom: 1.5rem; border-left: 2px solid var(--border);">
                <div style="position: absolute; left: -5px; top: 0; width: 8px; height: 8px; background: var(--accent); border-radius: 50%;"></div>
                <div style="font-size: 0.85rem; font-weight: 600; color: var(--text-main);">{{ $event->name }}</div>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">{{ date('j M, Y', strtotime($event->event_date)) }} | {{ $event->location ?? 'Campus Main' }}</div>
            </div>
            @empty
            <div style="padding: 1rem; text-align: center; color: var(--text-muted);">
                <p style="font-size: 0.8rem;">No events scheduled for this week.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
