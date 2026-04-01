<x-structure />
<x-header heading="{{$title}}"/>
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body table-card-body">
        <div class="text-end mb-2">
          <x-btn-add route="hospit.doctor.schedule.add.view" />
        </div>
        <div class="table-responsive">
          <table class="table table-hover table-bordered table-striped msc-smart-table">
            <thead>
                <tr>
                    <th>Doctor Name</th>
                    <th>Schedule</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $item)
                    <tr>
                        <td>{{App\Models\Doctor::find($item->doctor_id)->name}}</td>
                        <td class="text-uppercase">{{$item->name}}</td>
                        <td>
                            <span class="badge bg-{{$item->status == "1" ? "success":"danger"}}">
                                {{$item->status == "1" ? "Active":"Inactive"}}
                            </span>
                        </td>
                        <td>
                            <x-btn-edit id="{{Crypt::encrypt($item->id)}}" route="hospit.doctor.schedule.edit.view"/>
                            <x-btn-delete id="{{Crypt::encrypt($item->id)}}" route="hospit.doctor.schedule.delete"/>
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