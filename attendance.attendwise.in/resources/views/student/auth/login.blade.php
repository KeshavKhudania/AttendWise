@extends('layouts.student')

@section('title', 'Student Login - AttendWise PWA')

@section('styles')
<style>
    /* Override background for Light Theme on Login Page */
    body {
        background-color: #f8fafc !important;
        color: #0f172a !important;
        background-image: 
            radial-gradient(circle at 10% 15%, rgba(99, 102, 241, 0.1) 0%, transparent 45%),
            radial-gradient(circle at 90% 85%, rgba(14, 165, 233, 0.1) 0%, transparent 45%) !important;
    }

    .app-topbar {
        background: rgba(255, 255, 255, 0.85) !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }

    .brand-title {
        background: linear-gradient(135deg, #0f172a 0%, #334155 100%) !important;
        -webkit-background-clip: text !important;
        -webkit-text-fill-color: transparent !important;
    }

    /* Light Theme Glass Card */
    .light-login-card {
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 28px;
        padding: 32px 26px;
        box-shadow: 0 20px 40px -15px rgba(99, 102, 241, 0.12), 0 1px 3px rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    }

    /* Floating Header Glow */
    .light-glow-header {
        position: absolute;
        top: -60px;
        left: 50%;
        transform: translateX(-50%);
        width: 180px;
        height: 180px;
        background: rgba(99, 102, 241, 0.15);
        filter: blur(50px);
        border-radius: 50%;
        pointer-events: none;
    }

    /* Light Form Inputs */
    .form-label-light {
        display: block;
        font-size: 0.76rem;
        font-weight: 700;
        color: #475569;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }

    .input-wrapper-light {
        position: relative;
    }

    .input-icon-light {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.95rem;
        transition: color 0.2s ease;
    }

    .input-field-light {
        width: 100%;
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 14px;
        padding: 13px 14px 13px 42px;
        color: #0f172a;
        font-size: 0.92rem;
        font-weight: 500;
        outline: none;
        transition: all 0.2s ease;
    }

    .input-field-light::placeholder {
        color: #94a3b8;
    }

    .input-field-light:focus {
        background: #ffffff;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
    }

    .input-field-light:focus + .input-icon-light,
    .input-wrapper-light:focus-within .input-icon-light {
        color: #4f46e5;
    }

    /* Light Buttons */
    .btn-light-submit {
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        color: #ffffff;
        border: none;
        border-radius: 14px;
        padding: 14px 20px;
        font-weight: 700;
        font-size: 0.95rem;
        width: 100%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 8px 20px rgba(79, 70, 229, 0.25);
        transition: all 0.2s ease;
    }

    .btn-light-submit:hover {
        box-shadow: 0 10px 25px rgba(79, 70, 229, 0.35);
        transform: translateY(-1px);
    }

    .btn-light-submit:active {
        transform: scale(0.98);
    }

    .btn-demo-light {
        width: 100%;
        background: rgba(99, 102, 241, 0.06);
        border: 1px dashed rgba(99, 102, 241, 0.4);
        color: #4f46e5;
        border-radius: 14px;
        padding: 12px;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .btn-demo-light:hover {
        background: rgba(99, 102, 241, 0.12);
        border-color: #4f46e5;
    }

    /* Faculty Link Pill Button */
    .faculty-link-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        color: #4f46e5;
        text-decoration: none;
        padding: 8px 14px;
        border-radius: 16px;
        font-size: 0.82rem;
        font-weight: 700;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .faculty-link-pill:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transform: translateY(-1px);
    }
</style>
@endsection

@section('content')
<div style="min-height: 82vh; display: flex; flex-direction: column; justify-content: center; padding: 10px 0;">

    <!-- Top Navigation Header with Faculty Link -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding: 0 4px;">
        <div style="display: flex; align-items: center; gap: 6px;">
            <span style="width: 8px; height: 8px; border-radius: 50%; background: #10b981; display: inline-block;"></span>
            <span style="font-size: 0.78rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Student PWA</span>
        </div>

        <a href="{{ route('faculty.login') }}" class="faculty-link-pill">
            <i class="fa-solid fa-chalkboard-user"></i>
            <span>Faculty Login</span>
            <i class="fa-solid fa-chevron-right" style="font-size: 0.7rem; color: #94a3b8;"></i>
        </a>
    </div>

    <!-- Light Theme Login Form Card -->
    <div class="light-login-card">
        <div class="light-glow-header"></div>

        <!-- Brand Icon / App Logo Badge -->
        <div style="width: 72px; height: 72px; border-radius: 22px; background: #ffffff; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; padding: 10px; box-shadow: 0 10px 25px rgba(79, 70, 229, 0.15);">
            <img src="{{ asset('assets/images/logo.png') }}" alt="AttendWise Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
        </div>

        <div style="text-align: center; margin-bottom: 24px;">
            <h2 style="font-weight: 800; font-size: 1.55rem; color: #0f172a; margin-bottom: 6px; letter-spacing: -0.5px;">Student Portal</h2>
            <p style="color: #64748b; font-size: 0.88rem; font-weight: 500;">Sign in to mark attendance via dynamic QR code</p>
        </div>

        <!-- Error Alert -->
        @if ($errors->any())
        <div style="background: #fef2f2; border: 1px solid #fca5a5; border-radius: 16px; padding: 12px 14px; color: #991b1b; font-size: 0.85rem; text-align: left; margin-bottom: 20px; display: flex; align-items: flex-start; gap: 10px;">
            <i class="fa-solid fa-circle-exclamation" style="font-size: 1.1rem; color: #ef4444; margin-top: 2px;"></i>
            <div style="font-weight: 600;">{{ $errors->first() }}</div>
        </div>
        @endif

        <form id="loginForm" method="POST" action="{{ route('student.login.post') }}">
            @csrf
            <input type="hidden" name="device_id" id="loginDeviceId">

            <!-- Roll Number Input -->
            <div style="margin-bottom: 18px; text-align: left;">
                <label class="form-label-light">Roll Number</label>
                <div class="input-wrapper-light">
                    <input type="text" name="roll_number" id="rollNumberInput" value="{{ old('roll_number') }}" required placeholder="e.g. 101 or RW000001" class="input-field-light">
                    <i class="fa-solid fa-id-badge input-icon-light"></i>
                </div>
            </div>

            <!-- Email or Mobile Input -->
            <div style="margin-bottom: 18px; text-align: left;">
                <label class="form-label-light">Email or Mobile</label>
                <div class="input-wrapper-light">
                    <input type="text" name="login" id="loginInput" value="{{ old('login') }}" required placeholder="demo@attendwise.in or email/mobile" class="input-field-light">
                    <i class="fa-solid fa-envelope input-icon-light"></i>
                </div>
            </div>

            <!-- Password Input -->
            <div style="margin-bottom: 24px; text-align: left;">
                <label class="form-label-light">Password</label>
                <div class="input-wrapper-light">
                    <input type="password" name="password" id="passwordInput" required placeholder="••••••••" class="input-field-light">
                    <i class="fa-solid fa-lock input-icon-light"></i>
                </div>
            </div>

            <button type="submit" class="btn-light-submit" style="margin-bottom: 16px;">
                <span>Sign In to PWA</span>
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>

        <!-- One-Click Demo Login Button -->
        <button type="button" onclick="fillDemoCredentials()" class="btn-demo-light">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
            <span>One-Click Demo Student Sign In</span>
        </button>

        <!-- Single Device Security Footer Badge -->
        <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #f1f5f9; font-size: 0.76rem; color: #64748b; display: flex; align-items: center; justify-content: center; gap: 6px;">
            <i class="fa-solid fa-shield-halved" style="color: #4f46e5;"></i>
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
