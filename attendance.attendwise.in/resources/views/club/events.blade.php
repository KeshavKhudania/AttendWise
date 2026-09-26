@extends('layouts.club')

@section('header-title', 'Events')
@section('header-subtitle', 'Manage upcoming and past events for your club.')

@section('styles')
<link href="{{ asset('css/searchable-select.css') }}" rel="stylesheet">
<style>
    .events-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .btn-create {
        background: var(--text-main);
        color: var(--bg);
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.15);
    }

    /* Cards Grid */
    .events-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
    }

    .event-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 1.25rem;
        padding: 1.5rem;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
    }

    .event-card:hover {
        border-color: var(--text-muted);
        box-shadow: 0 8px 24px rgba(0,0,0,0.04);
        transform: translateY(-2px);
    }

    .event-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .event-icon {
        width: 48px;
        height: 48px;
        border-radius: 1rem;
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(59, 130, 246, 0.2);
    }

    .status-badge {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: 0.25rem 0.6rem;
        border-radius: 1rem;
    }

    .status-upcoming { background: rgba(59, 130, 246, 0.1); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.2); }
    .status-ongoing { background: rgba(34, 197, 94, 0.1); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.2); }
    .status-completed { background: rgba(107, 114, 128, 0.1); color: #6b7280; border: 1px solid rgba(107, 114, 128, 0.2); }
    .status-cancelled { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }

    .event-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 0.25rem;
    }

    .event-desc {
        font-size: 0.85rem;
        color: var(--text-muted);
        line-height: 1.4;
        min-height: 2.8em;
    }

    .event-meta {
        margin-top: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    .event-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.5rem;
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border);
    }

    .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 1px solid var(--border);
        background: transparent;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-action:hover {
        background: var(--subtle-bg);
        color: var(--text-main);
    }
    
    .btn-action.delete:hover {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border-color: rgba(239, 68, 68, 0.2);
    }

    /* Modal Styles */
    .modal-overlay {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.6); backdrop-filter: blur(5px);
        display: none; align-items: center; justify-content: center; z-index: 100;
        padding: 1rem;
    }
    .modal-overlay.active { display: flex; }
    
    .modal-content {
        background: var(--card-bg); 
        width: 100%; 
        max-width: 650px; 
        border-radius: 1.25rem; 
        border: 1px solid var(--border); 
        display: flex;
        flex-direction: column;
        animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        max-height: 90vh;
    }

    @keyframes slideUp {
        from { transform: translateY(20px) scale(0.98); opacity: 0; }
        to { transform: translateY(0) scale(1); opacity: 1; }
    }

    .modal-header {
        padding: 1.5rem 2rem; 
        border-bottom: 1px solid var(--border);
        display: flex; justify-content: space-between; align-items: center;
    }
    
    .modal-header h3 { font-size: 1.25rem; font-weight: 700; }
    .close-btn { background: var(--subtle-bg); border: 1px solid var(--border); border-radius: 50%; width: 32px; height: 32px; color: var(--text-muted); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
    .close-btn:hover { color: var(--text-main); background: var(--border); }
    
    .modal-body { 
        padding: 2rem; 
        overflow-y: auto;
        flex: 1;
        min-height: 0;
    }
    
    .modal-footer {
        padding: 1.5rem 2rem; 
        border-top: 1px solid var(--border);
        display: flex; justify-content: flex-end; gap: 1rem;
        background: var(--card-bg);
        border-radius: 0 0 1.25rem 1.25rem;
    }

    .form-group { margin-bottom: 1.5rem; }
    .form-row { display: flex; gap: 1rem; margin-bottom: 1.5rem; }
    .form-row .form-group { flex: 1; margin-bottom: 0; }
    
    .form-label { display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main); }
    .form-control { width: 100%; padding: 0.85rem 1rem; border: 1px solid var(--border); background: var(--subtle-bg); color: var(--text-main); border-radius: 0.75rem; font-size: 0.95rem; transition: border-color 0.2s; }
    .form-control:focus { outline: none; border-color: var(--text-muted); }
    textarea.form-control { resize: vertical; min-height: 80px; }

    .btn-secondary { background: var(--subtle-bg); color: var(--text-main); border: 1px solid var(--border); padding: 0.75rem 1.5rem; border-radius: 0.75rem; font-weight: 600; cursor: pointer; }
    .btn-primary { background: var(--text-main); color: var(--bg); border: none; padding: 0.75rem 1.5rem; border-radius: 0.75rem; font-weight: 600; cursor: pointer; }
</style>
@endsection

@section('content')

<div class="events-header">
    <div>
        <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.25rem;">Club Events</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Schedule and manage events hosted by your club.</p>
    </div>
    <button class="btn-create" onclick="openModal('create')">
        <i data-lucide="plus" style="width: 18px; height: 18px;"></i>
        Create Event
    </button>
</div>

