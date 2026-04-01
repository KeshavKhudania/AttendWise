<x-structure />
<x-header heading="{{$title}}" />
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="text-end mb-2"><x-btn-add route="hospit.beds.add.view" /></h4>
        <div class="table-responsive">
          <table class="table table-hover msc-smart-table">
            <thead>
                <tr>
                    <th>Bed Number</th>
                    <th>Room Details</th>
                    <th>Room Category</th>
                    <th>Bed Category</th>
                    <th>Bed Number</th>
                    <th>Bed Status</th>
                    <th>Description</th>
                    {{-- <th>Assigned Date</th> --}}
                    {{-- <th>Released  Date</th> --}}
                    {{-- <th>Floor</th> --}}
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

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
                @foreach ($beds as $item)
                    <tr>
                      {{-- @php
                        echo "<pre>";
                          print_r($item);
                          die();
                      @endphp --}}
                        <td>{{$item->bed_number}}</td>
<td>{{$item->room->floor ? ordinal($item->room->floor) : 'No Floor'}} Floor || Room Number  <b> {{$item->room->room_number}}</b></td>
<td>{{App\Models\RoomCategory::find($item->room->room_category_id)->name}}</td>
<td>{{$item->bedcategory->category_name}}</td>
<td>{{$item->bed_number}}</td>
                        <td> @if ($item->bed_status == "available")
                            <span class="badge bg-success" >Available</span>
                            @elseif ($item->bed_status == "occupied")    
                            <span class="badge bg-warning" >Occupied</span>
                            @elseif ($item->bed_status == "maintainence")
                            <span class="badge bg-danger" >Under Mainteinence</span>
                            @else
                            <span class="badge bg-warning">N/A</span>
                        @endif </td>
                        <td>{{$item->description}}</td>
                        <td>
                            <x-btn-edit id="{{Crypt::encrypt($item->id)}}" route="hospit.beds.edit.view"/>
                            <x-btn-delete id="{{Crypt::encrypt($item->id)}}" route="hospit.beds.delete"/>
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