@if (in_array($route, $allowed_permissions))
    <a href="{{route($route)}}" class="btn btn-warning-outlined fw-bold">
        <i class="fas fa-plus me-1"></i> Create
    </a>
@endif
