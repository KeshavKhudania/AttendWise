<x-structure />
<x-header heading="{{ $title }}" />

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">

            <form action="{{ $action }}" method="POST" class="msc-ord-form">
                @csrf
                @if($type === 'EDIT')
                {{-- Normally we would use \@method('PUT') but your controllers handle updating via POST to an update
                endpoint --}}
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">
                            Club Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. E Cell"
                            value="{{ old('name', $club->name ?? '') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select name="status" class="form-select form-control" required>
                            <option value="1" {{ old('status', $club->status ?? 1) == 1 ? 'selected' : '' }}>Active
                            </option>
                            <option value="0" {{ old('status', $club->status ?? '') === 0 ? 'selected' : '' }}>Inactive
                            </option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4"
                            placeholder="Brief details about the club...">{{ old('description', $club->description ?? '') }}</textarea>
                    </div>

                    <div class="col-md-12 mt-3">
                        <x-form-buttons />
                        <a href="{{ route('institution.club.manage') }}" class="btn btn-light ms-2">Cancel</a>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<x-footer />