<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - Book Appointment</title>
    
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

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-body);
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: 275px;
            background: linear-gradient(180deg, #860303 3%, #B21414 79%, #940000 97%);
            color: white;
            position: fixed;
            height: 100vh;
            left: 0; top: 0;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar-header {
            padding: 35px 25px;
            background: linear-gradient(180deg, #860303 0%, #6B0000 100%);
        }
        .sidebar-header h1 { font-size: 28px; font-weight: 700; margin-bottom: 5px; letter-spacing: -0.5px; }
        .sidebar-header p  { font-size: 14px; font-weight: 400; opacity: 0.9; margin-bottom: 0; }
        .sidebar-nav { flex: 1; padding-top: 20px; }
        .nav-item {
            padding: 16px 32px; cursor: pointer; transition: all .3s;
            font-size: 15px; font-weight: 500;
            border-left: 4px solid transparent;
            display: flex; align-items: center; gap: 12px;
            color: rgba(255,255,255,0.9);
        }
        .nav-item i { font-size: 18px; }
        .nav-item:hover { background: rgba(255,255,255,0.1); color: white; }
        .nav-item.active { background: rgba(0,0,0,0.2); border-left-color: white; color: white; }
        .sidebar-footer { padding: 20px; display: flex; justify-content: center; }
        .chatbot-toggle {
            width: 60px; height: 60px; background: white; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 28px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            cursor: pointer; transition: transform .3s;
        }
        .chatbot-toggle:hover { transform: scale(1.1); }

        /* ── Chatbot ── */
        .chatbot-container {
            position: fixed; bottom: 20px; left: 295px;
            width: 380px; height: 550px;
            background: white; border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            display: none; flex-direction: column;
            z-index: 1000; overflow: hidden;
        }
        .chatbot-container.show { display: flex; }
        .chatbot-header {
            background: linear-gradient(90deg, var(--primary-gradient-start), var(--primary-gradient-end));
            color: white; padding: 20px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .chatbot-header h3 { margin:0; font-size:18px; font-weight:600; display:flex; align-items:center; gap:10px; }
        .chatbot-close {
            background:none; border:none; color:white; font-size:24px; cursor:pointer;
            width:30px; height:30px; display:flex; align-items:center; justify-content:center;
            border-radius:50%; transition:background .3s;
        }
        .chatbot-close:hover { background: rgba(255,255,255,0.2); }
        .chatbot-messages { flex:1; padding:20px; overflow-y:auto; background:var(--bg-body); }
        .message { margin-bottom:16px; display:flex; gap:10px; animation: slideIn .3s ease; }
        @keyframes slideIn { from {opacity:0;transform:translateY(10px)} to {opacity:1;transform:translateY(0)} }
        .message.user { flex-direction: row-reverse; }
        .message-avatar { width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0; }
        .message.bot .message-avatar { background: var(--primary-soft); }
        .message.user .message-avatar { background: var(--primary-color); color:white; }
        .message-content { max-width:70%; padding:12px 16px; border-radius:12px; font-size:14px; line-height:1.5; }
        .message.bot .message-content { background:white; color:var(--text-dark); border-bottom-left-radius:4px; }
        .message.user .message-content { background:var(--primary-color); color:white; border-bottom-right-radius:4px; }
        .chatbot-input-area { padding:16px; background:white; border-top:1px solid #e5e7eb; }
        .chatbot-input-wrapper { display:flex; gap:10px; }
        .chatbot-input { flex:1; padding:12px 16px; border:2px solid #e5e7eb; border-radius:24px; font-size:14px; font-family:'Poppins',sans-serif; outline:none; transition:border-color .3s; }
        .chatbot-input:focus { border-color:var(--primary-color); }
        .chatbot-send { width:44px; height:44px; background:var(--primary-color); color:white; border:none; border-radius:50%; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:18px; transition:background .3s; }
        .chatbot-send:hover { background:var(--primary-light); }
        .typing-indicator { display:flex; gap:4px; padding:12px 16px; }
        .typing-indicator span { width:8px; height:8px; background:var(--text-gray); border-radius:50%; animation:typing 1.4s infinite; }
        .typing-indicator span:nth-child(2) { animation-delay:.2s; }
        .typing-indicator span:nth-child(3) { animation-delay:.4s; }
        @keyframes typing { 0%,60%,100% {transform:translateY(0);opacity:.7} 30% {transform:translateY(-10px);opacity:1} }
        .quick-actions { padding:12px 20px; display:flex; gap:8px; flex-wrap:wrap; background:var(--bg-body); }
        .quick-action-btn { padding:8px 16px; background:white; border:2px solid var(--primary-color); color:var(--primary-color); border-radius:20px; font-size:12px; font-weight:500; cursor:pointer; transition:all .3s; font-family:'Poppins',sans-serif; }
        .quick-action-btn:hover { background:var(--primary-color); color:white; }

        /* ── Main ── */
        .main-content { margin-left:275px; padding:40px 50px; }
        .page-header { margin-bottom:40px; }
        .page-header h2 { color:var(--primary-color); font-size:36px; font-weight:700; margin-bottom:8px; }
        .page-header p { color:var(--text-gray); font-size:16px; }
        .booking-section { background:white; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,0.06); overflow:hidden; margin-bottom:25px; }
        .section-header {
            background: linear-gradient(90deg, var(--primary-gradient-start), var(--primary-gradient-end));
            color:white; padding:20px 30px; font-size:18px; font-weight:600;
            display:flex; align-items:center; gap:12px;
        }
        .section-header i { font-size:20px; cursor:pointer; }
        .section-content { padding:35px; }
        .requirements-list { list-style:none; padding:0; }
        .requirements-list li { color:var(--text-gray); font-size:15px; font-weight:500; margin-bottom:10px; padding-left:20px; position:relative; }
        .requirements-list li::before { content:"•"; position:absolute; left:0; color:var(--primary-color); font-weight:700; font-size:20px; }
        .category-item { border-bottom:1px solid #e5e7eb; padding:25px 0; display:flex; justify-content:space-between; align-items:center; }
        .category-item:last-child { border-bottom:none; }
        .category-info h4 { color:var(--text-dark); font-size:16px; font-weight:600; margin-bottom:5px; }
        .category-info p { color:var(--text-gray); font-size:14px; margin:0; }
        .btn-select { background-color:var(--primary-color); color:white; padding:10px 30px; border-radius:6px; font-size:14px; font-weight:600; border:none; cursor:pointer; transition:all .3s; }
        .btn-select:hover { background-color:var(--primary-light); }
        .nurse-card { border-bottom:1px solid #e5e7eb; padding:25px 0; display:flex; justify-content:space-between; align-items:flex-start; }
        .nurse-card:last-child { border-bottom:none; }
        .nurse-info h4 { color:var(--text-dark); font-size:16px; font-weight:600; margin-bottom:8px; }
        .nurse-info p { color:var(--text-gray); font-size:14px; margin:3px 0; }
        .calendar-container { display:grid; grid-template-columns:2fr 1fr; gap:40px; }
        .calendar-controls { display:flex; justify-content:center; align-items:center; gap:20px; margin-bottom:30px; }
        .calendar-controls button { background:none; border:none; color:var(--text-dark); font-size:20px; cursor:pointer; padding:5px 10px; }
        .calendar-controls select { padding:8px 15px; border:1px solid #e5e7eb; border-radius:6px; font-size:14px; font-weight:500; color:var(--text-dark); cursor:pointer; }
        .calendar-grid { display:grid; grid-template-columns:repeat(7,1fr); gap:10px; margin-bottom:20px; }
        .calendar-day-header { text-align:center; color:var(--text-gray); font-size:13px; font-weight:600; padding:10px; }
        .calendar-day { aspect-ratio:1; display:flex; align-items:center; justify-content:center; border-radius:8px; font-size:14px; font-weight:500; color:var(--text-dark); cursor:pointer; transition:all .3s; }
        .calendar-day:hover { background-color:var(--primary-soft); }
        .calendar-day.selected { background-color:var(--primary-color); color:white; }
        .calendar-day.disabled { color:#d1d5db; cursor:not-allowed; pointer-events:none; }
        .time-slots { display:flex; flex-direction:column; gap:15px; }
        .time-slot-header { color:var(--text-dark); font-size:16px; font-weight:600; margin-bottom:5px; }
        .time-slot-subheader { color:var(--text-gray); font-size:13px; margin-bottom:15px; }
        .time-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; }
        .time-slot { background-color:var(--primary-color); color:white; padding:15px; border-radius:8px; text-align:center; font-size:14px; font-weight:600; cursor:pointer; transition:all .3s; }
        .time-slot:hover { background-color:var(--primary-light); transform:translateY(-2px); }
        .time-slot.selected-slot { background-color: #450a0a; box-shadow: 0 0 0 3px #fca5a5; }

        /* ── Step 4 form ── */
        .form-group { margin-bottom:25px; }
        .form-group label { display:block; color:var(--text-dark); font-size:14px; font-weight:600; margin-bottom:8px; }
        .form-group input, .form-group textarea {
            width:100%; padding:12px 16px; border:1px solid #e5e7eb; border-radius:8px;
            font-size:14px; font-family:'Poppins',sans-serif; color:var(--text-dark); transition:border-color .3s;
        }
        .form-group input:focus, .form-group textarea:focus { outline:none; border-color:var(--primary-color); }
        .form-group textarea { resize:vertical; min-height:120px; }

        /* Auto-filled badge */
        .autofill-badge {
            display: inline-flex; align-items: center; gap: 5px;
            background: #dcfce7; color: #15803d;
            font-size: 11px; font-weight: 600;
            padding: 3px 8px; border-radius: 20px;
            margin-left: 8px;
        }
        .field-wrapper { position: relative; }
        .field-wrapper .autofill-icon {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            color: #15803d; font-size: 16px;
        }
        .field-wrapper input { padding-right: 40px; background: #f0fdf4; border-color: #bbf7d0; }
        .field-wrapper input:focus { border-color: #15803d; background: white; }

        .form-row { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; }
        .checkbox-group { display:flex; align-items:flex-start; gap:10px; margin:25px 0; }
        .checkbox-group input[type="checkbox"] { margin-top:3px; width:18px; height:18px; cursor:pointer; }
        .checkbox-group label { color:var(--text-gray); font-size:13px; line-height:1.5; margin:0; }
        .btn-submit { background-color:var(--primary-color); color:white; padding:14px 40px; border-radius:8px; font-size:15px; font-weight:600; border:none; cursor:pointer; transition:all .3s; float:right; margin-bottom:30px; }
        .btn-submit:hover { background-color:var(--primary-light); }

        /* ── Appointment Summary Banner ── */
        .summary-banner {
            background: linear-gradient(135deg, #fef2f2, #fff7ed);
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 20px 25px;
            margin-bottom: 28px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .summary-item { display: flex; align-items: center; gap: 10px; }
        .summary-item i { color: var(--primary-color); font-size: 18px; }
        .summary-item .label { font-size: 11px; font-weight: 600; color: var(--text-gray); text-transform: uppercase; letter-spacing: 0.5px; }
        .summary-item .value { font-size: 14px; font-weight: 600; color: var(--text-dark); }

        /* ── Reason hint chip ── */
        .reason-hint {
            display: flex; gap: 8px; flex-wrap: wrap; margin-top: 10px;
        }
        .reason-chip {
            padding: 5px 12px; background: var(--primary-soft); border: 1px solid #fecaca;
            color: var(--primary-color); border-radius: 20px; font-size: 12px; font-weight: 500;
            cursor: pointer; transition: all .2s;
        }
        .reason-chip:hover { background: var(--primary-color); color: white; }

        /* ── Step 5: Confirmation ── */
        .confirmation-card {
            text-align: center; padding: 50px 30px;
        }
        .confirmation-icon {
            width: 80px; height: 80px; background: #dcfce7;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 40px; margin: 0 auto 24px;
            animation: popIn .5s cubic-bezier(0.175,0.885,0.32,1.275);
        }
        @keyframes popIn { from {transform:scale(0)} to {transform:scale(1)} }
        .confirmation-card h3 { font-size: 26px; font-weight: 700; color: var(--text-dark); margin-bottom: 10px; }
        .confirmation-card p { color: var(--text-gray); font-size: 15px; margin-bottom: 30px; }
        .confirmation-details {
            background: var(--bg-body); border-radius: 10px; padding: 24px;
            text-align: left; max-width: 500px; margin: 0 auto 30px;
        }
        .confirmation-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
        .confirmation-row:last-child { border-bottom: none; }
        .confirmation-row .conf-label { color: var(--text-gray); font-weight: 500; }
        .confirmation-row .conf-value { color: var(--text-dark); font-weight: 600; }
        .btn-dashboard { background: var(--primary-color); color: white; padding: 12px 32px; border-radius: 8px; font-size: 15px; font-weight: 600; border: none; cursor: pointer; transition: all .3s; text-decoration: none; display: inline-block; }
        .btn-dashboard:hover { background: var(--primary-light); }

        .hidden { display: none !important; }

        @media (max-width:1200px) { .calendar-container { grid-template-columns:1fr; } .form-row { grid-template-columns:1fr; } }
        @media (max-width:768px) { .sidebar{width:200px} .main-content{margin-left:200px;padding:30px 20px} .time-grid{grid-template-columns:repeat(2,1fr)} }
    </style>
</head>
<body>

<!-- ═══════════════════════════════ SIDEBAR ═══════════════════════════════ -->
<div class="sidebar">
    <div class="sidebar-header">
        <h1>Student Portal</h1>
        <p>Medical Services</p>
    </div>
    <nav class="sidebar-nav">
        <a href="student_dashboard.php" class="nav-item" style="text-decoration:none;color:inherit;"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a>
        <a href="book_appointment.php" class="nav-item active" style="text-decoration:none;color:inherit;"><i class="bi bi-calendar-plus"></i><span>Book Appointment</span></a>
        <a href="medical_history.php" class="nav-item" style="text-decoration:none;color:inherit;"><i class="bi bi-clock-history"></i><span>Medical History</span></a>
        <a href="certificates.php" class="nav-item" style="text-decoration:none;color:inherit;"><i class="bi bi-file-earmark-medical"></i><span>Certificates</span></a>
        <a href="profile.php" class="nav-item" style="text-decoration:none;color:inherit;"><i class="bi bi-person"></i><span>Profile</span></a>
    </nav>
    <div class="sidebar-footer">
        <div class="chatbot-toggle" id="chatbotToggle">🤖</div>
    </div>
</div>

<!-- ═══════════════════════════════ CHATBOT ═══════════════════════════════ -->
<div class="chatbot-container" id="chatbotContainer">
    <div class="chatbot-header">
        <h3><i class="bi bi-robot"></i> Medical Assistant</h3>
        <button class="chatbot-close" id="chatbotClose">&times;</button>
    </div>
    <div class="quick-actions">
        <button class="quick-action-btn" data-message="Help with booking">📅 Booking Help</button>
        <button class="quick-action-btn" data-message="Available time slots">⏰ Time Slots</button>
        <button class="quick-action-btn" data-message="What documents do I need">📄 Requirements</button>
    </div>
    <div class="chatbot-messages" id="chatbotMessages">
        <div class="message bot">
            <div class="message-avatar">🤖</div>
            <div class="message-content">Hello! I'm here to help you book an appointment. What would you like to know?</div>
        </div>
    </div>
    <div class="chatbot-input-area">
        <div class="chatbot-input-wrapper">
            <input type="text" class="chatbot-input" id="chatbotInput" placeholder="Type your message..." autocomplete="off">
            <button class="chatbot-send" id="chatbotSend"><i class="bi bi-send-fill"></i></button>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════ MAIN ═══════════════════════════════ -->
<main class="main-content">
    <div class="page-header">
        <h2>Book Appointment</h2>
        <p>Schedule your medical consultation or clearance</p>
    </div>

    <!-- Booking Requirements -->
    <div class="booking-section">
        <div class="section-header">Booking Requirements</div>
        <div class="section-content">
            <ul class="requirements-list">
                <li>Valid Student ID</li>
                <li>Clear reason for appointment</li>
                <li>Complete student information</li>
                <li>Available appointment slot</li>
            </ul>
        </div>
    </div>

    <!-- ── STEP 1: Select Category ── -->
    <div class="booking-section" id="step1">
        <div class="section-header">Step 1 — Select Category</div>
        <div class="section-content">
            <div class="category-item">
                <div class="category-info">
                    <h4>General Consultation</h4>
                    <p>For common symptoms and check-ups.</p>
                </div>
                <button class="btn-select" onclick="selectCategory('General Consultation')">Select</button>
            </div>
            <div class="category-item">
                <div class="category-info">
                    <h4>First Aid / Injury Care</h4>
                    <p>For minor injuries or urgent non-emergency care.</p>
                </div>
                <button class="btn-select" onclick="selectCategory('First Aid / Injury Care')">Select</button>
            </div>
            <div class="category-item">
                <div class="category-info">
                    <h4>Medical Clearance</h4>
                    <p>For PE, school requirements, or activity forms.</p>
                </div>
                <button class="btn-select" onclick="selectCategory('Medical Clearance')">Select</button>
            </div>
            <div class="category-item">
                <div class="category-info">
                    <h4>Follow-Up Checkup</h4>
                    <p>For returning patients needing monitoring.</p>
                </div>
                <button class="btn-select" onclick="selectCategory('Follow-Up Checkup')">Select</button>
            </div>
            <div class="category-item">
                <div class="category-info">
                    <h4>Health Counseling</h4>
                    <p>For stress, hygiene, and wellness concerns.</p>
                </div>
                <button class="btn-select" onclick="selectCategory('Health Counseling')">Select</button>
            </div>
        </div>
    </div>

    <!-- ── STEP 2: Select Nurse ── -->
    <div class="booking-section hidden" id="step2">
        <div class="section-header">
            <i class="bi bi-arrow-left" onclick="goToStep(1)" title="Back"></i>
            Step 2 — Select Nurse
        </div>
        <div class="section-content">
            <div class="nurse-card">
                <div class="nurse-info">
                    <h4>(Nurse) Maria Dela Cruz <span style="font-weight:400;color:var(--text-gray);">· PUP iTech Clinic</span></h4>
                    <p><i class="bi bi-geo-alt" style="color:var(--primary-color)"></i> PUP Institute of Technology, Manila</p>
                    <p><i class="bi bi-person-badge" style="color:var(--primary-color)"></i> General Clinic Nurse</p>
                    <p style="margin-top:8px;"><i class="bi bi-clock" style="color:var(--primary-color)"></i> 8:00 AM – 5:00 PM · MON–FRI</p>
                </div>
                <button class="btn-select" onclick="selectNurse('Nurse Maria Dela Cruz', 'PUP iTech Clinic')">Select</button>
            </div>
            <div class="nurse-card">
                <div class="nurse-info">
                    <h4>(Nurse) Ana Santos <span style="font-weight:400;color:var(--text-gray);">· PUP Main Clinic</span></h4>
                    <p><i class="bi bi-geo-alt" style="color:var(--primary-color)"></i> PUP Main Campus, Manila</p>
                    <p><i class="bi bi-person-badge" style="color:var(--primary-color)"></i> Senior Clinic Nurse</p>
                    <p style="margin-top:8px;"><i class="bi bi-clock" style="color:var(--primary-color)"></i> 8:00 AM – 5:00 PM · MON–FRI</p>
                </div>
                <button class="btn-select" onclick="selectNurse('Nurse Ana Santos', 'PUP Main Clinic')">Select</button>
            </div>
            <div class="nurse-card">
                <div class="nurse-info">
                    <h4>(Nurse) Jose Reyes <span style="font-weight:400;color:var(--text-gray);">· PUP Annex Clinic</span></h4>
                    <p><i class="bi bi-geo-alt" style="color:var(--primary-color)"></i> PUP Annex Building, Manila</p>
                    <p><i class="bi bi-person-badge" style="color:var(--primary-color)"></i> General Clinic Nurse</p>
                    <p style="margin-top:8px;"><i class="bi bi-clock" style="color:var(--primary-color)"></i> 8:00 AM – 5:00 PM · MON–FRI</p>
                </div>
                <button class="btn-select" onclick="selectNurse('Nurse Jose Reyes', 'PUP Annex Clinic')">Select</button>
            </div>
        </div>
    </div>

    <!-- ── STEP 3: Calendar ── -->
    <div class="booking-section hidden" id="step3">
        <div class="section-header">
            <i class="bi bi-arrow-left" onclick="goToStep(2)" title="Back"></i>
            Step 3 — Pick a Date & Time
        </div>
        <div class="section-content">
            <div class="calendar-container">
                <div class="calendar-section">
                    <div class="calendar-controls">
                        <button><i class="bi bi-chevron-left"></i></button>
                        <select><option>November</option></select>
                        <select><option>2025</option></select>
                        <button><i class="bi bi-chevron-right"></i></button>
                    </div>
                    <div class="calendar-grid">
                        <div class="calendar-day-header">M</div><div class="calendar-day-header">T</div><div class="calendar-day-header">W</div>
                        <div class="calendar-day-header">T</div><div class="calendar-day-header">F</div><div class="calendar-day-header">S</div><div class="calendar-day-header">S</div>
                        <div class="calendar-day disabled"></div><div class="calendar-day disabled"></div><div class="calendar-day disabled"></div>
                        <div class="calendar-day disabled"></div><div class="calendar-day disabled">1</div><div class="calendar-day disabled">2</div><div class="calendar-day">3</div>
                        <div class="calendar-day">4</div><div class="calendar-day">5</div><div class="calendar-day">6</div><div class="calendar-day">7</div>
                        <div class="calendar-day">8</div><div class="calendar-day">9</div><div class="calendar-day">10</div>
                        <div class="calendar-day">11</div><div class="calendar-day">12</div><div class="calendar-day">13</div><div class="calendar-day">14</div>
                        <div class="calendar-day">15</div><div class="calendar-day">16</div><div class="calendar-day">17</div>
                        <div class="calendar-day">18</div><div class="calendar-day">19</div><div class="calendar-day">20</div><div class="calendar-day">21</div>
                        <div class="calendar-day">22</div><div class="calendar-day">23</div><div class="calendar-day">24</div><div class="calendar-day">25</div>
                        <div class="calendar-day selected" data-date="Wednesday, November 26">26</div>
                        <div class="calendar-day">27</div><div class="calendar-day">28</div><div class="calendar-day">29</div><div class="calendar-day">30</div>
                    </div>
                </div>
                <div class="time-slots">
                    <div class="time-slot-header" id="selectedDateLabel">Wednesday, November 26</div>
                    <div class="time-slot-subheader">Time Zone: Manila (GMT +08:00)</div>
                    <div class="time-grid">
                        <div class="time-slot" onclick="selectTimeSlot(this, '6:30 AM')">6:30 AM</div>
                        <div class="time-slot" onclick="selectTimeSlot(this, '7:20 AM')">7:20 AM</div>
                        <div class="time-slot" onclick="selectTimeSlot(this, '9:40 AM')">9:40 AM</div>
                        <div class="time-slot" onclick="selectTimeSlot(this, '10:20 AM')">10:20 AM</div>
                        <div class="time-slot" onclick="selectTimeSlot(this, '12:00 PM')">12:00 PM</div>
                        <div class="time-slot" onclick="selectTimeSlot(this, '3:00 PM')">3:00 PM</div>
                        <div class="time-slot" onclick="selectTimeSlot(this, '4:00 PM')">4:00 PM</div>
                        <div class="time-slot" onclick="selectTimeSlot(this, '4:40 PM')">4:40 PM</div>
                        <div class="time-slot" onclick="selectTimeSlot(this, '5:20 PM')">5:20 PM</div>
                    </div>
                    <button class="btn-submit" onclick="proceedToInfo()" style="margin-top:20px;">Continue</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ── STEP 4: Your Information (auto-filled) ── -->
    <div class="booking-section hidden" id="step4">
        <div class="section-header">
            <i class="bi bi-arrow-left" onclick="goToStep(3)" title="Back"></i>
            Step 4 — Your Information
        </div>
        <div class="section-content">

            <!-- Summary Banner -->
            <div class="summary-banner" id="summaryBanner">
                <div class="summary-item">
                    <i class="bi bi-tag-fill"></i>
                    <div><div class="label">Category</div><div class="value" id="bannerCategory">—</div></div>
                </div>
                <div class="summary-item">
                    <i class="bi bi-person-fill"></i>
                    <div><div class="label">Nurse</div><div class="value" id="bannerNurse">—</div></div>
                </div>
                <div class="summary-item">
                    <i class="bi bi-calendar-fill"></i>
                    <div><div class="label">Date</div><div class="value" id="bannerDate">—</div></div>
                </div>
                <div class="summary-item">
                    <i class="bi bi-clock-fill"></i>
                    <div><div class="label">Time</div><div class="value" id="bannerTime">—</div></div>
                </div>
            </div>

            <!-- Auto-fill notice -->
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:14px 18px;margin-bottom:24px;display:flex;align-items:center;gap:10px;">
                <i class="bi bi-check-circle-fill" style="color:#15803d;font-size:18px;"></i>
                <span style="font-size:13px;color:#166534;font-weight:500;">Your profile information has been auto-filled. Please review and update if needed.</span>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Full Name <span class="autofill-badge"><i class="bi bi-magic"></i> Auto-filled</span></label>
                    <div class="field-wrapper">
                        <input type="text" id="fieldName" placeholder="Juan Dela Cruz">
                        <i class="bi bi-check-circle-fill autofill-icon" id="iconName"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label>Phone <span class="autofill-badge"><i class="bi bi-magic"></i> Auto-filled</span></label>
                    <div class="field-wrapper">
                        <input type="tel" id="fieldPhone" placeholder="e.g., 00-000-0000">
                        <i class="bi bi-check-circle-fill autofill-icon" id="iconPhone"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label>Email <span class="autofill-badge"><i class="bi bi-magic"></i> Auto-filled</span></label>
                    <div class="field-wrapper">
                        <input type="email" id="fieldEmail" placeholder="juan@iskolarngbayan.pup.edu.ph">
                        <i class="bi bi-check-circle-fill autofill-icon" id="iconEmail"></i>
                    </div>
                </div>
            </div>

            <div class="form-group" style="max-width:400px;">
                <label>Student Number <span class="autofill-badge"><i class="bi bi-magic"></i> Auto-filled</span></label>
                <div class="field-wrapper">
                    <input type="text" id="fieldStudentNo" placeholder="e.g., 2021-12345-MN-0">
                    <i class="bi bi-check-circle-fill autofill-icon" id="iconStudentNo"></i>
                </div>
            </div>

            <div class="form-group">
                <label>Reason for Visit
                    <span style="color:var(--text-gray);font-weight:400;font-size:13px;margin-left:6px;">— pre-filled based on your selected category, feel free to edit</span>
                </label>
                <textarea id="fieldReason" placeholder="Please describe your symptoms or reason for the appointment"></textarea>
                <!-- Quick reason suggestion chips -->
                <div class="reason-hint" id="reasonHints"></div>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="terms">
                <label for="terms">By submitting this form, you confirm to have read and agree to iTechCare's Privacy Notice and Terms and Conditions, and give your explicit consent for the processing, use and sharing of your personal data as outlined in our Privacy Notice. *</label>
            </div>
            <button type="button" class="btn-submit" onclick="submitAppointment()">Submit Appointment</button>
        </div>
    </div>

    <!-- ── STEP 5: Confirmation ── -->
    <div class="booking-section hidden" id="step5">
        <div class="section-content">
            <div class="confirmation-card">
                <div class="confirmation-icon">✅</div>
                <h3>Appointment Confirmed!</h3>
                <p>Your appointment has been successfully submitted. A confirmation will be sent to your email.</p>
                <div class="confirmation-details">
                    <div class="confirmation-row"><span class="conf-label">Name</span><span class="conf-value" id="confName">—</span></div>
                    <div class="confirmation-row"><span class="conf-label">Student No.</span><span class="conf-value" id="confStudentNo">—</span></div>
                    <div class="confirmation-row"><span class="conf-label">Category</span><span class="conf-value" id="confCategory">—</span></div>
                    <div class="confirmation-row"><span class="conf-label">Nurse</span><span class="conf-value" id="confNurse">—</span></div>
                    <div class="confirmation-row"><span class="conf-label">Date & Time</span><span class="conf-value" id="confDateTime">—</span></div>
                    <div class="confirmation-row"><span class="conf-label">Reason</span><span class="conf-value" id="confReason" style="max-width:260px;text-align:right;">—</span></div>
                </div>
                <a href="student_dashboard.php" class="btn-dashboard">Go to Dashboard</a>
            </div>
        </div>
    </div>

</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script>

// ═══════════════════════════════════════════════
//  SIMULATED PROFILE DATA
//  In production, replace this object with data
//  injected by PHP from the session/database:
//    const studentProfile = <?= json_encode($studentData) ?>;
// ═══════════════════════════════════════════════
const studentProfile = {
    name:      'Juan Dela Cruz',
    studentNo: '2021-12345-MN-0',
    email:     'juan.delacruz@iskolarngbayan.pup.edu.ph',
    phone:     '+63 917 123 4567',
    program:   'Diploma in Information Technology',
    year:      '3rd Year'
};

// ── Category → default reason & suggestion chips ──
const categoryReasonMap = {
    'General Consultation': {
        default: 'I am experiencing general discomfort and would like a check-up.',
        chips: ['Headache / Dizziness', 'Fever / Cold', 'Stomach pain', 'Fatigue / Weakness']
    },
    'First Aid / Injury Care': {
        default: 'I sustained a minor injury and require first aid treatment.',
        chips: ['Wound / Cut', 'Sprain / Strain', 'Bruise / Swelling', 'Insect bite']
    },
    'Medical Clearance': {
        default: 'I need a medical clearance certificate for school/PE/OJT requirements.',
        chips: ['PE clearance', 'OJT / Internship clearance', 'School activity clearance', 'Sports event clearance']
    },
    'Follow-Up Checkup': {
        default: 'I am returning for a follow-up checkup on my previous condition.',
        chips: ['Post-medication follow-up', 'Wound monitoring', 'Chronic condition check', 'Lab result discussion']
    },
    'Health Counseling': {
        default: 'I would like to discuss concerns about my overall health and wellness.',
        chips: ['Academic stress', 'Sleep problems', 'Nutrition / Diet', 'Mental wellness']
    }
};

// ── Booking state ──
let state = {
    category:  '',
    nurse:     '',
    clinic:    '',
    date:      'Wednesday, November 26',
    time:      ''
};

// ── Step navigation ──
function goToStep(n) {
    [1,2,3,4,5].forEach(i => document.getElementById('step'+i).classList.add('hidden'));
    document.getElementById('step'+n).classList.remove('hidden');
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ── Step 1: Category selected ──
function selectCategory(cat) {
    state.category = cat;
    goToStep(2);
}

// ── Step 2: Nurse selected ──
function selectNurse(nurse, clinic) {
    state.nurse  = nurse;
    state.clinic = clinic;
    goToStep(3);
}

// ── Step 3: Time slot ──
function selectTimeSlot(el, time) {
    document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected-slot'));
    el.classList.add('selected-slot');
    state.time = time;
}

// ── Calendar date click ──
document.querySelectorAll('.calendar-day:not(.disabled)').forEach(day => {
    day.addEventListener('click', function() {
        document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('selected'));
        this.classList.add('selected');
        const label = this.dataset.date || 'November ' + this.textContent.trim() + ', 2025';
        state.date = label;
        document.getElementById('selectedDateLabel').textContent = label;
    });
});

// ── Proceed to Step 4: auto-fill + populate ──
function proceedToInfo() {
    if (!state.time) { alert('Please select a time slot before continuing.'); return; }

    // Update summary banner
    document.getElementById('bannerCategory').textContent = state.category;
    document.getElementById('bannerNurse').textContent    = state.nurse + ' · ' + state.clinic;
    document.getElementById('bannerDate').textContent     = state.date;
    document.getElementById('bannerTime').textContent     = state.time;

    // Auto-fill profile fields
    document.getElementById('fieldName').value      = studentProfile.name;
    document.getElementById('fieldPhone').value     = studentProfile.phone;
    document.getElementById('fieldEmail').value     = studentProfile.email;
    document.getElementById('fieldStudentNo').value = studentProfile.studentNo;

    // Pre-fill reason based on category
    const mapping = categoryReasonMap[state.category];
    if (mapping) {
        document.getElementById('fieldReason').value = mapping.default;
        renderReasonHints(mapping.chips);
    }

    goToStep(4);
}

// ── Reason hint chips ──
function renderReasonHints(chips) {
    const container = document.getElementById('reasonHints');
    container.innerHTML = '';
    chips.forEach(chip => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'reason-chip';
        btn.textContent = chip;
        btn.onclick = () => {
            const ta = document.getElementById('fieldReason');
            ta.value = chip + '. ' + ta.value.replace(/^.*?\.\s*/, '');
        };
        container.appendChild(btn);
    });
}

// ── Submit ──
function submitAppointment() {
    const name      = document.getElementById('fieldName').value.trim();
    const studentNo = document.getElementById('fieldStudentNo').value.trim();
    const email     = document.getElementById('fieldEmail').value.trim();
    const reason    = document.getElementById('fieldReason').value.trim();
    const agreed    = document.getElementById('terms').checked;

    if (!name || !studentNo || !email || !reason) {
        alert('Please fill in all required fields.'); return;
    }
    if (!agreed) {
        alert('Please agree to the Terms and Conditions to proceed.'); return;
    }

    // Populate confirmation card
    document.getElementById('confName').textContent      = name;
    document.getElementById('confStudentNo').textContent = studentNo;
    document.getElementById('confCategory').textContent  = state.category;
    document.getElementById('confNurse').textContent     = state.nurse + ' — ' + state.clinic;
    document.getElementById('confDateTime').textContent  = state.date + ' · ' + state.time;
    document.getElementById('confReason').textContent    = reason.length > 80 ? reason.slice(0,80) + '…' : reason;

    goToStep(5);
}

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
    setTimeout(() => { hideTypingIndicator(); addMessage(getBotResponse(text), 'bot'); }, 1000 + Math.random() * 800);
}

function addMessage(text, sender) {
    const div = document.createElement('div');
    div.className = 'message ' + sender;
    const av = document.createElement('div'); av.className='message-avatar'; av.textContent = sender==='bot'?'🤖':'👤';
    const ct = document.createElement('div'); ct.className='message-content'; ct.textContent = text;
    div.appendChild(av); div.appendChild(ct);
    chatbotMessages.appendChild(div);
    chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
}

function showTypingIndicator() {
    const d = document.createElement('div'); d.className='message bot'; d.id='typingIndicator';
    const av = document.createElement('div'); av.className='message-avatar'; av.textContent='🤖';
    const ind = document.createElement('div'); ind.className='message-content typing-indicator'; ind.innerHTML='<span></span><span></span><span></span>';
    d.appendChild(av); d.appendChild(ind); chatbotMessages.appendChild(d);
    chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
}
function hideTypingIndicator() { const el=document.getElementById('typingIndicator'); if(el) el.remove(); }

function getBotResponse(msg) {
    const m = msg.toLowerCase();
    if (m.includes('category')||m.includes('type'))    return 'We offer 5 categories: General Consultation, First Aid/Injury Care, Medical Clearance, Follow-Up Checkup, and Health Counseling. Each has a pre-filled reason to save you time!';
    if (m.includes('time')||m.includes('slot'))        return 'Slots run from 6:30 AM to 5:20 PM, Mon–Fri. Select your date on the calendar to see what\'s available.';
    if (m.includes('require')||m.includes('document')) return 'You need: valid Student ID, clear visit reason, complete student information, and an available slot. Your profile details are auto-filled!';
    if (m.includes('nurse')||m.includes('doctor'))     return 'Nurses are available at PUP iTech Clinic, PUP Main Clinic, and PUP Annex Clinic — 8 AM to 5 PM, Mon–Fri.';
    if (m.includes('cancel')||m.includes('reschedule'))return 'To cancel or reschedule, visit your Medical History page or contact the clinic directly.';
    if (m.includes('help')||m.includes('how'))         return 'Steps: 1) Choose a category → 2) Pick a nurse → 3) Select date & time → 4) Review your auto-filled info & submit. Easy!';
    if (m.includes('hello')||m.includes('hi'))         return 'Hello! I\'m here to help you book an appointment. What would you like to know?';
    if (m.includes('autofill')||m.includes('auto'))    return 'Yes! Your name, student number, email, and phone are auto-filled from your profile. Just review and edit if needed.';
    return 'I can help with booking steps, time slots, requirements, and clinic info. What do you need?';
}

chatbotSend.addEventListener('click', () => sendMessage());
chatbotInput.addEventListener('keypress', e => { if(e.key==='Enter') sendMessage(); });

</script>
</body>
</html>
