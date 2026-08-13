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
                @if(isset($existingSession))
                <form id="reset-session-form" action="{{ route('faculty.attendance.reset') }}" method="POST">
                    @csrf
                    <input type="hidden" name="schedule_id" value="{{ $selectedSchedule->id }}">
                    <button type="button" onclick="showResetModal()" class="btn-primary" style="background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; font-size: 0.85rem;">
                        <i data-lucide="rotate-ccw" style="width: 14px;"></i>
                        Reset Session
                    </button>
                </form>
                @endif
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

            <div class="methods-grid">
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
        <div class="roster-grid">
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
                                <input type="checkbox" id="geofence-checkbox" onchange="toggleGeofencing()" checked>
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

                        <button type="button" onclick="startOcrScanner()" class="btn-primary" style="width: 100%; margin-top: 0.75rem; padding: 0.75rem; background: #4f46e5; color: #fff; border: none; border-radius: 0.75rem; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 0.75rem; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);">
                            <i data-lucide="scan-text" style="width: 18px;"></i>
                            Scan ID via OCR
                        </button>
                    </div>
                    <!-- Session Actions -->
                    <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border); display: flex; flex-direction: column; gap: 0.75rem; width: 100%;">
                        <button onclick="closeAttendanceSession()" class="btn-primary" style="width: 100%; padding: 0.85rem; font-size: 0.9rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                            Close Attendance Session
                        </button>
                        <button type="button" onclick="switchMethod()" class="btn-primary" style="width: 100%; background: transparent; color: var(--text-main); border: 1px solid var(--border); padding: 0.85rem; font-size: 0.9rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                            <i data-lucide="arrow-left-right" style="width: 16px;"></i> Switch Method
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right: Live Students -->
            <div class="card" style="background: var(--bg); border: 1px solid var(--border); border-radius: 1.5rem; display: flex; flex-direction: column;">
                <div style="padding: 1.5rem 2rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <h3 style="font-size: 1rem; font-weight: 700;">Live Feed</h3>
                        <button type="button" onclick="syncStudents(true)" title="Force Refresh Attendance Feed" style="background: rgba(99, 102, 241, 0.1); border: 1px solid rgba(99, 102, 241, 0.2); cursor: pointer; color: #6366f1; display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 0.4rem; transition: background 0.2s;">
                            <i data-lucide="refresh-cw" style="width: 14px; height: 14px;" id="sync-refresh-icon"></i>
                        </button>
                    </div>
                    <span id="present-count-badge" style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 0.25rem 0.75rem; border-radius: 2rem; font-size: 0.75rem; font-weight: 700;">0 Scanned</span>
                </div>
                <div style="padding: 1rem 2rem; border-bottom: 1px solid var(--border); background: var(--subtle-bg);">
                    <div style="position: relative;">
                        <i data-lucide="search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 16px; color: var(--text-muted);"></i>
                        <input type="text" id="live-search-input" placeholder="Search live feed..." style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border: 1px solid var(--border); border-radius: 0.5rem; background: var(--bg); color: var(--text-main); font-size: 0.85rem;" onkeyup="filterLiveStudents()">
                    </div>
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

            </div>
        </div>
    </div>
    <div id="manual-roster-container" style="display: none;">
        <form action="{{ route('faculty.attendance.submit') }}" method="POST">
            @csrf
            <input type="hidden" name="schedule_id" value="{{ $selectedSchedule->id }}">
            
            <!-- Search Bar -->
            <div style="margin-bottom: 1rem; position: relative;">
                <i data-lucide="search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 18px; color: var(--text-muted);"></i>
                <input type="text" id="student-search-input" placeholder="Search by name or roll number..." style="width: 100%; padding: 0.85rem 1rem 0.85rem 2.75rem; border: 1px solid var(--border); border-radius: 0.75rem; background: var(--subtle-bg); color: var(--text-main); font-size: 0.95rem; font-weight: 500;" onkeyup="filterStudents()">
            </div>
            
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
                        <tr class="student-row" style="border-bottom: 1px solid var(--border);">
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
            <div style="margin-top: 2.5rem; border-top: 1px solid var(--border); padding-top: 2.5rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
                <button type="button" onclick="switchMethod()" class="btn-primary" style="background: transparent; color: var(--text-main); border: 1px solid var(--border); padding: 0.8rem 1.5rem; font-size: 0.95rem;">
                    <i data-lucide="arrow-left-right" style="width: 16px; margin-right: 6px; display: inline-block; vertical-align: middle;"></i> Switch Method
                </button>
                <div style="display: flex; gap: 1rem;">
                    <a href="{{ route('faculty.dashboard') }}" class="btn-primary" style="background: transparent; color: var(--text-main); border: 1px solid var(--border); padding: 0.8rem 1.5rem;">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary" style="padding: 0.8rem 2rem; font-size: 0.95rem;">
                        {{ count($existingRecords) > 0 ? 'Update Attendance Record' : 'Submit Attendance Record' }}
                    </button>
                </div>
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

