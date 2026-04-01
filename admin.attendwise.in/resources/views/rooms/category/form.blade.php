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
                        <h4 class="border-bottom pb-1 d-inline-block border-2 border-dark">Room Details</h4>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">Category Name</label>
                            <input placeholder="Category Name" type="text" class="form-control" name="name" id="name" required value="{{$fields['name']}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status">Room Status </label>
                            <select placeholder="Room Category" name="status" id="status" class="form-control form-select" required>
                                <option value="">Select Room Status</option>
                              <option value="active"  {{$fields['status'] == "active" ? "selected":""}}>Active</option>  
                              <option value="disabled"  {{$fields['status'] == "disabled" ? "selected":""}} >Dsiabled</option>  
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="rate_list_id">Rate List</label>
                            <select placeholder="Rate List" name="rate_list_id" id="rate_list_id" class="form-control form-select" required>
                                @foreach ($rate_list as $item)
                                    <option value="{{Crypt::encrypt($item->id)}}"  {{$fields['rate_list_id'] == $item->id ? "selected":""}} >{{$item->name}}</option>  
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="price_per_day">Price Per Day</label>
                            <input placeholder="Price Per Day" type="number" class="form-control" name="price_per_day" id="price_per_day" required value="{{$fields['price_per_day']}}" min="1.00">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select placeholder="Type" name="type" id="type" class="form-control form-select" required>
                                <option value="surgical_rooms" {{$fields['type'] == "surgical_rooms"?"selected":""}}>Surgical Rooms</option>
                                <option value="critical_care" {{$fields['type'] == "critical_care"?"selected":""}}>Critical Care</option>
                                <option value="patient_accommodation" {{$fields['type'] == "patient_accommodation"?"selected":""}}>Patient Accommodation</option>
                                <option value="emergency_diagnostics" {{$fields['type'] == "emergency_diagnostics"?"selected":""}}>Emergency & Diagnostics</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" placeholder="Description" class="form-control" id="description" >{{$fields['description']}}</textarea>
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