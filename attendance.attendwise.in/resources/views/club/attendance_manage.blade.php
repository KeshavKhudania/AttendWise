@extends('layouts.club')

@section('header-title', request('method') == 'qr' ? 'Live QR Attendance' : 'Manage Attendance')
@section('header-subtitle', $event ? 'Event: ' . $event->name : 'Ad-Hoc Session')

@section('styles')
<style>
    .meta-bar {
        display: flex;
        gap: 2rem;
        background: var(--card-bg);
        border: 1px solid var(--border);
        padding: 1.5rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
    }
    
    .meta-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .meta-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        font-weight: 700;
        color: var(--text-muted);
        letter-spacing: 0.05em;
    }
    
    .meta-value {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-main);
    }
    
    .members-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 1rem;
        overflow: hidden;
    }

    .members-table th {
        background: rgba(0,0,0,0.02);
        padding: 1rem 1.5rem;
        text-align: left;
        font-size: 0.8rem;
        text-transform: uppercase;
        font-weight: 700;
        color: var(--text-muted);
        letter-spacing: 0.05em;
        border-bottom: 1px solid var(--border);
    }
    
    [data-theme="dark"] .members-table th { background: rgba(255,255,255,0.02); }

    .members-table td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }

    .members-table tr:last-child td { border-bottom: none; }
    
    .member-info { display: flex; align-items: center; gap: 1rem; }
    .member-avatar {
        width: 40px; height: 40px;
        border-radius: 50%;
        background: var(--border);
        display: flex; align-items: center; justify-content: center;
        font-weight: 700;
        color: var(--text-main);
    }
    
    /* Toggle Switch */
    .switch {
        position: relative;
        display: inline-block;
        width: 48px;
        height: 24px;
    }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider {
        position: absolute; cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: var(--border);
        transition: .3s; border-radius: 24px;
    }
    .slider:before {
        position: absolute; content: "";
        height: 18px; width: 18px;
        left: 3px; bottom: 3px;
        background-color: white;
        transition: .3s; border-radius: 50%;
    }
    input:checked + .slider { background-color: #10b981; }
    input:checked + .slider:before { transform: translateX(24px); }
    input:disabled + .slider { opacity: 0.5; cursor: not-allowed; }

    @keyframes spin { 100% { transform: rotate(360deg); } }
    .spin { animation: spin 1s linear infinite; }
    
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
        text-align: left;
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

    @media (max-width: 768px) {
        .roster-grid {
            grid-template-columns: 1fr !important;
        }
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
@endsection

@section('content')
<div class="content-inner">

    @if(request('method') == 'qr')
    <!-- DEDICATED QR LAYOUT -->
    <div class="roster-grid" style="display: grid; grid-template-columns: 400px 1fr; gap: 1.5rem; min-height: calc(100vh - 200px);">
        <div style="background: var(--card-bg); border: 1px solid var(--border); border-radius: 1.5rem; padding: 2.5rem; display: flex; flex-direction: column; align-items: center; text-align: center;">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.25rem;">Session QR Code</h3>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 2.5rem;">Regenerates every 10s for security</p>
            
            <div id="qrcode-display" style="width: 300px; height: 300px; background: white; padding: 1rem; border-radius: 1rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 40px rgba(0,0,0,0.08);"></div>
            
            <div style="width: 100%; height: 6px; background: var(--border); border-radius: 3px; overflow: hidden; margin-bottom: 2rem;">
                <div id="qr-timer-bar" style="height: 100%; width: 100%; background: #10b981;"></div>
            </div>
            
            <button onclick="openMethodModal()" style="background: transparent; padding: 0.85rem 2rem; border-radius: 0.75rem; border: 1px solid var(--border); color: var(--text-main); cursor: pointer; font-weight: 600; width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                <i data-lucide="arrow-left-right" style="width: 18px;"></i> Switch Method
            </button>
        </div>

        <div style="background: var(--card-bg); border: 1px solid var(--border); border-radius: 1.5rem; display: flex; flex-direction: column; max-height: calc(100vh - 200px);">
            <div style="padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-weight: 700; font-size: 1.1rem;">Live Feed</h3>
                <span id="present-badge" style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 0.35rem 1rem; border-radius: 2rem; font-weight: 700; font-size: 0.85rem;">
                    <span id="presentCountQR">{{ count($records) }}</span> / {{ $members->count() }} Present
                </span>
            </div>
            <div style="flex: 1; overflow-y: auto; padding: 1.5rem;" id="live-feed-list">
                @if($members->count() > 0)
                    @foreach($members as $member)
                    <div id="qr-st-{{ $member->member_id }}" style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; border-radius: 1rem; border: 1px solid {{ in_array($member->member_id, $records) ? '#10b981' : 'var(--border)' }}; margin-bottom: 0.75rem; background: {{ in_array($member->member_id, $records) ? 'rgba(16,185,129,0.05)' : 'transparent' }}; transition: all 0.3s;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div class="qr-avatar" style="width: 40px; height: 40px; border-radius: 50%; background: {{ in_array($member->member_id, $records) ? '#10b981' : 'var(--border)' }}; color: {{ in_array($member->member_id, $records) ? 'white' : 'var(--text-main)' }}; display: flex; align-items: center; justify-content: center; font-weight: 700; transition: all 0.3s;">
                                {{ strtoupper(substr($member->student->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight: 600;">{{ $member->student->name }}</div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $member->student->email }}</div>
                            </div>
                        </div>
                        <div class="qr-status-icon" style="color: {{ in_array($member->member_id, $records) ? '#10b981' : 'var(--text-muted)' }}; transition: all 0.3s;">
                            <i data-lucide="{{ in_array($member->member_id, $records) ? 'check-circle' : 'circle' }}"></i>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div style="text-align: center; color: var(--text-muted); padding: 2rem;">No members in this club.</div>
                @endif
            </div>
        </div>
    </div>
    
    @else
    
    <!-- STANDARD LAYOUT (Manual / Geo) -->
    <div class="meta-bar">
        <div class="meta-item">
            <span class="meta-label">Date</span>
            <span class="meta-value">{{ \Carbon\Carbon::parse($session->date)->format('M d, Y') }}</span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Time</span>
            <span class="meta-value">
                {{ $session->start_time ? \Carbon\Carbon::parse($session->start_time)->format('h:i A') : 'TBA' }}
                - 
                {{ $session->end_time ? \Carbon\Carbon::parse($session->end_time)->format('h:i A') : 'TBA' }}
            </span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Total Members</span>
            <span class="meta-value">{{ $members->count() }}</span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Present</span>
            <span class="meta-value" id="presentCount">{{ count($records) }}</span>
        </div>
    </div>
    
    @if($manager->role === 'admin' || $manager->hasPermission('attendance.take'))
    <div style="display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap;">
        <button onclick="openMethodModal()" style="background: var(--text-main); color: var(--bg); border: none; padding: 0.75rem 1.5rem; border-radius: 0.75rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; transition: opacity 0.2s;">
            <i data-lucide="arrow-left-right"></i> Switch Method
        </button>
        <button id="btnToggleGeo" style="background: {{ $session->is_geofencing ? 'rgba(16, 185, 129, 0.1)' : 'var(--card-bg)' }}; color: {{ $session->is_geofencing ? '#10b981' : 'var(--text-main)' }}; border: 1px solid {{ $session->is_geofencing ? '#10b981' : 'var(--border)' }}; padding: 0.75rem 1.5rem; border-radius: 0.75rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; transition: all 0.2s;">
            <i data-lucide="map-pin"></i> {{ $session->is_geofencing ? 'Geo-Location Active' : 'Require Geo-Location' }}
        </button>
    </div>
    @endif
    
    @if($members->count() > 0)
    <div style="overflow-x: auto;">
        <table class="members-table">
            <thead>
                <tr>
                    <th>Member</th>
                    <th>Email / Roll No</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $member)
                <tr id="st-{{ $member->member_id }}">
                    <td>
                        <div class="member-info">
                            <div class="member-avatar">
                                {{ strtoupper(substr($member->student->name, 0, 1)) }}
                            </div>
                            <div style="font-weight: 600;">{{ $member->student->name }}</div>
                        </div>
                    </td>
                    <td style="color: var(--text-muted);">
                        {{ $member->student->email }}
                    </td>
                    <td>
                        @php
                            $canTake = $manager->role === 'admin' || $manager->hasPermission('attendance.take');
                        @endphp
                        <label class="switch">
                            <input type="checkbox" class="attendance-toggle" 
                                id="toggle-{{ $member->member_id }}"
                                data-student="{{ $member->member_id }}"
                                {{ in_array($member->member_id, $records) ? 'checked' : '' }}
                                {{ !$canTake ? 'disabled' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="text-align: center; padding: 3rem; background: var(--card-bg); border-radius: 1rem; border: 1px solid var(--border);">
        <h3 style="margin-bottom: 0.5rem; color: var(--text-main);">No Students Found</h3>
        <p style="color: var(--text-muted);">Add student members to your club to track their attendance.</p>
    </div>
    @endif
    
    @endif
    
    <!-- Method Selection Modal -->
    <div id="methodModal" class="method-modal">
        <div class="method-modal-content">
            <h3 style="margin-top: 0; margin-bottom: 1.5rem; color: var(--text-main); font-size: 1.25rem;">Select Attendance Method</h3>
            
            <div class="method-option {{ request('method') == 'qr' ? 'selected' : '' }}" data-method="qr" onclick="selectMethod('qr', this)">
                <i data-lucide="qr-code" style="width: 24px; height: 24px;"></i>
                <div>
                    <div style="font-weight: 600; color: var(--text-main);">QR Code</div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">Students scan a dynamic QR code</div>
                </div>
            </div>
            
            <div class="method-option {{ request('method') == 'geo' ? 'selected' : '' }}" data-method="geo" onclick="selectMethod('geo', this)">
                <i data-lucide="map-pin" style="width: 24px; height: 24px;"></i>
                <div>
                    <div style="font-weight: 600; color: var(--text-main);">Geo-Location</div>
                    <div style="font-weight: 400; font-size: 0.8rem; color: var(--text-muted);">Require students to be in physical proximity</div>
                </div>
            </div>
            
            <div class="method-option {{ (request('method') == 'manual' || !request('method')) ? 'selected' : '' }}" data-method="manual" onclick="selectMethod('manual', this)">
                <i data-lucide="check-square" style="width: 24px; height: 24px;"></i>
                <div>
                    <div style="font-weight: 600; color: var(--text-main);">Manual</div>
                    <div style="font-weight: 400; font-size: 0.8rem; color: var(--text-muted);">Mark attendance manually from a list</div>
                </div>
            </div>
            
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button onclick="closeMethodModal()" style="flex: 1; padding: 0.75rem; border-radius: 0.75rem; border: 1px solid var(--border); background: transparent; color: var(--text-main); font-weight: 600; cursor: pointer;">Cancel</button>
                <button onclick="switchSessionMethod()" style="flex: 1; padding: 0.75rem; border-radius: 0.75rem; border: none; background: var(--text-main); color: var(--bg); font-weight: 600; cursor: pointer;">Switch</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const csrfToken = '{{ csrf_token() }}';
    const isQrMode = {{ request('method') == 'qr' ? 'true' : 'false' }};
    const currentSessionUuid = '{{ $session->uuid }}';
    
    // --- Manual Attendance Toggle Logic ---
    if(!isQrMode) {
        const markUrl = "{{ route('club.attendance.mark', $session->id) }}";
        let presentCount = {{ count($records) }};
        const countEl = document.getElementById('presentCount');

        document.querySelectorAll('.attendance-toggle').forEach(toggle => {
            toggle.addEventListener('change', async function() {
                const studentId = this.dataset.student;
                const status = this.checked ? 'present' : 'absent';
                
                if(status === 'present') presentCount++;
                else presentCount--;
                countEl.textContent = presentCount;
                
                this.disabled = true;
                
                try {
                    const response = await fetch(markUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            student_id: studentId,
                            status: status
                        })
                    });
                    
                    const data = await response.json();
                    if(!data.success) throw new Error(data.message || 'Failed to update');
                } catch (error) {
                    console.error(error);
                    alert('Failed to update attendance: ' + error.message);
                    this.checked = !this.checked;
                    if(status === 'present') presentCount--;
                    else presentCount++;
                    countEl.textContent = presentCount;
                } finally {
                    this.disabled = false;
                }
            });
        });
    }

    // --- QR Code Logic ---
    const qrRefreshUrl = "{{ route('club.attendance.qr.refresh', $session->id) }}";
    let qrInterval = null;
    let qrcodeObj = null;
    
    function refreshQR() {
        const timerBar = document.getElementById('qr-timer-bar');
        timerBar.style.transition = 'none';
        timerBar.style.width = '100%';
        
        fetch(qrRefreshUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
        }).then(res => res.json()).then(data => {
            if(data.success) {
                const container = document.getElementById("qrcode-display");
                if(!qrcodeObj) {
                    qrcodeObj = new QRCode(container, {
                        width: 300, height: 300, correctLevel: QRCode.CorrectLevel.H
                    });
                }
                qrcodeObj.clear();
                qrcodeObj.makeCode(data.payload);
                
                setTimeout(() => {
                    timerBar.style.transition = 'width 10s linear';
                    timerBar.style.width = '0%';
                }, 50);
            }
        });
    }

    if(isQrMode) {
        refreshQR();
        qrInterval = setInterval(refreshQR, 10000);
    }

    // --- Geo-Location Logic ---
    const btnToggleGeo = document.getElementById('btnToggleGeo');
    let isGeoActive = {{ $session->is_geofencing ? 'true' : 'false' }};
    const geoToggleUrl = "{{ route('club.attendance.geo.toggle', $session->id) }}";
    
    if(btnToggleGeo) {
        btnToggleGeo.addEventListener('click', () => {
            if(!isGeoActive) {
                if (navigator.geolocation) {
                    btnToggleGeo.innerHTML = `<i data-lucide="loader" class="spin"></i> Locating...`;
                    lucide.createIcons();
                    navigator.geolocation.getCurrentPosition(
                        (position) => toggleGeo(true, position.coords.latitude, position.coords.longitude),
                        (error) => { alert("Failed to get location: " + error.message); resetGeoBtn(); }
                    );
                } else {
                    alert("Geolocation is not supported by this browser.");
                }
            } else {
                toggleGeo(false, null, null);
            }
        });
    }
    
    function toggleGeo(active, lat, lng) {
        fetch(geoToggleUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ is_geofencing: active, latitude: lat, longitude: lng })
        }).then(res => res.json()).then(data => {
            if(data.success) {
                isGeoActive = active;
                resetGeoBtn();
            }
        });
    }
    
    function resetGeoBtn() {
        if(isGeoActive) {
            btnToggleGeo.style.background = 'rgba(16, 185, 129, 0.1)';
            btnToggleGeo.style.color = '#10b981';
            btnToggleGeo.style.borderColor = '#10b981';
            btnToggleGeo.innerHTML = `<i data-lucide="map-pin"></i> Geo-Location Active`;
        } else {
            btnToggleGeo.style.background = 'var(--card-bg)';
            btnToggleGeo.style.color = 'var(--text-main)';
            btnToggleGeo.style.borderColor = 'var(--border)';
            btnToggleGeo.innerHTML = `<i data-lucide="map-pin"></i> Require Geo-Location`;
        }
        lucide.createIcons();
    }
    
    // --- WebSockets integration for Live QR updates ---
    let qrPresentCount = {{ count($records) }};
    
    if (window.Echo) {
        window.Echo.join('attendance.session.' + currentSessionUuid)
            .here((users) => {
                console.log('Joined live session. Active users:', users);
            })
            .listen('.LiveAttendanceAction', (e) => {
                if (e.action === 'student_joined') {
                    markStudentLive(e.payload.student_id);
                }
            });
    }
    
    function markStudentLive(studentId) {
        if(isQrMode) {
            const row = document.getElementById('qr-st-' + studentId);
            if(row && row.style.backgroundColor !== 'rgba(16, 185, 129, 0.05)') {
                qrPresentCount++;
                document.getElementById('presentCountQR').textContent = qrPresentCount;
                
                row.style.borderColor = '#10b981';
                row.style.background = 'rgba(16, 185, 129, 0.05)';
                
                const avatar = row.querySelector('.qr-avatar');
                if(avatar) {
                    avatar.style.background = '#10b981';
                    avatar.style.color = 'white';
                }
                
                const icon = row.querySelector('.qr-status-icon');
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
        } else {
            const toggle = document.getElementById('toggle-' + studentId);
            if (toggle && !toggle.checked) {
                toggle.checked = true;
                toggle.disabled = true;
                
                const countEl = document.getElementById('presentCount');
                if(countEl) countEl.textContent = parseInt(countEl.textContent) + 1;
                
                const row = document.getElementById('st-' + studentId);
                if(row) {
                    row.style.transition = 'all 0.5s ease';
                    row.style.background = 'rgba(16, 185, 129, 0.15)';
                    setTimeout(() => {
                        row.style.background = 'transparent';
                    }, 2000);
                }
            }
        }
    }
    
    @if(request('method') == 'geo' && !$session->is_geofencing)
        setTimeout(() => {
            if(btnToggleGeo) btnToggleGeo.click();
        }, 500);
    @endif
    
    // --- Modal Logic ---
    let selectedMethod = '{{ request('method') ?: 'manual' }}';
    function selectMethod(method, element) {
        selectedMethod = method;
        document.querySelectorAll('.method-option').forEach(el => el.classList.remove('selected'));
        element.classList.add('selected');
    }

    function openMethodModal() {
        document.getElementById('methodModal').style.display = 'flex';
    }

    function closeMethodModal() {
        document.getElementById('methodModal').style.display = 'none';
    }

    function switchSessionMethod() {
        if (selectedMethod === 'geo') {
            window.location.href = "{{ route('club.attendance.geo.session', ['session_id' => $session->id]) }}";
        } else {
            window.location.href = "{{ route('club.attendance.manage', ['session_id' => $session->id]) }}?method=" + selectedMethod;
        }
    }
    
    document.getElementById('methodModal').addEventListener('click', function(e) {
        if(e.target === this) {
            closeMethodModal();
        }
    });
</script>
@endsection