<!-- Custom Reset Confirmation Modal -->
<div id="reset-modal-overlay" style="position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(8px); z-index: 10000; display: none; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease;">
    <div class="card" style="width: 100%; max-width: 420px; padding: 2.5rem; background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); border-radius: 1.5rem; position: relative; text-align: center; transform: scale(0.95); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);" id="reset-modal-card">
        <div class="warning-icon-pulse" style="width: 64px; height: 64px; border-radius: 50%; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto;">
            <i data-lucide="alert-triangle" style="width: 32px; height: 32px;"></i>
        </div>
        <h2 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin-bottom: 0.75rem;">Reset Attendance?</h2>
        <p style="color: #64748b; font-size: 0.9rem; line-height: 1.5; margin-bottom: 2rem;">
            This action will cancel all current attendance records for this session. Students will need to scan the QR code again.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center;">
            <button type="button" onclick="hideResetModal()" class="btn-primary btn-reset-cancel" style="flex: 1; padding: 0.85rem; border-radius: 0.75rem;">
                Keep Records
            </button>
            <button type="button" onclick="confirmResetSession()" class="btn-primary btn-reset-confirm" style="flex: 1; padding: 0.85rem; border-radius: 0.75rem;">
                Yes, Reset
            </button>
        </div>
    </div>
</div>

<!-- Custom Close Session Confirmation Modal -->
<div id="close-modal-overlay" style="position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(8px); z-index: 10000; display: none; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease;">
    <div class="card" style="width: 100%; max-width: 420px; padding: 2.5rem; background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); border-radius: 1.5rem; position: relative; text-align: center; transform: scale(0.95); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);" id="close-modal-card">
        <div class="warning-icon-pulse" style="width: 64px; height: 64px; border-radius: 50%; background: rgba(99, 102, 241, 0.1); color: #6366f1; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto;">
            <i data-lucide="help-circle" style="width: 32px; height: 32px;"></i>
        </div>
        <h2 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin-bottom: 0.75rem;">End Active Session?</h2>
        <p style="color: #64748b; font-size: 0.9rem; line-height: 1.5; margin-bottom: 2rem;">
            Are you sure you want to end this attendance session? Students will no longer be able to submit their attendance.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center;">
            <button type="button" onclick="hideCloseModal()" class="btn-primary btn-reset-cancel" style="flex: 1; padding: 0.85rem; border-radius: 0.75rem;">
                Keep Open
            </button>
            <button type="button" onclick="confirmCloseSession()" class="btn-primary btn-close-confirm" style="flex: 1; padding: 0.85rem; border-radius: 0.75rem;">
                Yes, End Session
            </button>
        </div>
    </div>
</div>

