<x-structure />
<x-header heading="{{$title}}"/>
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body table-card-body">
        <div class="text-end mb-2">
          <x-btn-add route="hospit.operation.add.view" />
        </div>
        <div class="table-responsive">
          <table class="table table-hover table-bordered msc-smart-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Department</th>
                    <th>Rate List</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $item)
                    <tr>
                        <td>{{$item->name}}</td>
                        <td>{{$item->category->name}}</td>
                        <td>{{$item->department->name}}</td>
                        <td>{{$item->rateList->name}}</td>
                        <td>₹{{$item->price}}</td>
                        <td>
                            <span class="badge bg-{{$item->status == "1" ? "success":"danger"}}">
                                {{$item->status == "1" ? "Active":"Inactive"}}
                            </span>
                        </td>
                        <td>
                            <x-btn-edit id="{{Crypt::encrypt($item->id)}}" route="hospit.operation.edit.view"/>
                            <x-btn-delete id="{{Crypt::encrypt($item->id)}}" route="hospit.operation.delete"/>
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