<x-structure />
<x-header />
<style>
#patientSummaryCard {
    background: linear-gradient(135deg,#f8fafc 60%,#e9ecef 100%);
    border-radius: 1rem;
}
#patientSummaryCard th, #patientSummaryCard td { font-size: 0.95rem; }
</style>

<main class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="fas fa-coins me-2"></i>Revenue & Transactions</h3>
        <div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#revenueModal">
                <i class="fas fa-plus"></i> Record Revenue
            </button>
            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#opdipdTxnModal">
                <i class="fas fa-notes-medical"></i> Record OPD/IPD Transaction
            </button>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <i class="fas fa-rupee-sign text-primary fa-2x mb-2"></i>
                <div class="text-muted">Total Revenue</div>
                <h4 id="kpiTotalRevenue" class="mb-0">₹ 0</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <i class="fas fa-chart-line text-success fa-2x mb-2"></i>
                <div class="text-muted">This Month</div>
                <h4 id="kpiMonthRevenue" class="mb-0 text-success">₹ 0</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <i class="fas fa-receipt text-warning fa-2x mb-2"></i>
                <div class="text-muted">Outstanding</div>
                <h4 id="kpiOutstanding" class="mb-0 text-warning">₹ 0</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <i class="fas fa-percentage text-info fa-2x mb-2"></i>
                <div class="text-muted">MoM Growth</div>
                <h4 id="kpiMoM" class="mb-0 text-info">0%</h4>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form id="revenueFilters" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Date From</label>
                    <input type="date" class="form-control" name="start_date">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date To</label>
                    <input type="date" class="form-control" name="end_date">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Source</label>
                    <select class="form-select msc-searchable" name="source">
                        <option value="">All</option>
                        <option>Consultation</option>
                        <option>Lab Tests</option>
                        <option>Pharmacy</option>
                        <option>Inpatient</option>
                        <option>Surgery</option>
                        <option>Insurance</option>
                        <option>Other</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select class="form-select msc-searchable" name="status">
                        <option value="">All</option>
                        <option>Received</option>
                        <option>Outstanding</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary w-100" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="fas fa-chart-area me-2"></i>Revenue Trend</h5>
                </div>
                <div class="card-body">
                    <canvas id="trendChart" style="min-height:320px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Source Breakdown</h5>
                </div>
                <div class="card-body">
                    <canvas id="breakdownChart" style="min-height:320px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0"><i class="fas fa-table me-2"></i>Transactions</h5>
                <div class="input-group" style="max-width:320px;">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input id="tableSearch" type="text" class="form-control" placeholder="Search in table...">
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 msc-smart-table" id="revenueTable">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Source</th>
                            <th>Patient/Party</th>
                            <th>Amount (₹)</th>
                            <th>Status</th>
                            <th>Reference</th>
                        </tr>
                    </thead>
                    <tbody><!-- Filled by JS --></tbody>
                </table>
            </div>
        </div>
    </div>
</main>

