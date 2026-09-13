@extends('layouts.student')

@section('title', 'Club QR Session - AttendWise PWA')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<div class="glass-card" style="padding: 16px; margin-bottom: 16px; border-left: 4px solid #4f46e5;">
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h4 style="font-weight: 800; font-size: 1.1rem; color: var(--text-main); margin-bottom: 2px;">{{ $club->name }}</h4>
            <p style="color: var(--text-muted); font-size: 0.8rem; margin: 0;">Attendance Session</p>
        </div>
        <a href="{{ route('student.club.index') }}" style="background: rgba(0,0,0,0.05); color: var(--text-main); padding: 8px 12px; border-radius: 8px; font-weight: 700; font-size: 0.8rem; text-decoration: none;">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>
</div>

@if(!$existingSession)
<div id="init-session-container" class="glass-card" style="padding: 24px 16px; text-align: center;">
    <div style="width: 64px; height: 64px; border-radius: 16px; background: rgba(79, 70, 229, 0.1); color: #4f46e5; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 16px auto;">
        <i class="fa-solid fa-users-viewfinder"></i>
    </div>
    <h3 style="font-weight: 800; font-size: 1.2rem; color: var(--text-main); margin-bottom: 8px;">Choose Attendance Method</h3>
    <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 24px;">How would you like to take attendance today?</p>
    
    <div style="display: flex; flex-direction: column; gap: 12px;">
        <button type="button" onclick="openLectureModal('qr')" class="btn-primary" style="padding: 14px 24px; font-size: 1rem; border-radius: 12px; width: 100%; border: none; display: flex; align-items: center; justify-content: center; gap: 10px;">
            <i class="fa-solid fa-qrcode"></i> Start QR Session
        </button>
        <button type="button" onclick="openLectureModal('geo')" class="btn-primary" style="padding: 14px 24px; font-size: 1rem; border-radius: 12px; width: 100%; border: none; background: #0ea5e9; color: white; display: flex; align-items: center; justify-content: center; gap: 10px;">
            <i class="fa-solid fa-map-location-dot"></i> Geo-Location Remote Session
        </button>
        <button type="button" onclick="openLectureModal('manual')" class="btn-primary" style="padding: 14px 24px; font-size: 1rem; border-radius: 12px; width: 100%; border: 1px solid var(--border); background: var(--bg); color: var(--text-main); display: flex; align-items: center; justify-content: center; gap: 10px;">
            <i class="fa-solid fa-list-check"></i> Manual Checklist
        </button>
    </div>
</div>

