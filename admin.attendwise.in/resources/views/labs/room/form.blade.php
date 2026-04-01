<x-structure />
<x-header />
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">{{$title}}</h4>
        <form action="{{$action}}" method="POST" id="mainForm" data-form-type="{{$type}}">
            @csrf
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <h4 class="border-bottom pb-1 d-inline-block border-2 border-dark">Laboratory Details</h4>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">Laboratory Room Name</label>
                            <input placeholder="Name" type="text" class="form-control" name="name" id="name" required value="{{$fields['name']}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="floor">Floor</label>
                            <input placeholder="Floor" type="text" class="form-control" name="floor" id="floor" required value="{{$fields['floor']}}" min="0">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="contact_number">Lab Contact Number</label>
                            <input placeholder="Lab Contact Number" type="text" class="form-control" name="contact_number" id="contact_number" value="{{$fields['contact_number']}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email">Lab Contact Email</label>
                            <input placeholder="Lab Contact Email" type="email" class="form-control" name="email" id="email" value="{{$fields['email']}}">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lab_id">Select Lab</label>
                            <select placeholder="Laboratory" name="lab_id" id="lab_id" class="form-control form-select" required>
                              @foreach ($labs as $item)
                                  <option value="{{Crypt::encrypt($item->id)}}" {{$fields['lab_id'] == $item->id ? "selected":""}}>{{$item->lab_name}}</option>
                              @endforeach  
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status">Laboratory Status </label>
                            <select placeholder="Laboratory" name="status" id="status" class="form-control form-select" required>
                              <option value="active"  {{$fields['status'] == "active" ? "selected":""}}>Active</option>  
                              <option value="disabled"  {{$fields['status'] == "disabled" ? "selected":""}} >Dsiabled</option>  
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