{{-- Record OPD/IPD Transaction Modal --}}
<div class="modal fade" id="opdipdTxnModal" tabindex="-1" aria-labelledby="opdipdTxnLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-sm">
            <div class="modal-header">
                <h5 class="modal-title" id="opdipdTxnLabel"><i class="fas fa-notes-medical me-2"></i>Record OPD/IPD Transaction</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="opdipdTxnForm" class="modal-form msc-ord-form" enctype="multipart/form-data" autocomplete="off">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">OPD/IPD Number</label>
                                    <input type="text" class="form-control" id="optNumberInput" name="opt_number" placeholder="IPD/OPD Number (IPD10102)" autocomplete="off" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Date</label>
                                    <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Patient Name</label>
                                    <input type="text" class="form-control" name="patient_name" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Patient ID / UHID</label>
                                    <input type="text" class="form-control" name="patient_id" placeholder="Unique hospital ID">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Doctor</label>
                                    <input type="text" class="form-control" name="doctor" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Department</label>
                                    <select class="form-select msc-searchable" name="department" required>
                                        <option value="" disabled selected></option>
                                        <option>General Medicine</option>
                                        <option>Orthopedics</option>
                                        <option>Pediatrics</option>
                                        <option>Ophthalmology</option>
                                        <option>Gynecology</option>
                                        <option>Surgery</option>
                                        <option>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Bill No. / Visit No.</label>
                                    <input type="text" class="form-control" name="bill_no" placeholder="Bill or Visit reference" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Service/Procedure</label>
                                    <input type="text" class="form-control" name="service" placeholder="E.g. Consultation, X-Ray, Surgery" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Amount (₹)</label>
                                    <input type="number" step="0.01" class="form-control" name="amount" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Status</label>
                                    <select class="form-select msc-searchable" name="status" required>
                                        <option value="" disabled selected></option>
                                        <option>Received</option>
                                        <option>Outstanding</option>
                                        <option>Refund</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Payment Mode</label>
                                    <select class="form-select msc-searchable" name="payment_mode">
                                        <option value="" disabled selected></option>
                                        <option>Bank</option>
                                        <option>Cash</option>
                                        <option>UPI</option>
                                        <option>Card</option>
                                        <option>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Notes</label>
                                    <input type="text" class="form-control" name="notes" placeholder="Optional">
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Attach Bill / Receipt</label>
                                    <div class="input-group">
                                        <input class="form-control" type="file" id="opdipdFileInput" name="file" accept="image/*,application/pdf">
                                        <button type="button" class="btn btn-outline-secondary d-none" id="opdipdViewFileBtn" tabindex="-1" title="Preview" style="border-radius:0 0.375rem 0.375rem 0;">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
        <!-- Patient Summary Card -->
                        <div id="patientSummaryCard" class="card border-0 shadow-sm p-3 mb-2 d-none" style="min-height:180px;">
                            <h6 class="text-primary mb-2"><i class="fas fa-user me-2"></i>Patient Details</h6>
                            <table class="table table-sm mb-2">
                                <tbody id="patientSummaryTable">
                                    <!-- Filled from JS -->
                                    <tr><td colspan="2" class="text-center text-muted">No patient loaded</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
                    <button class="btn btn-primary" type="submit"><i class="fas fa-save me-1"></i>Save Transaction</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Record Revenue Modal -->
<div class="modal fade" id="revenueModal" tabindex="-1" aria-labelledby="revenueModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-sm">
            <div class="modal-header">
                <h5 class="modal-title" id="revenueModalLabel"><i class="fas fa-plus-circle me-2"></i>Record Revenue</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="recordRevenueForm" class="modal-form msc-ord-form" enctype="multipart/form-data" autocomplete="off">
                <div class="modal-body">
                    <!-- Inline search bar if you need -->
                    <div class="mb-3">
                        <label class="form-label">Search Invoice/Party/Ref</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input id="revSearchInput" type="text" class="form-control" placeholder="Type to search...">
                        </div>
                        <div id="revSearchResults" class="list-group mt-2 d-none"></div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control" name="revenue_date" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Source</label>
                            <select class="form-select msc-searchable" name="source" required>
                                <option value="" disabled selected>Select source</option>
                                <option>Consultation</option>
                                <option>Lab Tests</option>
                                <option>Pharmacy</option>
                                <option>Inpatient</option>
                                <option>Surgery</option>
                                <option>Insurance</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select class="form-select msc-searchable" name="status" required>
                                <option value="" disabled selected>Select status</option>
                                <option>Received</option>
                                <option>Outstanding</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Patient/Party</label>
                            <input type="text" class="form-control" name="party" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Amount (₹)</label>
                            <input type="number" class="form-control" name="amount" min="0" step="0.01" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Reference No</label>
                            <input type="text" class="form-control" name="reference">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Mode</label>
                            <select class="form-select msc-searchable" name="payment_mode">
                                <option value="" disabled selected>Select</option>
                                <option>Bank</option>
                                <option>Cash</option>
                                <option>UPI</option>
                                <option>Card</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Notes</label>
                            <input type="text" class="form-control" name="notes" placeholder="Optional">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Attach Receipt/Bill</label>
                            <input class="form-control" type="file" name="file" accept="image/*,application/pdf">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
                    <button class="btn btn-primary" type="submit"><i class="fas fa-save me-1"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Chart.js and UI Scripts -->
