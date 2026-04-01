<x-structure />
<x-header heading="{{$title}}"/>
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card" style="background-color:#fff !important;">
      <div class="card-body table-card-body">

        <div class="text-end mb-2">
          <x-btn-add route="hospit.opd.add.view" />
        </div>
        <div class="table-responsive">
          <table class="table table-hover table-bordered msc-smart-table">
            <thead>
                <tr>
                    <th>Queue No.</th>
                    <th>OPD No.</th>
                    <th>UHID</th>
                    <th>Patient Name</th>
                    <th>Doctor Name</th>
                    <th>Date</th>
                    <th>Priority</th>
                    <th>Doctor Fee</th>
                    <th>Paid Amount (Dis.)</th>
                    <th>Payment Status</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $item)
                    <tr>
                        <td>{{$item->queue_number}}</td>
                        <td>{{$item->opd_id}}</td>
                        <td><span class="badge bg-warning">{{$item->patient->uhid}}</span></td>
                        <td>{{$item->patient->first_name}} {{$item->patient->last_name}}</td>
                        <td>Dr.{{$item->doctor->name}}</td>
                        <td class="text-uppercase">{{$item->appointment_date}}</td>
                        <td>
                            <span class="badge bg-{{$item->priority == "Urgent" ? "danger":"success"}}">
                                {{$item->priority}}
                            </span>
                        </td>
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
                          <a href="staff-portal/opd/show/{{ Crypt::encrypt($item->id) }}" class="text-decoration-none" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Print">
                            <button class="btn btn-purple">
                              <i class="fas fa-print"></i> Print
                            </button>
                          </a>
                          @if (in_array("hospit.opd.update", $allowed_permissions))
                              <a href="staff-portal/opd/regenrate-queue-number/{{ Crypt::encrypt($item->id) }}" class="MscTaskBtn text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="Regenerate Queue No.">
                                  <button class="btn btn-edit btn-success"><i class="fas fa-undo"></i> Regenerate Queue</button>
                              </a>
                          @endif
                          @if ($item->status != "Cancelled")
                          <x-btn-edit id="{{Crypt::encrypt($item->id)}}" route="hospit.opd.edit.view"/>
                          @endif
                            @if (in_array("hospit.opd.update", $allowed_permissions) && $item->status == "Cancelled")
                            <a href="staff-portal/opd/refund/{{ Crypt::encrypt($item->id) }}" class="MscTaskBtn text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="Refund">
                              <button class="btn btn-primary"><i class="fas fa-hand-holding-usd"></i> Refund</button>
                            </a>
                            @endif
                            @if (in_array("hospit.ipd.create", $allowed_permissions))
                            <a href="staff-portal/opd/convert-ipd?opd={{ Crypt::encrypt($item->id) }}" class="MscTaskBtn text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="Convert to IPD">
                              <button class="btn btn-teal"><i class="fas fa-bed"></i> IPD</button>
                            </a>
                            @endif
                            <a href="staff-portal/opd/view/{{ Crypt::encrypt($item->id) }}" class="text-decoration-none btnViewRow" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                              <button class="btn btn-warning"><i class="far fa-eye"></i> View</button>
                            </a>
                            <a href="test/assign/{{ Crypt::encrypt($item->id) }}" class="text-decoration-none text-dark" data-bs-toggle="tooltip" data-bs-placement="top" title="Assign Test">
                              <button class="btn btn-info">
                                <i class="fas fa-vial"></i> Lab Investigation
                              </button>
                            </a>
                            <x-btn-delete id="{{Crypt::encrypt($item->id)}}" route="hospit.opd.delete"/>
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