@if ($layout==1)
<!DOCTYPE html>
<html lang="en">
<head>
    <base href="{{env("APP_URL")}}">
    <meta name="auth_token" content="{{csrf_token()}}">
  <meta charset="UTF-8">
  <title>Hospital Payment Receipt</title>
  <style>
    :root {
      --primary: #036EAD;
      --primary-light: #178acd;
      --success: #10b981;
      --warning: #f59e0b;
      --warning-light: #ffb637;
      --danger: #E2021C;
      --danger-light: #e32c42;
      --theme-dark: #036EAD;
      --theme-grey-hover: #eef6ff50;
      --theme-white: #fff;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f0f4f8;
      padding: 20px;
      color: #212529;
    }

    .receipt-container {
      max-width: 850px;
      margin: auto;
      background-color: var(--theme-white);
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .hospital-header {
      display: flex;
      align-items: center;
      border-bottom: 3px solid var(--primary);
      padding-bottom: 15px;
      margin-bottom: 25px;
    }

    .hospital-logo {
      width: 80px;
      height: 80px;
      margin-right: 20px;
      border-radius: 8px;
      object-fit: contain;
    }

    .hospital-info h2 {
      margin: 0;
      font-size: 26px;
      color: var(--primary);
    }

    .hospital-info p {
      margin: 2px 0;
      font-size: 14px;
    }

    .section {
      margin-bottom: 25px;
    }

    .section h3 {
      margin-bottom: 10px;
      font-size: 18px;
      color: var(--primary);
      border-left: 5px solid var(--primary);
      padding-left: 10px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    .details td {
      padding: 10px;
      border: 1px solid #e1e5e9;
      font-size: 15px;
      background-color: var(--theme-white);
    }

    .amount {
      background-color: #e6fdf4;
      font-weight: bold;
      font-size: 18px;
      color: var(--success);
      text-align: right;
    }

    .footer {
      text-align: center;
      font-size: 13px;
      margin-top: 30px;
      color: #6c757d;
    }

    @media print {
      body {
        background: none;
        padding: 0;
      }

      .receipt-container {
        box-shadow: none;
        border-radius: 0;
      }
    }
  </style>
</head>
<body>

<div class="receipt-container">
  <div class="hospital-header">
    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/89/Red_Cross.svg/768px-Red_Cross.svg.png" alt="Hospital Logo" class="hospital-logo">
    <div class="hospital-info">
      <h2>CityCare Multispeciality Hospital</h2>
      <p>123 Health Street, Wellness Nagar, New Delhi - 110001</p>
      <p>Phone: +91-9876543210 | Email: info@citycare.com</p>
    </div>
  </div>

  <div class="section">
    <h3>Receipt Details</h3>
    <table class="details">
      <tr>
        <td><strong>Receipt No:</strong> RCPT-20250731001</td>
        <td><strong>Date:</strong> 31-Jul-2025</td>
      </tr>
      <tr>
        <td><strong>Patient Name:</strong> Ramesh Kumar</td>
        <td><strong>UHID:</strong> UHID-456789</td>
      </tr>
      <tr>
        <td><strong>Department:</strong> General Medicine</td>
        <td><strong>Doctor:</strong> Dr. Neha Sharma</td>
      </tr>
    </table>
  </div>

  <div class="section">
    <h3>Payment Information</h3>
    <table class="details">
      <tr>
        <td><strong>Payment Mode:</strong> UPI</td>
        <td><strong>Transaction ID:</strong> TXN9876543210</td>
      </tr>
      <tr>
        <td colspan="2"><strong>Description:</strong> Consultation Charges</td>
      </tr>
      <tr>
        <td colspan="2" class="amount">Total Amount Paid: ₹500.00</td>
      </tr>
    </table>
  </div>

  <div class="footer">
    This is a system-generated receipt and does not require a signature. Please keep it for future reference.
  </div>
</div>

</body>
</html>

@elseif($layout == 2)
    <!DOCTYPE html>
<html lang="en">
<head>
    <base href="{{env("APP_URL")}}">
    <meta name="auth_token" content="{{csrf_token()}}">
  <meta charset="UTF-8">
  <title>Hospital Payment Receipt</title>
  <style>
    :root {
      --primary: #036EAD;
      --primary-light: #178acd;
      --success: #10b981;
      --warning: #f59e0b;
      --warning-light: #ffb637;
      --danger: #E2021C;
      --danger-light: #e32c42;
      --theme-dark: #036EAD;
      --theme-grey-hover: #eef6ff50;
      --theme-white: #fff;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f7fa;
      padding: 20px;
      color: #2c3e50;
    }

    .receipt {
      max-width: 900px;
      margin: auto;
      background: var(--theme-white);
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.08);
      padding: 40px;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 3px solid var(--primary);
      padding-bottom: 15px;
      margin-bottom: 25px;
    }

    .header .hospital-name {
      font-size: 26px;
      font-weight: bold;
      color: var(--primary);
    }

    .header .hospital-details {
      font-size: 14px;
      color: #555;
    }

    .logo {
      width: 80px;
      height: 80px;
      object-fit: contain;
    }

    .section-title {
      font-size: 18px;
      font-weight: bold;
      color: var(--primary);
      margin-bottom: 10px;
      border-left: 5px solid var(--primary);
      padding-left: 10px;
    }

    .info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-bottom: 30px;
    }

    .info-box {
      background: #f9fbfd;
      border: 1px solid #dce5ed;
      padding: 15px;
      border-radius: 6px;
    }

    .info-box p {
      margin: 5px 0;
      font-size: 15px;
    }

    .payment-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    .payment-table th, .payment-table td {
      padding: 12px;
      text-align: left;
      border: 1px solid #ddd;
      font-size: 15px;
    }

    .payment-table th {
      background-color: var(--primary-light);
      color: white;
    }

    .totals {
      text-align: right;
      margin-top: 15px;
      font-size: 17px;
    }

    .totals strong {
      color: var(--success);
    }

    .footer {
      text-align: center;
      font-size: 13px;
      color: #777;
      margin-top: 40px;
      border-top: 1px dashed #ccc;
      padding-top: 10px;
    }
  </style>
