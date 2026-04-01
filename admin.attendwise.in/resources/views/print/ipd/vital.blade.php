<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vital Signs Report - {{ $details->patient->first_name }} {{ $details->patient->last_name }}</title>
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: white;
        }

        /* Print-specific styles */
        @page {
            size: A4;
            margin: 0.5in;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .no-print {
                display: none !important;
            }
            
            .page-break {
                page-break-before: always;
            }
        }

        /* Header Styles */
        .print-header {
            text-align: center;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .hospital-logo {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .hospital-details {
            color: #6c757d;
            font-size: 11px;
            margin-bottom: 10px;
        }

        .report-title {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Patient Information */
        .patient-info-section {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .patient-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
        }

        .patient-name {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
        }

        .patient-id {
            font-size: 12px;
            color: #6c757d;
            background: white;
            padding: 5px 10px;
            border-radius: 15px;
            border: 1px solid #dee2e6;
        }

        .patient-details-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-size: 10px;
            font-weight: bold;
            color: #6c757d;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .detail-value {
            font-size: 12px;
            font-weight: 600;
            color: #2c3e50;
        }

        /* Vital Signs Grid */
        .vitals-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .vital-card {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .vital-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: #28a745;
        }

        .vital-card.warning::before { background: #ffc107; }
        .vital-card.critical::before { background: #dc3545; }

        .vital-icon {
            font-size: 24px;
            margin-bottom: 8px;
            color: #6c757d;
        }

        .vital-label {
            font-size: 10px;
            font-weight: bold;
            color: #6c757d;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .vital-value {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 3px;
        }

        .vital-unit {
            font-size: 9px;
            color: #6c757d;
        }

        .vital-status {
            font-size: 8px;
            padding: 2px 6px;
            border-radius: 10px;
            margin-top: 5px;
            display: inline-block;
        }

        .status-normal {
            background: #d4edda;
            color: #155724;
        }

        .status-warning {
            background: #fff3cd;
            color: #856404;
        }

        .status-critical {
            background: #f8d7da;
            color: #721c24;
        }

        /* Additional Measurements */
        .measurements-section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #2c3e50;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 5px;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .measurements-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 12px;
        }

        .measurement-item {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 10px;
            text-align: center;
        }

        .measurement-label {
            font-size: 9px;
            font-weight: bold;
            color: #6c757d;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .measurement-value {
            font-size: 14px;
            font-weight: bold;
            color: #2c3e50;
        }

        .measurement-unit {
            font-size: 8px;
            color: #6c757d;
        }

        /* Clinical Notes */
        .notes-section {
            margin-bottom: 25px;
        }

        .notes-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .note-card {
            background: #f8f9fa;
            border-left: 4px solid #28a745;
            border-radius: 0 6px 6px 0;
            padding: 12px;
        }

        .note-card.abnormal {
            border-left-color: #dc3545;
        }

        .note-title {
            font-size: 11px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .note-content {
            font-size: 11px;
            line-height: 1.5;
            color: #495057;
        }

        /* Footer */
        .print-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid #dee2e6;
            padding: 10px;
            font-size: 10px;
            color: #6c757d;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Print button (hidden when printing) */
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .print-btn:hover {
            background: #0056b3;
        }

        /* Utility Classes */
        .mb-20 { margin-bottom: 20px; }

        /* Responsive adjustments for print */
        @media print {
            .vitals-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }
            
            .measurements-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 8px;
            }
            
            .patient-details-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <!-- Print Button -->
    <button class="print-btn no-print" onclick="window.print()">🖨️ Print Report</button>

    <!-- Header -->
    <div class="print-header">
        <div class="hospital-logo">{{ $hospital->name ?? 'MEDICAL CENTER HOSPITAL' }}</div>
        <div class="hospital-details">
            {{ $hospital->address ?? '' }}<br>
            Phone: {{ $$hospital->contact_number ?? '' }} | Email: {{ $hospital->email ?? '' }}
        </div>
        <div class="report-title">Vital Signs Report</div>
    </div>

    <!-- Patient Information -->
    <div class="patient-info-section">
        <div class="patient-header">
            <div class="patient-name">{{ $details->patient->first_name ?? '' }} {{ $details->patient->last_name ?? '' }}</div>
            <div class="patient-id">Patient ID: #{{ $details->patient->uhid ?? $details->patient->id ?? 'N/A' }}</div>
        </div>
        <div class="patient-details-grid">
            <div class="detail-item">
                <div class="detail-label">Date of Birth</div>
                <div class="detail-value">{{ isset($details->patient->date_of_birth) ? date('d/m/Y', strtotime($details->patient->date_of_birth)) : 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Age</div>
                <div class="detail-value">
                    @if(isset($details->patient->date_of_birth))
                        @php
                            $dob = new DateTime($details->patient->date_of_birth);
                            $now = new DateTime();
                            $age = $now->diff($dob);
                        @endphp
                        {{ $age->y }} years, {{ $age->m }} months
                    @else
                        N/A
                    @endif
                </div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Gender</div>
                <div class="detail-value">{{ $details->patient->gender ?? 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Blood Group</div>
                <div class="detail-value">{{ $details->patient->blood_group ?? 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">IPD Number</div>
                <div class="detail-value">{{ $details->ipd_number ?? 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Admission Date</div>
                <div class="detail-value">{{ isset($details->arrival_date) ? date('d/m/Y', strtotime($details->arrival_date)) : 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Current Bed</div>
                <div class="detail-value">{{ $details->bed->bed_number ?? $details->bed_id ?? 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Attending Doctor</div>
                <div class="detail-value">{{ isset($details->doctor) ? ($details->doctor->name ?? $details->doctor->first_name ?? 'N/A') : 'N/A' }} {{ $details->doctor->last_name ?? '' }}</div>
            </div>
        </div>
    </div>

    <!-- Get Latest Vital Record -->

    @if($latestVital)
        <!-- Recording Information -->
        <div class="mb-20">
            <div class="section-title">Recording Information</div>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">
                <div class="detail-item">
                    <div class="detail-label">Recorded Date</div>
                    <div class="detail-value">{{ isset($latestVital->recorded_at) ? date('d/m/Y h:i A', strtotime($latestVital->recorded_at)) : 'N/A' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Recorded By</div>
                    <div class="detail-value">{{ isset($latestVital->user) ? ($latestVital->user->name ?? 'Unknown') : 'Unknown' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Department</div>
                    <div class="detail-value">{{ $details->department->name ?? $details->department ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        <!-- Primary Vital Signs -->
        <div class="mb-20">
            <div class="section-title">Primary Vital Signs</div>
            <div class="vitals-grid">
                <div class="vital-card {{ (isset($latestVital->heart_rate) && ($latestVital->heart_rate < 60 || $latestVital->heart_rate > 100)) ? 'warning' : '' }}">
                    <div class="vital-icon">❤️</div>
                    <div class="vital-label">Heart Rate</div>
                    <div class="vital-value">{{ $latestVital->heart_rate ?? '--' }}</div>
                    <div class="vital-unit">bpm</div>
                    <div class="vital-status {{ isset($latestVital->heart_rate) ? (($latestVital->heart_rate >= 60 && $latestVital->heart_rate <= 100) ? 'status-normal' : 'status-warning') : '' }}">
                        {{ isset($latestVital->heart_rate) ? (($latestVital->heart_rate >= 60 && $latestVital->heart_rate <= 100) ? 'Normal' : ($latestVital->heart_rate < 60 ? 'Low' : 'High')) : '' }}
                    </div>
                </div>
                
                <div class="vital-card {{ (isset($latestVital->temperature) && ($latestVital->temperature < 36.1 || $latestVital->temperature > 37.2)) ? 'warning' : '' }}">
                    <div class="vital-icon">🌡️</div>
                    <div class="vital-label">Temperature</div>
                    <div class="vital-value">{{ $latestVital->temperature ?? '--' }}</div>
                    <div class="vital-unit">°C</div>
                    <div class="vital-status {{ isset($latestVital->temperature) ? (($latestVital->temperature >= 36.1 && $latestVital->temperature <= 37.2) ? 'status-normal' : 'status-warning') : '' }}">
                        {{ isset($latestVital->temperature) ? (($latestVital->temperature >= 36.1 && $latestVital->temperature <= 37.2) ? 'Normal' : ($latestVital->temperature < 36.1 ? 'Low' : 'High')) : '' }}
                    </div>
                </div>
                
                <div class="vital-card {{ ((isset($latestVital->systolic_bp) && $latestVital->systolic_bp > 120) || (isset($latestVital->diastolic_bp) && $latestVital->diastolic_bp > 80)) ? 'warning' : '' }}">
                    <div class="vital-icon">📊</div>
                    <div class="vital-label">Blood Pressure</div>
                    <div class="vital-value">{{ $latestVital->systolic_bp ?? '--' }}/{{ $latestVital->diastolic_bp ?? '--' }}</div>
                    <div class="vital-unit">mmHg</div>
                    <div class="vital-status {{ (isset($latestVital->systolic_bp) && isset($latestVital->diastolic_bp)) ? (($latestVital->systolic_bp <= 120 && $latestVital->diastolic_bp <= 80) ? 'status-normal' : 'status-warning') : '' }}">
                        {{ (isset($latestVital->systolic_bp) && isset($latestVital->diastolic_bp)) ? (($latestVital->systolic_bp <= 120 && $latestVital->diastolic_bp <= 80) ? 'Normal' : 'Elevated') : '' }}
                    </div>
                </div>
                
                <div class="vital-card {{ (isset($latestVital->oxygen_saturation) && $latestVital->oxygen_saturation < 95) ? 'warning' : '' }}">
                    <div class="vital-icon">💨</div>
                    <div class="vital-label">Oxygen Sat</div>
                    <div class="vital-value">{{ $latestVital->oxygen_saturation ?? '--' }}</div>
                    <div class="vital-unit">%</div>
                    <div class="vital-status {{ isset($latestVital->oxygen_saturation) ? (($latestVital->oxygen_saturation >= 95) ? 'status-normal' : 'status-warning') : '' }}">
                        {{ isset($latestVital->oxygen_saturation) ? (($latestVital->oxygen_saturation >= 95) ? 'Normal' : 'Low') : '' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Measurements -->
        <div class="measurements-section">
            <div class="section-title">Additional Measurements</div>
            <div class="measurements-grid">
                <div class="measurement-item">
                    <div class="measurement-label">Respiratory Rate</div>
                    <div class="measurement-value">{{ $latestVital->respiratory_rate ?? '--' }}</div>
                    <div class="measurement-unit">breaths/min</div>
                </div>
                <div class="measurement-item">
                    <div class="measurement-label">Weight</div>
                    <div class="measurement-value">{{ $latestVital->weight ?? '--' }}</div>
                    <div class="measurement-unit">kg</div>
                </div>
                <div class="measurement-item">
                    <div class="measurement-label">Height</div>
                    <div class="measurement-value">{{ $latestVital->height ?? '--' }}</div>
                    <div class="measurement-unit">cm</div>
                </div>
                <div class="measurement-item">
                    <div class="measurement-label">BMI</div>
                    <div class="measurement-value">{{ $latestVital->bmi ?? '--' }}</div>
                    <div class="measurement-unit">
                        {{ isset($latestVital->bmi) ? ($latestVital->bmi < 18.5 ? 'Underweight' : ($latestVital->bmi < 25 ? 'Normal' : ($latestVital->bmi < 30 ? 'Overweight' : 'Obese'))) : '' }}
                    </div>
                </div>
                <div class="measurement-item">
                    <div class="measurement-label">Pain Level</div>
                    <div class="measurement-value">{{ $latestVital->pain_level ?? '--' }}{{ isset($latestVital->pain_level) ? '/10' : '' }}</div>
                    <div class="measurement-unit">
                        {{ isset($latestVital->pain_level) ? ($latestVital->pain_level <= 2 ? 'Mild' : ($latestVital->pain_level <= 5 ? 'Moderate' : ($latestVital->pain_level <= 7 ? 'Severe' : 'Extreme'))) : '' }}
                    </div>
                </div>
                <div class="measurement-item">
                    <div class="measurement-label">Blood Glucose</div>
                    <div class="measurement-value">{{ $latestVital->glucose_level ?? '--' }}</div>
                    <div class="measurement-unit">mg/dL</div>
                </div>
            </div>
        </div>

        <!-- Clinical Observations -->
        <div class="mb-20">
            <div class="section-title">Clinical Observations</div>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">
                <div class="measurement-item">
                    <div class="measurement-label">Consciousness Level</div>
                    <div class="measurement-value">{{ $latestVital->consciousness_level ?? 'Alert' }}</div>
                </div>
                <div class="measurement-item">
                    <div class="measurement-label">Pupil Response</div>
                    <div class="measurement-value">{{ $latestVital->pupil_response ?? 'Normal' }}</div>
                </div>
                <div class="measurement-item">
                    <div class="measurement-label">Urine Output</div>
                    <div class="measurement-value">{{ $latestVital->urine_output ?? '--' }}</div>
                    <div class="measurement-unit">ml</div>
                </div>
            </div>
        </div>

        <!-- Clinical Notes -->
        @if((isset($latestVital->notes) && !empty($latestVital->notes)) || (isset($latestVital->abnormal_findings) && !empty($latestVital->abnormal_findings)))
            <div class="notes-section">
                <div class="section-title">Clinical Notes</div>
                <div class="notes-grid">
                    @if(isset($latestVital->notes) && !empty($latestVital->notes))
                        <div class="note-card">
                            <div class="note-title">General Observations</div>
                            <div class="note-content">
                                {{ $latestVital->notes }}
                            </div>
                        </div>
                    @endif
                    
                    @if(isset($latestVital->abnormal_findings) && !empty($latestVital->abnormal_findings))
                        <div class="note-card abnormal">
                            <div class="note-title">Abnormal Findings</div>
                            <div class="note-content">
                                {{ $latestVital->abnormal_findings }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

    @else
        <!-- No Vitals Available -->
        <div class="mb-20">
            <div class="section-title">Vital Signs</div>
            <div style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 8px; color: #6c757d;">
                <div style="font-size: 48px; margin-bottom: 15px;">📋</div>
                <div style="font-size: 16px; font-weight: bold;">No Vital Signs Recorded</div>
                <div style="font-size: 12px; margin-top: 5px;">No vital signs have been recorded for this patient yet.</div>
            </div>
        </div>
    @endif

    <!-- Footer -->
    <div class="print-footer no-print">
        <div>Generated on: {{ date('d/m/Y h:i A') }}</div>
        <div>Page 1 of 1</div>
        <div>Confidential Medical Record</div>
    </div>
<script>
    window.onload = function() {
        window.print();
        window.addEventListener('focus', function () {
        if (printDialogOpen && printAttempted) {
            setTimeout(function() {
                window.close();
            }, 300);
        }
    });
    };
    
</script>
</body>
</html>