@if($events->count() > 0)
    <div class="events-grid">
        @foreach($events as $event)
        <div class="event-card">
            <div class="event-header">
                <div class="event-icon">
                    <i data-lucide="calendar" style="width: 24px; height: 24px;"></i>
                </div>
                <div class="status-badge status-{{ strtolower($event->status) }}">
                    {{ ucfirst($event->status) }}
                </div>
            </div>
            
            <div class="event-title">{{ $event->name }}</div>
            <div class="event-desc">{{ Str::limit($event->description, 80) ?: 'No description provided.' }}</div>
            
            <div class="event-meta">
                <div class="meta-item">
                    <i data-lucide="calendar-days" style="width: 16px; height: 16px;"></i>
                    {{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }}
                </div>
                <div class="meta-item">
                    <i data-lucide="clock" style="width: 16px; height: 16px;"></i>
                    {{ $event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('h:i A') : 'TBA' }}
                    @if($event->end_time)
                        - {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}
                    @endif
                </div>
                <div class="meta-item">
                    <i data-lucide="map-pin" style="width: 16px; height: 16px;"></i>
                    @if($event->eventVenue)
                        {{ $event->eventVenue->name }}
                    @elseif($event->eventBlock)
                        {{ $event->eventBlock->name }} (Block)
                    @elseif($event->eventClassroom)
                        {{ $event->eventClassroom->name }} (Classroom)
                    @else
                        Location TBA
                    @endif
                </div>
            </div>

            <div class="event-footer">
                <button class="btn-action" onclick="openModal('edit', {{ json_encode($event) }})" title="Edit Event">
                    <i data-lucide="edit-2" style="width: 16px; height: 16px;"></i>
                </button>
                <form action="{{ route('club.events.remove', $event->id) }}" method="POST" onsubmit="return confirm('Delete this event?');" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-action delete" title="Delete Event">
                        <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div style="padding: 6rem 2rem; text-align: center; background: var(--card-bg); border: 1px dashed var(--border); border-radius: 1.5rem;">
        <div style="width: 72px; height: 72px; background: var(--subtle-bg); border-radius: 2rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; border: 1px solid var(--border);">
            <i data-lucide="calendar" style="width: 32px; height: 32px; color: var(--text-muted);"></i>
        </div>
        <h4 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.5rem;">No Events Scheduled</h4>
        <p style="font-size: 0.95rem; color: var(--text-muted); max-width: 400px; margin: 0 auto 1.5rem;">Host your first club event by scheduling it here.</p>
        <button class="btn-create" onclick="openModal('create')">
            <i data-lucide="plus" style="width: 18px; height: 18px;"></i>
            Create Event
        </button>
    </div>
@endif

<!-- Shared Modal for Create & Edit -->
<div class="modal-overlay" id="eventModal">
    <form id="eventForm" method="POST" action="" class="modal-content">
        @csrf
        <div class="modal-header">
            <h3 id="modalTitle">Create Event</h3>
            <button type="button" class="close-btn" onclick="closeModal()">
                <i data-lucide="x" style="width: 18px; height: 18px;"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">Event Name <span style="color: #ef4444;">*</span></label>
                <input type="text" name="name" id="formName" class="form-control" placeholder="e.g. Annual Tech Symposium" required>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" id="formDescription" class="form-control" placeholder="Describe the event..."></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Event Date <span style="color: #ef4444;">*</span></label>
                    <input type="date" name="event_date" id="formDate" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Status <span style="color: #ef4444;">*</span></label>
                    <select name="status" id="formStatus" class="form-control" required>
                        <option value="upcoming">Upcoming</option>
                        <option value="ongoing">Ongoing</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Start Time</label>
                    <input type="time" name="start_time" id="formStartTime" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">End Time</label>
                    <input type="time" name="end_time" id="formEndTime" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Location</label>
                <select name="location" id="formLocation" class="form-control" data-ajax-url="{{ route('club.locations.search') }}">
                    <option value="">Select a Location (TBA)</option>
                </select>
            </div>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn-secondary" onclick="closeModal()">Cancel</button>
            <button type="submit" class="btn-primary" id="submitBtn">Create Event</button>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script src="{{ asset('js/searchable-select.js') }}"></script>
<script>
    let locationSelect;

    document.addEventListener('DOMContentLoaded', function() {
        locationSelect = new CustomSearchableSelect('#formLocation');
    });

    function openModal(type, event = null) {
        const modal = document.getElementById('eventModal');
        const form = document.getElementById('eventForm');
        const title = document.getElementById('modalTitle');
        const btn = document.getElementById('submitBtn');
        
        form.reset();
        
        if(type === 'create') {
            title.textContent = 'Create New Event';
            btn.textContent = 'Create Event';
            form.action = "{{ route('club.events.add') }}";
        } else if (type === 'edit' && event) {
            title.textContent = 'Edit Event';
            btn.textContent = 'Save Changes';
            form.action = `/club/events/${event.id}/update`;
            
            document.getElementById('formName').value = event.name || '';
            document.getElementById('formDescription').value = event.description || '';
            document.getElementById('formDate').value = event.event_date ? event.event_date.split('T')[0] : '';
            document.getElementById('formStatus').value = event.status || 'upcoming';
            
            // Time fields
            if(event.start_time) {
                // Remove seconds if present
                document.getElementById('formStartTime').value = event.start_time.substring(0, 5);
            }
            if(event.end_time) {
                document.getElementById('formEndTime').value = event.end_time.substring(0, 5);
            }
            
            let locVal = '';
            let locName = 'Location TBA';
            
            if (event.venue_id) {
                locVal = 'venue_' + event.venue_id;
                locName = event.event_venue ? event.event_venue.name : 'Selected Venue';
            } else if (event.block_id) {
                locVal = 'block_' + event.block_id;
                locName = event.event_block ? event.event_block.name + ' (Block)' : 'Selected Block';
            } else if (event.classroom_id) {
                locVal = 'classroom_' + event.classroom_id;
                locName = event.event_classroom ? event.event_classroom.name + ' (Classroom)' : 'Selected Classroom';
            }
            
            if (locationSelect) {
                locationSelect.setValue(locVal, locName);
            } else {
                document.getElementById('formLocation').value = locVal;
            }
        } else {
            if (locationSelect) locationSelect.clear();
        }
        
        modal.classList.add('active');
        document.body.style.overflow = 'hidden'; 
    }

    function closeModal() {
        document.getElementById('eventModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    document.getElementById('eventModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
</script>
@endsection
