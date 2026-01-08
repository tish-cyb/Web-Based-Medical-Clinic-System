<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurse Portal - Record Consultation</title>
    
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
            z-index: 1000;
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
            margin-bottom: 35px;
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

        .consultation-form-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .form-header {
            background: linear-gradient(90deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
            color: white;
            padding: 22px 30px;
            font-size: 20px;
            font-weight: 600;
        }

        .form-body {
            padding: 40px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin-bottom: 25px;
        }

        .form-row.two-col {
            grid-template-columns: repeat(2, 1fr);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(127, 29, 29, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }

        select.form-control {
            cursor: pointer;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #f0f0f0;
        }

        .btn-action {
            padding: 14px 32px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-cancel {
            background-color: #e5e7eb;
            color: var(--text-dark);
        }

        .btn-cancel:hover {
            background-color: #d1d5db;
        }

        .btn-save {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-save:hover {
            background-color: var(--primary-light);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(127, 29, 29, 0.3);
        }

        /* Success Alert */
        .success-alert {
            position: fixed;
            top: 30px;
            right: 30px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
            display: none;
            align-items: center;
            gap: 15px;
            z-index: 3000;
            animation: slideInRight 0.4s ease, slideOutRight 0.4s ease 2.6s;
            min-width: 320px;
        }

        .success-alert.show {
            display: flex;
        }

        .success-alert i {
            font-size: 28px;
        }

        .success-content {
            flex: 1;
        }

        .success-title {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 4px;
        }

        .success-message {
            font-size: 13px;
            opacity: 0.95;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        .required {
            color: #ef4444;
            margin-left: 4px;
        }

        @media (max-width: 1024px) {
            .form-row {
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

            .nav-item span {
                display: none;
            }

            .form-body {
                padding: 25px;
            }

            .form-row,
            .form-row.two-col {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn-action {
                width: 100%;
                justify-content: center;
            }

            .success-alert {
                left: 20px;
                right: 20px;
                min-width: auto;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h1>Nurse Portal</h1>
            <p>Clinical Management</p>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-item" data-page="dashboard">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </div>
            <div class="nav-item" data-page="roster">
                <i class="bi bi-people"></i>
                <span>Student Roster</span>
            </div>
            <div class="nav-item" data-page="appointments">
                <i class="bi bi-calendar-check"></i>
                <span>Appointments</span>
            </div>
            <div class="nav-item" data-page="records">
                <i class="bi bi-folder2-open"></i>
                <span>Patient Records</span>
            </div>
            <div class="nav-item active" data-page="consultations">
                <i class="bi bi-chat-dots"></i>
                <span>Consultations</span>
            </div>
            <div class="nav-item" data-page="certificate">
                <i class="bi bi-file-earmark-medical"></i>
                <span>Medical Certificate</span>
            </div>
            <div class="nav-item" data-page="profile">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </div>
        </nav>
    </div>

    <main class="main-content">
        <div class="page-header">
            <h2>Record Consultation</h2>
            <p>Document patient consultations and treatments</p>
        </div>

        <div class="consultation-form-section">
            <div class="form-header">
                New Consultation Record
            </div>
            <div class="form-body">
                <form id="consultationForm">
                    <!-- Patient Information -->
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Student Name<span class="required">*</span></label>
                            <input type="text" class="form-control" id="studentName" placeholder="Enter Student Name" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Student Number<span class="required">*</span></label>
                            <input type="text" class="form-control" id="studentNumber" placeholder="e.g., 2021-12345-MN-0" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Consultation Date<span class="required">*</span></label>
                            <input type="date" class="form-control" id="consultationDate" required>
                        </div>
                    </div>

                    <div class="form-row two-col">
                        <div class="form-group">
                            <label class="form-label">Consultation Type<span class="required">*</span></label>
                            <select class="form-control" id="consultationType" required>
                                <option value="">Select Type</option>
                                <option value="General Consultation">General Consultation</option>
                                <option value="Medical Clearance">Medical Clearance</option>
                                <option value="Follow-up">Follow-up</option>
                                <option value="Emergency">Emergency</option>
                                <option value="Injury Report">Injury Report</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Time of Consultation</label>
                            <input type="time" class="form-control" id="consultationTime">
                        </div>
                    </div>

                    <!-- Clinical Information -->
                    <div class="form-group">
                        <label class="form-label">Chief Complaint / Symptoms<span class="required">*</span></label>
                        <textarea class="form-control" id="chiefComplaint" placeholder="Describe the patient's main complaint or symptoms" required></textarea>
                    </div>

                    <div class="form-row two-col">
                        <div class="form-group">
                            <label class="form-label">Vital Signs</label>
                            <textarea class="form-control" id="vitalSigns" placeholder="BP: ___ / ___&#10;Temp: ___ °C&#10;Pulse: ___ bpm&#10;RR: ___ /min" style="min-height: 100px;"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Physical Examination Findings</label>
                            <textarea class="form-control" id="physicalExam" placeholder="Enter physical examination findings" style="min-height: 100px;"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Diagnosis<span class="required">*</span></label>
                        <textarea class="form-control" id="diagnosis" placeholder="Enter diagnosis or assessment" required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Treatment / Recommendations<span class="required">*</span></label>
                        <textarea class="form-control" id="treatment" placeholder="Describe treatment given and recommendations" required></textarea>
                    </div>

                    <div class="form-row two-col">
                        <div class="form-group">
                            <label class="form-label">Medications Given</label>
                            <textarea class="form-control" id="medications" placeholder="List medications and dosage" style="min-height: 80px;"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Follow-up Date</label>
                            <input type="date" class="form-control" id="followUpDate">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Additional Notes</label>
                        <textarea class="form-control" id="additionalNotes" placeholder="Any additional observations, instructions, or comments"></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-action btn-cancel" onclick="resetForm()">
                            <i class="bi bi-x-circle"></i>
                            Clear Form
                        </button>
                        <button type="submit" class="btn-action btn-save">
                            <i class="bi bi-check-circle"></i>
                            Save Consultation Record
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Success Alert -->
    <div id="successAlert" class="success-alert">
        <i class="bi bi-check-circle-fill"></i>
        <div class="success-content">
            <div class="success-title">Consultation Saved!</div>
            <div class="success-message">The consultation record has been successfully saved.</div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Navigation functionality
        const pageFiles = {
            'dashboard': 'nurse_dashboard.php',
            'roster': 'student_roster.php',
            'appointments': 'appointments.php',
            'records': 'patient_records.php',
            'consultations': 'consultations.php',
            'certificate': 'medical_cert.php',
            'profile': 'profile.php'
        };

        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function() {
                const page = this.getAttribute('data-page');
                const filename = pageFiles[page];
                
                if (filename) {
                    window.location.href = filename;
                }
            });
        });

        // Set today's date as default
        document.getElementById('consultationDate').valueAsDate = new Date();

        // Form submission
        document.getElementById('consultationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            const formData = {
                studentName: document.getElementById('studentName').value,
                studentNumber: document.getElementById('studentNumber').value,
                consultationDate: document.getElementById('consultationDate').value,
                consultationType: document.getElementById('consultationType').value,
                consultationTime: document.getElementById('consultationTime').value,
                chiefComplaint: document.getElementById('chiefComplaint').value,
                vitalSigns: document.getElementById('vitalSigns').value,
                physicalExam: document.getElementById('physicalExam').value,
                diagnosis: document.getElementById('diagnosis').value,
                treatment: document.getElementById('treatment').value,
                medications: document.getElementById('medications').value,
                followUpDate: document.getElementById('followUpDate').value,
                additionalNotes: document.getElementById('additionalNotes').value
            };

            // Validate required fields
            if (!formData.studentName || !formData.studentNumber || !formData.consultationDate || 
                !formData.consultationType || !formData.chiefComplaint || !formData.diagnosis || 
                !formData.treatment) {
                alert('Please fill in all required fields.');
                return;
            }

            // Here you would normally send the data to your server
            console.log('Consultation Record:', formData);

            // Show success alert
            showSuccessAlert();

            // Reset form after short delay
            setTimeout(() => {
                resetForm();
            }, 1000);
        });

        // Show success alert
        function showSuccessAlert() {
            const alert = document.getElementById('successAlert');
            alert.classList.add('show');
            
            // Auto-hide after 3 seconds
            setTimeout(() => {
                alert.classList.remove('show');
            }, 3000);
        }

        // Reset form function
        function resetForm() {
            document.getElementById('consultationForm').reset();
            document.getElementById('consultationDate').valueAsDate = new Date();
        }

        // Auto-resize textareas
        document.querySelectorAll('textarea.form-control').forEach(textarea => {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        });

        // Student number validation and formatting
        document.getElementById('studentNumber').addEventListener('input', function(e) {
            let value = e.target.value.toUpperCase();
            // Remove any non-alphanumeric characters except hyphens
            value = value.replace(/[^A-Z0-9-]/g, '');
            e.target.value = value;
        });

        // Set current time as default
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        document.getElementById('consultationTime').value = `${hours}:${minutes}`;
    </script>
</body>
</html>