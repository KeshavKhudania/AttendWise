@extends('layouts.student')

@section('title', 'Live Attendance - AttendWise PWA')

@section('content')
<div class="glass-card" style="text-align: center; padding: 30px 20px; background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);">
    <div style="display: inline-flex; align-items: center; gap: 8px; background: #ecfdf5; border: 1px solid #a7f3d0; padding: 6px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; color: #047857; margin-bottom: 20px;">
        <i class="fa-solid fa-satellite-dish" style="animation: pulse 2s infinite;"></i> Live Geo-Session
    </div>
    
    <h2 style="font-weight: 800; font-size: 1.5rem; color: var(--text-main); margin-bottom: 8px;">{{ $session->club->name ?? 'Club Activity' }}</h2>
    <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 30px;">
        <i class="fa-solid fa-map-pin"></i> Venue: <strong style="color: var(--text-main);">{{ $session->venue ?? 'Unknown Venue' }}</strong><br>
        <i class="fa-regular fa-clock"></i> Scheduled: {{ \Carbon\Carbon::parse($session->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('h:i A') }}
    </p>

    <!-- Radar Animation -->
    <div style="position: relative; width: 200px; height: 200px; margin: 0 auto 40px; display: flex; align-items: center; justify-content: center;">
        <div class="radar-circle circle-1"></div>
        <div class="radar-circle circle-2"></div>
        <div class="radar-circle circle-3"></div>
        <div style="position: relative; z-index: 10; width: 70px; height: 70px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);">
            <i class="fa-solid fa-location-dot" style="font-size: 1.8rem; color: white;"></i>
        </div>
    </div>

    @if($alreadyMarked)
        <div style="background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; border-radius: 16px; padding: 18px; font-weight: 700; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; gap: 10px;">
            <i class="fa-solid fa-circle-check" style="font-size: 1.5rem;"></i> Attendance Marked
        </div>
        <a href="{{ route('student.dashboard') }}" class="btn-secondary" style="margin-top: 16px; display: block; text-decoration: none; padding: 14px; border-radius: 12px; font-weight: 700;">
            Back to Dashboard
        </a>
    @elseif($session->status != 'active')
        <div style="background: #fef2f2; color: #dc2626; border: 1px solid #fca5a5; border-radius: 16px; padding: 18px; font-weight: 700; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; gap: 10px;">
            <i class="fa-solid fa-circle-xmark" style="font-size: 1.5rem;"></i> Session is Closed
        </div>
        <a href="{{ route('student.dashboard') }}" class="btn-secondary" style="margin-top: 16px; display: block; text-decoration: none; padding: 14px; border-radius: 12px; font-weight: 700;">
            Back to Dashboard
        </a>
    @else
        <button id="markBtn" type="button" onclick="markLiveAttendance()" class="btn-primary" style="width: 100%; padding: 16px; font-size: 1.1rem; border-radius: 16px; display: flex; align-items: center; justify-content: center; gap: 10px; font-weight: 800; border: none; cursor: pointer;">
            <i class="fa-solid fa-location-arrow"></i> Mark Present Now
        </button>
        <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 16px; font-weight: 600;">
            <i class="fa-solid fa-circle-info"></i> Make sure you are at the venue. Your location will be verified.
        </p>
    @endif
</div>

<style>
@keyframes radar-pulse {
    0% { transform: scale(0.5); opacity: 0.8; }
    100% { transform: scale(2.5); opacity: 0; }
}
.radar-circle {
    position: absolute;
    width: 70px;
    height: 70px;
    background: rgba(59, 130, 246, 0.4);
    border-radius: 50%;
    animation: radar-pulse 2.5s infinite cubic-bezier(0.25, 1, 0.5, 1);
}
.circle-2 { animation-delay: 0.8s; }
.circle-3 { animation-delay: 1.6s; }

@keyframes spin {
    to { transform: rotate(360deg); }
}
.fa-spinner { animation: spin 1s linear infinite; }

@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.1); }
}
</style>

<script>
function markLiveAttendance() {
    const btn = document.getElementById('markBtn');
    if (!btn) return;
    
    if (!navigator.geolocation) {
        if(typeof showToast === 'function') showToast("Geolocation is not supported by your browser.", "error");
        else alert("Geolocation is not supported by your browser.");
        return;
    }
    
    // Set UI to loading state
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner"></i> Acquiring Location...';
    btn.style.opacity = '0.7';
    btn.disabled = true;
    
    navigator.geolocation.getCurrentPosition(function(position) {
        btn.innerHTML = '<i class="fa-solid fa-spinner"></i> Verifying...';
        
        fetch('{{ route("club.attendance.geo") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ 
                uuid: '{{ $session->uuid }}',
                latitude: position.coords.latitude,
                longitude: position.coords.longitude
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if(typeof showToast === 'function') showToast(data.message || 'Attendance marked successfully!', 'success');
                else alert(data.message || 'Attendance marked successfully!');
                
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                if(typeof showToast === 'function') showToast(data.message || 'Error marking attendance.', 'error');
                else alert(data.message || 'Error marking attendance.');
                
                btn.innerHTML = originalText;
                btn.style.opacity = '1';
                btn.disabled = false;
            }
        }).catch(err => {
            console.error(err);
            if(typeof showToast === 'function') showToast('Error marking attendance. Check connection.', 'error');
            else alert('Error marking attendance. Check connection.');
            
            btn.innerHTML = originalText;
            btn.style.opacity = '1';
            btn.disabled = false;
        });
    }, function(error) {
        let msg = "Error getting location.";
        if (error.code === 1) msg = "Location permission denied. Please allow it in settings.";
        else if (error.code === 2) msg = "Location unavailable. Ensure GPS is on.";
        else if (error.code === 3) msg = "Location request timed out.";
        
        if(typeof showToast === 'function') showToast(msg, "error");
        else alert(msg);
        
        btn.innerHTML = originalText;
        btn.style.opacity = '1';
        btn.disabled = false;
    }, {
        enableHighAccuracy: true,
        timeout: 10000,
        maximumAge: 0
    });
}
</script>
@endsection
