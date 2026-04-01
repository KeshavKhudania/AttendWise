<x-structure />
<x-header heading="{{ $title }}" />

<div class="aw-page-header mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-heading mb-1">{{ $title }}</h1>
            <p class="text-muted small mb-0">Define venue details and draw a geofence area on the map below.</p>
        </div>
        <div>
            <a href="{{ route('institution.venues') }}" class="btn btn-light border d-flex align-items-center">
                <i class="fa fa-arrow-left me-2"></i> Back to List
            </a>
        </div>
    </div>
</div>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form action="{{ $action }}" method="POST" id="mainForm" data-form-type="{{ $type }}">
                @csrf

                <div class="row g-4">
                    {{-- Basic Info Section --}}
                    <div class="col-md-12">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3"
                                style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                <i class="fa fa-info-circle small"></i>
                            </div>
                            <h6 class="mb-0 fw-bold">General Information</h6>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="name" class="form-label fw-600">Venue Name <span
                                class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control msc-form-control" required
                            placeholder="e.g. Cricket Ground, Boy's Hostel"
                            value="{{ old('name', $venue->name ?? '') }}">
                    </div>

                    <div class="col-md-6">
                        <label for="type" class="form-label fw-600">Venue Type <span
                                class="text-danger">*</span></label>
                        <select name="type" id="type" class="form-select msc-searchable" required>
                            <option value="">-- Select Type --</option>
                            <option value="Ground" {{ (old('type', $venue->type ?? '') == 'Ground') ? 'selected' : ''
                                }}>Ground</option>
                            <option value="Hostel" {{ (old('type', $venue->type ?? '') == 'Hostel') ? 'selected' : ''
                                }}>Hostel</option>
                            <option value="Auditorium" {{ (old('type', $venue->type ?? '') == 'Auditorium') ? 'selected'
                                : '' }}>Auditorium</option>
                            <option value="Canteen" {{ (old('type', $venue->type ?? '') == 'Canteen') ? 'selected' : ''
                                }}>Canteen</option>
                            <option value="Other" {{ (old('type', $venue->type ?? '') == 'Other') ? 'selected' : ''
                                }}>Other</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label for="description" class="form-label fw-600">Description</label>
                        <textarea name="description" id="description" class="form-control msc-form-control" rows="2"
                            placeholder="Brief details about the venue">{{ old('description', $venue->description ?? '') }}</textarea>
                    </div>

                    {{-- Geofence Area Section --}}
                    <div class="col-md-12 mt-5">
                        <div class="d-flex align-items-center mb-1">
                            <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-2 me-3"
                                style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                <i class="fa fa-map-marker-alt small"></i>
                            </div>
                            <h6 class="mb-0 fw-bold">Venue Area / Geofence</h6>
                        </div>
                        <p class="text-muted small ms-5 mb-3">Define the perimeter by clicking on the map to create
                            points of a polygon.</p>

                        <div id="map" style="height:480px; border-radius:16px; border: 1px solid var(--aw-gray-200);"
                            class="shadow-sm"></div>

                        <div id="latlngRows" class="mt-4 row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3"></div>

                        <div class="mt-4 p-3 bg-light rounded-3 d-flex flex-wrap gap-2 align-items-center">
                            <button type="button" class="btn btn-white shadow-xs border btn-sm py-2 px-3 fw-600"
                                onclick="addRowFromGPS()">
                                <i class="fa fa-crosshairs me-2 text-primary"></i> Add Current Location
                            </button>
                            <button type="button"
                                class="btn btn-white shadow-xs border btn-sm py-2 px-3 fw-600 text-danger"
                                onclick="clearPoints()">
                                <i class="fa fa-trash-alt me-2"></i> Clear All Points
                            </button>
                            <div class="ms-auto text-muted small">
                                <i class="fa fa-info-circle me-1"></i> Minimum 3 points required for a valid geofence.
                            </div>
                        </div>

                        {{-- Hidden JSON field --}}
                        <input type="hidden" name="latlng" id="latlng"
                            value="{{ old('latlng', is_array($venue->latlng ?? null) ? json_encode($venue->latlng) : ($venue->latlng ?? '')) }}">
                    </div>

                    <div class="form-actions-bar">
                        <div class="container-fluid d-flex justify-content-end align-items-center">
                            <a href="{{ route('institution.venues') }}"
                                class="btn btn-light me-3 px-4 py-2 border">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">
                                <i class="fa fa-save me-2"></i> {{ $type == 'ADD' ? 'Create Venue' : 'Save Changes' }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        </form>

    </div>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const map = L.map('map').setView([20.5937, 78.9629], 5); // Default India view

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
                    color: '#dc3545', // Danger theme for venues
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