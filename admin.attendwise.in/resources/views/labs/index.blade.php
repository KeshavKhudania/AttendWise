<x-structure />
<x-header heading="Manage Labs" />
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body table-card-body">
        <div class="text-end mb-2">
        <x-btn-add route="hospit.labs.add.view" />
        </div>
        <div class="table-responsive">
          <table class="table table-hover table-bordered msc-smart-table">
            <thead>
                <tr>
                    <th>S.no</th>
                    <th>Lab Name</th>
                    <th>Lab Contact Number</th>
                    <th>Lab Email</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($labs as $item)
                    <tr>
                      <td><b class="badge bg-warning">{{$loop->iteration}}</b></td>
                        <td>{{$item->first_name}} {{$item->lab_name}}</td>
                        <td class="text-uppercase">{{$item->contact_number}}</td>
                        {{-- <td>{{Carbon\Carbon::parse($item->date_of_birth)->age}}</td> --}}
                        <td>{{$item->email}}</td>
                        <td>
                            <span class="badge bg-{{$item->status == "active" ? "success":"danger"}}">
                                {{$item->status == "active" ? "Active":"Inactive"}}
                            </span>
                        </td>
                        <td>
                            <x-btn-edit id="{{Crypt::encrypt($item->id)}}" route="hospit.labs.edit.view"/>
                            <x-btn-delete id="{{Crypt::encrypt($item->id)}}" route="hospit.patient.delete"/>
                        </td>
                    </tr>
                @endforeach
            </tbody>
          </table>
          {{-- <div class="table-navigation-panel">
            @if ($labs->onFirstPage())
            <a href="javascript:void(0)" class="text-decoration-none text-muted">Prev</a>
            @else
            <a href="{{ $labs->previousPageUrl() }}" class="text-decoration-none text-primary">Prev</a>
            @endif
            @if ($labs->hasMorePages())
              <a href="{{ $labs->nextPageUrl() }}" class="text-decoration-none text-primary">Next</a>
            @else
              <a href="javascript:void(0)" class="text-decoration-none text-muted">Next</a>
            @endif
        </div> --}}
        </div>
      </div>
    </div>
  </div>
<x-footer/>
