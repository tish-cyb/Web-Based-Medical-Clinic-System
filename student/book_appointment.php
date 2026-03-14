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
            z-index: 1000;
        }

        .sidebar-header {
            padding: 35px 25px;
            background: linear-gradient(180deg, #860303 0%, #6B0000 100%);
        }

        .sidebar-header h1 { font-size: 28px; font-weight: 700; margin-bottom: 5px; letter-spacing: -0.5px; }
        .sidebar-header p  { font-size: 14px; opacity: 0.9; margin-bottom: 0; }

        .sidebar-nav { flex: 1; padding-top: 20px; }

        .nav-item {
            padding: 16px 32px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 15px; font-weight: 500;
            border-left: 4px solid transparent;
            display: flex; align-items: center; gap: 12px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
        }

        .nav-item i { font-size: 18px; }
        .nav-item:hover { background-color: rgba(255,255,255,0.1); color: white; }
        .nav-item.active { background-color: rgba(0,0,0,0.2); border-left-color: white; color: white; }

        .sidebar-footer { padding: 20px; display: flex; justify-content: center; }

        .chatbot-toggle {
            width: 60px; height: 60px;
            background: white; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 28px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            cursor: pointer; transition: transform 0.3s ease;
        }
        .chatbot-toggle:hover { transform: scale(1.1); }

        /* ── Chatbot ── */
        .chatbot-container {
            position: fixed; bottom: 20px; left: 295px;
            width: 380px; height: 550px;
            background: white; border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            display: none; flex-direction: column;
            z-index: 1001; overflow: hidden;
        }
        .chatbot-container.show { display: flex; }

        .chatbot-header {
            background: linear-gradient(90deg, var(--primary-gradient-start), var(--primary-gradient-end));
            color: white; padding: 20px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .chatbot-header h3 { margin: 0; font-size: 18px; font-weight: 600; display: flex; align-items: center; gap: 10px; }
        .chatbot-close {
            background: none; border: none; color: white;
            font-size: 24px; cursor: pointer;
            width: 30px; height: 30px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            transition: background 0.3s;
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
        .message-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; flex-shrink: 0;
        }
        .message.bot .message-avatar { background: var(--primary-soft); }
        .message.user .message-avatar { background: var(--primary-color); color: white; }
        .message-content {
            max-width: 70%; padding: 12px 16px; border-radius: 12px;
            font-size: 14px; line-height: 1.5;
        }
        .message.bot .message-content  { background: white; color: var(--text-dark); border-bottom-left-radius: 4px; }
        .message.user .message-content { background: var(--primary-color); color: white; border-bottom-right-radius: 4px; }

        .chatbot-input-area { padding: 16px; background: white; border-top: 1px solid #e5e7eb; }
        .chatbot-input-wrapper { display: flex; gap: 10px; }
        .chatbot-input {
            flex: 1; padding: 12px 16px;
            border: 2px solid #e5e7eb; border-radius: 24px;
            font-size: 14px; font-family: 'Poppins', sans-serif;
            outline: none; transition: border-color 0.3s;
        }
        .chatbot-input:focus { border-color: var(--primary-color); }
        .chatbot-send {
            width: 44px; height: 44px; background: var(--primary-color);
            color: white; border: none; border-radius: 50%;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            font-size: 18px; transition: background 0.3s;
        }
        .chatbot-send:hover { background: var(--primary-light); }

        .typing-indicator { display: flex; gap: 4px; padding: 12px 16px; }
        .typing-indicator span {
            width: 8px; height: 8px; background: var(--text-gray);
            border-radius: 50%; animation: typing 1.4s infinite;
        }
        .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
        .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }
        @keyframes typing {
            0%, 60%, 100% { transform: translateY(0); opacity: 0.7; }
            30%            { transform: translateY(-10px); opacity: 1; }
        }

        /* ── Main ── */
        .main-content { margin-left: 275px; padding: 40px 50px; }

        .page-header { margin-bottom: 40px; }
        .page-header h2 { color: var(--primary-color); font-size: 36px; font-weight: 700; margin-bottom: 8px; }
        .page-header p  { color: var(--text-gray); font-size: 16px; }

        /* ── Card ── */
        .form-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            overflow: hidden;
        }

        .form-card-header {
            background: linear-gradient(90deg, var(--primary-gradient-start), var(--primary-gradient-end));
            color: white; padding: 22px 30px;
            font-size: 20px; font-weight: 600;
            display: flex; align-items: center; gap: 10px;
        }

        .form-card-body { padding: 35px; }

        /* ── Divider ── */
        .section-divider {
            font-size: 13px; font-weight: 600;
            color: var(--primary-color);
            text-transform: uppercase; letter-spacing: 0.6px;
            border-bottom: 2px solid var(--primary-soft);
            padding-bottom: 8px;
            margin: 30px 0 22px;
        }
        .section-divider:first-child { margin-top: 0; }

        /* ── Form Controls ── */
        .form-grid-3 { display: grid; grid-template-columns: repeat(3,1fr); gap: 20px; }
        .form-grid-2 { display: grid; grid-template-columns: repeat(2,1fr); gap: 20px; }

        .form-group { display: flex; flex-direction: column; }
        .form-group label {
            font-size: 13px; font-weight: 600;
            color: var(--text-dark); margin-bottom: 7px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 11px 14px;
            border: 1px solid #e5e7eb; border-radius: 8px;
            font-size: 14px; font-family: 'Poppins', sans-serif;
            color: var(--text-dark); background: #fafafa;
            transition: border-color 0.25s, background 0.25s;
            outline: none;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--primary-color);
            background: white;
            box-shadow: 0 0 0 3px rgba(127,29,29,0.07);
        }

        .form-group select { cursor: pointer; appearance: auto; }

        .form-group textarea { resize: vertical; min-height: 110px; }

        /* ── Consent ── */
        .consent-group {
            display: flex; align-items: flex-start; gap: 12px;
            background: var(--primary-soft);
            border: 1px solid #fca5a5;
            border-radius: 8px; padding: 16px 18px;
            margin-top: 10px;
        }
        .consent-group input[type="checkbox"] {
            margin-top: 3px; width: 17px; height: 17px;
            accent-color: var(--primary-color); cursor: pointer; flex-shrink: 0;
        }
        .consent-group label {
            font-size: 13px; color: var(--text-gray); line-height: 1.6; cursor: pointer;
        }

        /* ── Submit ── */
        .btn-submit {
            background: var(--primary-color); color: white;
            padding: 13px 40px; border-radius: 8px;
            font-size: 15px; font-weight: 600; border: none;
            cursor: pointer; transition: background 0.3s, transform 0.15s;
            display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-submit:hover { background: var(--primary-light); transform: translateY(-1px); }
        .btn-submit:active { transform: translateY(0); }

        .form-actions {
            display: flex; justify-content: flex-end;
            padding-top: 28px; margin-top: 10px;
            border-top: 1px solid #f0f0f0;
        }

        /* ── Responsive ── */
        @media (max-width: 1100px) {
            .form-grid-3 { grid-template-columns: repeat(2,1fr); }
        }
        @media (max-width: 768px) {
            .sidebar { width: 200px; }
            .main-content { margin-left: 200px; padding: 30px 20px; }
            .form-grid-3,
            .form-grid-2 { grid-template-columns: 1fr; }
            .form-card-body { padding: 24px; }
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
            <a href="book_appointment.php" class="nav-item active" style="color:inherit;text-decoration:none;">
                <i class="bi bi-calendar-plus"></i><span>Book Appointment</span>
            </a>
            <a href="medical_history.php" class="nav-item" style="color:inherit;text-decoration:none;">
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

    <!-- ── Main ── -->
    <main class="main-content">
        <div class="page-header">
            <h2>Book Appointment</h2>
            <p>Schedule your medical consultation or clearance</p>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <i class="bi bi-calendar-plus"></i>
                Appointment Form
            </div>

            <div class="form-card-body">

                <!-- Section 1: Appointment Details -->
                <div class="section-divider">Appointment Details</div>

                <div class="form-grid-3">
                    <div class="form-group">
                        <label for="serviceCategory">Service Category <span style="color:var(--primary-light);">*</span></label>
                        <select id="serviceCategory" required>
                            <option value="" disabled selected>Select category</option>
                            <option>General Consultation</option>
                            <option>First Aid / Injury Care</option>
                            <option>Medical Clearance</option>
                            <option>Follow-Up Checkup</option>
                            <option>Health Counseling</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="apptDate">Preferred Date <span style="color:var(--primary-light);">*</span></label>
                        <input type="date" id="apptDate" required>
                    </div>
                    <div class="form-group">
                        <label for="apptTime">Preferred Time <span style="color:var(--primary-light);">*</span></label>
                        <input type="time" id="apptTime" min="06:30" max="17:20" required>
                    </div>
                </div>

                <div class="form-group" style="margin-top:20px;">
                    <label for="reasonVisit">Reason for Visit <span style="color:var(--primary-light);">*</span></label>
                    <textarea id="reasonVisit" placeholder="Please describe your symptoms or reason for the appointment" required></textarea>
                </div>

                <!-- Section 2: Patient Information -->
                <div class="section-divider">Patient Information</div>

                <div class="form-grid-3">
                    <div class="form-group">
                        <label for="fullName">Full Name <span style="color:var(--primary-light);">*</span></label>
                        <input type="text" id="fullName" placeholder="Last, First Middle Name" required>
                    </div>
                    <div class="form-group">
                        <label for="studentNumber">Student Number <span style="color:var(--primary-light);">*</span></label>
                        <input type="text" id="studentNumber" placeholder="e.g., 2023-12345-MN-0" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address <span style="color:var(--primary-light);">*</span></label>
                        <input type="email" id="email" placeholder="juan@iskolarngbayan.pup.edu.ph" required>
                    </div>
                </div>

                <div class="form-grid-3" style="margin-top:20px;">
                    <div class="form-group">
                        <label for="phone">Contact Number <span style="color:var(--primary-light);">*</span></label>
                        <input type="tel" id="phone" placeholder="09XX-XXX-XXXX" required>
                    </div>
                    <div class="form-group">
                        <label for="program">Program / Course</label>
                        <input type="text" id="program" placeholder="e.g., BSIT">
                    </div>
                    <div class="form-group">
                        <label for="yearLevel">Year Level</label>
                        <select id="yearLevel">
                            <option value="" disabled selected>Select year</option>
                            <option>1st Year</option>
                            <option>2nd Year</option>
                            <option>3rd Year</option>
                            <option>4th Year</option>
                            <option>5th Year</option>
                            <option>Graduate</option>
                        </select>
                    </div>
                </div>

                <!-- Consent -->
                <div class="section-divider">Privacy Consent</div>

                <div class="consent-group">
                    <input type="checkbox" id="terms" required>
                    <label for="terms">
                        By submitting this form, you confirm that you have read and agree to <strong>iTechCare's Privacy Notice and Terms and Conditions</strong>, and give your explicit consent for the processing, use, and sharing of your personal data as outlined in our Privacy Notice. <span style="color:var(--primary-light);">*</span>
                    </label>
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <button class="btn-submit" onclick="submitAppointment()">
                        <i class="bi bi-check2-circle"></i> Submit Appointment
                    </button>
                </div>

            </div>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set min date to today
        document.getElementById('apptDate').min = new Date().toISOString().split('T')[0];

        function submitAppointment() {
            const category = document.getElementById('serviceCategory').value;
            const date     = document.getElementById('apptDate').value;
            const time     = document.getElementById('apptTime').value;
            const reason   = document.getElementById('reasonVisit').value.trim();
            const name     = document.getElementById('fullName').value.trim();
            const idNum    = document.getElementById('studentNumber').value.trim();
            const email    = document.getElementById('email').value.trim();
            const phone    = document.getElementById('phone').value.trim();
            const terms    = document.getElementById('terms').checked;

            if (!category || !date || !time || !reason || !name || !idNum || !email || !phone) {
                alert('Please fill in all required fields.');
                return;
            }
            if (!terms) {
                alert('Please agree to the Privacy Notice and Terms and Conditions.');
                return;
            }

            alert(`Appointment submitted successfully!\n\nCategory: ${category}\nDate: ${date}\nTime: ${time}\nName: ${name}`);
        }

        // Chatbot
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
            if (m.includes('category') || m.includes('type'))
                return "We offer 5 categories: General Consultation, First Aid/Injury Care, Medical Clearance, Follow-Up Checkup, and Health Counseling. Select one from the dropdown.";
            if (m.includes('time') || m.includes('slot'))
                return "Clinic hours are 8:00 AM – 5:00 PM, Monday to Friday. Use the time field to pick your preferred slot.";
            if (m.includes('requirement') || m.includes('document') || m.includes('need'))
                return "You'll need your Student Number, a valid email, contact number, and a clear reason for your visit.";
            if (m.includes('cancel') || m.includes('reschedule'))
                return "To cancel or reschedule, visit your Medical History page or contact the clinic directly.";
            if (m.includes('hello') || m.includes('hi'))
                return "Hello! Ready to help you book an appointment. Fill in the form and hit Submit!";
            return "I can help with booking, time slots, and requirements. What would you like to know?";
        }

        chatbotSend.addEventListener('click', () => sendMessage());
        chatbotInput.addEventListener('keypress', e => { if (e.key === 'Enter') sendMessage(); });
    </script>
</body>
</html>