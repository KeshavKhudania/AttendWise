<x-structure />
<x-header heading="{{ $title }}" />

<div class="aw-page-header mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-heading mb-1">{{ $title }}</h1>
            <p class="text-muted small mb-0">Define outdoor grounds, hostels, or open venues and configure geofence perimeter coordinates.</p>
        </div>
        <div>
            <a href="{{ route('institution.venues.manage') }}" class="btn btn-light border">
                <i class="fa fa-arrow-left me-1.5 opacity-75"></i> Back to Venues
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
                    {{-- General Info Section --}}
                    <div class="col-md-12">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-landmark"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">Venue Identification</h3>
                                <p class="aw-form-section-subtitle">Venue title, facility category, and optional notes</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="aw-field-group">
                            <label for="name" class="form-label aw-field-label">Venue Name <span class="aw-field-required">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" required
                                placeholder="e.g. Football Ground / Hostel Block C"
                                value="{{ old('name', $venue->name ?? '') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="aw-field-group">
                            <label for="type" class="form-label aw-field-label">Venue Type <span class="aw-field-required">*</span></label>
                            <select name="type" id="type" class="form-select msc-searchable" required>
                                <option value="">-- Select Type --</option>
                                <option value="Ground" {{ (old('type', $venue->type ?? '') == 'Ground') ? 'selected' : '' }}>Ground</option>
                                <option value="Hostel" {{ (old('type', $venue->type ?? '') == 'Hostel') ? 'selected' : '' }}>Hostel</option>
                                <option value="Auditorium" {{ (old('type', $venue->type ?? '') == 'Auditorium') ? 'selected' : '' }}>Auditorium</option>
                                <option value="Canteen" {{ (old('type', $venue->type ?? '') == 'Canteen') ? 'selected' : '' }}>Canteen</option>
                                <option value="Other" {{ (old('type', $venue->type ?? '') == 'Other') ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="aw-field-group">
                            <label for="description" class="form-label aw-field-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="2"
                                placeholder="Brief details regarding capacity, entrance gates, or special instructions">{{ old('description', $venue->description ?? '') }}</textarea>
                        </div>
                    </div>

                    {{-- Geofence Area Section --}}
                    <div class="col-md-12 mt-4">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">Venue Area & Geofence Boundary</h3>
                                <p class="aw-form-section-subtitle">Click the map or fetch current GPS coordinates to set boundary polygon vertices</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="border rounded p-2 bg-light">
                            <div id="map" style="height:450px; border-radius:8px;" class="shadow-sm"></div>

                            <div id="latlngRows" class="mt-3 row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3"></div>

                            <div class="mt-3 p-2 bg-white border rounded d-flex flex-wrap gap-2 align-items-center">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addRowFromGPS()">
                                    <i class="fas fa-location-arrow me-1"></i> Add GPS Point
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="clearPoints()">
                                    <i class="fas fa-trash-alt me-1"></i> Clear All Points
                                </button>
                                <div class="ms-auto text-muted small">
                                    <i class="fas fa-info-circle me-1"></i> Minimum 3 points required for geofencing.
                                </div>
                            </div>

                            {{-- Hidden JSON field --}}
                            <input type="hidden" name="latlng" id="latlng"
                                value="{{ old('latlng', is_array($venue->latlng ?? null) ? json_encode($venue->latlng) : ($venue->latlng ?? '')) }}">
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

        const map = L.map('map').setView([20.5937, 78.9629], 5);

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
                    map.setView([lat, lng], 18, { animate: true });
                    appendRow(lat, lng, true);
                },
                () => alert('Location permission denied')
            );
        };

        window.clearPoints = function () {
            rowsContainer.innerHTML = '';
            syncFromInputs();
        };

        /* ------------------------
           Append input row
        -------------------------*/
        function appendRow(lat, lng, update = true) {
            const row = document.createElement('div');
            row.className = 'col latlng-row';

            row.innerHTML = `
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light">Lat</span>
                    <input type="number" step="any" class="form-control lat" value="${lat}">
                    <span class="input-group-text bg-light">Lng</span>
                    <input type="number" step="any" class="form-control lng" value="${lng}">
                    <button type="button" class="btn btn-outline-danger remove-btn"><i class="fa fa-times"></i></button>
                </div>
            `;

            row.querySelector('.remove-btn').onclick = () => {
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
           Draw geofence polygon
        -------------------------*/
        function redraw() {
            if (polygon) map.removeLayer(polygon);
            if (points.length >= 3) {
                polygon = L.polygon(points, {
                    color: '#4f46e5',
                    fillOpacity: 0.15,
                    weight: 2
                }).addTo(map);
            }
        }

        function fitBounds() {
            if (points.length >= 3) {
                map.fitBounds(points, { padding: [20, 20] });
            } else if (points.length > 0) {
                map.setView(points[0], 18);
            }
        }

    });
</script>
<x-footer />