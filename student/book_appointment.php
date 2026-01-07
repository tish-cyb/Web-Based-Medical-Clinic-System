<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - Book Appointment</title>
    
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

        .booking-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            overflow: hidden;
            margin-bottom: 25px;
        }

        .section-header {
            background: linear-gradient(90deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
            color: white;
            padding: 20px 30px;
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-header i {
            font-size: 20px;
            cursor: pointer;
        }

        .section-content {
            padding: 35px;
        }

        .requirements-list {
            list-style: none;
            padding: 0;
        }

        .requirements-list li {
            color: var(--text-gray);
            font-size: 15px;
            font-weight: 500;
            margin-bottom: 10px;
            padding-left: 20px;
            position: relative;
        }

        .requirements-list li::before {
            content: "•";
            position: absolute;
            left: 0;
            color: var(--primary-color);
            font-weight: 700;
            font-size: 20px;
        }

        .category-item {
            border-bottom: 1px solid #e5e7eb;
            padding: 25px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .category-item:last-child {
            border-bottom: none;
        }

        .category-info h4 {
            color: var(--text-dark);
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .category-info p {
            color: var(--text-gray);
            font-size: 14px;
            margin: 0;
        }

        .btn-select {
            background-color: var(--primary-color);
            color: white;
            padding: 10px 30px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-select:hover {
            background-color: var(--primary-light);
        }

        .nurse-card {
            border-bottom: 1px solid #e5e7eb;
            padding: 25px 0;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .nurse-card:last-child {
            border-bottom: none;
        }

        .nurse-info h4 {
            color: var(--text-dark);
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .nurse-info p {
            color: var(--text-gray);
            font-size: 14px;
            margin: 3px 0;
        }

        .calendar-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
        }

        .calendar-controls {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .calendar-controls button {
            background: none;
            border: none;
            color: var(--text-dark);
            font-size: 20px;
            cursor: pointer;
            padding: 5px 10px;
        }

        .calendar-controls select {
            padding: 8px 15px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-dark);
            cursor: pointer;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .calendar-day-header {
            text-align: center;
            color: var(--text-gray);
            font-size: 13px;
            font-weight: 600;
            padding: 10px;
        }

        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-dark);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .calendar-day:hover {
            background-color: var(--primary-soft);
        }

        .calendar-day.selected {
            background-color: var(--primary-color);
            color: white;
        }

        .calendar-day.disabled {
            color: #d1d5db;
            cursor: not-allowed;
        }

        .time-slots {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .time-slot-header {
            color: var(--text-dark);
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .time-slot-subheader {
            color: var(--text-gray);
            font-size: 13px;
            margin-bottom: 15px;
        }

        .time-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .time-slot {
            background-color: var(--primary-color);
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .time-slot:hover {
            background-color: var(--primary-light);
            transform: translateY(-2px);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            color: var(--text-dark);
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin: 25px 0;
        }

        .checkbox-group input[type="checkbox"] {
            margin-top: 3px;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .checkbox-group label {
            color: var(--text-gray);
            font-size: 13px;
            line-height: 1.5;
            margin: 0;
        }

        .btn-submit {
            background-color: var(--primary-color);
            color: white;
            padding: 14px 40px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            float: right;
            margin-bottom: 30px;
        }

        .btn-submit:hover {
            background-color: var(--primary-light);
        }

        .hidden {
            display: none;
        }

        @media (max-width: 1200px) {
            .calendar-container {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
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

            .time-grid {
                grid-template-columns: repeat(2, 1fr);
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
    <a href="book_appointment.php" class="nav-item active" style="text-decoration: none; color: inherit;">
        <i class="bi bi-calendar-plus"></i>
        <span>Book Appointment</span>
    </a>
    <a href="medical_history.php" class="nav-item" style="text-decoration: none; color: inherit;">
        <i class="bi bi-clock-history"></i>
        <span>Medical History</span>
    </a>
    <a href="certificates.php" class="nav-item" style="text-decoration: none; color: inherit;">
        <i class="bi bi-file-earmark-medical"></i>
        <span>Certificates</span>
    </a>
    <a href="profile.php" class="nav-item" style="text-decoration: none; color: inherit;">
        <i class="bi bi-person"></i>
        <span>Profile</span>
    </a>
</nav>
    </div>

    <main class="main-content">
        <div class="page-header">
            <h2>Book Appointment</h2>
            <p>Schedule your medical consultation or clearance</p>
        </div>

        <!-- Booking Requirements -->
        <div class="booking-section">
            <div class="section-header">
                Booking Requirements
            </div>
            <div class="section-content">
                <ul class="requirements-list">
                    <li>Valid Student ID</li>
                    <li>Clear reason for appointment</li>
                    <li>Complete student information</li>
                    <li>Available appointment slot</li>
                </ul>
            </div>
        </div>

        <!-- Step 1: Select Category -->
        <div class="booking-section" id="step1">
            <div class="section-header">
                Select Category
            </div>
            <div class="section-content">
                <div class="category-item">
                    <div class="category-info">
                        <h4>General Consultation:</h4>
                        <p>For common symptoms and check-ups.</p>
                    </div>
                    <button class="btn-select" onclick="selectCategory('General Consultation')">Select</button>
                </div>
                <div class="category-item">
                    <div class="category-info">
                        <h4>First Aid / Injury Care:</h4>
                        <p>For minor injuries or urgent non-emergency care.</p>
                    </div>
                    <button class="btn-select" onclick="selectCategory('First Aid / Injury Care')">Select</button>
                </div>
                <div class="category-item">
                    <div class="category-info">
                        <h4>Medical Clearance:</h4>
                        <p>For PE, school requirements, or activity forms.</p>
                    </div>
                    <button class="btn-select" onclick="selectCategory('Medical Clearance')">Select</button>
                </div>
                <div class="category-item">
                    <div class="category-info">
                        <h4>Follow-Up Checkup:</h4>
                        <p>For returning patients needing monitoring.</p>
                    </div>
                    <button class="btn-select" onclick="selectCategory('Follow-Up Checkup')">Select</button>
                </div>
                <div class="category-item">
                    <div class="category-info">
                        <h4>Health Counseling:</h4>
                        <p>For stress, hygiene, and wellness concerns.</p>
                    </div>
                    <button class="btn-select" onclick="selectCategory('Health Counseling')">Select</button>
                </div>
            </div>
        </div>

        <!-- Step 2: Select Nurse -->
        <div class="booking-section hidden" id="step2">
            <div class="section-header">
                <i class="bi bi-arrow-left" onclick="goToStep(1)"></i>
                Select Category
            </div>
            <div class="section-content">
                <div class="nurse-card">
                    <div class="nurse-info">
                        <h4>(Nurse) Maria Dela Cruz (PUP iTech Clinic)</h4>
                        <p>On Duty Location: PUP Institute of Technology, Manila</p>
                        <p>Role: General Clinic Nurse</p>
                        <p style="margin-top: 10px;"><strong>Schedule:</strong></p>
                        <p>8:00 AM – 5:00 PM (MON–FRI)</p>
                    </div>
                    <button class="btn-select" onclick="goToStep(3)">Select</button>
                </div>
                <div class="nurse-card">
                    <div class="nurse-info">
                        <h4>(Nurse) Maria Dela Cruz (PUP iTech Clinic)</h4>
                        <p>On Duty Location: PUP Institute of Technology, Manila</p>
                        <p>Role: General Clinic Nurse</p>
                        <p style="margin-top: 10px;"><strong>Schedule:</strong></p>
                        <p>8:00 AM – 5:00 PM (MON–FRI)</p>
                    </div>
                    <button class="btn-select" onclick="goToStep(3)">Select</button>
                </div>
                <div class="nurse-card">
                    <div class="nurse-info">
                        <h4>(Nurse) Maria Dela Cruz (PUP iTech Clinic)</h4>
                        <p>On Duty Location: PUP Institute of Technology, Manila</p>
                        <p>Role: General Clinic Nurse</p>
                        <p style="margin-top: 10px;"><strong>Schedule:</strong></p>
                        <p>8:00 AM – 5:00 PM (MON–FRI)</p>
                    </div>
                    <button class="btn-select" onclick="goToStep(3)">Select</button>
                </div>
            </div>
        </div>

        <!-- Step 3: Calendar -->
        <div class="booking-section hidden" id="step3">
            <div class="section-header">
                <i class="bi bi-arrow-left" onclick="goToStep(2)"></i>
                Calendar
            </div>
            <div class="section-content">
                <div class="calendar-container">
                    <div class="calendar-section">
                        <div class="calendar-controls">
                            <button><i class="bi bi-chevron-left"></i></button>
                            <select>
                                <option>November</option>
                            </select>
                            <select>
                                <option>2025</option>
                            </select>
                            <button><i class="bi bi-chevron-right"></i></button>
                        </div>
                        <div class="calendar-grid">
                            <div class="calendar-day-header">M</div>
                            <div class="calendar-day-header">T</div>
                            <div class="calendar-day-header">W</div>
                            <div class="calendar-day-header">T</div>
                            <div class="calendar-day-header">F</div>
                            <div class="calendar-day-header">S</div>
                            <div class="calendar-day-header">S</div>
                            <div class="calendar-day disabled"></div>
                            <div class="calendar-day disabled"></div>
                            <div class="calendar-day disabled"></div>
                            <div class="calendar-day disabled"></div>
                            <div class="calendar-day disabled">1</div>
                            <div class="calendar-day disabled">2</div>
                            <div class="calendar-day">3</div>
                            <div class="calendar-day">4</div>
                            <div class="calendar-day">5</div>
                            <div class="calendar-day">6</div>
                            <div class="calendar-day">7</div>
                            <div class="calendar-day">8</div>
                            <div class="calendar-day">9</div>
                            <div class="calendar-day">10</div>
                            <div class="calendar-day">11</div>
                            <div class="calendar-day">12</div>
                            <div class="calendar-day">13</div>
                            <div class="calendar-day">14</div>
                            <div class="calendar-day">15</div>
                            <div class="calendar-day">16</div>
                            <div class="calendar-day">17</div>
                            <div class="calendar-day">18</div>
                            <div class="calendar-day">19</div>
                            <div class="calendar-day">20</div>
                            <div class="calendar-day">21</div>
                            <div class="calendar-day">22</div>
                            <div class="calendar-day">23</div>
                            <div class="calendar-day">24</div>
                            <div class="calendar-day">25</div>
                            <div class="calendar-day selected">26</div>
                            <div class="calendar-day">27</div>
                            <div class="calendar-day">28</div>
                            <div class="calendar-day">29</div>
                            <div class="calendar-day">30</div>
                        </div>
                    </div>
                    <div class="time-slots">
                        <div class="time-slot-header">Wednesday, November 26</div>
                        <div class="time-slot-subheader">Time Zone: Manila (GMT + 08:00)</div>
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
                        <button class="btn-submit" onclick="goToStep(4)" style="margin-top: 20px;">Continue</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 4: Your Information -->
        <div class="booking-section hidden" id="step4">
            <div class="section-header">
                <i class="bi bi-arrow-left" onclick="goToStep(3)"></i>
                Your Information
            </div>
            <div class="section-content">
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" placeholder="Juan Dela Cruz">
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="tel" placeholder="e.g., 00-000-0000">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" placeholder="juan@iskolarngbayan.pup.edu.ph">
                    </div>
                </div>
                <div class="form-group">
                    <label>Student Number</label>
                    <input type="text" placeholder="e.g., 2021-12345-MN-0" style="max-width: 400px;">
                </div>
                <div class="form-group">
                    <label>Reason for visit</label>
                    <textarea placeholder="Please describe your symptoms or reason for the appointment"></textarea>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="terms">
                    <label for="terms">By submitting this form, you confirm to have read and agree to iTechCare's Privacy Notice and Terms and Conditions, and give your explicit consent for the processing, use and sharing of your personal data as outlined in our Privacy Notice. *</label>
                </div>
                <button type="button" class="btn-submit" onclick="submitAppointment()">Submit Appointment</button>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let selectedCategory = '';
        let selectedTimeSlot = '';

        function selectCategory(category) {
            selectedCategory = category;
            goToStep(2);
        }

        function goToStep(step) {
            document.getElementById('step1').classList.add('hidden');
            document.getElementById('step2').classList.add('hidden');
            document.getElementById('step3').classList.add('hidden');
            document.getElementById('step4').classList.add('hidden');
            
            document.getElementById('step' + step).classList.remove('hidden');
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        document.querySelectorAll('.calendar-day:not(.disabled)').forEach(day => {
            day.addEventListener('click', function() {
                document.querySelectorAll('.calendar-day').forEach(d => {
                    d.classList.remove('selected');
                });
                this.classList.add('selected');
            });
        });

        function selectTimeSlot(element, time) {
            document.querySelectorAll('.time-slot').forEach(slot => {
                slot.style.opacity = '0.7';
            });
            element.style.opacity = '1';
            selectedTimeSlot = time;
        }

        function submitAppointment() {
            alert('Appointment submitted successfully!\n\nCategory: ' + selectedCategory + '\nTime: ' + selectedTimeSlot);
        }

        // Navigation functionality
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.nav-item').forEach(nav => {
                    nav.classList.remove('active');
                });
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>