</head>
<body>

<div class="receipt">
  <div class="header">
    <div>
      <div class="hospital-name">CityCare Hospital</div>
      <div class="hospital-details">
        123 Health St, New Delhi, India<br>
        Phone: +91-9876543210 | Email: info@citycare.com
      </div>
    </div>
    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/89/Red_Cross.svg/768px-Red_Cross.svg.png" class="logo" alt="Hospital Logo">
  </div>

  <div class="section-title">Receipt Information</div>
  <div class="info-grid">
    <div class="info-box">
      <p><strong>Receipt No:</strong> RCPT-20250731-01</p>
      <p><strong>Date:</strong> 31 Jul 2025</p>
      <p><strong>Payment Mode:</strong> UPI</p>
      <p><strong>Status:</strong> Paid</p>
    </div>
    <div class="info-box">
      <p><strong>Patient Name:</strong> Ramesh Kumar</p>
      <p><strong>UHID:</strong> UH123456</p>
      <p><strong>Department:</strong> General Medicine</p>
      <p><strong>Doctor:</strong> Dr. Neha Sharma</p>
    </div>
  </div>

  <div class="section-title">Payment Breakdown</div>
  <table class="payment-table">
    <thead>
      <tr>
        <th>Description</th>
        <th>Amount (₹)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Consultation Charges</td>
        <td>1000.00</td>
      </tr>
      <tr>
        <td>CGST (9%)</td>
        <td>90.00</td>
      </tr>
      <tr>
        <td>SGST (9%)</td>
        <td>90.00</td>
      </tr>
      <tr>
        <td><strong>Discount</strong></td>
        <td>-100.00</td>
      </tr>
    </tbody>
  </table>

  <div class="totals">
    <p>Total Payable: <strong>₹1080.00</strong></p>
    <p>Transaction ID: TXN202507310001</p>
  </div>

  <div class="footer">
    This is a system-generated receipt and does not require a physical signature. Please keep it for your records.
  </div>
</div>

</body>
</html>
@elseif ($layout == 3 )
<!DOCTYPE html>
<html lang="en">
<head>
    <base href="{{env("APP_URL")}}">
    <meta name="auth_token" content="{{csrf_token()}}">
  <meta charset="UTF-8">
  <title>Hospital Receipt</title>
  <style>
    :root {
      --primary: #036EAD;
      --primary-light: #178acd;
      --success: #10b981;
      --warning: #f59e0b;
      --warning-light: #ffb637;
      --danger: #E2021C;
      --danger-light: #e32c42;
      --theme-dark: #036EAD;
      --theme-grey-hover: #eef6ff50;
      --theme-white: #fff;
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      padding: 30px;
      font-family: 'Segoe UI', sans-serif;
      background: #eef3f8;
      color: #2e2e2e;
    }

    .receipt-wrapper {
      max-width: 700px;
      background: var(--theme-white);
      margin: auto;
      padding: 30px 40px;
      border-radius: 10px;
      box-shadow: 0 0 12px rgba(0, 0, 0, 0.05);
    }

    .header {
      text-align: center;
      margin-bottom: 30px;
    }

    .header img {
      width: 70px;
      height: 70px;
      object-fit: contain;
    }

    .header h1 {
      margin: 10px 0 4px;
      font-size: 24px;
      color: var(--primary);
    }

    .header p {
      margin: 0;
      font-size: 13px;
      color: #666;
    }

    .section-title {
      font-weight: bold;
      margin: 25px 0 10px;
      font-size: 16px;
      color: var(--primary);
      border-left: 4px solid var(--primary);
      padding-left: 8px;
    }

    .row {
      display: flex;
      justify-content: space-between;
      font-size: 14px;
      margin-bottom: 6px;
    }

    .table {
      width: 100%;
      margin-top: 15px;
      border-collapse: collapse;
    }

    .table th, .table td {
      border: 1px solid #ddd;
      padding: 10px;
      font-size: 14px;
    }

    .table th {
      background-color: var(--primary-light);
      color: white;
      text-align: left;
    }

    .totals {
      margin-top: 20px;
      font-size: 16px;
      text-align: right;
    }

    .totals strong {
      color: var(--success);
    }

    .footer {
      text-align: center;
      margin-top: 30px;
      font-size: 12px;
      color: #888;
      border-top: 1px dashed #ccc;
      padding-top: 10px;
    }
  </style>
</head>
<body>