<div id="lecture-selection-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 1000; align-items: center; justify-content: center; padding: 16px;">
    <div class="glass-card" style="width: 100%; max-width: 460px; padding: 24px; max-height: 85vh; overflow-y: auto; text-align: left;">
        <h3 style="font-weight: 800; font-size: 1.2rem; color: var(--text-main); margin-bottom: 8px;">Select Covered Lectures</h3>
        <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 20px;">Which academic lectures does this club session cover? Members attending will be automatically excused from these overlapping periods.</p>
        
        <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 24px;">
            @forelse($periods as $index => $period)
            <label style="display: flex; align-items: center; gap: 12px; background: var(--bg); padding: 12px; border-radius: 12px; border: 1px solid var(--border); cursor: pointer;">
                <input type="checkbox" class="lecture-period-checkbox" data-start="{{ $period->start_time }}" data-end="{{ $period->end_time }}" style="width: 18px; height: 18px; accent-color: #4f46e5;">
                <div>
                    <div style="font-weight: 700; font-size: 0.95rem; color: var(--text-main);">Lecture {{ $index + 1 }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ \Carbon\Carbon::parse($period->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($period->end_time)->format('h:i A') }}</div>
                </div>
            </label>
            @empty
            <div style="text-align: center; color: var(--text-muted); padding: 10px;">No scheduled lectures found for today.</div>
            @endforelse
        </div>
        
        <div id="additional-options-container" style="margin-bottom: 24px; display: none;">
            <!-- Venue / Location Selection -->
            <div style="margin-bottom: 16px;">
                <label style="font-size: 0.85rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px; display: flex; align-items: center; justify-content: space-between;">
                    <span><i class="fa-solid fa-location-dot" style="color: #4f46e5; margin-right: 4px;"></i> Select Venue / Location</span>
                    <span id="venue-count-badge" style="font-size: 0.72rem; color: var(--text-muted); font-weight: 600;"></span>
                </label>

                <!-- Filter Pills: Filter between Block / Venue / Class Room -->
                <div style="display: flex; gap: 6px; margin-bottom: 10px; overflow-x: auto; padding-bottom: 4px; -webkit-overflow-scrolling: touch;">
                    <button type="button" class="venue-filter-pill active" onclick="setVenueFilter('all', this)">
                        All ({{ $venues->count() + $blocks->count() + $classrooms->count() }})
                    </button>
                    <button type="button" class="venue-filter-pill" onclick="setVenueFilter('venue', this)">
                        <i class="fa-solid fa-map-pin"></i> Venues ({{ $venues->count() }})
                    </button>
                    <button type="button" class="venue-filter-pill" onclick="setVenueFilter('block', this)">
                        <i class="fa-solid fa-building"></i> Blocks ({{ $blocks->count() }})
                    </button>
                    <button type="button" class="venue-filter-pill" onclick="setVenueFilter('classroom', this)">
                        <i class="fa-solid fa-chalkboard-user"></i> Class Rooms ({{ $classrooms->count() }})
                    </button>
                </div>

                <!-- Secondary Filter: Filter Classrooms by Block -->
                <div id="block-subfilter-container" style="margin-bottom: 8px; display: none;">
                    <select id="venue_block_filter" onchange="applyVenueFilters()" style="width: 100%; padding: 8px 10px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg); color: var(--text-main); font-size: 0.8rem; font-weight: 600;">
                        <option value="">🏢 All Blocks</option>
                        @foreach($blocks as $b)
                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Live Search Box -->
                <div style="position: relative; margin-bottom: 8px;">
                    <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); font-size: 0.75rem; color: var(--text-muted);"></i>
                    <input type="text" id="venue_search_input" oninput="applyVenueFilters()" placeholder="Filter by name, room # or block..." style="width: 100%; padding: 8px 10px 8px 30px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg); color: var(--text-main); font-size: 0.82rem;">
                </div>

                <!-- Main Dropdown List of Venues -->
                <div style="position: relative; margin-bottom: 8px;">
                    <select id="event_venue_select" onchange="onVenueSelectChange(this)" style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1.5px solid var(--border); background: var(--bg); color: var(--text-main); font-size: 0.85rem; font-weight: 600; cursor: pointer;">
                        <option value="">-- Choose Venue / Location --</option>
                    </select>
                </div>

                <!-- Custom Venue Input (shown when "custom" is selected) -->
                <div id="custom_venue_container" style="display: none; margin-bottom: 8px;">
                    <input type="text" id="event_venue_custom" oninput="onCustomVenueInput(this.value)" placeholder="Type custom venue name (e.g. Lawn, Foyer)..." style="width: 100%; padding: 10px; border-radius: 8px; border: 1px dashed #4f46e5; background: var(--bg); color: var(--text-main); font-size: 0.85rem;">
                </div>

                <!-- Selected Venue Preview Card -->
                <div id="selected_venue_badge" style="display: none; align-items: center; justify-content: space-between; background: rgba(79, 70, 229, 0.08); border: 1px solid rgba(79, 70, 229, 0.25); border-radius: 8px; padding: 8px 12px; margin-top: 6px;">
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 0.82rem; color: #4f46e5; font-weight: 700; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        <i id="selected_venue_icon" class="fa-solid fa-location-dot"></i>
                        <span id="selected_venue_text" style="overflow: hidden; text-overflow: ellipsis;"></span>
                    </div>
                    <button type="button" onclick="clearSelectedVenue()" style="background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 2px 6px; font-size: 0.85rem; flex-shrink: 0;" title="Clear">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- Hidden inputs for backend submission -->
                <input type="hidden" id="event_venue" value="">
                <input type="hidden" id="venue_latitude" value="">
                <input type="hidden" id="venue_longitude" value="">
            </div>
            
            <div id="geo-mode-container" style="display: none;">
                <label style="font-size: 0.85rem; font-weight: 700; color: var(--text-main); margin-bottom: 6px; display: block;">Session Mode</label>
                <div style="display: flex; gap: 12px;">
                    <label style="flex: 1; display: flex; align-items: center; gap: 8px; background: var(--bg); padding: 10px; border-radius: 8px; border: 1px solid var(--border); cursor: pointer;">
                        <input type="radio" name="geo_mode" value="active" checked style="accent-color: #0ea5e9;"> Start Now
                    </label>
                    <label style="flex: 1; display: flex; align-items: center; gap: 8px; background: var(--bg); padding: 10px; border-radius: 8px; border: 1px solid var(--border); cursor: pointer;">
                        <input type="radio" name="geo_mode" value="scheduled" style="accent-color: #0ea5e9;"> Schedule
                    </label>
                </div>
            </div>
        </div>
        
        <input type="hidden" id="event_start" value="{{ \Carbon\Carbon::now()->format('H:i') }}">
        <input type="hidden" id="event_end" value="{{ \Carbon\Carbon::now()->addHours(1)->format('H:i') }}">

        <div style="display: flex; gap: 12px;">
            <button type="button" onclick="closeLectureModal()" class="btn-primary" style="flex: 1; padding: 12px; border-radius: 12px; background: var(--subtle-bg); color: var(--text-main); border: 1px solid var(--border); font-weight: 700;">Cancel</button>
            <button type="button" onclick="confirmLecturesAndStart()" class="btn-primary" style="flex: 2; padding: 12px; border-radius: 12px; font-weight: 700; border: none;">Start Session</button>
        </div>
    </div>
