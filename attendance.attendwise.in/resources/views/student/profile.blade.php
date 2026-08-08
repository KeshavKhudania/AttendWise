@extends('layouts.student')

@section('title', 'Student Profile - AttendWise PWA')

@section('content')
<div style="margin-bottom: 16px;">
    <h3 style="font-weight: 800; font-size: 1.2rem; color: #fff; margin-bottom: 4px;">Digital ID Card</h3>
    <p style="font-size: 0.78rem; color: var(--text-muted);">Verified institutional student identity</p>
</div>

<!-- Digital Student ID Card -->
<div class="glass-card" style="background: linear-gradient(135deg, rgba(30, 41, 59, 0.9) 0%, rgba(15, 23, 42, 0.95) 100%); border: 1px solid rgba(99, 102, 241, 0.35); position: relative; overflow: hidden; padding: 24px; box-shadow: 0 15px 35px rgba(0,0,0,0.5);">
    
    <!-- Watermark Accent -->
    <div style="position: absolute; right: -30px; top: -30px; font-size: 10rem; color: rgba(99, 102, 241, 0.05); pointer-events: none;">
        <i class="fa-solid fa-id-card"></i>
    </div>

    <!-- ID Header -->
    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--card-border); padding-bottom: 14px; margin-bottom: 18px;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <div style="width: 34px; height: 34px; border-radius: 10px; background: var(--accent-gradient); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.1rem;">
                <i class="fa-solid fa-university"></i>
            </div>
            <div>
                <div style="font-weight: 800; font-size: 0.9rem; color: #fff;">{{ $student->institution->legal_name ?? 'AttendWise Institution' }}</div>
                <div style="font-size: 0.7rem; color: #818cf8; text-transform: uppercase; font-weight: 700;">Student Identification</div>
            </div>
        </div>
        <div style="background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); font-size: 0.68rem; font-weight: 700; padding: 4px 8px; border-radius: 12px;">
            VERIFIED
        </div>
    </div>

    <!-- ID Body -->
    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
        <div style="width: 72px; height: 72px; border-radius: 20px; background: var(--accent-gradient); display: flex; align-items: center; justify-content: center; font-size: 2.2rem; font-weight: 800; color: #fff; box-shadow: 0 6px 18px var(--accent-glow); flex-shrink: 0;">
            {{ strtoupper(substr($student->name ?? 'S', 0, 1)) }}
        </div>
        <div>
            <h2 style="font-weight: 800; font-size: 1.25rem; color: #fff; margin-bottom: 4px;">{{ $student->name }}</h2>
            <div style="font-size: 0.82rem; color: #cbd5e1; font-weight: 600; margin-bottom: 2px;">
                Roll No: <strong style="color: #818cf8;">{{ $student->roll_number }}</strong>
            </div>
            <div style="font-size: 0.75rem; color: var(--text-muted);">
                Academic Year: {{ $student->academic_year ?? '2024-2025' }}
            </div>
        </div>
    </div>

    <!-- Details Grid -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--card-border); border-radius: 14px; padding: 14px;">
        <div>
            <div style="font-size: 0.68rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Email</div>
            <div style="font-size: 0.82rem; font-weight: 600; color: #fff; word-break: break-all;">{{ $student->email ?? 'N/A' }}</div>
        </div>
        <div>
            <div style="font-size: 0.68rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Mobile</div>
            <div style="font-size: 0.82rem; font-weight: 600; color: #fff;">{{ $student->mobile ?? 'N/A' }}</div>
        </div>
        <div>
            <div style="font-size: 0.68rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Department</div>
            <div style="font-size: 0.82rem; font-weight: 600; color: #fff;">{{ $student->department->name ?? 'General' }}</div>
        </div>
        <div>
            <div style="font-size: 0.68rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Gender</div>
            <div style="font-size: 0.82rem; font-weight: 600; color: #fff;">{{ ucfirst($student->gender ?? 'Unspecified') }}</div>
        </div>
    </div>
</div>

<!-- Single Device Session Security Box -->
<div class="glass-card" style="padding: 18px; margin-bottom: 20px;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
        <h4 style="font-weight: 800; font-size: 0.95rem; color: #fff; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-mobile-screen" style="color: #818cf8;"></i> Active Device Session
        </h4>
        <span style="font-size: 0.7rem; background: rgba(99, 102, 241, 0.15); color: #a5b4fc; border: 1px solid rgba(99, 102, 241, 0.3); padding: 2px 8px; border-radius: 10px; font-weight: 600;">Locked</span>
    </div>

    <div style="font-size: 0.78rem; color: var(--text-muted); margin-bottom: 14px; line-height: 1.4;">
        Single-device policy is enforced for your account. Signing in on another mobile phone will automatically end this device's session.
    </div>

    @if($student->session)
    <div style="background: rgba(15, 23, 42, 0.5); border: 1px solid var(--card-border); border-radius: 12px; padding: 12px; font-size: 0.78rem; display: flex; flex-direction: column; gap: 6px;">
        <div style="display: flex; justify-content: space-between;">
            <span style="color: var(--text-muted);">Device Identifier:</span>
            <span style="font-family: monospace; color: #a5b4fc;">{{ Str::limit($student->session->device_id, 20) }}</span>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <span style="color: var(--text-muted);">Last Active:</span>
            <span style="color: #cbd5e1;">{{ \Carbon\Carbon::parse($student->session->last_login_at)->diffForHumans() }}</span>
        </div>
    </div>
    @endif
</div>

<!-- Logout Form -->
<form method="POST" action="{{ route('student.logout') }}">
    @csrf
    <button type="submit" style="width: 100%; background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5; border-radius: 16px; padding: 14px; font-weight: 700; font-size: 0.95rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s ease;">
        <i class="fa-solid fa-right-from-bracket"></i>
        <span>Sign Out of PWA</span>
    </button>
</form>
@endsection
