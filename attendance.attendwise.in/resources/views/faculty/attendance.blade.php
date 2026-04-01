@extends('layouts.faculty')

@section('header-title', 'Student Attendance')
@section('header-subtitle', 'Select an active class schedule to mark and review student presence.')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<div class="card" style="padding: 2.5rem; max-width: 1400px; margin: 0 auto; border-radius: 1rem;">
    <!-- Course Selection Header -->
    <div style="margin-bottom: 2.5rem; display: flex; flex-direction: column; gap: 1.5rem;">
        <div>
            <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.75rem; letter-spacing: 0.05em;">Select Active Schedule</label>
            <div style="position: relative; max-width: 400px;">
                <form action="{{ route('faculty.attendance') }}" method="GET" id="schedule-form">
                    <select name="schedule" onchange="document.getElementById('schedule-form').submit()" style="width: 100%; padding: 0.75rem 1rem; padding-right: 2.5rem; background: var(--bg); border: 1px solid var(--border); border-radius: 0.5rem; color: var(--text-main); font-weight: 500; font-size: 0.9rem; -webkit-appearance: none; cursor: pointer;">
                        <option value="">Select a course...</option>
                        @foreach($upcomingLectures ?? [] as $lecture)
                        <option value="{{ $lecture->id }}" {{ (isset($selectedSchedule) && $selectedSchedule->id == $lecture->id) ? 'selected' : '' }}>
                            {{ $lecture->subject->name ?? 'Course Title' }} ({{ date('h:i A', strtotime($lecture->start_time)) }})
                        </option>
                        @endforeach
                    </select>
                </form>
                <div style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); pointer-events: none; color: var(--text-muted);">
                    <i data-lucide="chevron-down" style="width: 16px; height: 16px;"></i>
                </div>
            </div>
        </div>
        
        @if($selectedSchedule)
        <div style="display: flex; justify-content: space-between; align-items: flex-end; border-top: 1px solid var(--border); padding-top: 2rem;">
            <div>
                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main);">{{ $selectedSchedule->subject->name }}</h3>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.25rem;">
                    {{ date('l, d F Y') }} | {{ date('h:i A', strtotime($selectedSchedule->start_time)) }} - {{ date('h:i A', strtotime($selectedSchedule->end_time)) }}
                </p>
                <p style="font-size: 0.8rem; color: var(--accent); margin-top: 0.25rem;">
                    Type: {{ $selectedSchedule->lecture_type }} | 
                    Block: {{ $selectedSchedule->classroom->block->name ?? 'Main' }} | 
                    Room: {{ $selectedSchedule->classroom->name ?? 'N/A' }} | 
                    Sec: {{ $selectedSchedule->section->name ?? 'N/A' }} |
                    Students: {{ count($students) }}
                </p>
            </div>
            <div style="display: flex; gap: 0.75rem;">
                <button type="button" onclick="markAll('present')" class="btn-primary" style="background: var(--subtle-bg); color: var(--text-main); border: 1px solid var(--border); font-size: 0.85rem;">
                    <i data-lucide="check-check" style="width: 14px;"></i>
                    Mark All Present
                </button>
            </div>
        </div>
        @endif
    </div>

    @if($selectedSchedule)
    <div id="method-modal-overlay" style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(8px); z-index: 9999; display: flex; align-items: center; justify-content: center; opacity: 1; transition: opacity 0.3s ease;">
        <div class="card" style="width: 100%; max-width: 800px; padding: 3rem; background: var(--bg); border: 1px solid var(--border); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); border-radius: 1.5rem; position: relative;">
            <div style="text-align: center; margin-bottom: 2.5rem;">
                <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-main); letter-spacing: -0.02em;">Choose Attendance Method</h2>
                <p style="color: var(--text-muted); margin-top: 0.5rem;">Select how you want to verify student presence for this session.</p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;">
                <!-- QR Code Option -->
                <div onclick="selectMethod('qr')" class="method-card" id="qr-card">
                    <div class="icon-box" style="background: rgba(99, 102, 241, 0.1); color: #6366f1;">
                        <i data-lucide="qr-code" style="width: 24px; height: 24px;"></i>
                    </div>
                    <h3 style="font-size: 1rem; font-weight: 700; margin-bottom: 0.5rem;">QR Code</h3>
                    <p style="font-size: 0.75rem; color: var(--text-muted); line-height: 1.4;">Students scan a dynamic code generated for this session.</p>
                </div>

                <!-- Geo Location Option -->
                <div onclick="selectMethod('geo')" class="method-card" id="geo-card">
                    <div class="icon-box" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i data-lucide="map-pin" style="width: 24px; height: 24px;"></i>
                    </div>
                    <h3 style="font-size: 1rem; font-weight: 700; margin-bottom: 0.5rem;">Geo Location</h3>
                    <p style="font-size: 0.75rem; color: var(--text-muted); line-height: 1.4;">Verify students are physically present within classroom range.</p>
                </div>

                <!-- Manual Option -->
                <div onclick="selectMethod('manual')" class="method-card" id="manual-card">
                    <div class="icon-box" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                        <i data-lucide="user-check" style="width: 24px; height: 24px;"></i>
                    </div>
                    <h3 style="font-size: 1rem; font-weight: 700; margin-bottom: 0.5rem;">Manual</h3>
                    <p style="font-size: 0.75rem; color: var(--text-muted); line-height: 1.4;">Mark students manually using the digital roster checklist.</p>
                </div>
            </div>

            <div style="margin-top: 2.5rem; text-align: center;">
                <button type="button" id="start-attendance-btn" onclick="startAttendance()" disabled class="btn-primary" style="padding: 1rem 3rem; font-size: 1rem; border-radius: 0.75rem; opacity: 0.5; cursor: not-allowed; width: 220px;">
                    Start Session
                </button>
            </div>
        </div>
    </div>

    <div id="qr-roster-container" style="display: none;">
        <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 2rem;">
            <!-- Left: QR Code & Multi-Verification -->
            <div class="card" style="display: flex; flex-direction: column; align-items: center; padding: 2.5rem; text-align: center; background: var(--bg); border: 1px solid var(--border); border-radius: 1.5rem;">
                <div style="margin-bottom: 1.5rem;">
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main);">Session QR Code</h3>
                    <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 0.25rem;">Regenerates every 8s for security</p>
                </div>
                
                <div id="qrcode-display" style="padding: 1.5rem; background: white; border-radius: 1.25rem; box-shadow: 0 10px 40px rgba(0,0,0,0.08); border: 1px solid #eee; width: fit-content;"></div>
                
                <div style="margin-top: 1.5rem; width: 100%; max-width: 350px;">
                    <div id="qr-timer-bar-container" style="height: 6px; background: #eee; width: 100%; border-radius: 10px; overflow: hidden; margin-bottom: 2rem;">
                        <div id="qr-timer-bar" style="height: 100%; background: var(--accent); width: 100%; transition: width 8s linear;"></div>
                    </div>

                    <!-- Multi-Verification Toggles -->
                    <div style="width: 100%; text-align: left;">
                        <label style="display: block; font-size: 0.7rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 1rem; letter-spacing: 0.05em;">Advanced Verification</label>
                        
                        <div class="switch-container">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <i data-lucide="map" style="width: 16px; color: #10b981;"></i>
                                <span style="font-size: 0.85rem; font-weight: 600;">GeoFencing Range</span>
                            </div>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="switch-container">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <i data-lucide="bluetooth" style="width: 16px; color: #6366f1;"></i>
                                <span style="font-size: 0.85rem; font-weight: 600;">Bluetooth Proximity</span>
                            </div>
                            <label class="switch">
                                <input type="checkbox">
                                <span class="slider"></span>
                            </label>
                        </div>

                        <button type="button" class="btn-primary" style="width: 100%; margin-top: 1rem; padding: 0.75rem; background: #000; color: #fff; border: none; border-radius: 0.75rem; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 0.75rem;">
                            <i data-lucide="scan-eye" style="width: 18px;"></i>
                            Start Face Recognition
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right: Live Students -->
            <div class="card" style="background: var(--bg); border: 1px solid var(--border); border-radius: 1.5rem; display: flex; flex-direction: column;">
                <div style="padding: 1.5rem 2rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-size: 1rem; font-weight: 700;">Live Feed</h3>
                    <span id="present-count-badge" style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 0.25rem 0.75rem; border-radius: 2rem; font-size: 0.75rem; font-weight: 700;">0 Scanned</span>
                </div>
                <div style="flex: 1; overflow-y: auto; padding: 1.5rem;" id="qr-student-list">
                    @foreach($students as $student)
                    <div class="qr-student-row" id="st-{{ $student->id }}" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; border-radius: 1rem; margin-bottom: 0.75rem; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); opacity: 0.4; border: 1px solid transparent;">
                        <div class="status-indicator" style="width: 10px; height: 10px; border-radius: 50%; background: #ddd; transition: all 0.3s;"></div>
                        <div style="flex: 1;">
                            <div style="font-size: 0.9rem; font-weight: 600; color: var(--text-main);">{{ $student->name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $student->roll_number }}</div>
                        </div>
                        <div class="check-icon" style="display: none; color: #10b981;">
                            <i data-lucide="check-circle-2" style="width: 18px;"></i>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div style="padding: 1.5rem; border-top: 1px solid var(--border); text-align: center;">
                    <button onclick="window.location.reload()" class="btn-primary" style="padding: 0.85rem 2.5rem; font-size: 0.9rem; border-radius: 0.75rem;">
                        Close Attendance Session
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="manual-roster-container" style="display: none;">
        <form action="{{ route('faculty.attendance.submit') }}" method="POST">
            @csrf
            <input type="hidden" name="schedule_id" value="{{ $selectedSchedule->id }}">
            
            <!-- Student Attendance List -->
            <div style="border: 1px solid var(--border); border-radius: 0.75rem; overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: var(--subtle-bg);">
                        <tr>
                            <th style="padding: 1.25rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Roll No</th>
                            <th style="padding: 1.25rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Student Full Name</th>
                            <th style="padding: 1.25rem 1.5rem; text-align: right; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        @php
                            $status = $existingRecords[$student->id] ?? 'present';
                        @endphp
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 1.25rem 1.5rem; font-size: 0.9rem; font-weight: 500; color: var(--text-muted);">{{ $student->roll_number }}</td>
                            <td style="padding: 1.25rem 1.5rem;">
                                <div style="font-size: 0.95rem; font-weight: 600; color: var(--text-main);">{{ $student->name }}</div>
                            </td>
                            <td style="padding: 1.25rem 1.5rem; text-align: right;">
                                <div style="display: inline-flex; background: var(--subtle-bg); border: 1px solid var(--border); border-radius: 0.6rem; padding: 0.25rem;">
                                    <input type="radio" name="attendance[{{ $student->id }}]" value="present" id="p{{ $student->id }}" class="status-radio" style="display:none" {{ $status == 'present' ? 'checked' : '' }}>
                                    <label for="p{{ $student->id }}" class="status-btn p-btn">P</label>
                                    
                                    <input type="radio" name="attendance[{{ $student->id }}]" value="absent" id="a{{ $student->id }}" class="status-radio" style="display:none" {{ $status == 'absent' ? 'checked' : '' }}>
                                    <label for="a{{ $student->id }}" class="status-btn a-btn">A</label>
                                    
                                    <input type="radio" name="attendance[{{ $student->id }}]" value="late" id="l{{ $student->id }}" class="status-radio" style="display:none" {{ $status == 'late' ? 'checked' : '' }}>
                                    <label for="l{{ $student->id }}" class="status-btn l-btn">L</label>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="padding: 3rem; text-align: center; color: var(--text-muted);">No students found for this section.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(count($students) > 0)
            <!-- Action Bar -->
            <div style="margin-top: 2.5rem; border-top: 1px solid var(--border); padding-top: 2.5rem; display: flex; justify-content: flex-end; gap: 1rem;">
                <a href="{{ route('faculty.dashboard') }}" class="btn-primary" style="background: transparent; color: var(--text-main); border: 1px solid var(--border); padding: 0.8rem 1.5rem;">
                    Cancel
                </a>
                <button type="submit" class="btn-primary" style="padding: 0.8rem 2rem; font-size: 0.95rem;">
                    {{ count($existingRecords) > 0 ? 'Update Attendance Record' : 'Submit Attendance Record' }}
                </button>
            </div>
            @endif
        </form>
    </div>
    </div>
    @else
    <div style="padding: 5rem 2rem; text-align: center; color: var(--text-muted); border: 1px dashed var(--border); border-radius: 1rem;">
        <i data-lucide="info" style="width: 48px; height: 48px; margin-bottom: 1.5rem; opacity: 0.5;"></i>
        <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-main);">No Schedule Selected</h3>
        <p style="margin-top: 0.5rem;">Please select a lecture from the dropdown above to start marking attendance.</p>
    </div>
    @endif
