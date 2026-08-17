<x-structure />
<x-header heading="Resource Info" />

<div class="aw-page-header mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-heading mb-1">Timetable Resource Info</h1>
            <p class="text-muted small mb-0">Required subjects and faculty for the selected sections.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('institution.time.table.manage') }}" class="btn btn-light border">
                <i class="fa fa-arrow-left me-1.5 opacity-75"></i> Back to Timetables
            </a>
            <button type="button" class="btn btn-success shadow-sm" onclick="downloadCSV()">
                <i class="fa fa-file-csv me-1.5"></i> Download CSV
            </button>
        </div>
    </div>
</div>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card aw-form-card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table aw-table table-hover" id="resourceTable">
                    <thead>
                        <tr>
                            <th>Section</th>
                            <th>Semester</th>
                            <th>Subject Name</th>
                            <th>Req. Lectures/Wk</th>
                            <th>Assigned Faculty</th>
                            <th>Working Days</th>
                            <th>Daily Limit</th>
                            <th>Max Capacity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($resources as $res)
                        <tr class="{{ $res['is_insufficient'] ? 'table-danger' : '' }}">
                            <td><strong>{{ $res['section'] }}</strong></td>
                            <td>{{ $res['semester'] }}</td>
                            <td>
                                {{ $res['subject'] }}
                                @if($res['is_insufficient'])
                                    <i class="fa fa-exclamation-triangle text-danger ms-1" title="Insufficient Resources"></i>
                                @endif
                            </td>
                            <td><span class="badge {{ $res['is_insufficient'] ? 'bg-danger' : 'bg-primary' }} rounded-pill">{{ $res['lectures'] }}</span></td>
                            <td>
                                @if($res['faculty'] == 'Unassigned')
                                    <span class="badge bg-danger">Unassigned</span>
                                @else
                                    {{ $res['faculty'] }}
                                @endif
                            </td>
                            <td>{{ $res['working_days'] }}</td>
                            <td>{{ $res['daily_limit'] }}</td>
                            <td>
                                <span class="fw-bold {{ $res['is_insufficient'] ? 'text-danger' : 'text-success' }}">
                                    {{ $res['max_capacity'] }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No resources found for selected sections.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function downloadCSV() {
        let csv = [];
        let rows = document.querySelectorAll("#resourceTable tr");
        
        for (let i = 0; i < rows.length; i++) {
            let row = [], cols = rows[i].querySelectorAll("td, th");
            
            for (let j = 0; j < cols.length; j++) 
                row.push('"' + cols[j].innerText.trim() + '"');
            
            csv.push(row.join(","));
        }

        let csvFile = new Blob([csv.join("\n")], {type: "text/csv"});
        let downloadLink = document.createElement("a");
        downloadLink.download = "Timetable_Resource_Info.csv";
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = "none";
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }
</script>

<x-footer />
