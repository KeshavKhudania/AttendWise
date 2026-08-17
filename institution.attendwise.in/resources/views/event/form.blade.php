<x-structure />
<x-header heading="{{ $title }}" />

<div class="aw-page-header mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-heading mb-1">{{ $title }}</h1>
            <p class="text-muted small mb-0">Schedule campus events, define venue geofences, and configure participant check-in rules.</p>
        </div>
        <div>
            <a href="{{ route('institution.events.manage') }}" class="btn btn-light border">
                <i class="fa fa-arrow-left me-1.5 opacity-75"></i> Back to Events
            </a>
        </div>
    </div>
</div>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card aw-form-card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ $action }}" method="POST" id="mainForm">
                @csrf
                @if(isset($type) && ($type === 'edit' || $type === 'EDIT'))
                    @method('PUT')
                @endif

                <div class="row g-4">
                    {{-- Basic Info Section --}}
                    <div class="col-md-12">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-calendar-star"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">Basic Event Information</h3>
                                <p class="aw-form-section-subtitle">Event title, classification, and general overview</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Event Name <span class="aw-field-required">*</span></label>
                            <input type="text" name="name" class="form-control" required
                                placeholder="e.g. Annual Tech & Innovation Symposium" value="{{ $event->name ?? '' }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Event Type <span class="aw-field-required">*</span></label>
                            <select name="event_type" class="form-control form-select msc-searchable" required>
                                <option value="Workshop" {{ ($event->event_type ?? '') == 'Workshop' ? 'selected' : '' }}>Workshop</option>
                                <option value="Seminar" {{ ($event->event_type ?? '') == 'Seminar' ? 'selected' : '' }}>Seminar</option>
                                <option value="Concert" {{ ($event->event_type ?? '') == 'Concert' ? 'selected' : '' }}>Concert</option>
                                <option value="Convention" {{ ($event->event_type ?? '') == 'Convention' ? 'selected' : '' }}>Convention</option>
                                <option value="Webinar" {{ ($event->event_type ?? '') == 'Webinar' ? 'selected' : '' }}>Webinar</option>
                                <option value="Other" {{ ($event->event_type ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Event Description</label>
                            <textarea name="description" class="form-control" rows="3"
                                placeholder="Briefly describe the objectives and itinerary of this event...">{{ $event->description ?? '' }}</textarea>
                        </div>
                    </div>

                    {{-- Venue Section --}}
                    <div class="col-md-12 mt-4">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">Venue & Location Assignment</h3>
                                <p class="aw-form-section-subtitle">Select rooms, geofenced zones, or specify custom venue locations</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Filter Room Type</label>
                            <select id="typeSelection" class="form-control form-select msc-searchable">
                                <option value="">-- All Types --</option>
                                @foreach($classroom_types as $type_item)
                                    <option value="{{ $type_item->id }}">{{ $type_item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Filter Block</label>
                            <select name="block_id" id="blockSelection" class="form-control form-select msc-searchable">
                                <option value="">-- Select Block --</option>
                                @foreach($blocks as $block)
                                    <option value="{{ $block->id }}" {{ ($event->block_id ?? '') == $block->id ? 'selected' : '' }}>
                                        {{ $block->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Classrooms / Halls</label>
                            <select name="classroom_ids[]" id="classroomSelection" class="form-control form-select msc-searchable" multiple>
                                @foreach($classrooms as $room)
                                    <option value="{{ $room->id }}" data-block="{{ $room->block_id }}" data-type="{{ $room->type }}"
                                        {{ in_array($room->id, $selected_classrooms ?? []) ? 'selected' : '' }}>
                                        {{ $room->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Geofenced Venues</label>
                            <select name="venue_ids[]" id="venueSelection" class="form-control form-select msc-searchable" multiple>
                                @foreach($all_venues as $venue)
                                    <option value="{{ $venue->id }}" {{ in_array($venue->id, $selected_venues ?? []) ? 'selected' : '' }}>
                                        {{ $venue->name }} ({{ $venue->type }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Additional Venue Details (Manual Note)</label>
                            <input type="text" name="venue_details" class="form-control"
                                placeholder="e.g. Main Campus Auditorium - Gate 3 Entrance" value="{{ $event->venue_details ?? '' }}">
                        </div>
                    </div>

                    {{-- Schedule Section --}}
                    <div class="col-md-12 mt-4">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">Date & Timing Schedule</h3>
                                <p class="aw-form-section-subtitle">Set event date and start/end time windows</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Event Date <span class="aw-field-required">*</span></label>
                            <input type="date" name="event_date" class="form-control" required value="{{ $event->event_date ?? '' }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Start Time <span class="aw-field-required">*</span></label>
                            <input type="time" name="start_time" class="form-control" required
                                value="{{ isset($event->start_time) ? date('H:i', strtotime($event->start_time)) : '' }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">End Time</label>
                            <input type="time" name="end_time" class="form-control"
                                value="{{ isset($event->end_time) ? date('H:i', strtotime($event->end_time)) : '' }}">
                        </div>
                    </div>

                    {{-- Settings --}}
                    <div class="col-md-12 mt-4">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-sliders-h"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">Participation & Status Controls</h3>
                                <p class="aw-form-section-subtitle">Configure student eligibility and activation status</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Event Status</label>
                            <div class="d-flex align-items-center gap-4 mt-2">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="status" id="statusActive" value="1"
                                        {{ ($event->status ?? 1) == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label fw-500" for="statusActive">Active</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="status" id="statusInactive" value="0"
                                        {{ ($event->status ?? 1) == 0 ? 'checked' : '' }}>
                                    <label class="form-check-label text-muted fw-500" for="statusInactive">Inactive</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Open Requirement (Participation)</label>
                            <div class="border rounded p-3 bg-light d-flex align-items-start gap-3">
                                <div class="form-check mt-1">
                                    <input type="checkbox" class="form-check-input" name="is_open" id="isOpenCheck" value="1"
                                        {{ ($event->is_open ?? 0) == 1 ? 'checked' : '' }}>
                                </div>
                                <div>
                                    <label class="form-check-label fw-bold cursor-pointer" for="isOpenCheck">
                                        Open to All Students
                                    </label>
                                    <p class="text-muted small mb-0 mt-1">When checked, any enrolled student can participate and be registered for attendance without prior invitation.</p>
                                </div>
                            </div>
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
        const blockSelect = document.getElementById('blockSelection');
        const typeSelect = document.getElementById('typeSelection');
        const classroomSelect = document.getElementById('classroomSelection');
        if (!blockSelect || !typeSelect || !classroomSelect) return;

        const originalOptions = Array.from(classroomSelect.options);

        function filterClassrooms() {
            const blockId = blockSelect.value;
            const typeId = typeSelect.value;
            const currentValue = classroomSelect.value;

            // Clear and re-filter
            classroomSelect.innerHTML = '';
            originalOptions.forEach(option => {
                const matchesBlock = !blockId || option.value === '' || option.getAttribute('data-block') === blockId;
                const matchesType = !typeId || option.value === '' || option.getAttribute('data-type') === typeId;

                if (matchesBlock && matchesType) {
                    classroomSelect.appendChild(option.cloneNode(true));
                }
            });

            // Restore value if possible
            classroomSelect.value = currentValue;

            // Refresh custom searchable UI (main.js)
            if (typeof jQuery !== 'undefined') {
                const $select = jQuery(classroomSelect);
                const $wrapper = $select.next('.search-wrapper');
                if ($wrapper.length) {
                    const $dropdown = $wrapper.find('.search-dropdown');
                    $dropdown.empty();
                    $select.find('option').each(function () {
                        const val = jQuery(this).val();
                        const text = jQuery(this).text();
                        if (val !== "") {
                            $dropdown.append(`<div data-value="${val}">${text}</div>`);
                        }
                    });
                    $select.trigger('change');
                }
            }
        }

        blockSelect.addEventListener('change', filterClassrooms);
        typeSelect.addEventListener('change', filterClassrooms);

        if (blockSelect.value || typeSelect.value) {
            filterClassrooms();
        }
    });
</script>

<x-footer />