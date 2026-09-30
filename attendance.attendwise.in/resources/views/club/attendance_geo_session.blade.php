@extends('layouts.club')

@section('header-title', 'Live Geo Attendance')
@section('header-subtitle', $event ? 'Event: ' . $event->name : 'Ad-Hoc Geo Session')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .roster-grid {
        display: grid;
        grid-template-columns: 400px 1fr;
        gap: 1.5rem;
        min-height: calc(100vh - 200px);
    }
    
    @media (max-width: 992px) {
        .roster-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Select2 Dark Theme Fixes */
    .select2-container--default .select2-selection--single {
        background-color: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 0.75rem;
        height: 48px;
        display: flex;
        align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: var(--text-main);
        padding-left: 1rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 46px;
        right: 10px;
    }
    .select2-dropdown {
        background-color: var(--card-bg);
        border: 1px solid var(--border);
    }
    .select2-container--default .select2-results__option--selected {
        background-color: var(--subtle-bg);
    }
    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: #10b981;
        color: white;
    }
    .select2-search--dropdown .select2-search__field {
        background-color: var(--bg);
        border: 1px solid var(--border);
        color: var(--text-main);
        border-radius: 0.25rem;
    }
    
    /* GPS Radar Animation */
    .gps-radar {
        position: relative;
        width: 150px;
        height: 150px;
        margin: 2rem auto;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .gps-pin {
        position: relative;
        z-index: 10;
        color: #10b981;
    }

    .gps-pin.inactive {
        color: var(--text-muted);
    }

    .gps-ring {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: rgba(16, 185, 129, 0.4);
        animation: pulse-ring 2s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
        opacity: 0;
    }

    .gps-ring:nth-child(2) {
        animation-delay: 0.6s;
    }
    
    .gps-ring:nth-child(3) {
        animation-delay: 1.2s;
    }

    .gps-radar.inactive .gps-ring {
        display: none;
    }

    @keyframes pulse-ring {
        0% { transform: scale(0.3); opacity: 0.8; }
        80% { transform: scale(1.5); opacity: 0; }
        100% { transform: scale(1.5); opacity: 0; }
    }
    
    /* Custom Modal Styles */
    .modal-overlay {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);
        display: none; align-items: center; justify-content: center; z-index: 100;
    }
    .modal-overlay.active { display: flex; }
    .modal-content {
        background: var(--card-bg); width: 100%; max-width: 400px;
        border-radius: 1rem; border: 1px solid var(--border); overflow: hidden;
        animation: slideUp 0.3s ease; text-align: center; padding: 2rem;
    }
    @keyframes slideUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>
@endsection

