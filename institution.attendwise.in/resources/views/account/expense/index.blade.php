<x-structure />
<x-header />
<style>
    #expenseChart{
        /* width: 100% !important; */
        /* height: 300px !important; */
    }
</style>
<main class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="fas fa-chart-line me-2"></i>Expense Analysis & Accounts Tracking</h3>
        <div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
                <i class="fas fa-plus"></i> Add Expense
            </button>
            <button class="btn btn-warning" onclick="renderTable()">
                <i class="fas fa-sync"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Add Expense Modal -->
    <div class="modal fade" id="addExpenseModal" tabindex="-1" aria-labelledby="addExpenseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-sm border-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="addExpenseModalLabel">
                        <i class="fas fa-file-invoice-dollar me-2"></i>Add New Expense
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addExpenseForm" data-callback="renderTable()" method="POST" action="{{$url['create']}}" class="modal-form msc-ord-form" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="expenseDate" class="form-label">Expense Date</label>
                                <input type="date" class="form-control" id="expenseDate" name="expense_date" required value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="expenseCategory" class="form-label">Category</label>
                                <select class="form-select form-control msc-searchable" id="expenseCategory" name="category" required>
                                    <option value="" selected disabled></option>
                                    <option value="salaries">Salaries</option>
                                    <option value="utilities">Utilities</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="procurement">Procurement</option>
                                    <option value="others">Others</option>
                                </select>
                            </div>
                            <div class="col-md-6 d-none" id="customCategoryDiv">
                                <label for="customCategory" class="form-label">Enter Category Name</label>
                                <input type="text" class="form-control" id="customCategory" name="custom_category" placeholder="Custom Category Name">
                            </div>
                            <div class="col-md-6">
                                <label for="expenseAmount" class="form-label">Amount (₹)</label>
                                <input type="number" class="form-control" id="expenseAmount" name="amount" min="1" step="0.01" placeholder="Enter amount" required>
                            </div>
                            <div class="col-md-6">
                                <label for="expenseAccount" class="form-label">Account</label>
                                <select class="form-select form-control msc-searchable" id="expenseAccount" name="account" required>
                                    <option value="" selected disabled></option>
                                    <option value="bank">Bank Account</option>
                                    <option value="cash">Cash</option>
                                    <option value="credit_card">Credit Card</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="expenseDescription" class="form-label">Description / Notes</label>
                                <textarea class="form-control" id="expenseDescription" name="description" rows="2" placeholder="Additional details (optional)"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="expenseStatus" class="form-label">Status</label>
                                <select class="form-select form-control msc-searchable" id="expenseStatus" name="status" required>
                                    <option value="" selected disabled></option>
                                    <option value="paid">Paid</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="expenseReference" class="form-label">Reference / Invoice No.</label>
                                <input type="text" class="form-control" id="expenseReference" name="reference" placeholder="Optional">
                            </div>
                            <div class="col-12">
                                <label for="expenseFile" class="form-label">Attach Bill / Receipt</label>
                                <input class="form-control" type="file" id="expenseFile" name="file" accept="image/*,application/pdf">
                                <div id="filePreview" class="mt-2 mb-1"></div>
                                <button type="button" class="btn btn-outline-danger btn-sm d-none" id="removeFileBtn">
                                    <i class="fas fa-times"></i> Remove File
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer ps-4 pe-4 pt-0 pb-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Expense
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center p-3">
                <i class="fas fa-wallet text-primary fa-2x mb-2"></i>
                <div class="text-muted">Total Expenses</div>
                <h4 class="mb-0" id="expensesTotal">₹ 0.00</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center p-3">
                <i class="fas fa-coins text-success fa-2x mb-2"></i>
                <div class="text-muted">Total Income</div>
                <h4 class="mb-0 text-success" id="paidTotal">₹ 0.00</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center p-3">
                <i class="fas fa-balance-scale text-info fa-2x mb-2"></i>
                <div class="text-muted">Net Balance</div>
                <h4 class="mb-0 text-info" id="netBalance">₹ 0.00</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center p-3">
                <i class="fas fa-file-invoice-dollar text-warning fa-2x mb-2"></i>
                <div class="text-muted">Pending Payments</div>
                <h4 class="mb-0 text-warning" id="pendingPayments">₹ 0.00</h4>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <form id="expenseFilterForm" class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label for="filterStartDate" class="form-label">Date From</label>
                    <input type="date" class="form-control" name="start_date" id="filterStartDate">
                </div>
                <div class="col-md-2">
                    <label for="filterEndDate" class="form-label">Date To</label>
                    <input type="date" class="form-control" name="end_date" id="filterEndDate">
                </div>
                <div class="col-md-3">
                    <label for="filterAccount" class="form-label">Payment Mode</label>
                    <select class="form-select msc-searchable" name="account" id="filterAccount">
                        <option value="">All Accounts</option>
                        <option value="bank">Bank Account</option>
                        <option value="cash">Cash</option>
                        <option value="credit">Credit Card</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterCategory" class="form-label">Category</label>
                    <select class="form-select msc-searchable" name="category" id="filterCategory">
                        <option value="">All Categories</option>
                        <option value="salaries">Salaries</option>
                        <option value="procurement">Procurement</option>
                        <option value="utilities">Utilities</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="revenue">Revenue</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filterReference" class="form-label">Reference</label>
                    <input type="text" class="form-control" name="reference" id="filterReference" placeholder="Invoice ID, Memo">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0"><i class="fas fa-table me-2"></i>Expense & Account Transactions</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="accountsTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Account</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Reference</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Expense Category Breakdown</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="expenseChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Expense Mode Breakdown</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="expensePaymentModeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<x-footer />

