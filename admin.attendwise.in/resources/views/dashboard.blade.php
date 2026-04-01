<x-structure/>
<x-header/>
    <div class="container-fluid">

<div class="row my-3">
  @foreach ($kpis as $item)
      <x-kpi-card :icon="$item['icon']" :color="$item['color']" :title="$item['name']" :value="$item['count']" :delta="'1.5%'" />
  @endforeach
</div>


  <!-- Charts -->
  <div class="row g-4 mb-4">
    <div class="col-md-6">
      <div class="card p-3">
        <h6 class="mb-3">Revenue Trend</h6>
        <canvas id="revenueChart" height="150"></canvas>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card p-3">
        <h6 class="mb-3">Patients by Department</h6>
        <canvas id="deptChart" height="150"></canvas>
      </div>
    </div>
  </div>

  <!-- Tables -->
  <div class="row g-4">
    <div class="col-md-6">
      <div class="card p-3">
        <h6 class="mb-3">Recent OPD Patients</h6>
        <table class="table table-sm">
          <thead>
            <tr>
              <th>UHID</th>
              <th>Name</th>
              <th>Dept</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <tr><td>UH001</td><td>Rahul Sharma</td><td>Cardiology</td><td>14-Aug-25</td></tr>
            <tr><td>UH002</td><td>Anita Verma</td><td>ENT</td><td>14-Aug-25</td></tr>
            <tr><td>UH003</td><td>Vikram Singh</td><td>Dermatology</td><td>13-Aug-25</td></tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card p-3">
        <h6 class="mb-3">Recent IPD Patients</h6>
        <table class="table table-sm">
          <thead>
            <tr>
              <th>UHID</th>
              <th>Name</th>
              <th>Room</th>
              <th>Admit Date</th>
            </tr>
          </thead>
          <tbody>
            <tr><td>UH010</td><td>Sunita Mehra</td><td>102-A</td><td>12-Aug-25</td></tr>
            <tr><td>UH011</td><td>Ajay Kumar</td><td>205-B</td><td>11-Aug-25</td></tr>
            <tr><td>UH012</td><td>Ravi Gupta</td><td>301-C</td><td>10-Aug-25</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script>
    const ctx1 = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx1, {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        datasets: [{
          label: 'Revenue (₹)',
          data: [120000, 150000, 130000, 180000, 170000, 200000, 190000],
          borderColor: '#28a745',
          backgroundColor: 'rgba(40,167,69,0.1)',
          fill: true,
          tension: 0.3
        }]
      }
    });

    const ctx2 = document.getElementById('deptChart').getContext('2d');
    new Chart(ctx2, {
      type: 'doughnut',
      data: {
        labels: ['Cardiology', 'ENT', 'Orthopedics', 'Dermatology', 'General'],
        datasets: [{
          data: [120, 80, 90, 60, 150],
          backgroundColor: ['#007bff', '#ffc107', '#28a745', '#dc3545', '#17a2b8']
        }]
      }
    });
  </script>
    </div>
<x-footer/>
       