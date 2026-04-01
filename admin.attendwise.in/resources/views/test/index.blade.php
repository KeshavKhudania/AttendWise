<x-structure />
<x-header heading="{{$title}}"/>
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body table-card-body">
        <div class="text-end mb-2">
          <x-btn-add route="hospit.test.add.view" />
        </div>
        <div class="table-responsive">
          <table class="table table-hover table-bordered table-striped msc-smart-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Lab</th>
                    <th>Rate</th>
                    <th>Rate List</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $item)
                    <tr>
                        <td>{{$item->name}}</td>
                        <td>{{DB::table("departments")->find($item->department_id)->name}}</td>
                        <td>{{DB::table("labs")->find($item->lab_id)->lab_name}}</td>
                        <td class="text-uppercase">₹{{$item->rate}}</td>
                        <td class="text-uppercase">{{DB::table("rate_lists")->find($item->rate_list_id)->name}}</td>
                        <td>
                            <span class="badge bg-{{$item->status == "1" ? "success":"danger"}}">
                                {{$item->status == "1" ? "Active":"Inactive"}}
                            </span>
                        </td>
                        <td>
                            <x-btn-edit id="{{Crypt::encrypt($item->id)}}" route="hospit.test.edit.view"/>
                            <x-btn-delete id="{{Crypt::encrypt($item->id)}}" route="hospit.test.delete"/>
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