@if ($layout == 1)
    <!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Hospital Payment Receipt</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
  :root{
    --brand:#0ea5e9;
    --ink:#111827;
    --muted:#6b7280;
    --border:#e5e7eb;
    --bg:#ffffff;
    --accent:#f3f4f6;
  }

  /* Page + print */
  html,body{ background:#f9fafb; }
  @page{ size:A4; margin:12mm; }
  @media print{
    body{ background:#fff; }
    .no-print{ display:none !important; }
    .receipt{ box-shadow:none; }
  }

  /* Layout */
  body{
    font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
    color:var(--ink);
    line-height:1.45;
    padding:24px 12px;
  }
  .wrap{
    max-width:900px;
    margin:0 auto;
  }
  .receipt{
    background:var(--bg);
    border:1px solid var(--border);
    border-radius:12px;
    box-shadow:0 10px 20px rgba(0,0,0,.04);
    overflow:hidden;
  }

  /* Header */
  .header{
    display:flex;
    align-items:center;
    gap:16px;
    padding:20px 24px;
    border-bottom:1px solid var(--border);
    background:linear-gradient(180deg,#ffffff 0%, #f8fafc 100%);
  }
  .logo{
    width:64px;height:64px;border-radius:8px;
    border:2px solid var(--brand); display:flex;align-items:center;justify-content:center;
    font-weight:800; color:var(--brand); letter-spacing:1px;
  }
  .h-meta{ flex:1; }
  .h-title{ font-size:22px; font-weight:800; margin:0; }
  .h-sub{ color:var(--muted); margin:2px 0 0 0; font-size:12px; }
  .badge{
    border:1px solid var(--brand); color:var(--brand);
    padding:6px 10px; border-radius:999px; font-weight:700; letter-spacing:.08em;
    font-size:12px;
  }

  /* Info rows */
  .grid{
    display:grid; gap:12px;
  }
  .g-2{ grid-template-columns:1fr 1fr; }
  .g-3{ grid-template-columns:repeat(3,1fr); }
  .section{
    padding:16px 24px;
    border-bottom:1px solid var(--border);
  }
  .kv{
    display:flex; gap:8px; font-size:13px;
  }
  .k{ color:var(--muted); min-width:120px; }
  .v{ font-weight:600; }

  /* Table */
  table{
    width:100%; border-collapse:collapse; font-size:13px;
  }
  thead th{
    text-align:left; background:var(--accent);
    color:#374151; padding:10px; border-bottom:1px solid var(--border);
  }
  tbody td{
    padding:10px; border-bottom:1px dashed var(--border);
    vertical-align:top;
  }
  tfoot td{
    padding:6px 10px;
  }
  .right{ text-align:right; }
  .center{ text-align:center; }

  /* Totals box */
  .totals{
    margin-top:12px; border:1px solid var(--border); border-radius:8px; overflow:hidden;
  }
  .totals .row{
    display:flex; justify-content:space-between; padding:10px 12px; background:#fff;
    border-bottom:1px solid var(--border);
  }
  .totals .row:last-child{ border-bottom:none; }
  .totals .label{ color:#374151; }
  .totals .val{ font-weight:700; }
  .totals .grand{ background:#ecfeff; }

  /* Footer */
  .footer{
    padding:14px 24px;
    display:flex; gap:14px; align-items:flex-start; justify-content:space-between;
  }
  .note{
    font-size:12px; color:var(--muted); background:#f8fafc;
    border:1px dashed var(--border); padding:10px 12px; border-radius:8px;
  }
  .sign{
    min-width:240px; text-align:center; margin-left:auto;
  }
  .sign .line{ border-top:1px solid var(--border); margin-top:40px; padding-top:6px; font-size:12px; color:var(--muted); }

  /* Utilities */
  .muted{ color:var(--muted); }
  .hr{ height:1px; background:var(--border); margin:8px 0; }
  .btnbar{
    display:flex; gap:8px; justify-content:flex-end; margin:12px 0;
  }
  .btn{
    padding:10px 14px; border-radius:8px; border:1px solid var(--border); background:#fff; cursor:pointer;
    font-weight:600;
  }
  .btn.primary{ background:var(--brand); color:#fff; border-color:var(--brand); }
  .qr{
    width:92px;height:92px;border:1px solid var(--border); border-radius:8px; display:flex;align-items:center;justify-content:center;
    font-size:10px; color:var(--muted); background:#fff;
  }

  /* Responsive */
  @media (max-width:720px){
    .g-2, .g-3{ grid-template-columns:1fr; }
    .header{ flex-direction:column; align-items:flex-start; }
    .sign{ min-width:auto; width:100%; }
  }
</style>
</head>
<body>
  <div class="wrap">
    <div class="no-print btnbar">
      <button class="btn" onclick="window.print()">🖨️ Print</button>
      <button class="btn primary" onclick="window.print()">Save as PDF</button>
    </div>

    <div class="receipt" id="receipt">

      <!-- Header -->
      <div class="header">
        <div class="logo">H+</div>
        <div class="h-meta">
          <h1 class="h-title">CityCare Multispeciality Hospital</h1>
          <p class="h-sub">12, Health Avenue, Sector 9, New Delhi 110001 • +91 11 4000 1234 • billing@citycare.in</p>
        </div>
        <div class="badge">PAYMENT RECEIPT</div>
      </div>

      <!-- Receipt meta -->
      <div class="section grid g-3">
        <div class="kv"><div class="k">Receipt No.</div><div class="v">RCPT-2025-00751</div></div>
        <div class="kv"><div class="k">Invoice No.</div><div class="v">INV-2025-10422</div></div>
        <div class="kv"><div class="k">Date & Time</div><div class="v">31 Jul 2025, 10:24 AM</div></div>

        <div class="kv"><div class="k">Patient MRN</div><div class="v">MRN004523</div></div>
        <div class="kv"><div class="k">Patient Name</div><div class="v">Rahul Sharma</div></div>
        <div class="kv"><div class="k">Attending Doctor</div><div class="v">Dr. J. Kumar (Gen. Medicine)</div></div>
      </div>

      <!-- Payer + Payment -->
      <div class="section grid g-2">
        <div>
          <div class="kv"><div class="k">Payer</div><div class="v">Rahul Sharma</div></div>
          <div class="kv"><div class="k">Mobile</div><div class="v">+91 98765 43210</div></div>
          <div class="kv"><div class="k">Address</div><div class="v">A-22, Patel Nagar, New Delhi</div></div>
        </div>
        <div>
          <div class="kv"><div class="k">Payment Method</div><div class="v">UPI</div></div>
          <div class="kv"><div class="k">Reference</div><div class="v">TXN-6C29ABF921</div></div>
          <div class="kv"><div class="k">Received By</div><div class="v">Anita (Cashier)</div></div>
        </div>
      </div>

      <!-- Items -->
      <div class="section">
        <table>
          <thead>
            <tr>
              <th style="width:40px;">#</th>
              <th>Description</th>
              <th class="right" style="width:70px;">Qty</th>
              <th class="right" style="width:110px;">Rate (₹)</th>
              <th class="right" style="width:85px;">Tax %</th>
              <th class="right" style="width:110px;">Tax (₹)</th>
              <th class="right" style="width:120px;">Amount (₹)</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="center">1</td>
              <td>OPD Consultation — General Medicine</td>
              <td class="right">1</td>
              <td class="right">500.00</td>
              <td class="right">0</td>
              <td class="right">0.00</td>
              <td class="right">500.00</td>
            </tr>
            <tr>
              <td class="center">2</td>
              <td>Lab Test — CBC</td>
              <td class="right">1</td>
              <td class="right">350.00</td>
              <td class="right">5</td>
              <td class="right">17.50</td>
              <td class="right">367.50</td>
            </tr>
            <tr>
              <td class="center">3</td>
              <td>Medicine — Amoxicillin 500mg (Strip of 10)</td>
              <td class="right">1</td>
              <td class="right">120.00</td>
              <td class="right">12</td>
              <td class="right">14.40</td>
              <td class="right">134.40</td>
            </tr>
          </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
          <div class="row">
            <div class="label">Subtotal</div>
            <div class="val">₹ 970.00</div>
          </div>
          <div class="row">
            <div class="label">Discount</div>
            <div class="val">₹ 70.00</div>
          </div>
          <div class="row">
            <div class="label">Tax Total</div>
            <div class="val">₹ 31.90</div>
          </div>
          <div class="row">
            <div class="label">Round Off</div>
            <div class="val">₹ 0.10</div>
          </div>
          <div class="row grand">
            <div class="label">Amount Paid</div>
            <div class="val">₹ 932.00</div>
          </div>
          <div class="row">
            <div class="label">Balance Due</div>
            <div class="val">₹ 0.00</div>
          </div>
        </div>

        <div style="margin-top:10px; font-size:13px;">
          <span class="muted">Amount in words:</span>
          <strong>Nine Hundred Thirty Two Rupees Only</strong>
        </div>
      </div>

      <!-- Footer (notes, signatures, QR) -->
      <div class="footer">
        <div class="note">
          <strong>Note:</strong> This is a computer-generated receipt and does not require a physical signature.
          All services are provided subject to hospital policies. Please retain this receipt for future reference.
        </div>
        <div class="qr">QR / Stamp</div>
        <div class="sign">
          <div class="line">Authorised Signature</div>
          <div class="muted" style="margin-top:6px;">CityCare Multispeciality Hospital</div>
        </div>
      </div>

    </div>

    <div class="muted" style="text-align:center; font-size:12px; margin-top:10px;">
      Thank you for choosing CityCare. Get well soon!
    </div>
  </div>

  <!-- (Optional) Small helper: auto-set today's date/time in IST if you want dynamic fill -->
  <script>
    // Replace the displayed date & time with the user's local (e.g., IST) at open.
    (function(){
      const metaRows = document.querySelectorAll('.section.grid.g-3 .kv .k');
      metaRows.forEach((k) => {
        if(k.textContent.trim().toLowerCase().includes('date')){
          const v = k.parentElement.querySelector('.v');
          const d = new Date();
          const opts = { day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit' };
          v.textContent = d.toLocaleString(undefined, opts);
        }
      });
    })();
  </script>
</body>
</html>

@elseif ($layout== 2)
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Hospital Payment Receipt</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 30px;
      background: #f9f9f9;
      color: #333;
    }

    .receipt {
      background: #fff;
      border: 1px solid #ccc;
      padding: 30px 40px;
      max-width: 850px;
      margin: auto;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .receipt-header {
      text-align: center;
      border-bottom: 3px solid #007BFF;
      padding-bottom: 15px;
    }

    .receipt-header h1 {
      color: #007BFF;
      margin: 0;
      font-size: 28px;
    }

    .receipt-header p {
      margin: 4px 0;
      font-size: 13px;
    }

    .receipt-title {
      text-align: center;
      font-size: 22px;
      margin-top: 20px;
      padding: 10px;
      background-color: #e9f5ff;
      color: #007BFF;
      font-weight: bold;
      border: 1px dashed #007BFF;
    }

    .section {
      margin-top: 25px;
    }

    .section h3 {
      background: #007BFF;
      color: white;
      padding: 6px 10px;
      margin-bottom: 10px;
      font-size: 16px;
    }

    .info-grid {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
    }

    .info-box {
      width: 48%;
      margin-bottom: 15px;
    }

    .info-box p {
      margin: 6px 0;
      font-size: 14px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 10px;
      font-size: 14px;
    }

    th {
      background-color: #007BFF;
      color: white;
    }

    .totals {
      float: right;
      width: 300px;
      margin-top: 25px;
    }

    .totals td {
      padding: 8px;
    }

    .totals tr:last-child td {
      font-weight: bold;
      font-size: 16px;
      background: #eaf6ff;
    }

    .footer {
      text-align: center;
      margin-top: 50px;
      font-size: 13px;
      color: #777;
      border-top: 1px dashed #ccc;
      padding-top: 10px;
    }

    .badge {
      display: inline-block;
      padding: 2px 8px;
      background: #28a745;
      color: white;
      font-size: 12px;
      border-radius: 3px;
    }

    .clearfix::after {
      content: "";
      display: table;
      clear: both;
    }
  </style>
</head>
<body>

<div class="receipt">

  <div class="receipt-header">
    <h1>Sanjeevani Multispeciality Hospital</h1>
    <p>Plot 45, Sector 21, Gurgaon, Haryana - 122001</p>
    <p>Phone: +91 9876543210 | Email: billing@sanjeevanihospital.com</p>
  </div>

  <div class="receipt-title">Hospital Payment Receipt</div>

  <div class="section">
    <h3>Receipt Details</h3>
    <div class="info-grid">
      <div class="info-box">
        <p><strong>Receipt No:</strong> REC-2025-000457</p>
        <p><strong>Date:</strong> 31st July 2025</p>
        <p><strong>Payment Mode:</strong> UPI (GPay)</p>
      </div>
      <div class="info-box" style="text-align: right;">
        <p><strong>UHID:</strong> UHID-000987</p>
        <p><strong>Transaction ID:</strong> TXN-GP-983734929</p>
        <p><strong>Status:</strong> <span class="badge">Paid</span></p>
      </div>
    </div>
  </div>

  <div class="section">
    <h3>Patient & Doctor Information</h3>
    <div class="info-grid">
      <div class="info-box">
        <p><strong>Patient Name:</strong> Anjali Sharma</p>
        <p><strong>Age/Gender:</strong> 34 / Female</p>
        <p><strong>Contact:</strong> +91 9988776655</p>
      </div>
      <div class="info-box" style="text-align: right;">
        <p><strong>Doctor:</strong> Dr. Arjun Patel</p>
        <p><strong>Department:</strong> Internal Medicine</p>
        <p><strong>Visit Type:</strong> Outpatient (OPD)</p>
      </div>
    </div>
  </div>

  <div class="section">
    <h3>Billing Summary</h3>
    <table>
      <thead>
        <tr>
          <th>S.No.</th>
          <th>Service</th>
          <th>Description</th>
          <th>Qty</th>
          <th>Rate</th>
          <th>Amount</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>Consultation</td>
          <td>Initial OPD Visit</td>
          <td>1</td>
          <td>₹500</td>
          <td>₹500</td>
        </tr>
        <tr>
          <td>2</td>
          <td>Lab Test</td>
          <td>Blood Panel</td>
          <td>1</td>
          <td>₹1200</td>
          <td>₹1200</td>
        </tr>
        <tr>
          <td>3</td>
          <td>Pharmacy</td>
          <td>5-Day Medication</td>
          <td>1</td>
          <td>₹750</td>
          <td>₹750</td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="totals">
    <table>
      <tr>
        <td>Subtotal</td>
        <td>₹2450</td>
      </tr>
      <tr>
        <td>Discount</td>
        <td>₹250</td>
      </tr>
      <tr>
        <td>Tax (0%)</td>
        <td>₹0</td>
      </tr>
      <tr>
        <td><strong>Total Payable</strong></td>
        <td><strong>₹2200</strong></td>
      </tr>
      <tr>
        <td>Amount Paid</td>
        <td>₹2200</td>
      </tr>
      <tr>
        <td>Balance Due</td>
        <td>₹0</td>
      </tr>
    </table>
  </div>

  <div class="clearfix"></div>

  <div class="section">
    <h3>Remarks</h3>
    <p>✔️ All dues cleared. Next follow-up scheduled for 5th August 2025.</p>
    <p>✔️ Lab test report will be available in the patient portal by 1st August.</p>
  </div>

  <div class="footer">
    Thank you for choosing <strong>Sanjeevani Hospital</strong>. We wish you good health and speedy recovery.<br>
    <em>This is a computer-generated receipt and does not require a physical signature.</em>
  </div>
</div>

</body>
</html>


@elseif($layout == 3)
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Hospital Payment Receipt (80mm Thermal)</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
  /* ====== PRINT SETUP (80mm) ====== */
  @page {
    size: 80mm auto;   /* Change to 58mm for smaller rolls */
    margin: 0;         /* Most thermal printers ignore margins, but set 0 */
  }
  html, body { height: 100%; }
  body {
    margin: 0;
    padding: 0;
    background: #fff;
    color: #000;
    font-family: "Courier New", ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
    font-size: 12px;          /* Adjust to 11px/10px if you need more content per slip */
    line-height: 1.25;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }

  /* ====== LAYOUT ====== */
  .receipt {
    width: 72mm;              /* Keep slightly less than paper width to avoid clipping */
    margin: 0 auto;
    padding: 4mm 3mm;
    box-sizing: border-box;
  }
  .center { text-align: center; }
  .right  { text-align: right; }
  .left   { text-align: left; }
  .bold   { font-weight: 700; }
  .small  { font-size: 11px; }
  .tiny   { font-size: 10px; }
  .mt-4   { margin-top: 4px; }
  .mt-6   { margin-top: 6px; }
  .mt-8   { margin-top: 8px; }
  .mb-4   { margin-bottom: 4px; }
  .mb-6   { margin-bottom: 6px; }
  .mb-8   { margin-bottom: 8px; }

  .row { display: flex; justify-content: space-between; align-items: baseline; }
  .row .k { color: #000; opacity: .8; }
  .row .v { font-weight: 700; }

  .hr {
    margin: 6px 0;
    border-top: 1px dashed #000;
    height: 0;
  }
  .hr-solid { border-top: 1px solid #000; margin: 6px 0; }

  /* ====== HEADER ====== */
  .brand h1 {
    font-size: 14px;
    margin: 0;
    letter-spacing: 0.5px;
  }
  .brand .sub { margin: 2px 0 0 0; }

  /* ====== ITEMS TABLE (monospace-friendly) ====== */
  .items { width: 100%; }
  .items .head,
  .items .line { display: grid; grid-template-columns: 1fr 7ch 10ch; column-gap: 6px; }
  /* 1fr (desc), 7ch (qty x rate), 10ch (amount) – ch units align digits in monospace */
  .items .head {
    font-weight: 700;
    border-bottom: 1px solid #000;
    padding-bottom: 3px;
    margin-bottom: 3px;
  }
  .items .desc { word-break: break-word; }
  .items .qtyrate, .items .amt { text-align: right; white-space: nowrap; }
  .items .line { padding: 2px 0; }

  /* ====== TOTALS ====== */
  .totals .row { margin: 2px 0; }
  .grand {
    font-size: 14px;
    font-weight: 900;
    padding: 6px 0 2px;
    border-top: 1px solid #000;
    border-bottom: 1px solid #000;
  }

  /* ====== FOOTER ====== */
  .amount-words { margin-top: 4px; }
  .note { margin-top: 6px; }
  .legal { margin-top: 4px; }

  /* ====== CUT LINE ====== */
  .cut {
    margin: 10px 0 0;
    position: relative;
    text-align: center;
  }
  .cut .dash {
    border-top: 1px dashed #000;
    margin: 2px 0 0;
  }
  .cut .scissor {
    font-size: 12px;
    position: absolute;
    left: 4mm;
    top: -8px;
  }

  /* Avoid page breaks inside key blocks */
  .no-break { page-break-inside: avoid; break-inside: avoid; }
</style>
</head>
<body>

<div class="receipt">

  <!-- HEADER -->
  <div class="brand center no-break">
    <h1 class="bold">CityCare Multispeciality Hospital</h1>
    <div class="sub tiny">
      12, Health Avenue, Sector 9, New Delhi 110001<br>
      +91 11 4000 1234 • billing@citycare.in • GSTIN: 07ABCDE1234F1Z5
    </div>
  </div>

  <div class="hr"></div>

  <!-- META -->
  <div class="no-break">
    <div class="row"><span class="k">Receipt No.</span><span class="v">RCPT-2025-01234</span></div>
    <div class="row"><span class="k">Invoice No.</span><span class="v">INV-2025-04567</span></div>
    <div class="row"><span class="k">Date & Time</span><span class="v">31-Jul-2025 12:05</span></div>
  </div>

  <div class="hr"></div>

  <!-- PATIENT / PAYMENT -->
  <div class="no-break">
    <div class="row"><span class="k">Patient</span><span class="v">Rahul Sharma</span></div>
    <div class="row"><span class="k">MRN</span><span class="v">MRN004523</span></div>
    <div class="row"><span class="k">Doctor</span><span class="v">Dr. J. Kumar</span></div>
    <div class="row"><span class="k">Method</span><span class="v">UPI</span></div>
    <div class="row"><span class="k">Ref</span><span class="v">TXN-6C29ABF921</span></div>
    <div class="row"><span class="k">Cashier</span><span class="v">Anita</span></div>
  </div>

  <div class="hr-solid"></div>

  <!-- ITEMS -->
  <div class="items no-break">
    <div class="head">
      <div>DESCRIPTION</div>
      <div class="right">Q×R</div>
      <div class="right">AMOUNT(₹)</div>
    </div>

    <div class="line">
      <div class="desc">OPD Consultation — Gen. Medicine</div>
      <div class="qtyrate">1×500.00</div>
      <div class="amt">500.00</div>
    </div>
    <div class="line">
      <div class="desc">Lab — CBC</div>
      <div class="qtyrate">1×350.00</div>
      <div class="amt">367.50</div>
    </div>
    <div class="line">
      <div class="desc">Medicine — Amoxicillin 500mg (10)</div>
      <div class="qtyrate">1×120.00</div>
      <div class="amt">134.40</div>
    </div>
  </div>

  <div class="hr"></div>

  <!-- TOTALS -->
  <div class="totals no-break">
    <div class="row"><span>Subtotal</span><span>₹ 970.00</span></div>
    <div class="row"><span>Discount</span><span>₹ 70.00</span></div>
    <div class="row"><span>Tax Total</span><span>₹ 31.90</span></div>
    <div class="row"><span>Round Off</span><span>₹ 0.10</span></div>
    <div class="row grand"><span>AMOUNT PAID</span><span>₹ 932.00</span></div>
    <div class="row"><span>Balance Due</span><span>₹ 0.00</span></div>
  </div>

  <!-- AMOUNT IN WORDS -->
  <div class="amount-words small no-break">
    Amount in words: <span class="bold">Nine Hundred Thirty Two Rupees Only</span>
  </div>

  <!-- NOTES -->
  <div class="note tiny no-break">
    Note: This is a computer-generated receipt. Prices include applicable taxes. Please retain for your records.
  </div>

  <div class="legal tiny no-break">
    PAN: AACCC1234D • CIN: U12345DL2015PTC000000
  </div>

  <!-- CUT LINE -->
  <div class="cut">
    <span class="scissor">✂</span>
    <div class="dash"></div>
  </div>

  <div class="center tiny mt-6">
    Thank you for choosing CityCare. Get well soon!
  </div>
</div>

<!-- Optional: auto-fill current date/time in local timezone -->
<script>
  (function(){
    // If you want to auto-set the date/time, replace static text:
    const matches = Array.from(document.querySelectorAll('.row .k'))
      .filter(el => /date/i.test(el.textContent||''));
    if (matches.length) {
      const v = matches[0].parentElement.querySelector('.v');
      const d = new Date();
      const pad = n => String(n).padStart(2,'0');
      v.textContent = `${pad(d.getDate())}-${['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'][d.getMonth()]}-${d.getFullYear()} ${pad(d.getHours())}:${pad(d.getMinutes())}`;
    }
  })();
</script>

</body>
</html>

@endif