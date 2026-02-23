<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Portal - Patient Records</title>
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
        .page-header { margin-bottom: 30px; }
        .page-header h2 { color: var(--primary-color); font-size: 36px; font-weight: 700; margin-bottom: 6px; }
        .page-header p { color: var(--text-gray); font-size: 15px; }

        /* TAB SWITCHER */
        .tab-switcher {
            display: flex;
            background: white;
            border-radius: 12px;
            padding: 6px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            margin-bottom: 24px;
            width: fit-content;
            gap: 4px;
        }
        .tab-btn {
            padding: 10px 28px;
            border: none;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
            color: var(--text-gray);
            background: transparent;
            display: flex; align-items: center; gap: 8px;
        }
        .tab-btn i { font-size: 16px; }
        .tab-btn.active {
            background: linear-gradient(90deg, var(--primary-gradient-start), var(--primary-gradient-end));
            color: white;
            box-shadow: 0 4px 12px rgba(127,29,29,0.3);
        }
        .tab-btn:not(.active):hover { background: var(--primary-soft); color: var(--primary-color); }

        /* SEARCH */
        .search-section {
            background: white;
            padding: 18px 28px;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            margin-bottom: 22px;
            display: flex;
            gap: 12px;
            align-items: center;
        }
        .search-input {
            flex: 1; padding: 11px 18px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px; font-size: 14px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.2s ease;
        }
        .search-input:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(127,29,29,0.09); }
        .btn-search {
            padding: 11px 28px;
            background: var(--primary-color); color: white;
            border: none; border-radius: 8px;
            font-size: 14px; font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer; transition: all 0.2s ease;
        }
        .btn-search:hover { background: var(--primary-light); transform: translateY(-1px); }

        /* RECORDS TABLE */
        .records-section {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            overflow: hidden;
        }
        .records-header {
            background: linear-gradient(90deg, var(--primary-gradient-start), var(--primary-gradient-end));
            color: white;
            padding: 20px 30px;
            font-size: 18px;
            font-weight: 600;
            display: flex; align-items: center; gap: 10px;
        }
        .table { margin-bottom: 0; }
        .table thead th {
            background: var(--primary-soft);
            padding: 15px 20px;
            font-weight: 700;
            color: var(--primary-color);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }
        .table tbody td {
            padding: 16px 20px;
            color: var(--text-dark);
            font-weight: 500;
            vertical-align: middle;
            border-color: #f3f4f6;
            font-size: 13.5px;
        }
        .table tbody tr:hover { background: #fafafa; }

        /* TYPE BADGE */
        .type-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }
        .badge-consultation { background: #fef2f2; color: #b91c1c; }
        .badge-laboratory   { background: #eff6ff; color: #1d4ed8; }
        .badge-healthexam   { background: #f0fdf4; color: #15803d; }
        .badge-clearance    { background: #fdf4ff; color: #7e22ce; }
        .badge-consent      { background: #fff7ed; color: #c2410c; }

        .btn-edit-record {
            padding: 7px 16px;
            background: var(--primary-color); color: white;
            border: none; border-radius: 6px;
            font-size: 12px; font-weight: 600;
            cursor: pointer; transition: all 0.2s ease;
            font-family: 'Poppins', sans-serif;
        }
        .btn-edit-record:hover { background: var(--primary-light); }

        .no-results {
            padding: 60px 30px; text-align: center;
            color: var(--text-gray); font-size: 15px;
        }
        .no-results i { font-size: 44px; opacity: 0.25; margin-bottom: 12px; display: block; }

        /* ===================== MODAL OVERLAY ===================== */
        .modal-overlay {
            display: none; position: fixed; z-index: 2000;
            inset: 0; background: rgba(0,0,0,0.55);
            backdrop-filter: blur(5px);
            animation: fadeIn 0.25s ease;
            align-items: center; justify-content: center;
            padding: 20px;
        }
        .modal-overlay.show { display: flex; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        .modal-box {
            background: white; border-radius: 18px;
            width: 100%; max-width: 820px;
            max-height: 92vh; overflow: hidden;
            display: flex; flex-direction: column;
            box-shadow: 0 24px 80px rgba(0,0,0,0.25);
            animation: slideUp 0.3s ease;
        }
        .modal-box.wide { max-width: 920px; }
        @keyframes slideUp { from { transform: translateY(40px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        .modal-head {
            background: linear-gradient(90deg, var(--primary-gradient-start), var(--primary-gradient-end));
            color: white; padding: 22px 30px;
            display: flex; justify-content: space-between; align-items: flex-start;
            flex-shrink: 0;
        }
        .modal-head-title { font-size: 19px; font-weight: 700; margin-bottom: 3px; }
        .modal-head-sub { font-size: 12px; opacity: 0.85; }
        .modal-close-btn {
            background: rgba(255,255,255,0.2); border: none; color: white;
            width: 34px; height: 34px; border-radius: 50%; font-size: 20px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s ease; flex-shrink: 0;
        }
        .modal-close-btn:hover { background: rgba(255,255,255,0.35); transform: rotate(90deg); }
        .modal-scroll { overflow-y: auto; flex: 1; padding: 28px; }

        /* FORM ELEMENTS */
        .f-section-title {
            font-size: 13px; font-weight: 700;
            color: var(--primary-color);
            text-transform: uppercase; letter-spacing: 0.5px;
            margin: 26px 0 14px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--primary-soft);
            display: flex; align-items: center; gap: 8px;
        }
        .f-section-title:first-child { margin-top: 0; }
        .f-grid-3 { display: grid; grid-template-columns: repeat(3,1fr); gap: 16px; margin-bottom: 16px; }
        .f-grid-2 { display: grid; grid-template-columns: repeat(2,1fr); gap: 16px; margin-bottom: 16px; }
        .f-grid-1 { display: grid; grid-template-columns: 1fr; gap: 16px; margin-bottom: 16px; }
        .f-group { display: flex; flex-direction: column; }
        .f-label {
            font-size: 11px; font-weight: 700; color: var(--text-gray);
            text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 5px;
        }
        .f-label .req { color: #ef4444; margin-left: 3px; }
        .f-control {
            padding: 9px 13px;
            border: 1.5px solid #e5e7eb; border-radius: 8px;
            font-family: 'Poppins', sans-serif; font-size: 13px;
            color: var(--text-dark); transition: all 0.2s ease; background: white;
        }
        .f-control:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(127,29,29,0.09); }
        textarea.f-control { resize: vertical; min-height: 85px; }
        select.f-control { cursor: pointer; }

        /* CHECKBOX */
        .checkbox-grid { display: grid; grid-template-columns: repeat(2,1fr); gap: 8px; margin-bottom: 16px; }
        .checkbox-grid.cols-3 { grid-template-columns: repeat(3,1fr); }
        .check-item {
            display: flex; align-items: center; gap: 8px;
            padding: 7px 11px; border: 1.5px solid #e5e7eb; border-radius: 8px;
            cursor: pointer; transition: all 0.2s ease;
            font-size: 12.5px; font-weight: 500; color: var(--text-dark);
        }
        .check-item:hover { border-color: var(--primary-color); background: var(--primary-soft); }
        .check-item input[type="checkbox"] { accent-color: var(--primary-color); width: 14px; height: 14px; }
        .check-item.checked { border-color: var(--primary-color); background: var(--primary-soft); }

        /* RADIO */
        .radio-group { display: flex; gap: 12px; flex-wrap: wrap; }
        .radio-item { display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 500; cursor: pointer; }
        .radio-item input[type="radio"] { accent-color: var(--primary-color); width: 14px; height: 14px; }

        /* MODAL ACTIONS */
        .modal-actions {
            padding: 18px 28px; background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            display: flex; gap: 12px; justify-content: flex-end;
            flex-shrink: 0;
        }
        .btn-modal-cancel {
            padding: 10px 24px; border-radius: 8px; border: none;
            background: #e5e7eb; color: var(--text-dark);
            font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600;
            cursor: pointer; transition: all 0.2s ease;
        }
        .btn-modal-cancel:hover { background: #d1d5db; }
        .btn-modal-save {
            padding: 10px 26px; border-radius: 8px; border: none;
            background: var(--primary-color); color: white;
            font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600;
            cursor: pointer; transition: all 0.2s ease;
            display: flex; align-items: center; gap: 8px;
        }
        .btn-modal-save:hover { background: var(--primary-light); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(127,29,29,0.3); }

        /* MISC */
        .consent-text {
            background: #f9fafb; border: 1.5px solid #e5e7eb;
            border-radius: 10px; padding: 16px 18px;
            font-size: 13px; line-height: 1.8; color: var(--text-dark); margin-bottom: 16px;
        }
        .sign-box {
            border: 1.5px dashed #d1d5db; border-radius: 10px;
            padding: 22px; text-align: center;
            color: var(--text-gray); font-size: 13px; font-weight: 500;
            margin-bottom: 16px; background: #fafafa;
        }
        .pup-header {
            text-align: center; padding: 14px;
            background: #f9fafb; border-radius: 10px;
            margin-bottom: 18px; border: 1px solid #e5e7eb;
        }
        .pup-header .pup-title { font-size: 14px; font-weight: 700; color: var(--text-dark); line-height: 1.4; }
        .pup-header .pup-sub { font-size: 12px; color: var(--text-gray); margin-top: 2px; }
        .f-divider { border: none; border-top: 1px solid #e5e7eb; margin: 18px 0; }

        /* TOAST */
        .toast-alert {
            position: fixed; top: 28px; right: 28px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white; padding: 16px 24px; border-radius: 12px;
            box-shadow: 0 10px 30px rgba(16,185,129,0.3);
            display: none; align-items: center; gap: 12px;
            z-index: 9999; min-width: 280px;
            animation: slideInRight 0.4s ease;
        }
        .toast-alert.show { display: flex; }
        @keyframes slideInRight { from { transform: translateX(360px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .toast-alert i { font-size: 24px; }
        .toast-title { font-weight: 700; font-size: 14px; margin-bottom: 2px; }
        .toast-msg { font-size: 12px; opacity: 0.9; }

        /* SECTION PANEL */
        .tab-panel { display: none; }
        .tab-panel.active { display: block; }

        @media (max-width: 768px) {
            .main-content { margin-left: 0; padding: 20px; }
            .f-grid-3, .f-grid-2 { grid-template-columns: 1fr; }
            .checkbox-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="sidebar-header">
        <h1>Medical Portal</h1>
        <p>Clinical Management</p>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-item" data-page="dashboard"><i class="bi bi-speedometer2"></i><span>Dashboard</span></div>
        <div class="nav-item" data-page="roster"><i class="bi bi-people"></i><span>Roster</span></div>
        <div class="nav-item" data-page="appointments"><i class="bi bi-calendar-check"></i><span>Appointments</span></div>
        <div class="nav-item active" data-page="records"><i class="bi bi-folder2-open"></i><span>Patient Records</span></div>
        <div class="nav-item" data-page="consultations"><i class="bi bi-chat-dots"></i><span>Consultations</span></div>
        <div class="nav-item" data-page="certificate"><i class="bi bi-file-earmark-medical"></i><span>Medical Certificate</span></div>
        <div class="nav-item" data-page="profile"><i class="bi bi-person"></i><span>Profile</span></div>
    </nav>
</div>

<!-- MAIN -->
<main class="main-content">
    <div class="page-header">
        <h2>Patient Records</h2>
        <p>Digital medical records and consultation history</p>
    </div>

    <!-- TAB SWITCHER -->
    <div class="tab-switcher">
        <button class="tab-btn active" onclick="switchTab('students')" id="tab-students">
            <i class="bi bi-mortarboard"></i> Students
        </button>
        <button class="tab-btn" onclick="switchTab('faculty')" id="tab-faculty">
            <i class="bi bi-person-badge"></i> Faculty / Non-Teaching Staff
        </button>
    </div>

    <!-- STUDENTS PANEL -->
    <div class="tab-panel active" id="panel-students">
        <div class="search-section">
            <input type="text" class="search-input" id="searchStudents" placeholder="Search student records by name, diagnosis, or type...">
            <button class="btn-search" onclick="filterRecords('students')"><i class="bi bi-search"></i> Search</button>
        </div>
        <div class="records-section">
            <div class="records-header">
                <i class="bi bi-folder2-open"></i> Student Records
            </div>
            <div style="overflow-x:auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Patient Name</th>
                        <th>ID No.</th>
                        <th>Form Type</th>
                        <th>Diagnosis / Purpose</th>
                        <th>Follow-up</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tbody-students"></tbody>
            </table>
            </div>
        </div>
    </div>

    <!-- FACULTY PANEL -->
    <div class="tab-panel" id="panel-faculty">
        <div class="search-section">
            <input type="text" class="search-input" id="searchFaculty" placeholder="Search faculty/staff records by name, diagnosis, or type...">
            <button class="btn-search" onclick="filterRecords('faculty')"><i class="bi bi-search"></i> Search</button>
        </div>
        <div class="records-section">
            <div class="records-header">
                <i class="bi bi-folder2-open"></i> Faculty / Non-Teaching Staff Records
            </div>
            <div style="overflow-x:auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Patient Name</th>
                        <th>Department</th>
                        <th>Form Type</th>
                        <th>Diagnosis / Purpose</th>
                        <th>Follow-up</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tbody-faculty"></tbody>
            </table>
            </div>
        </div>
    </div>
</main>

<!-- ===================== MODAL: General Consultation ===================== -->
<div class="modal-overlay" id="modal-edit-consultation">
    <div class="modal-box">
        <div class="modal-head">
            <div>
                <div class="modal-head-title"><i class="bi bi-chat-square-text me-2"></i>Edit — General Consultation Record</div>
                <div class="modal-head-sub">PUP Medical Services Department</div>
            </div>
            <button class="modal-close-btn" onclick="closeEditModal('consultation')">&times;</button>
        </div>
        <div class="modal-scroll">
            <form id="edit-form-consultation" onsubmit="updateRecord(event,'consultation')">
                <div class="f-section-title"><i class="bi bi-person"></i> Patient Information</div>
                <div class="f-grid-3">
                    <div class="f-group">
                        <label class="f-label">Patient Name <span class="req">*</span></label>
                        <input class="f-control" type="text" id="ec-name" required>
                    </div>
                    <div class="f-group">
                        <label class="f-label">ID Number <span class="req">*</span></label>
                        <input class="f-control" type="text" id="ec-id" required>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Date <span class="req">*</span></label>
                        <input class="f-control" type="date" id="ec-date" required>
                    </div>
                </div>
                <div class="f-grid-3">
                    <div class="f-group">
                        <label class="f-label">Time</label>
                        <input class="f-control" type="time" id="ec-time">
                    </div>
                    <div class="f-group">
                        <label class="f-label">Consultation Type <span class="req">*</span></label>
                        <select class="f-control" id="ec-type" required>
                            <option value="">Select type</option>
                            <option>General Consultation</option>
                            <option>Follow-up</option>
                            <option>Emergency</option>
                            <option>Injury Report</option>
                        </select>
                    </div>
                    <div class="f-group">
                        <label class="f-label">College / Department</label>
                        <input class="f-control" type="text" id="ec-dept">
                    </div>
                </div>

                <div class="f-section-title"><i class="bi bi-thermometer-half"></i> Vital Signs</div>
                <div class="f-grid-3">
                    <div class="f-group"><label class="f-label">Blood Pressure (mmHg)</label><input class="f-control" type="text" id="ec-bp" placeholder="120/80"></div>
                    <div class="f-group"><label class="f-label">Temperature (°C)</label><input class="f-control" type="text" id="ec-temp" placeholder="36.8"></div>
                    <div class="f-group"><label class="f-label">Heart Rate (bpm)</label><input class="f-control" type="text" id="ec-hr" placeholder="80"></div>
                    <div class="f-group"><label class="f-label">Respiratory Rate (/min)</label><input class="f-control" type="text" id="ec-rr" placeholder="18"></div>
                    <div class="f-group"><label class="f-label">Height (cm)</label><input class="f-control" type="text" id="ec-ht" placeholder="165"></div>
                    <div class="f-group"><label class="f-label">Weight (kg)</label><input class="f-control" type="text" id="ec-wt" placeholder="60"></div>
                </div>

                <div class="f-section-title"><i class="bi bi-journal-medical"></i> Clinical Information</div>
                <div class="f-grid-1">
                    <div class="f-group"><label class="f-label">Chief Complaint / Symptoms <span class="req">*</span></label><textarea class="f-control" id="ec-complaint" required></textarea></div>
                </div>
                <div class="f-grid-2">
                    <div class="f-group"><label class="f-label">Physical Examination Findings</label><textarea class="f-control" id="ec-findings"></textarea></div>
                    <div class="f-group"><label class="f-label">Diagnosis / Assessment <span class="req">*</span></label><textarea class="f-control" id="ec-diagnosis" required></textarea></div>
                </div>
                <div class="f-grid-1">
                    <div class="f-group"><label class="f-label">Treatment & Recommendations <span class="req">*</span></label><textarea class="f-control" id="ec-treatment" required></textarea></div>
                </div>
                <div class="f-grid-2">
                    <div class="f-group"><label class="f-label">Medications Given</label><textarea class="f-control" id="ec-meds" style="min-height:70px;"></textarea></div>
                    <div class="f-group"><label class="f-label">Follow-up Date</label><input class="f-control" type="date" id="ec-followup"></div>
                </div>
                <div class="f-group"><label class="f-label">Additional Notes</label><textarea class="f-control" id="ec-notes"></textarea></div>
            </form>
        </div>
        <div class="modal-actions">
            <button class="btn-modal-cancel" onclick="closeEditModal('consultation')"><i class="bi bi-x-circle"></i> Cancel</button>
            <button class="btn-modal-save" onclick="document.getElementById('edit-form-consultation').requestSubmit()"><i class="bi bi-check-circle"></i> Save Changes</button>
        </div>
    </div>
</div>

<!-- ===================== MODAL: Laboratory Exam ===================== -->
<div class="modal-overlay" id="modal-edit-laboratory">
    <div class="modal-box">
        <div class="modal-head">
            <div>
                <div class="modal-head-title"><i class="bi bi-eyedropper me-2"></i>Edit — Laboratory Examination Request</div>
                <div class="modal-head-sub">PUP-LAFO-6-MEDS-001 · Rev 0 · Medical Services Department</div>
            </div>
            <button class="modal-close-btn" onclick="closeEditModal('laboratory')">&times;</button>
        </div>
        <div class="modal-scroll">
            <form id="edit-form-laboratory" onsubmit="updateRecord(event,'laboratory')">
                <div class="pup-header">
                    <div class="pup-title">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES<br>Medical Services Department — Sta. Mesa, Manila</div>
                    <div class="pup-sub">LABORATORY EXAMINATION FORM</div>
                </div>
                <div class="f-section-title"><i class="bi bi-person"></i> Patient Information</div>
                <div class="f-grid-3">
                    <div class="f-group" style="grid-column:span 2;">
                        <label class="f-label">Name <span class="req">*</span></label>
                        <input class="f-control" type="text" id="el-name" required>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Date <span class="req">*</span></label>
                        <input class="f-control" type="date" id="el-date" required>
                    </div>
                </div>
                <div class="f-grid-2">
                    <div class="f-group"><label class="f-label">Age</label><input class="f-control" type="number" id="el-age" min="1" max="100"></div>
                    <div class="f-group"><label class="f-label">ID Number</label><input class="f-control" type="text" id="el-id"></div>
                </div>
                <div class="f-section-title"><i class="bi bi-list-check"></i> Request For</div>
                <p style="font-size:13px;color:var(--text-gray);margin-bottom:12px;">Check all tests requested:</p>
                <label class="f-label" style="margin-bottom:8px;">Chest X-Ray</label>
                <div class="checkbox-grid cols-3" style="margin-bottom:16px;" id="el-xray">
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> PA (Postero-Anterior)</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> AP-LAT</label>
                </div>
                <label class="f-label" style="margin-bottom:8px;">General Tests</label>
                <div class="checkbox-grid" style="margin-bottom:16px;" id="el-general">
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> ECG</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Urinalysis</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Fecalysis</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Drug Test</label>
                </div>
                <label class="f-label" style="margin-bottom:8px;">Blood Chemistry</label>
                <div class="checkbox-grid cols-3" style="margin-bottom:16px;" id="el-blood">
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
                <div class="f-group"><label class="f-label">Others (specify)</label><input class="f-control" type="text" id="el-others"></div>
                <hr class="f-divider">
                <div class="sign-box">
                    <div style="font-weight:700;font-size:15px;color:var(--text-dark);margin-bottom:4px;">FELICITAS A. BERMUDEZ, M.D.</div>
                    <div style="font-size:12px;">Lic. # 0115224 — Medical Officer III, PUP MSD</div>
                    <div style="font-size:11px;color:var(--text-gray);margin-top:6px;">Physician's Signature on printed form</div>
                </div>
                <div class="f-group"><label class="f-label">Notes</label><input class="f-control" type="text" id="el-notes"></div>
            </form>
        </div>
        <div class="modal-actions">
            <button class="btn-modal-cancel" onclick="closeEditModal('laboratory')"><i class="bi bi-x-circle"></i> Cancel</button>
            <button class="btn-modal-save" onclick="document.getElementById('edit-form-laboratory').requestSubmit()"><i class="bi bi-check-circle"></i> Save Changes</button>
        </div>
    </div>
</div>

<!-- ===================== MODAL: Health Exam ===================== -->
<div class="modal-overlay" id="modal-edit-healthexam">
    <div class="modal-box wide">
        <div class="modal-head">
            <div>
                <div class="modal-head-title"><i class="bi bi-clipboard2-pulse me-2"></i>Edit — Health Examination Record</div>
                <div class="modal-head-sub">PUP Medical Services Department — Faculty, Employee & Student</div>
            </div>
            <button class="modal-close-btn" onclick="closeEditModal('healthexam')">&times;</button>
        </div>
        <div class="modal-scroll">
            <form id="edit-form-healthexam" onsubmit="updateRecord(event,'healthexam')">
                <div class="pup-header">
                    <div class="pup-title">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES<br>HEALTH EXAMINATION RECORD</div>
                    <div class="pup-sub">Office of the VP for Administration — Medical Services Department</div>
                </div>
                <div class="f-section-title"><i class="bi bi-person-badge"></i> Patient Information</div>
                <div class="f-grid-2">
                    <div class="f-group"><label class="f-label">Name <span class="req">*</span></label><input class="f-control" type="text" id="ehe-name" required></div>
                    <div class="f-group"><label class="f-label">Date <span class="req">*</span></label><input class="f-control" type="date" id="ehe-date" required></div>
                </div>
                <div class="f-grid-2">
                    <div class="f-group"><label class="f-label">Address</label><input class="f-control" type="text" id="ehe-address"></div>
                    <div class="f-group"><label class="f-label">College / Department</label><input class="f-control" type="text" id="ehe-dept"></div>
                </div>
                <div class="f-grid-3">
                    <div class="f-group"><label class="f-label">Contact No.</label><input class="f-control" type="text" id="ehe-contact"></div>
                    <div class="f-group"><label class="f-label">Course / School Year</label><input class="f-control" type="text" id="ehe-course"></div>
                    <div class="f-group"><label class="f-label">Age</label><input class="f-control" type="number" id="ehe-age" min="1"></div>
                </div>
                <div class="f-grid-3">
                    <div class="f-group"><label class="f-label">Sex</label><select class="f-control" id="ehe-sex"><option value="">Select</option><option>Male</option><option>Female</option></select></div>
                    <div class="f-group"><label class="f-label">Civil Status</label><select class="f-control" id="ehe-civil"><option value="">Select</option><option>Single</option><option>Married</option><option>Widowed</option></select></div>
                    <div class="f-group"><label class="f-label">Emergency Contact No.</label><input class="f-control" type="text" id="ehe-emcon"></div>
                </div>
                <div class="f-group" style="margin-bottom:16px;"><label class="f-label">Contact Person In Case of Emergency</label><input class="f-control" type="text" id="ehe-cmperson"></div>

                <div class="f-section-title"><i class="bi bi-clock-history"></i> I. Past Medical History</div>
                <label class="f-label" style="margin-bottom:8px;">Childhood Illness</label>
                <div class="checkbox-grid cols-3" style="margin-bottom:14px;">
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Asthma</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Chicken Pox</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Heart Disease</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Measles</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Seizure Disorder</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Hyperventilation</label>
                </div>
                <div class="f-grid-3" style="margin-bottom:14px;">
                    <div class="f-group"><label class="f-label">Other Childhood Illness</label><input class="f-control" type="text" id="ehe-othchild"></div>
                    <div class="f-group"><label class="f-label">Previous Hospitalization</label><div class="radio-group" style="padding-top:10px;"><label class="radio-item"><input type="radio" name="ehe-hosp" value="No"> No</label><label class="radio-item"><input type="radio" name="ehe-hosp" value="Yes"> Yes</label></div></div>
                    <div class="f-group"><label class="f-label">Operation / Surgery</label><div class="radio-group" style="padding-top:10px;"><label class="radio-item"><input type="radio" name="ehe-surg" value="No"> No</label><label class="radio-item"><input type="radio" name="ehe-surg" value="Yes"> Yes</label></div></div>
                </div>
                <div class="f-grid-2">
                    <div class="f-group"><label class="f-label">Current Medications</label><input class="f-control" type="text" id="ehe-meds"></div>
                    <div class="f-group"><label class="f-label">Allergies</label><input class="f-control" type="text" id="ehe-allergy"></div>
                </div>

                <div class="f-section-title"><i class="bi bi-people"></i> II. Family History</div>
                <div class="checkbox-grid cols-3" style="margin-bottom:14px;">
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Diabetes</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> PTB</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Hypertension</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Cancer</label>
                </div>
                <div class="f-group" style="margin-bottom:16px;"><label class="f-label">Other Family History (MS/FS)</label><input class="f-control" type="text" id="ehe-famhist"></div>

                <div class="f-section-title"><i class="bi bi-person-check"></i> III. Personal History</div>
                <div class="f-grid-3">
                    <div class="f-group"><label class="f-label">Cigarette Smoking</label><div class="radio-group" style="padding-top:10px;"><label class="radio-item"><input type="radio" name="ehe-smoke" value="No"> No</label><label class="radio-item"><input type="radio" name="ehe-smoke" value="Yes"> Yes</label></div></div>
                    <div class="f-group"><label class="f-label">Alcohol Drinking</label><div class="radio-group" style="padding-top:10px;"><label class="radio-item"><input type="radio" name="ehe-alcohol" value="No"> No</label><label class="radio-item"><input type="radio" name="ehe-alcohol" value="Yes"> Yes</label></div></div>
                    <div class="f-group"><label class="f-label">Traveled Abroad</label><div class="radio-group" style="padding-top:10px;"><label class="radio-item"><input type="radio" name="ehe-travel" value="No"> No</label><label class="radio-item"><input type="radio" name="ehe-travel" value="Yes"> Yes</label></div></div>
                </div>

                <div class="f-section-title"><i class="bi bi-stethoscope"></i> IV. Physical Examination</div>
                <div class="f-grid-3" style="margin-bottom:14px;">
                    <div class="f-group"><label class="f-label">Height (cm)</label><input class="f-control" type="text" id="ehe-ht"></div>
                    <div class="f-group"><label class="f-label">Weight (kg)</label><input class="f-control" type="text" id="ehe-wt"></div>
                    <div class="f-group"><label class="f-label">BMI</label><input class="f-control" type="text" id="ehe-bmi"></div>
                    <div class="f-group"><label class="f-label">Blood Pressure</label><input class="f-control" type="text" id="ehe-bp"></div>
                    <div class="f-group"><label class="f-label">HR (/min)</label><input class="f-control" type="text" id="ehe-hr"></div>
                    <div class="f-group"><label class="f-label">RR (/min)</label><input class="f-control" type="text" id="ehe-rr"></div>
                    <div class="f-group"><label class="f-label">Temperature (°C)</label><input class="f-control" type="text" id="ehe-temp"></div>
                    <div class="f-group"><label class="f-label">1st Day of Last Menstruation</label><input class="f-control" type="date" id="ehe-lmp"></div>
                </div>

                <label class="f-label" style="margin-bottom:8px;">Chest / Lungs</label>
                <div class="checkbox-grid cols-3" style="margin-bottom:10px;">
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Normal</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Wheeze</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Rales</label>
                </div>
                <label class="f-label" style="margin-bottom:8px;">Chest X-Ray Result</label>
                <div class="radio-group" style="margin-bottom:14px;">
                    <label class="radio-item"><input type="radio" name="ehe-cxr" value="Normal"> Normal</label>
                    <label class="radio-item"><input type="radio" name="ehe-cxr" value="With findings"> With Findings</label>
                </div>
                <div class="f-grid-2" style="margin-bottom:14px;">
                    <div><label class="f-label">Heart Murmur</label><div class="radio-group" style="margin-top:8px;"><label class="radio-item"><input type="radio" name="ehe-murmur" value="Present"> Present</label><label class="radio-item"><input type="radio" name="ehe-murmur" value="Absent"> Absent</label></div></div>
                    <div><label class="f-label">Heart Rhythm</label><div class="radio-group" style="margin-top:8px;"><label class="radio-item"><input type="radio" name="ehe-rhythm" value="Regular"> Regular</label><label class="radio-item"><input type="radio" name="ehe-rhythm" value="Irregular"> Irregular</label></div></div>
                </div>
                <label class="f-label" style="margin-bottom:8px;">Skin</label>
                <div class="checkbox-grid cols-3" style="margin-bottom:14px;">
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Pallor</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Rashes</label>
                    <label class="check-item"><input type="checkbox" onchange="toggleCheck(this)"> Lesions</label>
                </div>

                <div class="f-section-title"><i class="bi bi-clipboard-check"></i> Working Impression</div>
                <div class="f-grid-2">
                    <div class="f-group"><label class="f-label">Fit <span class="req">*</span></label><input class="f-control" type="text" id="ehe-fit" required></div>
                    <div class="f-group"><label class="f-label">For Work-Up</label><input class="f-control" type="text" id="ehe-workup"></div>
                </div>
                <div class="f-grid-2">
                    <div class="f-group"><label class="f-label">Follow-up Date</label><input class="f-control" type="date" id="ehe-followup"></div>
                    <div class="f-group"><label class="f-label">Physician's Notes</label><textarea class="f-control" id="ehe-notes" style="min-height:70px;"></textarea></div>
                </div>
            </form>
        </div>
        <div class="modal-actions">
            <button class="btn-modal-cancel" onclick="closeEditModal('healthexam')"><i class="bi bi-x-circle"></i> Cancel</button>
            <button class="btn-modal-save" onclick="document.getElementById('edit-form-healthexam').requestSubmit()"><i class="bi bi-check-circle"></i> Save Changes</button>
        </div>
    </div>
</div>

<!-- ===================== MODAL: Medical Clearance ===================== -->
<div class="modal-overlay" id="modal-edit-clearance">
    <div class="modal-box">
        <div class="modal-head">
            <div>
                <div class="modal-head-title"><i class="bi bi-shield-check me-2"></i>Edit — Medical Clearance</div>
                <div class="modal-head-sub">PUP Medical Services Department · Sta. Mesa, Manila</div>
            </div>
            <button class="modal-close-btn" onclick="closeEditModal('clearance')">&times;</button>
        </div>
        <div class="modal-scroll">
            <form id="edit-form-clearance" onsubmit="updateRecord(event,'clearance')">
                <div class="pup-header">
                    <div class="pup-title">Republic of the Philippines<br>POLYTECHNIC UNIVERSITY OF THE PHILIPPINES · Manila</div>
                    <div class="pup-sub" style="font-weight:700;font-size:15px;margin-top:6px;color:var(--primary-color);">Medical CLEARANCE — PHYSICALLY FIT</div>
                </div>
                <div class="f-section-title"><i class="bi bi-person"></i> Patient Information</div>
                <div class="f-grid-3">
                    <div class="f-group" style="grid-column:span 2;"><label class="f-label">Patient's Full Name <span class="req">*</span></label><input class="f-control" type="text" id="ecl-name" required></div>
                    <div class="f-group"><label class="f-label">Date of Clearance <span class="req">*</span></label><input class="f-control" type="date" id="ecl-date" required></div>
                </div>
                <div class="f-grid-2">
                    <div class="f-group"><label class="f-label">ID / Student Number</label><input class="f-control" type="text" id="ecl-id"></div>
                    <div class="f-group"><label class="f-label">College / Department</label><input class="f-control" type="text" id="ecl-dept"></div>
                </div>
                <div class="f-section-title"><i class="bi bi-file-text"></i> Clearance Details</div>
                <div class="f-group" style="margin-bottom:16px;"><label class="f-label">Purpose of Clearance <span class="req">*</span></label><input class="f-control" type="text" id="ecl-purpose" required></div>
                <div class="f-group" style="margin-bottom:16px;"><label class="f-label">Additional Remarks / Conditions</label><textarea class="f-control" id="ecl-remarks" style="min-height:75px;"></textarea></div>
                <div class="f-section-title"><i class="bi bi-pen"></i> Issuing Physician</div>
                <div class="f-grid-2">
                    <div class="f-group"><label class="f-label">Physician's Name <span class="req">*</span></label><input class="f-control" type="text" id="ecl-physician" value="FELICITAS A. BERMUDEZ, M.D." required></div>
                    <div class="f-group"><label class="f-label">License Number</label><input class="f-control" type="text" id="ecl-license" value="0115224"></div>
                </div>
                <div class="sign-box"><i class="bi bi-pen" style="font-size:22px;margin-bottom:6px;display:block;"></i>Physician's signature will be affixed on the printed form</div>
            </form>
        </div>
        <div class="modal-actions">
            <button class="btn-modal-cancel" onclick="closeEditModal('clearance')"><i class="bi bi-x-circle"></i> Cancel</button>
            <button class="btn-modal-save" onclick="document.getElementById('edit-form-clearance').requestSubmit()"><i class="bi bi-check-circle"></i> Save Changes</button>
        </div>
    </div>
</div>

<!-- ===================== MODAL: Data Privacy Consent ===================== -->
<div class="modal-overlay" id="modal-edit-consent">
    <div class="modal-box">
        <div class="modal-head">
            <div>
                <div class="modal-head-title"><i class="bi bi-file-earmark-lock2 me-2"></i>Edit — Declaration & Data Subject Consent</div>
                <div class="modal-head-sub">Medical 40 · PUP Medical Services Department</div>
            </div>
            <button class="modal-close-btn" onclick="closeEditModal('consent')">&times;</button>
        </div>
        <div class="modal-scroll">
            <form id="edit-form-consent" onsubmit="updateRecord(event,'consent')">
                <div class="pup-header">
                    <div class="pup-title">Declaration of Medical Information and<br>Data Subject Consent Form</div>
                    <div class="pup-sub">Polytechnic University of the Philippines — Medical Services Department</div>
                </div>
                <div class="f-section-title"><i class="bi bi-person"></i> Patient Information</div>
                <div class="f-grid-3">
                    <div class="f-group" style="grid-column:span 2;"><label class="f-label">Full Name <span class="req">*</span></label><input class="f-control" type="text" id="eco-name" required></div>
                    <div class="f-group"><label class="f-label">Date <span class="req">*</span></label><input class="f-control" type="date" id="eco-date" required></div>
                </div>
                <div class="f-grid-2">
                    <div class="f-group"><label class="f-label">Student / Employee ID</label><input class="f-control" type="text" id="eco-id"></div>
                    <div class="f-group"><label class="f-label">College / Department</label><input class="f-control" type="text" id="eco-dept"></div>
                </div>
                <div class="f-section-title"><i class="bi bi-file-text"></i> Declaration</div>
                <div class="consent-text">I hereby certify that the medical health information given to the physician and nurses of Polytechnic University of the Philippines (PUP) during my on-site consultation for the issuance of medical clearance for off-campus activity/ies are true, correct and complete to the best of my knowledge. I have fully disclosed all the medical condition that may affect in the assessment to endorse my participation in the activity/ties as a student of PUP.<br><br>I also understand that the PUP Medical Services and University will not be liable for any untoward incident that may arise due to my failure to disclose accurate information or intentionally providing false and deceptive information.</div>
                <div class="f-group" style="margin-bottom:16px;"><label class="f-label">Activity / Purpose <span class="req">*</span></label><input class="f-control" type="text" id="eco-purpose" required></div>
                <div class="f-section-title"><i class="bi bi-shield-lock"></i> Data Privacy Consent</div>
                <div class="consent-text">In compliance with the <strong>Data Privacy Act of 2012</strong> and its Implementing Rules and Regulations, I voluntarily consent to the collection, processing and storage of my personal and health information for the purpose/s of health assessment, treatment, and/or research for the improvement of healthcare services.</div>
                <div class="f-section-title"><i class="bi bi-pen"></i> Signatures</div>
                <div class="f-grid-2">
                    <div class="f-group"><label class="f-label">Student's Printed Name <span class="req">*</span></label><input class="f-control" type="text" id="eco-printname" required></div>
                    <div class="f-group"><label class="f-label">Date Signed</label><input class="f-control" type="date" id="eco-signdate"></div>
                </div>
                <div class="sign-box"><i class="bi bi-pen" style="font-size:20px;margin-bottom:6px;display:block;"></i>Student's Signature Over Printed Name — to be signed on printed form</div>
                <div class="f-group"><label class="f-label">Remarks</label><textarea class="f-control" id="eco-remarks" style="min-height:65px;"></textarea></div>
                <div style="background:#fff7ed;border:1.5px solid #fed7aa;border-radius:10px;padding:12px 15px;font-size:12px;color:#92400e;margin-top:10px;">
                    <i class="bi bi-info-circle"></i> <strong>Note:</strong> Both student and guardian must affix their signature if the student is aged below 18 years old.
                </div>
            </form>
        </div>
        <div class="modal-actions">
            <button class="btn-modal-cancel" onclick="closeEditModal('consent')"><i class="bi bi-x-circle"></i> Cancel</button>
            <button class="btn-modal-save" onclick="document.getElementById('edit-form-consent').requestSubmit()"><i class="bi bi-check-circle"></i> Save Changes</button>
        </div>
    </div>
</div>

<!-- TOAST -->
<div id="toast" class="toast-alert">
    <i class="bi bi-check-circle-fill"></i>
    <div>
        <div class="toast-title" id="toast-title">Record Updated!</div>
        <div class="toast-msg" id="toast-msg">Changes have been saved successfully.</div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script>
// ===================== DATA =====================
let studentRecords = [
    { id: 1, date: '2025-05-20', name: 'Maria Santos', idNo: '2023-10201-MN-0', dept: 'CCS', formType: 'consultation', formLabel: 'General Consultation', diagPurpose: 'Common Cold', followup: '2025-05-24', data: { name:'Maria Santos', idNo:'2023-10201-MN-0', date:'2025-05-20', time:'09:30', type:'General Consultation', dept:'CCS', bp:'120/80', temp:'37.2', hr:'82', rr:'18', ht:'158', wt:'52', complaint:'Headache, runny nose, sore throat', findings:'Pharyngeal congestion, clear nasal discharge', diagnosis:'Common Cold', treatment:'Rest, increase fluid intake, Paracetamol 500mg TID PRN', meds:'Paracetamol 500mg', followup:'2025-05-24', notes:'Monitor temp, return if fever persists' } },
    { id: 2, date: '2025-04-02', name: 'Juan Dela Cruz', idNo: '2022-89012-MN-0', dept: 'CAFA', formType: 'clearance', formLabel: 'Medical Clearance', diagPurpose: 'Off-campus Immersion', followup: 'N/A', data: { name:'Juan Dela Cruz', date:'2025-04-02', idNo:'2022-89012-MN-0', dept:'CAFA', purpose:'Off-campus Immersion / OJT', remarks:'No contraindications noted', physician:'FELICITAS A. BERMUDEZ, M.D.', license:'0115224' } },
    { id: 3, date: '2025-03-15', name: 'Anna Reyes', idNo: '2024-33401-MN-0', dept: 'CON', formType: 'laboratory', formLabel: 'Lab Exam Request', diagPurpose: 'CBC, Urinalysis', followup: 'N/A', data: { name:'Anna Reyes', date:'2025-03-15', age:19, idNo:'2024-33401-MN-0', others:'' } },
    { id: 4, date: '2025-02-10', name: 'Carlos Ramos', idNo: '2023-55670-MN-0', dept: 'CE', formType: 'consent', formLabel: 'Consent Form', diagPurpose: 'Sports Event Participation', followup: 'N/A', data: { name:'Carlos Ramos', date:'2025-02-10', idNo:'2023-55670-MN-0', dept:'CE', purpose:'Sports Event Participation', printname:'Carlos Ramos', signdate:'2025-02-10', remarks:'' } },
    { id: 5, date: '2025-01-20', name: 'Liza Flores', idNo: '2021-77800-MN-0', dept: 'CBA', formType: 'healthexam', formLabel: 'Health Examination', diagPurpose: 'Fit for school activities', followup: '2025-07-20', data: { name:'Liza Flores', date:'2025-01-20', dept:'CBA', course:'BSBA / AY 2024-2025', age:21, sex:'Female', fit:'Fit for school activities', workup:'', notes:'Annual health exam completed' } }
];

let facultyRecords = [
    { id: 101, date: '2025-05-18', name: 'Prof. Roberto Cruz', idNo: 'EMP-00123', dept: 'CCS', formType: 'healthexam', formLabel: 'Health Examination', diagPurpose: 'Fit for work', followup: 'N/A', data: { name:'Prof. Roberto Cruz', date:'2025-05-18', dept:'CCS', age:45, sex:'Male', fit:'Fit for work', notes:'Annual PE completed, referred to cardio for monitoring' } },
    { id: 102, date: '2025-04-10', name: 'Ms. Grace Villanueva', idNo: 'EMP-00456', dept: 'HR Office', formType: 'consultation', formLabel: 'General Consultation', diagPurpose: 'Hypertension monitoring', followup: '2025-04-24', data: { name:'Ms. Grace Villanueva', idNo:'EMP-00456', date:'2025-04-10', time:'10:00', type:'Follow-up', dept:'HR Office', bp:'140/90', temp:'36.6', hr:'78', rr:'17', ht:'160', wt:'65', complaint:'Elevated BP, mild headache', findings:'BP elevated', diagnosis:'Hypertension — monitoring', treatment:'Continue antihypertensive, low-salt diet', meds:'Amlodipine 5mg OD', followup:'2025-04-24', notes:'Monitor BP daily' } },
    { id: 103, date: '2025-03-05', name: 'Mr. Edwin Santos', idNo: 'EMP-00789', dept: 'Maintenance', formType: 'clearance', formLabel: 'Medical Clearance', diagPurpose: 'Return to work clearance', followup: 'N/A', data: { name:'Mr. Edwin Santos', date:'2025-03-05', idNo:'EMP-00789', dept:'Maintenance', purpose:'Return to work after sick leave', remarks:'Fully recovered from URTI', physician:'FELICITAS A. BERMUDEZ, M.D.', license:'0115224' } }
];

let filteredStudents = [...studentRecords];
let filteredFaculty = [...facultyRecords];
let currentEditRecord = null;
let currentTab = 'students';

// ===================== NAVIGATION =====================
const pageFiles = { dashboard:'nurse_dashboard.php', roster:'nurse_roster.php', appointments:'appointments.php', records:'patient_records.php', consultations:'consultations.php', certificate:'medical_cert.php', profile:'profile.php' };
document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', function() {
        const f = pageFiles[this.getAttribute('data-page')];
        if (f) window.location.href = f;
    });
});

// ===================== TAB SWITCHER =====================
function switchTab(tab) {
    currentTab = tab;
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('panel-' + tab).classList.add('active');
    document.getElementById('tab-' + tab).classList.add('active');
}

// ===================== RENDER =====================
const formBadgeMap = {
    consultation: 'badge-consultation',
    laboratory:   'badge-laboratory',
    healthexam:   'badge-healthexam',
    clearance:    'badge-clearance',
    consent:      'badge-consent'
};

function renderStudents(data) {
    const tbody = document.getElementById('tbody-students');
    if (!data.length) { tbody.innerHTML = `<tr><td colspan="7" class="no-results"><i class="bi bi-folder-x"></i>No student records found.</td></tr>`; return; }
    tbody.innerHTML = data.map(r => `
        <tr>
            <td>${formatDate(r.date)}</td>
            <td><strong>${r.name}</strong></td>
            <td><span style="font-size:12px;color:var(--text-gray);">${r.idNo}</span></td>
            <td><span class="type-badge ${formBadgeMap[r.formType]}">${r.formLabel}</span></td>
            <td>${r.diagPurpose}</td>
            <td>${r.followup === 'N/A' ? '<span style="color:var(--text-gray);font-size:12px;">N/A</span>' : formatDate(r.followup)}</td>
            <td><button class="btn-edit-record" onclick="openEditModal(${r.id},'students')"><i class="bi bi-pencil-square"></i> Edit</button></td>
        </tr>
    `).join('');
}

function renderFaculty(data) {
    const tbody = document.getElementById('tbody-faculty');
    if (!data.length) { tbody.innerHTML = `<tr><td colspan="7" class="no-results"><i class="bi bi-folder-x"></i>No faculty/staff records found.</td></tr>`; return; }
    tbody.innerHTML = data.map(r => `
        <tr>
            <td>${formatDate(r.date)}</td>
            <td><strong>${r.name}</strong></td>
            <td><span style="font-size:12px;color:var(--text-gray);">${r.dept}</span></td>
            <td><span class="type-badge ${formBadgeMap[r.formType]}">${r.formLabel}</span></td>
            <td>${r.diagPurpose}</td>
            <td>${r.followup === 'N/A' ? '<span style="color:var(--text-gray);font-size:12px;">N/A</span>' : formatDate(r.followup)}</td>
            <td><button class="btn-edit-record" onclick="openEditModal(${r.id},'faculty')"><i class="bi bi-pencil-square"></i> Edit</button></td>
        </tr>
    `).join('');
}

function formatDate(d) {
    if (!d || d === 'N/A') return d;
    const months = ['Jan.','Feb.','Mar.','Apr.','May','Jun.','Jul.','Aug.','Sep.','Oct.','Nov.','Dec.'];
    const dt = new Date(d + 'T00:00:00');
    return `${months[dt.getMonth()]} ${dt.getDate()}, ${dt.getFullYear()}`;
}

// ===================== SEARCH =====================
function filterRecords(tab) {
    const val = document.getElementById('search' + (tab === 'students' ? 'Students' : 'Faculty')).value.toLowerCase();
    if (tab === 'students') {
        filteredStudents = val ? studentRecords.filter(r => r.name.toLowerCase().includes(val) || r.diagPurpose.toLowerCase().includes(val) || r.formLabel.toLowerCase().includes(val) || r.dept.toLowerCase().includes(val)) : [...studentRecords];
        renderStudents(filteredStudents);
    } else {
        filteredFaculty = val ? facultyRecords.filter(r => r.name.toLowerCase().includes(val) || r.diagPurpose.toLowerCase().includes(val) || r.formLabel.toLowerCase().includes(val) || r.dept.toLowerCase().includes(val)) : [...facultyRecords];
        renderFaculty(filteredFaculty);
    }
}
document.getElementById('searchStudents').addEventListener('keyup', e => { if(e.key==='Enter') filterRecords('students'); });
document.getElementById('searchFaculty').addEventListener('keyup', e => { if(e.key==='Enter') filterRecords('faculty'); });

// ===================== EDIT MODAL =====================
function openEditModal(id, tab) {
    const pool = tab === 'students' ? studentRecords : facultyRecords;
    const record = pool.find(r => r.id === id);
    if (!record) return;
    currentEditRecord = { ...record, _tab: tab };
    populateForm(record.formType, record.data);
    document.getElementById('modal-edit-' + record.formType).classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeEditModal(type) {
    document.getElementById('modal-edit-' + type).classList.remove('show');
    document.body.style.overflow = 'auto';
    currentEditRecord = null;
}

function setVal(id, val) { const el = document.getElementById(id); if(el) el.value = val || ''; }

function populateForm(type, d) {
    if (type === 'consultation') {
        setVal('ec-name', d.name); setVal('ec-id', d.idNo); setVal('ec-date', d.date);
        setVal('ec-time', d.time); setVal('ec-dept', d.dept);
        const sel = document.getElementById('ec-type'); if(sel) sel.value = d.type || '';
        setVal('ec-bp', d.bp); setVal('ec-temp', d.temp); setVal('ec-hr', d.hr);
        setVal('ec-rr', d.rr); setVal('ec-ht', d.ht); setVal('ec-wt', d.wt);
        setVal('ec-complaint', d.complaint); setVal('ec-findings', d.findings);
        setVal('ec-diagnosis', d.diagnosis); setVal('ec-treatment', d.treatment);
        setVal('ec-meds', d.meds); setVal('ec-followup', d.followup); setVal('ec-notes', d.notes);
    } else if (type === 'laboratory') {
        setVal('el-name', d.name); setVal('el-date', d.date);
        setVal('el-age', d.age); setVal('el-id', d.idNo); setVal('el-others', d.others); setVal('el-notes', d.notes);
    } else if (type === 'healthexam') {
        setVal('ehe-name', d.name); setVal('ehe-date', d.date); setVal('ehe-dept', d.dept);
        setVal('ehe-address', d.address); setVal('ehe-contact', d.contact); setVal('ehe-course', d.course);
        setVal('ehe-age', d.age); setVal('ehe-emcon', d.emcon); setVal('ehe-cmperson', d.cmperson);
        if(d.sex) { const s = document.getElementById('ehe-sex'); if(s) s.value = d.sex; }
        if(d.civil) { const c = document.getElementById('ehe-civil'); if(c) c.value = d.civil; }
        setVal('ehe-meds', d.meds); setVal('ehe-allergy', d.allergy); setVal('ehe-famhist', d.famhist);
        setVal('ehe-ht', d.ht); setVal('ehe-wt', d.wt); setVal('ehe-bmi', d.bmi);
        setVal('ehe-bp', d.bp); setVal('ehe-hr', d.hr); setVal('ehe-rr', d.rr); setVal('ehe-temp', d.temp);
        setVal('ehe-fit', d.fit); setVal('ehe-workup', d.workup); setVal('ehe-followup', d.followup); setVal('ehe-notes', d.notes);
    } else if (type === 'clearance') {
        setVal('ecl-name', d.name); setVal('ecl-date', d.date); setVal('ecl-id', d.idNo);
        setVal('ecl-dept', d.dept); setVal('ecl-purpose', d.purpose); setVal('ecl-remarks', d.remarks);
        setVal('ecl-physician', d.physician || 'FELICITAS A. BERMUDEZ, M.D.'); setVal('ecl-license', d.license || '0115224');
    } else if (type === 'consent') {
        setVal('eco-name', d.name); setVal('eco-date', d.date); setVal('eco-id', d.idNo);
        setVal('eco-dept', d.dept); setVal('eco-purpose', d.purpose); setVal('eco-printname', d.printname);
        setVal('eco-signdate', d.signdate); setVal('eco-remarks', d.remarks);
    }
}

function collectForm(type) {
    const g = id => { const el = document.getElementById(id); return el ? el.value : ''; };
    if (type === 'consultation') return { name:g('ec-name'), idNo:g('ec-id'), date:g('ec-date'), time:g('ec-time'), dept:g('ec-dept'), type:g('ec-type'), bp:g('ec-bp'), temp:g('ec-temp'), hr:g('ec-hr'), rr:g('ec-rr'), ht:g('ec-ht'), wt:g('ec-wt'), complaint:g('ec-complaint'), findings:g('ec-findings'), diagnosis:g('ec-diagnosis'), treatment:g('ec-treatment'), meds:g('ec-meds'), followup:g('ec-followup'), notes:g('ec-notes') };
    if (type === 'laboratory') return { name:g('el-name'), date:g('el-date'), age:g('el-age'), idNo:g('el-id'), others:g('el-others'), notes:g('el-notes') };
    if (type === 'healthexam') return { name:g('ehe-name'), date:g('ehe-date'), dept:g('ehe-dept'), address:g('ehe-address'), contact:g('ehe-contact'), course:g('ehe-course'), age:g('ehe-age'), sex:document.getElementById('ehe-sex')?.value, civil:document.getElementById('ehe-civil')?.value, emcon:g('ehe-emcon'), cmperson:g('ehe-cmperson'), meds:g('ehe-meds'), allergy:g('ehe-allergy'), famhist:g('ehe-famhist'), ht:g('ehe-ht'), wt:g('ehe-wt'), bmi:g('ehe-bmi'), bp:g('ehe-bp'), hr:g('ehe-hr'), rr:g('ehe-rr'), temp:g('ehe-temp'), fit:g('ehe-fit'), workup:g('ehe-workup'), followup:g('ehe-followup'), notes:g('ehe-notes') };
    if (type === 'clearance') return { name:g('ecl-name'), date:g('ecl-date'), idNo:g('ecl-id'), dept:g('ecl-dept'), purpose:g('ecl-purpose'), remarks:g('ecl-remarks'), physician:g('ecl-physician'), license:g('ecl-license') };
    if (type === 'consent') return { name:g('eco-name'), date:g('eco-date'), idNo:g('eco-id'), dept:g('eco-dept'), purpose:g('eco-purpose'), printname:g('eco-printname'), signdate:g('eco-signdate'), remarks:g('eco-remarks') };
}

function updateRecord(e, type) {
    e.preventDefault();
    if (!currentEditRecord) return;
    const newData = collectForm(type);
    const pool = currentEditRecord._tab === 'students' ? studentRecords : facultyRecords;
    const rec = pool.find(r => r.id === currentEditRecord.id);
    if (rec) {
        rec.data = { ...rec.data, ...newData };
        rec.date = newData.date || rec.date;
        rec.name = newData.name || rec.name;
        rec.dept = newData.dept || rec.dept;
        // Update diagPurpose
        if (type === 'consultation') rec.diagPurpose = newData.diagnosis;
        if (type === 'clearance') rec.diagPurpose = newData.purpose;
        if (type === 'consent') rec.diagPurpose = newData.purpose;
        if (type === 'healthexam') rec.diagPurpose = newData.fit;
        if (type === 'laboratory') rec.diagPurpose = newData.others || 'Lab Tests';
        if (newData.followup && newData.followup !== '') rec.followup = newData.followup; else rec.followup = 'N/A';
    }
    closeEditModal(type);
    if (currentEditRecord._tab === 'students') { filteredStudents = [...studentRecords]; renderStudents(filteredStudents); }
    else { filteredFaculty = [...facultyRecords]; renderFaculty(filteredFaculty); }
    showToast('Record Updated!', 'Changes have been saved successfully.');
}

// ===================== CHECKBOX STYLE =====================
function toggleCheck(el) { el.closest('.check-item').classList.toggle('checked', el.checked); }

// ===================== CLOSE ON BACKDROP =====================
document.querySelectorAll('.modal-overlay').forEach(m => {
    m.addEventListener('click', function(e) {
        if (e.target === this) {
            const type = this.id.replace('modal-edit-','');
            closeEditModal(type);
        }
    });
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.show').forEach(m => {
            const type = m.id.replace('modal-edit-','');
            closeEditModal(type);
        });
    }
});

// ===================== TOAST =====================
function showToast(title, msg) {
    document.getElementById('toast-title').textContent = title;
    document.getElementById('toast-msg').textContent = msg;
    const t = document.getElementById('toast');
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3500);
}

// ===================== INIT =====================
renderStudents(filteredStudents);
renderFaculty(filteredFaculty);

// Expose function so consultations.php can push new records
window.addPatientRecord = function(tab, record) {
    const pool = tab === 'students' ? studentRecords : facultyRecords;
    record.id = Date.now();
    pool.unshift(record);
    if (tab === 'students') { filteredStudents = [...studentRecords]; renderStudents(filteredStudents); }
    else { filteredFaculty = [...facultyRecords]; renderFaculty(filteredFaculty); }
};
</script>
</body>
</html>