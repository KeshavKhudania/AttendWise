<x-structure />
<x-header heading="{{$title}}" />
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body table-card-body">
        <div class="text-end mb-2">
          <x-btn-add route="institution.admin.group.add.view" />
        </div>
        <div class="table-responsive">
          <table class="table table-hover msc-smart-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $item)
                    <tr>
                        <td>{{$item->name}}</td>
                        <td>
                            <span class="badge bg-{{$item->status == "1" ? "success":"danger"}}">
                                {{$item->status == "1" ? "Active":"Inactive"}}
                            </span>
                        </td>
                        <td>
                            <x-btn-edit id="{{Crypt::encrypt($item->id)}}" route="institution.admin.group.edit.view"/>
                            <x-btn-delete id="{{Crypt::encrypt($item->id)}}" route="institution.admin.group.delete"/>
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