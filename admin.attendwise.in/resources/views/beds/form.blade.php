<x-structure />
<x-header />
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">{{$title}}</h4>
        <form action="{{$action}}" method="POST" id="mainForm" data-form-type="{{$type}}">
            @php
            function ordinal($number) {
                $suffix = 'th';
                if (!in_array(($number % 100), [11, 12, 13])) {
                    switch ($number % 10) {
                        case 1:
                            $suffix = 'st';
                            break;
                        case 2:
                            $suffix = 'nd';
                            break;
                        case 3:
                            $suffix = 'rd';
                            break;
                    }
                }
                return $number . $suffix;
            }
            @endphp
            
            @csrf
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <h4 class="border-bottom pb-1 d-inline-block border-2 border-dark">Bed Details</h4>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="room_id">Room Number</label>
                            <select name="room_id" id="room_id" class="form-control form-select" required>
                                <option value="">Select Room</option>
                                @foreach ($rooms as $items )
                                    <option class="form-control" value="{{$items->id}}" {{$fields['room_id'] == $items->id ? "selected" : " " }}>{{$items->floor ? ordinal($items->floor) : 'No Floor'}} Floor || <b> Room Number {{$items->room_number}}</b> || {{App\Models\RoomCategory::find($items->room_category_id)->name}}</option>
                                @endforeach
                            </select>   
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="bed_category_id">Bed Category</label>
                            <select name="bed_category_id" id="bed_category_id" class="form-control form-select" required>
                                <option value="">Select Bed Category</option>
                                @foreach ($beds_category as $items )
                                    <option class="form-control" title="{{$items->description}}" {{$fields['bed_category_id'] == $items->id ? "selected" : " "}} value="{{$items->id}}">{{$items->category_name}}</option>
                                @endforeach
                            </select>   
                        </div>
                    </div>

                    {{-- <div class="col-md-3">
                        <div class="form-group">
                            <label for="patient_id">Patient</label>
                            <select name="patient_id" id="patient_id" class="form-control form-select" required>
                                <option value="">Select Patient</option>
                                @foreach ($patient as $items )
                                    <option class="form-control" title="{{$items->first_name}} {{$items->last_name}}" value="{{$items->id}}">{{$items->first_name}} || {{$items->uhid}}</option>
                                @endforeach
                            </select>   
                        </div>
                    </div> --}}

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="bed_number">Bed Number</label>
                            <input placeholder="Bed Number" type="text" class="form-control" name="bed_number" id="bed_number" required value="{{$fields['bed_number']}}">
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="bed_status">Bed Status </label>
                            <select placeholder="Room Category" name="bed_status" id="bed_status" class="form-control form-select" required>
                                <option value="">Select Bed Status</option>
                              <option value="available"  {{$fields['bed_status'] == "available" ? "selected":""}} >Available</option>  
                              <option value="occupied"  {{$fields['bed_status'] == "occupied" ? "selected":""}} >Occupied</option>  
                              <option value="maintainence"  {{$fields['bed_status'] == "maintainence" ? "selected":""}} >Under Maintainence</option>  
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" placeholder="Description" class="form-control" id="description" cols="20" rows="20">{{$fields['description']}}</textarea>
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