<!-- Custom OCR ID Scanner Modal -->
<div id="ocr-modal-overlay" style="position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(8px); z-index: 10000; display: none; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease;">
    <div class="card" style="width: 100%; max-width: 480px; padding: 2rem; background: var(--card-bg); border: 1px solid var(--border); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); border-radius: 1.5rem; position: relative; display: flex; flex-direction: column; transform: scale(0.95); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);" id="ocr-modal-card">
        <button type="button" onclick="stopOcrScanner()" style="position: absolute; top: 1.25rem; right: 1.25rem; background: transparent; border: none; color: var(--text-muted); cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 0.25rem; border-radius: 0.375rem; transition: background 0.2s;" onmouseover="this.style.background='var(--hover-bg)'" onmouseout="this.style.background='transparent'">
            <i data-lucide="x" style="width: 20px; height: 20px;"></i>
        </button>
        
        <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.25rem; text-align: left; display: flex; align-items: center; gap: 0.5rem;">
            <i data-lucide="scan-text" style="color: #4f46e5; width: 22px; height: 22px;"></i>
            Scan Student ID Card
        </h2>
        <p style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 1.25rem; text-align: left;">
            Hold the student ID card in front of the camera. The system will detect and scan the Roll Number (e.g. RW03, RW000001) automatically.
        </p>
        
        <!-- Scanner Window Wrapper -->
        <div style="position: relative; width: 100%; height: 250px; background: #000; border-radius: 1rem; overflow: hidden; border: 1px solid var(--border);">
            <video id="ocr-video" autoplay playsinline style="width: 100%; height: 100%; object-fit: cover;"></video>
            
            <!-- Scanning Guidelines Overlay -->
            <div style="position: absolute; inset: 2rem; border: 2px dashed rgba(79, 70, 229, 0.6); border-radius: 0.5rem; pointer-events: none; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.4);">
                <!-- Laser Scanning Line -->
                <div class="ocr-scanner-line" style="position: absolute; left: 0; right: 0; height: 2px; background: #4f46e5; box-shadow: 0 0 10px #4f46e5; animation: scanLine 2s linear infinite;"></div>
            </div>
        </div>
        
        <!-- Status & Detections -->
        <div style="margin-top: 1rem; display: flex; flex-direction: column; gap: 0.5rem; text-align: left;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span id="ocr-status" style="font-size: 0.8rem; font-weight: 600; color: #4f46e5;">Initializing...</span>
                <span id="ocr-loader-spinner" style="display: none; width: 14px; height: 14px; border: 2px solid rgba(79, 70, 229, 0.2); border-top-color: #4f46e5; border-radius: 50%; animation: spin 0.8s linear infinite;"></span>
            </div>
            
            <div style="background: var(--subtle-bg); border: 1px solid var(--border); border-radius: 0.75rem; padding: 0.75rem; min-height: 50px; font-family: monospace; font-size: 0.75rem; color: var(--text-muted); word-break: break-all; max-height: 70px; overflow-y: auto;" id="ocr-detected-text">
                [No text scanned yet]
            </div>
        </div>

        <!-- Manual Override / Correction input -->
        <div style="margin-top: 1.25rem; display: flex; flex-direction: column; gap: 0.5rem; text-align: left;">
            <label style="font-size: 0.7rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Manual Verification / Correction</label>
            <div style="display: flex; gap: 0.5rem; width: 100%;">
                <input type="text" id="ocr-roll-input" placeholder="Roll Number (e.g. RW000001)" style="flex: 1; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.75rem; background: var(--bg); color: var(--text-main); font-size: 0.9rem; font-weight: 600; text-transform: uppercase;">
                <button type="button" onclick="submitManualOcr()" class="btn-primary" style="padding: 0.75rem 1.25rem; border-radius: 0.75rem; background: #4f46e5; color: #fff; border: none; font-weight: 600; display: flex; align-items: center; justify-content: center;">
                    Mark Present
                </button>
            </div>
            <div id="ocr-error-msg" style="color: #ef4444; font-size: 0.75rem; display: none;"></div>
            <div id="ocr-success-msg" style="color: #10b981; font-size: 0.75rem; display: none;"></div>
        </div>
    </div>
</div>

