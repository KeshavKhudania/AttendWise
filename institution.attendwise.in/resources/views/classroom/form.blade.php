<x-structure />
<x-header heading="{{ $title }}" />

<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">

      <form action="{{ $action }}" method="POST" id="mainForm">
        @csrf
        @if($type === 'edit')
        @method('PUT')
        @endif

        <div class="row g-3">

          {{-- Classroom Name --}}
          <div class="col-md-4">
            <label class="form-label">Classroom Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control msc-form-control" required placeholder="e.g. Room 101"
              value="{{ old('name', $classroom->name ?? '') }}">
          </div>

          {{-- Code --}}
          <div class="col-md-4">
            <label class="form-label">Classroom Code</label>
            <select name="type" class="form-control form-select msc-searchable" required>
              <option value=""></option>
              @foreach ($classroom_types as $classroom_type)
              <option value="{{ $classroom_type->id }}" {{ old('type', $classroom->type ?? '') == $classroom_type->id ?
                'selected' : '' }}>{{ $classroom_type->name }}</option>
              @endforeach
            </select>
          </div>

          {{-- Capacity --}}
          <div class="col-md-4">
            <label class="form-label">Capacity</label>
            <input type="number" name="capacity" class="form-control msc-form-control" min="1" placeholder="e.g. 60"
              value="{{ old('capacity', $classroom->capacity ?? '') }}">
          </div>

          {{-- Block --}}
          <div class="col-md-6">
            <label class="form-label">Block <span class="text-danger">*</span></label>
            <select name="block_id" class="form-control form-select msc-searchable" required>
              <option value=""></option>
              @foreach ($blocks as $block)
              <option value="{{ $block->id }}" {{ old('block_id', $classroom->block_id ?? '') == $block->id ? 'selected'
                : '' }}>{{ $block->name }}</option>
              @endforeach
            </select>
          </div>

          {{-- Department --}}
          <div class="col-md-6">
            <label class="form-label">Department</label>
            <select name="department_id" class="form-control form-select msc-searchable">
              <option value=""></option>
              @foreach ($departments as $dept)
              <option value="{{ $dept->id }}" {{ old('department_id', $classroom->department_id ?? '') == $dept->id ?
                'selected' : '' }}>{{ $dept->name }}</option>
              @endforeach
            </select>
          </div>

          {{-- LAtLng --}}
          <div class="col-md-12">
            <label class="mb-2">Block Area (Lat / Lng)</label>

            <div id="map" style="height:500px;border-radius:8px;"></div>

            <div id="latlngRows" class="mt-3"></div>

            <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addRowFromGPS()">
              + Add Point
            </button>

            {{-- Hidden JSON field --}}
            <input type="hidden" name="latlng" id="latlng"
              value="{{ old('latlng', is_array($classroom['latlng'] ?? null) ? json_encode($classroom['latlng']) : ($classroom['latlng'] ?? '')) }}">
          </div>
          {{-- Buttons --}}
          <div class="col-md-12 mt-3">
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
        redraw();
        fitBounds();
      } catch (e) { }
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
      }
    }

  });
</script>
<x-footer />