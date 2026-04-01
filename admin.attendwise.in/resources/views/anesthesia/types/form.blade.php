<x-structure />
<x-header heading="{{ $title }}" />

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <form action="{{ $action }}" method="POST" id="mainForm" data-form-type="{{ $type }}">
                @csrf
                <div class="container-fluid">
                    <div class="row">
                        <!-- Name -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="name"
                                    id="name"
                                    placeholder="Name"
                                    required
                                    value="{{ $fields['name'] ?? '' }}">
                            </div>
                        </div>
                        <!-- Status (Active/Inactive) -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control form-select">
                                    <option value="1" {{ (isset($fields['status']) && $fields['status'] == "1") ? "selected" : "" }}>Active</option>
                                    <option value="0" {{ (isset($fields['status']) && $fields['status'] == "0") ? "selected" : "" }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <!-- Description -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">Description (optional)</label>
                                <textarea
                                    name="description"
                                    id="description"
                                    class="form-control"
                                    placeholder="Write a short description...">{{ $fields['description'] ?? '' }}</textarea>
                            </div>
                        </div>
                        <x-form-buttons />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<x-footer />
