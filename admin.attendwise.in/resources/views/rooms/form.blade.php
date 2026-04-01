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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="room_number">Room Number</label>
                            <input placeholder="Room Number" type="text" class="form-control" name="room_number" id="room_number" required value="{{$fields['room_number']}}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="room_category_id">Room Category </label>
                            <select placeholder="Room Category" name="room_category_id" id="room_category_id" class="form-control form-select" required>
                                <option value="">Select a Room  Category</option>  
                                @foreach ($rooms_category as $items )
                                <option value="{{$items->id}}" {{$fields['room_category_id'] == $items->id ? "selected":""}}>{{$items->name}}</option>    
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="room_status">Room Status </label>
                            <select placeholder="Room Category" name="room_status" id="room_status" class="form-control form-select" required>
                                <option value="">Select Room Status</option>
                              <option value="available"  {{$fields['room_status'] == "available" ? "selected":""}} >Available</option>  
                              <option value="occupied"  {{$fields['room_status'] == "occupied" ? "selected":""}} >Occupied</option>  
                              <option value="maintainance"  {{$fields['room_status'] == "maintainance" ? "selected":""}} >In Maintainance</option>  
                            </select>
                        </div>
                    </div>
                    {{-- <div class="col-md-6">
                        <div class="form-group">
                            <label for="floor">Floor</label>
                            <select placeholder="Room Category" name="floor" id="floor" class="form-control form-select" required>
                              <option value="1"  {{$fields['floor'] == "1" ? "selected":""}} >1st Floor</option>  
                              <option value="occupied"  {{$fields['room_status'] == "occupied" ? "selected":""}} ></option>  
                              <option value="maintainance"  {{$fields['room_status'] == "maintainance" ? "selected":""}} ></option>  
                            </select>
                        </div>
                    </div> --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="floor">Floor</label>
                            <input placeholder="floor" type="number" class="form-control" name="floor" id="floor" value="{{$fields['floor']}}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                           <label for="rate_list">Rate List </label>
                            <select placeholder="Room List" name="rate_list" id="rate_list" class="form-control form-select" required>
                                @foreach ($rate_list as $item)
                                    <option value="{{Crypt::encrypt($item->id)}}" {{$fields['rate_list_id'] == $item->id ?"selected":""}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="price_per_day">Price Per Day</label>
                            <input placeholder="Price Per Day" type="number" min="1" class="form-control" name="price_per_day" id="price_per_day" value="{{$fields['price_per_day']}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" placeholder="Description" class="form-control" id="description" rows="5">{{$fields['description']}}</textarea>
                        </div>
                    </div>
                    {{-- <div class="col-md-3 mt-2"> --}}
                        <x-form-buttons />
                    {{-- </div> --}}
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
<x-footer />