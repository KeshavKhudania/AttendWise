<x-structure />
<x-header />

<style>
/* ---------------------------------------------------
   Advanced Analytics Command Center Design
----------------------------------------------------- */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

.adv-dashboard {
    background-color: #F8FAFC;
    padding: 2rem 1.5rem 4rem;
    font-family: 'Inter', sans-serif;
    min-height: 100vh;
}

/* Header */
.adv-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 2rem;
    border-bottom: 1px solid #E2E8F0;
    padding-bottom: 1.5rem;
}
.adv-title {
    font-size: 2rem;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 0.25rem;
    letter-spacing: -0.02em;
}
.adv-subtitle {
    font-size: 0.95rem;
    color: #64748B;
    font-weight: 500;
}

/* Common Card */
.adv-card {
    background: #FFFFFF;
    border-radius: 12px;
    border: 1px solid #E2E8F0;
    box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.05), 0 2px 4px -1px rgba(15, 23, 42, 0.03);
    padding: 1.5rem;
    height: 100%;
    position: relative;
    display: flex;
    flex-direction: column;
}
.adv-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}
.adv-card-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: #1E293B;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.adv-card-title i {
    color: #3B82F6;
}

/* KPIs */
.adv-kpi {
    position: relative;
    overflow: hidden;
    padding: 1.5rem;
    border-radius: 12px;
    background: #fff;
    border: 1px solid #E2E8F0;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.adv-kpi:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
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
    font-size: 2.25rem;
    font-weight: 800;
    color: #0F172A;
    line-height: 1;
    margin-bottom: 0.5rem;
}
.kpi-delta {
    font-size: 0.85rem;
    font-weight: 600;
}
.kpi-icon-bg {
    position: absolute;
    right: -15px;
    bottom: -20px;
    font-size: 6rem;
    color: #F8FAFC;
    z-index: 1;
    transform: rotate(-10deg);
}

/* Top Buttons */
.btn-adv {
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    color: #475569;
    font-weight: 600;
    padding: 0.6rem 1.25rem;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: all 0.2s;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
}
.btn-adv:hover {
    background: #F1F5F9;
    color: #0F172A;
}
.btn-adv-primary {
    background: #2563EB;
    border-color: #2563EB;
    color: #FFFFFF;
    box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
}
.btn-adv-primary:hover {
    background: #1D4ED8;
    border-color: #1D4ED8;
    color: #FFFFFF;
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
    padding: 1rem;
    border-bottom: 2px solid #E2E8F0;
    background: #F8FAFC;
}
.data-table th:first-child { border-top-left-radius: 8px; }
.data-table th:last-child { border-top-right-radius: 8px; }
.data-table td {
    padding: 1rem;
    font-size: 0.9rem;
    color: #334155;
    border-bottom: 1px solid #F1F5F9;
    vertical-align: middle;
}
.data-table tbody tr:hover td {
    background: #F8FAFC;
}
.status-pill {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
}
.status-pill.active { background: #DCFCE7; color: #15803D; }
.status-pill.pending { background: #FEF3C7; color: #B45309; }
</style>

<div class="adv-dashboard container-fluid px-4">

    <!-- Header Zone -->
    <div class="adv-header flex-column flex-md-row">
        <div>
            <h1 class="adv-title">Attendance System</h1>
            <p class="adv-subtitle">Dedicated view for real-time attendance activities and session management.</p>
        </div>
        <div class="d-flex gap-3 mt-3 mt-md-0">
            <button class="btn-adv btn-adv-primary">
                <i class="fas fa-file-export"></i>
                <span>Export Reports</span>
            </button>
        </div>
    </div>

    <!-- KPI Metrics -->
    <div class="row g-4 mb-4">
        <div class="col-xl-4 col-lg-4">
            <div class="adv-kpi kpi-success">
                <i class="fas fa-user-check kpi-icon-bg"></i>
                <div class="kpi-content">
                    <div class="kpi-label">Today's Presence</div>
                    <div class="kpi-value">{{ number_format($today_attendance) }}</div>
                    <div class="kpi-delta d-flex align-items-center gap-1">
                        <span class="text-muted" style="font-size: 0.8rem; font-weight: 500;">Students marked present today</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4">
            <div class="adv-kpi kpi-warning">
                <i class="fas fa-user-times kpi-icon-bg"></i>
                <div class="kpi-content">
                    <div class="kpi-label">Today's Absence</div>
                    <div class="kpi-value">{{ number_format($today_absent) }}</div>
                    <div class="kpi-delta d-flex align-items-center gap-1">
                        <span class="text-muted" style="font-size: 0.8rem; font-weight: 500;">Students marked absent today</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4">
            <div class="adv-kpi kpi-purple">
                <i class="fas fa-chalkboard-teacher kpi-icon-bg"></i>
                <div class="kpi-content">
                    <div class="kpi-label">Active Sessions</div>
                    <div class="kpi-value">{{ number_format($total_sessions_today) }}</div>
                    <div class="kpi-delta d-flex align-items-center gap-1">
                        <span class="text-muted" style="font-size: 0.8rem; font-weight: 500;">Total classes conducted today</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Comprehensive Data Table -->
    <div class="row g-4">
        <div class="col-12">
            <div class="adv-card p-0 overflow-hidden">
                <div class="adv-card-header px-4 pt-4 mb-2">
                    <div class="adv-card-title">
                        <i class="fas fa-list-alt"></i> Recent Attendance Sessions
                    </div>
                </div>
                <div class="table-responsive px-4 pb-4">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Faculty</th>
                                <th>Schedule ID</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Geofencing</th>
                                <th class="text-end">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_sessions as $session)
                            <tr>
                                <td class="fw-bold">{{ $session->faculty_name ?? 'Unknown Faculty' }}</td>
                                <td>#{{ $session->schedule_id }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="far fa-calendar text-muted"></i>
                                        <span>{{ date('M d, Y', strtotime($session->date)) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="far fa-clock text-muted"></i>
                                        <span>{{ date('h:i A', strtotime($session->start_time)) }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($session->is_geofencing)
                                        <span class="badge bg-light text-success border border-success"><i class="fas fa-map-marker-alt"></i> Enabled</span>
                                    @else
                                        <span class="badge bg-light text-secondary border"><i class="fas fa-map-marker-slash"></i> Disabled</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($session->status == 'completed')
                                        <span class="status-pill active">Completed</span>
                                    @elseif($session->status == 'active')
                                        <span class="status-pill pending" style="background:#DBEAFE;color:#1D4ED8;">Active</span>
                                    @else
                                        <span class="status-pill pending">{{ ucfirst($session->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-clipboard-list fa-3x mb-3 opacity-50"></i>
                                    <p class="mb-0 fw-bold">No recent attendance sessions found.</p>
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

<x-footer />
