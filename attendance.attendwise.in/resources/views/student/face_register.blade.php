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

    /* Guidelines Styles */
    #guidelinesPage {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        margin-bottom: 20px;
        animation: fadeIn 0.5s ease;
    }

    .guidelines-title {
        font-weight: 800;
        font-size: 1.3rem;
        color: #0f172a;
        margin-bottom: 15px;
        text-align: center;
    }

    .guidelines-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 15px;
        margin-bottom: 25px;
    }

    @media(min-width: 600px) {
        .guidelines-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    .guideline-card {
        padding: 15px;
        border-radius: 15px;
        border: 1px solid #e2e8f0;
    }

    .guideline-card.do {
        background-color: #f0fdf4;
        border-color: #bbf7d0;
    }

    .guideline-card.dont {
        background-color: #fef2f2;
        border-color: #fecaca;
    }

    .guideline-header {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        margin-bottom: 10px;
        font-size: 1.1rem;
    }

    .guideline-card.do .guideline-header {
        color: #166534;
    }

    .guideline-card.dont .guideline-header {
        color: #991b1b;
    }

    .guideline-list {
        margin: 0;
        padding-left: 20px;
        font-size: 0.9rem;
        color: #475569;
    }
    
    .guideline-list li {
        margin-bottom: 5px;
    }

    .visual-aids {
        display: flex;
        justify-content: space-around;
        margin-top: 15px;
    }

    .visual-aid {
        text-align: center;
        font-size: 0.8rem;
        color: #64748b;
    }

    .visual-aid i {
        font-size: 2rem;
        margin-bottom: 5px;
        display: block;
    }
    
    .visual-aid.do i { color: #22c55e; }
    .visual-aid.dont i { color: #ef4444; }

    .btn-start {
        width: 100%;
        padding: 12px;
        background: #6366f1;
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-start:hover {
        background: #4f46e5;
    }

    #cameraSection {
        display: none;
    }

    .glasses-warning {
        position: absolute;
        top: 20px;
        left: 20px;
        right: 20px;
        background: #ef4444;
        color: white;
        padding: 10px;
        border-radius: 10px;
        text-align: center;
        font-weight: 600;
        z-index: 25;
        font-size: 0.9rem;
        display: none;
        box-shadow: 0 4px 10px rgba(239, 68, 68, 0.4);
        animation: pulse 2s infinite;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.02); }
        100% { transform: scale(1); }
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 16px;">
    <h3 style="font-weight: 800; font-size: 1.2rem; color: var(--text-main); margin-bottom: 4px;">Face Registration</h3>
    <p style="font-size: 0.78rem; color: var(--text-muted);">Look into the camera to capture your biometrics.</p>
</div>

<!-- Guidelines Section -->
<div id="guidelinesPage">
    <div class="guidelines-title">Registration Guidelines</div>
    <p style="text-align: center; color: #64748b; font-size: 0.9rem; margin-bottom: 20px;">Please follow these rules for accurate face capture.</p>
    
    <div class="guidelines-grid">
        <div class="guideline-card do">
            <div class="guideline-header">
                <i class="fa-solid fa-check-circle"></i> DO's
            </div>
            <ul class="guideline-list">
                <li>Look straight into the camera</li>
                <li>Ensure good, even lighting on your face</li>
                <li>Keep a neutral facial expression</li>
            </ul>
            <div class="visual-aids">
                <div class="visual-aid do">
                    <i class="fa-solid fa-lightbulb"></i>
                    Good Light
                </div>
                <div class="visual-aid do">
                    <i class="fa-solid fa-face-smile"></i>
                    Face Forward
                </div>
            </div>
        </div>
        
        <div class="guideline-card dont">
            <div class="guideline-header">
                <i class="fa-solid fa-times-circle"></i> DON'Ts
            </div>
            <ul class="guideline-list">
                <li><strong>No eyeglasses or sunglasses</strong></li>
                <li>No masks, hats, or face coverings</li>
                <li>Avoid strong backlight (e.g., a window behind you)</li>
            </ul>
            <div class="visual-aids">
                <div class="visual-aid dont">
                    <i class="fa-solid fa-glasses"></i>
                    No Glasses
                </div>
                <div class="visual-aid dont">
                    <i class="fa-solid fa-mask"></i>
                    No Masks
                </div>
            </div>
        </div>
    </div>
    
    <button id="startCaptureBtn" class="btn-start">I Understand, Start Capture</button>
</div>

<!-- Camera Section -->
<div id="cameraSection">
    <div class="camera-container" id="cameraContainer">
        <div id="loadingScreen">
            <i class="fa-solid fa-spinner fa-spin" style="font-size: 2.5rem; margin-bottom: 15px; color: #6366f1;"></i>
            <div style="font-weight: 700; font-size: 1rem;">Loading AI Models...</div>
            <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 5px;">This may take a moment.</div>
        </div>
        
        <div class="glasses-warning" id="glassesWarning">
            <i class="fa-solid fa-triangle-exclamation"></i> Eyeglasses detected! Please remove them.
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
        
        const startCaptureBtn = document.getElementById('startCaptureBtn');
        const guidelinesPage = document.getElementById('guidelinesPage');
        const cameraSection = document.getElementById('cameraSection');
        const glassesWarning = document.getElementById('glassesWarning');

        startCaptureBtn.addEventListener('click', () => {
            guidelinesPage.style.display = 'none';
            cameraSection.style.display = 'block';
            loadModels();
        });

        // We will capture 3 descriptors: Straight, Slight Left, Slight Right
        let descriptors = [];
        let captureStage = 0; 
        let isProcessing = false;
        let detectionInterval;

        // Load Models from the raw GitHub repo (weights)
        const MODEL_URL = 'https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js@master/weights';

        function loadModels() {
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
        
        function detectGlasses(videoEl, landmarks) {
            try {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d', { willReadFrequently: true });
                canvas.width = videoEl.videoWidth;
                canvas.height = videoEl.videoHeight;
                ctx.drawImage(videoEl, 0, 0, canvas.width, canvas.height);
                
                // Nose bridge area (between eyes)
                const leftEyeInner = landmarks.positions[39];
                const rightEyeInner = landmarks.positions[42];
                
                const midX = (leftEyeInner.x + rightEyeInner.x) / 2;
                const midY = (leftEyeInner.y + rightEyeInner.y) / 2;
                
                const dist = rightEyeInner.x - leftEyeInner.x;
                const boxWidth = Math.floor(dist * 0.8);
                const boxHeight = Math.floor(dist * 0.4);
                
                const x = Math.floor(midX - boxWidth / 2);
                const y = Math.floor(midY - boxHeight / 2);
                
                if (boxWidth <= 0 || boxHeight <= 0 || x < 0 || y < 0) return false;
                
                const imgData = ctx.getImageData(x, y, boxWidth, boxHeight);
                const data = imgData.data;
                let edges = 0;
                
                for (let i = 1; i < boxHeight - 1; i++) {
                    for (let j = 1; j < boxWidth - 1; j++) {
                        const idx = (i * boxWidth + j) * 4;
                        const topIdx = ((i - 1) * boxWidth + j) * 4;
                        const bottomIdx = ((i + 1) * boxWidth + j) * 4;
                        
                        const gray = (data[idx] + data[idx+1] + data[idx+2]) / 3;
                        const topGray = (data[topIdx] + data[topIdx+1] + data[topIdx+2]) / 3;
                        const bottomGray = (data[bottomIdx] + data[bottomIdx+1] + data[bottomIdx+2]) / 3;
                        
                        const diffY = Math.abs(topGray - bottomGray);
                        
                        if (diffY > 35) {
                            edges++;
                        }
                    }
                }
                
                const edgeDensity = edges / (boxWidth * boxHeight);
                return edgeDensity > 0.08; 
            } catch(e) {
                return false;
            }
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
                    const hasGlasses = detectGlasses(video, detections.landmarks);
                    
                    if (hasGlasses) {
                        glassesWarning.style.display = 'block';
                        instructionTitle.innerText = "Remove Glasses";
                        instructionSub.innerText = "Please remove your eyeglasses to continue.";
                    } else {
                        glassesWarning.style.display = 'none';
                        const resizedDetections = faceapi.resizeResults(detections, displaySize);
                        
                        // Draw face box for feedback
                        const box = resizedDetections.detection.box;
                        ctx.strokeStyle = '#6366f1';
                        ctx.lineWidth = 3;
                        ctx.strokeRect(box.x, box.y, box.width, box.height);

                        await handleCaptureStage(detections.descriptor);
                    }
                } else {
                    glassesWarning.style.display = 'none';
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
