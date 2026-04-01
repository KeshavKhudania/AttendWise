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
                            <label for="patient_id">Patient</label>
                            <select name="patient_id" id="patient_id" class="form-control form-select" required>
                                <option value="">Select patient</option>
                                @foreach ($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ ((isset($fields['patient_id']) && $fields['patient_id'] == $patient->id) ? 'selected' : '') }}>
                                        {{ $patient->first_name }} {{ $patient->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="room_id">Room Number</label>
                            <input placeholder="Room Number" type="text" class="form-control" name="room_id" id="room_id" required value="{{$fields['room_id']}}">
                        </div>
                    </div>
                    {{-- <div class="col-md-3">
                        <div class="form-group">
                            <label for="room_category_id">Room Category </label>
                            <select placeholder="Room Category" name="room_category_id" id="room_category_id" class="form-control form-select" required>
                                <option value="">Select a Room  Category</option>  
                                @foreach ($rooms_category as $items )
                                <option value="{{$items->id}}">{{$items->name}}</option>    
                                @endforeach
                            </select>
                        </div>
                    </div> --}}

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="assignment_status">Assignment Status </label>
                            <select placeholder="Room Category" name="assignment_status" id="assignment_status" class="form-control form-select" required>
                                <option value="">Select Room Status</option>
                              <option value="Released"  {{$fields['assignment_status'] == "Released" ? "selected":""}} >Released</option>  
                              <option value="occupied"  {{$fields['assignment_status'] == "Occupied" ? "selected":""}} >Occupied</option>  
                              {{-- <option value="maintainance"  {{$fields['room_status'] == "maintainance" ? "selected":""}} >In Maintainance</option>   --}}
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
                    {{-- @php
                        echo "<pre>";
                            print_r($fields['assigned_date']);
                            die();
                    @endphp --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="assigned_date">Assigned Date</label>
                            <input placeholder="Assigned Date" type="datetime-local" class="form-control" name="assigned_date" id="assigned_date" value="{{$fields['assigned_date']}}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="release_date">Released Date</label>
                            <input placeholder="Release Date" type="datetime-local" class="form-control" name="release_date" id="release_date" value="{{$fields['release_date']}}">
                        </div>
                    </div>
                    {{-- <div class="col-md-3">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" placeholder="Description" class="form-control" id="description" cols="20" rows="20">{{$fields['description']}}</textarea>
                        </div>
                    </div> --}}
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