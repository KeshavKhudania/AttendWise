@extends('layouts.student')

@section('title', 'Facial Registration - AttendWise PWA')

@section('styles')
<style>
    .camera-container {
        position: relative;
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        background: #000;
        aspect-ratio: 3/4;
    }
    
    #videoElement {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scaleX(-1);
    }
    
    #overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10;
        transform: scaleX(-1);
    }
    
    .instruction-overlay {
        position: absolute;
        bottom: 20px;
        left: 20px;
        right: 20px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        padding: 15px;
        border-radius: 15px;
        text-align: center;
        z-index: 20;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .instruction-text {
        font-weight: 800;
        font-size: 1.1rem;
        color: #0f172a;
        margin-bottom: 5px;
    }
    
    .instruction-subtext {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 600;
    }
    
    .progress-dots {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 10px;
    }
    
    .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #cbd5e1;
        transition: all 0.3s ease;
    }
    
    .dot.active {
        background: #6366f1;
        transform: scale(1.2);
    }
    
    .dot.completed {
        background: #10b981;
    }

    #loadingScreen {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.8);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 30;
        color: white;
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 16px;">
    <h3 style="font-weight: 800; font-size: 1.2rem; color: var(--text-main); margin-bottom: 4px;">Face Registration</h3>
    <p style="font-size: 0.78rem; color: var(--text-muted);">Look into the camera to capture your biometrics.</p>
</div>

<div class="camera-container" id="cameraContainer">
    <div id="loadingScreen">
        <i class="fa-solid fa-spinner fa-spin" style="font-size: 2.5rem; margin-bottom: 15px; color: #6366f1;"></i>
        <div style="font-weight: 700; font-size: 1rem;">Loading AI Models...</div>
        <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 5px;">This may take a moment.</div>
    </div>
    
    <video id="videoElement" autoplay muted playsinline></video>
    <canvas id="overlay"></canvas>
    
    <div class="instruction-overlay">
        <div class="instruction-text" id="instructionTitle">Position Face</div>
        <div class="instruction-subtext" id="instructionSub">Please look straight into the camera.</div>
        
        <div class="progress-dots">
            <div class="dot active" id="dot-0"></div>
            <div class="dot" id="dot-1"></div>
            <div class="dot" id="dot-2"></div>
        </div>
    </div>
</div>

<div style="margin-top: 20px; text-align: center;">
    <a href="{{ route('student.profile') }}" style="color: var(--text-muted); font-size: 0.85rem; font-weight: 600; text-decoration: none;">
        <i class="fa-solid fa-arrow-left"></i> Cancel Registration
    </a>
</div>
@endsection

@section('scripts')
<!-- Load face-api.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const video = document.getElementById('videoElement');
        const overlay = document.getElementById('overlay');
        const instructionTitle = document.getElementById('instructionTitle');
        const instructionSub = document.getElementById('instructionSub');
        const loadingScreen = document.getElementById('loadingScreen');
        
        // We will capture 3 descriptors: Straight, Slight Left, Slight Right
        let descriptors = [];
        let captureStage = 0; 
        let isProcessing = false;
        let detectionInterval;

        // Load Models from the raw GitHub repo (weights)
        const MODEL_URL = 'https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js@master/weights';

        if (typeof faceapi !== 'undefined') {
            Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
            ]).then(startVideo).catch(err => {
                console.error("Failed to load models:", err);
                instructionTitle.innerText = "Error";
                instructionSub.innerText = "Failed to load AI models. Please check connection.";
                loadingScreen.style.display = 'none';
            });
        } else {
            console.error("faceapi is not loaded");
            instructionTitle.innerText = "Error";
            instructionSub.innerText = "Failed to load AI library. Please refresh.";
            loadingScreen.style.display = 'none';
        }

        function startVideo() {
            navigator.mediaDevices.getUserMedia({ 
                video: { 
                    facingMode: "user",
                    width: { ideal: 480 },
                    height: { ideal: 640 }
                } 
            })
            .then(stream => {
                video.srcObject = stream;
                loadingScreen.style.display = 'none';
            })
            .catch(err => {
                console.error("Camera access denied:", err);
                instructionTitle.innerText = "Camera Error";
                instructionSub.innerText = "Please allow camera access to register.";
                loadingScreen.style.display = 'none';
            });
        }

        video.addEventListener('play', () => {
            const displaySize = { width: video.clientWidth, height: video.clientHeight };
            faceapi.matchDimensions(overlay, displaySize);
            
            detectionInterval = setInterval(async () => {
                if (isProcessing) return;
                isProcessing = true;

                const detections = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.6 }))
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                const ctx = overlay.getContext('2d');
                ctx.clearRect(0, 0, overlay.width, overlay.height);

                if (detections) {
                    const resizedDetections = faceapi.resizeResults(detections, displaySize);
                    
                    // Draw face box for feedback
                    const box = resizedDetections.detection.box;
                    ctx.strokeStyle = '#6366f1';
                    ctx.lineWidth = 3;
                    ctx.strokeRect(box.x, box.y, box.width, box.height);

                    await handleCaptureStage(detections.descriptor);
                } else {
                    instructionTitle.innerText = "No Face Detected";
                    instructionSub.innerText = "Please ensure your face is clearly visible.";
                }
                
                isProcessing = false;
            }, 500); // Check every 500ms
        });

        async function handleCaptureStage(descriptor) {
            if (captureStage === 0) {
                instructionTitle.innerText = "Look Straight";
                instructionSub.innerText = "Hold still... capturing forward face.";
                await saveDescriptor(0, descriptor);
            } else if (captureStage === 1) {
                instructionTitle.innerText = "Look Slightly Left";
                instructionSub.innerText = "Turn your head slightly to the left.";
                setTimeout(async () => {
                    await saveDescriptor(1, descriptor);
                }, 1000); // 1 sec delay to allow turning
            } else if (captureStage === 2) {
                instructionTitle.innerText = "Look Slightly Right";
                instructionSub.innerText = "Turn your head slightly to the right.";
                setTimeout(async () => {
                    await saveDescriptor(2, descriptor);
                }, 1000);
            }
        }

        async function saveDescriptor(stageIndex, descriptor) {
            if (descriptors.length > stageIndex) return; 
            descriptors.push(Array.from(descriptor));
            
            document.getElementById('dot-' + stageIndex).classList.remove('active');
            document.getElementById('dot-' + stageIndex).classList.add('completed');
            
            captureStage++;
            
            if (captureStage < 3) {
                document.getElementById('dot-' + captureStage).classList.add('active');
            } else {
                clearInterval(detectionInterval);
                instructionTitle.innerText = "Registration Complete!";
                instructionSub.innerText = "Saving securely...";
                instructionTitle.style.color = '#10b981';
                
                await submitRegistration();
            }
        }

        async function submitRegistration() {
            try {
                const response = await fetch('{{ route("student.face_register.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        descriptors: JSON.stringify(descriptors)
                    })
                });

                const data = await response.json();
                if (data.success) {
                    video.srcObject.getTracks().forEach(track => track.stop());
                    window.location.href = "{{ route('student.profile') }}";
                } else {
                    alert("Failed to save face data.");
                    window.location.reload();
                }
            } catch (err) {
                console.error(err);
                alert("Network error occurred.");
                window.location.reload();
            }
        }
    });
</script>

</script>
@endsection
