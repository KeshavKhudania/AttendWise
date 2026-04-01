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
                        <h4 class="border-bottom pb-1 d-inline-block border-2 border-dark">Lab Information</h4>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="lab_name">Lab Name</label>
                            <input placeholder="Lab Name" type="text" class="form-control" name="lab_name" id="lab_name" required value="{{$fields['lab_name']}}">
                        </div>
                    </div>
                     <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select placeholder="Status" name="status" id="status" class="form-control form-select" required>
                              <option value="active">Active</option>  
                              <option value="incative" {{$fields['status'] == "inactive" ? "selected":""}}>Inactive</option>  
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