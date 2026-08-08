@extends('layouts.student')

@section('title', 'Student Login - AttendWise PWA')

@section('content')
<div style="min-height: 80vh; display: flex; flex-direction: column; justify-content: center; padding: 12px 0;">

    <!-- Faculty Portal Link Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; padding: 0 4px;">
        <span style="font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Student Attendance PWA</span>
        <a href="{{ route('faculty.login') }}" style="display: inline-flex; align-items: center; gap: 8px; background: rgba(26, 34, 52, 0.85); border: 1px solid var(--card-border); color: #818cf8; text-decoration: none; padding: 8px 14px; border-radius: 14px; font-size: 0.82rem; font-weight: 700; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
            <i class="fa-solid fa-chalkboard-user"></i>
            <span>Faculty Login</span>
            <i class="fa-solid fa-arrow-right" style="font-size: 0.72rem;"></i>
        </a>
    </div>

    <div class="glass-card" style="border: 1px solid rgba(99, 102, 241, 0.3); text-align: center; position: relative; overflow: hidden;">
        <!-- Header Glow -->
        <div style="position: absolute; top: -50px; left: 50%; transform: translateX(-50%); width: 140px; height: 140px; background: rgba(99, 102, 241, 0.3); filter: blur(40px); border-radius: 50%;"></div>

        <div style="width: 64px; height: 64px; border-radius: 18px; background: var(--accent-gradient); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; font-size: 2rem; color: #fff; box-shadow: 0 10px 25px var(--accent-glow);">
            <i class="fa-solid fa-graduation-cap"></i>
        </div>

        <h2 style="font-weight: 800; font-size: 1.5rem; margin-bottom: 6px; letter-spacing: -0.5px;">Student Portal</h2>
        <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 24px;">Sign in to mark your attendance via dynamic QR code.</p>

        @if ($errors->any())
        <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); border-radius: 14px; padding: 12px 14px; color: #fca5a5; font-size: 0.85rem; text-align: left; margin-bottom: 20px;">
            <i class="fa-solid fa-triangle-exclamation" style="margin-right: 6px;"></i>
            {{ $errors->first() }}
        </div>
        @endif

        <form id="loginForm" method="POST" action="{{ route('student.login.post') }}">
            @csrf
            <input type="hidden" name="device_id" id="loginDeviceId">

            <!-- Roll Number Input -->
            <div style="margin-bottom: 16px; text-align: left;">
                <label style="display: block; font-size: 0.78rem; font-weight: 700; color: var(--text-muted); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Roll Number</label>
                <div style="position: relative;">
                    <i class="fa-solid fa-id-badge" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                    <input type="text" name="roll_number" id="rollNumberInput" value="{{ old('roll_number') }}" required placeholder="e.g. 101 or RW000001" style="width: 100%; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--card-border); border-radius: 14px; padding: 12px 14px 12px 42px; color: #fff; font-size: 0.92rem; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#818cf8'" onblur="this.style.borderColor='var(--card-border)'">
                </div>
            </div>

            <!-- Email or Mobile Input -->
            <div style="margin-bottom: 16px; text-align: left;">
                <label style="display: block; font-size: 0.78rem; font-weight: 700; color: var(--text-muted); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Email or Mobile</label>
                <div style="position: relative;">
                    <i class="fa-solid fa-envelope" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                    <input type="text" name="login" id="loginInput" value="{{ old('login') }}" required placeholder="demo@attendwise.in or email/mobile" style="width: 100%; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--card-border); border-radius: 14px; padding: 12px 14px 12px 42px; color: #fff; font-size: 0.92rem; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#818cf8'" onblur="this.style.borderColor='var(--card-border)'">
                </div>
            </div>

            <!-- Password Input -->
            <div style="margin-bottom: 22px; text-align: left;">
                <label style="display: block; font-size: 0.78rem; font-weight: 700; color: var(--text-muted); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Password</label>
                <div style="position: relative;">
                    <i class="fa-solid fa-lock" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                    <input type="password" name="password" id="passwordInput" required placeholder="••••••••" style="width: 100%; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--card-border); border-radius: 14px; padding: 12px 14px 12px 42px; color: #fff; font-size: 0.92rem; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#818cf8'" onblur="this.style.borderColor='var(--card-border)'">
                </div>
            </div>

            <button type="submit" class="btn-primary" style="margin-bottom: 14px;">
                <span>Sign In to PWA</span>
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>

        <!-- One-Click Demo Login Button -->
        <button type="button" onclick="fillDemoCredentials()" style="width: 100%; background: rgba(99, 102, 241, 0.15); border: 1px dashed rgba(99, 102, 241, 0.4); color: #a5b4fc; border-radius: 14px; padding: 12px; font-weight: 700; font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
            <span>One-Click Demo Student Login</span>
        </button>

        <!-- Single Device Security Notice -->
        <div style="margin-top: 20px; font-size: 0.75rem; color: var(--text-muted); display: flex; align-items: center; justify-content: center; gap: 6px;">
            <i class="fa-solid fa-shield-halved" style="color: #818cf8;"></i>
            <span>Single-device session protection active</span>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('loginDeviceId').value = getOrGenerateDeviceId();
    });

    function fillDemoCredentials() {
        document.getElementById('rollNumberInput').value = '101';
        document.getElementById('loginInput').value = 'demo@attendwise.in';
        document.getElementById('passwordInput').value = 'password';
        showToast('Demo student credentials filled!', 'success');
    }
</script>
@endsection
