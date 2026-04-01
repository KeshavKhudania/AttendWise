<x-structure />
<x-header heading="{{$title}}"/>
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body table-card-body">
        <div class="text-end mb-2">
          <x-btn-add route="admin.institution.users.add.view" />
        </div>
        <div class="table-responsive">
          <table class="table table-hover table-bordered msc-smart-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Group Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($admins as $item)
                    <tr>
                        <td>{{$item->name}}</td>
                        <td>{{$item->email}}</td>
                        <td>{{$item->mobile}}</td>
                        <td>{{$item->institution->legal_name}}</td>
                        <td><span class="badge bg-warning">{{$item->group->name}}</span></td>
                        <td>
                            <x-btn-edit id="{{Crypt::encrypt($item->id)}}" route="admin.institution.users.edit.view"/>
                            <x-btn-delete id="{{Crypt::encrypt($item->id)}}" route="admin.institution.users.delete"/>
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