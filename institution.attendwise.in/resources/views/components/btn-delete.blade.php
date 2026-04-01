@if (in_array($route, $allowed_permissions))
    <a href="{{route($route, ["id"=>$id])}}" data-link="{{route($route, ["id"=>$id])}}" class="MscDeleteRowBtn text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
        <button class="btn btn-delete btn btn-danger"><i class="fas fa-trash-alt"></i> Delete</button>
    </a>
@endif