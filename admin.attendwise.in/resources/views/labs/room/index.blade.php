<x-structure />
<x-header />
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">{{$title}} <x-btn-add route="hospit.laboratories.add.view" /></h4>
        <div class="table-responsive">
          <table class="table table-hover table-bordered msc-smart-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Floor</th>
                    <th>Lab Department</th>
                    <th>Contact Numer</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rooms as $item)
                    <tr>
                        <td>{{$item->name}}</td>
                        <td>{{$item->floor > 0 ? Number::ordinal($item->floor):"Ground (0)"}}</td>
                        <td>{{$item->lab_department?->lab_name}}</td>
                        <td>{{$item->contact_number ?? "--"}}</td>
                        <td>{{$item->email ?? "--"}}</td>
                        <td>
                          <span class="badge bg-{{$item->status == "active" ? "success":"danger"}}">
                            {{$item->status == "active" ? "Active":"Inactive"}}
                          </span>
                        </td>
                        <td>
                            <x-btn-edit id="{{Crypt::encrypt($item->id)}}" route="hospit.laboratories.edit.view"/>
                            <x-btn-delete id="{{Crypt::encrypt($item->id)}}" route="hospit.laboratories.delete"/>
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