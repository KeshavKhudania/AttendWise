<x-structure />
<x-header heading="{{ $title }}" />

<div class="aw-page-header mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-heading mb-1">{{ $title }}</h1>
            <p class="text-muted small mb-0">Define building block identifiers and map physical perimeter coordinates for geofencing.</p>
        </div>
        <div>
            <a href="{{ route('institution.block.manage') }}" class="btn btn-light border">
                <i class="fa fa-arrow-left me-1.5 opacity-75"></i> Back to Blocks
            </a>
        </div>
    </div>
</div>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card aw-form-card shadow-sm border-0">
        <div class="card-body">

            <form action="{{ $action }}" method="POST" id="mainForm" data-form-type="{{ $type }}">
                @csrf
                @if(isset($type) && ($type === 'edit' || $type === 'EDIT'))
                    @method('PUT')
                @endif

                <div class="row g-4">

                    {{-- Block Identification Section --}}
                    <div class="col-md-12">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">Building Block Identification</h3>
                                <p class="aw-form-section-subtitle">Specify unique block name or structural identifier</p>
                            </div>
                        </div>
                    </div>

                    {{-- Block Name --}}
                    <div class="col-md-12">
                        <div class="aw-field-group">
                            <label for="name" class="form-label aw-field-label">Block Name <span class="aw-field-required">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" required
                                placeholder="e.g. Science & Technology Block - North Wing" value="{{ old('name', $block->name ?? '') }}">
                        </div>
                    </div>

                    {{-- Geofence Area Section --}}
                    <div class="col-md-12 mt-4">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-draw-polygon"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">Geofence Area (Lat / Lng)</h3>
                                <p class="aw-form-section-subtitle">Interactively click the map or click GPS to mark block perimeter vertices</p>
                            </div>
                        </div>
                    </div>

                    {{-- Map Container --}}
                    <div class="col-md-12">
                        <div class="border rounded p-2 bg-light">
                            <div id="map" style="height:450px; border-radius:8px;" class="shadow-sm"></div>

                            <div id="latlngRows" class="mt-3"></div>

                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addRowFromGPS()">
                                <i class="fas fa-location-arrow me-1"></i> Add Point via GPS / Manual
                            </button>

                            {{-- Hidden JSON field --}}
                            <input type="hidden" name="latlng" id="latlng"
                                value="{{ old('latlng', is_array($block['latlng'] ?? null) ? json_encode($block['latlng']) : ($block['latlng'] ?? '')) }}">
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="col-md-12">
                        <x-form-buttons />
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const map = L.map('map').setView([28.6139, 77.2090], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let points = [];
        let polygon = null;
        let markers = [];

        const rowsContainer = document.getElementById('latlngRows');
        const hiddenField = document.getElementById('latlng');

        /* ------------------------
           Load existing points
        -------------------------*/
        if (hiddenField.value) {
            try {
                points = JSON.parse(hiddenField.value);
                points.forEach(p => appendRow(p[0], p[1], false));
                syncFromInputs();
                fitBounds();
            } catch (e) { console.error("Error loading points", e); }
        }

        /* ------------------------
           Map click → add row
        -------------------------*/
        map.on('click', function (e) {
            appendRow(e.latlng.lat, e.latlng.lng, true);
        });

        /* ------------------------
           Add row from GPS
        -------------------------*/
        window.addRowFromGPS = function () {
            if (!navigator.geolocation) {
                alert('Geolocation not supported');
                return;
            }

            navigator.geolocation.getCurrentPosition(
                pos => {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;

                    // 🔥 Focus map on current location
                    map.setView([lat, lng], 18, {
                        animate: true
                    });

                    // Add row + marker
                    appendRow(lat, lng, true);
                },
                () => alert('Location permission denied')
            );
        };


        /* ------------------------
           Append input row
        -------------------------*/
        function appendRow(lat, lng, update = true) {
            const row = document.createElement('div');
            row.className = 'row g-2 mb-2 latlng-row';

            row.innerHTML = `
            <div class="col-md-5">
                <input type="number" step="any" class="form-control lat" value="${lat}">
            </div>
            <div class="col-md-5">
                <input type="number" step="any" class="form-control lng" value="${lng}">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm w-100">×</button>
            </div>
        `;

            row.querySelector('button').onclick = () => {
                row.remove();
                syncFromInputs();
            };

            row.querySelectorAll('input').forEach(inp => {
                inp.oninput = syncFromInputs;
            });

            rowsContainer.appendChild(row);

            if (update) syncFromInputs();
        }

        /* ------------------------
           Sync inputs → polygon
        -------------------------*/
        function syncFromInputs() {
            points = [];
            markers.forEach(m => map.removeLayer(m));
            markers = [];

            document.querySelectorAll('.latlng-row').forEach(row => {
                const lat = parseFloat(row.querySelector('.lat').value);
                const lng = parseFloat(row.querySelector('.lng').value);
                if (!isNaN(lat) && !isNaN(lng)) {
                    points.push([lat, lng]);
                    markers.push(L.marker([lat, lng]).addTo(map));
                }
            });

            redraw();
            hiddenField.value = JSON.stringify(points);
        }

        /* ------------------------
           Draw polygon
        -------------------------*/
        function redraw() {
            if (polygon) map.removeLayer(polygon);
            if (points.length >= 3) {
                polygon = L.polygon(points, {
                    color: 'blue',
                    fillOpacity: 0.25
                }).addTo(map);
            }
        }

        function fitBounds() {
            if (points.length >= 3) {
                map.fitBounds(points);
            } else if (points.length > 0) {
                map.setView(points[0], 18);
            }
        }

    });
</script>
<x-footer />