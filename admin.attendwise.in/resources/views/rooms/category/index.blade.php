<x-structure />
<x-header />
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">{{$title}} <x-btn-add route="hospit.room.category.add.view" /></h4>
        <div class="table-responsive">
          <table class="table table-hover msc-smart-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Rate List</th>
                    <th>Rate</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rooms as $item)
                    <tr>
                        <td>{{$item->name}}</td>
                        <td>{{$item->description}}</td>
                        <td>{{App\Models\RateList::find($item->rate_list_id ?? 1)->name}}</td>
                        <td>₹{{$item->price_per_day}}</td>
                        <td>
                          <span class="badge bg-{{$item->status == "active" ? "success":"danger"}}">
                            {{$item->status == "active" ? "Active":"Inactive"}}
                          </span>
                        </td>
                        <td>{{ucwords(str_replace('_', ' ', $item->type))}}</td>
                        <td>
                            <x-btn-edit id="{{Crypt::encrypt($item->id)}}" route="hospit.room.category.edit.view"/>
                            <x-btn-delete id="{{Crypt::encrypt($item->id)}}" route="hospit.room.category.delete"/>
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