<div class="receipt-wrapper">
  <div class="header">
    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/89/Red_Cross.svg/768px-Red_Cross.svg.png" alt="Hospital Logo">
    <h1>CityCare Multispeciality Hospital</h1>
    <p>123 Health Street, New Delhi - 110001<br>
      Phone: +91-9876543210 | Email: info@citycare.com</p>
  </div>

  <div class="section-title">Receipt Information</div>
  <div class="row"><span><strong>Receipt No:</strong> RCPT-20250731-02</span><span><strong>Date:</strong> 31 July 2025</span></div>
  <div class="row"><span><strong>Payment Mode:</strong> UPI</span><span><strong>Status:</strong> Paid</span></div>
  <div class="row"><span><strong>Transaction ID:</strong> TXN0032093</span></div>

  <div class="section-title">Patient Details</div>
  <div class="row"><span><strong>Name:</strong> Ramesh Kumar</span><span><strong>UHID:</strong> UH654321</span></div>
  <div class="row"><span><strong>Department:</strong> Orthopedics</span><span><strong>Doctor:</strong> Dr. Sneha Verma</span></div>

  <div class="section-title">Payment Details</div>
  <table class="table">
    <thead>
      <tr>
        <th>Description</th>
        <th>Amount (₹)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Fracture Dressing Charges</td>
        <td>1500.00</td>
      </tr>
      <tr>
        <td>CGST (9%)</td>
        <td>135.00</td>
      </tr>
      <tr>
        <td>SGST (9%)</td>
        <td>135.00</td>
      </tr>
      <tr>
        <td>Discount</td>
        <td>-200.00</td>
      </tr>
    </tbody>
  </table>

  <div class="totals">
    Total Payable: <strong>₹1570.00</strong>
  </div>

  <div class="footer">
    This is a computer-generated receipt and doesn't require a physical signature. Please retain for future reference.
  </div>
</div>

</body>
</html>
@elseif($layout == 4)
<!DOCTYPE html>
<html lang="en">
<head>
    <base href="{{env("APP_URL")}}">
    <meta name="auth_token" content="{{csrf_token()}}">
  <meta charset="UTF-8">
  <title>Hospital Receipt</title>
  <style>
    :root {
      --primary: #036EAD;
      --primary-light: #178acd;
      --success: #10b981;
      --warning: #f59e0b;
      --warning-light: #ffb637;
      --danger: #E2021C;
      --danger-light: #e32c42;
      --theme-dark: #036EAD;
      --theme-grey-hover: #eef6ff50;
      --theme-white: #fff;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f7fafc;
      color: #2c3e50;
      padding: 30px;
    }

    .receipt-box {
      max-width: 800px;
      margin: auto;
      background: var(--theme-white);
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.05);
    }

    .header {
      text-align: center;
      border-bottom: 3px solid var(--primary);
      padding-bottom: 20px;
      margin-bottom: 30px;
    }

    .header img {
      width: 70px;
      height: auto;
      margin-bottom: 10px;
    }

    .header h1 {
      margin: 0;
      color: var(--primary);
      font-size: 26px;
    }

    .header p {
      margin: 4px 0;
      font-size: 14px;
    }

    .section {
      margin-bottom: 25px;
    }

    .section h3 {
      font-size: 18px;
      margin-bottom: 10px;
      color: var(--primary);
      border-left: 5px solid var(--primary);
      padding-left: 10px;
    }

    .box {
      border: 1px solid #ddd;
      padding: 15px;
      border-radius: 6px;
      background-color: #f9fbfd;
    }

    .box p {
      margin: 6px 0;
      font-size: 15px;
    }

    .summary-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    .summary-table td {
      padding: 10px;
      font-size: 15px;
    }

    .summary-table tr td:first-child {
      text-align: left;
    }

    .summary-table tr td:last-child {
      text-align: right;
    }

    .summary-table tr.total td {
      font-weight: bold;
      color: var(--success);
      border-top: 2px solid var(--primary);
    }

    .footer {
      text-align: center;
      font-size: 13px;
      color: #777;
      margin-top: 30px;
      border-top: 1px dashed #ccc;
      padding-top: 10px;
    }

    @media print {
      body {
        background: none;
        padding: 0;
      }
      .receipt-box {
        box-shadow: none;
        border-radius: 0;
      }
    }
  </style>
</head>
<body>

<div class="receipt-box">
  <div class="header">
    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/89/Red_Cross.svg/768px-Red_Cross.svg.png" alt="Logo">
    <h1>CityCare Multispeciality Hospital</h1>
    <p>123 Health Street, New Delhi, India</p>
    <p>Phone: +91-9876543210 | Email: info@citycare.com</p>
  </div>

  <div class="section">
    <h3>Patient Information</h3>
    <div class="box">
      <p><strong>Name:</strong> Ramesh Kumar</p>
      <p><strong>UHID:</strong> UH123456</p>
      <p><strong>Department:</strong> General Medicine</p>
      <p><strong>Doctor:</strong> Dr. Neha Sharma</p>
    </div>
  </div>

  <div class="section">
    <h3>Payment Information</h3>
    <div class="box">
      <p><strong>Receipt No:</strong> RCPT-20250731-03</p>
      <p><strong>Date:</strong> 31-Jul-2025</p>
      <p><strong>Payment Mode:</strong> UPI</p>
      <p><strong>Transaction ID:</strong> TXN202507310003</p>
    </div>
  </div>

  <div class="section">
    <h3>Summary</h3>
    <table class="summary-table">
      <tr>
        <td>Consultation Fee</td>
        <td>₹ 1000.00</td>
      </tr>
      <tr>
        <td>CGST (9%)</td>
        <td>₹ 90.00</td>
      </tr>
      <tr>
        <td>SGST (9%)</td>
        <td>₹ 90.00</td>
      </tr>
      <tr>
        <td>Discount</td>
        <td>- ₹ 100.00</td>
      </tr>
      <tr class="total">
        <td>Total Payable</td>
        <td>₹ 1080.00</td>
      </tr>
    </table>
  </div>

  <div class="footer">
    This is a computer-generated receipt and does not require a signature. Please keep a copy for your records.
  </div>
</div>

