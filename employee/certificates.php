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
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h1>Student Portal</h1>
            <p>Medical Services</p>
        </div>
        
        <nav class="sidebar-nav">
            <a href="student_dashboard.php" class="nav-item" style="text-decoration: none; color: inherit;">
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
                            <button class="btn-action btn-view">View</button>
                            <button class="btn-action btn-download">Download PDF</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Fitness Certificate</td>
                        <td>March 20, 2024</td>
                        <td>June 20, 2024</td>
                        <td><span class="status-badge status-valid">Valid</span></td>
                        <td>
                            <button class="btn-action btn-view">View</button>
                            <button class="btn-action btn-download">Download PDF</button>
                        </td>
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