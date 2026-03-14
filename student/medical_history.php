<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - Medical History</title>

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

        /* ── Sidebar ── */
        .sidebar {
            width: 275px;
            background: linear-gradient(180deg, #860303 3%, #B21414 79%, #940000 97%);
            color: white; position: fixed;
            height: 100vh; left: 0; top: 0;
            display: flex; flex-direction: column;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar-header { padding: 35px 25px; background: linear-gradient(180deg, #860303 0%, #6B0000 100%); }
        .sidebar-header h1 { font-size: 28px; font-weight: 700; margin-bottom: 5px; letter-spacing: -0.5px; }
        .sidebar-header p  { font-size: 14px; opacity: 0.9; margin-bottom: 0; }
        .sidebar-nav { flex: 1; padding-top: 20px; }
        .nav-item {
            padding: 16px 32px; cursor: pointer; transition: all 0.3s ease;
            font-size: 15px; font-weight: 500; border-left: 4px solid transparent;
            display: flex; align-items: center; gap: 12px;
            color: rgba(255,255,255,0.9); text-decoration: none;
        }
        .nav-item i { font-size: 18px; }
        .nav-item:hover { background-color: rgba(255,255,255,0.1); color: white; }
        .nav-item.active { background-color: rgba(0,0,0,0.2); border-left-color: white; color: white; }
        .sidebar-footer { padding: 20px; display: flex; justify-content: center; }
        .chatbot-toggle {
            width: 60px; height: 60px; background: white; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 28px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            cursor: pointer; transition: transform 0.3s ease;
        }
        .chatbot-toggle:hover { transform: scale(1.1); }

        /* ── Chatbot ── */
        .chatbot-container {
            position: fixed; bottom: 20px; left: 295px;
            width: 380px; height: 550px; background: white;
            border-radius: 16px; box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            display: none; flex-direction: column; z-index: 1001; overflow: hidden;
        }
        .chatbot-container.show { display: flex; }
        .chatbot-header {
            background: linear-gradient(90deg, var(--primary-gradient-start), var(--primary-gradient-end));
            color: white; padding: 20px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .chatbot-header h3 { margin: 0; font-size: 18px; font-weight: 600; display: flex; align-items: center; gap: 10px; }
        .chatbot-close {
            background: none; border: none; color: white; font-size: 24px; cursor: pointer;
            width: 30px; height: 30px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; transition: background 0.3s;
        }
        .chatbot-close:hover { background: rgba(255,255,255,0.2); }
        .quick-actions { padding: 12px 20px; display: flex; gap: 8px; flex-wrap: wrap; background: var(--bg-body); }
        .quick-action-btn {
            padding: 8px 16px; background: white;
            border: 2px solid var(--primary-color); color: var(--primary-color);
            border-radius: 20px; font-size: 12px; font-weight: 500;
            cursor: pointer; transition: all 0.3s; font-family: 'Poppins', sans-serif;
        }
        .quick-action-btn:hover { background: var(--primary-color); color: white; }
        .chatbot-messages { flex: 1; padding: 20px; overflow-y: auto; background: var(--bg-body); }
        .message { margin-bottom: 16px; display: flex; gap: 10px; }
        .message.user { flex-direction: row-reverse; }
        .message-avatar { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
        .message.bot .message-avatar { background: var(--primary-soft); }
        .message.user .message-avatar { background: var(--primary-color); color: white; }
        .message-content { max-width: 70%; padding: 12px 16px; border-radius: 12px; font-size: 14px; line-height: 1.5; }
        .message.bot .message-content  { background: white; color: var(--text-dark); border-bottom-left-radius: 4px; }
        .message.user .message-content { background: var(--primary-color); color: white; border-bottom-right-radius: 4px; }
        .chatbot-input-area { padding: 16px; background: white; border-top: 1px solid #e5e7eb; }
        .chatbot-input-wrapper { display: flex; gap: 10px; }
        .chatbot-input { flex: 1; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 24px; font-size: 14px; font-family: 'Poppins', sans-serif; outline: none; transition: border-color 0.3s; }
        .chatbot-input:focus { border-color: var(--primary-color); }
        .chatbot-send { width: 44px; height: 44px; background: var(--primary-color); color: white; border: none; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 18px; transition: background 0.3s; }
        .chatbot-send:hover { background: var(--primary-light); }
        .typing-indicator { display: flex; gap: 4px; padding: 12px 16px; }
        .typing-indicator span { width: 8px; height: 8px; background: var(--text-gray); border-radius: 50%; animation: typing 1.4s infinite; }
        .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
        .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }
        @keyframes typing { 0%,60%,100%{transform:translateY(0);opacity:.7} 30%{transform:translateY(-10px);opacity:1} }

        /* ── Main ── */
        .main-content { margin-left: 275px; padding: 40px 50px; }
        .page-header { margin-bottom: 40px; }
        .page-header h2 { color: var(--primary-color); font-size: 36px; font-weight: 700; margin-bottom: 8px; }
        .page-header p  { color: var(--text-gray); font-size: 16px; }

        .history-section { background: white; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); overflow: hidden; }
        .section-header {
            background: linear-gradient(90deg, var(--primary-gradient-start), var(--primary-gradient-end));
            color: white; padding: 20px 30px; font-size: 18px; font-weight: 600;
        }
        .table { margin-bottom: 0; }
        .table thead th {
            background-color: var(--primary-soft); padding: 18px 30px;
            font-weight: 600; color: var(--text-dark); font-size: 13px;
            text-transform: uppercase; letter-spacing: 0.5px; border: none;
        }
        .table tbody td {
            padding: 20px 30px; color: var(--text-dark); font-weight: 500;
            vertical-align: middle; border-color: #f0f0f0;
        }
        .table tbody tr:hover { background-color: var(--primary-soft); }
        .status-badge { padding: 6px 18px; border-radius: 20px; font-size: 13px; font-weight: 600; display: inline-block; }
        .status-completed { background-color: #d1ecf1; color: #0c5460; }
        .btn-details {
            background-color: var(--primary-color); color: white;
            padding: 8px 20px; border-radius: 6px; font-size: 13px;
            font-weight: 600; border: none; cursor: pointer; transition: all 0.3s;
        }
        .btn-details:hover { background-color: var(--primary-light); }

        /* ── View Details Modal ── */
        .modal-overlay {
            display: none; position: fixed; z-index: 2000;
            inset: 0; background: rgba(0,0,0,0.55);
            backdrop-filter: blur(5px);
            align-items: center; justify-content: center; padding: 20px;
        }
        .modal-overlay.show { display: flex; animation: fadeIn 0.25s ease; }
        @keyframes fadeIn { from{opacity:0} to{opacity:1} }

        .modal-box {
            background: white; border-radius: 18px;
            width: 100%; max-width: 780px; max-height: 92vh;
            overflow: hidden; display: flex; flex-direction: column;
            box-shadow: 0 24px 80px rgba(0,0,0,0.25);
            animation: slideUp 0.3s ease;
        }
        @keyframes slideUp { from{transform:translateY(40px);opacity:0} to{transform:translateY(0);opacity:1} }

        /* Modal Header */
        .modal-head {
            background: linear-gradient(90deg, var(--primary-gradient-start), var(--primary-gradient-end));
            color: white; padding: 24px 30px;
            display: flex; justify-content: space-between; align-items: flex-start;
            flex-shrink: 0;
        }
        .modal-head-title { font-size: 20px; font-weight: 700; margin-bottom: 3px; }
        .modal-head-sub   { font-size: 13px; opacity: 0.85; }
        .modal-close-btn {
            background: rgba(255,255,255,0.2); border: none; color: white;
            width: 34px; height: 34px; border-radius: 50%; font-size: 20px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s; flex-shrink: 0;
        }
        .modal-close-btn:hover { background: rgba(255,255,255,0.35); transform: rotate(90deg); }

        /* Meta strip */
        .record-meta {
            display: flex; gap: 0; flex-shrink: 0;
            border-bottom: 1px solid #f0f0f0;
            background: #fafafa;
        }
        .meta-item {
            flex: 1; padding: 16px 22px;
            border-right: 1px solid #f0f0f0;
            display: flex; flex-direction: column; gap: 3px;
        }
        .meta-item:last-child { border-right: none; }
        .meta-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-gray); }
        .meta-value { font-size: 14px; font-weight: 600; color: var(--text-dark); }
        .meta-value.highlight { color: var(--primary-color); }

        /* Scrollable body */
        .modal-scroll { overflow-y: auto; flex: 1; padding: 28px 30px; }

        /* Section titles */
        .d-section-title {
            font-size: 12px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.6px; color: var(--primary-color);
            padding-bottom: 8px; border-bottom: 2px solid var(--primary-soft);
            margin: 24px 0 16px; display: flex; align-items: center; gap: 8px;
        }
        .d-section-title:first-child { margin-top: 0; }

        /* Vitals grid */
        .vitals-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;
            margin-bottom: 4px;
        }
        .vital-card {
            background: #f9fafb; border: 1px solid #e5e7eb;
            border-radius: 10px; padding: 14px 16px;
        }
        .vital-card .v-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.4px; color: var(--text-gray); margin-bottom: 5px; }
        .vital-card .v-value { font-size: 20px; font-weight: 700; color: var(--text-dark); line-height: 1; }
        .vital-card .v-unit  { font-size: 11px; color: var(--text-gray); margin-top: 2px; }
        .vital-card.has-value { border-color: #fca5a5; background: var(--primary-soft); }
        .vital-card.has-value .v-value { color: var(--primary-color); }

        /* Read-only field */
        .d-grid-2 { display: grid; grid-template-columns: repeat(2,1fr); gap: 14px; margin-bottom: 14px; }
        .d-grid-1 { display: grid; grid-template-columns: 1fr; gap: 14px; margin-bottom: 14px; }
        .d-field { display: flex; flex-direction: column; gap: 4px; }
        .d-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; color: var(--text-gray); }
        .d-value {
            font-size: 13px; font-weight: 500; color: var(--text-dark);
            background: #f9fafb; border: 1px solid #e5e7eb;
            border-radius: 8px; padding: 10px 14px; line-height: 1.6;
            min-height: 40px;
        }
        .d-value.multiline { min-height: 70px; white-space: pre-wrap; }
        .d-value.empty { color: #9ca3af; font-style: italic; }

        /* Status pill */
        .status-pill {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 16px; border-radius: 999px;
            font-size: 13px; font-weight: 600;
        }
        .pill-completed  { background: #d1fae5; color: #065f46; }
        .pill-active     { background: #dbeafe; color: #1e40af; }
        .pill-follow-up  { background: #fef3c7; color: #92400e; }
        .pill-resolved   { background: #f0fdf4; color: #166534; }

        /* Follow-up alert */
        .followup-alert {
            background: #fff7ed; border: 1.5px solid #fed7aa;
            border-radius: 10px; padding: 14px 18px;
            display: flex; align-items: center; gap: 12px;
            margin-top: 4px;
        }
        .followup-alert i { color: #c2410c; font-size: 20px; flex-shrink: 0; }
        .followup-alert .fa-text { font-size: 13px; color: #92400e; font-weight: 500; }
        .followup-alert .fa-date { font-weight: 700; }

        /* Modal footer */
        .modal-footer-bar {
            padding: 18px 30px; background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            display: flex; justify-content: flex-end; gap: 10px;
            flex-shrink: 0;
        }
        .btn-close-modal {
            padding: 10px 28px; border-radius: 8px; border: none;
            background: var(--primary-color); color: white;
            font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 600;
            cursor: pointer; transition: all 0.2s;
        }
        .btn-close-modal:hover { background: var(--primary-light); }

        @media (max-width: 768px) {
            .sidebar { width: 200px; }
            .main-content { margin-left: 200px; padding: 30px 20px; }
            .vitals-grid { grid-template-columns: repeat(2,1fr); }
            .d-grid-2 { grid-template-columns: 1fr; }
            .record-meta { flex-wrap: wrap; }
            .meta-item { min-width: 50%; border-right: none; border-bottom: 1px solid #f0f0f0; }
        }
    </style>
</head>
<body>

    <!-- ── Sidebar ── -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h1>Student Portal</h1>
            <p>Medical Services</p>
        </div>
        <nav class="sidebar-nav">
            <a href="book_appointment.php" class="nav-item" style="color:inherit;text-decoration:none;">
                <i class="bi bi-calendar-plus"></i><span>Book Appointment</span>
            </a>
            <a href="medical_history.php" class="nav-item active" style="color:inherit;text-decoration:none;">
                <i class="bi bi-clock-history"></i><span>Medical History</span>
            </a>
            <a href="certificates.php" class="nav-item" style="color:inherit;text-decoration:none;">
                <i class="bi bi-file-earmark-medical"></i><span>Certificates</span>
            </a>
            <a href="profile.php" class="nav-item" style="color:inherit;text-decoration:none;">
                <i class="bi bi-person"></i><span>Profile</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <div class="chatbot-toggle" id="chatbotToggle">🤖</div>
        </div>
    </div>

    <!-- ── Chatbot ── -->
    <div class="chatbot-container" id="chatbotContainer">
        <div class="chatbot-header">
            <h3><i class="bi bi-robot"></i> Medical Assistant</h3>
            <button class="chatbot-close" id="chatbotClose">&times;</button>
        </div>
        <div class="quick-actions">
            <button class="quick-action-btn" data-message="Show my records">📋 My Records</button>
            <button class="quick-action-btn" data-message="Latest consultation">🩺 Latest Visit</button>
            <button class="quick-action-btn" data-message="Download records">💾 Download</button>
        </div>
        <div class="chatbot-messages" id="chatbotMessages">
            <div class="message bot">
                <div class="message-avatar">🤖</div>
                <div class="message-content">Hello! I can help you with your medical history. What would you like to know?</div>
            </div>
        </div>
        <div class="chatbot-input-area">
            <div class="chatbot-input-wrapper">
                <input type="text" class="chatbot-input" id="chatbotInput" placeholder="Type your message..." autocomplete="off">
                <button class="chatbot-send" id="chatbotSend"><i class="bi bi-send-fill"></i></button>
            </div>
        </div>
    </div>

    <!-- ── Main ── -->
    <main class="main-content">
        <div class="page-header">
            <h2>Medical History</h2>
            <p>View your past consultations and treatments</p>
        </div>

        <div class="history-section">
            <div class="section-header">Recent Records</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Healthcare Provider</th>
                        <th>Type</th>
                        <th>Diagnosis</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Mar. 20, 2024</td>
                        <td>Nurse Garcia</td>
                        <td>General Consultation</td>
                        <td>Common Cold</td>
                        <td><span class="status-badge status-completed">Completed</span></td>
                        <td>
                            <button class="btn-details" onclick="viewDetails(0)">View Details</button>
                        </td>
                    </tr>
                    <tr>
                        <td>May 20, 2025</td>
                        <td>Dr. Santos</td>
                        <td>Medical Clearance</td>
                        <td>Fit for Activities</td>
                        <td><span class="status-badge status-completed">Completed</span></td>
                        <td>
                            <button class="btn-details" onclick="viewDetails(1)">View Details</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Apr. 2, 2025</td>
                        <td>Nurse Cruz</td>
                        <td>Follow-up</td>
                        <td>Normal</td>
                        <td><span class="status-badge status-completed">Completed</span></td>
                        <td>
                            <button class="btn-details" onclick="viewDetails(2)">View Details</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

    <!-- ── View Details Modal ── -->
    <div class="modal-overlay" id="detailsModal">
        <div class="modal-box">

            <!-- Header -->
            <div class="modal-head">
                <div>
                    <div class="modal-head-title" id="modal-type-title">
                        <i class="bi bi-chat-square-text me-2"></i>Consultation Record
                    </div>
                    <div class="modal-head-sub">PUP Medical Services Department</div>
                </div>
                <button class="modal-close-btn" onclick="closeDetails()">&times;</button>
            </div>

            <!-- Meta strip: date / time / provider / status -->
            <div class="record-meta">
                <div class="meta-item">
                    <span class="meta-label"><i class="bi bi-calendar3"></i> Date</span>
                    <span class="meta-value" id="d-meta-date">—</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label"><i class="bi bi-clock"></i> Time</span>
                    <span class="meta-value" id="d-meta-time">—</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label"><i class="bi bi-person-badge"></i> Healthcare Provider</span>
                    <span class="meta-value highlight" id="d-meta-provider">—</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label"><i class="bi bi-tag"></i> Status</span>
                    <span class="meta-value" id="d-meta-status">—</span>
                </div>
            </div>

            <!-- Scrollable content -->
            <div class="modal-scroll">

                <!-- Patient Info -->
                <div class="d-section-title"><i class="bi bi-person"></i> Patient Information</div>
                <div class="d-grid-2">
                    <div class="d-field">
                        <span class="d-label">Patient Name</span>
                        <span class="d-value" id="d-patient-name">—</span>
                    </div>
                    <div class="d-field">
                        <span class="d-label">ID Number</span>
                        <span class="d-value" id="d-patient-id">—</span>
                    </div>
                    <div class="d-field">
                        <span class="d-label">Consultation Type</span>
                        <span class="d-value" id="d-consult-type">—</span>
                    </div>
                    <div class="d-field">
                        <span class="d-label">College / Department</span>
                        <span class="d-value" id="d-department">—</span>
                    </div>
                </div>

                <!-- Vital Signs -->
                <div class="d-section-title"><i class="bi bi-thermometer-half"></i> Vital Signs</div>
                <div class="vitals-grid">
                    <div class="vital-card" id="vc-bp">
                        <div class="v-label">Blood Pressure</div>
                        <div class="v-value" id="d-bp">—</div>
                        <div class="v-unit">mmHg</div>
                    </div>
                    <div class="vital-card" id="vc-temp">
                        <div class="v-label">Temperature</div>
                        <div class="v-value" id="d-temp">—</div>
                        <div class="v-unit">°C</div>
                    </div>
                    <div class="vital-card" id="vc-hr">
                        <div class="v-label">Heart Rate</div>
                        <div class="v-value" id="d-hr">—</div>
                        <div class="v-unit">bpm</div>
                    </div>
                    <div class="vital-card" id="vc-rr">
                        <div class="v-label">Respiratory Rate</div>
                        <div class="v-value" id="d-rr">—</div>
                        <div class="v-unit">/min</div>
                    </div>
                    <div class="vital-card" id="vc-ht">
                        <div class="v-label">Height</div>
                        <div class="v-value" id="d-ht">—</div>
                        <div class="v-unit">cm</div>
                    </div>
                    <div class="vital-card" id="vc-wt">
                        <div class="v-label">Weight</div>
                        <div class="v-value" id="d-wt">—</div>
                        <div class="v-unit">kg</div>
                    </div>
                </div>

                <!-- Clinical Information -->
                <div class="d-section-title"><i class="bi bi-journal-medical"></i> Clinical Information</div>
                <div class="d-grid-1">
                    <div class="d-field">
                        <span class="d-label">Chief Complaint / Symptoms</span>
                        <span class="d-value multiline" id="d-complaint">—</span>
                    </div>
                </div>
                <div class="d-grid-2">
                    <div class="d-field">
                        <span class="d-label">Physical Examination Findings</span>
                        <span class="d-value multiline" id="d-findings">—</span>
                    </div>
                    <div class="d-field">
                        <span class="d-label">Diagnosis / Assessment</span>
                        <span class="d-value multiline" id="d-diagnosis">—</span>
                    </div>
                </div>
                <div class="d-grid-1">
                    <div class="d-field">
                        <span class="d-label">Treatment & Recommendations</span>
                        <span class="d-value multiline" id="d-treatment">—</span>
                    </div>
                </div>
                <div class="d-grid-2">
                    <div class="d-field">
                        <span class="d-label">Medications Given</span>
                        <span class="d-value multiline" id="d-medications">—</span>
                    </div>
                    <div class="d-field">
                        <span class="d-label">Additional Notes</span>
                        <span class="d-value multiline" id="d-notes">—</span>
                    </div>
                </div>

                <!-- Follow-up -->
                <div id="followup-section" style="display:none;">
                    <div class="d-section-title"><i class="bi bi-calendar-check"></i> Follow-up</div>
                    <div class="followup-alert">
                        <i class="bi bi-bell-fill"></i>
                        <div class="fa-text">
                            Your follow-up appointment is scheduled on <span class="fa-date" id="d-followup">—</span>.
                            Please visit the clinic or book an appointment on or before this date.
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div class="modal-footer-bar">
                <button class="btn-close-modal" onclick="closeDetails()">
                    <i class="bi bi-x-circle me-1"></i> Close
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>

        // ── Sample consultation records (as saved by medical staff via consultations.php) ──
        const records = [
            {
                type:         'General Consultation',
                date:         'March 20, 2024',
                time:         '9:15 AM',
                provider:     'Nurse Garcia',
                status:       'Completed',
                statusClass:  'pill-completed',
                patientName:  'Juan Dela Cruz',
                patientId:    '2023-12345-MN-0',
                consultType:  'General Consultation',
                department:   'CCS',
                bp:           '110/70',
                temp:         '36.9',
                hr:           '78',
                rr:           '16',
                ht:           '168',
                wt:           '62',
                complaint:    'Patient complained of runny nose, sore throat, and mild headache for 2 days.',
                findings:     'Pharynx slightly hyperemic. No tonsillar enlargement. Clear breath sounds bilaterally.',
                diagnosis:    'Acute Nasopharyngitis (Common Cold)',
                treatment:    'Rest and increase fluid intake. Avoid cold drinks and air-conditioned environments for 3–5 days.',
                medications:  'Paracetamol 500mg – 1 tablet every 6 hours as needed for fever/pain\nVitamin C 500mg – 1 tablet once daily',
                notes:        'Patient advised to return if symptoms worsen or fever exceeds 38.5°C.',
                followUp:     ''
            },
            {
                type:         'Medical Clearance',
                date:         'May 20, 2025',
                time:         '10:30 AM',
                provider:     'Dr. Santos',
                status:       'Completed',
                statusClass:  'pill-completed',
                patientName:  'Juan Dela Cruz',
                patientId:    '2023-12345-MN-0',
                consultType:  'Medical Clearance',
                department:   'CCS',
                bp:           '120/80',
                temp:         '36.6',
                hr:           '75',
                rr:           '17',
                ht:           '168',
                wt:           '63',
                complaint:    'Request for medical clearance for off-campus OJT / immersion activity.',
                findings:     'No acute distress. Cardiovascular and respiratory systems within normal limits.',
                diagnosis:    'Fit for Activities — No contraindications found.',
                treatment:    'No treatment required. Patient is physically fit for the requested activity.',
                medications:  'None',
                notes:        'Clearance valid for the current academic semester (AY 2025-2026, 2nd Sem).',
                followUp:     ''
            },
            {
                type:         'Follow-up',
                date:         'April 2, 2025',
                time:         '8:45 AM',
                provider:     'Nurse Cruz',
                status:       'Completed',
                statusClass:  'pill-completed',
                patientName:  'Juan Dela Cruz',
                patientId:    '2023-12345-MN-0',
                consultType:  'Follow-up',
                department:   'CCS',
                bp:           '115/75',
                temp:         '36.7',
                hr:           '72',
                rr:           '16',
                ht:           '168',
                wt:           '62',
                complaint:    'Follow-up visit after previous consultation for cold/upper respiratory tract infection.',
                findings:     'Pharynx no longer hyperemic. Breath sounds clear bilaterally. No nasal congestion.',
                diagnosis:    'Resolved — Upper Respiratory Tract Infection',
                treatment:    'No further medication required. Patient may resume normal activities.',
                medications:  'None prescribed. Previous medications completed.',
                notes:        'Patient recovered well. No further follow-up required unless symptoms recur.',
                followUp:     'May 5, 2025'
            }
        ];

        function viewDetails(index) {
            const r = records[index];

            // Header
            document.getElementById('modal-type-title').innerHTML =
                `<i class="bi bi-chat-square-text me-2"></i>${r.type}`;

            // Meta strip
            document.getElementById('d-meta-date').textContent     = r.date;
            document.getElementById('d-meta-time').textContent     = r.time;
            document.getElementById('d-meta-provider').textContent = r.provider;
            document.getElementById('d-meta-status').innerHTML     =
                `<span class="status-pill ${r.statusClass}"><i class="bi bi-check-circle-fill"></i>${r.status}</span>`;

            // Patient info
            document.getElementById('d-patient-name').textContent  = r.patientName;
            document.getElementById('d-patient-id').textContent    = r.patientId;
            document.getElementById('d-consult-type').textContent  = r.consultType;
            document.getElementById('d-department').textContent    = r.department;

            // Vitals
            const vitals = {
                bp: { id: 'd-bp',   card: 'vc-bp'   },
                temp: { id: 'd-temp', card: 'vc-temp' },
                hr:   { id: 'd-hr',   card: 'vc-hr'   },
                rr:   { id: 'd-rr',   card: 'vc-rr'   },
                ht:   { id: 'd-ht',   card: 'vc-ht'   },
                wt:   { id: 'd-wt',   card: 'vc-wt'   },
            };
            Object.entries(vitals).forEach(([key, { id, card }]) => {
                const val = r[key];
                document.getElementById(id).textContent = val || '—';
                document.getElementById(card).classList.toggle('has-value', !!val);
            });

            // Clinical fields
            const fields = [
                ['d-complaint',   r.complaint],
                ['d-findings',    r.findings],
                ['d-diagnosis',   r.diagnosis],
                ['d-treatment',   r.treatment],
                ['d-medications', r.medications],
                ['d-notes',       r.notes],
            ];
            fields.forEach(([id, val]) => {
                const el = document.getElementById(id);
                el.textContent = val || '—';
                el.classList.toggle('empty', !val);
            });

            // Follow-up
            const fuSection = document.getElementById('followup-section');
            if (r.followUp) {
                document.getElementById('d-followup').textContent = r.followUp;
                fuSection.style.display = 'block';
            } else {
                fuSection.style.display = 'none';
            }

            // Show modal
            document.getElementById('detailsModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeDetails() {
            document.getElementById('detailsModal').classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        // Close on backdrop click
        document.getElementById('detailsModal').addEventListener('click', function(e) {
            if (e.target === this) closeDetails();
        });

        // Close on Escape
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeDetails();
        });

        // ── Chatbot ──
        const chatbotToggle    = document.getElementById('chatbotToggle');
        const chatbotContainer = document.getElementById('chatbotContainer');
        const chatbotClose     = document.getElementById('chatbotClose');
        const chatbotMessages  = document.getElementById('chatbotMessages');
        const chatbotInput     = document.getElementById('chatbotInput');
        const chatbotSend      = document.getElementById('chatbotSend');

        chatbotToggle.addEventListener('click', () => chatbotContainer.classList.toggle('show'));
        chatbotClose.addEventListener('click',  () => chatbotContainer.classList.remove('show'));

        document.querySelectorAll('.quick-action-btn').forEach(btn => {
            btn.addEventListener('click', () => sendMessage(btn.getAttribute('data-message')));
        });

        function sendMessage(messageText = null) {
            const text = messageText || chatbotInput.value.trim();
            if (!text) return;
            addMessage(text, 'user');
            chatbotInput.value = '';
            showTypingIndicator();
            setTimeout(() => { hideTypingIndicator(); addMessage(getBotResponse(text), 'bot'); }, 900 + Math.random() * 700);
        }

        function addMessage(text, sender) {
            const div = document.createElement('div');
            div.className = `message ${sender}`;
            div.innerHTML = `<div class="message-avatar">${sender === 'bot' ? '🤖' : '👤'}</div><div class="message-content">${text}</div>`;
            chatbotMessages.appendChild(div);
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
        }

        function showTypingIndicator() {
            const div = document.createElement('div');
            div.className = 'message bot'; div.id = 'typingIndicator';
            div.innerHTML = `<div class="message-avatar">🤖</div><div class="message-content typing-indicator"><span></span><span></span><span></span></div>`;
            chatbotMessages.appendChild(div);
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
        }

        function hideTypingIndicator() {
            const el = document.getElementById('typingIndicator');
            if (el) el.remove();
        }

        function getBotResponse(message) {
            const m = message.toLowerCase();
            if (m.includes('record') || m.includes('history'))
                return "You have 3 medical records on file. Click 'View Details' on any row to see the full consultation record.";
            if (m.includes('latest') || m.includes('recent') || m.includes('last'))
                return "Your most recent visit was on May 20, 2025 with Dr. Santos — Medical Clearance. You were found fit for activities.";
            if (m.includes('download') || m.includes('print'))
                return "To print a record, open it using 'View Details' and use your browser's print function (Ctrl+P).";
            if (m.includes('diagnosis') || m.includes('treatment'))
                return "Click 'View Details' next to any record to see the full diagnosis, treatment, and medications prescribed.";
            if (m.includes('hello') || m.includes('hi'))
                return "Hello! I can help you view and understand your medical history. What would you like to know?";
            return "I can help you view past consultations, diagnoses, and treatment records. What do you need?";
        }

        chatbotSend.addEventListener('click', () => sendMessage());
        chatbotInput.addEventListener('keypress', e => { if (e.key === 'Enter') sendMessage(); });
    </script>
</body>
</html>