@section('content')
<div class="content-inner">
    <div class="roster-grid">
        <!-- Control Panel -->
        <div style="background: var(--card-bg); border: 1px solid var(--border); border-radius: 1.5rem; padding: 2.5rem; display: flex; flex-direction: column; align-items: center; text-align: center;">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.25rem;">Geo-Location Status</h3>
            <p style="color: var(--text-muted); font-size: 0.85rem;" id="geo-status-text">
                {{ $session->is_geofencing ? 'Currently accepting attendance in radius' : 'Geo-Location is currently disabled' }}
            </p>
            
            <div class="gps-radar {{ $session->is_geofencing ? '' : 'inactive' }}" id="gps-radar-animation">
                <div class="gps-ring"></div>
                <div class="gps-ring"></div>
                <div class="gps-ring"></div>
                <i data-lucide="map-pin" class="gps-pin {{ $session->is_geofencing ? '' : 'inactive' }}" style="width: 48px; height: 48px;"></i>
            </div>
            
            <div id="geoCoordinates" style="margin-bottom: 2rem; width: 100%; display: block;">
                <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Active Coordinates</div>
                <div id="locationsList" style="display: flex; flex-direction: column; gap: 0.5rem; margin-top: 0.5rem; text-align: left; max-height: 150px; overflow-y: auto;">
                    <!-- Rendered by JS -->
                </div>
            </div>

            @if($manager->role === 'admin' || $manager->hasPermission('attendance.take'))
            <div style="width: 100%; text-align: left; margin-bottom: 1.5rem;">
                <label style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.5rem; display: block;">Add a Location</label>
                <div style="display: flex; gap: 0.5rem;">
                    <div style="flex: 1;">
                        <select id="location-select" style="width: 100%;">
                            <option value="">Search for a venue or block...</option>
                        </select>
                    </div>
                    <button id="btnAddLocation" style="background: var(--subtle-bg); color: var(--text-main); border: 1px solid var(--border); padding: 0 1rem; border-radius: 0.5rem; cursor: pointer;" title="Add Location">
                        <i data-lucide="plus" style="width: 18px;"></i>
                    </button>
                </div>
            </div>
            
            <div style="width: 100%; display: flex; gap: 0.5rem;">
                <button id="btnSetLocation" style="flex: 2; background: #10b981; color: white; border: none; padding: 0.85rem; border-radius: 0.75rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <i data-lucide="check" style="width: 18px;"></i> Update & Enable Geo-Location
                </button>
                <button id="btnToggleGeo" style="flex: 1; background: {{ $session->is_geofencing ? 'rgba(239, 68, 68, 0.1)' : 'var(--subtle-bg)' }}; color: {{ $session->is_geofencing ? '#ef4444' : 'var(--text-main)' }}; border: 1px solid {{ $session->is_geofencing ? '#ef4444' : 'var(--border)' }}; border-radius: 0.75rem; cursor: pointer; display: flex; align-items: center; justify-content: center;" title="{{ $session->is_geofencing ? 'Disable Geo-Location' : 'Auto Detect & Enable' }}">
                    <i data-lucide="{{ $session->is_geofencing ? 'power-off' : 'crosshair' }}" style="width: 20px;"></i>
                </button>
            </div>
            @endif
        </div>

        <!-- Live Feed -->
        <div style="background: var(--card-bg); border: 1px solid var(--border); border-radius: 1.5rem; display: flex; flex-direction: column; max-height: calc(100vh - 200px);">
            <div style="padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-weight: 700; font-size: 1.1rem;">Live Feed</h3>
                <span style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 0.35rem 1rem; border-radius: 2rem; font-weight: 700; font-size: 0.85rem;">
                    <span id="presentCount">{{ count($records) }}</span> / {{ $members->count() }} Present
                </span>
            </div>
            <div style="flex: 1; overflow-y: auto; padding: 1.5rem;" id="live-feed-list">
                @if($members->count() > 0)
                    @foreach($members as $member)
                    <div id="st-{{ $member->member_id }}" style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; border-radius: 1rem; border: 1px solid {{ in_array($member->member_id, $records) ? '#10b981' : 'var(--border)' }}; margin-bottom: 0.75rem; background: {{ in_array($member->member_id, $records) ? 'rgba(16,185,129,0.05)' : 'transparent' }}; transition: all 0.3s;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div class="member-avatar" style="width: 40px; height: 40px; border-radius: 50%; background: {{ in_array($member->member_id, $records) ? '#10b981' : 'var(--border)' }}; color: {{ in_array($member->member_id, $records) ? 'white' : 'var(--text-main)' }}; display: flex; align-items: center; justify-content: center; font-weight: 700; transition: all 0.3s;">
                                {{ strtoupper(substr($member->student->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight: 600;">{{ $member->student->name }}</div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $member->student->email }}</div>
                            </div>
                        </div>
                        <div class="status-icon" style="color: {{ in_array($member->member_id, $records) ? '#10b981' : 'var(--text-muted)' }}; transition: all 0.3s;">
                            <i data-lucide="{{ in_array($member->member_id, $records) ? 'check-circle' : 'circle' }}"></i>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div style="text-align: center; color: var(--text-muted); padding: 2rem;">No students in this club.</div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Custom Alert Dialog -->
<div class="modal-overlay" id="alertDialog">
    <div class="modal-content">
        <div id="alertIconContainer" style="color: #ef4444; margin-bottom: 1rem; display: flex; justify-content: center;">
            <i data-lucide="alert-circle" style="width: 48px; height: 48px;"></i>
        </div>
        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--text-main);" id="alertTitle">Notice</h3>
        <p style="color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.95rem;" id="alertMessage"></p>
        <button onclick="closeCustomDialog()" style="background: var(--text-main); color: var(--bg); border: none; padding: 0.75rem 2rem; border-radius: 0.75rem; font-weight: 600; cursor: pointer; width: 100%; font-size: 1rem;">OK</button>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    const csrfToken = '{{ csrf_token() }}';
    const currentSessionUuid = '{{ $session->uuid }}';
    let isGeoActive = {{ $session->is_geofencing ? 'true' : 'false' }};
    const geoToggleUrl = "{{ route('club.attendance.geo.toggle', $session->id) }}";
    
    let presentCount = {{ count($records) }};
    const countEl = document.getElementById('presentCount');

    function showCustomDialog(message, title = 'Notice', type = 'info') {
        document.getElementById('alertTitle').textContent = title;
        document.getElementById('alertMessage').textContent = message;
        
        const iconContainer = document.getElementById('alertIconContainer');
        if (type === 'success') {
            iconContainer.style.color = '#10b981';
            iconContainer.innerHTML = '<i data-lucide="check-circle" style="width: 48px; height: 48px;"></i>';
        } else if (type === 'error') {
            iconContainer.style.color = '#ef4444';
            iconContainer.innerHTML = '<i data-lucide="alert-circle" style="width: 48px; height: 48px;"></i>';
        } else {
            iconContainer.style.color = '#3b82f6';
            iconContainer.innerHTML = '<i data-lucide="info" style="width: 48px; height: 48px;"></i>';
        }
        
        document.getElementById('alertDialog').classList.add('active');
        if(window.lucide) lucide.createIcons();
    }
    function closeCustomDialog() {
        document.getElementById('alertDialog').classList.remove('active');
    }

    let geoLocations = @json($session->geo_locations ?? []);
    if (!Array.isArray(geoLocations)) {
        geoLocations = [];
    }
    
    if (geoLocations.length === 0 && '{{ $session->latitude }}') {
        geoLocations.push({
            name: 'Legacy Location',
            lat: {{ $session->latitude ?? 'null' }},
            lng: {{ $session->longitude ?? 'null' }}
        });
    }

    function renderLocationList() {
        let html = '';
        if (geoLocations.length > 0) {
            geoLocations.forEach((loc, index) => {
                html += `
                <div style="background: var(--subtle-bg); padding: 0.5rem; border-radius: 0.5rem; border: 1px solid var(--border); font-size: 0.8rem; text-align: left; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-weight: 600; color: var(--text-main);">${loc.name || 'Location'}</div>
                        <div style="color: var(--text-muted); font-size: 0.7rem;">Lat: ${loc.lat}, Lng: ${loc.lng}</div>
                    </div>
                    @if($manager->role === 'admin' || $manager->hasPermission('attendance.take'))
                    <button class="btn-remove-loc" data-index="${index}" style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 0.25rem;">
                        <i data-lucide="trash-2" style="width: 14px;"></i>
                    </button>
                    @endif
                </div>`;
            });
        } else {
            html = `<div style="font-size: 0.8rem; color: var(--text-muted); text-align: center;">No locations selected.</div>`;
        }
        document.getElementById('locationsList').innerHTML = html;
        if(window.lucide) lucide.createIcons();
        
        document.querySelectorAll('.btn-remove-loc').forEach(btn => {
            btn.addEventListener('click', function() {
                const idx = parseInt(this.getAttribute('data-index'));
                geoLocations.splice(idx, 1);
                renderLocationList();
            });
        });
    }

    // Call once to render initial list
    renderLocationList();

    // Select2 Initialization
    $(document).ready(function() {
        $('#location-select').select2({
            ajax: {
                url: "{{ route('club.locations.search') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { q: params.term, page: params.page || 1 };
                },
                processResults: function (data) {
                    return { results: data.results, pagination: data.pagination };
                },
                cache: true
            },
            placeholder: 'Search for a venue or block...',
            minimumInputLength: 0,
        });
    });

    const btnSetLocation = document.getElementById('btnSetLocation');
    const btnToggleGeo = document.getElementById('btnToggleGeo');
    const btnAddLocation = document.getElementById('btnAddLocation');
    
    if (btnAddLocation) {
        btnAddLocation.addEventListener('click', function() {
            const data = $('#location-select').select2('data')[0];
            if (!data || !data.id) {
                showCustomDialog('Please search and select a location from the dropdown first.');
                return;
            }
            if (!data.latitude || !data.longitude) {
                showCustomDialog('This location does not have coordinates defined.');
                return;
            }
            
            geoLocations.push({
                name: data.text,
                lat: data.latitude,
                lng: data.longitude
            });
            $('#location-select').val(null).trigger('change');
            renderLocationList();
        });
    }

    if (btnSetLocation) {
        btnSetLocation.addEventListener('click', function() {
            if (geoLocations.length === 0) {
                showCustomDialog('Please add at least one location first.');
                return;
            }
            toggleGeo(true, geoLocations[0].lat, geoLocations[0].lng, geoLocations);
        });
    }

    if (btnToggleGeo) {
        btnToggleGeo.addEventListener('click', () => {
            if(!isGeoActive) {
                if (navigator.geolocation) {
                    btnToggleGeo.innerHTML = `<i data-lucide="loader" class="spin"></i>`;
                    lucide.createIcons();
                    navigator.geolocation.getCurrentPosition(
                        (position) => toggleGeo(true, position.coords.latitude, position.coords.longitude, [{name: 'Auto Detected', lat: position.coords.latitude, lng: position.coords.longitude}]),
                        (error) => { showCustomDialog("Failed to get location: " + error.message, 'Error', 'error'); resetGeoUI(); }
                    );
                } else {
                    showCustomDialog("Geolocation is not supported by this browser.", 'Error', 'error');
                }
            } else {
                toggleGeo(false, null, null, null);
            }
        });
    }
    
    function toggleGeo(active, lat, lng, payloadGeoLocations = null) {
        fetch(geoToggleUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ is_geofencing: active, latitude: lat, longitude: lng, geo_locations: payloadGeoLocations })
        }).then(res => res.json()).then(data => {
            if(data.success) {
                isGeoActive = active;
                if (payloadGeoLocations) {
                    geoLocations = payloadGeoLocations;
                }
                renderLocationList();
                resetGeoUI();
                showCustomDialog(active ? 'Geo-attendance locations updated and enabled successfully.' : 'Geo-attendance disabled successfully.', 'Success', 'success');
            } else {
                showCustomDialog(data.message || 'Failed to update geo-location.', 'Error', 'error');
            }
        }).catch(err => {
            showCustomDialog('An error occurred while updating geo-location.', 'Error', 'error');
        });
    }
    
    function resetGeoUI() {
        const radar = document.getElementById('gps-radar-animation');
        const pin = radar.querySelector('.gps-pin');
        const coords = document.getElementById('geoCoordinates');
        const statusText = document.getElementById('geo-status-text');
        
        if(isGeoActive) {
            radar.classList.remove('inactive');
            pin.classList.remove('inactive');
            coords.style.display = 'block';
            statusText.textContent = 'Currently accepting attendance in radius';
            
            btnToggleGeo.style.background = 'rgba(239, 68, 68, 0.1)';
            btnToggleGeo.style.color = '#ef4444';
            btnToggleGeo.style.borderColor = '#ef4444';
            btnToggleGeo.title = 'Disable Geo-Location';
            btnToggleGeo.innerHTML = '<i data-lucide="power-off" style="width: 20px;"></i>';
        } else {
            radar.classList.add('inactive');
            pin.classList.add('inactive');
            coords.style.display = 'none';
            statusText.textContent = 'Geo-Location is currently disabled';
            
            btnToggleGeo.style.background = 'var(--subtle-bg)';
            btnToggleGeo.style.color = 'var(--text-main)';
            btnToggleGeo.style.borderColor = 'var(--border)';
            btnToggleGeo.title = 'Auto Detect & Enable';
            btnToggleGeo.innerHTML = '<i data-lucide="crosshair" style="width: 20px;"></i>';
        }
        lucide.createIcons();
    }
    
    // WebSockets integration for Live updates
    if (window.Echo) {
        window.Echo.join('attendance.session.' + currentSessionUuid)
            .here((users) => {
                console.log('Joined geo session. Active users:', users);
            })
            .listen('.LiveAttendanceAction', (e) => {
                if (e.action === 'student_joined') {
                    const row = document.getElementById('st-' + e.payload.student_id);
                    if(row && row.style.backgroundColor !== 'rgba(16, 185, 129, 0.05)') {
                        presentCount++;
                        countEl.textContent = presentCount;
                        
                        row.style.borderColor = '#10b981';
                        row.style.background = 'rgba(16, 185, 129, 0.05)';
                        
                        const avatar = row.querySelector('.member-avatar');
                        if(avatar) {
                            avatar.style.background = '#10b981';
                            avatar.style.color = 'white';
                        }
                        
                        const icon = row.querySelector('.status-icon');
                        if(icon) {
                            icon.style.color = '#10b981';
                            icon.innerHTML = '<i data-lucide="check-circle"></i>';
                            lucide.createIcons();
                        }
                        
                        // Highlight pulse
                        const origBg = row.style.background;
                        row.style.background = 'rgba(16, 185, 129, 0.3)';
                        setTimeout(() => {
                            row.style.background = origBg;
                        }, 1000);
                    }
                }
            });
    }
</script>
@endsection
