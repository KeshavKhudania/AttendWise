@extends('layouts.club')

@section('header-title', 'Attendance Management')
@section('header-subtitle', 'Manage event attendance or start an ad-hoc session.')

@section('header-actions')
    @if($manager->hasPermission('attendance.take') || $manager->role === 'admin')
    <button type="button" onclick="openMethodModal('{{ route('club.attendance.start_adhoc') }}', 'post')" class="btn-create" style="background: var(--text-main); color: var(--bg); border: none; padding: 0.75rem 1.5rem; border-radius: 0.75rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
        <i data-lucide="play" style="width: 18px; height: 18px;"></i>
        Start Ad-Hoc Session
    </button>
    @endif
@endsection

@section('styles')
<style>
    .slot-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 0.5rem;
        margin-top: 0.5rem;
    }
    
    .slot-option {
        border: 1px solid var(--border);
        border-radius: 0.5rem;
        padding: 0.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        background: var(--bg);
        user-select: none;
    }
    
    .slot-option:hover {
        border-color: var(--text-main);
    }
    
    .slot-option.selected {
        background: var(--text-main);
        color: var(--bg);
        border-color: var(--text-main);
    }
    
    .slot-option.selected .slot-time {
        color: var(--bg) !important;
        opacity: 0.9;
    }

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
    .status-ongoing { background: rgba(16, 185, 129, 0.1); color: #10b981; }
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

    .method-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(4px);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .method-modal-content {
        background: var(--card-bg);
        border: 1px solid var(--border);
        padding: 2rem;
        border-radius: 1rem;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }

    .method-option {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border: 1px solid var(--border);
        border-radius: 0.75rem;
        margin-bottom: 1rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .method-option:hover {
        border-color: var(--text-main);
        background: var(--subtle-bg);
    }

    .method-option.selected {
        border-color: #10b981;
        background: rgba(16, 185, 129, 0.1);
    }

    .method-option i {
        color: var(--text-main);
    }
    
    .method-option.selected i {
        color: #10b981;
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

    @if($events->count() > 0)
        <div class="events-grid">
            @foreach($events as $event)
            <div class="event-card">
                <div>
                    <h3 class="event-title">{{ $event->name }}</h3>
                    <div class="event-meta">
                        <i data-lucide="calendar"></i>
                        {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                    </div>
                    @if($event->start_time && $event->end_time)
                    <div class="event-meta">
                        <i data-lucide="clock"></i>
                        {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}
                    </div>
                    @endif
                    
                    <span class="event-status status-{{ $event->status }}">
                        {{ $event->status }}
                    </span>
                </div>
                
                @if($manager->hasPermission('attendance.take') || $manager->role === 'admin')
                    <a href="javascript:void(0)" onclick="openMethodModal('{{ route('club.attendance.init_event', $event->id) }}', 'get')" class="btn-action">
                        <i data-lucide="clipboard-edit" style="width: 18px;"></i> Manage Attendance
                    </a>
                @else
                    <a href="{{ route('club.attendance.init_event', $event->id) }}" class="btn-action">
                        <i data-lucide="eye" style="width: 18px;"></i> View Attendance
                    </a>
                @endif
            </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i data-lucide="calendar-x" style="width: 48px; height: 48px; opacity: 0.5; margin-bottom: 1rem;"></i>
            <h3>No Events Found</h3>
            <p style="margin-top: 0.5rem; max-width: 400px; margin-left: auto; margin-right: auto;">
                Create events from the Events page first to take attendance for them.
            </p>
            <a href="{{ route('club.events') }}" class="btn-action" style="display: inline-flex; width: auto; padding: 0.75rem 1.5rem; margin-top: 1.5rem;">
                Go to Events
            </a>
        </div>
    @endif
    
    @if(isset($adhoc_sessions) && ($adhoc_sessions->count() > 0 || request()->has('from_date')))
        <div style="display: flex; justify-content: space-between; align-items: center; margin: 2.5rem 0 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin: 0;">Ad-Hoc Sessions</h2>
            
            <form action="{{ route('club.attendance') }}" method="GET" style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                <input type="date" name="from_date" value="{{ request('from_date') }}" style="padding: 0.5rem; border-radius: 0.5rem; border: 1px solid var(--border); background: var(--card-bg); color: var(--text-main); font-size: 0.85rem;">
                <span style="color: var(--text-muted); font-size: 0.85rem;">to</span>
                <input type="date" name="to_date" value="{{ request('to_date') }}" style="padding: 0.5rem; border-radius: 0.5rem; border: 1px solid var(--border); background: var(--card-bg); color: var(--text-main); font-size: 0.85rem;">
                <button type="submit" style="background: var(--text-main); color: var(--bg); border: none; padding: 0.5rem 1rem; border-radius: 0.5rem; cursor: pointer; font-weight: 600; font-size: 0.85rem;">Filter</button>
                @if(request()->has('from_date') || request()->has('to_date'))
                <a href="{{ route('club.attendance') }}" style="background: var(--subtle-bg); border: 1px solid var(--border); color: var(--text-main); padding: 0.5rem 1rem; border-radius: 0.5rem; text-decoration: none; font-weight: 600; font-size: 0.85rem;">Clear</a>
                @endif
            </form>
        </div>
        <div class="events-grid">
            @foreach($adhoc_sessions as $session)
            <div class="event-card">
                <div>
                    <h3 class="event-title">Ad-Hoc Session</h3>
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
                
                <a href="{{ route('club.attendance.manage', $session->id) }}" class="btn-action">
                    @if($manager->hasPermission('attendance.take') || $manager->role === 'admin')
                        <i data-lucide="clipboard-edit" style="width: 18px;"></i> Manage Attendance
                    @else
                        <i data-lucide="eye" style="width: 18px;"></i> View Attendance
                    @endif
                </a>
            </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Method Selection Modal -->
<div id="methodModal" class="method-modal">
    <div class="method-modal-content">
        <h3 style="margin-top: 0; margin-bottom: 1.5rem; color: var(--text-main); font-size: 1.25rem;">Select Attendance Method</h3>
        
        <div class="method-option selected" data-method="qr" onclick="selectMethod('qr', this)">
            <i data-lucide="qr-code" style="width: 24px; height: 24px;"></i>
            <div>
                <div style="font-weight: 600; color: var(--text-main);">QR Code</div>
                <div style="font-size: 0.8rem; color: var(--text-muted);">Students scan a dynamic QR code</div>
            </div>
        </div>
        
        <div class="method-option" data-method="geo" onclick="selectMethod('geo', this)">
            <i data-lucide="map-pin" style="width: 24px; height: 24px;"></i>
            <div>
                <div style="font-weight: 600; color: var(--text-main);">Geo-Location</div>
                <div style="font-weight: 400; font-size: 0.8rem; color: var(--text-muted);">Require students to be in physical proximity</div>
            </div>
        </div>
        
        <div class="method-option" data-method="manual" onclick="selectMethod('manual', this)">
            <i data-lucide="check-square" style="width: 24px; height: 24px;"></i>
            <div>
                <div style="font-weight: 600; color: var(--text-main);">Manual</div>
                <div style="font-weight: 400; font-size: 0.8rem; color: var(--text-muted);">Mark attendance manually from a list</div>
            </div>
        </div>
        
        <div id="lectureSlotContainer" style="display: block; margin-top: 1.5rem; text-align: left;">
            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.5rem; text-transform: uppercase;">Link to Timing Slot (Optional)</label>
            <div class="slot-grid">
                @foreach($slot_timings as $index => $timing)
                    <div class="slot-option" data-slot-id="{{ $index }}" onclick="toggleSlot(this)">
                        <div style="font-weight: 600; font-size: 0.85rem;">Slot {{ $index }}</div>
                        <div class="slot-time" style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">{{ \Carbon\Carbon::parse($timing['start'])->format('h:i A') }} - {{ \Carbon\Carbon::parse($timing['end'])->format('h:i A') }}</div>
                    </div>
                @endforeach
            </div>
            <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem; line-height: 1.4;">If selected, attendance will be linked to this timing slot for later academic tracking.</p>
        </div>
        
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button onclick="closeMethodModal()" style="flex: 1; padding: 0.75rem; border-radius: 0.75rem; border: 1px solid var(--border); background: transparent; color: var(--text-main); font-weight: 600; cursor: pointer;">Cancel</button>
            <button onclick="startSession()" style="flex: 1; padding: 0.75rem; border-radius: 0.75rem; border: none; background: var(--text-main); color: var(--bg); font-weight: 600; cursor: pointer;">Start Session</button>
        </div>
        
        <!-- Hidden form for submission -->
        <form id="startSessionForm" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="method" id="selectedMethodInput" value="qr">
            <!-- timing_slot_ids inputs will be dynamically appended here -->
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let currentTargetUrl = '';
    let currentMethodType = 'post';
    let selectedMethod = 'qr';

    function selectMethod(method, element) {
        selectedMethod = method;
        document.querySelectorAll('.method-option').forEach(el => el.classList.remove('selected'));
        element.classList.add('selected');
        document.getElementById('selectedMethodInput').value = method;
    }

    function toggleSlot(element) {
        element.classList.toggle('selected');
    }

    function openMethodModal(url, type) {
        currentTargetUrl = url;
        currentMethodType = type;
        
        // Reset selections
        document.querySelectorAll('.slot-option').forEach(el => el.classList.remove('selected'));
        
        document.getElementById('methodModal').style.display = 'flex';
    }

    function closeMethodModal() {
        document.getElementById('methodModal').style.display = 'none';
    }

    function startSession() {
        const scheduleVals = Array.from(document.querySelectorAll('.slot-option.selected')).map(el => el.getAttribute('data-slot-id'));
        if(currentMethodType === 'post') {
            const form = document.getElementById('startSessionForm');
            
            // Clear existing timing inputs
            form.querySelectorAll('.timing-input').forEach(el => el.remove());
            
            // Append new timing inputs
            scheduleVals.forEach(val => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'timing_slot_ids[]';
                input.value = val;
                input.classList.add('timing-input');
                form.appendChild(input);
            });
            
            form.action = currentTargetUrl;
            form.submit();
        } else {
            let queryParams = '?method=' + selectedMethod;
            scheduleVals.forEach(val => {
                queryParams += '&timing_slot_ids[]=' + encodeURIComponent(val);
            });
            window.location.href = currentTargetUrl + queryParams;
        }
    }
    
    // Close modal if clicked outside
    document.getElementById('methodModal').addEventListener('click', function(e) {
        if(e.target === this) {
            closeMethodModal();
        }
    });
</script>
@endsection
