<x-structure />
<x-header />

<style>
  .quick-action-card {
    transition: all 0.3s ease;
    border: 1px solid #edf2f7;
    cursor: pointer;
  }

  .quick-action-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    border-color: #4299e1;
  }

  .quick-action-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    margin-bottom: 12px;
  }

  .chart-container {
    position: relative;
    height: 300px;
  }
</style>

<div class="aw-page-header mb-4">
  <div class="d-flex justify-content-between align-items-center">
    <div>
      <h1 class="page-heading mb-1">Institution Dashboard</h1>
      <p class="text-muted small mb-0">Overview of students, staff, and institutional activities.</p>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-white shadow-xs border d-flex align-items-center">
        <i class="fa fa-calendar me-2 text-muted"></i> {{ date('D, d M Y') }}
      </button>
      <a href="{{ route('institution.events.manage.add.view') }}" class="btn btn-primary d-flex align-items-center">
        <i class="fa fa-plus me-2"></i> New Event
      </a>
    </div>
  </div>
</div>

<div class="container-fluid px-0">
  <!-- App Errors Alert -->
  @if(count($app_errors) > 0)
  <div class="alert alert-danger shadow-sm border-0 d-flex align-items-center mb-4" role="alert">
    <div class="flex-shrink-0 me-3">
      <i class="fas fa-exclamation-triangle fa-2x"></i>
    </div>
    <div class="flex-grow-1">
      <h6 class="alert-heading fw-bold mb-1">Mobile App Errors Detected</h6>
      <p class="mb-0 small">There are {{ count($app_errors) }} unresolved technical errors reported by the mobile application.</p>
    </div>
    <div class="flex-shrink-0">
      <button class="btn btn-danger btn-sm px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#appErrorsModal">
        View Error Log
      </button>
    </div>
  </div>
  @endif

  <!-- KPI Row -->
  <div class="row gx-4 mb-4">
    @foreach ($kpis as $item)
    <x-kpi-card :icon="$item['icon']" :color="$item['color']" :title="$item['name']" :value="$item['count']"
      :delta="$item['delta']" />
    @endforeach
  </div>

  <div class="row g-4 mb-4">
    <!-- Main Analytics Graph -->
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
          <h6 class="mb-0 fw-bold">Attendance Trends</h6>
          <div class="dropdown">
            <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown"><i
                class="fa fa-ellipsis-v"></i></button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="#">View Reports</a></li>
              <li><a class="dropdown-item" href="#">Export CSV</a></li>
            </ul>
          </div>
        </div>
        <div class="card-body">
          <div class="chart-container">
            <canvas id="attendanceChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4">
      <h6 class="fw-bold mb-3">Quick Actions</h6>
      <div class="row g-3">
        <div class="col-6">
          <a href="{{ route('institution.student.add.view') }}" class="text-decoration-none">
            <div class="card quick-action-card h-100">
              <div class="card-body">
                <div class="quick-action-icon bg-soft-primary text-primary">
                  <i class="fa fa-user-plus"></i>
                </div>
                <h6 class="mb-1 text-dark small fw-bold">Add Student</h6>
                <p class="text-muted mb-0" style="font-size: 10px;">Enrol new student</p>
              </div>
            </div>
          </a>
        </div>
        <div class="col-6">
          <a href="{{ route('institution.faculty.add.view') }}" class="text-decoration-none">
            <div class="card quick-action-card h-100">
              <div class="card-body">
                <div class="quick-action-icon bg-soft-success text-success">
                  <i class="fa fa-chalkboard-teacher"></i>
                </div>
                <h6 class="mb-1 text-dark small fw-bold">Add Faculty</h6>
                <p class="text-muted mb-0" style="font-size: 10px;">Onboard new staff</p>
              </div>
            </div>
          </a>
        </div>
        <div class="col-6">
          <a href="{{ route('institution.time.table.add.view') }}" class="text-decoration-none">
            <div class="card quick-action-card h-100">
              <div class="card-body">
                <div class="quick-action-icon bg-soft-warning text-warning">
                  <i class="fa fa-clock"></i>
                </div>
                <h6 class="mb-1 text-dark small fw-bold">Schedule</h6>
                <p class="text-muted mb-0" style="font-size: 10px;">Manage timetable</p>
              </div>
            </div>
          </a>
        </div>
        <div class="col-6">
          <a href="{{ route('institution.department.manage') }}" class="text-decoration-none">
            <div class="card quick-action-card h-100">
              <div class="card-body">
                <div class="quick-action-icon bg-soft-info text-info">
                  <i class="fa fa-building"></i>
                </div>
                <h6 class="mb-1 text-dark small fw-bold">Departments</h6>
                <p class="text-muted mb-0" style="font-size: 10px;">Configure depts</p>
              </div>
            </div>
          </a>
        </div>
        <div class="col-6">
          <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#appErrorsModal" class="text-decoration-none">
            <div class="card quick-action-card h-100">
              <div class="card-body position-relative">
                @if(count($app_errors) > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm" style="margin-top: 5px; margin-left: -15px; border: 2px solid white;">
                  {{ count($app_errors) }}
                </span>
                @endif
                <div class="quick-action-icon bg-soft-danger text-danger">
                  <i class="fa fa-bug"></i>
                </div>
                <h6 class="mb-1 text-dark small fw-bold">App Logs</h6>
                <p class="text-muted mb-0" style="font-size: 10px;">Monitor app health</p>
              </div>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4 mb-4">
    <!-- Enrollment by Faculty -->
    <div class="col-md-4">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
          <h6 class="mb-0 fw-bold">Enrollment Share</h6>
        </div>
        <div class="card-body">
          <div class="chart-container" style="height: 250px;">
            <canvas id="enrollmentChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Events -->
    <div class="col-md-8">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
          <h6 class="mb-0 fw-bold">Recent Institutional Events</h6>
          <a href="{{ route('institution.events.manage') }}"
            class="btn btn-link btn-sm text-primary p-0 text-decoration-none fw-bold">View All</a>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="bg-light text-muted small text-uppercase">
                <tr>
                  <th class="px-4 border-0">Event Name</th>
                  <th class="border-0">Date</th>
                  <th class="border-0">Participants</th>
                  <th class="border-0 text-end px-4">Status</th>
                </tr>
              </thead>
              <tbody>
                @php
                $recentEvents = \App\Models\Event::withCount('participants')->latest()->take(4)->get();
                @endphp
                @forelse($recentEvents as $re)
                <tr>
                  <td class="px-4">
                    <div class="d-flex align-items-center">
                      <div
                        class="symbol symbol-40px me-3 bg-light-primary rounded-3 d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px;">
                        <i class="fa fa-calendar-check text-primary"></i>
                      </div>
                      <div>
                        <div class="fw-bold text-dark">{{ $re->name }}</div>
                        <small class="text-muted">{{ $re->event_type }}</small>
                      </div>
                    </div>
                  </td>
                  <td>{{ date('d M Y', strtotime($re->event_date)) }}</td>
                  <td><span class="badge bg-light text-dark">{{ $re->participants_count }}</span></td>
                  <td class="text-end px-4">
                    @if($re->status == 1)
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Active</span>
                    @else
                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">Upcoming</span>
                    @endif
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="4" class="text-center py-5 text-muted">No events recorded yet.</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Attendance Trends Chart
    new Chart(document.getElementById('attendanceChart'), {
        type: 'line',
        data: {
            labels: @json($analytics['attendance']['labels']),
            datasets: [{
                label: 'Attendance %',
                data: @json($analytics['attendance']['data']),
                borderColor: '#4299e1',
                backgroundColor: 'rgba(66, 153, 225, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#4299e1',
                pointBorderWidth: 2,
                pointRadius: 4
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, max: 100, grid: { display: false }, ticks: { stepSize: 20 } },
                x: { grid: { display: false } }
            }
        }
    });

    // Enrollment Chart
    new Chart(document.getElementById('enrollmentChart'), {
        type: 'doughnut',
        data: {
            labels: @json($analytics['enrollment']['labels']),
            datasets: [{
                data: @json($analytics['enrollment']['data']),
                backgroundColor: ['#4299e1', '#48bb78', '#ecc94b', '#ed64a6', '#9f7aea'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 15, font: { size: 10 } } }
            }
        }
    });
});
</script>

