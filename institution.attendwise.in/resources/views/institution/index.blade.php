<x-structure />
<x-header heading="{{$title}}" />
<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body table-card-body">
      <div class="text-end mb-2">
        <x-btn-add route="admin.institution.add.view" />
      </div>

      <div class="table-responsive">
        <table class="table table-hover table-bordered msc-smart-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Legal Name</th>
              <th>Type</th>
              <th>Year</th>
              <th>Registered Address</th>
              <th>Plan Type</th>
              <th>Billing Email</th>
              <th>Created At</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($institutions as $item)
              <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->legal_name }}</td>
                <td>{{ $item->institution_type }}</td>
                <td>{{ $item->year_of_establishment }}</td>
                <td style="max-width:260px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $item->registered_address }}</td>
                <td>{{ $item->plan_type ?? '—' }}</td>
                <td>{{ $item->billing_contact_email ?? '—' }}</td>
                <td>{{ optional($item->created_at)->format('d M, Y') }}</td>
                <td>
                  <x-btn-edit id="{{ Crypt::encrypt($item->id) }}" route="admin.institution.edit.view" />
                  <x-btn-delete id="{{ Crypt::encrypt($item->id) }}" route="admin.institution.delete" />
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <div class="table-navigation-panel">
          @if ($institutions->onFirstPage())
            <a href="javascript:void(0)" class="text-decoration-none text-muted">Prev</a>
          @else
            <a href="{{ $institutions->previousPageUrl() }}" class="text-decoration-none text-primary">Prev</a>
          @endif

          @if ($institutions->hasMorePages())
            <a href="{{ $institutions->nextPageUrl() }}" class="text-decoration-none text-primary">Next</a>
          @else
            <a href="javascript:void(0)" class="text-decoration-none text-muted">Next</a>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
<x-footer />
