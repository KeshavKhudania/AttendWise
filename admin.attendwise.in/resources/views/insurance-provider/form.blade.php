<x-structure />
<x-header heading="{{$title}}"/>
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <form action="{{$action}}" method="POST" id="mainForm" data-form-type="{{$type}}">
            @csrf
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input placeholder="Name" type="text" class="form-control" name="name" id="name" required value="{{$fields['name']}}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="contact_email">Contact Email</label>
                            <input placeholder="Contact Email" type="email" class="form-control" name="contact_email" id="contact_email" required value="{{$fields['contact_email']}}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="contact_phone">Contact Phone</label>
                            <input placeholder="Contact Phone" type="number" class="form-control" name="contact_phone" id="contact_phone" required value="{{$fields['contact_phone']}}" required>
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="website">Website</label>
                            <input placeholder="Website" type="url" class="form-control" name="website" id="website" value="{{$fields['website']}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" placeholder="Address" class="form-control" name="address" id="address" value="{{$fields['address']}}">
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