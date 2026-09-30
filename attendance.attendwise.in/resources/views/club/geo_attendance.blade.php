@extends('layouts.club')

@section('header-title', 'Geo Location Attendance')
@section('header-subtitle', 'Manage geo-location based attendance sessions.')

@section('header-actions')
    @if($manager->hasPermission('attendance.take') || $manager->role === 'admin')
    <form action="{{ route('club.attendance.geo.start') }}" method="POST">
        @csrf
        <button type="submit" class="btn-create" style="background: var(--text-main); color: var(--bg); border: none; padding: 0.75rem 1.5rem; border-radius: 0.75rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
            <i data-lucide="map-pin" style="width: 18px; height: 18px;"></i>
            Start Geo Session
        </button>
    </form>
    @endif
@endsection

@section('styles')
<style>
    .events-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    
    .event-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 1rem;
        padding: 1.5rem;
        transition: all 0.2s;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .event-card:hover {
        border-color: var(--text-muted);
        transform: translateY(-2px);
    }

    .event-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 0.5rem;
    }

    .event-meta {
        font-size: 0.85rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.3rem;
    }
    
    .event-meta i {
        width: 14px;
        height: 14px;
    }

    .event-status {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-top: 1rem;
    }

    .status-upcoming { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .status-active { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .status-completed { background: var(--subtle-bg); color: var(--text-muted); }
    
    .btn-action {
        width: 100%;
        margin-top: 1.5rem;
        background: var(--text-main);
        color: var(--bg);
        border: none;
        padding: 0.75rem;
        border-radius: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: opacity 0.2s;
        text-align: center;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-action:hover {
        opacity: 0.9;
        color: var(--bg);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--card-bg);
        border: 1px dashed var(--border);
        border-radius: 1rem;
        color: var(--text-muted);
    }
</style>
@endsection

@section('content')
<div class="content-inner">
    @if(session('success'))
        <div style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 1rem; border-radius: 0.75rem; margin-bottom: 1.5rem;">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 1rem; border-radius: 0.75rem; margin-bottom: 1.5rem;">
            {{ session('error') }}
        </div>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin: 0;">Geo-Location Sessions</h2>
        
        <form action="{{ route('club.attendance.geo') }}" method="GET" style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
            <input type="date" name="from_date" value="{{ request('from_date') }}" style="padding: 0.5rem; border-radius: 0.5rem; border: 1px solid var(--border); background: var(--card-bg); color: var(--text-main); font-size: 0.85rem;">
            <span style="color: var(--text-muted); font-size: 0.85rem;">to</span>
            <input type="date" name="to_date" value="{{ request('to_date') }}" style="padding: 0.5rem; border-radius: 0.5rem; border: 1px solid var(--border); background: var(--card-bg); color: var(--text-main); font-size: 0.85rem;">
            <button type="submit" style="background: var(--text-main); color: var(--bg); border: none; padding: 0.5rem 1rem; border-radius: 0.5rem; cursor: pointer; font-weight: 600; font-size: 0.85rem;">Filter</button>
            @if(request()->has('from_date') || request()->has('to_date'))
            <a href="{{ route('club.attendance.geo') }}" style="background: var(--subtle-bg); border: 1px solid var(--border); color: var(--text-main); padding: 0.5rem 1rem; border-radius: 0.5rem; text-decoration: none; font-weight: 600; font-size: 0.85rem;">Clear</a>
            @endif
        </form>
    </div>

    @if($geo_sessions->count() > 0)
        <div class="events-grid">
            @foreach($geo_sessions as $session)
            <div class="event-card">
                <div>
                    <h3 class="event-title">Geo Session</h3>
                    <div class="event-meta">
                        <i data-lucide="calendar"></i>
                        {{ \Carbon\Carbon::parse($session->date)->format('M d, Y') }}
                    </div>
                    <div class="event-meta">
                        <i data-lucide="clock"></i>
                        {{ \Carbon\Carbon::parse($session->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('h:i A') }}
                    </div>
                    
                    <span class="event-status status-{{ $session->status }}">
                        {{ $session->status }}
                    </span>
                </div>
                
                <a href="{{ route('club.attendance.geo.session', ['session_id' => $session->id]) }}" class="btn-action">
                    @if($manager->hasPermission('attendance.take') || $manager->role === 'admin')
                        <i data-lucide="clipboard-edit" style="width: 18px;"></i> Manage Geo Session
                    @else
                        <i data-lucide="eye" style="width: 18px;"></i> View Geo Session
                    @endif
                </a>
            </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i data-lucide="map-pin-off" style="width: 48px; height: 48px; opacity: 0.5; margin-bottom: 1rem;"></i>
            <h3>No Geo Sessions Found</h3>
            <p style="margin-top: 0.5rem; max-width: 400px; margin-left: auto; margin-right: auto;">
                Start a new geo-location session to begin taking attendance based on physical proximity.
            </p>
        </div>
    @endif
</div>
@endsection