</body>
</html>
@elseif($layout == 5)
<!DOCTYPE html>
<html lang="en">
<head>
    <base href="{{env("APP_URL")}}">
    <meta name="auth_token" content="{{csrf_token()}}">
  <meta charset="UTF-8">
  <title>Hospital Payment Receipt</title>
  <style>
    :root {
      --primary: #036EAD;
      --primary-light: #178acd;
      --success: #10b981;
      --warning: #f59e0b;
      --warning-light: #ffb637;
      --danger: #E2021C;
      --danger-light: #e32c42;
      --theme-dark: #036EAD;
      --theme-grey-hover: #eef6ff50;
      --theme-white: #fff;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f7fbff;
      color: #333;
      margin: 0;
      padding: 20px;
    }

    .receipt {
      max-width: 750px;
      margin: auto;
      background: var(--theme-white);
      padding: 25px;
      border: 1px solid #ccc;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(3, 110, 173, 0.15);
    }

    .header {
      display: flex;
      align-items: center;
      border-bottom: 2px solid var(--primary);
      padding-bottom: 15px;
      margin-bottom: 20px;
    }

    .header img {
      height: 70px;
      margin-right: 15px;
    }

    .header h1 {
      font-size: 24px;
      color: var(--primary);
      margin: 0;
    }

    .info, .summary, .footer {
      margin-bottom: 20px;
    }

    .info table, .summary table {
      width: 100%;
      border-collapse: collapse;
    }

    .info th, .info td,
    .summary th, .summary td {
      padding: 8px 10px;
      text-align: left;
    }

    .info th {
      background: var(--primary-light);
      color: #fff;
    }

    .summary td {
      border-top: 1px solid #ddd;
    }

    .summary .total {
      font-weight: bold;
      color: var(--primary);
    }

    .summary .amount {
      text-align: right;
    }

    .footer {
      text-align: center;
      font-size: 13px;
      color: #555;
      border-top: 1px dashed #aaa;
      padding-top: 15px;
    }

    .highlight {
      background: var(--theme-grey-hover);
      padding: 10px;
      border-left: 4px solid var(--primary);
    }
  </style>
</head>
<body>

  <div class="receipt">
    <div class="header">
      <img src="hospital-logo.png" alt="Hospital Logo">
      <div>
        <h1>City Care Multispeciality Hospital</h1>
        <div style="color: #777;">123 Main Road, New Delhi - 110001<br>Phone: +91-9876543210</div>
      </div>
    </div>

    <div class="info">
      <table>
        <tr>
          <th>Receipt No.</th>
          <td>#TXN-2025-1032</td>
          <th>Date</th>
          <td>31-Jul-2025</td>
        </tr>
        <tr>
          <th>Patient Name</th>
          <td>Ravi Sharma</td>
          <th>UHID</th>
          <td>UH123456</td>
        </tr>
        <tr>
          <th>Payment Mode</th>
          <td>Online (UPI)</td>
          <th>Transaction ID</th>
          <td>PAY987654321</td>
        </tr>
      </table>
    </div>

    <div class="summary">
      <table>
        <tr>
          <td>Consultation Charges</td>
          <td class="amount">₹500.00</td>
        </tr>
        <tr>
          <td>Lab Tests</td>
          <td class="amount">₹1,200.00</td>
        </tr>
        <tr>
          <td>Medicines</td>
          <td class="amount">₹750.00</td>
        </tr>
        <tr>
          <td>Room Charges</td>
          <td class="amount">₹2,000.00</td>
        </tr>
        <tr>
          <td>CGST (9%)</td>
          <td class="amount">₹396.00</td>
        </tr>
        <tr>
          <td>SGST (9%)</td>
          <td class="amount">₹396.00</td>
        </tr>
        <tr>
          <td>Discount</td>
          <td class="amount">- ₹200.00</td>
        </tr>
        <tr class="total">
          <td>Total Amount Paid</td>
          <td class="amount">₹5,042.00</td>
        </tr>
      </table>
    </div>

    <div class="highlight">
      Thank you for choosing City Care Hospital. Get well soon!
    </div>

    <div class="footer">
      This is a computer-generated receipt and does not require a physical signature.<br>
      GSTIN: 07ABCDE1234F1Z5 | PAN: ABCDE1234F
    </div>
  </div>

