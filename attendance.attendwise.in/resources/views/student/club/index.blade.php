@extends('layouts.student')

@section('title', 'Club Management - AttendWise PWA')

@section('content')
<div class="glass-card" style="padding: 16px; margin-bottom: 16px; border-left: 4px solid #4f46e5;">
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h4 style="font-weight: 800; font-size: 1.2rem; color: var(--text-main); margin-bottom: 4px;">Club Management</h4>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0;">Select a club to manage its attendance session.</p>
        </div>
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(79, 70, 229, 0.1); display: flex; align-items: center; justify-content: center; color: #4f46e5; font-size: 1.4rem;">
            <i class="fa-solid fa-users-gear"></i>
        </div>
    </div>
</div>

<div class="clubs-list">
    @foreach($student->clubMemberships as $membership)
        <div class="glass-card" style="margin-bottom: 12px; padding: 18px; border-radius: 16px;">
            <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 12px;">
                <div>
                    <h5 style="font-weight: 800; font-size: 1.1rem; color: var(--text-main); margin-bottom: 4px;">
                        {{ $membership->club->name ?? 'Unknown Club' }}
                    </h5>
                    <p style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 12px;">
                        <i class="fa-solid fa-user-tag"></i> {{ ucfirst($membership->designation ?? 'Member') }}
                    </p>
                </div>
            </div>
            
            <a href="{{ route('student.club.session', ['club_id' => $membership->club_id]) }}" class="btn-primary" style="text-decoration: none; display: block; text-align: center; padding: 12px; font-weight: 700; border-radius: 12px;">
                <i class="fa-solid fa-qrcode"></i> Start Session
            </a>
        </div>
    @endforeach
</div>
@endsection
