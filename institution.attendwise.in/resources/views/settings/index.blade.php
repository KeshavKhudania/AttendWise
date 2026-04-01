<x-structure />
<x-header :heading="$title" />

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 py-3">
                    <ul class="nav nav-pills custom-pills" id="settingsTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="general-tab" data-bs-toggle="pill"
                                data-bs-target="#general" type="button">
                                <i class="fa fa-university me-2"></i> General Details
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="academic-tab" data-bs-toggle="pill" data-bs-target="#academic"
                                type="button">
                                <i class="fa fa-book me-2"></i> Academic Config
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="timings-tab" data-bs-toggle="pill" data-bs-target="#timings"
                                type="button">
                                <i class="fa fa-clock me-2"></i> Slot Timings
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="working-tab" data-bs-toggle="pill" data-bs-target="#working"
                                type="button">
                                <i class="fa fa-calendar-check me-2"></i> Working Days
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="holidays-tab" data-bs-toggle="pill" data-bs-target="#holidays"
                                type="button">
                                <i class="fa fa-umbrella-beach me-2"></i> Holidays
                            </button>
                        </li>
                    </ul>
                </div>

                <form id="settingsForm" onsubmit="submitSettings(event)" class="card-body p-4 bg-light">
                    @csrf
                    <div class="tab-content" id="settingsTabsContent">

                        <!-- General Details -->
                        <div class="tab-pane fade show active" id="general">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Institution Legal Name</label>
                                    <input type="text" name="legal_name" class="form-control rounded-3"
                                        value="{{ $institution->legal_name }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Institution Type</label>
                                    <select name="institution_type" class="form-select rounded-3">
                                        <option value="University" {{ $institution->institution_type == 'University' ?
                                            'selected' : '' }}>University</option>
                                        <option value="College" {{ $institution->institution_type == 'College' ?
                                            'selected' : '' }}>College</option>
                                        <option value="School" {{ $institution->institution_type == 'School' ?
                                            'selected' : '' }}>School</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Year of Establishment</label>
                                    <input type="number" name="year_of_establishment" class="form-control rounded-3"
                                        value="{{ $institution->year_of_establishment }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Registered Address</label>
                                    <textarea name="registered_address" class="form-control rounded-3"
                                        rows="3">{{ $institution->registered_address }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Academic Config -->
                        <div class="tab-pane fade" id="academic">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="p-3 bg-white rounded-3 border text-center">
                                        <label class="form-label d-block fw-bold">Slots per Day</label>
                                        <input type="number" name="slots_per_day" id="slots_per_day"
                                            class="form-control text-center fs-4 fw-bold border-0 bg-transparent"
                                            value="{{ $settings->slots_per_day }}" min="1" max="15">
                                        <small class="text-muted">Total periods in the timetable</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 bg-white rounded-3 border text-center">
                                        <label class="form-label d-block fw-bold">Faculty Lecture Limit</label>
                                        <input type="number" name="faculty_lecture_limit"
                                            class="form-control text-center fs-4 fw-bold border-0 bg-transparent"
                                            value="{{ $settings->faculty_lecture_limit }}">
                                        <small class="text-muted">Max lectures a faculty can take daily</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 bg-white rounded-3 border text-center">
                                        <label class="form-label d-block fw-bold">Default Theory Slots</label>
                                        <input type="number" name="theory_slot_limit"
                                            class="form-control text-center fs-4 fw-bold border-0 bg-transparent"
                                            value="{{ $settings->theory_slot_limit }}">
                                        <small class="text-muted">Slots occupied by Theory</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 bg-white rounded-3 border text-center">
                                        <label class="form-label d-block fw-bold">Default Lab Slots</label>
                                        <input type="number" name="lab_slot_limit"
                                            class="form-control text-center fs-4 fw-bold border-0 bg-transparent"
                                            value="{{ $settings->lab_slot_limit }}">
                                        <small class="text-muted">Slots occupied by Lab/Practical</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Slot Timings -->
                        <div class="tab-pane fade" id="timings">
                            <div class="alert alert-info py-2 rounded-3 border-0">
                                <i class="fa fa-info-circle me-2"></i> Set the start and end time for each numbered
                                slot.
                            </div>
                            <div id="timingContainer" class="row g-3 mt-2">
                                @for($i = 1; $i <= $settings->slots_per_day; $i++)
                                    <div class="col-md-6 slot-timing-row" data-slot="{{ $i }}">
                                        <div class="card border-0 shadow-sm rounded-3">
                                            <div class="card-body d-flex align-items-center gap-3">
                                                <span class="badge bg-primary rounded-pill p-2"
                                                    style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">{{
                                                    $i }}</span>
                                                <div class="flex-grow-1">
                                                    <div class="row g-2">
                                                        <div class="col-6">
                                                            <small class="text-muted d-block">Start Time</small>
                                                            <input type="time" name="slot_{{ $i }}_start"
                                                                class="form-control form-control-sm"
                                                                value="{{ $settings->slot_timings[$i]['start'] ?? '' }}">
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-muted d-block">End Time</small>
                                                            <input type="time" name="slot_{{ $i }}_end"
                                                                class="form-control form-control-sm"
                                                                value="{{ $settings->slot_timings[$i]['end'] ?? '' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endfor
                            </div>
                        </div>

                        <!-- Working Days -->
                        <div class="tab-pane fade" id="working">
                            <div class="row g-3">
                                @foreach($working_days_list as $day)
                                <div class="col-md-3">
                                    <div class="form-check custom-card-check">
                                        <input class="form-check-input" type="checkbox" name="working_days[]"
                                            value="{{ $day }}" id="day_{{ $day }}" {{ in_array($day,
                                            $settings->working_days ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label w-100 p-3 rounded-3 border text-center fw-bold"
                                            for="day_{{ $day }}">
                                            {{ $day }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Holidays -->
                        <div class="tab-pane fade" id="holidays">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0">Academic Holidays</h6>
                                <button type="button" onclick="addHolidayRow()"
                                    class="btn btn-sm btn-primary rounded-pill">
                                    <i class="fa fa-plus me-1"></i> Add Holiday
                                </button>
                            </div>
                            <div id="holidayContainer" class="row g-3">
                                @if(!empty($settings->holidays))
                                @foreach($settings->holidays as $holiday)
                                <div class="col-md-6 holiday-row">
                                    <div class="card border-0 shadow-sm rounded-3">
                                        <div class="card-body d-flex gap-2">
                                            <input type="date" name="holiday_date[]"
                                                class="form-control form-control-sm" value="{{ $holiday['date'] }}">
                                            <input type="text" name="holiday_name[]"
                                                class="form-control form-control-sm" value="{{ $holiday['name'] }}"
                                                placeholder="Event Name">
                                            <button type="button" onclick="this.closest('.holiday-row').remove()"
                                                class="btn btn-link text-danger p-0">
                                                <i class="fa fa-times-circle"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>

                    </div>

                    <div class="mt-5 text-end">
                        <button type="submit" class="btn btn-primary px-5 rounded-pill shadow">
                            <i class="fa fa-save me-2"></i> Save All Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-pills .nav-link {
        color: #6c757d;
        font-weight: 600;
        border-radius: 10px;
        padding: 0.8rem 1.2rem;
        margin-right: 0.5rem;
        transition: all 0.3s;
    }

    .custom-pills .nav-link.active {
        background-color: #0d6efd;
        color: #fff;
        box-shadow: 0 4px 15px rgba(13, 110, 253, 0.2);
    }

    .custom-card-check .form-check-input {
        display: none;
    }

    .custom-card-check .form-check-label {
        cursor: pointer;
        transition: all 0.2s;
        background: #fff;
    }

    .custom-card-check .form-check-input:checked+.form-check-label {
        background: #0d6efd;
        color: #fff;
        border-color: #0d6efd !important;
        transform: scale(1.05);
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    }
</style>

<script>
    function addHolidayRow() {
        const row = `
            <div class="col-md-6 holiday-row">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body d-flex gap-2">
                        <input type="date" name="holiday_date[]" class="form-control form-control-sm">
                        <input type="text" name="holiday_name[]" class="form-control form-control-sm" placeholder="Event Name">
                        <button type="button" onclick="this.closest('.holiday-row').remove()" class="btn btn-link text-danger p-0">
                            <i class="fa fa-times-circle"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.getElementById('holidayContainer').insertAdjacentHTML('beforeend', row);
    }

    document.getElementById('slots_per_day').addEventListener('change', function () {
        const count = parseInt(this.value);
        const container = document.getElementById('timingContainer');
        const existingRows = container.querySelectorAll('.slot-timing-row');

        if (count > existingRows.length) {
            for (let i = existingRows.length + 1; i <= count; i++) {
                const row = `
                    <div class="col-md-6 slot-timing-row" data-slot="${i}">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-body d-flex align-items-center gap-3">
                                <span class="badge bg-primary rounded-pill p-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">${i}</span>
                                <div class="flex-grow-1">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Start Time</small>
                                            <input type="time" name="slot_${i}_start" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">End Time</small>
                                            <input type="time" name="slot_${i}_end" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', row);
            }
        } else {
            existingRows.forEach(row => {
                if (parseInt(row.dataset.slot) > count) row.remove();
            });
        }
    });

    async function submitSettings(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);

        try {
            const response = await fetch("{{ route('institution.settings.update') }}", {
                method: "POST",
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': "application/json"
                }
            });

            const result = await response.json();

            if (response.ok) {
                // Assuming you have a toast or alert system like showNotification
                // If not, standard alert
                if (typeof showNotification === 'function') {
                    showNotification(result.msg, result.color, result.icon);
                } else {
                    alert(result.msg);
                }
            } else {
                alert(result.msg || "Something went wrong.");
            }
        } catch (error) {
            console.error(error);
            alert("Connection error.");
        }
    }
</script>

<x-footer />