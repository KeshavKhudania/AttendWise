<x-structure />
<x-header heading="Test Assigned"/>

<div class="container py-4">
    <div class="row g-4">
        @foreach($test_assigned as $test)
        <?php
        $id = Crypt::encrypt($test->id);
        ?>
        <div class="col-md-4">
            <div class="card border border-secondary h-100 shadow-sm bg-white">
                <div class="card-body">
                    <h5 class="card-title text-primary">{{ $test->test_name ?? 'Unnamed Test' }}</h5>
                    <p class="card-text mb-1"><strong>Patient:</strong> {{ $test->patient->name ?? 'N/A' }}</p>
                    <p class="card-text mb-1"><strong>Doctor:</strong> {{ $test->doctor->name ?? 'N/A' }}</p>
                    <p class="card-text mb-2"><strong>Date:</strong> {{ \Carbon\Carbon::parse($test->created_at)->format('Y-m-d') }}</p>

                    @php
                        $status = strtolower($test->status ?? 'pending');
                        $badgeClass = match($status) {
                            'completed' => 'bg-success',
                            'in progress' => 'bg-primary',
                            'rejected' => 'bg-danger',
                            default => 'bg-warning text-dark',
                        };
                    @endphp

                    <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                </div>
                <div class="card-footer bg-light d-flex justify-content-between">
                 <a href="{{route("hospit.manage.test.edit.view", ["id"=>$id])}}">   <button class="btn btn-sm btn-success">Upload Report</button></a>
                    <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-primary">Edit</button>
                        <button class="btn btn-sm btn-secondary">View</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<x-footer />
