<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurse Portal - Appointments</title>
    
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .appointments-header h3 {
            font-size: 20px;
            font-weight: 600;
            margin: 0;
        }

        .btn-add-appointment {
            padding: 10px 24px;
            background-color: white;
            color: var(--primary-color);
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

        .btn-add-appointment:hover {
            background-color: #f9fafb;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
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

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: var(--primary-soft);
        }

        .status-badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .status-confirmed {
            background-color: #d4edda;
            color: #155724;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-completed {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-action {
            padding: 6px 16px;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-complete {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-complete:hover {
            background-color: var(--primary-light);
        }

        .btn-confirm {
            background-color: #2f9447ff;
            color: white;
        }

        .btn-confirm:hover {
            background-color: #2f9447ff;
        }

        .btn-reschedule {
            background-color: #6c757d;
            color: white;
        }

        .btn-reschedule:hover {
            background-color: #5a6268;
        }

        .btn-cancel {
            background-color: #dc3545;
            color: white;
        }

        .btn-cancel:hover {
            background-color: #c82333;
        }

        /* Modal Styles */
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
            max-width: 500px;
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

        .modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 28px;
            cursor: pointer;
            padding: 0;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .modal-close:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: rotate(90deg);
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

        .form-group {
            margin-bottom: 20px;
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

            .table {
                font-size: 12px;
            }

            .table thead th,
            .table tbody td {
                padding: 12px 15px;
            }

            .action-buttons {
                flex-direction: column;
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
            <div class="nav-item active" data-page="appointments">
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
            <div class="nav-item" data-page="profile">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </div>
        </nav>
    </div>

    <main class="main-content">
        <div class="page-header">
            <h2>Appointment Management</h2>
            <p>Manage student appointments and schedules</p>
        </div>

        <div class="appointments-section">
            <div class="appointments-header">
                <h3>All Appointments</h3>
                <button class="btn-add-appointment" onclick="openAddAppointmentModal()">
                    <i class="bi bi-plus-circle"></i>
                    Add Appointment
                </button>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Student Name</th>
                        <th>Purpose</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="appointmentsTableBody">
                    <!-- Appointments will be populated here -->
                </tbody>
            </table>
        </div>
    </main>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Confirm Action</h3>
                <button class="modal-close" onclick="closeModal('confirmationModal')">&times;</button>
            </div>
            <div class="modal-body">
                <p id="modalMessage">Are you sure you want to proceed?</p>
                <div class="modal-footer">
                    <button class="btn-modal btn-modal-cancel" onclick="closeModal('confirmationModal')">Cancel</button>
                    <button class="btn-modal btn-modal-confirm" id="confirmButton">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Reschedule Appointment Modal -->
    <div id="appointmentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="appointmentModalTitle">Add New Appointment</h3>
                <button class="modal-close" onclick="closeModal('appointmentModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="appointmentForm">
                    <div class="form-group">
                        <label class="form-label">Student Name</label>
                        <input type="text" class="form-control" id="studentName" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" id="appointmentDate" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Time</label>
                        <input type="time" class="form-control" id="appointmentTime" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Purpose</label>
                        <select class="form-control" id="appointmentPurpose" required>
                            <option value="">Select Purpose</option>
                            <option value="Medical Clearance">Medical Clearance</option>
                            <option value="Consultation">Consultation</option>
                            <option value="Medical Certificate">Medical Certificate</option>
                            <option value="Follow-up">Follow-up</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-modal btn-modal-cancel" onclick="closeModal('appointmentModal')">Cancel</button>
                        <button type="submit" class="btn-modal btn-modal-confirm">Save Appointment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sample appointments data
        let appointments = [
            { id: 1, date: 'Mar. 20, 2024', time: '9:00 AM', student: 'Juan Dela Cruz', purpose: 'Medical Clearance', status: 'confirmed' },
            { id: 2, date: 'May 20, 2025', time: '9:30 AM', student: 'May Reyes', purpose: 'Consultation', status: 'pending' },
            { id: 3, date: 'Apr. 2, 2025', time: '11:00 AM', student: 'John Garcia', purpose: 'Consultation', status: 'pending' },
            { id: 4, date: 'Apr. 5, 2025', time: '1:00 PM', student: 'Mark Cruz', purpose: 'Medical Certificate', status: 'pending' },
            { id: 5, date: 'Mar. 15, 2024', time: '10:00 AM', student: 'Maria Santos', purpose: 'Follow-up', status: 'completed' },
            { id: 6, date: 'Feb. 28, 2024', time: '2:00 PM', student: 'Ana Lopez', purpose: 'Consultation', status: 'cancelled' }
        ];

        let currentAppointmentId = null;

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

        // Render appointments table
        function renderAppointments() {
            const tbody = document.getElementById('appointmentsTableBody');
            
            tbody.innerHTML = appointments.map(appointment => {
                const statusClass = `status-${appointment.status}`;
                const statusText = appointment.status.charAt(0).toUpperCase() + appointment.status.slice(1);
                
                let actionButtons = '';
                
                if (appointment.status === 'confirmed') {
                    actionButtons = `
                        <button class="btn-action btn-complete" onclick="handleComplete(${appointment.id})">Complete</button>
                        <button class="btn-action btn-reschedule" onclick="handleReschedule(${appointment.id})">Reschedule</button>
                    `;
                } else if (appointment.status === 'pending') {
                    actionButtons = `
                        <button class="btn-action btn-confirm" onclick="handleConfirm(${appointment.id})">Confirm</button>
                        <button class="btn-action btn-cancel" onclick="handleCancel(${appointment.id})">Cancel</button>
                    `;
                } else {
                    actionButtons = `<span style="color: var(--text-gray); font-size: 12px;">No actions available</span>`;
                }
                
                return `
                    <tr>
                        <td>${appointment.date}</td>
                        <td>${appointment.time}</td>
                        <td>${appointment.student}</td>
                        <td>${appointment.purpose}</td>
                        <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                        <td>
                            <div class="action-buttons">
                                ${actionButtons}
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Modal functions
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        function openConfirmationModal(title, message, onConfirm) {
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalMessage').textContent = message;
            document.getElementById('confirmButton').onclick = function() {
                onConfirm();
                closeModal('confirmationModal');
            };
            document.getElementById('confirmationModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        // Action handlers
        function handleConfirm(id) {
            openConfirmationModal(
                'Confirm Appointment',
                'Are you sure you want to confirm this appointment?',
                () => {
                    const appointment = appointments.find(a => a.id === id);
                    if (appointment) {
                        appointment.status = 'confirmed';
                        renderAppointments();
                        showSuccessMessage('Appointment confirmed successfully!');
                    }
                }
            );
        }

        function handleComplete(id) {
            openConfirmationModal(
                'Complete Appointment',
                'Mark this appointment as completed?',
                () => {
                    const appointment = appointments.find(a => a.id === id);
                    if (appointment) {
                        appointment.status = 'completed';
                        renderAppointments();
                        showSuccessMessage('Appointment marked as completed!');
                    }
                }
            );
        }

        function handleCancel(id) {
            openConfirmationModal(
                'Cancel Appointment',
                'Are you sure you want to cancel this appointment? This action cannot be undone.',
                () => {
                    const appointment = appointments.find(a => a.id === id);
                    if (appointment) {
                        appointment.status = 'cancelled';
                        renderAppointments();
                        showSuccessMessage('Appointment cancelled.');
                    }
                }
            );
        }

        function handleReschedule(id) {
            currentAppointmentId = id;
            const appointment = appointments.find(a => a.id === id);
            if (appointment) {
                document.getElementById('appointmentModalTitle').textContent = 'Reschedule Appointment';
                document.getElementById('studentName').value = appointment.student;
                document.getElementById('appointmentPurpose').value = appointment.purpose;
                document.getElementById('appointmentModal').classList.add('show');
                document.body.style.overflow = 'hidden';
            }
        }

        function openAddAppointmentModal() {
            currentAppointmentId = null;
            document.getElementById('appointmentModalTitle').textContent = 'Add New Appointment';
            document.getElementById('appointmentForm').reset();
            document.getElementById('appointmentModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        // Form submission
        document.getElementById('appointmentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const studentName = document.getElementById('studentName').value;
            const date = document.getElementById('appointmentDate').value;
            const time = document.getElementById('appointmentTime').value;
            const purpose = document.getElementById('appointmentPurpose').value;
            
            if (currentAppointmentId) {
                // Rescheduling existing appointment
                const appointment = appointments.find(a => a.id === currentAppointmentId);
                if (appointment) {
                    appointment.date = new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                    appointment.time = formatTime(time);
                    appointment.status = 'pending';
                    showSuccessMessage('Appointment rescheduled successfully!');
                }
            } else {
                // Adding new appointment
                const newAppointment = {
                    id: appointments.length + 1,
                    date: new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
                    time: formatTime(time),
                    student: studentName,
                    purpose: purpose,
                    status: 'pending'
                };
                appointments.push(newAppointment);
                showSuccessMessage('New appointment added successfully!');
            }
            
            renderAppointments();
            closeModal('appointmentModal');
        });

        function formatTime(time) {
            const [hours, minutes] = time.split(':');
            const hour = parseInt(hours);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const displayHour = hour % 12 || 12;
            return `${displayHour}:${minutes} ${ampm}`;
        }

        function showSuccessMessage(message) {
            alert(message);
        }

        // Close modals when clicking outside
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal(this.id);
                }
            });
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal.show').forEach(modal => {
                    closeModal(modal.id);
                });
            }
        });

        // Initial render
        renderAppointments();
    </script>
</body>
</html>