<script src="assets/js/chart.js"></script>
<script>
    $("#optNumberInput").on("blur", function() {
    const number = $(this).val().trim();
    if (!number) return;

    $.ajax({
        method: "POST",
        url: "{{ route('api.fetch.opd.ipd.details') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}"
            number: number,
        },
        success: function(data) {
            const $card  = $("#patientSummaryCard").removeClass("d-none");
            const $table = $("#patientSummaryTable");
            if (data.status === "success" && data.patient) {
                $table.html(`
                    <tr><th>Name</th><td>${data.patient.name}</td></tr>
                    <tr><th>Gender</th><td>${data.patient.gender}</td></tr>
                    <tr><th>Age</th><td>${data.patient.age}</td></tr>
                    <tr><th>Mobile</th><td>${data.patient.mobile}</td></tr>
                    <tr><th>Admit Date</th><td>${data.patient.admit_date}</td></tr>
                    <tr><th>Address</th><td>${data.patient.address}</td></tr>
                    <tr><th>Outstanding</th>
                        <td><span class="fw-bold text-danger">₹ ${parseFloat(data.patient.outstanding).toLocaleString('en-IN', {minimumFractionDigits:2})}</span></td>
                    </tr>
                `);
            } else {
                $table.html(`<tr><td colspan="2" class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>Not found</td></tr>`);
            }
        },
        error: function(xhr) {
            $("#patientSummaryCard").removeClass("d-none");
            $("#patientSummaryTable").html(`<tr><td colspan="2" class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>Server Error</td></tr>`);
        }
    });
});
    const opdFileInput = document.getElementById('opdipdFileInput');
const opdViewFileBtn = document.getElementById('opdipdViewFileBtn');
let lastOpdFileURL = null;

opdFileInput.addEventListener('change', function() {
    // Clean up temp URL if exists
    if (lastOpdFileURL) {
        URL.revokeObjectURL(lastOpdFileURL);
        lastOpdFileURL = null;
    }
    const file = this.files[0];
    if (file) {
        lastOpdFileURL = URL.createObjectURL(file);
        opdViewFileBtn.classList.remove('d-none');
    } else {
        opdViewFileBtn.classList.add('d-none');
    }
});

opdViewFileBtn.addEventListener('click', function() {
    if (lastOpdFileURL) {
        window.open(lastOpdFileURL, '_blank');
    }
});

// Optional: On modal close/reset, hide button and revoke object
document.getElementById('opdipdTxnModal').addEventListener('hidden.bs.modal', function() {
    opdViewFileBtn.classList.add('d-none');
    if (lastOpdFileURL) { URL.revokeObjectURL(lastOpdFileURL); lastOpdFileURL = null; }
    opdFileInput.value = '';
});
let revenues = [
    {revenue_date:'2025-09-01', source:'Consultation', party:'John Doe', amount:2500, status:'Received', reference_no:'RCPT-2001'},
    {revenue_date:'2025-09-02', source:'Lab Tests', party:'Jane Smith', amount:3900, status:'Received', reference_no:'RCPT-2002'},
    {revenue_date:'2025-09-05', source:'Surgery', party:'David Roy', amount:42000, status:'Outstanding', reference_no:'INV-2003'},
    {revenue_date:'2025-09-06', source:'Pharmacy', party:'Emily Clark', amount:900, status:'Received', reference_no:'RCPT-2004'},
    {revenue_date:'2025-09-07', source:'Insurance', party:'LIC Health', amount:10000, status:'Outstanding', reference_no:'INV-2005'},
];

function renderKPIs() {
    const total = revenues.reduce((s,t)=>s+Number(t.amount),0);
    const ym = new Date().toISOString().slice(0,7);
    const thisMonth = revenues.filter(t=>t.revenue_date.startsWith(ym)).reduce((s,t)=>s+Number(t.amount),0);
    const outstanding = revenues.filter(t=>t.status==='Outstanding').reduce((s,t)=>s+Number(t.amount),0);
    const mom = 0; // Replace with a real calculation for MoM
    document.getElementById('kpiTotalRevenue').textContent = `₹ ${total.toLocaleString('en-IN')}`;
    document.getElementById('kpiMonthRevenue').textContent = `₹ ${thisMonth.toLocaleString('en-IN')}`;
    document.getElementById('kpiOutstanding').textContent = `₹ ${outstanding.toLocaleString('en-IN')}`;
    document.getElementById('kpiMoM').textContent = `${mom}%`;
}