</div>
@endif

<div id="qr-session-container" style="display: {{ $existingSession ? 'block' : 'none' }};">
    <div class="glass-card" style="padding: 24px; display: flex; flex-direction: column; align-items: center; text-align: center; margin-bottom: 16px;">
        <h3 style="font-weight: 800; font-size: 1.2rem; color: var(--text-main); margin-bottom: 4px;" id="session-title">
            {{ $existingSession && $existingSession->is_geofencing ? 'Geo-Location Active' : 'Dynamic QR Code' }}
        </h3>
        <p style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 8px;" id="session-desc">
            {{ $existingSession && $existingSession->is_geofencing ? 'Members can self-mark attendance if they are near your location.' : 'Ask members to scan this code. It refreshes every 8 seconds.' }}
        </p>

        <div id="active-session-venue-badge" style="display: {{ $existingSession && $existingSession->venue ? 'inline-flex' : 'none' }}; align-items: center; gap: 6px; background: rgba(79, 70, 229, 0.1); color: #4f46e5; padding: 6px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; margin-bottom: 16px;">
            <i class="fa-solid fa-location-dot"></i>
            <span id="active-session-venue-text">{{ $existingSession->venue ?? '' }}</span>
        </div>
        
        <div id="qrcode-display" style="padding: 16px; background: white; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #eee; margin-bottom: 16px; {{ $existingSession && $existingSession->is_geofencing ? 'display: none;' : '' }}"></div>
        
        <div style="width: 100%; max-width: 250px;">
            <div id="qr-timer-bar-container" style="height: 6px; background: #eee; width: 100%; border-radius: 10px; overflow: hidden; margin-bottom: 20px; {{ $existingSession && $existingSession->is_geofencing ? 'display: none;' : '' }}">
                <div id="qr-timer-bar" style="height: 100%; background: #4f46e5; width: 100%; transition: width 8s linear;"></div>
            </div>
            
            <button onclick="closeQrSession()" class="btn-primary" style="background: #ef4444; color: white; width: 100%; padding: 12px; font-size: 0.9rem; border-radius: 12px; border: none; font-weight: 700;">
                <i class="fa-solid fa-power-off"></i> End Session
            </button>
        </div>
    </div>

    <!-- Live Scanned Members List -->
    <div class="glass-card" style="padding: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <h4 style="font-weight: 800; font-size: 1rem; color: var(--text-main);">Live Attendees</h4>
            <span id="present-count-badge" style="background: rgba(16, 185, 129, 0.1); color: #059669; padding: 4px 10px; border-radius: 12px; font-size: 0.75rem; font-weight: 700;">
                0 Scanned
            </span>
        </div>
        
        <div id="live-attendees-list" style="display: flex; flex-direction: column; gap: 8px; max-height: 250px; overflow-y: auto;">
            <!-- Attendees will be appended here dynamically -->
        </div>
    </div>
