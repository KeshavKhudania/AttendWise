<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1024">
    <title>{{ $title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 20px;
            font-size: 14px;
            color: #000;
        }
        .header {
            border-bottom: 2px solid black;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 22px;
            color: #0072bc;
        }
        .header .iso {
            font-size: 14px;
            background: #0072bc;
            color: #fff;
            display: inline-block;
            padding: 2px 8px;
            margin-top: 2px;
        }
        .patient_details td {
            padding: 2px 5px;
            font-size: 13px;
            vertical-align: top;
        }
        .vitals-table td {
            border: 1px solid #000;
            padding: 3px;
            font-size: 12px;
        }
        .vitals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .findings-table td, .findings-table th {
            border: 1px solid #000;
            padding: 3px;
            font-size: 12px;
            vertical-align: top;
        }
        .findings-table {
            width: 100%;
            border-collapse: collapse;
        }
        .rx-symbol {
            font-size: 28px;
            border: 2px solid #000;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        @media print {
            .btn {
                display: none !important;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid">

    <!-- Hospital Header -->
    <div class="header d-flex justify-content-between">
        <div>
            <h2>{{$hospital->name}}</h2>
            <div class="iso">ISO 9001:2015 Certified</div>
            <div style="font-size: 12px;">
                {{$hospital->address}}<br>
                Mobile: +91-{{$hospital->contact_number}} | Email: {{$hospital->email}}<br>
            </div>
        </div>
        <div>
            <img src="../hospit_images/{{$hospital->img}}" alt="Logo" height="70">
        </div>
    </div>

    <!-- Patient Details -->
    <table class="patient_details" style="width: 100%;">
        <tr>
            <td>UHID No.: {{ $OpdDetails->patient->uhid }}</td>
            <td>
                <div style="border:1px dotted black;max-width:max-content;padding:0 50px 0 10px;">
                    Queue No: {{ $OpdDetails->queue_number }}
                </div>
            </td>
        </tr>
        <tr>
            <td>Name: {{ $OpdDetails->patient->first_name }} {{ $OpdDetails->patient->last_name }}</td>
            <td>OPD No.: {{ $OpdDetails->opd_id }}</td>
        </tr>
        <tr>
            <td>Date: {{ $OpdDetails->created_at->format('d-M-Y h:i:s A') }}</td>
            <td>Age/Gen: {{ \Carbon\Carbon::parse($OpdDetails->patient->date_of_birth)->age }}Y / {{ $OpdDetails->patient->gender }}</td>
        </tr>
        <tr>
            <td>Department: {{ $OpdDetails->department->name }}</td>
            <td>Mobile: {{ $OpdDetails->patient->phone_number }}</td>
        </tr>
        <tr>
            <td>Dr. {{ $OpdDetails->doctor->name }}</td>
            <td>Address: {{ $OpdDetails->patient->address }}</td>
        </tr>
    </table>

    <!-- Main Content -->
    <div class="row mt-2">
        <!-- Vitals Section -->
        <div class="col-4">
            <table class="vitals-table">
                <tr><td>Pulse:</td></tr>
                <tr><td>BP:</td></tr>
                <tr><td>Temp:</td></tr>
                <tr><td>RR:</td></tr>
                <tr><td>SPO₂:</td></tr>
                <tr><td>Weight:</td></tr>
                <tr><td>Height:</td></tr>
                <tr><td>BMI:</td></tr>
                <tr><td>BSL F:</td></tr>
                <tr><td>BSL PP:</td></tr>
                <tr><td>BSL R:</td></tr>
                <tr><td>ECG:</td></tr>
                <tr><td>SPO₂:</td></tr>
                <tr><td>Others:</td></tr>
            </table>
        </div>

        <!-- Findings / Treatment Section -->
        <div class="col-8">
            <table class="findings-table">
                <tr>
                    <th colspan="5">History / Control Findings / Treatment / Instructions</th>
                </tr>
                <tr>
                    <td><input type="checkbox"> New Patient</td>
                    <td><input type="checkbox"> Repeat</td>
                    <td><input type="checkbox"> Follow-Up</td>
                    <td><input type="checkbox"> Referral</td>
                    <td><input type="checkbox"> Others</td>
                </tr>
                <tr>
                    <td colspan="5" style="height:150px; vertical-align:top;">Notes...</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Rx and Barcode -->
    <div class="d-flex justify-content-between mt-3">
        <div class="rx-symbol">℞</div>
        <div class="text-end">
            <img src="https://barcode.tec-it.com/barcode.ashx?data={{ $OpdDetails->patient->uhid }}&code=Code128&translate-esc=false" height="70" alt="Barcode" />
        </div>
    </div>

    <!-- Print Button -->
    <button class="btn btn-primary mt-3" onclick="window.print()">Print</button>

</div>
</body>
</html>