function renderTable(term='') {
    const tbody = document.querySelector("#revenueTable tbody");
    tbody.innerHTML = '';
    const filter = term.trim().toLowerCase();
    revenues.forEach(r => {
        let hay = `${r.revenue_date} ${r.source} ${r.party} ${r.status} ${r.reference_no}`.toLowerCase();
        if (filter && !hay.includes(filter)) return;
        let badge = r.status==='Received' ? 'success' : 'warning';
        tbody.innerHTML += `
            <tr>
                <td>${r.revenue_date}</td>
                <td>${r.source}</td>
                <td>${r.party}</td>
                <td>₹ ${Number(r.amount).toLocaleString('en-IN')}</td>
                <td><span class="badge bg-${badge}">${r.status}</span></td>
                <td>${r.reference_no}</td>
            </tr>
        `;
    });
}

// Chart rendering with destroy pattern
let trendChart, breakdownChart;
function renderTrendChart(){
    const map = {};
    revenues.forEach(r => map[r.revenue_date] = (map[r.revenue_date]||0) + Number(r.amount));
    const labels = Object.keys(map).sort();
    const data = labels.map(d => map[d]);
    const ctx = document.getElementById('trendChart').getContext('2d');
    if (trendChart) trendChart.destroy();
    trendChart = new Chart(ctx, {
        type:'line',
        data:{labels, datasets:[{label:'Revenue', data, borderColor: '#0d6efd', backgroundColor: 'rgba(13,110,253,0.08)', fill:true, tension:0.3}]},
        options:{responsive:true, plugins:{legend:{display:false}}}
    });
}

function renderBreakdownChart(){
    const map = {};
    revenues.forEach(r => map[r.source] = (map[r.source]||0) + Number(r.amount));
    const labels = Object.keys(map);
    const data   = Object.values(map);
    const ctx    = document.getElementById('breakdownChart').getContext('2d');
    if (breakdownChart) breakdownChart.destroy();
    breakdownChart = new Chart(ctx, {
        type:'doughnut',
        data:{labels, datasets:[{data, backgroundColor:['#0d6efd','#20c997','#ffc107','#dc3545','#198754','#6610f2','#fd7e14'],borderWidth:1}]},
        options:{responsive:true, plugins:{legend:{position:'right'}}}
    });
}

// Table search
document.getElementById('tableSearch').addEventListener('input', function(e){
    renderTable(e.target.value);
});

// Filters (connect to backend for real app)
document.getElementById('revenueFilters').addEventListener('submit', function(e){
    e.preventDefault();
    renderKPIs();
    renderTable();
    renderTrendChart();
    renderBreakdownChart();
});

// Modal live search
document.getElementById('revSearchInput').addEventListener('input', function(e){
    const box = document.getElementById('revSearchResults');
    let q = e.target.value.toLowerCase().trim();
    box.innerHTML = '';
    let found = revenues.filter(r=>`${r.party}${r.reference_no}`.toLowerCase().includes(q)).slice(0,5);
    if (!q || !found.length) return box.classList.add('d-none');
    found.forEach(r => {
        let btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'list-group-item list-group-item-action';
        btn.textContent = `${r.party} • ${r.reference_no} • ₹${Number(r.amount).toLocaleString('en-IN')}`;
        btn.onclick = function(){
            const f = document.getElementById('recordRevenueForm');
            f.elements['party'].value = r.party;
            f.elements['reference'].value = r.reference_no;
            f.elements['amount'].value = r.amount;
            box.classList.add('d-none');
        }
        box.appendChild(btn);
    });
    box.classList.remove('d-none');
});

// Modal form submission (demo—wire to backend in real app)
document.getElementById('recordRevenueForm').addEventListener('submit', function(e){
    e.preventDefault();
    const f = e.target;
    revenues.unshift({
        revenue_date: f.elements['revenue_date'].value,
        source: f.elements['source'].value,
        party: f.elements['party'].value,
        amount: parseFloat(f.elements['amount'].value || '0'),
        status: f.elements['status'].value,
        reference_no: f.elements['reference'].value,
    });
    renderKPIs(); renderTable(); renderTrendChart(); renderBreakdownChart();
    bootstrap.Modal.getInstance(document.getElementById('revenueModal')).hide();
    f.reset();
    document.getElementById('revSearchResults').classList.add('d-none');
});


document.addEventListener('DOMContentLoaded', function() {
    renderKPIs();
    renderTable();
    renderTrendChart();
    renderBreakdownChart();
});
</script>
<x-footer />