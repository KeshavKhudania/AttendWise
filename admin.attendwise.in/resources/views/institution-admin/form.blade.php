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
                            <input type="text" placeholder="Name" class="form-control" name="name" id="name" required value="{{$fields['name']}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" placeholder="Email" class="form-control" name="email" id="email" required value="{{$fields['email']}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mobile">Mobile</label>
                            <input type="number" placeholder="Mobile" class="form-control" name="mobile" id="mobile" required value="{{$fields['mobile']}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" placeholder="Password" class="form-control" name="password" id="password" required value="{{$fields['password']}}">
                        </div>
                    </div>
                    {{-- @if (in_array("admin.institution.admin.group.manage", $permissions)) --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="institution_id">Institution</label>
                            <select name="institution_id" id="institution_id" onchange="fetchRelatedList({
                                            'data': {
                                            'institution_id': this.value,
                                            },
                                            'target': '#group_id',
                                            'url': '{{ route('api.fetch.institution.groups') }}'
                                        })" class="form-control form-select">
                                @foreach ($institution_list as $item)
                                    <option value="{{Crypt::encryptString($item->id)}}" {{$fields['institution_id'] == $item->id ? "selected":""}}>{{$item->legal_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="group_id">User Group</label>
                            <select name="group_id" id="group_id" class="form-control form-select">
                                @foreach ($group_list as $item)
                                    <option value="{{Crypt::encryptString($item->id)}}" {{$fields['group_id'] == $item->id ? "selected":""}}>{{$item->name}}</option>
                                @endforeach
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