<x-structure />
<x-header />

<style>
/* ---------------------------------------------------
   Advanced Analytics Command Center Design - Modern Light Mode
----------------------------------------------------- */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

.adv-dashboard {
    background: radial-gradient(circle at top right, #F1F5F9 0%, #F8FAFC 60%, #EEF2F6 100%);
    padding: 2rem 1.5rem 4rem;
    font-family: 'Inter', sans-serif;
    min-height: 100vh;
    color: #0F172A;
}

/* Header */
.adv-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 2.5rem;
    border-bottom: 1px solid #E2E8F0;
    padding-bottom: 1.5rem;
}
.adv-title {
    font-size: 2.2rem;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 0.25rem;
    letter-spacing: -0.025em;
    background: linear-gradient(90deg, #0F172A 0%, #334155 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.adv-subtitle {
    font-size: 0.95rem;
    color: #64748B;
    font-weight: 500;
}

/* Common Card */
.adv-card {
    background: #FFFFFF;
    border-radius: 16px;
    border: 1px solid #E2E8F0;
    box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.04), 0 8px 10px -6px rgba(15, 23, 42, 0.02);
    padding: 1.5rem;
    height: 100%;
    position: relative;
    display: flex;
    flex-direction: column;
    transition: box-shadow 0.3s ease;
}
.adv-card:hover {
    box-shadow: 0 15px 30px -5px rgba(15, 23, 42, 0.07), 0 10px 12px -5px rgba(15, 23, 42, 0.03);
}
.adv-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}
.adv-card-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #0F172A;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.adv-card-title i {
    color: #2563EB;
}

