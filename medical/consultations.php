<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Medical Portal - Consultations</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #7f1d1d;
            --primary-light: #b91c1c;
            --primary-soft: #fef2f2;
            --primary-gradient-start: #7f1d1d;
            --primary-gradient-end: #ef4444;
            --text-dark: #1f2937;
            --text-gray: #6b7280;
            --bg-body: #f3f4f6;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-body); min-height: 100vh; }

        /* SIDEBAR */
        .sidebar {
            width: 275px;
            background: linear-gradient(180deg, #860303 3%, #B21414 79%, #940000 97%);
            color: white;
            position: fixed;
            height: 100vh; left: 0; top: 0;
            display: flex; flex-direction: column;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        .sidebar-header { padding: 35px 25px; background: linear-gradient(180deg, #860303 0%, #6B0000 100%); }
        .sidebar-header h1 { font-size: 28px; font-weight: 700; margin-bottom: 5px; letter-spacing: -0.5px; }
        .sidebar-header p { font-size: 14px; opacity: 0.9; margin-bottom: 0; }
        .sidebar-nav { flex: 1; padding-top: 20px; }
        .nav-item {
            padding: 16px 32px; cursor: pointer; transition: all 0.3s ease;
            font-size: 15px; font-weight: 500; border-left: 4px solid transparent;
            display: flex; align-items: center; gap: 12px; color: rgba(255,255,255,0.9);
        }
        .nav-item i { font-size: 18px; }
        .nav-item:hover { background-color: rgba(255,255,255,0.1); color: white; }
        .nav-item.active { background-color: rgba(0,0,0,0.2); border-left-color: white; color: white; }

        /* MAIN */
        .main-content { margin-left: 275px; padding: 40px 50px; }
        .page-header { margin-bottom: 35px; }
        .page-header h2 { color: var(--primary-color); font-size: 36px; font-weight: 700; margin-bottom: 6px; }
        .page-header p { color: var(--text-gray); font-size: 15px; }

        /* FORM CATEGORY CARDS */
        .forms-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin-bottom: 40px;
        }
        .form-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 16px rgba(0,0,0,0.07);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
        }
        .form-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(127,29,29,0.15);
            border-color: var(--primary-light);
        }
        .form-card-header {
            padding: 28px 30px 20px;
            display: flex;
            align-items: flex-start;
            gap: 18px;
        }
        .form-card-icon {
            width: 56px; height: 56px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 26px;
            flex-shrink: 0;
        }
        .icon-consultation { background: #fef2f2; color: #b91c1c; }
        .icon-laboratory  { background: #eff6ff; color: #1d4ed8; }
        .icon-health-exam { background: #f0fdf4; color: #15803d; }
        .icon-clearance   { background: #fdf4ff; color: #7e22ce; }
        .icon-consent     { background: #fff7ed; color: #c2410c; }

        .form-card-info { flex: 1; }
        .form-card-title { font-size: 17px; font-weight: 700; color: var(--text-dark); margin-bottom: 5px; }
        .form-card-desc { font-size: 13px; color: var(--text-gray); line-height: 1.5; }
        .form-card-footer {
            padding: 14px 30px;
            background: #f9fafb;
            border-top: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13px;
            font-weight: 600;
            color: var(--primary-color);
        }
        .form-card-footer i { font-size: 16px; transition: transform 0.2s ease; }
        .form-card:hover .form-card-footer i { transform: translateX(4px); }

        .badge-form {
            background: var(--primary-soft);
            color: var(--primary-color);
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
        }

        /* SECTION HEADER */
        .section-label {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-gray);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-label::after { content: ''; flex: 1; height: 1px; background: #e5e7eb; }

        /* MODAL OVERLAY */
        .modal-overlay {
            display: none;
            position: fixed; z-index: 2000;
            inset: 0;
            background: rgba(0,0,0,0.55);
            backdrop-filter: blur(5px);
            animation: fadeIn 0.25s ease;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .modal-overlay.show { display: flex; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        .modal-box {
            background: white;
            border-radius: 18px;
            width: 100%;
            max-width: 820px;
            max-height: 92vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 24px 80px rgba(0,0,0,0.25);
            animation: slideUp 0.3s ease;
        }
        @keyframes slideUp { from { transform: translateY(40px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        .modal-head {
            background: linear-gradient(90deg, var(--primary-gradient-start), var(--primary-gradient-end));
            color: white;
            padding: 24px 30px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-shrink: 0;
        }
        .modal-head-title { font-size: 20px; font-weight: 700; margin-bottom: 3px; }
        .modal-head-sub { font-size: 13px; opacity: 0.85; }
        .modal-close-btn {
            background: rgba(255,255,255,0.2); border: none; color: white;
            width: 34px; height: 34px; border-radius: 50%; font-size: 20px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s ease; flex-shrink: 0;
        }
        .modal-close-btn:hover { background: rgba(255,255,255,0.35); transform: rotate(90deg); }
        .modal-scroll { overflow-y: auto; flex: 1; padding: 30px; }

        /* FORM ELEMENTS */
        .f-section-title {
            font-size: 14px; font-weight: 700;
            color: var(--primary-color);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 28px 0 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--primary-soft);
            display: flex; align-items: center; gap: 8px;
        }
        .f-section-title:first-child { margin-top: 0; }
        .f-grid-3 { display: grid; grid-template-columns: repeat(3,1fr); gap: 18px; margin-bottom: 18px; }
        .f-grid-2 { display: grid; grid-template-columns: repeat(2,1fr); gap: 18px; margin-bottom: 18px; }
        .f-grid-1 { display: grid; grid-template-columns: 1fr; gap: 18px; margin-bottom: 18px; }
        .f-group { display: flex; flex-direction: column; }
        .f-label {
            font-size: 12px; font-weight: 600; color: var(--text-gray);
            text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 6px;
        }
        .f-label .req { color: #ef4444; margin-left: 3px; }
        .f-control {
            padding: 10px 14px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            color: var(--text-dark);
            transition: all 0.2s ease;
            background: white;
        }
        .f-control:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(127,29,29,0.09); }
        textarea.f-control { resize: vertical; min-height: 90px; }
        select.f-control { cursor: pointer; }

        /* CHECKBOX GRID */
        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            margin-bottom: 18px;
        }
        .checkbox-grid.cols-3 { grid-template-columns: repeat(3, 1fr); }
        .check-item {
            display: flex; align-items: center; gap: 8px;
            padding: 8px 12px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-dark);
        }
        .check-item:hover { border-color: var(--primary-color); background: var(--primary-soft); }
        .check-item input[type="checkbox"] { accent-color: var(--primary-color); width: 15px; height: 15px; }
        .check-item.checked { border-color: var(--primary-color); background: var(--primary-soft); }

        /* RADIO GROUP */
        .radio-group { display: flex; gap: 12px; flex-wrap: wrap; }
        .radio-item {
            display: flex; align-items: center; gap: 6px;
            font-size: 13px; font-weight: 500; cursor: pointer;
        }
        .radio-item input[type="radio"] { accent-color: var(--primary-color); width: 15px; height: 15px; }

        /* FORM ACTIONS */
        .modal-actions {
            padding: 20px 30px;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            flex-shrink: 0;
        }
        .btn-modal-cancel {
            padding: 11px 26px; border-radius: 8px; border: none;
            background: #e5e7eb; color: var(--text-dark);
            font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 600;
            cursor: pointer; transition: all 0.2s ease;
        }
        .btn-modal-cancel:hover { background: #d1d5db; }
        .btn-modal-save {
            padding: 11px 28px; border-radius: 8px; border: none;
            background: var(--primary-color); color: white;
            font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 600;
            cursor: pointer; transition: all 0.2s ease;
            display: flex; align-items: center; gap: 8px;
        }
        .btn-modal-save:hover { background: var(--primary-light); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(127,29,29,0.3); }

        /* CONSENT TEXT BLOCK */
        .consent-text {
            background: #f9fafb;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: 18px 20px;
            font-size: 13px;
            line-height: 1.8;
            color: var(--text-dark);
            margin-bottom: 18px;
        }

        /* PHYSICIAN SIGN BOX */
        .sign-box {
            border: 1.5px dashed #d1d5db;
            border-radius: 10px;
            padding: 24px;
            text-align: center;
            color: var(--text-gray);
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 18px;
            background: #fafafa;
        }

        /* SUCCESS TOAST */
        .toast-alert {
            position: fixed; top: 28px; right: 28px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white; padding: 18px 26px; border-radius: 12px;
            box-shadow: 0 10px 30px rgba(16,185,129,0.3);
            display: none; align-items: center; gap: 14px;
            z-index: 9999; min-width: 300px;
            animation: slideInRight 0.4s ease;
        }
        .toast-alert.show { display: flex; }
        @keyframes slideInRight { from { transform: translateX(360px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .toast-alert i { font-size: 26px; }
        .toast-title { font-weight: 700; font-size: 15px; margin-bottom: 2px; }
        .toast-msg { font-size: 12px; opacity: 0.9; }

        /* PUP Header */
        .pup-header {
            text-align: center;
            padding: 16px;
            background: #f9fafb;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
        }
        .pup-header .pup-title { font-size: 15px; font-weight: 700; color: var(--text-dark); line-height: 1.4; }
        .pup-header .pup-sub { font-size: 12px; color: var(--text-gray); margin-top: 2px; }

        /* DIVIDER */
        .f-divider { border: none; border-top: 1px solid #e5e7eb; margin: 20px 0; }

        @media (max-width: 900px) {
            .forms-grid { grid-template-columns: 1fr; }
            .f-grid-3 { grid-template-columns: repeat(2,1fr); }
        }
        @media (max-width: 768px) {
            .main-content { margin-left: 0; padding: 20px; }
            .f-grid-3, .f-grid-2 { grid-template-columns: 1fr; }
            .checkbox-grid { grid-template-columns: 1fr; }
            .modal-box { max-height: 98vh; border-radius: 12px; }
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="sidebar-header">
        <h1>Nurse Portal</h1>
        <p>Clinical Management</p>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-item" data-page="dashboard"><i class="bi bi-speedometer2"></i><span>Dashboard</span></div>
        <div class="nav-item" data-page="roster"><i class="bi bi-people"></i><span>Roster</span></div>
        <div class="nav-item" data-page="appointments"><i class="bi bi-calendar-check"></i><span>Appointments</span></div>
        <div class="nav-item" data-page="records"><i class="bi bi-folder2-open"></i><span>Patient Records</span></div>
        <div class="nav-item active" data-page="consultations"><i class="bi bi-chat-dots"></i><span>Consultations</span></div>
        <div class="nav-item" data-page="certificate"><i class="bi bi-file-earmark-medical"></i><span>Medical Certificate</span></div>
        <div class="nav-item" data-page="profile"><i class="bi bi-person"></i><span>Profile</span></div>
    </nav>
</div>

<!-- MAIN -->
<main class="main-content">
    <div class="page-header">
        <h2>Consultations</h2>
        <p>Select a form to fill out and record for a patient</p>
    </div>

    <div class="section-label"><i class="bi bi-collection"></i> Available Forms</div>

    <div class="forms-grid">

        <!-- 1. General Consultation -->
        <div class="form-card" onclick="openModal('consultation')">
            <div class="form-card-header">
                <div class="form-card-icon icon-consultation"><i class="bi bi-chat-square-text"></i></div>
                <div class="form-card-info">
                    <div class="form-card-title">General Consultation Record</div>
                    <div class="form-card-desc">Document walk-in consultations, complaints, diagnosis, treatment given, and follow-up instructions.</div>
                </div>
            </div>
            <div class="form-card-footer">
                <span>Open Form</span>
                <i class="bi bi-arrow-right"></i>
            </div>
        </div>

        <!-- 2. Laboratory Exam Request -->
        <div class="form-card" onclick="openModal('laboratory')">
            <div class="form-card-header">
                <div class="form-card-icon icon-laboratory"><i class="bi bi-eyedropper"></i></div>
                <div class="form-card-info">
                    <div class="form-card-title">Laboratory Examination Request</div>
                    <div class="form-card-desc">Issue requests for Chest X-Ray, ECG, CBC, urinalysis, blood chemistry, and other lab tests.</div>
                </div>
            </div>
            <div class="form-card-footer">
                <span>Open Form</span>
                <i class="bi bi-arrow-right"></i>
            </div>
        </div>

        <!-- 3. Health Examination Record -->
        <div class="form-card" onclick="openModal('healthexam')">
            <div class="form-card-header">
                <div class="form-card-icon icon-health-exam"><i class="bi bi-clipboard2-pulse"></i></div>
                <div class="form-card-info">
                    <div class="form-card-title">Health Examination Record</div>
                    <div class="form-card-desc">Complete health exam form covering medical history, family history, physical findings, and working impression.</div>
                </div>
            </div>
            <div class="form-card-footer">
                <span>Open Form</span>
                <i class="bi bi-arrow-right"></i>
            </div>
        </div>

        <!-- 4. Medical Clearance (Physically Fit) -->
        <div class="form-card" onclick="openModal('clearance')">
            <div class="form-card-header">
                <div class="form-card-icon icon-clearance"><i class="bi bi-shield-check"></i></div>
                <div class="form-card-info">
                    <div class="form-card-title">Medical Clearance — Physically Fit</div>
                    <div class="form-card-desc">Issue PUP Medical Services clearance certifying that a student or employee is physically fit for a specified purpose.</div>
                </div>
            </div>
            <div class="form-card-footer">
                <span>Open Form</span>
                <i class="bi bi-arrow-right"></i>
            </div>
        </div>

        <!-- 5. Data Privacy Consent Form -->
        <div class="form-card" onclick="openModal('consent')">
            <div class="form-card-header">
                <div class="form-card-icon icon-consent"><i class="bi bi-file-earmark-lock2"></i></div>
                <div class="form-card-info">
                    <div class="form-card-title">Declaration & Data Subject Consent</div>
                    <div class="form-card-desc">Record patient consent to the collection and processing of personal health information in compliance with the Data Privacy Act of 2012.</div>
                </div>
            </div>
            <div class="form-card-footer">
                <span>Open Form</span>
                <i class="bi bi-arrow-right"></i>
            </div>
        </div>

    </div>
</main>

<!-- ============================================================ -->
<!-- MODAL: General Consultation -->
<!-- ============================================================ -->
<div class="modal-overlay" id="modal-consultation">
    <div class="modal-box">
        <div class="modal-head">
            <div>
                <div class="modal-head-title"><i class="bi bi-chat-square-text me-2"></i>General Consultation Record</div>
                <div class="modal-head-sub">PUP Medical Services Department</div>
            </div>
            <button class="modal-close-btn" onclick="closeModal('consultation')">&times;</button>
        </div>
        <div class="modal-scroll">
            <form id="form-consultation" onsubmit="saveForm(event,'consultation')">

                <div class="f-section-title"><i class="bi bi-person"></i> Patient Information</div>
                <div class="f-grid-3">
                    <div class="f-group">
                        <label class="f-label">Patient Name <span class="req">*</span></label>
                        <input class="f-control" type="text" placeholder="Full Name" required>
                    </div>
                    <div class="f-group">
                        <label class="f-label">ID Number <span class="req">*</span></label>
                        <input class="f-control" type="text" placeholder="e.g., 2023-12345-MN-0" required>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Date <span class="req">*</span></label>
                        <input class="f-control" type="date" required id="c-date">
                    </div>
                </div>
                <div class="f-grid-3">
                    <div class="f-group">
                        <label class="f-label">Time</label>
                        <input class="f-control" type="time" id="c-time">
                    </div>
                    <div class="f-group">
                        <label class="f-label">Consultation Type <span class="req">*</span></label>
                        <select class="f-control" required>
                            <option value="">Select type</option>
                            <option>General Consultation</option>
                            <option>Follow-up</option>
                            <option>Emergency</option>
                            <option>Injury Report</option>
                        </select>
                    </div>
                    <div class="f-group">
                        <label class="f-label">College / Department</label>
                        <input class="f-control" type="text" placeholder="e.g., CCS">
                    </div>
                </div>

                <div class="f-section-title"><i class="bi bi-thermometer-half"></i> Vital Signs</div>
                <div class="f-grid-3">
                    <div class="f-group">
                        <label class="f-label">Blood Pressure (mmHg)</label>
                        <input class="f-control" type="text" placeholder="e.g., 120/80">
                    </div>
                    <div class="f-group">
                        <label class="f-label">Temperature (°C)</label>
                        <input class="f-control" type="text" placeholder="e.g., 36.8">
                    </div>
                    <div class="f-group">
                        <label class="f-label">Heart Rate (bpm)</label>
                        <input class="f-control" type="text" placeholder="e.g., 80">
                    </div>
                    <div class="f-group">
                        <label class="f-label">Respiratory Rate (/min)</label>
                        <input class="f-control" type="text" placeholder="e.g., 18">
                    </div>
                    <div class="f-group">
                        <label class="f-label">Height (cm)</label>
                        <input class="f-control" type="text" placeholder="e.g., 165">
                    </div>
                    <div class="f-group">
                        <label class="f-label">Weight (kg)</label>
                        <input class="f-control" type="text" placeholder="e.g., 60">
                    </div>
                </div>

                <div class="f-section-title"><i class="bi bi-journal-medical"></i> Clinical Information</div>
                <div class="f-group f-grid-1">
                    <label class="f-label">Chief Complaint / Symptoms <span class="req">*</span></label>
                    <textarea class="f-control" placeholder="Describe the main complaint or symptoms..." required></textarea>
                </div>
                <div class="f-grid-2">
                    <div class="f-group">
                        <label class="f-label">Physical Examination Findings</label>
                        <textarea class="f-control" placeholder="Enter PE findings..."></textarea>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Diagnosis / Assessment <span class="req">*</span></label>
                        <textarea class="f-control" placeholder="Enter diagnosis..." required></textarea>
                    </div>
                </div>
                <div class="f-group f-grid-1">
                    <label class="f-label">Treatment & Recommendations <span class="req">*</span></label>
                    <textarea class="f-control" placeholder="Describe treatment given..." required></textarea>
                </div>
                <div class="f-grid-2">
                    <div class="f-group">
                        <label class="f-label">Medications Given</label>
                        <textarea class="f-control" placeholder="List medications and dosage..." style="min-height:75px;"></textarea>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Follow-up Date</label>
                        <input class="f-control" type="date">
                    </div>
                </div>
                <div class="f-group">
                    <label class="f-label">Additional Notes</label>
                    <textarea class="f-control" placeholder="Any additional observations or instructions..."></textarea>
                </div>
            </form>
        </div>
        <div class="modal-actions">
            <button class="btn-modal-cancel" type="button" onclick="closeModal('consultation')"><i class="bi bi-x-circle"></i> Cancel</button>
            <button class="btn-modal-save" type="button" onclick="document.getElementById('form-consultation').requestSubmit()"><i class="bi bi-check-circle"></i> Save Record</button>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL: Laboratory Examination Request -->
<!-- ============================================================ -->
<div class="modal-overlay" id="modal-laboratory">
    <div class="modal-box">
        <div class="modal-head">
            <div>
                <div class="modal-head-title"><i class="bi bi-eyedropper me-2"></i>Laboratory Examination Request</div>
                <div class="modal-head-sub">PUP-LAFO-6-MEDS-001 · Rev 0 · Medical Services Department</div>
            </div>
            <button class="modal-close-btn" onclick="closeModal('laboratory')">&times;</button>
        </div>
        <div class="modal-scroll">
            <form id="form-laboratory" onsubmit="saveForm(event,'laboratory')">

                <div class="pup-header">
                    <div class="pup-title">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES<br>Medical Services Department — Sta. Mesa, Manila</div>
                    <div class="pup-sub">LABORATORY EXAMINATION FORM</div>
                </div>

                <div class="f-section-title"><i class="bi bi-person"></i> Patient Information</div>
                <div class="f-grid-3">
                    <div class="f-group" style="grid-column: span 2;">
                        <label class="f-label">Name <span class="req">*</span></label>
                        <input class="f-control" type="text" placeholder="Full Name" required>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Date <span class="req">*</span></label>
                        <input class="f-control" type="date" required id="lab-date">
                    </div>
                </div>
                <div class="f-grid-2">
                    <div class="f-group">
                        <label class="f-label">Age</label>
                        <input class="f-control" type="number" placeholder="e.g., 20" min="1" max="100">
                    </div>
                    <div class="f-group">
                        <label class="f-label">ID Number</label>
                        <input class="f-control" type="text" placeholder="e.g., 2023-12345-MN-0">
                    </div>
                </div>

                <div class="f-section-title"><i class="bi bi-list-check"></i> Request For</div>

                <p style="font-size:13px; color:var(--text-gray); margin-bottom:14px;">Check all tests to be requested:</p>

                <label class="f-label" style="margin-bottom:10px;">Chest X-Ray</label>
                <div class="checkbox-grid cols-3" style="margin-bottom:20px;">
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> PA (Postero-Anterior)</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> AP-LAT</label>
                </div>

                <label class="f-label" style="margin-bottom:10px;">General Tests</label>
                <div class="checkbox-grid" style="margin-bottom:20px;">
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> ECG</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Urinalysis</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Fecalysis</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Drug Test</label>
                </div>

                <label class="f-label" style="margin-bottom:10px;">Blood Chemistry</label>
                <div class="checkbox-grid cols-3" style="margin-bottom:20px;">
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> CBC</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> FBS</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> BUN</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Creatinine</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Total Cholesterol</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Triglycerides</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> HDL</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> LDL</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Uric Acid</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> SGPT</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Hepatitis B</label>
                </div>

                <div class="f-group">
                    <label class="f-label">Others (specify)</label>
                    <input class="f-control" type="text" placeholder="Specify other tests...">
                </div>

                <hr class="f-divider">
                <div class="sign-box">
                    <div style="font-weight:700; font-size:15px; color:var(--text-dark); margin-bottom:4px;">FELICITAS A. BERMUDEZ, M.D.</div>
                    <div style="font-size:12px;">Lic. # 0115224 — Medical Officer III, PUP MSD</div>
                    <div style="font-size:11px; color:var(--text-gray); margin-top:6px;">Physician's Signature on printed form</div>
                </div>
                <div class="f-group">
                    <label class="f-label">Requesting Physician Override / Notes</label>
                    <input class="f-control" type="text" placeholder="Add notes if needed...">
                </div>
            </form>
        </div>
        <div class="modal-actions">
            <button class="btn-modal-cancel" onclick="closeModal('laboratory')"><i class="bi bi-x-circle"></i> Cancel</button>
            <button class="btn-modal-save" onclick="document.getElementById('form-laboratory').requestSubmit()"><i class="bi bi-check-circle"></i> Submit Request</button>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL: Health Examination Record -->
<!-- ============================================================ -->
<div class="modal-overlay" id="modal-healthexam">
    <div class="modal-box" style="max-width:900px;">
        <div class="modal-head">
            <div>
                <div class="modal-head-title"><i class="bi bi-clipboard2-pulse me-2"></i>Health Examination Record</div>
                <div class="modal-head-sub">PUP Medical Services Department — Faculty, Employee & Student</div>
            </div>
            <button class="modal-close-btn" onclick="closeModal('healthexam')">&times;</button>
        </div>
        <div class="modal-scroll">
            <form id="form-healthexam" onsubmit="saveForm(event,'healthexam')">

                <div class="pup-header">
                    <div class="pup-title">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES<br>HEALTH EXAMINATION RECORD</div>
                    <div class="pup-sub">Office of the VP for Administration — Medical Services Department</div>
                </div>

                <!-- Patient Info -->
                <div class="f-section-title"><i class="bi bi-person-badge"></i> Patient Information</div>
                <div class="f-grid-2">
                    <div class="f-group">
                        <label class="f-label">Name <span class="req">*</span></label>
                        <input class="f-control" type="text" placeholder="Last, First Middle" required>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Date <span class="req">*</span></label>
                        <input class="f-control" type="date" required id="he-date">
                    </div>
                </div>
                <div class="f-grid-2">
                    <div class="f-group">
                        <label class="f-label">Address</label>
                        <input class="f-control" type="text" placeholder="Complete Address">
                    </div>
                    <div class="f-group">
                        <label class="f-label">College / Department</label>
                        <input class="f-control" type="text" placeholder="e.g., CCS">
                    </div>
                </div>
                <div class="f-grid-3">
                    <div class="f-group">
                        <label class="f-label">Contact No.</label>
                        <input class="f-control" type="text" placeholder="09XX-XXX-XXXX">
                    </div>
                    <div class="f-group">
                        <label class="f-label">Course / School Year</label>
                        <input class="f-control" type="text" placeholder="e.g., BSIT / AY 2025-2026">
                    </div>
                    <div class="f-group">
                        <label class="f-label">Age</label>
                        <input class="f-control" type="number" placeholder="Age" min="1">
                    </div>
                </div>
                <div class="f-grid-3">
                    <div class="f-group">
                        <label class="f-label">Sex</label>
                        <select class="f-control">
                            <option value="">Select</option>
                            <option>Male</option>
                            <option>Female</option>
                        </select>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Civil Status</label>
                        <select class="f-control">
                            <option value="">Select</option>
                            <option>Single</option>
                            <option>Married</option>
                            <option>Widowed</option>
                        </select>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Emergency Contact No.</label>
                        <input class="f-control" type="text" placeholder="09XX-XXX-XXXX">
                    </div>
                </div>
                <div class="f-group">
                    <label class="f-label">Contact Person In Case of Emergency</label>
                    <input class="f-control" type="text" placeholder="Full Name & Relationship">
                </div>

                <!-- I. Past Medical History -->
                <div class="f-section-title"><i class="bi bi-clock-history"></i> I. Past Medical History</div>
                <p style="font-size:12px;color:var(--text-gray);margin-bottom:10px;">Leave blank for No, check for Yes. Include age if applicable.</p>
                <label class="f-label">Childhood Illness</label>
                <div class="checkbox-grid cols-3" style="margin-bottom:16px;">
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Asthma</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Chicken Pox</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Heart Disease</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Measles</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Seizure Disorder</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Hyperventilation</label>
                </div>
                <div class="f-grid-3" style="margin-bottom:16px;">
                    <div class="f-group">
                        <label class="f-label">Other Childhood Illness</label>
                        <input class="f-control" type="text" placeholder="Specify...">
                    </div>
                    <div class="f-group">
                        <label class="f-label">Previous Hospitalization</label>
                        <div class="radio-group" style="padding-top:10px;">
                            <label class="radio-item"><input type="radio" name="hospitalization" value="No"> No</label>
                            <label class="radio-item"><input type="radio" name="hospitalization" value="Yes"> Yes</label>
                        </div>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Operation / Surgery</label>
                        <div class="radio-group" style="padding-top:10px;">
                            <label class="radio-item"><input type="radio" name="surgery" value="No"> No</label>
                            <label class="radio-item"><input type="radio" name="surgery" value="Yes"> Yes</label>
                        </div>
                    </div>
                </div>
                <div class="f-grid-2">
                    <div class="f-group">
                        <label class="f-label">Current Medications (N/A if none)</label>
                        <input class="f-control" type="text" placeholder="List medications...">
                    </div>
                    <div class="f-group">
                        <label class="f-label">Allergies (N/A if none)</label>
                        <input class="f-control" type="text" placeholder="Food, medicine, etc...">
                    </div>
                </div>

                <!-- II. Family History -->
                <div class="f-section-title"><i class="bi bi-people"></i> II. Family History</div>
                <p style="font-size:12px;color:var(--text-gray);margin-bottom:10px;">Check if present. Indicate MS (mother's side) or FS (father's side).</p>
                <div class="checkbox-grid cols-3" style="margin-bottom:16px;">
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Diabetes</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> PTB</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Hypertension</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Cancer</label>
                </div>
                <div class="f-group">
                    <label class="f-label">Other Family History (specify with MS/FS)</label>
                    <input class="f-control" type="text" placeholder="e.g., Heart Disease - FS">
                </div>

                <!-- III. Personal History -->
                <div class="f-section-title"><i class="bi bi-person-check"></i> III. Personal History</div>
                <div class="f-grid-3">
                    <div class="f-group">
                        <label class="f-label">Cigarette Smoking</label>
                        <div class="radio-group" style="padding-top:10px;">
                            <label class="radio-item"><input type="radio" name="smoking" value="No"> No</label>
                            <label class="radio-item"><input type="radio" name="smoking" value="Yes"> Yes</label>
                        </div>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Alcohol Drinking</label>
                        <div class="radio-group" style="padding-top:10px;">
                            <label class="radio-item"><input type="radio" name="alcohol" value="No"> No</label>
                            <label class="radio-item"><input type="radio" name="alcohol" value="Yes"> Yes</label>
                        </div>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Traveled Abroad</label>
                        <div class="radio-group" style="padding-top:10px;">
                            <label class="radio-item"><input type="radio" name="travel" value="No"> No</label>
                            <label class="radio-item"><input type="radio" name="travel" value="Yes"> Yes</label>
                        </div>
                    </div>
                </div>

                <!-- IV. Physical Examination -->
                <div class="f-section-title"><i class="bi bi-stethoscope"></i> IV. Physical Examination</div>
                <div class="f-grid-3" style="margin-bottom:16px;">
                    <div class="f-group">
                        <label class="f-label">Vital Signs</label>
                        <div class="radio-group" style="padding-top:10px;">
                            <label class="radio-item"><input type="radio" name="distress" value="Not in Distress"> Not in Distress</label>
                            <label class="radio-item"><input type="radio" name="distress" value="In Distress"> In Distress</label>
                        </div>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Height (cm)</label>
                        <input class="f-control" type="text" placeholder="e.g., 165">
                    </div>
                    <div class="f-group">
                        <label class="f-label">Weight (kg)</label>
                        <input class="f-control" type="text" placeholder="e.g., 60">
                    </div>
                    <div class="f-group">
                        <label class="f-label">BMI</label>
                        <input class="f-control" type="text" placeholder="Auto or manual">
                    </div>
                    <div class="f-group">
                        <label class="f-label">Blood Pressure</label>
                        <input class="f-control" type="text" placeholder="e.g., 120/80">
                    </div>
                    <div class="f-group">
                        <label class="f-label">HR (/min)</label>
                        <input class="f-control" type="text" placeholder="e.g., 80">
                    </div>
                    <div class="f-group">
                        <label class="f-label">RR (/min)</label>
                        <input class="f-control" type="text" placeholder="e.g., 18">
                    </div>
                    <div class="f-group">
                        <label class="f-label">Temperature (°C)</label>
                        <input class="f-control" type="text" placeholder="e.g., 36.8">
                    </div>
                    <div class="f-group">
                        <label class="f-label">1st Day of Last Menstruation <span style="font-size:10px;">(Female)</span></label>
                        <input class="f-control" type="date">
                    </div>
                </div>

                <label class="f-label" style="margin-bottom:10px;">Head</label>
                <div class="checkbox-grid cols-3" style="margin-bottom:16px;">
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Wound</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Mass</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Alopecia</label>
                </div>

                <label class="f-label" style="margin-bottom:10px;">Eyes</label>
                <div class="checkbox-grid cols-3" style="margin-bottom:16px;">
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> w/o Glasses</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> w/ Glasses</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Anicteric Sclera</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Pink Palpebral Conjunctiva</label>
                </div>

                <label class="f-label" style="margin-bottom:10px;">Ears</label>
                <div class="checkbox-grid cols-3" style="margin-bottom:16px;">
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> No Gross Deformity</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> No Discharge</label>
                </div>

                <label class="f-label" style="margin-bottom:10px;">Throat</label>
                <div class="checkbox-grid cols-3" style="margin-bottom:16px;">
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> No TPC</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> No Mass</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> No Lymphadenopathy</label>
                </div>

                <label class="f-label" style="margin-bottom:10px;">Chest / Lungs</label>
                <div class="checkbox-grid cols-3" style="margin-bottom:10px;">
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Normal</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Wheeze</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Rales</label>
                </div>

                <label class="f-label" style="margin-bottom:10px;">Chest X-Ray Result</label>
                <div class="radio-group" style="margin-bottom:16px;">
                    <label class="radio-item"><input type="radio" name="cxr" value="Normal"> Normal</label>
                    <label class="radio-item"><input type="radio" name="cxr" value="With findings"> With Findings</label>
                </div>

                <label class="f-label" style="margin-bottom:10px;">Breast</label>
                <div class="radio-group" style="margin-bottom:16px;">
                    <label class="radio-item"><input type="radio" name="breast" value="Normal"> Normal</label>
                </div>

                <label class="f-label" style="margin-bottom:10px;">Heart</label>
                <div class="f-grid-2" style="margin-bottom:16px;">
                    <div>
                        <label class="f-label">Murmur</label>
                        <div class="radio-group">
                            <label class="radio-item"><input type="radio" name="murmur" value="Present"> Present</label>
                            <label class="radio-item"><input type="radio" name="murmur" value="Absent"> Absent</label>
                        </div>
                    </div>
                    <div>
                        <label class="f-label">Rhythm</label>
                        <div class="radio-group">
                            <label class="radio-item"><input type="radio" name="rhythm" value="Regular"> Regular</label>
                            <label class="radio-item"><input type="radio" name="rhythm" value="Irregular"> Irregular</label>
                        </div>
                    </div>
                </div>

                <label class="f-label" style="margin-bottom:10px;">Abdomen</label>
                <div class="radio-group" style="margin-bottom:16px;">
                    <label class="radio-item"><input type="radio" name="abdomen" value="Normal"> Normal</label>
                </div>

                <label class="f-label" style="margin-bottom:10px;">Extremities</label>
                <div class="radio-group" style="margin-bottom:16px;">
                    <label class="radio-item"><input type="radio" name="extremities" value="No Deformities"> No Deformities</label>
                </div>

                <label class="f-label" style="margin-bottom:10px;">Vertebral Column</label>
                <div class="radio-group" style="margin-bottom:16px;">
                    <label class="radio-item"><input type="radio" name="vertebral" value="Normal"> Normal</label>
                    <label class="radio-item"><input type="radio" name="vertebral" value="With Deformity"> With Deformity</label>
                </div>

                <label class="f-label" style="margin-bottom:10px;">Skin</label>
                <div class="checkbox-grid cols-3" style="margin-bottom:10px;">
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Pallor</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Rashes</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Lesions</label>
                </div>
                <div class="f-grid-2" style="margin-bottom:16px;">
                    <div>
                        <label class="f-label">Scars</label>
                        <div class="radio-group">
                            <label class="radio-item"><input type="radio" name="scars" value="Absent"> Absent</label>
                            <label class="radio-item"><input type="radio" name="scars" value="Present"> Present</label>
                        </div>
                    </div>
                </div>

                <!-- Working Impression -->
                <div class="f-section-title"><i class="bi bi-clipboard-check"></i> Working Impression</div>
                <div class="f-grid-2">
                    <div class="f-group">
                        <label class="f-label">Fit <span class="req">*</span></label>
                        <input class="f-control" type="text" placeholder="e.g., Fit for work / Fit for school" required>
                    </div>
                    <div class="f-group">
                        <label class="f-label">For Work-Up</label>
                        <input class="f-control" type="text" placeholder="Specify if any...">
                    </div>
                </div>
                <div class="f-group">
                    <label class="f-label">Referred to</label>
                    <div class="checkbox-grid cols-3" style="margin-bottom:8px;">
                        <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Cardio</label>
                        <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Pulmo</label>
                        <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Derma</label>
                        <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> ENT</label>
                        <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Optha</label>
                    </div>
                    <input class="f-control" type="text" placeholder="Others (specify)..." style="margin-top:8px;">
                </div>
                <div class="f-grid-2">
                    <div class="f-group">
                        <label class="f-label">Follow-up Date</label>
                        <input class="f-control" type="date">
                    </div>
                    <div class="f-group">
                        <label class="f-label">Physician's Notes</label>
                        <textarea class="f-control" style="min-height:75px;" placeholder="Additional notes..."></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-actions">
            <button class="btn-modal-cancel" onclick="closeModal('healthexam')"><i class="bi bi-x-circle"></i> Cancel</button>
            <button class="btn-modal-save" onclick="document.getElementById('form-healthexam').requestSubmit()"><i class="bi bi-check-circle"></i> Save Record</button>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL: Medical Clearance -->
<!-- ============================================================ -->
<div class="modal-overlay" id="modal-clearance">
    <div class="modal-box">
        <div class="modal-head">
            <div>
                <div class="modal-head-title"><i class="bi bi-shield-check me-2"></i>Medical Clearance — Physically Fit</div>
                <div class="modal-head-sub">PUP Medical Services Department · Sta. Mesa, Manila</div>
            </div>
            <button class="modal-close-btn" onclick="closeModal('clearance')">&times;</button>
        </div>
        <div class="modal-scroll">
            <form id="form-clearance" onsubmit="saveForm(event,'clearance')">

                <div class="pup-header">
                    <div class="pup-title">Republic of the Philippines<br>POLYTECHNIC UNIVERSITY OF THE PHILIPPINES · Manila</div>
                    <div class="pup-sub" style="font-weight:700; font-size:15px; margin-top:6px; color:var(--primary-color);">Medical CLEARANCE — PHYSICALLY FIT</div>
                </div>

                <div class="f-section-title"><i class="bi bi-person"></i> Patient Information</div>
                <div class="f-grid-3">
                    <div class="f-group" style="grid-column:span 2;">
                        <label class="f-label">Patient's Full Name <span class="req">*</span></label>
                        <input class="f-control" type="text" placeholder="Last, First Middle" required>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Date of Clearance <span class="req">*</span></label>
                        <input class="f-control" type="date" required id="cl-date">
                    </div>
                </div>
                <div class="f-grid-2">
                    <div class="f-group">
                        <label class="f-label">ID / Student Number</label>
                        <input class="f-control" type="text" placeholder="e.g., 2023-12345-MN-0">
                    </div>
                    <div class="f-group">
                        <label class="f-label">College / Department</label>
                        <input class="f-control" type="text" placeholder="e.g., CCS">
                    </div>
                </div>

                <div class="f-section-title"><i class="bi bi-file-text"></i> Clearance Details</div>

                <div class="consent-text" style="font-size:14px; line-height:2;">
                    This is to certify that
                    <strong style="border-bottom:1px solid #aaa; padding: 0 40px; display:inline-block; min-width:250px;">&nbsp;</strong>
                    has been examined by the undersigned and found to be <strong>physically fit</strong> at the time of examination.
                </div>

                <div class="f-group">
                    <label class="f-label">Purpose of Clearance <span class="req">*</span></label>
                    <input class="f-control" type="text" placeholder="e.g., Off-campus activity, Practicum, Enrollment, Graduation" required>
                </div>
                <div class="f-group">
                    <label class="f-label">Additional Remarks / Conditions</label>
                    <textarea class="f-control" placeholder="e.g., With noted conditions but cleared for the stated purpose..." style="min-height:80px;"></textarea>
                </div>

                <div class="f-section-title"><i class="bi bi-pen"></i> Issuing Physician</div>
                <div class="f-grid-2">
                    <div class="f-group">
                        <label class="f-label">Physician's Name <span class="req">*</span></label>
                        <input class="f-control" type="text" value="FELICITAS A. BERMUDEZ, M.D." required>
                    </div>
                    <div class="f-group">
                        <label class="f-label">License Number</label>
                        <input class="f-control" type="text" value="0115224">
                    </div>
                </div>
                <div class="sign-box">
                    <i class="bi bi-pen" style="font-size:24px; margin-bottom:8px; display:block;"></i>
                    Physician's signature will be affixed on the printed form
                </div>
            </form>
        </div>
        <div class="modal-actions">
            <button class="btn-modal-cancel" onclick="closeModal('clearance')"><i class="bi bi-x-circle"></i> Cancel</button>
            <button class="btn-modal-save" onclick="document.getElementById('form-clearance').requestSubmit()"><i class="bi bi-check-circle"></i> Issue Clearance</button>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL: Data Privacy Consent Form -->
<!-- ============================================================ -->
<div class="modal-overlay" id="modal-consent">
    <div class="modal-box">
        <div class="modal-head">
            <div>
                <div class="modal-head-title"><i class="bi bi-file-earmark-lock2 me-2"></i>Declaration & Data Subject Consent</div>
                <div class="modal-head-sub">Medical 40 · PUP Medical Services Department</div>
            </div>
            <button class="modal-close-btn" onclick="closeModal('consent')">&times;</button>
        </div>
        <div class="modal-scroll">
            <form id="form-consent" onsubmit="saveForm(event,'consent')">

                <div class="pup-header">
                    <div class="pup-title">Declaration of Medical Information and<br>Data Subject Consent Form</div>
                    <div class="pup-sub">Polytechnic University of the Philippines — Medical Services Department</div>
                </div>

                <div class="f-section-title"><i class="bi bi-person"></i> Patient Information</div>
                <div class="f-grid-3">
                    <div class="f-group" style="grid-column:span 2;">
                        <label class="f-label">Full Name <span class="req">*</span></label>
                        <input class="f-control" type="text" placeholder="Last, First Middle" required>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Date <span class="req">*</span></label>
                        <input class="f-control" type="date" required id="con-date">
                    </div>
                </div>
                <div class="f-grid-2">
                    <div class="f-group">
                        <label class="f-label">Student / Employee ID</label>
                        <input class="f-control" type="text" placeholder="ID Number">
                    </div>
                    <div class="f-group">
                        <label class="f-label">College / Department</label>
                        <input class="f-control" type="text" placeholder="e.g., CCS">
                    </div>
                </div>

                <div class="f-section-title"><i class="bi bi-file-text"></i> Declaration of Medical Information</div>
                <div class="consent-text">
                    I hereby certify that the medical health information given to the physician and nurses of Polytechnic University of the Philippines (PUP) during my on-site consultation for the issuance of medical clearance for off-campus activity/ies are true, correct and complete to the best of my knowledge. I have fully disclosed all the medical condition that may affect in the assessment to endorse my participation in the activity/ties as a student of PUP.
                    <br><br>
                    I also understand that the PUP Medical Services and University will not be liable for any untoward incident that may arise due to my failure to disclose accurate information or intentionally providing false and deceptive information.
                </div>

                <div class="f-group">
                    <label class="f-label">Activity / Purpose of Consultation <span class="req">*</span></label>
                    <input class="f-control" type="text" placeholder="e.g., Off-campus immersion, OJT, Sports event..." required>
                </div>

                <div class="f-section-title"><i class="bi bi-shield-lock"></i> Data Privacy Consent</div>
                <div class="consent-text">
                    In compliance with the <strong>Data Privacy Act of 2012</strong> and its Implementing Rules and Regulations, I voluntarily consent to the collection, processing and storage of my personal and health information for the purpose/s of health assessment, treatment, and/or research (following research ethics guidelines) for the improvement of healthcare services.
                </div>

                <div class="f-section-title"><i class="bi bi-pen"></i> Signatures</div>
                <div class="f-grid-2">
                    <div class="f-group">
                        <label class="f-label">Student's Printed Name <span class="req">*</span></label>
                        <input class="f-control" type="text" placeholder="Student Full Name" required>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Date Signed</label>
                        <input class="f-control" type="date" id="con-sign-date">
                    </div>
                </div>
                <div class="sign-box">
                    <i class="bi bi-pen" style="font-size:22px; margin-bottom:6px; display:block;"></i>
                    Student's Signature Over Printed Name — to be signed on printed form
                </div>

                <div class="f-group">
                    <label class="f-label">Remarks</label>
                    <textarea class="f-control" placeholder="Additional remarks from the nurse or physician..." style="min-height:70px;"></textarea>
                </div>

                <div style="background:#fff7ed; border:1.5px solid #fed7aa; border-radius:10px; padding:14px 16px; font-size:12px; color:#92400e; margin-top:10px;">
                    <i class="bi bi-info-circle"></i> <strong>Note:</strong> Both student and guardian must affix their signature if the student is aged below 18 years old. The guardian's signature section will be available on the printed form.
                </div>
            </form>
        </div>
        <div class="modal-actions">
            <button class="btn-modal-cancel" onclick="closeModal('consent')"><i class="bi bi-x-circle"></i> Cancel</button>
            <button class="btn-modal-save" onclick="document.getElementById('form-consent').requestSubmit()"><i class="bi bi-check-circle"></i> Save Consent Record</button>
        </div>
    </div>
</div>

<!-- SUCCESS TOAST -->
<div id="toast" class="toast-alert">
    <i class="bi bi-check-circle-fill"></i>
    <div>
        <div class="toast-title" id="toast-title">Record Saved!</div>
        <div class="toast-msg" id="toast-msg">The form has been successfully saved.</div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script>
    /* NAV */
    const pageFiles = { dashboard:'nurse_dashboard.php', roster:'nurse_roster.php', appointments:'appointments.php', records:'patient_records.php', consultations:'consultations.php', certificate:'medical_cert.php', profile:'profile.php' };
    document.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', function() {
            const f = pageFiles[this.getAttribute('data-page')];
            if (f) window.location.href = f;
        });
    });

    /* SET DEFAULTS */
    const today = new Date().toISOString().split('T')[0];
    const now = new Date();
    const hh = String(now.getHours()).padStart(2,'0');
    const mm = String(now.getMinutes()).padStart(2,'0');
    ['c-date','lab-date','he-date','cl-date','con-date','con-sign-date'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = today;
    });
    const ct = document.getElementById('c-time');
    if (ct) ct.value = `${hh}:${mm}`;

    /* MODAL CONTROLS */
    function openModal(id) {
        document.getElementById('modal-' + id).classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        document.getElementById('modal-' + id).classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    /* CLOSE ON BACKDROP CLICK */
    document.querySelectorAll('.modal-overlay').forEach(m => {
        m.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('show');
                document.body.style.overflow = 'auto';
            }
        });
    });

    /* ESC KEY */
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.show').forEach(m => {
                m.classList.remove('show');
                document.body.style.overflow = 'auto';
            });
        }
    });

    /* CHECKBOX STYLING */
    function toggleCheck(el) {
        el.closest('.check-item').classList.toggle('checked', el.checked);
    }

    /* SAVE FORM */
    const formTitles = {
        consultation: 'Consultation Saved!',
        laboratory: 'Lab Request Submitted!',
        healthexam: 'Health Exam Record Saved!',
        clearance: 'Medical Clearance Issued!',
        consent: 'Consent Record Saved!'
    };
    const formMsgs = {
        consultation: 'The consultation record has been successfully documented.',
        laboratory: 'Laboratory examination request has been recorded.',
        healthexam: 'Health examination record has been saved successfully.',
        clearance: 'Medical clearance has been issued and recorded.',
        consent: 'Patient consent form has been saved.'
    };

    function saveForm(e, id) {
        e.preventDefault();
        closeModal(id);
        showToast(formTitles[id], formMsgs[id]);
        setTimeout(() => {
            document.getElementById('form-' + id).reset();
            // Re-set dates
            ['c-date','lab-date','he-date','cl-date','con-date','con-sign-date'].forEach(did => {
                const el = document.getElementById(did);
                if (el) el.value = today;
            });
        }, 400);
    }

    /* TOAST */
    function showToast(title, msg) {
        document.getElementById('toast-title').textContent = title;
        document.getElementById('toast-msg').textContent = msg;
        const toast = document.getElementById('toast');
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 3500);
    }
</script>
</body>
</html>