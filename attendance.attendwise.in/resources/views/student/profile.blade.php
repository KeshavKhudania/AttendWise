@extends('layouts.student')

@section('title', 'Student Profile - AttendWise PWA')

@section('content')
<div style="margin-bottom: 16px;">
    <h3 style="font-weight: 800; font-size: 1.2rem; color: var(--text-main); margin-bottom: 4px;">Digital ID Card</h3>
    <p style="font-size: 0.78rem; color: var(--text-muted);">Your official institutional identification</p>
</div>

<!-- Virtual ID Card -->
<div class="glass-card" style="background: #ffffff; border: 1px solid #e0e0e0; position: relative; overflow: hidden; padding: 24px; box-shadow: 0 15px 35px rgba(0,0,0,0.06);">
    <!-- Background Icon Decoration -->
    <div style="position: absolute; right: -30px; top: -30px; font-size: 10rem; color: rgba(0, 0, 0, 0.05); pointer-events: none;">
        <i class="fa-solid fa-id-badge"></i>
    </div>

    <!-- Institution Header -->
    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e0e0e0; padding-bottom: 14px; margin-bottom: 18px;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.2rem; font-weight: 800;">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div>
                <div style="font-weight: 800; font-size: 0.9rem; color: var(--text-main);">{{ $student->institution->legal_name ?? 'AttendWise Institution' }}</div>
                <div style="font-size: 0.7rem; color: var(--text-main); text-transform: uppercase; font-weight: 700;">Student Identification</div>
            </div>
        </div>
        <div style="background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; font-size: 0.68rem; font-weight: 700; padding: 4px 8px; border-radius: 12px;">
            <i class="fa-solid fa-check-circle"></i> VALID
        </div>
    </div>

    <!-- ID Body -->
    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px; position: relative; z-index: 1;">
        <div style="width: 80px; height: 80px; border-radius: 20px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 2.5rem; font-weight: 800; box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3); border: 3px solid #ffffff;">
            {{ strtoupper(substr($student->name ?? 'S', 0, 1)) }}
        </div>
        <div>
            <h2 style="font-weight: 800; font-size: 1.25rem; color: var(--text-main); margin-bottom: 4px;">{{ $student->name }}</h2>
            <div style="font-size: 0.82rem; color: var(--text-muted); font-weight: 600; margin-bottom: 2px;">
                Roll No: <strong style="color: var(--text-main);">{{ $student->roll_number }}</strong>
            </div>
            <div style="font-size: 0.75rem; color: var(--text-muted);">
                Course: {{ $student->course->name ?? 'N/A' }}
            </div>
        </div>
    </div>

    <!-- Personal Info Grid -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; background: #f5f5f5; border: 1px solid #e0e0e0; border-radius: 14px; padding: 14px;">
        <div>
            <div style="font-size: 0.65rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-bottom: 2px;">Email</div>
            <div style="font-size: 0.82rem; font-weight: 600; color: var(--text-main); word-break: break-all;">{{ $student->email ?? 'N/A' }}</div>
        </div>
        <div>
            <div style="font-size: 0.65rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-bottom: 2px;">Phone</div>
            <div style="font-size: 0.82rem; font-weight: 600; color: var(--text-main);">{{ $student->mobile ?? 'N/A' }}</div>
        </div>
        <div>
            <div style="font-size: 0.65rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-bottom: 2px;">Department</div>
            <div style="font-size: 0.82rem; font-weight: 600; color: var(--text-main);">{{ $student->department->name ?? 'General' }}</div>
        </div>
        <div>
            <div style="font-size: 0.65rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-bottom: 2px;">Gender</div>
            <div style="font-size: 0.82rem; font-weight: 600; color: var(--text-main);">{{ ucfirst($student->gender ?? 'Unspecified') }}</div>
        </div>
    </div>
</div>

<!-- Facial Biometrics Status -->
<div class="glass-card" style="padding: 16px; margin-bottom: 24px;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
        <h4 style="font-weight: 800; font-size: 0.95rem; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-face-viewfinder" style="color: var(--text-main);"></i> Facial Biometrics
        </h4>
        @if($student->face_descriptor)
            <span style="font-size: 0.7rem; background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; padding: 2px 8px; border-radius: 10px; font-weight: 600;">Registered</span>
        @else
            <span style="font-size: 0.7rem; background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 2px 8px; border-radius: 10px; font-weight: 600;">Not Registered</span>
        @endif
    </div>

    @if($student->face_descriptor)
        <p style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 14px; line-height: 1.4;">
            Your face data is securely registered and can be used for live attendance verification.
        </p>
        <button disabled style="width: 100%; background: #f5f5f5; border: 1px solid #e0e0e0; color: #999999; border-radius: 12px; padding: 10px; font-weight: 700; font-size: 0.85rem; display: flex; align-items: center; justify-content: center; gap: 8px;">
            <i class="fa-solid fa-check"></i> Registration Complete
        </button>
    @else
        <p style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 14px; line-height: 1.4;">
            You need to register your face from multiple angles to enable live facial match for attendance.
        </p>
        <a href="{{ route('student.face_register') }}" style="width: 100%; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; text-decoration: none; border-radius: 12px; padding: 10px; font-weight: 700; font-size: 0.85rem; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);">
            <i class="fa-solid fa-camera"></i> Register Face Now
        </a>
    @endif
</div>

<!-- Single Device Session Security Box -->
<div class="glass-card" style="padding: 16px; margin-bottom: 24px;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
        <h4 style="font-weight: 800; font-size: 0.95rem; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-mobile-screen" style="color: var(--text-main);"></i> Active Device Session
        </h4>
        <span style="font-size: 0.7rem; background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; padding: 2px 8px; border-radius: 10px; font-weight: 600;">Locked</span>
    </div>

    @if($student->session)
    <p style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 14px; line-height: 1.4;">
        Your account is bound to this physical device to prevent proxy attendance.
    </p>
    
    <div style="background: #f5f5f5; border: 1px solid #e0e0e0; border-radius: 12px; padding: 12px; font-size: 0.78rem; display: flex; flex-direction: column; gap: 6px;">
        <div style="display: flex; justify-content: space-between;">
            <span style="color: var(--text-muted); font-weight: 600;">Device ID:</span>
            <span style="font-family: monospace; color: var(--text-main); font-weight: 600;">{{ Str::limit($student->session->device_id, 20) }}</span>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <span style="color: var(--text-muted); font-weight: 600;">Last Login:</span>
            <span style="color: var(--text-main); font-weight: 600;">{{ \Carbon\Carbon::parse($student->session->last_login_at)->diffForHumans() }}</span>
        </div>
    </div>
    @endif
</div>

<!-- Logout Form -->
<form method="POST" action="{{ route('student.logout') }}">
    @csrf
    <button type="submit" style="width: 100%; background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; border-radius: 16px; padding: 14px; font-weight: 700; font-size: 0.95rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s ease;">
        <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout Securely
    </button>
</form>
@endsection