<style>
    /* Reset Modal Styles */
    .btn-reset-confirm {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border: none;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25);
        font-weight: 700;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-reset-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(220, 38, 38, 0.35);
        background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
    }
    .btn-close-confirm {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        border: none;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        font-weight: 700;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-close-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
        background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
    }
    .btn-reset-cancel {
        background: #ffffff;
        color: #475569;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        font-weight: 700;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        transition: all 0.2s ease;
    }
    .btn-reset-cancel:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #1e293b;
    }
    @keyframes pulse-red {
        0% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(220, 38, 38, 0); }
        100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
    }
    .warning-icon-pulse {
        animation: pulse-red 2s infinite;
    }

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
    
    @keyframes spin { 100% { transform: rotate(360deg); } }
    .spin-anim { animation: spin 1s linear infinite; }
    @keyframes scanLine {
        0% { top: 0%; }
        50% { top: 100%; }
        100% { top: 0%; }
    }

    .methods-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }
    .roster-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 2rem;
    }
    @media (max-width: 1024px) {
        .methods-grid, .roster-grid {
            grid-template-columns: 1fr;
        }
        
        .card {
            padding: 1.5rem !important;
        }
        
        #method-modal-overlay .card {
            padding: 1.5rem !important;
            margin: 1rem;
        }
        
        .student-row td {
            padding: 1rem 0.5rem !important;
        }
        
        .status-btn {
            padding: 0.4rem 0.6rem;
        }
    }
</style>

