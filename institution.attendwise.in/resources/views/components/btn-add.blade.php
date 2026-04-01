@if (in_array($route, $allowed_permissions))
@if ($slot != "")
    {{ $slot }}
@else
    <a href="{{route($route)}}" class="btn btn-warning-outlined fw-bold">
        <i class="fas fa-plus me-1"></i> Create
    </a>
@endif
    
@endif
