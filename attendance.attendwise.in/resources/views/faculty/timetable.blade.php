@extends('layouts.faculty')

@section('header-title', 'Weekly Schedule')
@section('header-subtitle', 'Your comprehensive class and laboratory timetable.')

@section('content')
<div class="card" style="padding: 0; overflow: hidden; border-radius: 1rem;">
    <!-- Timetable Header -->
    <div style="background: var(--subtle-bg); border-bottom: 1px solid var(--border); padding: 1.5rem 2.5rem; display: flex; justify-content: space-between; align-items: center;">
        <h3 style="font-size: 1rem; font-weight: 700; letter-spacing: -0.02em;">Academic Calendar</h3>
        <div style="display: flex; gap: 0.5rem;">
            <button class="btn-primary" style="background: transparent; color: var(--text-main); border: 1px solid var(--border); font-size: 0.8rem;">
                <i data-lucide="printer" style="width: 14px;"></i>
                Print
            </button>
            <button class="btn-primary" style="font-size: 0.8rem;">
                <i data-lucide="download" style="width: 14px;"></i>
                Export PDF
            </button>
        </div>
    </div>

    <!-- Timetable Body -->
    <div style="overflow-x: auto; padding: 1.5rem 1rem;">
        <table style="width: 100%; border-collapse: collapse; table-layout: fixed; min-width: 900px;">
            <thead>
                <tr>
                    <th style="width: 100px; padding: 1rem; border-bottom: 2px solid var(--border); font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; text-align: center;">Time</th>
                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                    <th style="padding: 1rem; border-bottom: 2px solid var(--border); font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; text-align: left;">{{ substr($day, 0, 3) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($times as $time)
                <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 1.5rem 1rem; text-align: center; color: var(--text-main); font-size: 0.85rem; font-weight: 700; border-right: 1px solid var(--border);">
                        {{ date('h:i A', strtotime($time)) }}
                    </td>
                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $dayName)
                    <td style="padding: 0.5rem; vertical-align: top;">
                        @php
                            $classes = isset($schedules[$dayName]) ? $schedules[$dayName]->where('start_time', $time) : collect();
                        @endphp
                        @foreach($classes as $sched)
                        <div style="background: var(--subtle-bg); border: 1px solid var(--border); padding: 0.75rem; border-radius: 0.6rem; display: flex; flex-direction: column; gap: 0.4rem; transition: border-color 0.2s; margin-bottom: 0.4rem;">
                            <div style="font-weight: 700; font-size: 0.8rem; color: var(--text-main); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $sched->subject->name ?? 'Course' }}">
                                {{ $sched->subject->name ?? 'Course' }}
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 0.2rem; font-size: 0.65rem; color: var(--text-muted);">
                                <span><i data-lucide="map-pin" style="width: 10px; height: 10px; display: inline-block;"></i> {{ $sched->classroom->name ?? '00' }} ({{ $sched->classroom->block->name ?? '?' }})</span>
                                <span style="color: var(--accent); font-weight: 600;">{{ $sched->lecture_type }} | {{ $sched->section->name ?? '?' }}</span>
                            </div>
                        </div>
                        @endforeach
                    </td>
                    @endforeach
                </tr>
                @endforeach

                @if(count($times) == 0)
                <tr>
                    <td colspan="7" style="padding: 4rem; text-align: center; color: var(--text-muted);">
                        <i data-lucide="calendar-off" style="width: 48px; height: 48px; opacity: 0.2; margin-bottom: 1rem;"></i>
                        <p>No active schedules found for your profile.</p>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