<!-- App Errors Modal -->
<div class="modal fade" id="appErrorsModal" tabindex="-1" aria-labelledby="appErrorsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-danger text-white border-0 py-3">
        <h5 class="modal-title fw-bold" id="appErrorsModalLabel">
          <i class="fas fa-bug me-2"></i> Application Error Log
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-muted small text-uppercase">
              <tr>
                <th class="px-4 py-3">Timestamp</th>
                <th>User / Student</th>
                <th>Device Info</th>
                <th>Error Message</th>
                <th class="text-end px-4">Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse($app_errors as $error)
              <tr data-bs-toggle="collapse" data-bs-target="#errorDetail{{ $error->id }}" style="cursor: pointer;">
                <td class="px-4 small text-muted">
                  {{ $error->created_at->format('d M, H:i') }}
                  <div class="small" style="font-size: 10px;">{{ $error->created_at->diffForHumans() }}</div>
                </td>
                <td>
                  @if($error->student)
                    <div class="fw-bold text-dark">{{ $error->student->name }}</div>
                    <div class="small text-muted">Roll: {{ $error->student->roll_number }}</div>
                  @else
                    <span class="text-muted italic small">Guest Student</span>
                  @endif
                </td>
                <td>
                  <div class="small">
                    <span class="badge bg-light text-dark border">{{ $error->app_version ?? 'v1.0' }}</span>
                    <span class="ms-1 text-muted">{{ $error->device_info['model'] ?? 'Unknown' }}</span>
                  </div>
                </td>
                <td>
                  <div class="text-truncate" style="max-width: 300px;" title="{{ $error->error_message }}">
                    {{ $error->error_message }}
                  </div>
                  <div class="small text-muted" style="font-size: 10px;">{{ $error->api_endpoint }}</div>
                </td>
                <td class="text-end px-4">
                    <button class="btn btn-outline-secondary btn-xs py-1 px-2" type="button" data-bs-toggle="collapse" data-bs-target="#errorDetail{{ $error->id }}">
                        Details
                    </button>
                </td>
              </tr>
              <tr class="collapse" id="errorDetail{{ $error->id }}">
                <td colspan="5" class="p-4 bg-light shadow-inner">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-7">
                                    <h6 class="fw-bold small text-uppercase text-muted border-bottom pb-2 mb-3">Stack Trace</h6>
                                    <pre class="bg-dark text-light p-3 rounded small mb-0" style="max-height: 300px; overflow: auto; font-size: 11px;"><code>{{ $error->stack_trace }}</code></pre>
                                </div>
                                <div class="col-md-5">
                                    <h6 class="fw-bold small text-uppercase text-muted border-bottom pb-2 mb-3">Technical Context</h6>
                                    <div class="list-group list-group-flush small bg-white rounded border mb-3">
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <span class="text-muted">API Endpoint</span>
                                            <span class="fw-bold">{{ $error->api_endpoint ?: 'N/A' }}</span>
                                        </div>
                                        <div class="list-group-item">
                                            <div class="mb-2 text-muted fw-bold">Request Payload:</div>
                                            <pre class="bg-light p-2 rounded mb-0" style="font-size: 10px; max-height: 150px; overflow: auto;"><code>{{ json_encode($error->request_payload, JSON_PRETTY_PRINT) }}</code></pre>
                                        </div>
                                        <div class="list-group-item">
                                            <div class="mb-2 text-muted fw-bold">Device Info:</div>
                                            <pre class="bg-light p-2 rounded mb-0" style="font-size: 10px;"><code>{{ json_encode($error->device_info, JSON_PRETTY_PRINT) }}</code></pre>
                                        </div>
                                    </div>
                                    <div class="mt-3 text-end">
                                        <form action="{{ route('institution.app-errors.resolve', $error->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm px-4 fw-bold">
                                                <i class="fas fa-check me-2"></i> Mark as Resolved
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center py-5">
                    <div class="text-muted p-5">
                        <i class="fas fa-check-circle fa-3x mb-3 text-light"></i>
                        <p>All clear! No unresolved app errors.</p>
                    </div>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer bg-light border-0">
        <button type="button" class="btn btn-secondary shadow-sm" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<x-footer />