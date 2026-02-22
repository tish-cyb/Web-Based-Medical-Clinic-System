<!-- filepath: c:\xampp74\htdocs\Web-Based-Medical-Clinic-System\student\student_dashboard.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - Medical Services</title>
    
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
            --header-height: 90px;
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

        .welcome-section {
            margin-bottom: 40px;
        }

        .welcome-section h2 {
            color: var(--primary-color);
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .welcome-section p {
            color: var(--text-gray);
            font-size: 16px;
            font-weight: 400;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 32px 28px;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            border-left: 5px solid var(--primary-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 20px rgba(127, 29, 29, 0.15);
        }

        .stat-number {
            font-size: 52px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 8px;
            line-height: 1;
        }

        .stat-label {
            color: var(--text-gray);
            font-size: 15px;
            font-weight: 500;
        }

        .appointments-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            overflow: hidden;
        }

        .appointments-header {
            background: linear-gradient(90deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
            color: white;
            padding: 22px 30px;
            font-size: 20px;
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

        .status-confirmed {
            background-color: #d4edda;
            color: #155724;
        }

        .status-completed {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 200px;
                padding: 30px 20px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .nav-item {
                padding: 14px 20px;
                font-size: 14px;
            }

            .nav-item span {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h1>Student Portal</h1>
            <p>Medical Services</p>
        </div>
        
        <nav class="sidebar-nav">
    <a href="student_dashboard.php" class="nav-item active" style="text-decoration: none; color: inherit;">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="book_appointment.php" class="nav-item" style="text-decoration: none; color: inherit;">
        <i class="bi bi-calendar-plus"></i>
        <span>Book Appointment</span>
    </a>
    <a href="medical_history.php" class="nav-item" style="text-decoration: none; color: inherit;">
        <i class="bi bi-clock-history"></i>
        <span>Medical History</span>
    </a>
    <a href="certificates.php" class="nav-item" style="text-decoration: none; color: inherit;">
        <i class="bi bi-file-earmark-text"></i>
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
            <button class="quick-action-btn" data-message="Book an appointment">📅 Book Appointment</button>
            <button class="quick-action-btn" data-message="Check my medical records">📋 Medical Records</button>
            <button class="quick-action-btn" data-message="Request a certificate">📄 Get Certificate</button>
        </div>
        <div class="chatbot-messages" id="chatbotMessages">
            <div class="message bot">
                <div class="message-avatar">🤖</div>
                <div class="message-content">
                    Hello! I'm your medical assistant. How can I help you today?
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
        <div class="welcome-section">
            <h2>Welcome to Dashboard!</h2>
            <p>Medical portal of PUP iTech Clinic</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">3</div>
                <div class="stat-label">Upcoming Appointments</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">12</div>
                <div class="stat-label">Medical Records</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">2</div>
                <div class="stat-label">Available Certificates</div>
            </div>
        </div>

        <div class="appointments-section">
            <div class="appointments-header">
                Recent Appointments
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Purpose</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>March 20, 2024</td>
                        <td>9:00 AM</td>
                        <td>Medical Clearance</td>
                        <td><span class="status-badge status-confirmed">Confirmed</span></td>
                    </tr>
                    <tr>
                        <td>March 18, 2024</td>
                        <td>10:00 AM</td>
                        <td>Consultation</td>
                        <td><span class="status-badge status-completed">Completed</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Navigation functionality
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

        // Bot responses
        function getBotResponse(message) {
            const lowerMessage = message.toLowerCase();
            
            if (lowerMessage.includes('appointment') || lowerMessage.includes('book')) {
                return "I can help you book an appointment! You currently have 3 upcoming appointments. Would you like to schedule a new one? Please go to the 'Book Appointment' section.";
            } else if (lowerMessage.includes('record') || lowerMessage.includes('history')) {
                return "You have 12 medical records in your file. You can view all your medical history in the 'Medical History' section.";
            } else if (lowerMessage.includes('certificate')) {
                return "You have 2 certificates available for download. Please visit the 'Certificates' section to view and download them.";
            } else if (lowerMessage.includes('hello') || lowerMessage.includes('hi')) {
                return "Hello! How can I assist you with your medical needs today?";
            } else if (lowerMessage.includes('help')) {
                return "I can help you with:\n• Booking appointments\n• Viewing medical records\n• Downloading certificates\n• General clinic information\n\nWhat would you like to know?";
            } else if (lowerMessage.includes('hour') || lowerMessage.includes('time') || lowerMessage.includes('open')) {
                return "The PUP iTech Clinic is open Monday to Friday, 8:00 AM to 5:00 PM. We're closed on weekends and holidays.";
            } else {
                return "I understand you're asking about: '" + message + "'. For specific inquiries, please contact our clinic staff or use the navigation menu to access different sections.";
            }
        }

        // Event listeners
        chatbotSend.addEventListener('click', () => sendMessage());
        
        chatbotInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    </script>
</body>
</html>