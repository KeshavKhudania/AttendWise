<x-structure />
<x-header heading="{{$title}}" />
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="text-end mb-2"><x-btn-add route="hospit.beds.category.add.view" /></h4>
        <div class="table-responsive">
          <table class="table table-hover msc-smart-table">
            <thead>
                <tr>
                    {{-- <th>Room Number</th> --}}
                    <th>Bed Category</th>
                    <th>Description</th>
                    <th>Status</th>
                    {{-- <th>Floor</th> --}}
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($beds as $item)
                    <tr>
                        <td>{{$item->category_name}}</td>
                        <td>{{$item->description}}</td>
                        <td> @if ($item->cat_status == "active")
                            <span class="badge bg-success" >Active</span>
                            @elseif ($item->cat_status == "disabled")    
                            <span class="badge bg-danger" >Disabled</span>
                            @else
                            <span class="badge bg-warning">N/A</span>
                        @endif </td>
                        <td>
                            <x-btn-edit id="{{Crypt::encrypt($item->id)}}" route="hospit.beds.category.edit.view"/>
                            <x-btn-delete id="{{Crypt::encrypt($item->id)}}" route="hospit.beds.category.delete"/>
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