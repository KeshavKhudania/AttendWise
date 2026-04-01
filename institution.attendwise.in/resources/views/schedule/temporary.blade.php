<x-structure />
<x-header heading="Temporary Timetable Override" />

<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="fw-bold mb-1">Temporary Timetable Override</h4>
            <p class="text-muted mb-0">Generate a special timetable for a specific date (e.g., copying Tuesday's
                schedule to an upcoming Saturday).</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('institution.time.table.manage') }}" class="btn btn-light rounded-pill px-4 shadow-sm">
                <i class="fa fa-arrow-left me-2"></i> Back to Manage
            </a>
        </div>
    </div>

    @if(session('msg'))
    <div class="alert alert-{{ session('color', 'info') }} alert-dismissible fade show rounded-4" role="alert">
        {{ session('msg') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row g-4">
        <!-- Generate Form -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header border-0 bg-white pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-0">Generate Override</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('institution.time.table.manage.temporary.generate') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">Target Date <span class="text-danger">*</span></label>
                            <input type="date" name="target_date" class="form-control bg-light border-0 rounded-3 p-3"
                                required min="{{ date('Y-m-d') }}">
                            <div class="form-text small">Select the exact date you want to apply the special timetable.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Action <span class="text-danger">*</span></label>
                            <div class="d-flex gap-4 p-3 bg-light rounded-3">
                                <div class="form-check custom-radio">
                                    <input class="form-check-input" type="radio" name="action_type" id="actionCopy"
                                        value="copy" checked onchange="toggleReferenceDay()">
                                    <label class="form-check-label fw-semibold" for="actionCopy">
                                        Copy Timetable from Reference Day
                                    </label>
                                </div>
                                <div class="form-check custom-radio text-danger">
                                    <input class="form-check-input" type="radio" name="action_type" id="actionClear"
                                        value="clear" onchange="toggleReferenceDay()">
                                    <label class="form-check-label fw-semibold" for="actionClear">
                                        Clear Special Timetable
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4" id="referenceDayContainer">
                            <label class="form-label fw-bold">Reference Day <span class="text-danger">*</span></label>
                            <select name="reference_day" class="form-select border-0 bg-light rounded-3 p-3" required>
                                <option value="" disabled selected>Select a day to copy from...</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                            <div class="form-text small">The timetable of this selected day will be assigned to the
                                Target Date.</div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold">
                            <i class="fa fa-cogs me-2"></i> Apply Override
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- History / Active List -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header border-0 bg-white pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-0">Active Temporary Schedules</h5>
                </div>
                <div class="card-body p-4">
                    @if($temporaryDates->count() > 0)
                    <div class="list-group list-group-flush border-top-0">
                        @foreach($temporaryDates as $t)
                        <div
                            class="list-group-item d-flex justify-content-between align-items-center py-3 px-0 border-light">
                            <div class="d-flex align-items-center gap-3">
                                <div
                                    class="bg-primary bg-opacity-10 text-primary p-3 rounded-4 d-flex align-items-center justify-content-center">
                                    <i class="fa fa-calendar-star fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold">{{ date('F d, Y', strtotime($t->schedule_date)) }}</h6>
                                    <small class="text-muted">{{ date('l', strtotime($t->schedule_date)) }} Special
                                        Schedule Active</small>
                                </div>
                            </div>
                            <form action="{{ route('institution.time.table.manage.temporary.generate') }}"
                                method="POST">
                                @csrf
                                <input type="hidden" name="target_date" value="{{ $t->schedule_date }}">
                                <input type="hidden" name="action_type" value="clear">
                                <button type="submit"
                                    class="btn btn-light text-danger btn-sm rounded-pill px-3 shadow-sm"
                                    onclick="return confirm('Clear schedule for {{ date('M d, Y', strtotime($t->schedule_date)) }}?')">
                                    <i class="fa fa-trash me-1"></i> Clear
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-5 h-100 d-flex flex-column align-items-center justify-content-center">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3"
                            style="width: 80px; height: 80px;">
                            <i class="fa fa-calendar-check text-muted fs-2"></i>
                        </div>
                        <h6 class="fw-bold text-muted mb-1">No special schedules found</h6>
                        <p class="text-muted small">Special day-specific schedules will appear here.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleReferenceDay() {
        const isCopy = document.getElementById('actionCopy').checked;
        const refContainer = document.getElementById('referenceDayContainer');
        const refSelect = refContainer.querySelector('select');

        if (isCopy) {
            refContainer.style.display = 'block';
            refSelect.setAttribute('required', 'required');
        } else {
            refContainer.style.display = 'none';
            refSelect.removeAttribute('required');
        }
    }
    // Initialize state
    document.addEventListener('DOMContentLoaded', toggleReferenceDay);
</script>

<x-footer />