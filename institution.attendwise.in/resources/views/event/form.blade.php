<x-structure />
<x-header heading="{{ $title }}" />

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ $action }}" method="POST" id="mainForm">
                @csrf
                <div class="row g-4">
                    {{-- Basic Info Section --}}
                    <div class="col-md-12">
                        <h5 class="card-description fw-bold text-primary mb-3">
                            <i class="fa fa-info-circle me-2"></i> Basic Information
                        </h5>
                        <hr>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-600">Event Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control msc-form-control" required
                            placeholder="e.g. Annual Tech Symposium" value="{{ $event->name ?? '' }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-600">Event Type <span class="text-danger">*</span></label>
                        <select name="event_type" class="form-control form-select msc-searchable" required>
                            <option value="Workshop" {{ ($event->event_type ?? '') == 'Workshop' ? 'selected' : ''
                                }}>Workshop</option>
                            <option value="Seminar" {{ ($event->event_type ?? '') == 'Seminar' ? 'selected' : ''
                                }}>Seminar</option>
                            <option value="Concert" {{ ($event->event_type ?? '') == 'Concert' ? 'selected' : ''
                                }}>Concert</option>
                            <option value="Convention" {{ ($event->event_type ?? '') == 'Convention' ? 'selected' : ''
                                }}>Convention</option>
                            <option value="Webinar" {{ ($event->event_type ?? '') == 'Webinar' ? 'selected' : ''
                                }}>Webinar</option>
                            <option value="Other" {{ ($event->event_type ?? '') == 'Other' ? 'selected' : '' }}>Other
                            </option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-600">Description</label>
                        <textarea name="description" class="form-control msc-form-control" rows="3"
                            placeholder="Briefly describe the purpose of this event...">{{ $event->description ?? '' }}</textarea>
                    </div>

                    {{-- Venue Section --}}
                    <div class="col-md-12 mt-4">
                        <h5 class="card-description fw-bold text-primary mb-3">
                            <i class="fa fa-map-marker-alt me-2"></i> Venue Information
                        </h5>
                        <hr>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-600">Filter by Room Type</label>
                        <select id="typeSelection" class="form-control form-select msc-searchable">
                            <option value="">-- All Types --</option>
                            @foreach($classroom_types as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-600">Block</label>
                        <select name="block_id" id="blockSelection" class="form-control form-select msc-searchable">
                            <option value="">-- Select Block --</option>
                            @foreach($blocks as $block)
                            <option value="{{ $block->id }}" {{ ($event->block_id ?? '') == $block->id ? 'selected' : ''
                                }}>{{ $block->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-600">Classroom(s) / Hall(s)</label>
                        <select name="classroom_ids[]" id="classroomSelection"
                            class="form-control form-select msc-searchable" multiple>
                            @foreach($classrooms as $room)
                            <option value="{{ $room->id }}" data-block="{{ $room->block_id }}"
                                data-type="{{ $room->type }}" {{ in_array($room->id, $selected_classrooms ?? []) ?
                                'selected' : '' }}>
                                {{ $room->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-600">Ground/Hostel/Other (Geofenced)</label>
                        <select name="venue_ids[]" id="venueSelection" class="form-control form-select msc-searchable"
                            multiple>
                            @foreach($all_venues as $venue)
                            <option value="{{ $venue->id }}" {{ in_array($venue->id, $selected_venues ?? []) ?
                                'selected' : '' }}>
                                {{ $venue->name }} ({{ $venue->type }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-600">Other Details (Manual)</label>
                        <input type="text" name="venue_details" class="form-control msc-form-control"
                            placeholder="e.g. Main Auditorium" value="{{ $event->venue_details ?? '' }}">
                    </div>

                    {{-- Schedule Section --}}
                    <div class="col-md-12 mt-4">
                        <h5 class="card-description fw-bold text-primary mb-3">
                            <i class="fa fa-calendar-alt me-2"></i> Date & Timing
                        </h5>
                        <hr>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-600">Event Date <span class="text-danger">*</span></label>
                        <input type="date" name="event_date" class="form-control msc-form-control" required
                            value="{{ $event->event_date ?? '' }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-600">Start Time <span class="text-danger">*</span></label>
                        <input type="time" name="start_time" class="form-control msc-form-control" required
                            value="{{ isset($event->start_time) ? date('H:i', strtotime($event->start_time)) : '' }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-600">End Time</label>
                        <input type="time" name="end_time" class="form-control msc-form-control"
                            value="{{ isset($event->end_time) ? date('H:i', strtotime($event->end_time)) : '' }}">
                    </div>

                    {{-- Settings --}}
                    <div class="col-md-12 mt-4">
                        <h5 class="card-description fw-bold text-primary mb-3">
                            <i class="fa fa-cog me-2"></i> Settings
                        </h5>
                        <hr>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-600">Event Status</label>
                        <div class="d-flex align-items-center mt-2">
                            <div class="form-check form-check-primary me-4">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="status" value="1" {{
                                        ($event->status ?? 1) == 1 ? 'checked' : '' }}>
                                    Active
                                    <i class="input-helper"></i>
                                </label>
                            </div>
                            <div class="form-check form-check-danger">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="status" value="0" {{
                                        ($event->status ?? 1) == 0 ? 'checked' : '' }}>
                                    Inactive
                                    <i class="input-helper"></i>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-600">Open Requirement (Eligibility)</label>
                        <div class="form-check form-check-success mt-2 p-3 bg-light rounded-3">
                            <label class="form-check-label fw-bold">
                                <input type="checkbox" class="form-check-input" name="is_open" value="1" {{
                                    ($event->is_open ?? 0) == 1 ? 'checked' : '' }}>
                                Open for all Students?
                                <i class="input-helper"></i>
                            </label>
                            <div class="form-text small opacity-75">If enabled, any student in the institution can
                                participate and be marked present without pre-recruitment.</div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-5">
                        <div class="d-flex justify-content-end p-3 bg-light rounded shadow-xs">
                            <a href="{{ route('institution.events.manage') }}"
                                class="btn btn-light me-2 border">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold">
                                <i class="fa fa-save me-2"></i> {{ $type == 'ADD' ? 'Create Event' : 'Save Changes' }}
                            </button>
                        </div>
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

        // Initer if values are present (e.g. on EDIT or after page reload)
        if (blockSelect.value || typeSelect.value) {
            filterClassrooms();
        }
    });
</script>

<x-footer />