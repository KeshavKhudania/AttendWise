@extends('layouts.student')

@section('title', 'Live Attendance - AttendWise PWA')

@section('content')
<div class="glass-card attendance-card" style="text-align: center; padding: 40px 24px; background: var(--card-bg); border: none; border-radius: 0; position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: center; min-height: 100vh;">
    
    <!-- Background Accents -->
    <div style="position: absolute; top: -100px; left: -100px; width: 300px; height: 300px; background: rgba(59, 130, 246, 0.2); filter: blur(100px); z-index: 0; pointer-events: none; border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -100px; right: -100px; width: 300px; height: 300px; background: rgba(16, 185, 129, 0.15); filter: blur(100px); z-index: 0; pointer-events: none; border-radius: 50%;"></div>

    <div style="position: relative; z-index: 1; max-width: 400px; margin: 0 auto; width: 100%; display: flex; flex-direction: column; align-items: center;">
        
        <div style="display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.1) 100%); border: 1px solid rgba(16, 185, 129, 0.2); padding: 8px 16px; border-radius: 30px; font-size: 0.75rem; font-weight: 800; color: #10b981; margin-bottom: 24px; letter-spacing: 0.5px; text-transform: uppercase; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.1);">
            <i class="fa-solid fa-satellite-dish" style="animation: pulse 2s infinite;"></i> Secure Geo-Session
        </div>
        
        <h2 style="font-weight: 900; font-size: 2.2rem; color: var(--text-main); margin-bottom: 30px; letter-spacing: -0.5px; line-height: 1.1; font-family: 'Inter', sans-serif;">{{ $session->event->title ?? $session->club->name ?? 'Club Activity' }}</h2>
        
        @php
            $locationName = 'Multiple Locations';
            if ($session->event && $session->event->venue) {
                $locationName = $session->event->venue;
            } elseif ($session->venue) {
                $locationName = $session->venue;
            } elseif (!empty($session->geo_locations) && is_array($session->geo_locations) && count($session->geo_locations) === 1) {
                $locationName = $session->geo_locations[0]['name'] ?? 'Multiple Locations';
            }
        @endphp
        
        <div style="display: flex; justify-content: center; gap: 16px; margin-bottom: 40px; width: 100%;">
            <div style="flex: 1; display: flex; flex-direction: column; align-items: center; background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); padding: 20px 12px; border-radius: 24px; border: 1px solid rgba(255, 255, 255, 0.08); box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);">
                <div style="width: 48px; height: 48px; border-radius: 16px; background: linear-gradient(135deg, rgba(96, 165, 250, 0.15) 0%, rgba(59, 130, 246, 0.15) 100%); display: flex; align-items: center; justify-content: center; margin-bottom: 12px; box-shadow: inset 0 2px 4px rgba(255,255,255,0.05);">
                    <i class="fa-solid fa-map-pin" style="color: #60a5fa; font-size: 1.3rem;"></i>
                </div>
                <span style="font-size: 0.7rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">Venue</span>
                <strong style="color: var(--text-main); font-size: 1rem; text-align: center;">{{ $locationName }}</strong>
            </div>
            <div style="flex: 1; display: flex; flex-direction: column; align-items: center; background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); padding: 20px 12px; border-radius: 24px; border: 1px solid rgba(255, 255, 255, 0.08); box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);">
                <div style="width: 48px; height: 48px; border-radius: 16px; background: linear-gradient(135deg, rgba(96, 165, 250, 0.15) 0%, rgba(59, 130, 246, 0.15) 100%); display: flex; align-items: center; justify-content: center; margin-bottom: 12px; box-shadow: inset 0 2px 4px rgba(255,255,255,0.05);">
                    <i class="fa-regular fa-clock" style="color: #60a5fa; font-size: 1.3rem;"></i>
                </div>
                <span style="font-size: 0.7rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">Schedule</span>
                <strong style="color: var(--text-main); font-size: 1rem;">{{ \Carbon\Carbon::parse($session->event ? $session->event->start_time : $session->start_time)->format('h:i A') }}</strong>
            </div>
        </div>

        <!-- Radar Animation (Shown before camera starts) -->
        <div id="radarContainer" style="position: relative; width: 240px; height: 240px; margin: 0 auto 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%; border: 1px solid rgba(59, 130, 246, 0.2); background: radial-gradient(circle, rgba(59,130,246,0.05) 0%, transparent 70%); box-shadow: 0 0 50px rgba(59, 130, 246, 0.1);">
            <div class="radar-grid"></div>
            <div class="radar-sweep"></div>
            <div class="radar-circle circle-1"></div>
            <div class="radar-circle circle-2"></div>
            <div class="radar-circle circle-3"></div>
            <div style="position: relative; z-index: 10; width: 130px; height: 130px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 30px rgba(59, 130, 246, 0.5), inset 0 0 20px rgba(0,0,0,0.8); border: 4px solid #3b82f6; overflow: hidden; background: #000;">
                <i class="fa-solid fa-fingerprint" style="font-size: 3rem; color: #3b82f6; filter: drop-shadow(0 0 10px rgba(59,130,246,0.5));"></i>
            </div>
        </div>

        <!-- Camera Container (Initially Hidden) -->
        <div id="cameraContainer" style="display: none; position: relative; width: 100%; max-width: 320px; aspect-ratio: 3/4; margin: 0 auto 40px; border-radius: 24px; border: 2px solid rgba(59, 130, 246, 0.5); overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.2), 0 0 30px rgba(59, 130, 246, 0.2); background: #000;">
            <video id="faceVideo" autoplay muted playsinline style="width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1); filter: contrast(1.1) brightness(1.1);"></video>
            <canvas id="faceOverlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; transform: scaleX(-1); pointer-events: none;"></canvas>
            <div id="faceStatus" style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(5px); padding: 12px; text-align: center; font-size: 0.8rem; font-weight: 800; color: #fff; z-index: 15; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s;">Initializing</div>
        </div>

        @if($alreadyMarked)
            <div style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(5, 150, 105, 0.15) 100%); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 24px; padding: 20px; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; gap: 12px; box-shadow: 0 15px 35px rgba(16, 185, 129, 0.15); width: 100%; backdrop-filter: blur(10px);">
                <i class="fa-solid fa-circle-check" style="font-size: 1.8rem;"></i> Identity Verified
            </div>
            <a href="{{ route('student.dashboard') }}" target="_top" style="margin-top: 24px; display: block; text-decoration: none; padding: 18px; border-radius: 20px; font-weight: 700; background: var(--hover-bg); color: var(--text-main); border: 1px solid var(--card-border); width: 100%; transition: all 0.2s;">
                Return to Dashboard
            </a>
        @elseif($session->status != 'active')
            <div style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 24px; padding: 24px; font-weight: 800; font-size: 1.1rem; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 16px; width: 100%; box-shadow: 0 10px 30px rgba(239, 68, 68, 0.05); backdrop-filter: blur(10px);">
                <div style="width: 56px; height: 56px; border-radius: 50%; background: rgba(239,68,68,0.15); display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-clock-rotate-left" style="font-size: 1.8rem; color: #ef4444;"></i>
                </div>
                Session Ended
            </div>
            <a href="{{ route('student.dashboard') }}" target="_top" style="margin-top: 24px; display: block; text-decoration: none; padding: 18px; border-radius: 20px; font-weight: 700; background: var(--hover-bg); color: var(--text-main); border: 1px solid var(--card-border); width: 100%; transition: all 0.2s;">
                Return to Dashboard
            </a>
        @else
            <button id="startScanBtn" type="button" onclick="initiateCamera()" style="width: 100%; padding: 20px; font-size: 1.1rem; border-radius: 24px; display: flex; align-items: center; justify-content: center; gap: 12px; font-weight: 800; border: none; cursor: pointer; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; box-shadow: 0 15px 35px rgba(59, 130, 246, 0.3); text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);">
                <i class="fa-solid fa-camera" style="font-size: 1.5rem;"></i> Start Face Scanner
            </button>
            <button id="markBtn" type="button" onclick="markLiveAttendance()" style="width: 100%; padding: 20px; font-size: 1.1rem; border-radius: 24px; display: none; align-items: center; justify-content: center; gap: 12px; font-weight: 800; border: none; cursor: pointer; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; box-shadow: 0 15px 35px rgba(16, 185, 129, 0.3); text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);">
                <i class="fa-solid fa-face-viewfinder" style="font-size: 1.5rem;"></i> Authenticate & Mark
            </button>
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 24px; font-weight: 600; line-height: 1.5; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <i class="fa-solid fa-shield-halved" style="color: #60a5fa;"></i> Secure biometric & geo-location check required
            </p>
        @endif
    </div>
