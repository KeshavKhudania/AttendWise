<x-structure />
<x-header heading="{{$title}}" />
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="text-end mb-2"><x-btn-add route="hospit.room.add.view" /></h4>
        <div class="table-responsive">
          <table class="table table-hover msc-smart-table">
            <thead>
                <tr>
                    <th>Room Number</th>
                    <th>Room Category</th>
                    <th>Status</th>
                    <th>Floor</th>
                    <th>Rate</th>
                    <th>Rate List</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rooms as $item)
                    <tr>
                        <td>{{$item->room_number}}</td>
                        <td>{{$item->roomcategory->name}}</td>
                        <td>{{$item->room_status}}</td>
                        <td>{{$item->floor}}</td>
                        <td>₹{{$item->price_per_day ?? 1}}</td>
                        <td>{{App\Models\RateList::find($item->rate_list_id)->name}}</td>
                        <td>
                            <x-btn-edit id="{{Crypt::encrypt($item->id)}}" route="hospit.room.edit.view"/>
                            <x-btn-delete id="{{Crypt::encrypt($item->id)}}" route="hospit.room.delete"/>
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