<!-- Scripts -->
<script src="asstes/js/chart.js"></script>
<script>
     const fileInput = document.getElementById('expenseFile');
  const previewDiv = document.getElementById('filePreview');
  const removeFileBtn = document.getElementById('removeFileBtn');


  fileInput.addEventListener('change', function() {
    previewDiv.innerHTML = "";
    removeFileBtn.classList.add('d-none');
    const file = this.files[0];
    if (!file) return;


    const fileType = file.type;
    removeFileBtn.classList.remove('d-none'); // Show remove button


    if (fileType.startsWith('image/')) {
      // Image preview
      const img = document.createElement('img');
      img.src = URL.createObjectURL(file);
      img.onload = () => URL.revokeObjectURL(img.src);
      img.style.maxHeight = "170px";
      img.style.maxWidth = "240px";
      img.classList.add('border', 'rounded', 'shadow');
      previewDiv.appendChild(img);
    } else {
        const tmp_uri = URL.createObjectURL(file);
      previewDiv.innerHTML = '<a href="'+tmp_uri+'" target="_blank" class="text-warning small"> Click here to view file.</a>';
    }
  });


  removeFileBtn.addEventListener('click', function() {
    fileInput.value = '';
    previewDiv.innerHTML = '';
    removeFileBtn.classList.add('d-none');
  });

    var accountsData = [
        { date:'2025-08-15', type:'Expense', category:'Salaries', account:'Bank Account', amount:120000, description:'Monthly salary payout', status:'Paid', reference:'SAL-2025-08' },
        { date:'2025-08-17', type:'Income', category:'Revenue', account:'Bank Account', amount:90000, description:'Patient billing', status:'Received', reference:'RCPT-2025-08-17' },
        { date:'2025-08-19', type:'Expense', category:'Utilities', account:'Cash', amount:15000, description:'Electricity bill', status:'Pending', reference:'UTIL-2025-08' }
    ];

    $("#expenseFilterForm").on('submit', function(e){
        e.preventDefault();
        const filterData = $(this).serializeArray().reduce((obj, item) => (obj[item.name] = item.value, obj), {});
        renderTable(filterData);
    });
    function renderTable(filter = {}) {
        const tbody = document.querySelector("#accountsTable tbody");
        tbody.innerHTML = '';
        $("#addExpenseModal").modal('hide');
        $.ajax({
            type: "POST",
            url: "account/expense/records/fetch",
            data: {
                "_token": "{{ csrf_token() }}",
                ...filter
                // Add filter parameters here
            },
            beforeSend: function() {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>';
            },
            success: function (response) {
                
                setTimeout(() => {
                    if(response.status === 'success') {
                        tbody.innerHTML = response.content || '<tr><td colspan="8" class="text-center py-4 text-muted">No records found.</td></tr>';
                        updateSummary(response.raw_data.data || []);
                        renderChart(response.raw_data.data || []);
                    } else {
                        tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4 text-danger">Error loading data. Please try again.</td></tr>';
                    }
                }, 500);
            }, error: function() {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4 text-danger">Error loading data. Please try again.</td></tr>';
            }
        });
    }

    function updateSummary(exp_data) {
        let expenses = 0, paid_expense = 0, pending_expense = 0;
        console.log(exp_data)
        exp_data.forEach(txn => {
            expenses += Number(txn.amount);
            if(txn.status === 'paid') paid_expense += Number(txn.amount);
            if(txn.status === 'pending') pending_expense += Number(txn.amount);
        });
        document.getElementById('expensesTotal').textContent = `₹ ${(expenses.toFixed(2))}`;
        document.getElementById('paidTotal').textContent = `₹ ${(paid_expense.toFixed(2))}`;
        // document.getElementById('netBalance').textContent = `₹ ${(income - (expenses).toFixed(2))}`;
        document.getElementById('pendingPayments').textContent = `₹ ${(pending_expense.toFixed(2))}`;
    }

    function renderChart(exp_data) {
        const ctx = document.getElementById('expenseChart').getContext('2d');
        const payment_mode_ctx = document.getElementById('expensePaymentModeChart').getContext('2d');
        const dataMap = {};
        const payment_mode_dataMap = {};
        exp_data.forEach(txn => {
                dataMap[txn.category] = (dataMap[txn.category] || 0) + Number(txn.amount);
                payment_mode_dataMap[txn.account] = (payment_mode_dataMap[txn.account] || 0) + Number(txn.amount);
        });
        const labels = Object.keys(dataMap);
        const data = Object.values(dataMap);
        const payment_mode_labels = Object.keys(payment_mode_dataMap);
        const payment_mode_data = Object.values(payment_mode_dataMap);
        console.log(payment_mode_dataMap)
        if(window.expenseChartInstance) window.expenseChartInstance.destroy();
        if(window.expensePaymentModeChartInstance) window.expensePaymentModeChartInstance.destroy();
        window.expenseChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: ['#0d6efd','#198754','#ffc107','#dc3545','#20c997','#6f42c1','#fd7e14','#0dcaf0','#6610f2','#6c757d'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'right' } }
            }
        });
        window.expensePaymentModeChartInstance = new Chart(payment_mode_ctx, {
            type: 'bar',
            data: {
                labels: payment_mode_labels,
                datasets: [{
                    data: payment_mode_data,
                    backgroundColor: ['#0d6efd','#198754','#ffc107','#dc3545','#20c997','#6f42c1','#fd7e14','#0dcaf0','#6610f2','#6c757d'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'right' } }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function(){
        setTimeout(() => {
           renderTable();
        }, 500);
    });

    // Add your JS handlers for filtering, adding expenses, etc.

</script>
