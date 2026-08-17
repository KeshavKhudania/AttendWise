@extends('layouts.student')

@section('title', 'Student Login - AttendWise PWA')

@section('styles')
<style>
    /* Premium Black & White Theme */
    body {
        background-color: #f8fafc !important;
        color: #0f172a !important;
        background-image: 
            radial-gradient(circle at 10% 15%, rgba(99, 102, 241, 0.05) 0%, transparent 45%),
            radial-gradient(circle at 90% 85%, rgba(59, 130, 246, 0.05) 0%, transparent 45%) !important;
    }

    .app-topbar {
        background: rgba(255, 255, 255, 0.9) !important;
        border-bottom: 1px solid #eaeaea !important;
    }

    .brand-title {
        color: #000000 !important;
        background: none !important;
        -webkit-text-fill-color: initial !important;
    }

    /* Grayscale Glass Card */
    .light-login-card {
        background: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 28px;
        padding: 32px 26px;
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.05);
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
        background: radial-gradient(circle, rgba(99, 102, 241, 0.25) 0%, rgba(99, 102, 241, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    /* Grayscale Form Inputs */
    .form-label-light {
        display: block;
        font-size: 0.76rem;
        font-weight: 700;
        color: #555555;
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
        color: #888888;
        font-size: 0.95rem;
        transition: color 0.2s ease;
    }

    .input-field-light {
        width: 100%;
        background: #f9f9f9;
        border: 1px solid #d4d4d4;
        border-radius: 14px;
        padding: 13px 14px 13px 42px;
        color: #000000;
        font-size: 0.92rem;
        font-weight: 500;
        outline: none;
        transition: all 0.2s ease;
    }

    .input-field-light::placeholder {
        color: #999999;
    }

    .input-field-light:focus {
        background: #ffffff;
        border-color: #000000;
        box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.08);
    }

    .input-field-light:focus + .input-icon-light,
    .input-wrapper-light:focus-within .input-icon-light {
        color: #000000;
    }

    /* Colorful Buttons */
    .btn-light-submit {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
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
        box-shadow: 0 8px 20px rgba(79, 70, 229, 0.3);
        transition: all 0.2s ease;
    }

    .btn-light-submit:hover {
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        box-shadow: 0 10px 25px rgba(79, 70, 229, 0.4);
        transform: translateY(-1px);
    }

    .btn-light-submit:active {
        transform: scale(0.98);
    }

    .btn-demo-light {
        width: 100%;
        background: #f5f5f5;
        border: 1px dashed #cccccc;
        color: #333333;
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
        background: #eeeeee;
        border-color: #999999;
        color: #000000;
    }

    /* Faculty Link Pill Button */
    .faculty-link-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        border: 1px solid #dddddd;
        color: #000000;
        text-decoration: none;
        padding: 8px 14px;
        border-radius: 16px;
        font-size: 0.82rem;
        font-weight: 700;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .faculty-link-pill:hover {
        background: #f7f7f7;
        border-color: #bbbbbb;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-1px);
    }
</style>
@endsection

@section('content')
<div style="min-height: 82vh; display: flex; flex-direction: column; justify-content: center; padding: 10px 0;">

    <!-- Top Navigation Header with Faculty Link -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding: 0 4px;">
        <div style="display: flex; align-items: center; gap: 6px;">
            <span style="width: 8px; height: 8px; border-radius: 50%; background: #6366f1; display: inline-block;"></span>
            <span style="font-size: 0.78rem; font-weight: 700; color: #555555; text-transform: uppercase; letter-spacing: 0.5px;">Student PWA</span>
        </div>

        <a href="{{ route('faculty.login') }}" class="faculty-link-pill">
            <i class="fa-solid fa-chalkboard-user"></i>
            <span>Faculty Login</span>
            <i class="fa-solid fa-chevron-right" style="font-size: 0.7rem; color: #888888;"></i>
        </a>
    </div>

    <!-- Light Theme Login Form Card -->
    <div class="light-login-card">
        <div class="light-glow-header"></div>

        <!-- Brand Icon / App Logo Badge -->
        <div style="text-align: center; margin-bottom: 24px;">
            <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; color: #ffffff; box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3); margin: 0 auto 16px auto; transform: rotate(-5deg);">
                <i class="fa-solid fa-graduation-cap" style="transform: rotate(5deg);"></i>
            </div>
            <h2 style="font-weight: 800; font-size: 1.4rem; color: #0f172a; margin-bottom: 4px; letter-spacing: -0.5px;">Student Login</h2>
            <p style="color: #666666; font-size: 0.88rem; font-weight: 500;">Sign in to mark attendance via dynamic QR code</p>
        </div>

        <!-- Error Alert -->
        @if ($errors->any())
        <div style="background: #f5f5f5; border: 1px solid #cccccc; border-radius: 16px; padding: 12px 14px; color: #000000; font-size: 0.85rem; text-align: left; margin-bottom: 20px; display: flex; align-items: flex-start; gap: 10px;">
            <i class="fa-solid fa-circle-exclamation" style="font-size: 1.1rem; color: #333333; margin-top: 2px;"></i>
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
        <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #eeeeee; font-size: 0.76rem; color: #666666; display: flex; align-items: center; justify-content: center; gap: 6px;">
            <i class="fa-solid fa-shield-halved" style="color: #000000;"></i>
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