</div>

<div id="manual-roster-container" style="display: none;">
    <form action="{{ route('student.club.attendance.submit') }}" method="POST">
        @csrf
        <input type="hidden" name="club_id" value="{{ $club->id }}">
        <input type="hidden" name="event_start" id="manual_event_start">
        <input type="hidden" name="event_end" id="manual_event_end">
        <input type="hidden" name="venue" id="manual_venue">
        
        <div class="glass-card" style="padding: 16px; margin-bottom: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <h4 style="font-weight: 800; font-size: 1.1rem; color: var(--text-main); margin: 0;">Club Roster</h4>
                <div id="manual-venue-tag" style="display: none; align-items: center; gap: 6px; background: rgba(79, 70, 229, 0.1); color: #4f46e5; padding: 4px 10px; border-radius: 12px; font-size: 0.75rem; font-weight: 700;">
                    <i class="fa-solid fa-location-dot"></i> <span id="manual-venue-display"></span>
                </div>
            </div>
            
            @forelse($clubMembers as $member)
                @if($member->member_type === 'student')
                @php
                    $status = $existingRecords[$member->member_id] ?? 'absent';
                @endphp
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--border);">
                    <div>
                        <div style="font-weight: 700; font-size: 0.95rem; color: var(--text-main);">{{ $member->member->name ?? 'Unknown' }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $member->member->roll_number ?? '' }} • {{ $member->designation ?? 'Member' }}</div>
                    </div>
                    
                    <div style="display: flex; gap: 6px; background: var(--subtle-bg); padding: 4px; border-radius: 8px;">
                        <input type="radio" name="attendance[{{ $member->member_id }}]" value="present" id="p{{ $member->member_id }}" style="display:none" {{ $status == 'present' ? 'checked' : '' }}>
                        <label for="p{{ $member->member_id }}" class="status-btn p-btn" style="padding: 6px 12px; border-radius: 6px; cursor: pointer; font-weight: 700; font-size: 0.8rem; color: var(--text-muted);">P</label>
                        
                        <input type="radio" name="attendance[{{ $member->member_id }}]" value="absent" id="a{{ $member->member_id }}" style="display:none" {{ $status == 'absent' ? 'checked' : '' }}>
                        <label for="a{{ $member->member_id }}" class="status-btn a-btn" style="padding: 6px 12px; border-radius: 6px; cursor: pointer; font-weight: 700; font-size: 0.8rem; color: var(--text-muted);">A</label>
                    </div>
                </div>
                @endif
            @empty
                <div style="text-align: center; padding: 24px; color: var(--text-muted);">No students in this club yet.</div>
            @endforelse
        </div>
        
        <div style="display: flex; gap: 12px; position: sticky; bottom: 16px;">
            <button type="button" onclick="cancelManualRoster()" class="btn-primary" style="flex: 1; padding: 14px; background: var(--subtle-bg); color: var(--text-main); border: 1px solid var(--border); border-radius: 12px; font-weight: 700;">Cancel</button>
            <button type="submit" class="btn-primary" style="flex: 2; padding: 14px; border-radius: 12px; font-weight: 700; border: none;">Save Attendance</button>
        </div>
    </form>
</div>