</div>

<style>
    .method-card {
        padding: 2rem 1.5rem;
        background: var(--subtle-bg);
        border: 2px solid transparent;
        border-radius: 1.25rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
    }
    .method-card:hover { transform: translateY(-5px); border-color: var(--border); }
    .method-card.selected { border-color: var(--accent); background: var(--bg); box-shadow: 0 10px 30px -10px rgba(99, 102, 241, 0.2); }
    .icon-box { padding: 1rem; border-radius: 1rem; margin-bottom: 0.5rem; }
    .status-btn {
        padding: 0.4rem 1rem;
        font-size: 0.75rem;
        font-weight: 700;
        border-radius: 0.4rem;
        cursor: pointer;
        transition: 0.2s;
        display: inline-block;
        color: var(--text-muted);
    }
    .status-radio:checked + .p-btn { background: #10b981; color: white; }
    .status-radio:checked + .a-btn { background: #ef4444; color: white; }
    .status-radio:checked + .l-btn { background: #f59e0b; color: white; }

    /* Toggle Switch Styles */
    .switch-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 1rem;
        background: var(--subtle-bg);
        border: 1px solid var(--border);
        border-radius: 0.75rem;
        margin-bottom: 0.75rem;
        transition: all 0.3s;
    }
    .switch-container:hover { border-color: var(--accent); }
    .switch {
        position: relative;
        display: inline-block;
        width: 38px;
        height: 22px;
    }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #ddd;
        transition: .4s;
        border-radius: 34px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .slider { background-color: #10b981; }
    input:checked + .slider:before { transform: translateX(16px); }
</style>

<script>
    let qrSyncInterval;
    let qrRefreshInterval;
    let qrTimerTimeout;
    let currentSessionUuid = null;
    let qrcode = null;

    function markAll(status) {
        document.querySelectorAll('.status-radio[value="' + status + '"]').forEach(radio => {
            radio.checked = true;
        });
    }

    function selectMethod(method) {
        document.querySelectorAll('.method-card').forEach(card => card.classList.remove('selected'));
        document.getElementById(method + '-card').classList.add('selected');
        const btn = document.getElementById('start-attendance-btn');
        btn.disabled = false;
        btn.style.opacity = '1';
        btn.style.cursor = 'pointer';
        btn.dataset.method = method;
        const labels = {
            'qr': 'Generate QR Code',
            'geo': 'Activate Geo-Fence',
            'manual': 'Open Digital Roster'
        };
        btn.innerText = labels[method];
    }

    function startAttendance() {
        const method = document.getElementById('start-attendance-btn').dataset.method;
        const overlay = document.getElementById('method-modal-overlay');
        const scheduleId = {{ $selectedSchedule->id ?? 'null' }};
        
        if (method === 'manual') {
            overlay.style.opacity = '0';
            setTimeout(() => {
                overlay.style.display = 'none';
                document.getElementById('manual-roster-container').style.display = 'block';
            }, 300);
        } else if (method === 'qr') {
            fetch('{{ route("faculty.attendance.qr.init") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ schedule_id: scheduleId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    currentSessionUuid = data.uuid;
                    overlay.style.opacity = '0';
                    setTimeout(() => {
                        overlay.style.display = 'none';
                        document.getElementById('qr-roster-container').style.display = 'block';
                        initQR();
                    }, 300);
                }
            });
        }
    }

    function initQR() {
        const container = document.getElementById("qrcode-display");
        container.innerHTML = ""; // Clear
        qrcode = new QRCode(container, {
            width: 320,
            height: 320,
            correctLevel: QRCode.CorrectLevel.H
        });
        
        refreshQR();
        qrRefreshInterval = setInterval(refreshQR, 8000);
        qrSyncInterval = setInterval(syncStudents, 2000);
    }

    function refreshQR() {
        const timerBar = document.getElementById('qr-timer-bar');
        timerBar.style.transition = 'none';
        timerBar.style.width = '100%';
        
        fetch('{{ route("faculty.attendance.qr.refresh") }}', {
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
                qrcode.makeCode(data.payload);
                setTimeout(() => {
                    timerBar.style.transition = 'width 8s linear';
                    timerBar.style.width = '0%';
                }, 50);
            }
        });
    }

    function syncStudents() {
        fetch('{{ route("faculty.attendance.qr.students") }}?uuid=' + currentSessionUuid)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const presentIds = data.present_student_ids;
                document.getElementById('present-count-badge').innerText = presentIds.length + ' Present';
                
                presentIds.forEach(id => {
                    const row = document.getElementById('st-' + id);
                    if (row) {
                        row.style.opacity = '1';
                        row.style.background = 'rgba(16, 185, 129, 0.04)';
                        row.style.borderColor = 'rgba(16, 185, 129, 0.2)';
                        row.querySelector('.status-indicator').style.background = '#10b981';
                        row.querySelector('.status-indicator').style.boxShadow = '0 0 12px #10b981';
                        row.querySelector('.check-icon').style.display = 'block';
                    }
                });
            }
        });
    }
</script>
@endsection
