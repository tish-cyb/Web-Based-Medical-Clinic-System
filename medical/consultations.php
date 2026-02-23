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
        /* Non-clickable card variant for clearance */
        .form-card.no-click { cursor: default; }
        .form-card.no-click:hover { transform: none; box-shadow: 0 2px 16px rgba(0,0,0,0.07); border-color: transparent; }

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
        .icon-certificate { background: #f0f9ff; color: #0369a1; }

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

        /* CLEARANCE ROLE BUTTONS */
        .clearance-btn-row {
            padding: 14px 30px;
            background: #f9fafb;
            border-top: 1px solid #f0f0f0;
            display: flex;
            gap: 12px;
        }
        .clearance-role-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 16px;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: 2px solid;
            transition: all 0.25s ease;
            text-decoration: none;
        }
        .clearance-role-btn.student {
            background: #fef2f2;
            border-color: #ef4444;
            color: #b91c1c;
        }
        .clearance-role-btn.student:hover {
            background: #b91c1c;
            color: white;
            box-shadow: 0 4px 14px rgba(185,28,28,0.3);
            transform: translateY(-1px);
        }
        .clearance-role-btn.faculty {
            background: #fef2f2;
            border-color: #ef4444;
            color: #b91c1c;
        }
        .clearance-role-btn.faculty:hover {
            background: #b91c1c;
            color: white;
            box-shadow: 0 4px 14px rgba(185,28,28,0.3);
            transform: translateY(-1px);
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
        /* Student modal head — purple gradient */
        .modal-head.student-head {
            background: linear-gradient(90deg, var(--primary-gradient-start), var(--primary-gradient-end));
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
        .f-section-title.purple-title { color: var(--primary-color); border-bottom-color: var(--primary-soft); }
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
        .f-control.purple-focus:focus { border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(127,29,29,0.09); }
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
        .btn-modal-save.purple-btn { background: var(--primary-color); }
        .btn-modal-save.purple-btn:hover { background: var(--primary-light); box-shadow: 0 4px 12px rgba(127,29,29,0.3); }
        .btn-modal-secondary {
            padding: 11px 26px; border-radius: 8px; border: none;
            background: #e5e7eb; color: var(--text-dark);
            font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 600;
            cursor: pointer; transition: all 0.2s ease;
            display: flex; align-items: center; gap: 8px;
        }
        .btn-modal-secondary:hover { background: #d1d5db; }

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

        /* OFFICIAL CERTIFICATE PREVIEW - mimics the actual PUP form */
        .cert-document {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 30px 36px;
            background: white;
            font-family: 'Times New Roman', serif;
            margin-bottom: 18px;
        }
        .cert-document .cert-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 10px;
            padding-bottom: 12px;
            border-bottom: 2px solid #7f1d1d;
        }
        .cert-document .cert-logo {
            width: 60px; height: 60px;
            background: #7f1d1d;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 22px; font-weight: 700; flex-shrink: 0;
        }
        .cert-document .cert-header-text { flex: 1; text-align: center; }
        .cert-document .cert-header-text .rep { font-size: 11px; color: #555; }
        .cert-document .cert-header-text .univ { font-size: 14px; font-weight: bold; color: #1f2937; }
        .cert-document .cert-header-text .loc { font-size: 11px; color: #555; }
        .cert-document .cert-title-block { text-align: center; margin: 18px 0 6px; }
        .cert-document .cert-title-block .main-title { font-size: 18px; font-weight: bold; color: #7f1d1d; letter-spacing: 1px; }
        .cert-document .cert-title-block .sub-title { font-size: 13px; font-weight: bold; color: #7f1d1d; letter-spacing: 2px; margin-top: 2px; }
        .cert-document .cert-body-text { font-size: 13px; line-height: 2.2; color: #1f2937; margin-top: 16px; }
        .cert-document .cert-field-line {
            display: inline-block;
            border-bottom: 1.5px solid #1f2937;
            min-width: 220px;
            text-align: center;
            font-weight: bold;
            color: #7f1d1d;
            padding: 0 4px;
        }
        .cert-document .cert-field-line.wide { min-width: 320px; }
        .cert-document .cert-field-line.full { display: block; width: 100%; margin: 4px 0; }
        .cert-document .cert-sig-block { margin-top: 40px; text-align: center; }
        .cert-document .cert-sig-line {
            border-top: 2px solid #1f2937;
            width: 260px;
            margin: 0 auto;
            padding-top: 6px;
            font-weight: bold;
            font-size: 14px;
            color: #1f2937;
        }
        .cert-document .cert-sig-detail { font-size: 12px; color: #555; margin-top: 3px; }
        .cert-document .cert-form-ref {
            font-size: 10px; color: #999;
            margin-top: 20px; text-align: right;
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
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

        /* ROLE BADGE */
        .role-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 4px 14px; border-radius: 999px;
            font-size: 12px; font-weight: 700; letter-spacing: 0.5px;
            margin-bottom: 20px;
        }
        .role-badge.student { background: #fef2f2; color: #b91c1c; border: 1.5px solid #ef4444; }
        .role-badge.faculty { background: #fef2f2; color: #b91c1c; border: 1.5px solid #ef4444; }

        /* FACULTY SPECIFIC */
        .faculty-fields { background: #fff7ed; border: 1.5px solid #fed7aa; border-radius: 10px; padding: 16px 18px; margin-bottom: 18px; }
        .faculty-fields .f-label { color: #92400e; }

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

        /* CERTIFICATE PREVIEW */
        .cert-preview-box {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 36px;
            background: white;
            font-family: 'Times New Roman', serif;
            margin-bottom: 18px;
        }
        .cert-preview-box .cert-title {
            font-size: 22px; font-weight: bold; color: var(--primary-color);
            text-align: center; margin-bottom: 6px;
        }
        .cert-preview-box .cert-clinic-name {
            font-size: 15px; font-weight: 600; text-align: center;
            margin-bottom: 16px; padding-bottom: 16px;
            border-bottom: 2px solid var(--primary-color);
        }
        .cert-preview-box .cert-body { line-height: 2; font-size: 14px; color: var(--text-dark); }
        .cert-preview-box .cert-field { font-weight: bold; color: var(--primary-color); }
        .cert-preview-box .cert-sig { margin-top: 50px; text-align: right; }
        .cert-preview-box .cert-sig-line {
            border-top: 2px solid #1f2937; width: 240px; margin-left: auto;
            padding-top: 6px; font-weight: 600; font-size: 14px;
        }

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
        <h1>Medical Portal</h1>
        <p>Clinical Management</p>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-item" data-page="dashboard"><i class="bi bi-speedometer2"></i><span>Dashboard</span></div>
        <div class="nav-item" data-page="roster"><i class="bi bi-people"></i><span>Roster</span></div>
        <div class="nav-item" data-page="appointments"><i class="bi bi-calendar-check"></i><span>Appointments</span></div>
        <div class="nav-item" data-page="records"><i class="bi bi-folder2-open"></i><span>Patient Records</span></div>
        <div class="nav-item active" data-page="consultations"><i class="bi bi-chat-dots"></i><span>Consultations</span></div>
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

        <!-- 4. Medical Clearance — SPLIT BUTTONS -->
        <div class="form-card no-click">
            <div class="form-card-header">
                <div class="form-card-icon icon-clearance"><i class="bi bi-shield-check"></i></div>
                <div class="form-card-info">
                    <div class="form-card-title">Medical Clearance — Physically Fit</div>
                    <div class="form-card-desc">Issue PUP Medical Services clearance certifying physical fitness. Select the applicable role to open the correct form.</div>
                </div>
            </div>
            <div class="clearance-btn-row">
                <button class="clearance-role-btn student" onclick="openModal('clearance-student')">
                    <i class="bi bi-mortarboard-fill"></i> Student
                </button>
                <button class="clearance-role-btn faculty" onclick="openModal('clearance-faculty')">
                    <i class="bi bi-person-badge-fill"></i> Employee
                </button>
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

        <!-- 6. Medical Certificate -->
        <div class="form-card" onclick="openModal('certificate')">
            <div class="form-card-header">
                <div class="form-card-icon icon-certificate"><i class="bi bi-file-earmark-medical"></i></div>
                <div class="form-card-info">
                    <div class="form-card-title">Medical Certificate</div>
                    <div class="form-card-desc">Generate and print official medical certificates for sick leave, clearance, fitness to work/study, or other purposes.</div>
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
                <div class="f-section-title"><i class="bi bi-clock-history"></i> I. Past Medical History</div>
                <p style="font-size:12px;color:var(--text-gray);margin-bottom:10px;">Leave blank for No, check for Yes.</p>
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
                <div class="f-section-title"><i class="bi bi-people"></i> II. Family History</div>
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
                <div class="f-section-title"><i class="bi bi-stethoscope"></i> IV. Physical Examination</div>
                <div class="f-grid-3" style="margin-bottom:16px;">
                    <div class="f-group">
                        <label class="f-label">General Condition</label>
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
            </form>
        </div>
        <div class="modal-actions">
            <button class="btn-modal-cancel" onclick="closeModal('healthexam')"><i class="bi bi-x-circle"></i> Cancel</button>
            <button class="btn-modal-save" onclick="document.getElementById('form-healthexam').requestSubmit()"><i class="bi bi-check-circle"></i> Save Record</button>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL: Medical Clearance — STUDENT                           -->
<!-- ============================================================ -->
<div class="modal-overlay" id="modal-clearance-student">
    <div class="modal-box">
        <div class="modal-head student-head">
            <div>
                <div class="modal-head-title"><i class="bi bi-mortarboard-fill me-2"></i>Medical Clearance — Student</div>
                <div class="modal-head-sub">PUP Medical Services Department · Sta. Mesa, Manila</div>
            </div>
            <button class="modal-close-btn" onclick="closeModal('clearance-student')">&times;</button>
        </div>
        <div class="modal-scroll">
            <form id="form-clearance-student" onsubmit="saveForm(event,'clearance-student')">

                <!-- Official Certificate Preview layout -->
                <div class="cert-document">
                    <div class="cert-header">
                        <div class="cert-logo">PUP</div>
                        <div class="cert-header-text">
                            <div class="rep">Republic of the Philippines</div>
                            <div class="univ">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</div>
                            <div class="loc">Manila</div>
                        </div>
                    </div>
                    <div class="cert-title-block">
                        <div class="main-title">Medical CLEARANCE</div>
                        <div class="sub-title">PHYSICALLY FIT</div>
                    </div>
                    <div class="cert-body-text">
                        <p>Date &nbsp;<span class="cert-field-line" id="s-preview-date" style="min-width:180px;">_______________</span></p>
                        <br>
                        <p>To Whom It May Concern:</p>
                        <br>
                        <p>This is to certify that</p>
                        <span class="cert-field-line full" id="s-preview-name" style="display:block; min-width:100%; margin:6px 0;">&nbsp;</span>
                        <p>has been examined by the undersigned and found to be <strong>physically fit</strong> at the time of examination.</p>
                        <br>
                        <p>This certification is issued upon his/her request for</p>
                        <span class="cert-field-line full" id="s-preview-purpose" style="display:block; min-width:100%; margin:6px 0;">&nbsp;</span>
                        <span class="cert-field-line full" id="s-preview-purpose2" style="display:block; min-width:100%; margin:0 0 4px;">&nbsp;</span>
                        <p>purpose but not for medico-legal reason.</p>
                    </div>
                    <div class="cert-sig-block">
                        <div class="cert-sig-line" id="s-preview-physician">_________________________ M.D.</div>
                        <div class="cert-sig-detail">Lic No. <span id="s-preview-license">_______________</span></div>
                    </div>
                    <div class="cert-form-ref">Medical 03 · Rev 1 · PUP-LAFO-6-MEDS · Medical Services Department</div>
                </div>

                <!-- Form fields -->
                <div class="role-badge student"><i class="bi bi-mortarboard-fill"></i> Student Clearance</div>

                <div class="f-section-title purple-title"><i class="bi bi-person"></i> Student Information</div>
                <div class="f-grid-3">
                    <div class="f-group" style="grid-column: span 2;">
                        <label class="f-label">Student's Full Name <span class="req">*</span></label>
                        <input class="f-control purple-focus" type="text" id="s-name" placeholder="Last, First Middle Name" required
                            oninput="liveUpdate('s-preview-name', this.value)">
                    </div>
                    <div class="f-group">
                        <label class="f-label">Date <span class="req">*</span></label>
                        <input class="f-control purple-focus" type="date" id="s-date" required
                            oninput="liveUpdateDate('s-preview-date', this.value)">
                    </div>
                </div>
                <div class="f-grid-3">
                    <div class="f-group">
                        <label class="f-label">Student Number <span class="req">*</span></label>
                        <input class="f-control purple-focus" type="text" placeholder="e.g., 2023-12345-MN-0" required>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Course</label>
                        <input class="f-control purple-focus" type="text" placeholder="e.g., BSIT">
                    </div>
                    <div class="f-group">
                        <label class="f-label">College</label>
                        <input class="f-control purple-focus" type="text" placeholder="e.g., CCS">
                    </div>
                </div>
                <div class="f-grid-2">
                    <div class="f-group">
                        <label class="f-label">Year Level</label>
                        <select class="f-control purple-focus">
                            <option value="">Select Year</option>
                            <option>1st Year</option>
                            <option>2nd Year</option>
                            <option>3rd Year</option>
                            <option>4th Year</option>
                            <option>5th Year</option>
                            <option>Graduate</option>
                        </select>
                    </div>
                    <div class="f-group">
                        <label class="f-label">School Year / Semester</label>
                        <input class="f-control purple-focus" type="text" placeholder="e.g., AY 2025-2026, 2nd Sem">
                    </div>
                </div>

                <div class="f-section-title purple-title"><i class="bi bi-file-text"></i> Clearance Details</div>
                <div class="f-group" style="margin-bottom:18px;">
                    <label class="f-label">Purpose of Clearance <span class="req">*</span></label>
                    <input class="f-control purple-focus" type="text" id="s-purpose"
                        placeholder="e.g., Off-campus activity, Immersion, OJT, Practicum, Sports event, Enrollment"
                        required oninput="liveUpdate('s-preview-purpose', this.value)">
                </div>
                <div class="f-group" style="margin-bottom:18px;">
                    <label class="f-label">Additional Remarks / Conditions</label>
                    <textarea class="f-control purple-focus" placeholder="e.g., With noted conditions but cleared for the stated purpose..." style="min-height:75px;"></textarea>
                </div>

                <div class="f-section-title purple-title"><i class="bi bi-pen"></i> Issuing Physician</div>
                <div class="f-grid-2">
                    <div class="f-group">
                        <label class="f-label">Physician's Name <span class="req">*</span></label>
                        <input class="f-control purple-focus" type="text" id="s-physician"
                            value="FELICITAS A. BERMUDEZ, M.D." required
                            oninput="liveUpdate('s-preview-physician', this.value + ' M.D.')">
                    </div>
                    <div class="f-group">
                        <label class="f-label">License Number</label>
                        <input class="f-control purple-focus" type="text" id="s-license"
                            value="0115224" oninput="liveUpdate('s-preview-license', this.value)">
                    </div>
                </div>
                <div class="sign-box">
                    <i class="bi bi-pen-fill" style="font-size:20px; margin-bottom:6px; display:block; color:#b91c1c;"></i>
                    Physician's signature will be affixed on the printed form
                </div>
            </form>
        </div>
        <div class="modal-actions">
            <button class="btn-modal-cancel" onclick="closeModal('clearance-student')"><i class="bi bi-x-circle"></i> Cancel</button>
            <button class="btn-modal-save purple-btn" onclick="document.getElementById('form-clearance-student').requestSubmit()">
                <i class="bi bi-shield-check"></i> Issue Student Clearance
            </button>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL: Medical Clearance — FACULTY / EMPLOYEE               -->
<!-- ============================================================ -->
<div class="modal-overlay" id="modal-clearance-faculty">
    <div class="modal-box">
        <div class="modal-head">
            <div>
                <div class="modal-head-title"><i class="bi bi-person-badge-fill me-2"></i>Medical Clearance — Employee</div>
                <div class="modal-head-sub">PUP Medical Services Department · Sta. Mesa, Manila</div>
            </div>
            <button class="modal-close-btn" onclick="closeModal('clearance-faculty')">&times;</button>
        </div>
        <div class="modal-scroll">
            <form id="form-clearance-faculty" onsubmit="saveForm(event,'clearance-faculty')">

                <!-- Official Certificate Preview layout -->
                <div class="cert-document">
                    <div class="cert-header">
                        <div class="cert-logo">PUP</div>
                        <div class="cert-header-text">
                            <div class="rep">Republic of the Philippines</div>
                            <div class="univ">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</div>
                            <div class="loc">Manila</div>
                        </div>
                    </div>
                    <div class="cert-title-block">
                        <div class="main-title">Medical CLEARANCE</div>
                        <div class="sub-title">PHYSICALLY FIT</div>
                    </div>
                    <div class="cert-body-text">
                        <p>Date &nbsp;<span class="cert-field-line" id="f-preview-date" style="min-width:180px;">_______________</span></p>
                        <br>
                        <p>To Whom It May Concern:</p>
                        <br>
                        <p>This is to certify that</p>
                        <span class="cert-field-line full" id="f-preview-name" style="display:block; min-width:100%; margin:6px 0;">&nbsp;</span>
                        <p>has been examined by the undersigned and found to be <strong>physically fit</strong> at the time of examination.</p>
                        <br>
                        <p>This certification is issued upon his/her request for</p>
                        <span class="cert-field-line full" id="f-preview-purpose" style="display:block; min-width:100%; margin:6px 0;">&nbsp;</span>
                        <span class="cert-field-line full" id="f-preview-purpose2" style="display:block; min-width:100%; margin:0 0 4px;">&nbsp;</span>
                        <p>purpose but not for medico-legal reason.</p>
                    </div>
                    <div class="cert-sig-block">
                        <div class="cert-sig-line" id="f-preview-physician">_________________________ M.D.</div>
                        <div class="cert-sig-detail">Lic No. <span id="f-preview-license">_______________</span></div>
                    </div>
                    <div class="cert-form-ref">Medical 03 · Rev 1 · PUP-LAFO-6-MEDS · Medical Services Department</div>
                </div>

                <!-- Form fields -->
                <div class="role-badge faculty"><i class="bi bi-person-badge-fill"></i> Employee Clearance</div>

                <div class="f-section-title"><i class="bi bi-person"></i> Employee Information</div>
                <div class="f-grid-3">
                    <div class="f-group" style="grid-column: span 2;">
                        <label class="f-label">Employee's Full Name <span class="req">*</span></label>
                        <input class="f-control" type="text" id="f-name" placeholder="Last, First Middle Name" required
                            oninput="liveUpdate('f-preview-name', this.value)">
                    </div>
                    <div class="f-group">
                        <label class="f-label">Date <span class="req">*</span></label>
                        <input class="f-control" type="date" id="f-date" required
                            oninput="liveUpdateDate('f-preview-date', this.value)">
                    </div>
                </div>

                <div class="faculty-fields">
                    <div style="font-size:12px; font-weight:700; color:#92400e; margin-bottom:12px; text-transform:uppercase; letter-spacing:0.5px;">
                        <i class="bi bi-briefcase-fill me-1"></i> Employment Details
                    </div>
                    <div class="f-grid-3">
                        <div class="f-group">
                            <label class="f-label">Employee / Faculty ID</label>
                            <input class="f-control" type="text" placeholder="Employee ID number">
                        </div>
                        <div class="f-group">
                            <label class="f-label">Position / Designation</label>
                            <input class="f-control" type="text" placeholder="e.g., Associate Professor I">
                        </div>
                        <div class="f-group">
                            <label class="f-label">Employment Type</label>
                            <select class="f-control">
                                <option value="">Select</option>
                                <option>Permanent</option>
                                <option>Temporary</option>
                                <option>Part-time / Contractual</option>
                                <option>Job Order</option>
                            </select>
                        </div>
                    </div>
                    <div class="f-grid-2">
                        <div class="f-group">
                            <label class="f-label">Department / College</label>
                            <input class="f-control" type="text" placeholder="e.g., Department of CS, CCS">
                        </div>
                        <div class="f-group">
                            <label class="f-label">Campus</label>
                            <select class="f-control">
                                <option value="">Select Campus</option>
                                <option>PUP Manila (Sta. Mesa)</option>
                                <option>PUP Bataan</option>
                                <option>PUP Calauan</option>
                                <option>PUP Lopez</option>
                                <option>PUP Maragondon</option>
                                <option>PUP Mulanay</option>
                                <option>PUP Paranaque</option>
                                <option>PUP Quezon City</option>
                                <option>PUP San Juan</option>
                                <option>PUP Santa Rosa</option>
                                <option>PUP Sto. Tomas</option>
                                <option>PUP Taguig</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="f-section-title"><i class="bi bi-file-text"></i> Clearance Details</div>
                <div class="f-group" style="margin-bottom:18px;">
                    <label class="f-label">Purpose of Clearance <span class="req">*</span></label>
                    <input class="f-control" type="text" id="f-purpose"
                        placeholder="e.g., Return to work, Pre-employment requirement, Official travel, Annual check-up"
                        required oninput="liveUpdate('f-preview-purpose', this.value)">
                </div>
                <div class="f-group" style="margin-bottom:18px;">
                    <label class="f-label">Additional Remarks / Conditions</label>
                    <textarea class="f-control" placeholder="e.g., With noted conditions but cleared for the stated purpose..." style="min-height:75px;"></textarea>
                </div>

                <div class="f-section-title"><i class="bi bi-pen"></i> Issuing Physician</div>
                <div class="f-grid-2">
                    <div class="f-group">
                        <label class="f-label">Physician's Name <span class="req">*</span></label>
                        <input class="f-control" type="text" id="f-physician"
                            value="FELICITAS A. BERMUDEZ, M.D." required
                            oninput="liveUpdate('f-preview-physician', this.value + ' M.D.')">
                    </div>
                    <div class="f-group">
                        <label class="f-label">License Number</label>
                        <input class="f-control" type="text" id="f-license"
                            value="0115224" oninput="liveUpdate('f-preview-license', this.value)">
                    </div>
                </div>
                <div class="sign-box">
                    <i class="bi bi-pen-fill" style="font-size:20px; margin-bottom:6px; display:block; color:#b91c1c;"></i>
                    Physician's signature will be affixed on the printed form
                </div>
            </form>
        </div>
        <div class="modal-actions">
            <button class="btn-modal-cancel" onclick="closeModal('clearance-faculty')"><i class="bi bi-x-circle"></i> Cancel</button>
            <button class="btn-modal-save" onclick="document.getElementById('form-clearance-faculty').requestSubmit()">
                <i class="bi bi-shield-check"></i> Issue Employee Clearance
            </button>
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
                <div style="background:#fff7ed; border:1.5px solid #fed7aa; border-radius:10px; padding:14px 16px; font-size:12px; color:#92400e; margin-top:10px;">
                    <i class="bi bi-info-circle"></i> <strong>Note:</strong> Both student and guardian must affix their signature if the student is aged below 18 years old.
                </div>
            </form>
        </div>
        <div class="modal-actions">
            <button class="btn-modal-cancel" onclick="closeModal('consent')"><i class="bi bi-x-circle"></i> Cancel</button>
            <button class="btn-modal-save" onclick="document.getElementById('form-consent').requestSubmit()"><i class="bi bi-check-circle"></i> Save Consent Record</button>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL: Medical Certificate -->
<!-- ============================================================ -->
<div class="modal-overlay" id="modal-certificate">
    <div class="modal-box" style="max-width:860px;">
        <div class="modal-head">
            <div>
                <div class="modal-head-title"><i class="bi bi-file-earmark-medical me-2"></i>Medical Certificate</div>
                <div class="modal-head-sub">PUP Ttech Clinic · Medical Services Department</div>
            </div>
            <button class="modal-close-btn" onclick="closeModal('certificate')">&times;</button>
        </div>
        <div class="modal-scroll">
            <div id="cert-form-step">
                <form id="form-certificate" onsubmit="generateCertPreview(event)">
                    <div class="f-section-title"><i class="bi bi-person"></i> Patient & Issue Details</div>
                    <div class="f-grid-3">
                        <div class="f-group">
                            <label class="f-label">Issue Date <span class="req">*</span></label>
                            <input class="f-control" type="date" id="cert-issue-date" required>
                        </div>
                        <div class="f-group" style="grid-column:span 2;">
                            <label class="f-label">Student / Patient Name <span class="req">*</span></label>
                            <input class="f-control" type="text" id="cert-student-name" placeholder="Full Name" required>
                        </div>
                    </div>
                    <div class="f-group" style="margin-bottom:18px;">
                        <label class="f-label">Diagnosis <span class="req">*</span></label>
                        <textarea class="f-control" id="cert-diagnosis" placeholder="Enter diagnosis or assessment..." required></textarea>
                    </div>
                    <div class="f-group" style="margin-bottom:18px;">
                        <label class="f-label">Remarks / Symptoms</label>
                        <textarea class="f-control" id="cert-remarks" placeholder="Describe patient's main complaint or symptoms..." style="min-height:75px;"></textarea>
                    </div>
                    <div class="f-grid-3">
                        <div class="f-group">
                            <label class="f-label">Purpose <span class="req">*</span></label>
                            <select class="f-control" id="cert-purpose" required>
                                <option value="">Select Purpose</option>
                                <option value="Sick Leave">Sick Leave</option>
                                <option value="Medical Clearance">Medical Clearance</option>
                                <option value="Fitness to Work/Study">Fitness to Work/Study</option>
                                <option value="Excuse from PE/Activities">Excuse from PE/Activities</option>
                                <option value="Legal/Court Requirements">Legal/Court Requirements</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="f-group">
                            <label class="f-label">Clinic Physician <span class="req">*</span></label>
                            <input class="f-control" type="text" id="cert-physician" placeholder="Dr. Full Name, M.D." required>
                        </div>
                        <div class="f-group">
                            <label class="f-label">License Number <span class="req">*</span></label>
                            <input class="f-control" type="text" id="cert-license" placeholder="e.g., 0115224" required>
                        </div>
                    </div>
                    <div class="f-grid-2">
                        <div class="f-group">
                            <label class="f-label">Days of Rest (if applicable)</label>
                            <input class="f-control" type="number" id="cert-days-rest" placeholder="Number of days" min="0">
                        </div>
                        <div class="f-group">
                            <label class="f-label">Valid Until</label>
                            <input class="f-control" type="date" id="cert-valid-until">
                        </div>
                    </div>
                    <button type="submit" style="display:none;" id="cert-submit-trigger"></button>
                </form>
            </div>
            <div id="cert-preview-step" style="display:none;">
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:18px;">
                    <button class="btn-modal-secondary" onclick="backToForm()" style="padding:8px 16px; font-size:13px;">
                        <i class="bi bi-arrow-left"></i> Back to Form
                    </button>
                    <span style="font-size:13px; color:var(--text-gray);">Review the certificate below before printing or saving.</span>
                </div>
                <div class="cert-preview-box" id="cert-preview-content"></div>
            </div>
        </div>
        <div class="modal-actions" id="cert-modal-actions">
            <button class="btn-modal-cancel" onclick="closeModal('certificate')"><i class="bi bi-x-circle"></i> Cancel</button>
            <button class="btn-modal-save" id="cert-generate-btn" onclick="document.getElementById('cert-submit-trigger').click()">
                <i class="bi bi-eye"></i> Preview Certificate
            </button>
        </div>
        <div class="modal-actions" id="cert-print-actions" style="display:none;">
            <button class="btn-modal-cancel" onclick="closeModal('certificate')"><i class="bi bi-x-circle"></i> Close</button>
            <button class="btn-modal-secondary" onclick="backToForm()"><i class="bi bi-pencil"></i> Edit</button>
            <button class="btn-modal-save" onclick="printCertificate()"><i class="bi bi-printer"></i> Print Certificate</button>
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
    const pageFiles = {
        dashboard: 'nurse_dashboard.php',
        roster: 'nurse_roster.php',
        appointments: 'appointments.php',
        records: 'patient_records.php',
        consultations: 'consultations.php',
        profile: 'profile.php'
    };
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
    ['c-date','lab-date','he-date','con-date','con-sign-date','cert-issue-date','s-date','f-date'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = today;
    });
    const ct = document.getElementById('c-time');
    if (ct) ct.value = `${hh}:${mm}`;

    // Init live previews with today's date
    const todayFormatted = new Date().toLocaleDateString('en-US', { year:'numeric', month:'long', day:'numeric' });
    ['s-preview-date','f-preview-date'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.textContent = todayFormatted;
    });

    /* LIVE PREVIEW HELPERS */
    function liveUpdate(previewId, value) {
        const el = document.getElementById(previewId);
        if (!el) return;
        el.textContent = value || '\u00A0';
    }
    function liveUpdateDate(previewId, value) {
        const el = document.getElementById(id);
        if (!value) return;
        const formatted = new Date(value + 'T00:00:00').toLocaleDateString('en-US', { year:'numeric', month:'long', day:'numeric' });
        if (el) el.textContent = formatted;
    }
    // Fix: liveUpdateDate uses previewId not id
    function liveUpdateDate(previewId, value) {
        const el = document.getElementById(previewId);
        if (!el || !value) return;
        const formatted = new Date(value + 'T00:00:00').toLocaleDateString('en-US', { year:'numeric', month:'long', day:'numeric' });
        el.textContent = formatted;
    }

    /* MODAL CONTROLS */
    function openModal(id) {
        document.getElementById('modal-' + id).classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        document.getElementById('modal-' + id).classList.remove('show');
        document.body.style.overflow = 'auto';
        if (id === 'certificate') resetCertModal();
    }

    document.querySelectorAll('.modal-overlay').forEach(m => {
        m.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('show');
                document.body.style.overflow = 'auto';
                if (this.id === 'modal-certificate') resetCertModal();
            }
        });
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.show').forEach(m => {
                m.classList.remove('show');
                document.body.style.overflow = 'auto';
                if (m.id === 'modal-certificate') resetCertModal();
            });
        }
    });

    /* CHECKBOX STYLING */
    function toggleCheck(el) {
        el.closest('.check-item').classList.toggle('checked', el.checked);
    }

    /* SAVE FORM */
    const formTitles = {
        'consultation': 'Consultation Saved!',
        'laboratory': 'Lab Request Submitted!',
        'healthexam': 'Health Exam Record Saved!',
        'clearance-student': 'Student Clearance Issued!',
        'clearance-faculty': 'Faculty Clearance Issued!',
        'consent': 'Consent Record Saved!'
    };
    const formMsgs = {
        'consultation': 'The consultation record has been successfully documented.',
        'laboratory': 'Laboratory examination request has been recorded.',
        'healthexam': 'Health examination record has been saved successfully.',
        'clearance-student': 'Medical clearance for student has been issued and recorded.',
        'clearance-faculty': 'Medical clearance for faculty/employee has been issued and recorded.',
        'consent': 'Patient consent form has been saved.'
    };

    function saveForm(e, id) {
        e.preventDefault();
        closeModal(id);
        showToast(formTitles[id] || 'Saved!', formMsgs[id] || 'Form successfully submitted.');
        setTimeout(() => {
            const form = document.getElementById('form-' + id);
            if (form) form.reset();
            ['c-date','lab-date','he-date','con-date','con-sign-date','s-date','f-date'].forEach(did => {
                const el = document.getElementById(did);
                if (el) el.value = today;
            });
            // Reset live previews
            ['s-preview-date','f-preview-date'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.textContent = todayFormatted;
            });
            ['s-preview-name','f-preview-name','s-preview-purpose','f-preview-purpose'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.textContent = '\u00A0';
            });
            const sp = document.getElementById('s-preview-physician');
            if (sp) sp.textContent = 'FELICITAS A. BERMUDEZ, M.D. M.D.';
            const fp = document.getElementById('f-preview-physician');
            if (fp) fp.textContent = 'FELICITAS A. BERMUDEZ, M.D. M.D.';
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

    /* MEDICAL CERTIFICATE */
    function generateCertPreview(e) {
        e.preventDefault();
        const issueDate   = document.getElementById('cert-issue-date').value;
        const studentName = document.getElementById('cert-student-name').value;
        const diagnosis   = document.getElementById('cert-diagnosis').value;
        const remarks     = document.getElementById('cert-remarks').value;
        const purpose     = document.getElementById('cert-purpose').value;
        const physician   = document.getElementById('cert-physician').value;
        const license     = document.getElementById('cert-license').value;
        const daysRest    = document.getElementById('cert-days-rest').value;
        const validUntil  = document.getElementById('cert-valid-until').value;
        const fmtDate = d => new Date(d + 'T00:00:00').toLocaleDateString('en-US', { year:'numeric', month:'long', day:'numeric' });
        let restStatement = '';
        if (daysRest && parseInt(daysRest) > 0) {
            restStatement = ` and is advised to rest for <span class="cert-field">${daysRest} day(s)</span>`;
        }
        let validStatement = '';
        if (validUntil) {
            validStatement = `<p style="margin-top:6px;"><strong>Valid Until:</strong> <span class="cert-field">${fmtDate(validUntil)}</span></p>`;
        }
        document.getElementById('cert-preview-content').innerHTML = `
            <div class="cert-title">MEDICAL CERTIFICATE</div>
            <div class="cert-clinic-name">PUP Ttech Clinic — Medical Services Department</div>
            <div class="cert-body">
                <p><strong>Date:</strong> ${fmtDate(issueDate)}</p><br>
                <p>TO WHOM IT MAY CONCERN:</p><br>
                <p>This is to certify that <span class="cert-field">${studentName}</span> was examined and treated at this clinic on <span class="cert-field">${fmtDate(issueDate)}</span>.</p><br>
                <p><strong>Diagnosis:</strong> <span class="cert-field">${diagnosis}</span></p>
                ${remarks ? `<p><strong>Remarks:</strong> ${remarks}</p>` : ''}
                <br>
                <p>This certificate is issued for the purpose of <span class="cert-field">${purpose}</span>${restStatement}.</p>
                ${validStatement}
            </div>
            <div class="cert-sig">
                <div class="cert-sig-line">${physician}</div>
                <div style="font-size:12px; margin-top:4px; text-align:right;">Attending Physician</div>
                <div style="font-size:12px; text-align:right;">License No: ${license}</div>
            </div>
        `;
        document.getElementById('cert-form-step').style.display = 'none';
        document.getElementById('cert-preview-step').style.display = 'block';
        document.getElementById('cert-modal-actions').style.display = 'none';
        document.getElementById('cert-print-actions').style.display = 'flex';
    }

    function backToForm() {
        document.getElementById('cert-form-step').style.display = 'block';
        document.getElementById('cert-preview-step').style.display = 'none';
        document.getElementById('cert-modal-actions').style.display = 'flex';
        document.getElementById('cert-print-actions').style.display = 'none';
    }

    function resetCertModal() { backToForm(); }

    function printCertificate() {
        const content = document.getElementById('cert-preview-content').innerHTML;
        const win = window.open('', '_blank');
        win.document.write(`<!DOCTYPE html><html><head><title>Medical Certificate</title>
            <style>
                body { font-family:'Times New Roman',serif; padding:60px; color:#1f2937; }
                .cert-title { font-size:26px; font-weight:bold; color:#7f1d1d; text-align:center; margin-bottom:8px; }
                .cert-clinic-name { font-size:16px; font-weight:600; text-align:center; margin-bottom:18px; padding-bottom:16px; border-bottom:2px solid #7f1d1d; }
                .cert-body { line-height:2; font-size:14px; margin:24px 0; }
                .cert-field { font-weight:bold; color:#7f1d1d; }
                .cert-sig { margin-top:60px; text-align:right; }
                .cert-sig-line { border-top:2px solid #1f2937; width:240px; margin-left:auto; padding-top:6px; font-weight:600; font-size:14px; }
            </style></head><body>${content}</body></html>`);
        win.document.close();
        win.focus();
        win.print();
        win.close();
        closeModal('certificate');
        showToast('Certificate Printed!', 'Medical certificate has been sent to the printer.');
    }

    document.getElementById('cert-days-rest').addEventListener('input', function() {
        const days = parseInt(this.value);
        if (days > 0) {
            const base = new Date(document.getElementById('cert-issue-date').value + 'T00:00:00');
            base.setDate(base.getDate() + days);
            document.getElementById('cert-valid-until').value = base.toISOString().split('T')[0];
        }
    });
</script>
</body>
</html>