<script src="{{ asset('js/tesseract/tesseract.min.js') }}"></script>
<script>
    let qrRefreshInterval;
    let qrTimerTimeout;
    let currentSessionUuid = null;
    let qrcode = null;
    let echoChannel = null;

    function filterStudents() {
        const query = document.getElementById('student-search-input').value.toLowerCase();
        const rows = document.querySelectorAll('.student-row');
        
        rows.forEach(row => {
            const rollNo = row.querySelector('td:nth-child(1)').innerText.toLowerCase();
            const name = row.querySelector('td:nth-child(2) div').innerText.toLowerCase();
            
            if (rollNo.includes(query) || name.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function filterLiveStudents() {
        const query = document.getElementById('live-search-input').value.toLowerCase();
        const rows = document.querySelectorAll('.qr-student-row');
        
        rows.forEach(row => {
            // Find the div containing the name and roll number text
            const nameEl = row.querySelector('div:nth-child(2) > div:nth-child(1)');
            const rollNoEl = row.querySelector('div:nth-child(2) > div:nth-child(2)');
            
            if (nameEl && rollNoEl) {
                const name = nameEl.innerText.toLowerCase();
                const rollNo = rollNoEl.innerText.toLowerCase();
                if (name.includes(query) || rollNo.includes(query)) {
                    row.style.display = 'flex';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    }

    function showResetModal() {
        const modal = document.getElementById('reset-modal-overlay');
        const card = document.getElementById('reset-modal-card');
        modal.style.display = 'flex';
        // tiny delay to allow display block to apply before transitioning opacity
        setTimeout(() => {
            modal.style.opacity = '1';
            card.style.transform = 'scale(1)';
        }, 10);
    }

    function hideResetModal() {
        const modal = document.getElementById('reset-modal-overlay');
        const card = document.getElementById('reset-modal-card');
        modal.style.opacity = '0';
        card.style.transform = 'scale(0.95)';
        setTimeout(() => modal.style.display = 'none', 300);
    }

    function confirmResetSession() {
        document.getElementById('reset-session-form').submit();
    }

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
        } else if (method === 'qr' || method === 'geo') {
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
                        
                        const geofenceCheckbox = document.getElementById('geofence-checkbox');
                        if (geofenceCheckbox) {
                            geofenceCheckbox.checked = (method === 'geo');
                            toggleGeofencing();
                        }
                        
                        initQR();
                    }, 300);
                }
            });
        }
    }

    function switchMethod() {
        const overlay = document.getElementById('method-modal-overlay');
        overlay.style.display = 'flex';
        setTimeout(() => {
            overlay.style.opacity = '1';
        }, 50);
        document.getElementById('qr-roster-container').style.display = 'none';
        document.getElementById('manual-roster-container').style.display = 'none';
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
        
        // Initial sync
        syncStudents();
        
        // Setup Echo WebSocket
        if (window.Echo) {
            echoChannel = window.Echo.join('attendance.session.' + currentSessionUuid)
                .here((users) => {
                    console.log('Joined live session. Active users:', users);
                })
                .joining((user) => {
                    console.log('User joined:', user);
                })
                .leaving((user) => {
                    console.log('User left:', user);
                })
                .listen('.LiveAttendanceAction', (e) => {
                    if (e.action === 'student_joined') {
                        markStudentLive(e.payload.student_id);
                    }
                });
        }
    }

    function markStudentLive(studentId) {
        const row = document.getElementById('st-' + studentId);
        if (row && !row.classList.contains('marked')) {
            row.style.opacity = '1';
            row.style.background = 'rgba(16, 185, 129, 0.04)';
            row.style.borderColor = 'rgba(16, 185, 129, 0.2)';
            row.querySelector('.status-indicator').style.background = '#10b981';
            row.querySelector('.status-indicator').style.boxShadow = '0 0 12px #10b981';
            row.querySelector('.check-icon').style.display = 'block';
            row.classList.add('marked');
            
            const badge = document.getElementById('present-count-badge');
            const currentCount = parseInt(badge.innerText) || 0;
            badge.innerText = (currentCount + 1) + ' Scanned';
        }
    }

    function showCloseModal() {
        const modal = document.getElementById('close-modal-overlay');
        const card = document.getElementById('close-modal-card');
        modal.style.display = 'flex';
        setTimeout(() => {
            modal.style.opacity = '1';
            card.style.transform = 'scale(1)';
        }, 10);
    }

    function hideCloseModal() {
        const modal = document.getElementById('close-modal-overlay');
        const card = document.getElementById('close-modal-card');
        modal.style.opacity = '0';
        card.style.transform = 'scale(0.95)';
        setTimeout(() => modal.style.display = 'none', 300);
    }

    function confirmCloseSession() {
        hideCloseModal();
        executeCloseAttendanceSession();
    }

    function closeAttendanceSession() {
        if (!currentSessionUuid) return window.location.href = "{{ route('faculty.dashboard') }}";
        showCloseModal();
    }

    function executeCloseAttendanceSession() {
        fetch('{{ route("faculty.attendance.qr.close") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ uuid: currentSessionUuid })
        })
        .then(() => {
            window.location.href = "{{ route('faculty.dashboard') }}";
        })
        .catch(() => {
            window.location.href = "{{ route('faculty.dashboard') }}";
        });
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

    function syncStudents(isManual = false) {
        const icon = document.getElementById('sync-refresh-icon');
        if (isManual && icon) {
            icon.classList.add('spin-anim');
        }

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
                        row.classList.add('marked');
                    }
                });
            }
            if (isManual && icon) {
                setTimeout(() => {
                    icon.classList.remove('spin-anim');
                }, 500); // minimum spin duration for UI feedback
            }
        })
        .catch(() => {
            if (isManual && icon) {
                icon.classList.remove('spin-anim');
            }
        });
    }

    function toggleGeofencing() {
        if (!currentSessionUuid) return;
        const isChecked = document.getElementById('geofence-checkbox').checked;
        
        fetch('{{ route("faculty.attendance.qr.toggle_geofence") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                uuid: currentSessionUuid,
                is_geofencing: isChecked ? 1 : 0
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                console.log('Geofencing toggled:', data.is_geofencing);
            }
        })
        .catch(err => {
            console.error('Failed to toggle geofencing:', err);
        });
    }

    @if(isset($existingSession) && $existingSession->status === 'active')
    document.addEventListener('DOMContentLoaded', function() {
        currentSessionUuid = '{{ $existingSession->uuid }}';
        document.getElementById('method-modal-overlay').style.display = 'none';
        document.getElementById('qr-roster-container').style.display = 'block';
        
        const geofenceCheckbox = document.getElementById('geofence-checkbox');
        if (geofenceCheckbox) {
            geofenceCheckbox.checked = {{ $existingSession->is_geofencing ? 'true' : 'false' }};
        }
        
        initQR();
    });
    @endif
    let ocrStream = null;
    let ocrScanInterval = null;
    let ocrWorker = null;
    let isOcrProcessing = false;

    async function startOcrScanner() {
        document.getElementById('ocr-roll-input').value = '';
        document.getElementById('ocr-detected-text').innerText = '[No text scanned yet]';
        document.getElementById('ocr-error-msg').style.display = 'none';
        document.getElementById('ocr-success-msg').style.display = 'none';

        const overlay = document.getElementById('ocr-modal-overlay');
        const card = document.getElementById('ocr-modal-card');
        overlay.style.display = 'flex';
        lucide.createIcons();
        setTimeout(() => {
            overlay.style.opacity = '1';
            card.style.transform = 'scale(1)';
        }, 10);

        document.getElementById('ocr-status').innerText = 'Initializing camera...';
        try {
            ocrStream = await navigator.mediaDevices.getUserMedia({ 
                video: { facingMode: 'environment', width: { ideal: 1280 }, height: { ideal: 720 } } 
            });
            const video = document.getElementById('ocr-video');
            video.srcObject = ocrStream;
            video.setAttribute('playsinline', true);
            video.play();

            document.getElementById('ocr-status').innerText = 'Starting OCR Engine...';
            if (!ocrWorker) {
                document.getElementById('ocr-loader-spinner').style.display = 'inline-block';
                ocrWorker = await Tesseract.createWorker('eng', 1, {
                    workerPath: '{{ asset("js/tesseract/worker.min.js") }}',
                    langPath: '{{ asset("js/tesseract") }}',
                    corePath: '{{ asset("js/tesseract/core/tesseract-core.wasm.js") }}'
                });
                await ocrWorker.setParameters({
                    tessedit_pageseg_mode: '11', // SPARSE_TEXT
                });
                document.getElementById('ocr-loader-spinner').style.display = 'none';
            }

            document.getElementById('ocr-status').innerText = 'OCR Engine active. Scanning...';
            ocrScanInterval = setInterval(captureAndScanOcr, 1500);
        } catch (err) {
            console.error(err);
            document.getElementById('ocr-status').innerText = 'Camera access failed or worker failed.';
            document.getElementById('ocr-status').style.color = '#ef4444';
        }
    }

    async function captureAndScanOcr() {
        if (isOcrProcessing) return;
        const video = document.getElementById('ocr-video');
        if (!video || !video.videoWidth) return;

        isOcrProcessing = true;
        document.getElementById('ocr-loader-spinner').style.display = 'inline-block';
        
        const canvas = document.createElement('canvas');
        
        // Crop to the center region where the ID is expected
        const scanWidth = Math.min(video.videoWidth * 0.9, 800);
        const scanHeight = Math.min(video.videoHeight * 0.6, 400);
        const startX = (video.videoWidth - scanWidth) / 2;
        const startY = (video.videoHeight - scanHeight) / 2;

        canvas.width = scanWidth;
        canvas.height = scanHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, startX, startY, scanWidth, scanHeight, 0, 0, scanWidth, scanHeight);

        // Pre-process: Apply Grayscale filter (let Tesseract handle adaptive binarization internally)
        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const data = imageData.data;

        for (let i = 0; i < data.length; i += 4) {
            // Luminance formula
            const gray = 0.299 * data[i] + 0.587 * data[i+1] + 0.114 * data[i+2];
            
            data[i] = gray;
            data[i+1] = gray;
            data[i+2] = gray;
        }
        ctx.putImageData(imageData, 0, 0);

        try {
            const { data: { text } } = await ocrWorker.recognize(canvas);
            const cleanText = text.trim();
            document.getElementById('ocr-detected-text').innerText = cleanText || '[No text detected]';
            
            // Only attempt verification if we picked up at least some alphanumeric characters
            const alphanumericCount = (cleanText.match(/[a-zA-Z0-9]/g) || []).length;
            
            if (alphanumericCount >= 4) {
                document.getElementById('ocr-status').innerText = 'Analyzing detected text...';
                await submitOcrRollNumber(null, cleanText);
            } else {
                document.getElementById('ocr-status').innerText = 'Scanning...';
            }
        } catch (err) {
            console.error(err);
        } finally {
            isOcrProcessing = false;
            document.getElementById('ocr-loader-spinner').style.display = 'none';
        }
    }

    async function submitOcrRollNumber(rollNumber, ocrText = null) {
        const errorMsg = document.getElementById('ocr-error-msg');
        const successMsg = document.getElementById('ocr-success-msg');
        errorMsg.style.display = 'none';
        successMsg.style.display = 'none';

        try {
            const response = await fetch('{{ route("faculty.attendance.qr.mark_ocr") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    uuid: currentSessionUuid,
                    roll_number: rollNumber,
                    ocr_text: ocrText
                })
            });
            const data = await response.json();
            
            if (data.success) {
                successMsg.innerText = data.message;
                successMsg.style.display = 'block';
                document.getElementById('ocr-status').innerText = 'Student verified!';
                document.getElementById('ocr-status').style.color = '#10b981';
                
                // Autofill the input with the matched roll number for visual confirmation
                if (data.roll_number) {
                    document.getElementById('ocr-roll-input').value = data.roll_number;
                }
                
                if (data.student_id) {
                    markStudentLive(data.student_id);
                }
                
                // Do not close the scanner! Allow continuous scanning.
                // Just clear the success state after 2.5 seconds to prepare for the next ID.
                setTimeout(() => {
                    successMsg.style.display = 'none';
                    document.getElementById('ocr-roll-input').value = '';
                    document.getElementById('ocr-detected-text').innerText = '[Ready for next scan]';
                    document.getElementById('ocr-status').innerText = 'Scanning...';
                    document.getElementById('ocr-status').style.color = '#4f46e5';
                }, 2500);
            } else {
                if (rollNumber) {
                    // Only show error for manual submission, otherwise ignore and keep scanning
                    errorMsg.innerText = data.message || 'Verification failed.';
                    errorMsg.style.display = 'block';
                } else {
                    document.getElementById('ocr-status').innerText = 'Scanning...';
                    if (data.message) {
                        errorMsg.innerText = data.message;
                        errorMsg.style.display = 'block';
                    }
                    if (data.suggested_roll) {
                        document.getElementById('ocr-roll-input').value = data.suggested_roll;
                    }
                }
            }
        } catch (err) {
            console.error(err);
            if (rollNumber) {
                errorMsg.innerText = 'Server error verifying student.';
                errorMsg.style.display = 'block';
            }
        }
    }

    function submitManualOcr() {
        const inputVal = document.getElementById('ocr-roll-input').value.trim();
        if (!inputVal) {
            const errorMsg = document.getElementById('ocr-error-msg');
            errorMsg.innerText = 'Please enter a roll number.';
            errorMsg.style.display = 'block';
            return;
        }
        submitOcrRollNumber(inputVal);
    }

    function stopOcrScanner() {
        if (ocrScanInterval) {
            clearInterval(ocrScanInterval);
            ocrScanInterval = null;
        }
        if (ocrStream) {
            ocrStream.getTracks().forEach(track => track.stop());
            ocrStream = null;
        }
        const video = document.getElementById('ocr-video');
        if (video) {
            video.srcObject = null;
        }
        isOcrProcessing = false;
        
        const overlay = document.getElementById('ocr-modal-overlay');
        const card = document.getElementById('ocr-modal-card');
        overlay.style.opacity = '0';
        card.style.transform = 'scale(0.95)';
        setTimeout(() => {
            overlay.style.display = 'none';
            document.getElementById('ocr-loader-spinner').style.display = 'none';
        }, 300);
    }
</script>
@endsection
