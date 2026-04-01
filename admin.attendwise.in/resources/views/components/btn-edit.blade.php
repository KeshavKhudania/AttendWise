@if (in_array($route, $allowed_permissions))
    <a href="{{route($route, ["id"=>$id])}}" class="text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
        <button class="btn btn-edit btn-primary"><i class="far fa-edit"></i> Edit</button>
    </a>
@endif