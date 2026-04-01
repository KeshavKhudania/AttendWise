<x-structure />
<x-header heading="{{$title}}"/>
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body table-card-body">
        
        <div class="text-end mb-2">
          <x-btn-add route="hospit.test.add.view" />
        </div>
        <div class="table-responsive">
          <table class="table table-hover table-bordered msc-smart-table">
            <thead>
                <tr>
                    {{-- <th>Test Name</th> --}}
                    {{-- <th>CODE</th> --}}
                    <th>UHID</th>
                    <th>Patient Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Total Amount</th>
                    <th>Paid Amount (Dis.)</th>
                    <th>Payment Status</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $item)
                    <tr>
                        {{-- <td>{{$item->name}}</td> --}}
                        {{-- <td>{{$item->test->code}}</td> --}}
                        <td><span class="badge bg-warning">{{$item->patient->uhid}}</span></td>
                        <td>{{$item->patient->first_name}} {{$item->patient->last_name}}</td>
                        <td class="text-uppercase">{{$item->date}}</td>
                        <td class="text-uppercase">{{$item->time}}</td>
                        <td>₹{{$item->transaction->total_amount}}</td>
                        <td>₹{{$item->transaction->payable_amount}} ({{$item->transaction->discount}}%)</td>
                        {{-- <td>{{$item->transaction->status}}</td> --}}
                        <td>
                            <span class="badge bg-{{$item->transaction->status == "Pending" ? "warning":($item->transaction->status == "Refund" ? "danger":"success")}}">
                                {{$item->transaction->status}}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{$item->status == "Scheduled" ? "warning":($item->status == "Cancelled" ? "danger":"success")}}">
                                {{$item->status}}
                            </span>
                        </td>
                        <td>
                          @if (in_array("hospit.test.update", $allowed_permissions))
                              <a href="test/regenrate-queue-number/{{ Crypt::encrypt($item->id) }}" class="MscTaskBtn text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="Regenerate Queue No.">
                                  <button class="btn btn-edit text-hover-success"><i class="mdi mdi-refresh"></i></button>
                              </a>
                          @endif
                          @if ($item->status != "Cancelled")
                          <x-btn-edit id="{{Crypt::encrypt($item->id)}}" route="hospit.test.edit.view"/>
                          @endif
                            @if (in_array("hospit.test.update", $allowed_permissions) && $item->status == "Cancelled")
                            <a href="test/refund/{{ Crypt::encrypt($item->id) }}" class="MscTaskBtn text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="Refund">
                              <button class="btn text-hover-primary"><i class="fas fa-hand-holding-usd"></i></button>
                            </a>
                            @endif
                            @if (in_array("hospit.ipd.create", $allowed_permissions))
                            <a href="test/convert-ipd/{{ Crypt::encrypt($item->id) }}" class="MscTaskBtn text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="Convert to IPD">
                              <button class="btn text-hover-purple"><i class="mdi mdi-bed-empty"></i></button>
                            </a>
                            @endif
                            <a href="test/view/{{ Crypt::encrypt($item->id) }}" class="text-decoration-none btnViewRow" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                              <button class="btn text-hover-warning"><i class="mdi mdi-eye"></i></button>
                            </a>
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