</body>
</html>
@elseif($layout == 6)
<!DOCTYPE html>
<html lang="en">
<head>
    <base href="{{env("APP_URL")}}">
    <meta name="auth_token" content="{{csrf_token()}}">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Payment Receipt - MediCare Hospital</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f7fafc;
      margin: 0;
      padding: 20px;
      color: #244464;
    }
    .receipt-container {
      max-width: 800px;
      margin: auto;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 20px rgba(3, 110, 173, 0.08);
      overflow: hidden;
      padding: 32px 36px 24px 36px;
    }
    .header {
      border-bottom: 4px solid #036EAD;
      padding-bottom: 14px;
      text-align: center;
    }
    .header img {
      width: 80px;
      margin-bottom: 10px;
      border-radius: 12px;
    }
    .hospital-name {
      font-size: 2rem;
      font-weight: 700;
      color: #036EAD;
      letter-spacing: 1px;
      margin-bottom: 2px;
    }
    .tagline {
      font-size: 1rem;
      font-style: italic;
      color: #244464;
      margin-top: 0;
      margin-bottom: 16px;
      opacity: 0.78;
    }
    .receipt-title {
      font-size: 1.3rem;
      font-weight: 700;
      color: #036EAD;
      margin-top: 10px;
      margin-bottom: 10px;
      border-bottom: 2px solid #036EAD;
      display: inline-block;
      padding-bottom: 5px;
      letter-spacing: .5px;
    }
    .details {
      margin-top: 30px;
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 18px;
    }
    .patient-info, .payment-info {
      width: 48%;
      min-width: 240px;
    }
    .info-title {
      font-weight: 600;
      color: #036EAD;
      margin-bottom: 10px;
      border-bottom: 1px dashed #036EAD30;
      padding-bottom: 5px;
      letter-spacing: .2px;
    }
    .info-table {
      width: 100%;
      border-collapse: collapse;
    }
    .info-table td {
      padding: 6px 4px 6px 0;
      vertical-align: top;
      font-size: 1.04rem;
    }
    .info-table .label {
      font-weight: 600;
      color: #466088;
      width: 38%;
    }
    .amount {
      font-size: 2rem;
      font-weight: 700;
      color: #036EAD;
      text-align: right;
      margin-top: 38px;
      letter-spacing: 1px;
    }
    .footer {
      text-align: center;
      margin-top: 36px;
      font-size: 0.98rem;
      color: #35526f;
      border-top: 1px solid #e1e7ef;
      padding-top: 16px;
    }
    .btn-print {
      display: block;
      margin: 28px auto 0 auto;
      padding: 12px 34px;
      background: #036EAD;
      color: #fff;
      border: none;
      border-radius: 22px;
      font-weight: 600;
      font-size: 1.04rem;
      cursor: pointer;
      box-shadow: 0 5px 15px rgba(3,110,173,0.14);
      transition: background 0.2s;
    }
    .btn-print:hover {
      background: #024e7c;
    }
    @media (max-width: 900px) {
      .receipt-container { padding: 18px 5vw; }
      .amount { font-size: 1.4rem; }
      .header img { width: 60px; }
      .receipt-title { font-size: 1.08rem; }
    }
    @media (max-width: 600px) {
      .details {
        flex-direction: column;
        gap: 24px;
      }
      .patient-info, .payment-info {
        width: 100%;
      }
      .amount {
        font-size: 1.2rem;
        margin-top: 22px;
      }
    }
    @media print {
      body, .receipt-container { margin: 0; box-shadow: none; background: #fff; }
      .btn-print { display: none; }
    }
  </style>
</head>
<body>
  <div class="receipt-container">
    <div class="header">
      <!-- Replace src with your actual hospital logo path -->
      <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/Hospital_icon.svg/1024px-Hospital_icon.svg.png" alt="Hospital Logo" />
      <div class="hospital-name">MediCare Hospital</div>
      <div class="tagline">Advanced Healthcare & Wellness</div>
      <div class="receipt-title">Payment Receipt</div>
    </div>
    <div class="details">
      <div class="patient-info">
        <div class="info-title">Patient Details</div>
        <table class="info-table">
          <tr><td class="label">Patient Name:</td><td>John Doe</td></tr>
          <tr><td class="label">Patient ID:</td><td>123456</td></tr>
          <tr><td class="label">Age/Sex:</td><td>45 / Male</td></tr>
          <tr><td class="label">Admission Date:</td><td>31/07/2025</td></tr>
          <tr><td class="label">Ward/Room:</td><td>General Ward / 101</td></tr>
        </table>
      </div>
      <div class="payment-info">
        <div class="info-title">Payment Details</div>
        <table class="info-table">
          <tr><td class="label">Receipt No:</td><td>TXN-789654</td></tr>
          <tr><td class="label">Date & Time:</td><td>31/07/2025 11:00 AM</td></tr>
          <tr><td class="label">Payment Method:</td><td>Credit Card</td></tr>
          <tr><td class="label">Transaction ID:</td><td>ABC12345</td></tr>
          <tr><td class="label">Remarks:</td><td>Paid in full</td></tr>
        </table>
      </div>
    </div>
    <div class="amount">₹ 4,23,000.00</div>
    <div class="footer">
      Thank you for choosing MediCare Hospital.<br />
      For inquiries, please contact our billing department.
    </div>
  </div>
  <button class="btn-print" onclick="window.print()">Print Receipt</button>
</body>
</html>
@elseif($layout == 7)
<!DOCTYPE html>
<html lang="en">
<head>
    <base href="{{env("APP_URL")}}">
    <meta name="auth_token" content="{{csrf_token()}}">
  <meta charset="UTF-8">
  <title>Hospital Payment Receipt</title>
  <style>
    :root {
      --primary: #036EAD;
      --primary-light: #178acd;
      --success: #10b981;
      --warning: #f59e0b;
      --danger: #E2021C;
      --theme-white: #fff;
      --gray-light: #f5f7fa;
      --border-color: #d3e2ef;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: var(--gray-light);
      padding: 30px;
    }

    .receipt-container {
      background: var(--theme-white);
      max-width: 800px;
      margin: auto;
      border: 1px solid var(--border-color);
      border-radius: 8px;
      padding: 30px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .header {
      display: flex;
      align-items: center;
      border-bottom: 2px solid var(--primary);
      padding-bottom: 12px;
      margin-bottom: 20px;
    }

    .header img {
      height: 60px;
      margin-right: 15px;
    }

    .header h1 {
      font-size: 24px;
      color: var(--primary);
      margin: 0;
    }

    .section-title {
      font-weight: bold;
      font-size: 16px;
      color: var(--primary);
      margin-bottom: 10px;
      border-bottom: 1px dashed var(--border-color);
      padding-bottom: 5px;
    }

    .info-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 15px;
      margin-bottom: 20px;
    }

    .info-box label {
      font-weight: 600;
      font-size: 13px;
      color: #333;
    }

    .info-box div {
      font-size: 14px;
      color: #555;
    }

    .amount-paid {
      background-color: var(--success);
      color: white;
      padding: 14px;
      border-radius: 6px;
      font-size: 18px;
      font-weight: bold;
      text-align: center;
      margin-top: 15px;
      margin-bottom: 20px;
    }

    .footer {
      text-align: center;
      font-size: 13px;
      color: #666;
      border-top: 1px dashed var(--border-color);
      padding-top: 12px;
    }

    @media (max-width: 600px) {
      .info-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>

<div class="receipt-container">
  <div class="header">
    <img src="hospital-logo.png" alt="Hospital Logo">
    <h1>City Care Hospital</h1>
  </div>

  <div class="section-title">Patient & Admission Info</div>
  <div class="info-grid">
    <div class="info-box">
      <label>Patient Name</label>
      <div>John Doe</div>
    </div>
    <div class="info-box">
      <label>Patient ID</label>
      <div>123456</div>
    </div>
    <div class="info-box">
      <label>Age / Sex</label>
      <div>45 / Male</div>
    </div>
    <div class="info-box">
      <label>Admission Date</label>
      <div>31/07/2025</div>
    </div>
    <div class="info-box">
      <label>Ward / Room</label>
      <div>General Ward / 101</div>
    </div>
  </div>

  <div class="section-title">Payment Details</div>
  <div class="info-grid">
    <div class="info-box">
      <label>Receipt No</label>
      <div>TXN-789654</div>
    </div>
    <div class="info-box">
      <label>Date & Time</label>
      <div>31/07/2025 11:00 AM</div>
    </div>
    <div class="info-box">
      <label>Payment Method</label>
      <div>Credit Card</div>
    </div>
    <div class="info-box">
      <label>Transaction ID</label>
      <div>ABC12345</div>
    </div>
    <div class="info-box">
      <label>Remarks</label>
      <div>Paid in full</div>
    </div>
  </div>

  <div class="amount-paid">
    Amount Paid: ₹12,500.00
  </div>

  <div class="footer">
    GSTIN: 07ABCDE1234F1Z5 | PAN: ABCDE1234F <br>
    This is a computer-generated receipt and does not require a signature.
  </div>
</div>

</body>
</html>
@elseif($layout == 8)
<!DOCTYPE html>
<html lang="en">
<head>
    <base href="{{env("APP_URL")}}">
    <meta name="auth_token" content="{{csrf_token()}}">
  <meta charset="UTF-8">
  <title>Hospital Payment Receipt</title>
  <style>
    :root {
      --primary: #036EAD;
      --primary-light: #178acd;
      --success: #10b981;
      --warning: #f59e0b;
      --danger: #E2021C;
      --theme-white: #fff;
      --gray-light: #f5f7fa;
      --border-color: #d3e2ef;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: var(--gray-light);
      padding: 30px;
    }

    .receipt-container {
      background: var(--theme-white);
      max-width: 800px;
      margin: auto;
      border: 1px solid var(--border-color);
      border-radius: 8px;
      padding: 30px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .header {
      display: flex;
      align-items: center;
      border-bottom: 2px solid var(--primary);
      padding-bottom: 12px;
      margin-bottom: 10px;
    }

    .header img {
      height: 60px;
      margin-right: 15px;
    }

    .header h1 {
      font-size: 24px;
      color: var(--primary);
      margin: 0;
    }

    .hospital-info {
      font-size: 13px;
      color: #444;
      margin-bottom: 20px;
    }

    .hospital-info div {
      line-height: 1.4;
    }

    .section-title {
      font-weight: bold;
      font-size: 16px;
      color: var(--primary);
      margin-bottom: 10px;
      border-bottom: 1px dashed var(--border-color);
      padding-bottom: 5px;
    }

    .info-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 15px;
      margin-bottom: 20px;
    }

    .info-box label {
      font-weight: 600;
      font-size: 13px;
      color: #333;
    }

    .info-box div {
      font-size: 14px;
      color: #555;
    }

    .amount-paid {
      background-color: var(--success);
      color: white;
      padding: 14px;
      border-radius: 6px;
      font-size: 18px;
      font-weight: bold;
      text-align: center;
      margin-top: 15px;
      margin-bottom: 20px;
    }

    .footer {
      text-align: center;
      font-size: 13px;
      color: #666;
      border-top: 1px dashed var(--border-color);
      padding-top: 12px;
    }

    .footer small {
      display: block;
      font-size: 12px;
      color: #999;
    }

    @media (max-width: 600px) {
      .info-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>

<div class="receipt-container">
  <div class="header">
    <img src="hospital-logo.png" alt="Hospital Logo">
    <h1>City Care Hospital</h1>
  </div>

  <div class="hospital-info">
    <div><strong>Address:</strong> 123 Health Street, New Delhi, India – 110001</div>
    <div><strong>Contact:</strong> +91-9876543210 | <strong>Email:</strong> info@citycarehospital.com</div>
    <div><strong>Website:</strong> www.citycarehospital.com | <strong>GSTIN:</strong> 07ABCDE1234F1Z5</div>
  </div>

  <div class="section-title">Patient & Admission Info</div>
  <div class="info-grid">
    <div class="info-box">
      <label>Patient Name</label>
      <div>John Doe</div>
    </div>
    <div class="info-box">
      <label>Patient ID</label>
      <div>123456</div>
    </div>
    <div class="info-box">
      <label>Age / Sex</label>
      <div>45 / Male</div>
    </div>
    <div class="info-box">
      <label>Admission Date</label>
      <div>31/07/2025</div>
    </div>
    <div class="info-box">
      <label>Ward / Room</label>
      <div>General Ward / 101</div>
    </div>
  </div>

  <div class="section-title">Payment Details</div>
  <div class="info-grid">
    <div class="info-box">
      <label>Receipt No</label>
      <div>TXN-789654</div>
    </div>
    <div class="info-box">
      <label>Date & Time</label>
      <div>31/07/2025 11:00 AM</div>
    </div>
    <div class="info-box">
      <label>Payment Method</label>
      <div>Credit Card</div>
    </div>
    <div class="info-box">
      <label>Transaction ID</label>
      <div>ABC12345</div>
    </div>
    <div class="info-box">
      <label>Remarks</label>
      <div>Paid in full</div>
    </div>
  </div>

  <div class="amount-paid">
    Amount Paid: ₹12,500.00
  </div>

  <div class="footer">
    Thank you for choosing City Care Hospital. <br>
    <small>This is a computer-generated receipt and does not require a physical signature.</small>
  </div>
</div>

</body>
</html>
@elseif($layout == 9)
<!DOCTYPE html>
<html lang="en">
<head>
    <base href="{{env("APP_URL")}}">
    <meta name="auth_token" content="{{csrf_token()}}">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Hospital Payment Receipt</title>
  <style>
    :root {
      --primary: #036EAD;
      --primary-light: #178acd;
      --success: #10b981;
      --warning: #f59e0b;
      --danger: #E2021C;
      --danger-light: #e32c42;
      --gray: #f5f7fa;
      --border: #e2e8f0;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: var(--gray);
      padding: 30px;
    }

    .receipt-box {
      background: white;
      max-width: 800px;
      margin: auto;
      border: 1px solid var(--border);
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.05);
      padding: 30px;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 2px solid var(--primary);
      padding-bottom: 10px;
    }

    .header img {
      height: 60px;
    }

    .hospital-info {
      text-align: right;
      font-size: 13px;
      color: #444;
    }

    .section {
      margin-top: 25px;
    }

    .section h3 {
      font-size: 16px;
      color: var(--primary);
      border-bottom: 1px dashed var(--border);
      padding-bottom: 4px;
      margin-bottom: 12px;
    }

    .row {
      display: flex;
      flex-wrap: wrap;
      margin-bottom: 10px;
    }

    .col-50 {
      width: 50%;
      padding-right: 10px;
      margin-bottom: 10px;
    }

    .label {
      font-weight: 600;
      color: #333;
      font-size: 13px;
    }

    .value {
      font-size: 14px;
      color: #222;
    }

    .amount-box {
      background-color: var(--success);
      color: white;
      text-align: center;
      padding: 20px;
      font-size: 22px;
      font-weight: bold;
      border-radius: 8px;
      margin: 30px auto 10px auto;
      width: 280px;
    }

    .footer {
      text-align: center;
      font-size: 12px;
      color: #999;
      border-top: 1px dashed var(--border);
      padding-top: 10px;
      margin-top: 20px;
    }

    @media (max-width: 600px) {
      .col-50 {
        width: 100%;
        padding-right: 0;
      }
      .header {
        flex-direction: column;
        align-items: flex-start;
      }
      .hospital-info {
        text-align: left;
        margin-top: 10px;
      }
    }
  </style>
</head>
<body>

<div class="receipt-box">
  <div class="header">
    <img src="hospital-logo.png" alt="Hospital Logo">
    <div class="hospital-info">
      <strong>City Care Hospital</strong><br>
      123 Main St, Mumbai, Maharashtra - 400001<br>
      Email: info@citycare.com | Phone: +91-9876543210<br>
      GSTIN: 27ABCDE1234F1Z5
    </div>
  </div>

  <div class="section">
    <h3>Patient Details</h3>
    <div class="row">
      <div class="col-50">
        <div class="label">Patient Name</div>
        <div class="value">Rahul Sharma</div>
      </div>
      <div class="col-50">
        <div class="label">Patient ID</div>
        <div class="value">PT20250731</div>
      </div>
      <div class="col-50">
        <div class="label">Gender / Age</div>
        <div class="value">Male / 36</div>
      </div>
      <div class="col-50">
        <div class="label">Doctor</div>
        <div class="value">Dr. A. Mehta</div>
      </div>
    </div>
  </div>

  <div class="section">
    <h3>Transaction Details</h3>
    <div class="row">
      <div class="col-50">
        <div class="label">Receipt No</div>
        <div class="value">RCPT-875401</div>
      </div>
      <div class="col-50">
        <div class="label">Transaction ID</div>
        <div class="value">TXN-RAZOR12345</div>
      </div>
      <div class="col-50">
        <div class="label">Payment Mode</div>
        <div class="value">Card (Visa)</div>
      </div>
      <div class="col-50">
        <div class="label">Date & Time</div>
        <div class="value">31/07/2025 - 12:25 PM</div>
      </div>
      <div class="col-50">
        <div class="label">Remarks</div>
        <div class="value">Final Payment</div>
      </div>
    </div>
  </div>

  <div class="amount-box">
    ₹ 12,500.00 Paid
  </div>

  <div class="footer">
    Thank you for your payment. Wishing you a speedy recovery.<br>
    <small>This is a system-generated receipt and does not require a signature.</small>
  </div>
</div>

</body>
</html>
@elseif($layout == 10)
<!DOCTYPE html>
<html lang="en">
<head>
    <base href="{{env("APP_URL")}}">
    <meta name="auth_token" content="{{csrf_token()}}">
  <meta charset="UTF-8" />
  <title>Hospital POS Receipt</title>
  <style>
    body {
      width: 270px; /* approx. 58mm */
      font-family: 'Courier New', monospace;
      font-size: 12px;
      padding: 10px;
      margin: auto;
      color: #000;
    }

    .center {
      text-align: center;
    }

    .bold {
      font-weight: bold;
    }

    .line {
      border-top: 1px dashed #000;
      margin: 5px 0;
    }

    .logo {
      height: 50px;
      margin-bottom: 5px;
    }

    .info, .summary {
      margin-bottom: 10px;
    }

    table {
      width: 100%;
    }

    table td {
      vertical-align: top;
    }

    .totals td {
      padding: 3px 0;
    }

    .totals td:first-child {
      width: 60%;
    }

    .footer {
      margin-top: 15px;
      font-size: 11px;
      text-align: center;
    }
  </style>
</head>
<body>

  <div class="center">
    <img src="logo.png" class="logo" alt="Logo" />
    <div class="bold">City Care Hospital</div>
    123 Main Rd, Mumbai - 400001<br/>
    Phone: +91-9876543210<br/>
    GSTIN: 27ABCDE1234F1Z5
  </div>

  <div class="line"></div>

  <div class="info">
    <strong>Receipt No:</strong> RCPT-875401<br/>
    <strong>Date:</strong> 31-07-2025 12:24 PM<br/>
    <strong>Patient:</strong> Rahul Sharma<br/>
    <strong>UHID:</strong> UHID-123456<br/>
    <strong>Doctor:</strong> Dr. A. Mehta
  </div>

  <div class="line"></div>

  <table class="totals">
    <tr>
      <td>Consultation</td>
      <td class="right">₹ 800.00</td>
    </tr>
    <tr>
      <td>Lab Charges</td>
      <td class="right">₹ 1,200.00</td>
    </tr>
    <tr>
      <td>Medicines</td>
      <td class="right">₹ 600.00</td>
    </tr>
    <tr>
      <td>Subtotal</td>
      <td class="right">₹ 2,600.00</td>
    </tr>
    <tr>
      <td>CGST (9%)</td>
      <td class="right">₹ 234.00</td>
    </tr>
    <tr>
      <td>SGST (9%)</td>
      <td class="right">₹ 234.00</td>
    </tr>
    <tr class="bold">
      <td>Total Amount</td>
      <td class="right">₹ 3,068.00</td>
    </tr>
    <tr>
      <td>Paid via UPI</td>
      <td class="right">₹ 3,068.00</td>
    </tr>
  </table>

  <div class="line"></div>

  <div class="footer">
    Thank you for your visit!<br/>
    Get well soon.<br/>
    <br/>
    This is a system generated receipt.
  </div>

</body>
</html>
@elseif($layout == 11)
<!DOCTYPE html>
<html lang="en">
<head>
    <base href="{{env("APP_URL")}}">
    <meta name="auth_token" content="{{csrf_token()}}">
  <meta charset="UTF-8">
  <title>Hospital POS Receipt</title>
  <style>
    body {
      width: 270px; /* ~58mm width */
      font-family: 'Courier New', monospace;
      font-size: 12px;
      padding: 10px;
      margin: auto;
      color: #000;
    }

    .center {
      text-align: center;
    }

    .logo {
      height: 45px;
      margin-bottom: 5px;
    }

    .bold {
      font-weight: bold;
    }

    .line {
      border-top: 1px solid #000;
      margin: 6px 0;
    }

    .header, .footer {
      margin-bottom: 10px;
    }

    .section {
      margin: 5px 0;
    }

    .section td {
      padding: 3px 0;
    }

    .section td:first-child {
      width: 60%;
    }

    .footer-note {
      font-size: 10px;
      margin-top: 10px;
    }

    .barcode {
      margin-top: 8px;
      text-align: center;
      font-size: 10px;
    }

    .right {
      text-align: right;
    }
  </style>
</head>
<body>

  <div class="center header">
    <img src="logo.png" alt="Hospital Logo" class="logo" />
    <div class="bold">MediLife Hospital</div>
    24x7 Multi-Specialty Clinic<br/>
    501 Apollo Street, Delhi - 110001<br/>
    Phone: +91-9023456789<br/>
    GSTIN: 07ABCDX1234F1Z9
  </div>

  <div class="line"></div>

  <div class="section">
    <strong>Receipt #: </strong> HOS-000512<br>
    <strong>Date: </strong> 31-07-2025 15:10<br>
    <strong>UHID: </strong> UH987654<br>
    <strong>Patient: </strong> Pooja Sinha<br>
    <strong>Doctor: </strong> Dr. K. Jain
  </div>

  <div class="line"></div>

  <table class="section">
    <tr>
      <td>Consultation Fee</td>
      <td class="right">₹ 600.00</td>
    </tr>
    <tr>
      <td>Pathology</td>
      <td class="right">₹ 850.00</td>
    </tr>
    <tr>
      <td>Pharmacy</td>
      <td class="right">₹ 540.00</td>
    </tr>
    <tr>
      <td>Subtotal</td>
      <td class="right">₹ 1,990.00</td>
    </tr>
    <tr>
      <td>CGST (9%)</td>
      <td class="right">₹ 179.10</td>
    </tr>
    <tr>
      <td>SGST (9%)</td>
      <td class="right">₹ 179.10</td>
    </tr>
    <tr class="bold">
      <td>Total Payable</td>
      <td class="right">₹ 2,348.20</td>
    </tr>
    <tr>
      <td>Paid (Cash)</td>
      <td class="right">₹ 2,348.20</td>
    </tr>
  </table>

  <div class="line"></div>

  <div class="center footer-note">
    Thank you for choosing MediLife Hospital<br>
    Stay Healthy. Stay Safe.<br><br>
    This is a computer-generated receipt.
  </div>

  <div class="barcode">
    UHID: <img src="" alt="" id="uhid-barcode" width="100%">
  </div>
  <script src="{{env("APP_URL")}}assets/js/jquery.3.6.0.min.js"></script>
  <script src="{{env("APP_URL")}}assets/js/main.js"></script>
  <script>
      mscGetBarcode("UHID10001", "#uhid-barcode");
  </script>
</body>
</html>
@endif