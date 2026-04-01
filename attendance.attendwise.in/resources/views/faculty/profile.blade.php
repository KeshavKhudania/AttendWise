@extends('layouts.faculty')

@section('header-title', 'My Profile')
@section('header-subtitle', 'Manage your account settings and professional details.')

@section('content')
<div class="card" style="padding: 0; max-width: 900px; margin: 0 auto;">
    <!-- Profile Background & Header -->
    <div style="height: 120px; background: var(--subtle-bg); border-bottom: 1px solid var(--border); border-radius: 0.75rem 0.75rem 0 0; position: relative;">
        <!-- Avatar Position -->
        <div style="position: absolute; bottom: -40px; left: 2.5rem; width: 100px; height: 100px; border-radius: 1.5rem; background: var(--bg); border: 4px solid var(--bg); display: flex; align-items: center; justify-content: center; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            <div style="width: 100%; height: 100%; background: var(--primary); color: var(--bg); display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: 700;">
                {{ substr($faculty->name ?? 'F', 0, 1) }}
            </div>
        </div>
    </div>

    <!-- Profile Details Header Area -->
    <div style="padding: 3rem 2.5rem 2rem 2.5rem; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 700; letter-spacing: -0.04em;">{{ $faculty->name }}</h2>
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.25rem;">
                <span style="color: var(--accent); font-weight: 600; font-size: 0.9rem;">{{ strtoupper(Str::replace("_"," ",$faculty->designation)) }}</span>
                <span style="color: var(--text-muted); font-size: 0.8rem;">•</span>
                <span style="color: var(--text-muted); font-size: 0.85rem;">{{ $faculty->department->name ?? 'Dept.' }}</span>
            </div>
        </div>
        <div>
            <button class="btn-primary" style="padding: 0.6rem 1.25rem;">
                <i data-lucide="edit-3" style="width: 14px;"></i>
                Edit Account
            </button>
        </div>
    </div>

    <!-- Profile Sections -->
    <div style="padding: 0 2.5rem 3rem 2.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; margin-top: 2rem;">
        
        <!-- Professional Details -->
        <div>
            <h4 style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 1.5rem; letter-spacing: 0.05em;">Information</h4>
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.25rem;">Full Name</label>
                    <div style="font-size: 0.95rem; font-weight: 500;">{{ $faculty->name }}</div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.25rem;">Work Email</label>
                    <div style="font-size: 0.95rem; font-weight: 500;">{{ $faculty->email }}</div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.25rem;">Phone Number</label>
                    <div style="font-size: 0.95rem; font-weight: 500;">{{ $faculty->mobile }}</div>
                </div>
            </div>
        </div>

        <!-- Institutional Info -->
        <div>
            <h4 style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 1.5rem; letter-spacing: 0.05em;">Institution</h4>
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.25rem;">Employee ID</label>
                    <div style="font-size: 0.95rem; font-weight: 500;">{{ $faculty->employee_code ?? 'EMP-000000' }}</div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.25rem;">Department</label>
                    <div style="font-size: 0.95rem; font-weight: 500;">{{ $faculty->department->name ?? 'Dept. Not Set' }}</div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.25rem;">Campus Location</label>
                    <div style="font-size: 0.95rem; font-weight: 500;">AttendWise Main Campus</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