</div>

<style>
/* New Dark Theme Overrides for Iframe */
@if(request()->has('iframe'))
html, body {
    background: transparent !important;
    background-color: transparent !important;
}
.attendance-card {
    border-radius: 0 !important;
    min-height: 100vh;
    border: none !important;
    box-shadow: none !important;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
@endif

.radar-grid {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: 
        linear-gradient(90deg, transparent 49%, rgba(59, 130, 246, 0.15) 50%, transparent 51%),
        linear-gradient(0deg, transparent 49%, rgba(59, 130, 246, 0.15) 50%, transparent 51%);
    background-size: 100% 100%;
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.radar-grid::after {
    content: '';
    position: absolute;
    top: 25%; left: 25%; right: 25%; bottom: 25%;
    border-radius: 50%;
    border: 1px solid rgba(59, 130, 246, 0.15);
}

.radar-sweep {
    position: absolute;
    width: 50%;
    height: 50%;
    top: 0;
    left: 50%;
    transform-origin: 0% 100%;
    background: linear-gradient(90deg, rgba(59, 130, 246, 0.8) 0%, transparent 100%);
    animation: radar-spin 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    border-radius: 100% 0 0 0;
    z-index: 5;
    opacity: 0.7;
    filter: drop-shadow(0 0 10px rgba(59,130,246,0.5));
}

@keyframes radar-spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes radar-pulse {
    0% { transform: scale(0.4); opacity: 0.9; }
    100% { transform: scale(2.8); opacity: 0; }
}
.radar-circle {
    position: absolute;
    width: 80px;
    height: 80px;
    background: radial-gradient(circle, rgba(59, 130, 246, 0.6) 0%, transparent 70%);
    border-radius: 50%;
    animation: radar-pulse 3s infinite cubic-bezier(0.21, 0.85, 0.35, 1);
    z-index: 2;
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

<script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
    const registeredFaceDescriptors = {!! Auth::guard('student')->user()->face_descriptor ? Auth::guard('student')->user()->face_descriptor : 'null' !!};
    const MODEL_URL = 'https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js@master/weights';
    let faceModelsLoaded = false;
    let cameraStream = null;

    @if(!$alreadyMarked && $session->status == 'active')
    window.addEventListener('DOMContentLoaded', () => {
        if (typeof faceapi !== 'undefined') {
            Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
            ]).then(() => {
                faceModelsLoaded = true;
                console.log("Face models loaded for fast matching");
            }).catch(e => console.error("Error loading face models:", e));
        }
    });

    async function initiateCamera() {
        const startBtn = document.getElementById('startScanBtn');
        const markBtn = document.getElementById('markBtn');
        const radar = document.getElementById('radarContainer');
        const camContainer = document.getElementById('cameraContainer');
        const video = document.getElementById('faceVideo');
        const status = document.getElementById('faceStatus');
        
        if (startBtn) {
            startBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="font-size: 1.5rem;"></i> Initializing...';
            startBtn.disabled = true;
            startBtn.style.opacity = '0.7';
        }

        // Hide radar, show camera container
        if (radar) radar.style.display = 'none';
        if (camContainer) camContainer.style.display = 'block';
        
        status.innerText = "Starting Camera...";

        if (!faceModelsLoaded) {
            status.innerText = "Loading AI Models...";
            while (!faceModelsLoaded) {
                await new Promise(resolve => setTimeout(resolve, 500));
            }
        }
        
        try {
            cameraStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" } });
            video.srcObject = cameraStream;
            status.innerText = "Camera Active - Tap to Verify";
            
            if (startBtn) startBtn.style.display = 'none';
            if (markBtn) markBtn.style.display = 'flex';
        } catch(err) {
            status.innerText = "Camera Denied";
            status.style.background = "rgba(239, 68, 68, 0.8)";
            if (startBtn) {
                startBtn.innerHTML = '<i class="fa-solid fa-camera" style="font-size: 1.5rem;"></i> Try Camera Again';
                startBtn.disabled = false;
                startBtn.style.opacity = '1';
                startBtn.style.display = 'flex';
            }
        }
    }
    @endif

function markLiveAttendance() {
    const btn = document.getElementById('markBtn');
    if (!btn) return;
    
    if (!registeredFaceDescriptors) {
        if(typeof showToast === 'function') showToast("No registered face found. Please register your face in your profile.", "error");
        else alert("No registered face found. Please register your face in your profile.");
        return;
    }
    
    let isGeofencingRequired = {{ $session->is_geofencing ? 'true' : 'false' }};
    
    if (isGeofencingRequired && !navigator.geolocation) {
        if(typeof showToast === 'function') showToast("Geolocation is not supported by your browser.", "error");
        else alert("Geolocation is not supported by your browser.");
        return;
    }
    
    if (!cameraStream) {
        if(typeof showToast === 'function') showToast("Please allow camera access to verify your identity.", "error");
        else alert("Please allow camera access to verify your identity.");
        return;
    }

    // Set UI to loading state
    const originalText = btn.innerHTML;
    btn.style.opacity = '0.7';
    btn.disabled = true;

    async function processFaceMatch(lat = null, lng = null) {
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Analyzing Face...';
        
        const video = document.getElementById('faceVideo');
        const overlay = document.getElementById('faceOverlay');
        const status = document.getElementById('faceStatus');
        status.style.background = 'rgba(0,0,0,0.7)';
        status.innerText = "Scanning Face...";

        const parsedDescriptors = registeredFaceDescriptors.map(arr => new Float32Array(arr));
        const labeledDescriptor = new faceapi.LabeledFaceDescriptors('student', parsedDescriptors);
        const faceMatcher = new faceapi.FaceMatcher(labeledDescriptor, 0.45); 

        let verificationInterval;
        let scanAttempts = 0;

        const displaySize = { width: video.clientWidth, height: video.clientHeight };
        faceapi.matchDimensions(overlay, displaySize);

        verificationInterval = setInterval(async () => {
            const detections = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptor();
            
            const ctx = overlay.getContext('2d');
            ctx.clearRect(0, 0, overlay.width, overlay.height);

                if (detections) {
                    const resizedDetections = faceapi.resizeResults(detections, displaySize);
                    
                    const box = resizedDetections.detection.box;
                    ctx.strokeStyle = '#6366f1';
                    ctx.lineWidth = 2;
                    ctx.strokeRect(box.x, box.y, box.width, box.height);

                    const bestMatch = faceMatcher.findBestMatch(detections.descriptor);
                    
                    if (bestMatch.label === 'student') {
                        // Match successful!
                        clearInterval(verificationInterval);
                        
                        document.getElementById('faceStatus').innerHTML = '<i class="fa-solid fa-circle-check"></i> Face Matched!';
                        document.getElementById('faceStatus').style.background = '#10b981';
                        
                        submitAttendance(lat, lng);
                    } else {
                        document.getElementById('faceStatus').innerText = "Face not matched. Try again.";
                        document.getElementById('faceStatus').style.color = "#ef4444";
                    }
                } else {
                    document.getElementById('faceStatus').innerText = "No face detected.";
                    document.getElementById('faceStatus').style.color = "#ffffff";
                }
                
                scanAttempts++;
                if (scanAttempts > 60) { // 30 seconds max
                    clearInterval(verificationInterval);
                    document.getElementById('faceStatus').innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Verification timed out.';
                    document.getElementById('faceStatus').style.background = '#ef4444';
                    btn.style.display = 'flex';
                    btn.innerHTML = '<i class="fa-solid fa-location-arrow"></i> Try Again';
                    btn.style.opacity = '1';
                    btn.disabled = false;
                }
            }, 500);
    }
    
    if (isGeofencingRequired) {
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Acquiring Location...';
        navigator.geolocation.getCurrentPosition(async function(position) {
            processFaceMatch(position.coords.latitude, position.coords.longitude);
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
    } else {
        processFaceMatch(null, null);
    }
}

function submitAttendance(lat, lng) {
    fetch('{{ route("student.club.attendance.geo") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ 
            uuid: '{{ $session->uuid }}',
            latitude: lat,
            longitude: lng
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
            
            const btn = document.getElementById('markBtn');
            if (btn) {
                btn.style.display = 'flex';
                btn.innerHTML = '<i class="fa-solid fa-location-arrow"></i> Try Again';
                btn.style.opacity = '1';
                btn.disabled = false;
            }
        }
    }).catch(err => {
        console.error(err);
        if(typeof showToast === 'function') showToast('Error marking attendance. Check connection.', 'error');
        else alert('Error marking attendance. Check connection.');
        
        const btn = document.getElementById('markBtn');
        if (btn) {
            btn.style.display = 'flex';
            btn.innerHTML = '<i class="fa-solid fa-location-arrow"></i> Try Again';
            btn.style.opacity = '1';
            btn.disabled = false;
        }
    });
}
</script>
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.Echo) {
            window.Echo.join('attendance.session.{{ $session->uuid }}')
                .listen('.LiveAttendanceAction', (e) => {
                    if (e.action === 'geo_updated') {
                        isGeofencingRequired = e.payload.is_geofencing;
                        
                        if (isGeofencingRequired) {
                            if(typeof showToast === 'function') showToast("Manager enabled Geo-Fencing. GPS will be required.", "info");
                        } else {
                            if(typeof showToast === 'function') showToast("Manager disabled Geo-Fencing. GPS is no longer required.", "info");
                        }
                    } else if (e.action === 'session_ended') {
                        if(typeof showToast === 'function') showToast("Session closed by manager.", "info");
                        setTimeout(() => {
                            window.location.href = "{{ route('student.dashboard') }}";
                        }, 2000);
                    }
                });
        }
    });
</script>
@endsection
