<x-structure />
<x-header />
<style>
  .dashboard-container {
      padding: 20px;
    }
    .dashboard-card {
      border: none;
      border-radius: 16px;
      padding: 20px;
      color: #000;
      background-color: #fff;
      /* box-shadow: 0 4px 20px rgba(0,0,0,0.08); */
      transition: transform 0.2s ease;
    }
    .dashboard-card:hover {
      transform: translateY(-4px);
    }
    .card-icon {
      font-size: 2rem;
      opacity: 0.85;
    }
    .stat-number {
      font-size: 1.8rem;
      font-weight: bold;
    }
    /* Custom colors per card */
    /* .bg-opd { background: linear-gradient(135deg, #4facfe, #00f2fe); }
    .bg-ipd { background: linear-gradient(135deg, #43e97b, #38f9d7); }
    .bg-revenue { background: linear-gradient(135deg, #f093fb, #f5576c); }
    .bg-beds { background: linear-gradient(135deg, #fa709a, #fee140); } */
    .table-card {
      border-radius: 16px;
      /* box-shadow: 0 4px 20px rgba(0,0,0,0.05); */
      background: #fff;
    }
    .table thead th {
      background-color: #f1f3f6;
      border-bottom: none;
    }
    .shortcut-btn {
      border-radius: 12px;
      padding: 10px 16px;
      font-weight: 500;
    }
    .chart-placeholder {
      height: 250px;
      background: repeating-linear-gradient(
        45deg,
        #f8f9fa,
        #f8f9fa 10px,
        #e9ecef 10px,
        #e9ecef 20px
      );
      border-radius: 12px;
    }
</style>
<div class="container-fluid dashboard-container">
  
  <!-- Stats Row -->
  <div class="row mb-4">
    <div class="col-md-3">
      <div class="dashboard-card text-primary">
        <div class="d-flex justify-content-between">
          <div>
            <div class="stat-number">120</div>
            <div>OPD Patients Today</div>
          </div>
          <i class="fas fa-user-md card-icon"></i>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="dashboard-card text-warning">
        <div class="d-flex justify-content-between">
          <div>
            <div class="stat-number">45</div>
            <div>IPD Admissions</div>
          </div>
          <i class="fas fa-procedures card-icon"></i>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="dashboard-card text-danger">
        <div class="d-flex justify-content-between">
          <div>
            <div class="stat-number">₹85,000</div>
            <div>Today's Revenue</div>
          </div>
          <i class="fas fa-rupee-sign card-icon"></i>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="dashboard-card text-success">
        <div class="d-flex justify-content-between">
          <div>
            <div class="stat-number">8</div>
            <div>Beds Available</div>
          </div>
          <i class="fas fa-bed card-icon"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Graphs Row -->
  <div class="row mb-4">
    <div class="col-md-6">
      <div class="table-card p-3">
        <h5 class="mb-3">Patients Overview</h5>
        <div class="chart-placeholder"></div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="table-card p-3">
        <h5 class="mb-3">Revenue Overview</h5>
        <div class="chart-placeholder"></div>
      </div>
    </div>
  </div>

  <!-- Tables Row -->
  <div class="row mb-4">
    <div class="col-md-6">
      <div class="table-card p-3">
        <h5 class="mb-3">Today's OPD Appointments</h5>
        <table class="table table-sm">
          <thead>
            <tr>
              <th>Patient</th>
              <th>Doctor</th>
              <th>Time</th>
            </tr>
          </thead>
          <tbody>
            <tr><td>Rajesh Kumar</td><td>Dr. Mehta</td><td>09:30 AM</td></tr>
            <tr><td>Priya Sharma</td><td>Dr. Singh</td><td>10:00 AM</td></tr>
            <tr><td>Amit Verma</td><td>Dr. Gupta</td><td>10:30 AM</td></tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="col-md-6">
      <div class="table-card p-3">
        <h5 class="mb-3">Today's IPD Admissions</h5>
        <table class="table table-sm">
          <thead>
            <tr>
              <th>Patient</th>
              <th>Ward</th>
              <th>Bed</th>
            </tr>
          </thead>
          <tbody>
            <tr><td>Suman Devi</td><td>Ward A</td><td>Bed 3</td></tr>
            <tr><td>Mohit Yadav</td><td>Ward B</td><td>Bed 6</td></tr>
            <tr><td>Sunita Rao</td><td>Ward C</td><td>Bed 2</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Shortcuts -->
  <div class="row">
    <div class="col-12">
      <div class="table-card p-3">
        <h5 class="mb-3">Quick Actions</h5>
        <button class="btn btn-primary shortcut-btn"><i class="fas fa-user-plus"></i> Register Patient</button>
        <button class="btn btn-success shortcut-btn"><i class="fas fa-file-medical"></i> Generate OPD Slip</button>
        <button class="btn btn-warning shortcut-btn"><i class="fas fa-bed"></i> Admit Patient</button>
        <button class="btn btn-info shortcut-btn"><i class="fas fa-file-invoice"></i> Create Bill</button>
      </div>
    </div>
  </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('patientsChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
      datasets: [
        {
          label: 'OPD',
          data: [12, 15, 10, 18, 20, 16],
          borderColor: '#007bff',
          fill: false
        },
        {
          label: 'IPD',
          data: [5, 7, 6, 8, 7, 5],
          borderColor: '#28a745',
          fill: false
        }
      ]
    },
    options: { responsive: true }
  });
</script>
<x-footer />