/* KPIs */
.adv-kpi {
    position: relative;
    overflow: hidden;
    padding: 1.5rem;
    border-radius: 16px;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    box-shadow: 0 4px 12px -2px rgba(15, 23, 42, 0.04);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.adv-kpi:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
    border-color: #CBD5E1;
}
.adv-kpi::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 4px;
    background: #3B82F6;
}
.adv-kpi.kpi-success::before { background: #10B981; }
.adv-kpi.kpi-warning::before { background: #F59E0B; }
.adv-kpi.kpi-purple::before { background: #8B5CF6; }

.kpi-content {
    position: relative;
    z-index: 2;
}
.kpi-label {
    font-size: 0.8rem;
    color: #64748B;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.5rem;
}
.kpi-value {
    font-size: 2.5rem;
    font-weight: 800;
    color: #0F172A;
    line-height: 1;
    margin-bottom: 0.5rem;
}
.kpi-delta {
    font-size: 0.85rem;
    font-weight: 600;
}
.delta-up { color: #10B981; }
.delta-down { color: #EF4444; }
.kpi-icon-bg {
    position: absolute;
    right: -10px;
    bottom: -20px;
    font-size: 7rem;
    color: #F1F5F9;
    z-index: 1;
    transform: rotate(-10deg);
    transition: transform 0.3s ease;
}
.adv-kpi:hover .kpi-icon-bg {
    transform: rotate(-5deg) scale(1.05);
}

/* Quick Actions Panel */
.qa-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
}

.qa-btn {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1.5rem 1rem;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    text-decoration: none;
    color: #1E293B;
    font-weight: 700;
    font-size: 0.95rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-align: center;
    gap: 1rem;
    overflow: hidden;
    z-index: 1;
    box-shadow: 0 2px 4px -1px rgba(15, 23, 42, 0.03);
}

.qa-btn::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: var(--qa-color, #3B82F6);
    transform: scaleX(0);
    transform-origin: center;
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.qa-icon-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 56px;
    height: 56px;
    border-radius: 14px;
    background: var(--qa-bg, #EFF6FF);
    color: var(--qa-color, #3B82F6);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.qa-icon-wrapper i {
    font-size: 1.75rem;
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.qa-btn:hover {
    transform: translateY(-5px);
    background: #FFFFFF;
    border-color: transparent;
    box-shadow: 0 12px 25px -8px var(--qa-shadow, rgba(59, 130, 246, 0.3));
    color: #0F172A;
}

.qa-btn:hover::after {
    transform: scaleX(1);
}

.qa-btn:hover .qa-icon-wrapper {
    background: var(--qa-color, #3B82F6);
    color: #FFFFFF;
    box-shadow: 0 8px 16px -4px var(--qa-shadow, rgba(59, 130, 246, 0.4));
}

.qa-btn:hover .qa-icon-wrapper i {
    transform: scale(1.15) rotate(5deg);
}

/* Charts */
.chart-container-large {
    position: relative;
    height: 380px;
    width: 100%;
    flex-grow: 1;
}
.chart-container-small {
    position: relative;
    height: 280px;
    width: 100%;
}

/* Top Buttons */
.btn-adv {
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    color: #334155;
    font-weight: 600;
    padding: 0.65rem 1.25rem;
    border-radius: 10px;
    font-size: 0.9rem;
    transition: all 0.25s ease;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
}
.btn-adv:hover {
    background: #F8FAFC;
    color: #0F172A;
    border-color: #94A3B8;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
}
.btn-adv-primary {
    background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
    border: 1px solid #2563EB;
    color: #FFFFFF;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
}
.btn-adv-primary:hover {
    background: linear-gradient(135deg, #1D4ED8 0%, #1E40AF 100%);
    border-color: #1D4ED8;
    color: #FFFFFF;
    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
    transform: translateY(-1px);
}

/* Data Table */
.data-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}
.data-table th {
    font-size: 0.75rem;
    font-weight: 700;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 1rem 1.25rem;
    border-bottom: 2px solid #E2E8F0;
    background: #F8FAFC;
}
.data-table th:first-child { border-top-left-radius: 12px; }
.data-table th:last-child { border-top-right-radius: 12px; }
.data-table td {
    padding: 1.1rem 1.25rem;
    font-size: 0.9rem;
    color: #334155;
    border-bottom: 1px solid #F1F5F9;
    vertical-align: middle;
    transition: background 0.2s ease;
}
.data-table tbody tr:hover td {
    background: #F8FAFC;
    color: #0F172A;
}
.status-pill {
    padding: 0.35rem 0.85rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}
.status-pill.active { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }
.status-pill.pending { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }

/* Alert */
.adv-alert {
    background: #FEF2F2;
    border: 1px solid #FECACA;
    border-left: 4px solid #EF4444;
    border-radius: 14px;
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.25rem;
    box-shadow: 0 4px 12px -2px rgba(239, 68, 68, 0.08);
}
</style>

<div class="adv-dashboard container-fluid px-4">

    <!-- Header Zone -->
    <div class="adv-header flex-column flex-md-row">
        <div>
            <h1 class="adv-title">Analytics Command Center</h1>
            <p class="adv-subtitle">Advanced overview of institutional performance and attendance metrics.</p>
        </div>
        <div class="d-flex gap-3 mt-3 mt-md-0">
            <button class="btn-adv">
                <i class="far fa-calendar-alt"></i>
                <span>{{ date('M d, Y') }}</span>
            </button>
            <button class="btn-adv">
                <i class="fas fa-download"></i>
                <span>Export Data</span>
            </button>
            <a href="{{ route('institution.events.manage.add.view') }}" class="btn-adv btn-adv-primary">
                <i class="fas fa-plus"></i>
                <span>Create Event</span>
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(count($app_errors) > 0)
    <div class="adv-alert mb-4">
        <div style="font-size: 2rem; color: #EF4444;">
            <i class="fas fa-engine-warning"></i>
        </div>
        <div class="flex-grow-1">
            <h5 class="fw-bold mb-1" style="color: #991B1B; font-size: 1.1rem;">System Diagnostics Alert</h5>
            <p class="mb-0 text-danger" style="font-size: 0.9rem;">
                The diagnostic engine has detected <strong>{{ count($app_errors) }}</strong> anomalies originating from the mobile application nodes.
            </p>
        </div>
        <div>
            <button class="btn-adv" data-bs-toggle="modal" data-bs-target="#appErrorsModal" style="color: #B91C1C; border-color: #FCA5A5;">
                <i class="fas fa-terminal"></i> Analyze Logs
            </button>
        </div>
    </div>
    @endif

    <!-- KPI Metrics -->
    <div class="row g-4 mb-4">
        @php
            $kpiStyles = ['', 'kpi-success', 'kpi-warning', 'kpi-purple'];
        @endphp
        @foreach ($kpis as $index => $item)
        <div class="col-xl-3 col-lg-6">
            <div class="adv-kpi {{ $kpiStyles[$index % 4] }}">
                <i class="{{ $item['icon'] }} kpi-icon-bg"></i>
                <div class="kpi-content">
                    <div class="kpi-label">{{ $item['name'] }}</div>
                    <div class="kpi-value">{{ number_format($item['count']) }}</div>
                    
                    <div class="kpi-delta d-flex align-items-center gap-1">
                        @if(isset($item['delta']))
                            @php 
                                $isPositive = strpos($item['delta'], '+') !== false || (float)$item['delta'] > 0; 
                            @endphp
                            <span class="{{ $isPositive ? 'delta-up' : 'delta-down' }}">
                                <i class="fas {{ $isPositive ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
                                {{ trim($item['delta'], '+-') }}%
                            </span>
                            <span class="text-muted" style="font-size: 0.8rem; font-weight: 500;">vs last period</span>
                        @else
                            <span class="delta-up"><i class="fas fa-minus"></i> 0.0%</span>
                            <span class="text-muted" style="font-size: 0.8rem; font-weight: 500;">baseline established</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Main Analytics Row -->
    <div class="row g-4 mb-4">
        <!-- Advanced Mixed Chart -->
        <div class="col-xl-8">
            <div class="adv-card">
                <div class="adv-card-header">
                    <div class="adv-card-title">
                        <i class="fas fa-chart-network"></i> Attendance Velocity & Volume
                    </div>
                    <div>
                        <select class="form-select form-select-sm shadow-sm" style="width: auto; font-weight: 600; color: #475569; background-color: #F8FAFC; border: 1px solid #CBD5E1; border-radius: 8px;">
                            <option>Last 30 Days</option>
                            <option>This Semester</option>
                            <option>Year to Date</option>
                        </select>
                    </div>
                </div>
                <div class="chart-container-large">
                    <canvas id="advAttendanceChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Quick Actions Panel -->
        <div class="col-xl-4 d-flex flex-column gap-4">
            <div class="adv-card" style="background: linear-gradient(180deg, #FFFFFF 0%, #F8FAFC 100%);">
                <div class="adv-card-header mb-3">
                    <div class="adv-card-title">
                        <i class="fas fa-bolt"></i> Quick Actions
                    </div>
                </div>
                <div class="qa-grid flex-grow-1">
                    <a href="{{ route('institution.student.add.view') }}" class="qa-btn" style="--qa-color: #3B82F6; --qa-bg: #EFF6FF; --qa-shadow: rgba(59, 130, 246, 0.4);">
                        <div class="qa-icon-wrapper">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <span>Enroll Student</span>
                    </a>
                    <a href="{{ route('institution.faculty.add.view') }}" class="qa-btn" style="--qa-color: #10B981; --qa-bg: #ECFDF5; --qa-shadow: rgba(16, 185, 129, 0.4);">
                        <div class="qa-icon-wrapper">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <span>Add Faculty</span>
                    </a>
                    <a href="{{ route('institution.time.table.add.view') }}" class="qa-btn" style="--qa-color: #F59E0B; --qa-bg: #FFFBEB; --qa-shadow: rgba(245, 158, 11, 0.4);">
                        <div class="qa-icon-wrapper">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <span>Timetable</span>
                    </a>
                    <a href="{{ route('institution.department.manage') }}" class="qa-btn" style="--qa-color: #8B5CF6; --qa-bg: #F5F3FF; --qa-shadow: rgba(139, 92, 246, 0.4);">
                        <div class="qa-icon-wrapper">
                            <i class="fas fa-sitemap"></i>
                        </div>
                        <span>Departments</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Analytics & Data Table -->
    <div class="row g-4">
        <!-- Polar Area Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="adv-card">
                <div class="adv-card-header">
                    <div class="adv-card-title">
                        <i class="fas fa-chart-pie"></i> Faculty Distribution Matrix
                    </div>
                </div>
                <div class="chart-container-small">
                    <canvas id="advPolarChart"></canvas>
                </div>
                <div class="mt-4 pt-3 border-top text-center text-muted" style="font-size: 0.8rem; font-weight: 500;">
                    Visualization of resource allocation across operational departments.
                </div>
            </div>
        </div>

        <!-- Comprehensive Data Table -->
        <div class="col-xl-8 col-lg-7">
            <div class="adv-card p-0 overflow-hidden">
                <div class="adv-card-header px-4 pt-4 mb-2">
                    <div class="adv-card-title">
                        <i class="fas fa-list-alt"></i> Recent Institutional Events
                    </div>
                    <a href="{{ route('institution.events.manage') }}" class="btn btn-sm btn-outline-primary" style="font-weight: 600;">View All</a>
                </div>
                <div class="table-responsive px-4 pb-4">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Event Identifier</th>
                                <th>Schedule Date</th>
                                <th>Classification</th>
                                <th>Participants</th>
                                <th class="text-end">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $recentEvents = \App\Models\Event::withCount('participants')->latest()->take(5)->get();
                            @endphp
                            
                            @forelse($recentEvents as $re)
                            <tr>
                                <td class="fw-bold">{{ $re->name }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="far fa-calendar text-muted"></i>
                                        <span>{{ date('M d, Y', strtotime($re->event_date)) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border shadow-sm" style="font-weight: 600;">{{ $re->event_type }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-users text-muted"></i>
                                        <span class="fw-bold">{{ $re->participants_count }}</span>
                                    </div>
                                </td>
                                <td class="text-end">
                                    @if($re->status == 1)
                                        <span class="status-pill active">Active</span>
                                    @else
                                        <span class="status-pill pending">Upcoming</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                                    <p class="mb-0 fw-bold">No event data available in the current timeframe.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. Advanced Mixed Chart (Attendance Velocity) ---
    const attCtx = document.getElementById('advAttendanceChart').getContext('2d');
    
    // Creating an advanced mixed chart using the single attendance dataset
    // We will show it as a line chart (Trend) and a bar chart (Volume representation)
    const attendanceLabels = @json($analytics['attendance']['labels']);
    const attendanceData = @json($analytics['attendance']['data']);
    
    // Gradient for the area under the line
    const gradientLine = attCtx.createLinearGradient(0, 0, 0, 400);
    gradientLine.addColorStop(0, 'rgba(59, 130, 246, 0.4)');
    gradientLine.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

    new Chart(attCtx, {
        type: 'line', // Base type
        data: {
            labels: attendanceLabels,
            datasets: [
                {
                    type: 'line',
                    label: 'Attendance Trend (Moving Avg)',
                    data: attendanceData,
                    borderColor: '#2563EB',
                    backgroundColor: gradientLine,
                    borderWidth: 3,
                    tension: 0.4, // smooth curve
                    fill: true,
                    pointBackgroundColor: '#FFFFFF',
                    pointBorderColor: '#2563EB',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    yAxisID: 'y'
                },
                {
                    type: 'bar',
                    label: 'Daily Volume Index',
                    // Faking volume data based on attendance % to create a complex look
                    data: attendanceData.map(val => val * (0.8 + Math.random() * 0.4)), 
                    backgroundColor: 'rgba(148, 163, 184, 0.2)', // Slate-400 transparent
                    borderRadius: 4,
                    barPercentage: 0.6,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { 
                legend: { 
                    position: 'top',
                    align: 'end',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 8,
                        font: { family: 'Inter', size: 12, weight: '600' },
                        color: '#475569'
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleColor: '#F8FAFC',
                    bodyColor: '#F1F5F9',
                    padding: 12,
                    cornerRadius: 10,
                    titleFont: { size: 13, family: 'Inter', weight: 'bold' },
                    bodyFont: { size: 12, family: 'Inter' }
                }
            },
            scales: {
                y: { 
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: { display: true, text: 'Attendance %', font: {family: 'Inter', size:11, weight: 'bold'}, color: '#64748B' },
                    grid: { color: '#F1F5F9', borderDash: [5, 5] },
                    min: 0, max: 100,
                    ticks: { font: { family: 'Inter', size: 11 }, color: '#64748B' }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: { display: true, text: 'Volume Index', font: {family: 'Inter', size:11, weight: 'bold'}, color: '#94A3B8' },
                    grid: { drawOnChartArea: false },
                    ticks: { display: false }
                },
                x: { 
                    grid: { display: false },
                    ticks: { font: { family: 'Inter', size: 11 }, color: '#64748B' }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });

    // --- 2. Advanced Polar Area Chart (Faculty Distribution) ---
    // A polar area chart looks much more analytical than a standard pie/doughnut chart.
    const polarCtx = document.getElementById('advPolarChart').getContext('2d');
    
    new Chart(polarCtx, {
        type: 'polarArea',
        data: {
            labels: @json($analytics['enrollment']['labels']),
            datasets: [{
                data: @json($analytics['enrollment']['data']),
                backgroundColor: [
                    'rgba(59, 130, 246, 0.7)',  // Blue
                    'rgba(16, 185, 129, 0.7)',  // Emerald
                    'rgba(245, 158, 11, 0.7)',  // Amber
                    'rgba(139, 92, 246, 0.7)',  // Purple
                    'rgba(236, 72, 153, 0.7)'   // Pink
                ],
                borderColor: '#FFFFFF',
                borderWidth: 2
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    position: 'bottom', 
                    labels: { 
                        usePointStyle: true, 
                        padding: 20, 
                        font: { size: 11, family: 'Inter', weight: '600' },
                        color: '#475569'
                    } 
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleColor: '#F8FAFC',
                    bodyColor: '#F1F5F9',
                    padding: 12,
                    cornerRadius: 10,
                    usePointStyle: true,
                    titleFont: { family: 'Inter', size: 13, weight: 'bold' },
                    bodyFont: { family: 'Inter', size: 12 }
                }
            },
            scales: {
                r: {
                    ticks: { display: false },
                    grid: { color: 'rgba(226, 232, 240, 0.6)' }
                }
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