<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - Certificates</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-body);
            min-height: 100vh;
        }

        .sidebar {
            width: 275px;
            background: linear-gradient(180deg, #860303 3%, #B21414 79%, #940000 97%);
            color: white;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-header {
            padding: 35px 25px;
            background: linear-gradient(180deg, #860303 0%, #6B0000 100%);
        }

        .sidebar-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
            letter-spacing: -0.5px;
        }

        .sidebar-header p {
            font-size: 14px;
            font-weight: 400;
            opacity: 0.9;
            margin-bottom: 0;
        }

        .sidebar-nav {
            flex: 1;
            padding-top: 20px;
        }

        .nav-item {
            padding: 16px 32px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 15px;
            font-weight: 500;
            border-left: 4px solid transparent;
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255, 255, 255, 0.9);
        }

        .nav-item i {
            font-size: 18px;
        }

        .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .nav-item.active {
            background-color: rgba(0, 0, 0, 0.2);
            border-left-color: white;
            color: white;
        }

        .sidebar-footer {
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .chatbot-toggle {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .chatbot-toggle:hover {
            transform: scale(1.1);
        }

        .chatbot-container {
            position: fixed;
            bottom: 20px;
            left: 295px;
            width: 380px;
            height: 550px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            display: none;
            flex-direction: column;
            z-index: 1000;
            overflow: hidden;
        }

        .chatbot-container.show {
            display: flex;
        }

        .chatbot-header {
            background: linear-gradient(90deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chatbot-header h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chatbot-close {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background 0.3s ease;
        }

        .chatbot-close:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .chatbot-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background: var(--bg-body);
        }

        .message {
            margin-bottom: 16px;
            display: flex;
            gap: 10px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message.user {
            flex-direction: row-reverse;
        }

        .message-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .message.bot .message-avatar {
            background: var(--primary-soft);
        }

        .message.user .message-avatar {
            background: var(--primary-color);
            color: white;
        }

        .message-content {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 14px;
            line-height: 1.5;
        }

        .message.bot .message-content {
            background: white;
            color: var(--text-dark);
            border-bottom-left-radius: 4px;
        }

        .message.user .message-content {
            background: var(--primary-color);
            color: white;
            border-bottom-right-radius: 4px;
        }

        .chatbot-input-area {
            padding: 16px;
            background: white;
            border-top: 1px solid #e5e7eb;
        }

        .chatbot-input-wrapper {
            display: flex;
            gap: 10px;
        }

        .chatbot-input {
            flex: 1;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 24px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .chatbot-input:focus {
            border-color: var(--primary-color);
        }

        .chatbot-send {
            width: 44px;
            height: 44px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: background 0.3s ease;
        }

        .chatbot-send:hover {
            background: var(--primary-light);
        }

        .chatbot-send:disabled {
            background: #d1d5db;
            cursor: not-allowed;
        }

        .typing-indicator {
            display: flex;
            gap: 4px;
            padding: 12px 16px;
        }

        .typing-indicator span {
            width: 8px;
            height: 8px;
            background: var(--text-gray);
            border-radius: 50%;
            animation: typing 1.4s infinite;
        }

        .typing-indicator span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-indicator span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typing {
            0%, 60%, 100% {
                transform: translateY(0);
                opacity: 0.7;
            }
            30% {
                transform: translateY(-10px);
                opacity: 1;
            }
        }

        .quick-actions {
            padding: 12px 20px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            background: var(--bg-body);
        }

        .quick-action-btn {
            padding: 8px 16px;
            background: white;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        .quick-action-btn:hover {
            background: var(--primary-color);
            color: white;
        }

        .main-content {
            margin-left: 275px;
            padding: 40px 50px;
        }

        .page-header {
            margin-bottom: 40px;
        }

        .page-header h2 {
            color: var(--primary-color);
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .page-header p {
            color: var(--text-gray);
            font-size: 16px;
            font-weight: 400;
        }

        .certificates-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            overflow: hidden;
        }

        .section-header {
            background: linear-gradient(90deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
            color: white;
            padding: 20px 30px;
            font-size: 18px;
            font-weight: 600;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: var(--primary-soft);
            padding: 18px 30px;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }

        .table tbody td {
            padding: 20px 30px;
            color: var(--text-dark);
            font-weight: 500;
            vertical-align: middle;
            border-color: #f0f0f0;
        }

        .table tbody tr:hover {
            background-color: var(--primary-soft);
        }

        .status-badge {
            padding: 6px 18px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
        }

        .status-valid {
            background-color: #d4edda;
            color: #155724;
        }

        .btn-action {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 8px;
        }

        .btn-view {
            background-color: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-view:hover {
            background-color: var(--primary-soft);
        }

        .btn-download {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-download:hover {
            background-color: var(--primary-light);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 200px;
                padding: 30px 20px;
            }

            .table {
                font-size: 14px;
            }
        }

        /* ── Modal overlay ── */
        .modal-overlay {
            display: none; position: fixed; z-index: 2000;
            inset: 0; background: rgba(0,0,0,0.55);
            backdrop-filter: blur(5px);
            align-items: center; justify-content: center; padding: 20px;
        }
        .modal-overlay.show { display: flex; animation: mfadeIn 0.25s ease; }
        @keyframes mfadeIn { from{opacity:0} to{opacity:1} }
        .modal-box {
            background: white; border-radius: 18px;
            width: 100%; max-width: 640px; max-height: 92vh;
            overflow: hidden; display: flex; flex-direction: column;
            box-shadow: 0 24px 80px rgba(0,0,0,0.25);
            animation: mslideUp 0.3s ease;
        }
        @keyframes mslideUp { from{transform:translateY(40px);opacity:0} to{transform:translateY(0);opacity:1} }
        .modal-head {
            background: linear-gradient(90deg, #7f1d1d, #ef4444);
            color: white; padding: 22px 28px;
            display: flex; justify-content: space-between; align-items: center;
            flex-shrink: 0;
        }
        .modal-head-title { font-size: 18px; font-weight: 700; }
        .modal-head-sub   { font-size: 12px; opacity: 0.85; margin-top: 2px; }
        .modal-close-btn {
            background: rgba(255,255,255,0.2); border: none; color: white;
            width: 32px; height: 32px; border-radius: 50%; font-size: 18px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s; flex-shrink: 0;
        }
        .modal-close-btn:hover { background: rgba(255,255,255,0.35); transform: rotate(90deg); }
        .modal-scroll { overflow-y: auto; flex: 1; padding: 28px; background: #f3f4f6; }
        /* PUP cert document inside modal */
        .cert-document {
            background: white; border-radius: 8px; padding: 32px 40px;
            font-family: 'Times New Roman', serif;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        .cert-doc-header {
            display: flex; align-items: center; gap: 14px;
            margin-bottom: 12px; padding-bottom: 12px;
            border-bottom: 2.5px solid #7f1d1d;
        }
        .cert-doc-logo {
            width: 58px; height: 58px; background: #7f1d1d;
            border-radius: 50%; display: flex; align-items: center;
            justify-content: center; color: white; font-size: 18px;
            font-weight: 700; flex-shrink: 0; font-family: 'Poppins', sans-serif;
        }
        .cert-doc-header-text { flex: 1; text-align: center; }
        .cert-doc-header-text .rep  { font-size: 10px; color: #555; font-style: italic; }
        .cert-doc-header-text .univ { font-size: 13px; font-weight: bold; color: #1f2937; }
        .cert-doc-header-text .loc  { font-size: 10px; color: #555; }
        .cert-doc-title { text-align: center; margin: 18px 0 4px; }
        .cert-doc-title .main-t { font-size: 20px; font-weight: bold; color: #7f1d1d; letter-spacing: 1px; }
        .cert-doc-title .sub-t  { font-size: 12px; font-weight: bold; color: #7f1d1d; letter-spacing: 3px; margin-top: 2px; }
        .cert-doc-body { font-size: 13px; line-height: 2.4; color: #1f2937; margin-top: 16px; }
        .cert-doc-uline {
            display: inline-block; border-bottom: 1.5px solid #1f2937;
            min-width: 180px; text-align: center;
            font-weight: bold; color: #7f1d1d; padding: 0 4px;
        }
        .cert-doc-uline.full { display: block; width: 100%; margin: 4px 0; }
        .cert-doc-sig { margin-top: 42px; text-align: center; }
        .cert-doc-sig-line { border-top: 2px solid #1f2937; width: 240px; margin: 0 auto; padding-top: 6px; font-weight: bold; font-size: 13px; color: #1f2937; }
        .cert-doc-sig-detail { font-size: 11px; color: #555; margin-top: 3px; }
        .cert-doc-ref { font-size: 10px; color: #aaa; margin-top: 18px; text-align: right; border-top: 1px solid #e5e7eb; padding-top: 6px; }
        /* modal footer */
        .modal-footer-bar {
            padding: 16px 28px; background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            display: flex; justify-content: flex-end; gap: 10px; flex-shrink: 0;
        }
        .btn-mclose {
            padding: 10px 22px; border-radius: 8px; border: none;
            background: #e5e7eb; color: #1f2937;
            font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 600;
            cursor: pointer; transition: background 0.2s;
        }
        .btn-mclose:hover { background: #d1d5db; }
        .btn-mdl {
            padding: 10px 22px; border-radius: 8px; border: none;
            background: #7f1d1d; color: white;
            font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 600;
            cursor: pointer; transition: all 0.2s;
            display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-mdl:hover { background: #b91c1c; transform: translateY(-1px); }
        .btn-mdl:disabled { background: #9ca3af; cursor: not-allowed; transform: none; }
        .dl-spin { width: 15px; height: 15px; border: 2px solid rgba(255,255,255,0.4); border-top-color: white; border-radius: 50%; animation: dspin 0.7s linear infinite; display: none; }
        @keyframes dspin { to{ transform: rotate(360deg); } }
        .btn-action { display: inline-flex; align-items: center; gap: 5px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h1>Student Portal</h1>
            <p>Medical Services</p>
        </div>
        
        <nav class="sidebar-nav">
            <a href="book_appointment.php" class="nav-item" style="text-decoration: none; color: inherit;">
                <i class="bi bi-calendar-plus"></i>
                <span>Book Appointment</span>
            </a>
            <a href="medical_history.php" class="nav-item" style="text-decoration: none; color: inherit;">
                <i class="bi bi-clock-history"></i>
                <span>Medical History</span>
            </a>
            <a href="certificates.php" class="nav-item active" style="text-decoration: none; color: inherit;">
                <i class="bi bi-file-earmark-medical"></i>
                <span>Certificates</span>
            </a>
            <a href="profile.php" class="nav-item" style="text-decoration: none; color: inherit;">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="chatbot-toggle" id="chatbotToggle">🤖</div>
        </div>
    </div>

    <!-- Chatbot Container -->
    <div class="chatbot-container" id="chatbotContainer">
        <div class="chatbot-header">
            <h3><i class="bi bi-robot"></i> Medical Assistant</h3>
            <button class="chatbot-close" id="chatbotClose">&times;</button>
        </div>
        <div class="quick-actions">
            <button class="quick-action-btn" data-message="Available certificates">📄 My Certificates</button>
            <button class="quick-action-btn" data-message="How to download">💾 Download Help</button>
            <button class="quick-action-btn" data-message="Request new certificate">✨ Request New</button>
        </div>
        <div class="chatbot-messages" id="chatbotMessages">
            <div class="message bot">
                <div class="message-avatar">🤖</div>
                <div class="message-content">
                    Hello! I can help you with your medical certificates. What do you need?
                </div>
            </div>
        </div>
        <div class="chatbot-input-area">
            <div class="chatbot-input-wrapper">
                <input 
                    type="text" 
                    class="chatbot-input" 
                    id="chatbotInput" 
                    placeholder="Type your message..."
                    autocomplete="off"
                >
                <button class="chatbot-send" id="chatbotSend">
                    <i class="bi bi-send-fill"></i>
                </button>
            </div>
        </div>
    </div>

    <main class="main-content">
        <div class="page-header">
            <h2>Medical Certificates</h2>
            <p>Download and view your medical certificates</p>
        </div>

        <div class="certificates-section">
            <div class="section-header">
                Available Certificates
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Certificate Type</th>
                        <th>Issue Date</th>
                        <th>Valid Until</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Medical Clearance</td>
                        <td>March 18, 2024</td>
                        <td>June 18, 2024</td>
                        <td><span class="status-badge status-valid">Valid</span></td>
                        <td>
                            <button class="btn-action btn-view" onclick="viewCert(0, this)"><i class="bi bi-eye me-1"></i>View</button>
                            <button class="btn-action btn-download" onclick="downloadCert(0, this)"><i class="bi bi-download me-1"></i>Download PDF</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Fitness Certificate</td>
                        <td>March 20, 2024</td>
                        <td>June 20, 2024</td>
                        <td><span class="status-badge status-valid">Valid</span></td>
                        <td>
                            <button class="btn-action btn-view" onclick="viewCert(1, this)"><i class="bi bi-eye me-1"></i>View</button>
                            <button class="btn-action btn-download" onclick="downloadCert(1, this)"><i class="bi bi-download me-1"></i>Download PDF</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    <!-- ── View Certificate Modal ── -->
    <div class="modal-overlay" id="certModal">
        <div class="modal-box">
            <div class="modal-head">
                <div>
                    <div class="modal-head-title" id="modal-cert-type">Medical Clearance</div>
                    <div class="modal-head-sub">PUP Medical Services Department</div>
                </div>
                <button class="modal-close-btn" onclick="closeCertModal()">&times;</button>
            </div>
            <div class="modal-scroll">
                <div class="cert-document" id="cert-modal-preview"></div>
            </div>
            <div class="modal-footer-bar">
                <button class="btn-mclose" onclick="closeCertModal()"><i class="bi bi-x-circle me-1"></i> Close</button>
                <button class="btn-mdl" id="modal-dl-btn" onclick="downloadCurrentCert(this)">
                    <div class="dl-spin" id="modal-dl-spin"></div>
                    <i class="bi bi-download" id="modal-dl-icon"></i> Download PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Hidden render target for PDF -->
    <div id="pdf-render" style="position:fixed;left:-9999px;top:0;width:680px;background:white;padding:44px 52px;font-family:'Times New Roman',serif;z-index:-1;"></div>

    </main>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    
    <script>

        // ── Certificate data ──
        const certData = [
            {
                type: 'Medical Clearance',
                filename: 'Medical_Clearance',
                date: 'March 18, 2024',
                name: 'DELA CRUZ, JUAN PEDRO M.',
                purpose: 'Off-campus OJT / Immersion Activity (AY 2023-2024, 2nd Semester)',
                physician: 'FELICITAS A. BERMUDEZ',
                license: '0115224'
            },
            {
                type: 'Fitness Certificate',
                filename: 'Fitness_Certificate',
                date: 'March 20, 2024',
                name: 'DELA CRUZ, JUAN PEDRO M.',
                purpose: 'Sports Event / Intramurals Participation',
                physician: 'FELICITAS A. BERMUDEZ',
                license: '0115224'
            }
        ];
        let currentCertIdx = 0;

        function buildCertPreviewHTML(c) {
            return `
            <div class="cert-doc-header">
                <div class="cert-doc-logo">PUP</div>
                <div class="cert-doc-header-text">
                    <div class="rep">Republic of the Philippines</div>
                    <div class="univ">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</div>
                    <div class="loc">Manila</div>
                </div>
            </div>
            <div class="cert-doc-title">
                <div class="main-t">Medical CLEARANCE</div>
                <div class="sub-t">PHYSICALLY FIT</div>
            </div>
            <div class="cert-doc-body">
                <p>Date &nbsp;<span class="cert-doc-uline" style="min-width:180px;">${c.date}</span></p>
                <br>
                <p>To Whom It May Concern:</p>
                <br>
                <p>This is to certify that</p>
                <div class="cert-doc-uline full">${c.name}</div>
                <p>has been examined by the undersigned and found to be <strong>physically fit</strong> at the time of examination.</p>
                <br>
                <p>This certification is issued upon his/her request for</p>
                <div class="cert-doc-uline full">${c.purpose}</div>
                <div class="cert-doc-uline full" style="min-height:22px;">&nbsp;</div>
                <p>purpose but not for medico-legal reason.</p>
            </div>
            <div class="cert-doc-sig">
                <div class="cert-doc-sig-line">${c.physician}, M.D.</div>
                <div class="cert-doc-sig-detail">Lic No. ${c.license}</div>
            </div>
            <div class="cert-doc-ref">Medical 03 · Rev 1 · PUP-LAFO-6-MEDS · Medical Services Department</div>`;
        }

        function buildCertPDFHTML(c) {
            return `<div style="font-family:'Times New Roman',serif;background:white;padding:0;color:#1f2937;">
                <div style="display:flex;align-items:center;gap:14px;margin-bottom:12px;padding-bottom:12px;border-bottom:2.5px solid #7f1d1d;">
                    <div style="width:60px;height:60px;background:#7f1d1d;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:18px;font-weight:700;flex-shrink:0;font-family:Arial,sans-serif;">PUP</div>
                    <div style="flex:1;text-align:center;">
                        <div style="font-size:10px;color:#555;font-style:italic;">Republic of the Philippines</div>
                        <div style="font-size:13px;font-weight:bold;color:#1f2937;">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</div>
                        <div style="font-size:10px;color:#555;">Manila</div>
                    </div>
                </div>
                <div style="text-align:center;margin:18px 0 4px;">
                    <div style="font-size:22px;font-weight:bold;color:#7f1d1d;letter-spacing:1px;">Medical CLEARANCE</div>
                    <div style="font-size:12px;font-weight:bold;color:#7f1d1d;letter-spacing:3px;margin-top:3px;">PHYSICALLY FIT</div>
                </div>
                <div style="font-size:13px;line-height:2.4;color:#1f2937;margin-top:18px;">
                    <p>Date &nbsp;<span style="display:inline-block;border-bottom:1.5px solid #1f2937;min-width:180px;text-align:center;font-weight:bold;color:#7f1d1d;padding:0 4px;">${c.date}</span></p>
                    <br><p>To Whom It May Concern:</p><br>
                    <p>This is to certify that</p>
                    <div style="display:block;width:100%;border-bottom:1.5px solid #1f2937;text-align:center;font-weight:bold;color:#7f1d1d;padding:2px 4px;margin:6px 0;">${c.name}</div>
                    <p>has been examined by the undersigned and found to be <strong>physically fit</strong> at the time of examination.</p>
                    <br><p>This certification is issued upon his/her request for</p>
                    <div style="display:block;width:100%;border-bottom:1.5px solid #1f2937;text-align:center;font-weight:bold;color:#7f1d1d;padding:2px 4px;margin:6px 0;">${c.purpose}</div>
                    <div style="display:block;width:100%;border-bottom:1.5px solid #1f2937;min-height:22px;margin:0 0 4px;">&nbsp;</div>
                    <p>purpose but not for medico-legal reason.</p>
                </div>
                <div style="margin-top:50px;text-align:center;">
                    <div style="border-top:2px solid #1f2937;width:240px;margin:0 auto;padding-top:7px;font-weight:bold;font-size:13px;color:#1f2937;">${c.physician}, M.D.</div>
                    <div style="font-size:11px;color:#555;margin-top:3px;">Lic No. ${c.license}</div>
                </div>
                <div style="font-size:10px;color:#aaa;margin-top:22px;text-align:right;border-top:1px solid #e5e7eb;padding-top:6px;">Medical 03 · Rev 1 · PUP-LAFO-6-MEDS · Medical Services Department</div>
            </div>`;
        }

        function viewCert(idx) {
            currentCertIdx = idx;
            const c = certData[idx];
            document.getElementById('modal-cert-type').textContent = c.type;
            document.getElementById('cert-modal-preview').innerHTML = buildCertPreviewHTML(c);
            document.getElementById('certModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeCertModal() {
            document.getElementById('certModal').classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('certModal').addEventListener('click', function(e) {
                if (e.target === this) closeCertModal();
            });
        });

        async function runPDFDownload(idx, spinEl, iconEl, btn) {
            const c = certData[idx];
            btn.disabled = true;
            spinEl.style.display = 'block';
            iconEl.style.display = 'none';
            try {
                const target = document.getElementById('pdf-render');
                target.innerHTML = buildCertPDFHTML(c);
                await new Promise(r => setTimeout(r, 320));
                const canvas = await html2canvas(target, { scale: 2, useCORS: true, backgroundColor: '#ffffff', logging: false });
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });
                const pw = pdf.internal.pageSize.getWidth();
                const m  = 14;
                const iw = pw - m * 2;
                const ih = (canvas.height * iw) / canvas.width;
                pdf.addImage(canvas.toDataURL('image/jpeg', 0.96), 'JPEG', m, m, iw, Math.min(ih, pdf.internal.pageSize.getHeight() - m * 2));
                pdf.save(c.filename + '.pdf');
            } catch(err) {
                alert('PDF generation failed. Please try again.');
                console.error(err);
            } finally {
                btn.disabled = false;
                spinEl.style.display = 'none';
                iconEl.style.display = '';
                document.getElementById('pdf-render').innerHTML = '';
            }
        }

        function downloadCert(idx, btn) {
            const spin = btn.querySelector('.dl-spin') || document.createElement('div');
            const icon = btn.querySelector('i');
            runPDFDownload(idx, spin, icon, btn);
        }

        function downloadCurrentCert(btn) {
            runPDFDownload(currentCertIdx,
                document.getElementById('modal-dl-spin'),
                document.getElementById('modal-dl-icon'),
                btn);
        }

        // ── Navigation ──

        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.nav-item').forEach(nav => {
                    nav.classList.remove('active');
                });
                this.classList.add('active');
            });
        });

        // Chatbot functionality
        const chatbotToggle = document.getElementById('chatbotToggle');
        const chatbotContainer = document.getElementById('chatbotContainer');
        const chatbotClose = document.getElementById('chatbotClose');
        const chatbotMessages = document.getElementById('chatbotMessages');
        const chatbotInput = document.getElementById('chatbotInput');
        const chatbotSend = document.getElementById('chatbotSend');

        // Toggle chatbot
        chatbotToggle.addEventListener('click', () => {
            chatbotContainer.classList.toggle('show');
        });

        chatbotClose.addEventListener('click', () => {
            chatbotContainer.classList.remove('show');
        });

        // Quick action buttons
        document.querySelectorAll('.quick-action-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const message = btn.getAttribute('data-message');
                sendMessage(message);
            });
        });

        // Send message function
        function sendMessage(messageText = null) {
            const text = messageText || chatbotInput.value.trim();
            
            if (!text) return;

            // Add user message
            addMessage(text, 'user');
            chatbotInput.value = '';

            // Show typing indicator
            showTypingIndicator();

            // Simulate bot response
            setTimeout(() => {
                hideTypingIndicator();
                const response = getBotResponse(text);
                addMessage(response, 'bot');
            }, 1000 + Math.random() * 1000);
        }

        // Add message to chat
        function addMessage(text, sender) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${sender}`;
            
            const avatar = document.createElement('div');
            avatar.className = 'message-avatar';
            avatar.textContent = sender === 'bot' ? '🤖' : '👤';
            
            const content = document.createElement('div');
            content.className = 'message-content';
            content.textContent = text;
            
            messageDiv.appendChild(avatar);
            messageDiv.appendChild(content);
            chatbotMessages.appendChild(messageDiv);
            
            // Scroll to bottom
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
        }

        // Typing indicator
        function showTypingIndicator() {
            const typingDiv = document.createElement('div');
            typingDiv.className = 'message bot';
            typingDiv.id = 'typingIndicator';
            
            const avatar = document.createElement('div');
            avatar.className = 'message-avatar';
            avatar.textContent = '🤖';
            
            const indicator = document.createElement('div');
            indicator.className = 'message-content typing-indicator';
            indicator.innerHTML = '<span></span><span></span><span></span>';
            
            typingDiv.appendChild(avatar);
            typingDiv.appendChild(indicator);
            chatbotMessages.appendChild(typingDiv);
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
        }

        function hideTypingIndicator() {
            const indicator = document.getElementById('typingIndicator');
            if (indicator) indicator.remove();
        }

        // Bot responses for certificates
        function getBotResponse(message) {
            const lowerMessage = message.toLowerCase();
            
            if (lowerMessage.includes('certificate') || lowerMessage.includes('available')) {
                return "You have 2 available certificates:\n• Medical Clearance - valid until June 18, 2024\n• Fitness Certificate - valid until June 20, 2024\n\nBoth are currently valid and ready to download!";
            } else if (lowerMessage.includes('download') || lowerMessage.includes('pdf')) {
                return "To download a certificate:\n1. Click the 'Download PDF' button next to the certificate\n2. The file will be saved to your device\n3. You can print or share it as needed\n\nAll certificates are in PDF format for easy use.";
            } else if (lowerMessage.includes('request') || lowerMessage.includes('new') || lowerMessage.includes('apply')) {
                return "To request a new certificate:\n1. Book an appointment for Medical Clearance\n2. Visit the clinic for examination\n3. Your certificate will be available here after approval\n\nWould you like to book an appointment?";
            } else if (lowerMessage.includes('valid') || lowerMessage.includes('expire') || lowerMessage.includes('expiry')) {
                return "Certificate validity:\n• Medical Clearance: Valid until June 18, 2024\n• Fitness Certificate: Valid until June 20, 2024\n\nYou'll be notified before they expire so you can renew them.";
            } else if (lowerMessage.includes('view') || lowerMessage.includes('see') || lowerMessage.includes('check')) {
                return "Click the 'View' button next to any certificate to preview it before downloading. You can see all details including issue date, validity, and official stamps.";
            } else if (lowerMessage.includes('print') || lowerMessage.includes('physical')) {
                return "After downloading your certificate as PDF, you can print it at any printer. Make sure to print in color for the best quality and official appearance.";
            } else if (lowerMessage.includes('hello') || lowerMessage.includes('hi')) {
                return "Hello! I'm here to help you with your medical certificates. What would you like to know?";
            } else if (lowerMessage.includes('help')) {
                return "I can help you with:\n• Viewing available certificates\n• Downloading certificates as PDF\n• Requesting new certificates\n• Checking certificate validity\n\nWhat do you need help with?";
            } else {
                return "I can help you view, download, and manage your medical certificates. You have 2 certificates available. Would you like to know more about them?";
            }
        }

        // Event listeners for chatbot
        chatbotSend.addEventListener('click', () => sendMessage());
        
        chatbotInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    </script>
</body>
</html>