<x-structure />
<x-header heading="{{$title}}"/>
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <form action="{{$action}}" method="POST" id="mainForm" data-form-type="{{$type}}">
            @csrf
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <h4 class="border-bottom pb-1 d-inline-block border-2 border-dark">Personal Information</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input placeholder="Name" type="text" class="form-control" name="name" id="name" required value="{{$fields['name']}}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="code">Code</label>
                            <input placeholder="Code" type="text" class="form-control" name="code" id="code" required value="{{$fields['code']}}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="rate">Rate</label>
                            <input placeholder="Rate" type="number" class="form-control" name="rate" id="rate" required value="{{$fields['rate']}}" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="department_id">Department</label>
                            <select placeholder="Department" name="department_id" id="department_id" class="form-control form-select" required>
                                @foreach ($departments as $item)
                                    <option value="{{Crypt::encrypt($item->id)}}" {{$fields['department_id'] == $item->id ? "selected":""}}>{{$item->name}}</option>  
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="lab_id">Lab</label>
                            <select placeholder="Lab" name="lab_id" id="lab_id" class="form-control form-select" required>
                                @foreach ($labs as $item)
                                    <option value="{{Crypt::encrypt($item->id)}}" {{$fields['lab_id'] == $item->id ? "selected":""}}>{{$item->lab_name}}</option>  
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="rate_list_id">Rate List</label>
                            <select placeholder="Rate List" name="rate_list_id" id="rate_list_id" class="form-control form-select" required>
                                @foreach ($rate_list as $item)
                                    <option value="{{Crypt::encrypt($item->id)}}" {{$fields['rate_list_id'] == $item->id ? "selected":""}}>{{$item->name}}</option>  
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select placeholder="Status" name="status" id="status" class="form-control form-select" required>
                              <option value="1">Active</option>  
                              <option value="0" {{$fields['status'] == "0" ? "selected":""}}>Inactive</option>  
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="sample_required">Sample Required</label>
                            <select placeholder="Sample Required" name="sample_required" id="sample_required" class="form-control form-select" required>
                                <option value="0" {{$fields['sample_required'] == "0" ? "selected":""}}>No</option>  
                              <option value="1">Yes</option>  
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" placeholder="Description" class="form-control" name="description" id="description" value="{{$fields['description']}}">
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