<x-structure />
<x-header heading="{{$title}}"/>
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
                            <input placeholder="Name" type="text" class="form-control" name="name" id="name" required value="{{$fields['name']}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select placeholder="Status" name="status" id="status" class="form-control form-select" required>
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