<x-structure />
<x-header heading="{{$title}}" />
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <form action="{{$action}}" method="POST" id="mainForm" data-form-type="{{$type}}">
            @csrf
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" id="name" required value="{{$fields['name']}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" id="email" required value="{{$fields['email']}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mobile">Mobile</label>
                            <input type="number" class="form-control" name="mobile" id="mobile" required value="{{$fields['mobile']}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" id="password" required value="{{$fields['password']}}">
                        </div>
                    </div>
                    @if (in_array("hospit.users.group.manage", $permissions))
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="group_id">User Group</label>
                            <select name="group_id" id="group_id" class="form-control form-select">
                                @foreach ($all_groups as $item)
                                    <option value="{{Crypt::encrypt($item->id)}}" {{$fields['group_id'] == $item->id ? "selected":""}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control form-select" required>
                              <option value="1">Active</option>  
                              <option value="0" {{$fields['status'] == "0" ? "selected":""}}>Inactive</option>  
                            </select>
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