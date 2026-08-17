<x-structure />
<x-header heading="{{ $title }}" />

<div class="aw-page-header mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-heading mb-1">{{ $title }}</h1>
            <p class="text-muted small mb-0">Register student clubs, cultural societies, and extracurricular organization details.</p>
        </div>
        <div>
            <a href="{{ route('institution.club.manage') }}" class="btn btn-light border">
                <i class="fa fa-arrow-left me-1.5 opacity-75"></i> Back to Clubs
            </a>
        </div>
    </div>
</div>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card aw-form-card shadow-sm border-0">
        <div class="card-body">

            <form action="{{ $action }}" method="POST" class="msc-ord-form" id="mainForm">
                @csrf
                @if(isset($type) && ($type === 'edit' || $type === 'EDIT'))
                    @method('PUT')
                @endif

                <div class="row g-4">
                    {{-- Club Info Section --}}
                    <div class="col-md-12">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">Club & Society Details</h3>
                                <p class="aw-form-section-subtitle">Name, active status, and organizational summary</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">
                                Club Name <span class="aw-field-required">*</span>
                            </label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g. Entrepreneurship Cell (E-Cell)"
                                value="{{ old('name', $club->name ?? '') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">
                                Operational Status <span class="aw-field-required">*</span>
                            </label>
                            <select name="status" class="form-select form-control" required>
                                <option value="1" {{ old('status', $club->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', $club->status ?? '') === 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="aw-field-group">
                            <label class="form-label aw-field-label">Description & Activities</label>
                            <textarea name="description" class="form-control" rows="4"
                                placeholder="Provide brief details about the club's mission, activities, and faculty heads...">{{ old('description', $club->description ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <x-form-buttons />
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<x-footer />