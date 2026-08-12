@extends('layouts.faculty')

@section('header-title', 'Overview')
@section('header-subtitle', 'Welcome back, ' . explode(' ', $faculty->name)[0] . '! Track your performance and manage class schedules.')

@section('styles')
<style>
    .modern-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 1rem;
        padding: 1.75rem;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02), 0 2px 4px -2px rgba(0,0,0,0.02);
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }
    
    .modern-card:hover {
        border-color: var(--text-muted);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05), 0 4px 6px -4px rgba(0,0,0,0.05);
    }

    .stat-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        transition: transform 0.2s ease;
    }
    
    .modern-card:hover .stat-icon-wrapper {
        transform: translateY(-2px);
    }

    .stat-icon-wrapper.success {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .stat-icon-wrapper.warning {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .lecture-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem;
        border-radius: 0.75rem;
        background: transparent;
        border: 1px solid var(--border);
        transition: all 0.2s ease;
        margin-bottom: 0.75rem;
        position: relative;
    }
    
    .lecture-item:hover {
        background: var(--subtle-bg);
        border-color: var(--text-muted);
    }

    .lecture-status-indicator {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 0%;
        border-radius: 0 4px 4px 0;
        background: #10b981;
        transition: height 0.3s ease;
    }
    
    .lecture-item.taken .lecture-status-indicator {
        height: 60%;
    }

    .lecture-item.pending .lecture-status-indicator {
        height: 60%;
        background: #f59e0b;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 1.5rem;
        margin-top: 2rem;
    }
    
    .col-span-8 {
        grid-column: span 8;
    }
    
    .col-span-4 {
        grid-column: span 4;
    }
    
    @media (max-width: 1024px) {
        .col-span-8, .col-span-4 {
            grid-column: span 12;
        }
    }
    
    .event-timeline-item {
        position: relative;
        padding-left: 2rem;
        padding-bottom: 1.75rem;
        border-left: 1px solid var(--border);
        transition: all 0.2s ease;
    }
    
    .event-timeline-item:hover {
        border-left-color: var(--text-muted);
    }
    
    .event-timeline-item:last-child {
        border-left-color: transparent;
        padding-bottom: 0;
    }
    
    .event-timeline-dot {
        position: absolute;
        left: -5.5px;
        top: 0.25rem;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--bg);
        border: 2px solid var(--text-muted);
        transition: all 0.2s ease;
    }
    
    .event-timeline-item:hover .event-timeline-dot {
        border-color: var(--text-main);
        background: var(--text-main);
    }

    .btn-modern {
        background: var(--text-main);
        color: var(--bg);
        border: 1px solid transparent;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 500;
        font-size: 0.8rem;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-modern:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    .btn-modern.success {
        background: #10b981;
        color: white;
    }
    
    .btn-modern.success:hover {
        background: #059669;
    }
    
    .btn-modern.outline {
        background: transparent;
        color: var(--text-main);
        border: 1px solid var(--border);
    }
    
    .btn-modern.outline:hover {
        border-color: var(--text-main);
        background: var(--subtle-bg);
    }
    
    .progress-bar-container {
        width: 100%;
        height: 4px;
        background: var(--subtle-bg);
        border-radius: 99px;
        overflow: hidden;
        margin-top: 1rem;
    }
    
    .progress-bar-fill {
        height: 100%;
        background: var(--text-main);
        border-radius: 99px;
        transition: width 1s ease-out;
    }
    
    .badge {
        font-size: 0.65rem;
        padding: 0.2rem 0.5rem;
        border-radius: 0.35rem;
        font-weight: 500;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        border: 1px solid var(--border);
        background: var(--subtle-bg);
        color: var(--text-muted);
    }
    
    .badge-blue {
        color: #3b82f6;
        border-color: rgba(59, 130, 246, 0.2);
    }
    
    .badge-green {
        color: #10b981;
        border-color: rgba(16, 185, 129, 0.2);
    }
    
    .animate-fade-in {
        animation: fadeIn 0.4s ease-out forwards;
        opacity: 0;
        transform: translateY(5px);
    }
    
    @keyframes fadeIn {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection

@section('content')
<div class="grid" style="grid-template-columns: repeat(3, 1fr);">
    <!-- Stats Cards -->
    <div class="modern-card animate-fade-in" style="animation-delay: 0.1s;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="color: var(--text-muted); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Lectures Today</div>
                <div style="font-size: 2.5rem; font-weight: 800; color: var(--text-main); margin-top: 0.5rem; line-height: 1;">
                    {{ count($upcomingLectures) }}
                </div>
                <div style="margin-top: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <span class="badge badge-green">
                        <i data-lucide="trending-up" style="width: 12px; height: 12px; display: inline; vertical-align: text-bottom;"></i> Active
                    </span>
                    <span style="font-size: 0.8rem; color: var(--text-muted);">Scheduled</span>
                </div>
            </div>
            <div class="stat-icon-wrapper">
                <i data-lucide="calendar" style="width: 24px; height: 24px;"></i>
            </div>
        </div>
    </div>

    <div class="modern-card animate-fade-in" style="animation-delay: 0.2s;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div style="width: 100%;">
                <div style="color: var(--text-muted); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Attendance Rate</div>
                <div style="display: flex; align-items: baseline; gap: 0.5rem; margin-top: 0.5rem;">
                    <div style="font-size: 2.5rem; font-weight: 800; color: var(--text-main); line-height: 1;">85%</div>
                    <div style="color: #10b981; font-size: 0.85rem; font-weight: 600;">+2.4%</div>
                </div>
                <div class="progress-bar-container">
                    <div class="progress-bar-fill" style="width: 0%;" data-target="85%"></div>
                </div>
            </div>
            <div class="stat-icon-wrapper success">
                <i data-lucide="check-circle" style="width: 24px; height: 24px;"></i>
            </div>
        </div>
    </div>

    <div class="modern-card animate-fade-in" style="animation-delay: 0.3s;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="color: var(--text-muted); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Pending Leaves</div>
                <div style="font-size: 2.5rem; font-weight: 800; color: var(--text-main); margin-top: 0.5rem; line-height: 1;">0</div>
                <div style="margin-top: 1rem; color: var(--text-muted); font-size: 0.85rem;">
                    No active requests
                </div>
            </div>
            <div class="stat-icon-wrapper warning">
                <i data-lucide="message-square" style="width: 24px; height: 24px;"></i>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <!-- Classes List -->
    <div class="col-span-8">
        <div class="modern-card animate-fade-in" style="animation-delay: 0.4s; padding: 0;">
            <div style="padding: 1.5rem 1.75rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1.1rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="clock" style="width: 18px; color: var(--text-muted);"></i>
                    Today's Schedule
                </h3>
                <a href="{{ route('faculty.timetable') }}" class="btn-modern outline" style="padding: 0.4rem 0.8rem; font-size: 0.75rem;">View Full</a>
            </div>
            <div style="padding: 1.25rem;">
                @forelse($upcomingLectures as $lecture)
                <div class="lecture-item {{ $lecture->attendance_taken ? 'taken' : 'pending' }}">
                    <div class="lecture-status-indicator"></div>
                    <div style="display: flex; align-items: center; gap: 1.25rem;">
                        <div style="width: 54px; height: 54px; background: var(--card-bg); border: 1px solid var(--border); border-radius: 12px; display: flex; flex-direction: column; align-items: center; justify-content: center; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                            <span style="font-weight: 800; font-size: 0.9rem; color: var(--text-main);">{{ date('h:i', strtotime($lecture->start_time)) }}</span>
                            <span style="font-size: 0.65rem; color: var(--text-muted); font-weight: 600;">{{ date('A', strtotime($lecture->start_time)) }}</span>
                        </div>
                        <div>
                            <div style="font-size: 1.05rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.2rem;">{{ $lecture->subject->name ?? 'Course Title' }}</div>
                            <div style="font-size: 0.85rem; color: var(--text-muted); display: flex; align-items: center; gap: 0.4rem;">
                                <i data-lucide="map-pin" style="width: 12px; height: 12px;"></i>
                                Room {{ $lecture->classroom->name ?? '00' }} &bull; {{ $lecture->classroom->block->name ?? 'Main' }} Block
                            </div>
                            <div style="display: flex; gap: 0.5rem; margin-top: 0.6rem;">
                                <span class="badge badge-blue">{{ $lecture->lecture_type }}</span>
                                <span class="badge badge-green">Sec {{ $lecture->section->name ?? 'A' }}</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('faculty.attendance', ['schedule' => $lecture->id]) }}" class="btn-modern {{ $lecture->attendance_taken ? 'success' : '' }}">
                            <i data-lucide="{{ $lecture->attendance_taken ? 'check-circle' : 'users' }}" style="width: 16px;"></i>
                            {{ $lecture->attendance_taken ? 'Update' : 'Mark' }}
                        </a>
                    </div>
                </div>
                @empty
                <div style="padding: 4rem 2rem; text-align: center;">
                    <div style="width: 64px; height: 64px; background: var(--subtle-bg); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; border: 1px solid var(--border);">
                        <i data-lucide="coffee" style="width: 32px; height: 32px; color: var(--text-muted);"></i>
                    </div>
                    <h4 style="font-size: 1.1rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.5rem;">No scheduled lectures</h4>
                    <p style="font-size: 0.9rem; color: var(--text-muted);">Enjoy your free time or prepare for upcoming classes.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Events Section -->
    <div class="col-span-4">
        <div class="modern-card animate-fade-in" style="animation-delay: 0.5s; padding: 0; height: 100%;">
            <div style="padding: 1.5rem 1.75rem; border-bottom: 1px solid var(--border);">
                <h3 style="font-size: 1.1rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="zap" style="width: 18px; color: var(--text-muted);"></i>
                    Campus Activity
                </h3>
            </div>
            <div style="padding: 1.75rem;">
                @forelse($events ?? [] as $event)
                <div class="event-timeline-item">
                    <div class="event-timeline-dot"></div>
                    <div style="font-size: 0.95rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.3rem;">{{ $event->name }}</div>
                    <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                        <span style="font-size: 0.8rem; color: var(--text-main); font-weight: 500;">
                            <i data-lucide="calendar" style="width: 12px; display: inline-block; vertical-align: text-bottom; margin-right: 0.2rem; color: var(--text-muted);"></i>
                            {{ date('l, j M Y', strtotime($event->event_date)) }}
                        </span>
                        <span style="font-size: 0.8rem; color: var(--text-muted);">
                            <i data-lucide="map-pin" style="width: 12px; display: inline-block; vertical-align: text-bottom; margin-right: 0.2rem;"></i>
                            {{ $event->location ?? 'Campus Main' }}
                        </span>
                    </div>
                </div>
                @empty
                <div style="padding: 2rem 1rem; text-align: center; color: var(--text-muted);">
                    <i data-lucide="calendar-off" style="width: 40px; height: 40px; opacity: 0.3; margin-bottom: 1rem;"></i>
                    <p style="font-size: 0.9rem;">No major events scheduled for this week.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Trigger progress bar animations
        setTimeout(() => {
            document.querySelectorAll('.progress-bar-fill').forEach(bar => {
                bar.style.width = bar.getAttribute('data-target');
            });
        }, 500);
    });
</script>
@endsection
