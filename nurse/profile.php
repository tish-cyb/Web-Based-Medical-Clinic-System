<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurse Portal - Profile</title>
    
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

        .profile-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .section-header {
            background: linear-gradient(90deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
            color: white;
            padding: 22px 30px;
            font-size: 20px;
            font-weight: 600;
        }

        .section-body {
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
            background-color: #f9fafb;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(127, 29, 29, 0.1);
            background-color: white;
        }

        .form-control:disabled {
            background-color: #f3f4f6;
            cursor: not-allowed;
        }

        .form-control.error {
            border-color: #ef4444;
        }

        .error-message {
            color: #ef4444;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }

        .error-message.show {
            display: block;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #f0f0f0;
        }

        .btn-action {
            padding: 12px 28px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-edit {
            background-color: #e5e7eb;
            color: var(--text-dark);
        }

        .btn-edit:hover {
            background-color: #d1d5db;
        }

        .btn-logout {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-logout:hover {
            background-color: var(--primary-light);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(127, 29, 29, 0.3);
        }

        .btn-save {
            background-color: #10b981;
            color: white;
        }

        .btn-save:hover {
            background-color: #059669;
        }

        .btn-cancel {
            background-color: #6b7280;
            color: white;
        }

        .btn-cancel:hover {
            background-color: #4b5563;
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

        /* Logout Confirmation Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            border-radius: 16px;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            background: linear-gradient(90deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
            color: white;
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 16px 16px 0 0;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }

        .modal-body {
            padding: 30px;
        }

        .modal-body p {
            margin-bottom: 20px;
            color: var(--text-dark);
            font-size: 15px;
            line-height: 1.6;
        }

        .modal-footer {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .btn-modal {
            padding: 10px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-modal-cancel {
            background-color: #e5e7eb;
            color: var(--text-dark);
        }

        .btn-modal-cancel:hover {
            background-color: #d1d5db;
        }

        .btn-modal-confirm {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-modal-confirm:hover {
            background-color: var(--primary-light);
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

            .section-body {
                padding: 25px;
            }

            .form-row,
            .form-row.two-col {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-action {
                width: 100%;
                justify-content: center;
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
            <div class="nav-item" data-page="consultations">
                <i class="bi bi-chat-dots"></i>
                <span>Consultations</span>
            </div>
            <div class="nav-item" data-page="certificate">
                <i class="bi bi-file-earmark-medical"></i>
                <span>Medical Certificate</span>
            </div>
            <div class="nav-item active" data-page="profile">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </div>
        </nav>
    </div>

    <main class="main-content">
        <div class="page-header">
            <h2>Nurse Profile</h2>
            <p>Manage your professional information</p>
        </div>

        <div class="profile-section">
            <div class="section-header">
                Professional Information
            </div>
            <div class="section-body">
                <form id="profileForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="fullName" value="Maria Garcia, RN" disabled>
                            <span class="error-message" id="fullNameError">Please enter a valid name (letters and spaces only)</span>
                        </div>
                        <div class="form-group">
                            <label class="form-label">License Number</label>
                            <input type="text" class="form-control" id="licenseNumber" value="RN-123456" disabled>
                            <span class="error-message" id="licenseNumberError">Please enter a valid license number</span>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Position</label>
                            <input type="text" class="form-control" id="position" value="Registered Nurse" disabled>
                            <span class="error-message" id="positionError">Position is required</span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Department</label>
                            <input type="text" class="form-control" id="department" value="PUP iTech Clinic" disabled>
                            <span class="error-message" id="departmentError">Department is required</span>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="emailAddress" value="maria.garcia@pup.edu.ph" disabled>
                            <span class="error-message" id="emailError">Please enter a valid email address</span>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" id="contactNumber" value="+63 917 123 4567" disabled>
                            <span class="error-message" id="contactError">Please enter a valid phone number (e.g., +63 917 123 4567)</span>
                        </div>
                    </div>

                    <div class="action-buttons" id="viewModeButtons">
                        <button type="button" class="btn-action btn-edit" onclick="enableEdit()">
                            <i class="bi bi-pencil-square"></i>
                            Edit Profile
                        </button>
                        <button type="button" class="btn-action btn-logout" onclick="showLogoutModal()">
                            <i class="bi bi-box-arrow-right"></i>
                            Logout
                        </button>
                    </div>

                    <div class="action-buttons" id="editModeButtons" style="display: none;">
                        <button type="button" class="btn-action btn-cancel" onclick="cancelEdit()">
                            <i class="bi bi-x-circle"></i>
                            Cancel
                        </button>
                        <button type="submit" class="btn-action btn-save">
                            <i class="bi bi-check-circle"></i>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Logout Confirmation Modal -->
    <div id="logoutModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirm Logout</h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to logout? You will be redirected to the login page.</p>
                <div class="modal-footer">
                    <button class="btn-modal btn-modal-cancel" onclick="closeLogoutModal()">Cancel</button>
                    <button class="btn-modal btn-modal-confirm" onclick="confirmLogout()">Logout</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Alert -->
    <div id="successAlert" class="success-alert">
        <i class="bi bi-check-circle-fill"></i>
        <div class="success-content">
            <div class="success-title">Profile Updated!</div>
            <div class="success-message">Your profile information has been successfully saved.</div>
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

        let isEditMode = false;

        // Enable edit mode
        function enableEdit() {
            isEditMode = true;
            document.getElementById('fullName').disabled = false;
            document.getElementById('licenseNumber').disabled = false;
            document.getElementById('position').disabled = false;
            document.getElementById('department').disabled = false;
            document.getElementById('emailAddress').disabled = false;
            document.getElementById('contactNumber').disabled = false;

            document.getElementById('viewModeButtons').style.display = 'none';
            document.getElementById('editModeButtons').style.display = 'flex';
        }

        // Cancel edit mode
        function cancelEdit() {
            isEditMode = false;
            document.getElementById('fullName').disabled = true;
            document.getElementById('licenseNumber').disabled = true;
            document.getElementById('position').disabled = true;
            document.getElementById('department').disabled = true;
            document.getElementById('emailAddress').disabled = true;
            document.getElementById('contactNumber').disabled = true;

            // Reset values
            document.getElementById('fullName').value = "Maria Garcia, RN";
            document.getElementById('licenseNumber').value = "RN-123456";
            document.getElementById('position').value = "Registered Nurse";
            document.getElementById('department').value = "PUP iTech Clinic";
            document.getElementById('emailAddress').value = "maria.garcia@pup.edu.ph";
            document.getElementById('contactNumber').value = "+63 917 123 4567";

            // Clear errors
            clearAllErrors();

            document.getElementById('viewModeButtons').style.display = 'flex';
            document.getElementById('editModeButtons').style.display = 'none';
        }

        // Validation functions
        function validateName(name) {
            const nameRegex = /^[a-zA-Z\s,.-]+$/;
            return nameRegex.test(name) && name.trim().length >= 3;
        }

        function validateLicenseNumber(license) {
            return license.trim().length >= 5;
        }

        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function validatePhone(phone) {
            const phoneRegex = /^[\+]?[(]?[0-9]{1,3}[)]?[-\s\.]?[(]?[0-9]{1,4}[)]?[-\s\.]?[0-9]{1,4}[-\s\.]?[0-9]{1,9}$/;
            return phoneRegex.test(phone.replace(/\s/g, ''));
        }

        function validateRequired(value) {
            return value.trim().length > 0;
        }

        // Show error
        function showError(fieldId, errorId) {
            document.getElementById(fieldId).classList.add('error');
            document.getElementById(errorId).classList.add('show');
        }

        // Clear error
        function clearError(fieldId, errorId) {
            document.getElementById(fieldId).classList.remove('error');
            document.getElementById(errorId).classList.remove('show');
        }

        // Clear all errors
        function clearAllErrors() {
            const errorMessages = document.querySelectorAll('.error-message');
            errorMessages.forEach(msg => msg.classList.remove('show'));
            
            const errorFields = document.querySelectorAll('.form-control.error');
            errorFields.forEach(field => field.classList.remove('error'));
        }

        // Real-time validation
        document.getElementById('fullName').addEventListener('input', function() {
            if (isEditMode) {
                if (validateName(this.value)) {
                    clearError('fullName', 'fullNameError');
                } else {
                    showError('fullName', 'fullNameError');
                }
            }
        });

        document.getElementById('licenseNumber').addEventListener('input', function() {
            if (isEditMode) {
                if (validateLicenseNumber(this.value)) {
                    clearError('licenseNumber', 'licenseNumberError');
                } else {
                    showError('licenseNumber', 'licenseNumberError');
                }
            }
        });

        document.getElementById('position').addEventListener('input', function() {
            if (isEditMode) {
                if (validateRequired(this.value)) {
                    clearError('position', 'positionError');
                } else {
                    showError('position', 'positionError');
                }
            }
        });

        document.getElementById('department').addEventListener('input', function() {
            if (isEditMode) {
                if (validateRequired(this.value)) {
                    clearError('department', 'departmentError');
                } else {
                    showError('department', 'departmentError');
                }
            }
        });

        document.getElementById('emailAddress').addEventListener('input', function() {
            if (isEditMode) {
                if (validateEmail(this.value)) {
                    clearError('emailAddress', 'emailError');
                } else {
                    showError('emailAddress', 'emailError');
                }
            }
        });

        document.getElementById('contactNumber').addEventListener('input', function() {
            if (isEditMode) {
                if (validatePhone(this.value)) {
                    clearError('contactNumber', 'contactError');
                } else {
                    showError('contactNumber', 'contactError');
                }
            }
        });

        // Form submission
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();

            clearAllErrors();

            // Get values
            const fullName = document.getElementById('fullName').value;
            const licenseNumber = document.getElementById('licenseNumber').value;
            const position = document.getElementById('position').value;
            const department = document.getElementById('department').value;
            const emailAddress = document.getElementById('emailAddress').value;
            const contactNumber = document.getElementById('contactNumber').value;

            // Validate all fields
            let isValid = true;

            if (!validateName(fullName)) {
                showError('fullName', 'fullNameError');
                isValid = false;
            }

            if (!validateLicenseNumber(licenseNumber)) {
                showError('licenseNumber', 'licenseNumberError');
                isValid = false;
            }

            if (!validateRequired(position)) {
                showError('position', 'positionError');
                isValid = false;
            }

            if (!validateRequired(department)) {
                showError('department', 'departmentError');
                isValid = false;
            }

            if (!validateEmail(emailAddress)) {
                showError('emailAddress', 'emailError');
                isValid = false;
            }

            if (!validatePhone(contactNumber)) {
                showError('contactNumber', 'contactError');
                isValid = false;
            }

            if (!isValid) {
                return;
            }

            // Save the profile data (in a real application, this would be sent to the server)
            console.log('Profile Updated:', {
                fullName,
                licenseNumber,
                position,
                department,
                emailAddress,
                contactNumber
            });

            // Disable fields and switch buttons
            cancelEdit();

            // Show success alert
            showSuccessAlert();
        });

        // Show success alert
        function showSuccessAlert() {
            const alert = document.getElementById('successAlert');
            alert.classList.add('show');
            
            setTimeout(() => {
                alert.classList.remove('show');
            }, 3000);
        }

        // Logout modal functions
        function showLogoutModal() {
            document.getElementById('logoutModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        function confirmLogout() {
            // Redirect to index.php
            window.location.href = 'index.php';
        }

        // Close modal when clicking outside
        document.getElementById('logoutModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLogoutModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLogoutModal();
            }
        });
    </script>
</body>
</html>