<style>
    .venue-filter-pill {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        border: 1px solid var(--border);
        background: var(--bg);
        color: var(--text-muted);
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        flex-shrink: 0;
    }
    .venue-filter-pill:hover {
        border-color: #4f46e5;
        color: #4f46e5;
    }
    .venue-filter-pill.active {
        background: #4f46e5;
        color: #ffffff !important;
        border-color: #4f46e5;
        box-shadow: 0 2px 6px rgba(79, 70, 229, 0.25);
    }

    .attendee-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: var(--subtle-bg);
        border: 1px solid var(--border);
        border-radius: 12px;
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .status-btn { transition: 0.2s all; }
    input[value="present"]:checked + .p-btn { background: #10b981; color: white !important; }
    input[value="absent"]:checked + .a-btn { background: #ef4444; color: white !important; }
</style>

<script>
    let currentSessionUuid = '{{ $existingSession->uuid ?? "" }}';
    let qrRefreshInterval;
    let syncInterval;
    let qrcode = null;
    let presentStudentIds = new Set();
    
    let pendingSessionType = null;

    // Master dataset for Venues, Blocks, and Classrooms
    const allVenueData = [
        // Designated Venues
        @foreach($venues as $v)
        {
            type: 'venue',
            typeLabel: 'Venue',
            id: {{ $v->id }},
            name: @json($v->name),
            detail: @json($v->type ? "Type: {$v->type}" : ($v->description ?? 'Campus Venue')),
            display: @json($v->name . ($v->type ? " ({$v->type})" : '')),
            blockId: null,
            lat: @json($v->latitude ?? null),
            lng: @json($v->longitude ?? null),
        },
        @endforeach

        // Blocks
        @foreach($blocks as $b)
        {
            type: 'block',
            typeLabel: 'Block',
            id: {{ $b->id }},
            name: @json($b->name),
            detail: 'Academic Block',
            display: @json($b->name),
            blockId: {{ $b->id }},
            lat: @json($b->latitude ?? null),
            lng: @json($b->longitude ?? null),
        },
        @endforeach

        // Classrooms
        @foreach($classrooms as $c)
        {
            type: 'classroom',
            typeLabel: 'Class Room',
            id: {{ $c->id }},
            name: @json($c->name),
            detail: @json(($c->block ? $c->block->name : 'No Block') . ($c->floor_number ? " • Floor {$c->floor_number}" : '')),
            display: @json("Room {$c->name}" . ($c->block ? " ({$c->block->name})" : '')),
            blockId: {{ $c->block_id ?? 'null' }},
            lat: @json($c->latitude ?? null),
            lng: @json($c->longitude ?? null),
        },
        @endforeach
    ];

    let currentFilterType = 'all';

    function setVenueFilter(type, btnElement) {
        currentFilterType = type;
        
        document.querySelectorAll('.venue-filter-pill').forEach(pill => {
            pill.classList.remove('active');
        });
        if (btnElement) {
            btnElement.classList.add('active');
        }
        
        const blockSubFilter = document.getElementById('block-subfilter-container');
        if (type === 'classroom' || type === 'all') {
            blockSubFilter.style.display = 'block';
        } else {
            blockSubFilter.style.display = 'none';
            const blockSelect = document.getElementById('venue_block_filter');
            if (blockSelect) blockSelect.value = '';
        }
        
        applyVenueFilters();
    }

    function applyVenueFilters() {
        const searchTerm = (document.getElementById('venue_search_input')?.value || '').toLowerCase().trim();
        const selectedBlockId = document.getElementById('venue_block_filter')?.value;
        const select = document.getElementById('event_venue_select');
        if (!select) return;
        
        const previousValue = select.value;
        select.innerHTML = '<option value="">-- Choose Venue / Location --</option>';
        
        const filtered = allVenueData.filter(item => {
            if (currentFilterType !== 'all' && item.type !== currentFilterType) {
                return false;
            }
            if (selectedBlockId && item.type === 'classroom' && String(item.blockId) !== String(selectedBlockId)) {
                return false;
            }
            if (selectedBlockId && item.type === 'block' && String(item.id) !== String(selectedBlockId)) {
                return false;
            }
            if (searchTerm) {
                const matchName = (item.name || '').toLowerCase().includes(searchTerm);
                const matchDisplay = (item.display || '').toLowerCase().includes(searchTerm);
                const matchDetail = (item.detail || '').toLowerCase().includes(searchTerm);
                if (!matchName && !matchDisplay && !matchDetail) {
                    return false;
                }
            }
            return true;
        });
        
        const badge = document.getElementById('venue-count-badge');
        if (badge) {
            badge.innerText = `${filtered.length} locations`;
        }
        
        if (currentFilterType === 'all') {
            const groups = {
                venue: { label: '📍 Venues', items: [] },
                block: { label: '🏢 Blocks', items: [] },
                classroom: { label: '🚪 Class Rooms', items: [] },
            };
            
            filtered.forEach(item => {
                if (groups[item.type]) {
                    groups[item.type].items.push(item);
                }
            });
            
            Object.keys(groups).forEach(gKey => {
                const g = groups[gKey];
                if (g.items.length > 0) {
                    const optgroup = document.createElement('optgroup');
                    optgroup.label = `${g.label} (${g.items.length})`;
                    g.items.forEach(item => {
                        const opt = document.createElement('option');
                        opt.value = item.display;
                        opt.textContent = `${item.display} — ${item.detail}`;
                        opt.dataset.lat = item.lat || '';
                        opt.dataset.lng = item.lng || '';
                        opt.dataset.type = item.type;
                        optgroup.appendChild(opt);
                    });
                    select.appendChild(optgroup);
                }
            });
        } else {
            filtered.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.display;
                opt.textContent = `${item.display} — ${item.detail}`;
                opt.dataset.lat = item.lat || '';
                opt.dataset.lng = item.lng || '';
                opt.dataset.type = item.type;
                select.appendChild(opt);
            });
        }
        
        // Custom option
        const customOpt = document.createElement('option');
        customOpt.value = '__custom__';
        customOpt.textContent = '✏️ + Enter Custom Venue...';
        select.appendChild(customOpt);
        
        if (previousValue && Array.from(select.options).some(o => o.value === previousValue)) {
            select.value = previousValue;
        }
    }

    function onVenueSelectChange(select) {
        const val = select.value;
        const customContainer = document.getElementById('custom_venue_container');
        const selectedOption = select.options[select.selectedIndex];
        
        if (val === '__custom__') {
            customContainer.style.display = 'block';
            const customVal = document.getElementById('event_venue_custom').value;
            document.getElementById('event_venue').value = customVal;
            updateSelectedBadge('✏️ Custom: ' + (customVal || 'Typing...'), 'fa-pen');
        } else if (val) {
            customContainer.style.display = 'none';
            document.getElementById('event_venue').value = val;
            
            const lat = selectedOption.dataset.lat;
            const lng = selectedOption.dataset.lng;
            document.getElementById('venue_latitude').value = lat || '';
            document.getElementById('venue_longitude').value = lng || '';
            
            const type = selectedOption.dataset.type;
            const icon = type === 'block' ? 'fa-building' : (type === 'classroom' ? 'fa-chalkboard-user' : 'fa-map-pin');
            updateSelectedBadge(val, icon);
        } else {
            customContainer.style.display = 'none';
            clearSelectedVenue();
        }
    }

    function onCustomVenueInput(text) {
        document.getElementById('event_venue').value = text;
        updateSelectedBadge('✏️ ' + (text || 'Custom Venue'), 'fa-pen');
    }

    function updateSelectedBadge(text, iconClass) {
        const badge = document.getElementById('selected_venue_badge');
        const badgeText = document.getElementById('selected_venue_text');
        const badgeIcon = document.getElementById('selected_venue_icon');
        if (!badge || !badgeText || !badgeIcon) return;
        
        badgeText.innerText = text;
        badgeIcon.className = `fa-solid ${iconClass || 'fa-location-dot'}`;
        badge.style.display = 'flex';
    }

    function clearSelectedVenue() {
        const select = document.getElementById('event_venue_select');
        if (select) select.value = '';
        document.getElementById('event_venue').value = '';
        document.getElementById('venue_latitude').value = '';
        document.getElementById('venue_longitude').value = '';
        const customContainer = document.getElementById('custom_venue_container');
        if (customContainer) customContainer.style.display = 'none';
        const customInput = document.getElementById('event_venue_custom');
        if (customInput) customInput.value = '';
        const badge = document.getElementById('selected_venue_badge');
        if (badge) badge.style.display = 'none';
    }
    
    function openLectureModal(type) {
        pendingSessionType = type;
        
        document.getElementById('additional-options-container').style.display = 'block';
        if (type === 'geo') {
            document.getElementById('geo-mode-container').style.display = 'block';
        } else {
            document.getElementById('geo-mode-container').style.display = 'none';
        }
        
        document.getElementById('lecture-selection-modal').style.display = 'flex';
    }
    
    function closeLectureModal() {
        document.getElementById('lecture-selection-modal').style.display = 'none';
    }
    
    function confirmLecturesAndStart() {
        closeLectureModal();
        
        let minStart = null;
        let maxEnd = null;
        
        document.querySelectorAll('.lecture-period-checkbox:checked').forEach(cb => {
            const start = cb.dataset.start;
            const end = cb.dataset.end;
            if (!minStart || start < minStart) minStart = start;
            if (!maxEnd || end > maxEnd) maxEnd = end;
        });
        
        if (minStart) {
            document.getElementById('event_start').value = minStart;
        }
        if (maxEnd) {
            document.getElementById('event_end').value = maxEnd;
        }
        
        if (pendingSessionType === 'qr') {
            initQrSession();
        } else if (pendingSessionType === 'geo') {
            initGeoSession();
        } else if (pendingSessionType === 'manual') {
            openManualRoster();
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        applyVenueFilters();
        if (currentSessionUuid) {
            startQrLoop();
        }
    });

    function openManualRoster() {
        document.getElementById('manual_event_start').value = document.getElementById('event_start').value;
        document.getElementById('manual_event_end').value = document.getElementById('event_end').value;
        
        const venue = document.getElementById('event_venue').value;
        document.getElementById('manual_venue').value = venue;
        const manualVenueDisplay = document.getElementById('manual-venue-display');
        const manualVenueTag = document.getElementById('manual-venue-tag');
        if (venue) {
            if (manualVenueDisplay) manualVenueDisplay.innerText = venue;
            if (manualVenueTag) manualVenueTag.style.display = 'inline-flex';
        } else {
            if (manualVenueTag) manualVenueTag.style.display = 'none';
        }
        
        document.getElementById('init-session-container').style.display = 'none';
        document.getElementById('manual-roster-container').style.display = 'block';
    }

    function cancelManualRoster() {
        document.getElementById('manual-roster-container').style.display = 'none';
        document.getElementById('init-session-container').style.display = 'block';
    }

    function initGeoSession() {
        if (!navigator.geolocation) {
            alert("Geolocation is not supported by this browser.");
            return;
        }
        
        // Change button to loading state
        document.getElementById('init-session-container').style.opacity = '0.5';
        
        const mode = document.querySelector('input[name="geo_mode"]:checked').value;
        const venue = document.getElementById('event_venue').value;
        const venueLat = document.getElementById('venue_latitude').value;
        const venueLng = document.getElementById('venue_longitude').value;
        
        navigator.geolocation.getCurrentPosition(function(position) {
            fetch('{{ route("student.club.qr.init") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    club_id: {{ $club->id }},
                    is_geofencing: 1,
                    latitude: position.coords.latitude || venueLat,
                    longitude: position.coords.longitude || venueLng,
                    event_start: document.getElementById('event_start').value,
                    event_end: document.getElementById('event_end').value,
                    status: mode,
                    venue: venue
                })
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('init-session-container').style.opacity = '1';
                if (data.success) {
                    if (mode === 'scheduled') {
                        alert("Session has been scheduled successfully!");
                        window.location.reload();
                        return;
                    }
                    
                    currentSessionUuid = data.uuid;
                    document.getElementById('init-session-container').style.display = 'none';
                    document.getElementById('qr-session-container').style.display = 'block';
                    
                    document.getElementById('session-title').innerText = 'Geo-Location Active';
                    document.getElementById('session-desc').innerText = 'Members can self-mark attendance if they are near your location.';
                    document.getElementById('qrcode-display').style.display = 'none';
                    document.getElementById('qr-timer-bar-container').style.display = 'none';
                    if (venue) {
                        const venueText = document.getElementById('active-session-venue-text');
                        const venueBadge = document.getElementById('active-session-venue-badge');
                        if (venueText) venueText.innerText = venue;
                        if (venueBadge) venueBadge.style.display = 'inline-flex';
                    }
                    
                    // Polling for live students only (no QR refresh needed)
                    syncStudents();
                    syncInterval = setInterval(syncStudents, 3000);
                } else {
                    alert(data.message || 'Error initializing session');
                }
            }).catch(err => {
                document.getElementById('init-session-container').style.opacity = '1';
                console.error(err);
                alert('Error initializing session');
            });
        }, function(error) {
            document.getElementById('init-session-container').style.opacity = '1';
            alert("Error getting location. Please allow location permissions.");
        });
    }

    function initQrSession() {
        const venue = document.getElementById('event_venue').value;
        const venueLat = document.getElementById('venue_latitude').value;
        const venueLng = document.getElementById('venue_longitude').value;
        
        fetch('{{ route("student.club.qr.init") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ 
                club_id: {{ $club->id }},
                is_geofencing: 0,
                latitude: venueLat || null,
                longitude: venueLng || null,
                event_start: document.getElementById('event_start').value,
                event_end: document.getElementById('event_end').value,
                venue: venue
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                currentSessionUuid = data.uuid;
                document.getElementById('init-session-container').style.display = 'none';
                document.getElementById('qr-session-container').style.display = 'block';
                if (venue) {
                    const venueText = document.getElementById('active-session-venue-text');
                    const venueBadge = document.getElementById('active-session-venue-badge');
                    if (venueText) venueText.innerText = venue;
                    if (venueBadge) venueBadge.style.display = 'inline-flex';
                }
                startQrLoop();
            } else {
                alert(data.message || 'Error initializing session');
            }
        }).catch(err => {
            console.error(err);
            alert('Error initializing session');
        });
    }
    
    function startQrLoop() {
        document.getElementById('qrcode-display').style.display = 'block';
        document.getElementById('qr-timer-bar-container').style.display = 'block';
        
        const container = document.getElementById("qrcode-display");
        container.innerHTML = "";
        qrcode = new QRCode(container, {
            width: 220,
            height: 220,
            correctLevel: QRCode.CorrectLevel.H
        });
        
        refreshQR();
        qrRefreshInterval = setInterval(refreshQR, 8000);
        
        syncStudents();
        syncInterval = setInterval(syncStudents, 3000); // Polling for simplicity in PWA without websockets setup
    }

    function refreshQR() {
        fetch('{{ route("student.club.qr.refresh") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ uuid: currentSessionUuid })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                qrcode.clear();
                qrcode.makeCode(data.payload);
                
                // Animate Timer Bar
                const bar = document.getElementById('qr-timer-bar');
                bar.style.transition = 'none';
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.transition = 'width 8s linear';
                    bar.style.width = '100%';
                }, 50);
            }
        }).catch(console.error);
    }
    
    function syncStudents() {
        fetch('{{ route("student.club.qr.students") }}?uuid=' + currentSessionUuid, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const list = document.getElementById('live-attendees-list');
                const badge = document.getElementById('present-count-badge');
                
                badge.innerText = data.students.length + ' Scanned';
                
                data.students.forEach(st => {
                    if (!presentStudentIds.has(st.id)) {
                        presentStudentIds.add(st.id);
                        
                        const el = document.createElement('div');
                        el.className = 'attendee-item';
                        el.innerHTML = `
                            <div style="width: 10px; height: 10px; border-radius: 50%; background: #10b981; box-shadow: 0 0 8px #10b981;"></div>
                            <div style="flex: 1;">
                                <div style="font-size: 0.9rem; font-weight: 700; color: var(--text-main); line-height: 1.2;">${st.name}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">${st.roll_number}</div>
                            </div>
                            <i class="fa-solid fa-check-circle" style="color: #10b981;"></i>
                        `;
                        // Prepend so newest is on top
                        list.insertBefore(el, list.firstChild);
                    }
                });
            }
        });
    }

    function closeQrSession() {
        if (!confirm('Are you sure you want to end this attendance session?')) return;
        
        clearInterval(qrRefreshInterval);
        clearInterval(syncInterval);
        
        fetch('{{ route("student.club.qr.close") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ uuid: currentSessionUuid })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route("student.club.index") }}';
            }
        }).catch(console.error);
    }
</script>
@endsection
