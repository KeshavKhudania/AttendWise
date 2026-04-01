<x-structure/>
<x-header/>
<x-structure />
<x-header heading="Upload Report for Patient {{$patient->first_name}} ( {{$patient->uhid}} )" />
<div class="col-lg-12 grid-margin stretch-card">
  <div class="card bg-white">
    <div class="card-body">
      <div class="container-fluid">
        <div class="row">
          <!-- Patient Profile -->
          <div class="col-md-3">
            <div class="card bg-white">
              <div class="card-body patientDetailContainer">
                <h5 class="border-bottom pb-1 border-dark">Patient Profile</h5>
                <table class="table table-sm table-bordered">
                  <tbody>
                    <tr><th>Name</th><td>{{ $patient->first_name ?? '' }}</td></tr>
                    <tr><th>Age</th><td>{{ $patient->age ?? '' }}</td></tr>
                    <tr><th>Gender</th><td>{{ $patient->gender ?? '' }}</td></tr>
                    <tr><th>Contact</th><td>{{ $patient->contact ?? '' }}</td></tr>
                    <tr><th>Address</th><td>{{ $patient->address ?? '' }}</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Report Upload Section -->
          <div class="col-md-9">
            <form action="{{ route('lab.reports.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="patient_id" value="{{ $patient->id }}">
              <input type="hidden" name="hospital_id" value="{{ $patient->hospital_id }}">

              <h5 class="border-bottom pb-1 border-dark">Upload Test Report</h5>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="test_id">Test Name</label>
                    <select name="test_id" id="test_id" class="form-control form-select" required>
                      <option value="">Select Test</option>
                      @foreach ($tests as $test)
                        <option value="{{ $test->id }}">{{ $test->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="lab_id">Lab / Operator</label>
                    <select name="lab_id" id="lab_id" class="form-control form-select" required>
                      <option value="">Select Lab</option>
                      @foreach ($labs as $lab)
                        <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="report_date">Report Date</label>
                    <input type="date" name="report_date" id="report_date" class="form-control" required>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="report_file">Upload Report (PDF/Image)</label>
                    <input type="file" name="report_file" id="report_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="report_summary">Report Summary</label>
                    <textarea name="report_summary" id="report_summary" class="form-control" rows="3" placeholder="Short summary of findings" required></textarea>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-group">
                    <label for="remarks">Lab Remarks (optional)</label>
                    <textarea name="remarks" id="remarks" class="form-control" rows="3" placeholder="Additional notes or observations"></textarea>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control form-select" required>
                      <option value="Completed">Completed</option>
                      <option value="Pending">Pending</option>
                      <option value="Under Review">Under Review</option>
                    </select>
                  </div>
                </div>
              </div>

              <x-form-buttons />
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<x-footer/>
