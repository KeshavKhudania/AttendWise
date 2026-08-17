<x-structure />
<x-header heading="{{ $title }}" />

<div class="aw-page-header mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-heading mb-1">{{ $title }}</h1>
            <p class="text-muted small mb-0">Define user roles, access control levels, and granular route permissions for system users.</p>
        </div>
        <div>
            <a href="{{ route('institution.group.manage') }}" class="btn btn-light border">
                <i class="fa fa-arrow-left me-1.5 opacity-75"></i> Back to User Groups
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
                    {{-- User Group Identity --}}
                    <div class="col-md-12">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">User Group & Role Identity</h3>
                                <p class="aw-form-section-subtitle">Name of the role group and operational status</p>
                            </div>
                        </div>
                    </div>

                    {{-- Group Name --}}
                    <div class="col-md-6">
                        <div class="aw-field-group">
                            <label for="name" class="form-label aw-field-label">
                                Group / Role Title <span class="aw-field-required">*</span>
                            </label>
                            <input type="text" class="form-control" name="name" id="name" required value="{{ $fields['name'] }}" placeholder="e.g. Academic Administrator">
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6">
                        <div class="aw-field-group">
                            <label for="status" class="form-label aw-field-label">
                                Status <span class="aw-field-required">*</span>
                            </label>
                            <select name="status" id="status" class="form-control form-select" required>
                                <option value="1">Active</option>
                                <option value="0" {{ $fields['status'] == "0" ? "selected" : "" }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    {{-- Permissions Matrix Section --}}
                    <div class="col-md-12 mt-4">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-key"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">Access Control & Permissions Matrix</h3>
                                <p class="aw-form-section-subtitle">Assign granular Create, Read, Update, and Delete permissions across modules</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="table-responsive rounded border shadow-sm bg-white">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="py-3 px-4 fw-600"><i class="fas fa-cog me-2 text-primary"></i>Module / Feature</th>
                                        <th class="text-center py-3 fw-600"><i class="fas fa-eye me-2 text-info"></i>Read</th>
                                        <th class="text-center py-3 fw-600"><i class="fas fa-plus me-2 text-success"></i>Create</th>
                                        <th class="text-center py-3 fw-600"><i class="fas fa-edit me-2 text-warning"></i>Update</th>
                                        <th class="text-center py-3 fw-600"><i class="fas fa-trash me-2 text-danger"></i>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($all_permissions as $item)
                                    <tr class="bg-light bg-opacity-50">
                                        <td class="px-4 fw-bold text-dark">
                                            <i class="fas {{ $item['icon'] }} me-2 text-muted"></i>
                                            <span>{{ $item['name'] }}</span>
                                            <span class="badge bg-primary ms-2 rounded-pill small">Module Parent</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check d-inline-block">
                                                <input type="checkbox" name="allowed_permissions[]" value="{{ Crypt::encrypt($item['id']) }}"
                                                    {{ in_array($item['route_name'], $fields['permissions']) ? "checked" : "" }}
                                                    class="form-check-input" data-parent="{{ Str::slug($item['name']) }}">
                                            </div>
                                        </td>
                                        <td class="text-center"><small class="text-muted opacity-50">-</small></td>
                                        <td class="text-center"><small class="text-muted opacity-50">-</small></td>
                                        <td class="text-center"><small class="text-muted opacity-50">-</small></td>
                                    </tr>

                                    @foreach (($item['childs'] ?? []) as $child_key => $child_item)
                                    <tr>
                                        <td class="ps-5">
                                            @if(isset($child_item['R'][0]))
                                                <i class="fas {{ $child_item['R'][0]['icon'] }} me-2 text-secondary"></i>
                                            @endif
                                            <span class="fw-500">{{ $child_key }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if(isset($child_item["R"][0]))
                                                <div class="form-check d-inline-block">
                                                    <input {{ in_array($item['route_name'], $fields['permissions']) ? "" : "disabled" }}
                                                        data-parent-rel="{{ Str::slug($item['name']) }}" type="checkbox" name="allowed_permissions[]"
                                                        value="{{ Crypt::encrypt($child_item["R"][0]['id']) }}"
                                                        {{ in_array($child_item["R"][0]['route_name'], $fields['permissions']) ? "checked" : "" }}
                                                        class="form-check-input" data-bs-toggle="collapse"
                                                        data-bs-target="#{{ Str::slug($child_key) }}" aria-expanded="false"
                                                        aria-controls="{{ Str::slug($child_key) }}"
                                                        onchange="toggleSubPermission(this, '{{ Str::slug($child_key) }}')">
                                                </div>
                                            @else
                                                <small class="text-muted">-</small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(isset($child_item["C"][0]))
                                                <div class="form-check d-inline-block">
                                                    <input {{ in_array($item['route_name'], $fields['permissions']) ? "" : "disabled" }}
                                                        data-parent-rel="{{ Str::slug($item['name']) }}" type="checkbox" name="allowed_permissions[]"
                                                        value="{{ Crypt::encrypt($child_item["C"][0]['id']) }}"
                                                        {{ in_array($child_item["C"][0]['route_name'], $fields['permissions']) ? "checked" : "" }}
                                                        class="form-check-input">
                                                </div>
                                            @else
                                                <small class="text-muted">-</small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(isset($child_item["U"][0]))
                                                <div class="form-check d-inline-block">
                                                    <input {{ in_array($item['route_name'], $fields['permissions']) ? "" : "disabled" }}
                                                        data-parent-rel="{{ Str::slug($item['name']) }}" type="checkbox" name="allowed_permissions[]"
                                                        value="{{ Crypt::encrypt($child_item["U"][0]['id']) }}"
                                                        {{ in_array($child_item["U"][0]['route_name'], $fields['permissions']) ? "checked" : "" }}
                                                        class="form-check-input">
                                                </div>
                                            @else
                                                <small class="text-muted">-</small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(isset($child_item["D"][0]))
                                                <div class="form-check d-inline-block">
                                                    <input {{ in_array($item['route_name'], $fields['permissions']) ? "" : "disabled" }}
                                                        data-parent-rel="{{ Str::slug($item['name']) }}" type="checkbox" name="allowed_permissions[]"
                                                        value="{{ Crypt::encrypt($child_item["D"][0]['id']) }}"
                                                        {{ in_array($child_item["D"][0]['route_name'], $fields['permissions']) ? "checked" : "" }}
                                                        class="form-check-input">
                                                </div>
                                            @else
                                                <small class="text-muted">-</small>
                                            @endif
                                        </td>
                                    </tr>

                                    @if (isset($child_item["R"][0]) && ($child_item["R"][0]['children'] ?? false))
                                    <tr class="collapse collapse-row {{ in_array($child_item["R"][0]['route_name'], $fields['permissions']) ? "show" : "" }}" id="{{ Str::slug($child_key) }}">
                                        <td colspan="5" class="bg-light p-3">
                                            <div class="sub-permissions-container ps-4">
                                                <h6 class="mb-2 text-muted fw-bold small"><i class="fas fa-level-up-alt fa-rotate-90 me-2"></i>Granular Sub-Permissions</h6>
                                                <div class="d-flex flex-wrap gap-3">
                                                    @foreach ($child_item["R"][0]['children'] as $sub_child_key => $sub_child_item)
                                                    <div class="sub-permission-item d-flex align-items-center bg-white p-2 rounded border">
                                                        <i class="fas {{ $sub_child_item['icon'] }} me-2 text-primary"></i>
                                                        <span class="me-3 small fw-500">{{ $sub_child_item['name'] }}</span>
                                                        <input {{ in_array($item['route_name'], $fields['permissions']) ? "" : "disabled" }}
                                                            data-parent-rel="{{ Str::slug($item['name']) }}" type="checkbox"
                                                            name="allowed_permissions[]" value="{{ Crypt::encrypt($sub_child_item['id']) }}"
                                                            {{ in_array($sub_child_item['route_name'], $fields['permissions']) ? "checked" : "" }}
                                                            class="form-check-input">
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach

                                    @endforeach
                                </tbody>
                            </table>
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
        $("input[data-parent]").on("change", function () {
            var parentId = $(this).attr("data-parent");
            if ($(this).is(":checked")) {
                $("input[data-parent-rel='" + parentId + "']").prop("disabled", false);
            } else {
                $("#" + parentId).collapse("hide");
                $("input[data-parent-rel='" + parentId + "']").prop("checked", false).prop("disabled", true);
            }
        });
    });

    function toggleSubPermission(checkbox, targetId) {
        var targetEl = document.getElementById(targetId);
        if (!targetEl) return;
        var collapse = bootstrap.Collapse.getInstance(targetEl) || new bootstrap.Collapse(targetEl, { toggle: false });
        if (checkbox.checked) {
            collapse.show();
        } else {
            $("#" + targetId).find("input[type='checkbox']").prop("checked", false);
            collapse.hide();
        }
    }
</script>

<x-footer />