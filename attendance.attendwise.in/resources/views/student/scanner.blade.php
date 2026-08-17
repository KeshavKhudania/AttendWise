@extends('layouts.student')

@section('title', 'QR Scanner - AttendWise PWA')

@section('styles')
<style>
    /* Full-height scanner overlay container */
    .scanner-wrapper {
        position: relative;
        width: 100%;
        border-radius: 24px;
        overflow: hidden;
        background: #000;
        border: 2px solid var(--border);
        box-shadow: 0 12px 40px rgba(0,0,0,0.15);
    }

    #qrReader {
        width: 100% !important;
        border: none !important;
    }

    #qrReader video {
        width: 100% !important;
        object-fit: cover !important;
        border-radius: 20px;
    }

    /* Target Framing Overlay */
    .scanner-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }

    .scan-target {
        width: 250px;
        height: 250px;
        border: 2px solid rgba(255, 255, 255, 0.4);
        border-radius: 24px;
        position: relative;
        box-shadow: 0 0 0 9999px rgba(15, 23, 42, 0.55);
    }

    /* Corner Markers */
    .scan-target::before, .scan-target::after {
        content: '';
        position: absolute;
        width: 30px;
        height: 30px;
        border-color: #ffffff;
        border-style: solid;
    }
    .scan-target::before { top: -2px; left: -2px; border-width: 4px 0 0 4px; border-top-left-radius: 18px; }
    .scan-target::after { top: -2px; right: -2px; border-width: 4px 4px 0 0; border-top-right-radius: 18px; }

    .corner-bottom-left {
        position: absolute; bottom: -2px; left: -2px; width: 30px; height: 30px;
        border-color: #ffffff; border-style: solid; border-width: 0 0 4px 4px; border-bottom-left-radius: 18px;
    }
    .corner-bottom-right {
        position: absolute; bottom: -2px; right: -2px; width: 30px; height: 30px;
        border-color: #ffffff; border-style: solid; border-width: 0 4px 4px 0; border-bottom-right-radius: 18px;
    }

    /* Laser Line Scan Animation */
    .laser-line {
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, transparent, #ffffff, transparent);
        position: absolute;
        top: 0;
        box-shadow: 0 0 15px #ffffff;
        animation: scan-laser 2.2s ease-in-out infinite alternate;
    }

    @keyframes scan-laser {
        0% { top: 4%; }
        100% { top: 94%; }
    }

    /* Toolbar buttons below camera */
    .scanner-controls {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        margin-top: 16px;
    }

    .btn-icon-control {
        width: 50px;
        height: 50px;
        border-radius: 16px;
        background: var(--bg);
        border: 1px solid var(--border);
        color: var(--text-main);
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .btn-icon-control:active {
        transform: scale(0.92);
        background: var(--text-main);
        color: var(--bg);
    }

    /* Result Modal Backdrop */
    .result-modal-backdrop {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(12px);
        z-index: 2500;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .result-modal-card {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 24px;
        padding: 28px 24px;
        width: 100%;
        max-width: 420px;
        text-align: center;
        box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        animation: modalPop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes modalPop {
        0% { opacity: 0; transform: scale(0.8); }
        100% { opacity: 1; transform: scale(1); }
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between;">
    <div>
        <h3 style="font-weight: 800; font-size: 1.2rem; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-qrcode" style="color: var(--text-main);"></i> Live Camera Scanner
        </h3>
        <p style="font-size: 0.78rem; color: var(--text-muted);">Align the faculty QR code inside the box frame</p>
    </div>
    <a href="{{ route('student.dashboard') }}" style="color: var(--text-muted); font-size: 1.2rem; text-decoration: none;">
        <i class="fa-solid fa-xmark"></i>
    </a>
</div>

<!-- Camera Viewport Container -->
<div class="scanner-wrapper">
    <div id="qrReader"></div>
    <div class="scanner-overlay" id="scannerOverlay">
        <div class="scan-target">
            <div class="corner-bottom-left"></div>
            <div class="corner-bottom-right"></div>
            <div class="laser-line"></div>
        </div>
        <div style="margin-top: 18px; background: var(--bg); backdrop-filter: blur(10px); padding: 6px 14px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <i class="fa-solid fa-location-crosshairs" style="color: var(--text-main);" id="gpsIcon"></i>
            <span id="gpsStatusText">Acquiring GPS Location...</span>
        </div>
    </div>
</div>

<!-- Controls Toolbar -->
<div class="scanner-controls">
    <button class="btn-icon-control" id="toggleFlashBtn" onclick="toggleTorch()" title="Toggle Flashlight">
        <i class="fa-solid fa-bolt-lightning"></i>
    </button>
    <button class="btn-icon-control" onclick="switchCamera()" title="Switch Camera">
        <i class="fa-solid fa-camera-rotate"></i>
    </button>
    <button class="btn-icon-control" onclick="openManualInput()" title="Manual Input / Simulation">
        <i class="fa-solid fa-keyboard"></i>
    </button>
</div>

<!-- Manual QR Payload Input Modal (Fallback / Demo) -->
<div class="glass-card" id="manualInputCard" style="display: none; margin-top: 20px; border: 1px dashed var(--border);">
    <h4 style="font-size: 0.95rem; font-weight: 800; color: var(--text-main); margin-bottom: 8px;">Manual QR Token Entry</h4>
    <p style="font-size: 0.78rem; color: var(--text-muted); margin-bottom: 12px;">If camera access is unavailable or testing without camera, paste the QR payload string (e.g. <code>uuid|timestamp</code>):</p>
    
    <input type="text" id="manualPayloadInput" placeholder="uuid|timestamp" style="width: 100%; background: var(--subtle-bg); border: 1px solid var(--border); border-radius: 12px; padding: 10px 12px; color: var(--text-main); font-size: 0.88rem; outline: none; margin-bottom: 12px;">
    
    <div style="display: flex; gap: 10px;">
        <button class="btn-primary" onclick="submitManualPayload()" style="padding: 10px; font-size: 0.88rem;">Submit Code</button>
        <button style="background: var(--subtle-bg); border: 1px solid var(--border); color: var(--text-main); padding: 10px; border-radius: 12px; font-size: 0.88rem; cursor: pointer; font-weight: 600;" onclick="document.getElementById('manualInputCard').style.display='none'">Close</button>
    </div>
</div>

<!-- Attendance Result Modal -->
<div class="result-modal-backdrop" id="resultModal">
    <div class="result-modal-card">
        <div id="modalStatusIcon" style="width: 72px; height: 72px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; font-size: 2.2rem;"></div>

        <h3 id="modalTitle" style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 6px;"></h3>
        <p id="modalMessage" style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 20px;"></p>

        <div id="modalDetailsCard" style="background: var(--subtle-bg); border: 1px solid var(--border); border-radius: 16px; padding: 14px; margin-bottom: 20px; text-align: left; display: none;">
            <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Class Details</div>
            <div id="modalSubject" style="font-size: 1rem; font-weight: 800; color: var(--text-main); margin-top: 4px;"></div>
            <div style="font-size: 0.82rem; color: var(--text-main); font-weight: 600; margin-top: 4px; display: flex; align-items: center; justify-content: space-between;">
                <span id="modalFaculty"></span>
                <span id="modalTime"></span>
            </div>
        </div>
        
        <div id="modalWaitMessage" style="display: none; font-size: 0.85rem; color: var(--text-main); margin-bottom: 20px; font-weight: 600;">
            <i class="fa-solid fa-spinner fa-spin"></i> Waiting for faculty to end the session...
        </div>

        <button id="modalCloseBtn" class="btn-primary" onclick="closeResultModal()">Done & Return to Dashboard</button>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
    const registeredFaceDescriptors = {!! $student->face_descriptor ? $student->face_descriptor : 'null' !!};
    const MODEL_URL = 'https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js@master/weights';
    let faceModelsLoaded = false;
    
    // Preload models for fast recognition later
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof faceapi !== 'undefined') {
            Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
            ]).then(() => {
                faceModelsLoaded = true;
                console.log("Face models loaded for fast matching");
            }).catch(err => console.error("Failed to load models:", err));
        }
    });

    let html5QrCode = null;
    let currentCameraId = null;
    let availableCameras = [];
    let isProcessing = false;
    let userLatitude = null;
    let userLongitude = null;
    let mediaTrack = null;
    let torchOn = false;

    document.addEventListener('DOMContentLoaded', () => {
        initGPS();
        initScanner();
    });

    // Acquire GPS Coordinates
    function initGPS() {
        if ('geolocation' in navigator) {
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    userLatitude = pos.coords.latitude;
                    userLongitude = pos.coords.longitude;
                    document.getElementById('gpsStatusText').innerText = `GPS Verified (${userLatitude.toFixed(3)}, ${userLongitude.toFixed(3)})`;
                    document.getElementById('gpsIcon').style.color = '#10b981';
                },
                (err) => {
                    document.getElementById('gpsStatusText').innerText = 'Location permission disabled';
                    document.getElementById('gpsIcon').style.color = '#ef4444';
                },
                { enableHighAccuracy: true, timeout: 8000 }
            );
        }
    }

    // Initialize Camera Scanner
    function initScanner() {
        html5QrCode = new Html5Qrcode("qrReader");

        Html5Qrcode.getCameras().then(cameras => {
            if (cameras && cameras.length) {
                availableCameras = cameras;
                // Prefer rear / back camera
                const rearCamera = cameras.find(c => c.label.toLowerCase().includes('back') || c.label.toLowerCase().includes('rear')) || cameras[0];
                currentCameraId = rearCamera.id;
                startScanner(currentCameraId);
            } else {
                showToast('No camera detected on device', 'error');
                openManualInput();
            }
        }).catch(err => {
            console.error('[Scanner] Camera access error:', err);
            document.getElementById('gpsStatusText').innerText = 'Camera permission required';
            openManualInput();
        });
    }

    function startScanner(cameraId) {
        html5QrCode.start(
            cameraId,
            {
                fps: 15,
                qrbox: { width: 240, height: 240 }
            },
            (decodedText, decodedResult) => {
                onQrScanned(decodedText);
            },
            (errorMessage) => {
                // Ignore scanning cycle misses
            }
        ).then(() => {
            // Attempt to capture video track for Torch
            const videoElem = document.querySelector("#qrReader video");
            if (videoElem && videoElem.srcObject) {
                const tracks = videoElem.srcObject.getVideoTracks();
                if (tracks.length > 0) {
                    mediaTrack = tracks[0];
                }
            }
        }).catch(err => {
            console.error('[Scanner] Failed to start camera:', err);
        });
    }

    // Switch between front and back camera
    function switchCamera() {
        if (!availableCameras.length) return;
        const currentIndex = availableCameras.findIndex(c => c.id === currentCameraId);
        const nextIndex = (currentIndex + 1) % availableCameras.length;
        currentCameraId = availableCameras[nextIndex].id;

        html5QrCode.stop().then(() => {
            startScanner(currentCameraId);
            showToast('Switched Camera', 'success');
        });
    }

    // Toggle Torch/Flashlight
    function toggleTorch() {
        if (!mediaTrack) {
            showToast('Flashlight not available on this camera', 'error');
            return;
        }
        const capabilities = mediaTrack.getCapabilities ? mediaTrack.getCapabilities() : {};
        if (capabilities.torch) {
            torchOn = !torchOn;
            mediaTrack.applyConstraints({ advanced: [{ torch: torchOn }] })
                .then(() => {
                    document.getElementById('toggleFlashBtn').style.color = torchOn ? 'var(--text-muted)' : 'var(--text-main)';
                });
        } else {
            showToast('Flashlight unsupported by browser', 'error');
        }
    }

    // Handle Successful Scanned QR Code Payload
    function onQrScanned(payload) {
        if (isProcessing) return;
        isProcessing = true;

        // Play Synthesizer Audio Beep Chime
        playChimeSound();

        // Haptic Vibration
        if (navigator.vibrate) {
            navigator.vibrate([100, 40, 100]);
        }

        // Send Ajax Request to Mark Attendance
        fetch("{{ route('student.attendance.mark') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                payload: payload,
                device_id: getOrGenerateDeviceId(),
                latitude: userLatitude,
                longitude: userLongitude
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.device_locked) {
                showModal('Device Access Error', data.message, 'error');
                setTimeout(() => window.location.href = "{{ route('student.login') }}", 2500);
                return;
            }

            if (data.success) {
                showModal('Attendance Marked!', data.message, 'success', data);
                if (data.session_uuid) {
                    listenForSessionEnd(data.session_uuid);
                }
            } else {
                showModal('Attendance Failed', data.message || 'Invalid or expired QR code.', 'error');
            }
        })
        .catch(err => {
            showModal('Network Error', 'Unable to connect to server. Please check your internet connection.', 'error');
        });
    }

    // Synthesize Success Audio Chime
    function playChimeSound() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
            osc.frequency.exponentialRampToValueAtTime(880, ctx.currentTime + 0.15); // A5
            gain.gain.setValueAtTime(0.3, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.3);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.3);
        } catch (e) {
            // Audio context disabled
        }
    }

    // Display Result Modal
    function showModal(title, msg, type = 'success', data = null) {
        const modal = document.getElementById('resultModal');
        const iconContainer = document.getElementById('modalStatusIcon');
        
        document.getElementById('modalTitle').innerText = title;
        document.getElementById('modalMessage').innerText = msg;

        if (type === 'success') {
            iconContainer.style.background = '#d1fae5';
            iconContainer.style.color = '#10b981';
            iconContainer.innerHTML = '<i class="fa-solid fa-circle-check"></i>';

            if (data && data.subject) {
                document.getElementById('modalDetailsCard').style.display = 'block';
                document.getElementById('modalSubject').innerText = data.subject;
                document.getElementById('modalFaculty').innerText = data.faculty ? `Prof. ${data.faculty}` : '';
                document.getElementById('modalTime').innerText = data.time || '';
            }
        } else {
            iconContainer.style.background = '#fee2e2';
            iconContainer.style.color = '#ef4444';
            iconContainer.innerHTML = '<i class="fa-solid fa-circle-xmark"></i>';
            document.getElementById('modalDetailsCard').style.display = 'none';
        }

        modal.style.display = 'flex';
    }

    function listenForSessionEnd(uuid) {
        if (!window.Echo) return;
        
        // Hide standard close button, show wait message
        document.getElementById('modalCloseBtn').style.display = 'none';
        document.getElementById('modalWaitMessage').style.display = 'block';

        window.Echo.join('attendance.session.' + uuid)
            .listen('.LiveAttendanceAction', (e) => {
                if (e.action === 'session_ended') {
                    // Turn wait message green and redirect
                    const waitMsg = document.getElementById('modalWaitMessage');
                    waitMsg.style.color = 'var(--text-main)';
                    waitMsg.innerHTML = '<i class="fa-solid fa-check"></i> Session closed by faculty. Redirecting...';
                    
                    setTimeout(() => {
                        window.location.href = "{{ route('student.dashboard') }}";
                    }, 2000);
                } else if (e.action === 'facial_scan_requested') {
                    document.getElementById('modalStatusIcon').innerHTML = '<i class="fa-solid fa-camera"></i>';
                    document.getElementById('modalStatusIcon').style.color = '#000000';
                    document.getElementById('modalTitle').innerText = 'Facial Scan Required';
                    document.getElementById('modalMessage').innerText = 'Faculty has requested a live facial match to verify your attendance.';
                    
                    const waitMsg = document.getElementById('modalWaitMessage');
                    waitMsg.style.color = 'var(--text-main)';
                    waitMsg.innerHTML = `
                        <div style="background: var(--subtle-bg); border: 1px solid var(--border); padding: 15px; border-radius: 12px; margin-top: 10px; text-align: center;">
                            <div style="font-weight: 800; font-size: 1rem; margin-bottom: 6px;">Live Face Verification</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 12px;">Position your face inside the frame.</div>
                            <button onclick="startFaceScan(this)" style="width: 100%; background: #000; color: #fff; border: none; padding: 10px; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                <i class="fa-solid fa-camera"></i> Open Camera
                            </button>
                        </div>
                    `;
                }
            });
    }

    async function startFaceScan(btn) {
        if (!registeredFaceDescriptors) {
            alert("No registered face found. Please register your face in your profile.");
            return;
        }

        const waitMsg = document.getElementById('modalWaitMessage');
        waitMsg.innerHTML = `
            <div style="background: #000; border-radius: 12px; overflow: hidden; position: relative; width: 100%; aspect-ratio: 1; display: flex; align-items: center; justify-content: center;">
                <video id="faceVideo" autoplay muted playsinline style="width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1);"></video>
                <canvas id="faceOverlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; transform: scaleX(-1);"></canvas>
                <div id="faceStatus" style="position: absolute; bottom: 10px; left: 10px; right: 10px; background: rgba(0,0,0,0.6); color: #fff; padding: 5px; border-radius: 8px; text-align: center; font-size: 0.8rem;">Initializing...</div>
            </div>
        `;

        if (!faceModelsLoaded) {
            document.getElementById('faceStatus').innerText = "Loading AI Models...";
            while (!faceModelsLoaded) {
                await new Promise(resolve => setTimeout(resolve, 500));
            }
        }

        document.getElementById('faceStatus').innerText = "Starting Camera...";
        const video = document.getElementById('faceVideo');
        const overlay = document.getElementById('faceOverlay');

        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" } });
            video.srcObject = stream;
        } catch(err) {
            document.getElementById('faceStatus').innerText = "Camera Access Denied";
            return;
        }

        // Setup matcher
        const parsedDescriptors = registeredFaceDescriptors.map(arr => new Float32Array(arr));
        const labeledDescriptor = new faceapi.LabeledFaceDescriptors('student', parsedDescriptors);
        // Using 0.45 for slightly stricter matching to prevent false positives
        const faceMatcher = new faceapi.FaceMatcher(labeledDescriptor, 0.45); 

        let verificationInterval;
        let scanAttempts = 0;

        video.addEventListener('play', () => {
            document.getElementById('faceStatus').innerText = "Analyzing Face...";
            const displaySize = { width: video.clientWidth, height: video.clientHeight };
            faceapi.matchDimensions(overlay, displaySize);

            verificationInterval = setInterval(async () => {
                const detections = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptor();
                
                const ctx = overlay.getContext('2d');
                ctx.clearRect(0, 0, overlay.width, overlay.height);

                if (detections) {
                    const resizedDetections = faceapi.resizeResults(detections, displaySize);
                    
                    // Draw box
                    const box = resizedDetections.detection.box;
                    ctx.strokeStyle = '#6366f1';
                    ctx.lineWidth = 2;
                    ctx.strokeRect(box.x, box.y, box.width, box.height);

                    const bestMatch = faceMatcher.findBestMatch(detections.descriptor);
                    
                    if (bestMatch.label === 'student') {
                        // Match successful!
                        clearInterval(verificationInterval);
                        video.srcObject.getTracks().forEach(track => track.stop());
                        
                        document.getElementById('modalTitle').innerText = 'Attendance Confirmed!';
                        document.getElementById('modalMessage').innerText = 'Facial match successful. Waiting for session to end.';
                        document.getElementById('modalStatusIcon').innerHTML = '<i class="fa-solid fa-check"></i>';
                        document.getElementById('modalStatusIcon').style.color = '#10b981';
                        
                        waitMsg.innerHTML = '<div style="color: var(--text-main);"><i class="fa-solid fa-spinner fa-spin"></i> Waiting for faculty to end the session...</div>';
                    } else {
                        document.getElementById('faceStatus').innerText = "Face not matched. Try again.";
                        document.getElementById('faceStatus').style.color = "#ef4444";
                    }
                } else {
                    document.getElementById('faceStatus').innerText = "No face detected.";
                    document.getElementById('faceStatus').style.color = "#ffffff";
                }
                
                scanAttempts++;
                if (scanAttempts > 60) { // 30 seconds max (500ms * 60)
                    clearInterval(verificationInterval);
                    video.srcObject.getTracks().forEach(track => track.stop());
                    waitMsg.innerHTML = '<div style="color: #ef4444; font-weight: bold;"><i class="fa-solid fa-triangle-exclamation"></i> Verification timed out.</div>';
                }
            }, 500);
        });
    }

    function closeResultModal() {
        document.getElementById('resultModal').style.display = 'none';
        isProcessing = false;
        window.location.href = "{{ route('student.dashboard') }}";
    }

    function openManualInput() {
        document.getElementById('manualInputCard').style.display = 'block';
    }

    function submitManualPayload() {
        const payload = document.getElementById('manualPayloadInput').value.trim();
        if (!payload) {
            showToast('Please enter QR code string', 'error');
            return;
        }
        onQrScanned(payload);
    }
</script>
@endsection
