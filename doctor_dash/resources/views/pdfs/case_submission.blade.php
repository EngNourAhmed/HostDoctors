<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Case Submission - {{ $clinical_data['patient_info']['name'] ?? 'Case' }}</title>
    <style>
        @page { margin: 0; background-color: #0c0c0c; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #ffffff; margin: 0; padding: 0; background-color: #0c0c0c; }
        .container { padding: 40px; }
        .header { border-left: 6px solid #FACC15; padding-left: 20px; margin-bottom: 40px; }
        .header h1 { margin: 0; color: #FACC15; text-transform: uppercase; font-size: 28px; letter-spacing: 2px; }
        .header p { margin: 5px 0 0; color: #666; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .section { margin-bottom: 30px; }
        .section-title { color: #FACC15; font-size: 14px; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .card { background-color: #111111; border-radius: 16px; padding: 20px; border: 1px solid rgba(255, 255, 255, 0.05); }
        .grid { width: 100%; border-collapse: collapse; }
        .grid td { padding: 8px 0; vertical-align: top; }
        .label { color: #777; font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; width: 35%; }
        .value { color: #fff; font-size: 13px; font-weight: 500; }
        .description-box { background-color: rgba(255, 255, 255, 0.02); border-radius: 12px; padding: 20px; font-size: 13px; line-height: 1.6; color: #ccc; border: 1px solid rgba(255, 255, 255, 0.05); direction: rtl; text-align: right; }
        .file-list { margin-top: 10px; }
        .file-item { padding: 12px; background: rgba(255, 255, 255, 0.03); border-radius: 10px; margin-bottom: 8px; font-size: 11px; border: 1px solid rgba(255, 255, 255, 0.05); }
        .file-name { color: #FACC15; font-weight: bold; }
        .footer { position: fixed; bottom: 40px; left: 40px; right: 40px; text-align: center; font-size: 9px; color: #444; border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Case Submission</h1>
            <p>Official Record &bull; Batch ID: {{ substr($batch_id, 0, 8) }} &bull; {{ date('Y-m-d H:i') }}</p>
        </div>

        <!-- 1. Patient & Case Metadata -->
        <div style="width: 100%; display: table; margin-bottom: 30px;">
            <div style="display: table-row;">
                <div style="display: table-cell; width: 48%; vertical-align: top;">
                    <div class="section-title">Patient Information</div>
                    <div class="card">
                        <table class="grid">
                            <tr><td class="label">Name</td><td class="value">{{ $clinical_data['patient_info']['name'] ?? 'N/A' }}</td></tr>
                            <tr><td class="label">Age/Gender</td><td class="value">{{ $clinical_data['patient_info']['age'] ?? 'N/A' }} / {{ $clinical_data['patient_info']['gender'] ?? 'N/A' }}</td></tr>
                            <tr><td class="label">Case Date</td><td class="value">{{ $clinical_data['patient_info']['case_date'] ?? 'N/A' }}</td></tr>
                            <tr><td class="label">Surgery Date</td><td class="value text-[#FACC15]">{{ $clinical_data['patient_info']['surgery_date'] ?? 'N/A' }}</td></tr>
                        </table>
                    </div>
                </div>
                <div style="display: table-cell; width: 4%;"></div>
                <div style="display: table-cell; width: 48%; vertical-align: top;">
                    <div class="section-title">Doctor Information</div>
                    <div class="card">
                        <table class="grid">
                            <tr><td class="label">Doctor</td><td class="value">{{ $clinical_data['doctor_info']['name'] ?? 'N/A' }}</td></tr>
                            <tr><td class="label">Clinic</td><td class="value">{{ $clinical_data['doctor_info']['clinic_name'] ?? 'N/A' }}</td></tr>
                            <tr><td class="label">Address</td><td class="value">{{ $clinical_data['doctor_info']['clinic_address'] ?? 'N/A' }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Case Details -->
        <div class="section">
            <div class="section-title">Structural & Clinical Overview</div>
            <div class="card">
                <table class="grid">
                    <tr>
                        <td class="label">Arch to Treat</td><td class="value">{{ $clinical_data['case_overview']['arch'] ?? 'N/A' }}</td>
                        <td class="label">Immediate Loading</td><td class="value">{{ $clinical_data['case_overview']['immediate_loading'] ?? 'No' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Opposing Arch</td><td class="value">{{ $clinical_data['case_overview']['opposing_arch'] ?? 'N/A' }}</td>
                        <td class="label">Final Prosthesis</td><td class="value">{{ $clinical_data['case_overview']['final_prosthesis'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Current Condition</td><td class="value">{{ $clinical_data['case_overview']['condition'] ?? 'N/A' }}</td>
                        <td class="label">Provisional Required</td><td class="value">{{ $clinical_data['case_overview']['provisional_required'] ?? 'No' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Guide Type</td><td class="value">{{ $clinical_data['case_overview']['guide_type'] ?? 'Standard' }}</td>
                        <td class="label">Selected Shade</td><td class="value text-[#FACC15]">{{ $clinical_data['case_overview']['shade'] ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- 3. Implant System Details -->
        <div class="section">
            <div class="section-title">Implant & Component Specifications</div>
            <div class="card">
                <table class="grid">
                    <tr>
                        <td class="label">Implant Brand</td><td class="value">{{ $clinical_data['implant_system']['brand'] ?? 'N/A' }}</td>
                        <td class="label">System/Line</td><td class="value">{{ $clinical_data['implant_system']['system'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Implants Planned</td><td class="value">{{ $clinical_data['case_overview']['implants_planned'] ?? 0 }} Units</td>
                        <td class="label">Implant Sizes</td><td class="value">{{ $clinical_data['implant_system']['sizes'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">MUA Components</td><td class="value">{{ $clinical_data['implant_system']['mua'] ?? 'N/A' }}</td>
                        <td class="label">Fixation Pins</td><td class="value">{{ $clinical_data['implant_system']['pins'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Bone Reduction</td><td class="value">{{ $clinical_data['implant_system']['bone_reduction'] ?? 'N/A' }}</td>
                        <td class="label">MUA/Abutment Shade</td><td class="value">{{ $clinical_data['prescription']['shade'] ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- 4. Lab Instructions -->
        <div class="section">
            <div class="section-title">Prescription & Lab Instructions</div>
            <div class="description-box" style="margin-bottom: 20px;">
                <strong>Lab Instructions:</strong><br>
                {{ $clinical_data['prescription']['lab_instructions'] ?? ($description ?: 'No additional instructions.') }}
            </div>
            @if(isset($clinical_data['prescription']['prosthesis_design']))
            <div class="description-box">
                <strong>Prosthesis Design Notes:</strong><br>
                {{ $clinical_data['prescription']['prosthesis_design'] }}
            </div>
            @endif
        </div>

        <!-- 5. Logistics -->
        <div class="section">
            <div class="section-title">Logistics & Delivery</div>
            <div class="card">
                <table class="grid">
                    <tr><td class="label">Preferred Date</td><td class="value">{{ $clinical_data['logistics']['preferred_date'] ?? 'N/A' }}</td></tr>
                    <tr><td class="label">Shipping Address</td><td class="value">{{ $clinical_data['logistics']['shipping_address'] ?? 'N/A' }}</td></tr>
                    @if(isset($clinical_data['logistics']['comments']))
                    <tr><td class="label">Shipping Comments</td><td class="value">{{ $clinical_data['logistics']['comments'] }}</td></tr>
                    @endif
                </table>
            </div>
        </div>

        @if(!empty($uploaded_files))
        <div class="section">
            <div class="section-title">Attached Records ({{ count($uploaded_files) }})</div>
            <div class="file-list">
                @foreach($uploaded_files as $file)
                <div class="file-item">
                    <span class="file-name">{{ $file['original_name'] }}</span>
                    <span style="color: #666; margin-left: 10px;">• Category: {{ $file['category'] ?? 'General' }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="footer">
            <p>Signature: {{ $clinical_data['prescription']['signature'] ?? 'ELECTRONICALLY SIGNED' }}</p>
            <p>This document is an automated clinical record generated by BoneHard Surgical Planning Systems. CONFIDENTIAL.</p>
        </div>
    </div>
</body>
</html>
