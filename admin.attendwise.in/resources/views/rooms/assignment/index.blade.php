<x-structure />
<x-header heading="{{$title}}" />
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="text-end mb-2"><x-btn-add route="hospit.room.assignment.add.view" /></h4>
        <div class="table-responsive">
          <table class="table table-hover msc-smart-table">
            <thead>
                <tr>
                    <th>Room Number</th>
                    <th>Patient Name</th>
                    <th>Admission Details</th>
                    <th>Assigned Date</th>
                    <th>Release Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rooms_assigned as $item)
                    <tr>
                        <td>{{$item->room_id}}</td>
                        <td>{{$item->patients->first_name}}</td>
                        <td>{{$item->admission_id}}</td>
                        <td>{{$item->assigned_date}}</td>
                        <td>{{$item->release_date}}</td>
                        <td>@if ($item->assignment_status == "Occupied")
                          <span class="badge bg-danger" >Occupied</span>
                          @elseif ($item->assignment_status == "Released")
                          <span class="badge bg-success" >Released</span>
                          @else
                          <span class="badge bg-warning" >N/A</span>
                        @endif</td>
                        <td>
                            <x-btn-edit id="{{Crypt::encrypt($item->id)}}" route="hospit.room.assignment.edit.view"/>
                            <x-btn-delete id="{{Crypt::encrypt($item->id)}}" route="hospit.room.assignment.delete"/>
                        </td>
                    </tr>
                @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<x-footer />
