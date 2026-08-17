<x-structure />
<x-header heading="{{ $title }}" />

<div class="aw-page-header mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-heading mb-1">{{ $title }}</h1>
            <p class="text-muted small mb-0">Configure academic or administrative department settings and flags.</p>
        </div>
        <div>
            <a href="{{ route('institution.department.manage') }}" class="btn btn-light border">
                <i class="fa fa-arrow-left me-1.5 opacity-75"></i> Back to Departments
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
                    <div class="col-md-12">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-sitemap"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">Department Details & Classification</h3>
                                <p class="aw-form-section-subtitle">Name, operational status, and auxiliary type declaration</p>
                            </div>
                        </div>
                    </div>

                    {{-- Name --}}
                    <div class="col-md-6">
                        <div class="aw-field-group">
                            <label for="name" class="form-label aw-field-label">
                                Department Name <span class="aw-field-required">*</span>
                            </label>
                            <input
                                placeholder="e.g. Department of Computer Science & Engineering"
                                type="text"
                                class="form-control"
                                name="name"
                                id="name"
                                required
                                value="{{ $fields['name'] }}"
                            >
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6">
                        <div class="aw-field-group">
                            <label for="status" class="form-label aw-field-label">
                                Operational Status <span class="aw-field-required">*</span>
                            </label>
                            <select name="status" id="status" class="form-control form-select" required>
                                <option value="1">Active</option>
                                <option value="0" {{ $fields['status'] == "0" ? "selected" : "" }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    {{-- Additional Department Checkbox --}}
                    <div class="col-md-12">
                        <div class="form-check form-check-primary p-3 rounded bg-light border">
                            <label class="form-check-label fw-600 cursor-pointer">
                                <input
                                    type="checkbox"
                                    class="form-check-input me-2"
                                    name="is_additional"
                                    value="1"
                                    {{ $fields['is_additional'] == "1" ? "checked" : "" }}
                                >
                                Additional / Auxiliary Department
                            </label>
                            <span class="aw-field-help ms-4">Check this option if this department serves as an inter-disciplinary or non-degree unit.</span>
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

<x-footer />