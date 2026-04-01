<x-structure />
<x-header heading="{{$title}}"/>
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card" style="background-color:#fff !important;">
      <div class="card-body table-card-body">

        <div class="text-end mb-2">
          {{-- <x-btn-add route="hospit.ipd.add.view" /> --}}
        </div>
        <div class="table-responsive">
          <table class="table table-hover table-bordered msc-smart-table">
            <thead>
                <tr>
                    {{-- <th>Queue No.</th> --}}
                    <th>IPD No.</th>
                    <th>UHID</th>
                    <th>Patient Name</th>
                    <th>ABHA No.</th>
                    <th>Doctor (Department)</th>
                    <th>Arrival</th>
                    <th>Status</th>
                    <th>Room Allotment</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $item)
                    <tr>
                        <td>{{$item->ipd_number}}</td>
                        <td><span class="badge bg-warning">{{$item->patient->uhid}}</span></td>
                        <td>{{$item->patient->first_name." ".$item->patient->last_name}}</td>
                        <td>{{$item->patient->abha_id ?? "--"}}</td>
                        <td>Dr.{{$item->doctor->name}} ({{$item->department->name}})</td>
                        <td class="">{{$item->arrival_date}} {{$item->arrival_time}}</td>
                        <td>
                            <span class="badge bg-warning">
                                {{$item->ipd_status}}
                            </span>
                        </td>
                        <td>
                            Room: {{$item->room->room_number}} ({{$item->room_category->name}}) <br>
                            <br>
                            Bed: {{$item->bed->bed_number}} ({{$item->bed_category->category_name}}) <br>
                        </td>
                        <td>
                          @if (in_array("hospit.ipd.manage", $allowed_permissions))
                            <a href="staff-portal/ipd/view/{{ Crypt::encrypt($item->id) }}" class="MscTaskBtn text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                              <button class="btn text-hover-primary"><i class="fas fa-eye"></i></button>
                            </a>
                            @endif
                          <a href="staff-portal/ipd/show/{{ Crypt::encrypt($item->id) }}" class="text-decoration-none" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Print">
                            <button class="btn text-hover-primary"><i class="fas fa-print"></i></button>
                          </a>
                          @if (in_array("hospit.ipd.update", $allowed_permissions))
                              <a href="staff-portal/ipd/regenrate-queue-number/{{ Crypt::encrypt($item->id) }}" class="MscTaskBtn text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="Regenerate Queue No.">
                                  <button class="btn btn-edit text-hover-success"><i class="mdi mdi-refresh"></i></button>
                              </a>
                          @endif
                          @if ($item->status != "Cancelled")
                          <x-btn-edit id="{{Crypt::encrypt($item->id)}}" route="hospit.ipd.edit.view"/>
                          @endif
                            
                            @if (in_array("hospit.ipd.create", $allowed_permissions))
                            <a href="staff-portal/ipd/convert-staff-portal/ipd/{{ Crypt::encrypt($item->id) }}" class="MscTaskBtn text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="Convert to IPD">
                              <button class="btn text-hover-purple"><i class="mdi mdi-bed-empty"></i></button>
                            </a>
                            @endif
                            <a href="staff-portal/ipd/view/{{ Crypt::encrypt($item->id) }}" class="text-decoration-none btnViewRow" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                              <button class="btn text-hover-warning"><i class="mdi mdi-eye"></i></button>
                            </a>
                            <a href="test/assign/{{ Crypt::encrypt($item->id) }}" class="text-decoration-none text-dark" data-bs-toggle="tooltip" data-bs-placement="top" title="Assign Test"> <i class="mdi mdi-test-tube"></i> </a>
                            <x-btn-delete id="{{Crypt::encrypt($item->id)}}" route="hospit.ipd.delete"/>
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