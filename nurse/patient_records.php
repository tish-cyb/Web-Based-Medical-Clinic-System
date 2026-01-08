<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurse Portal - Patient Records</title>
    
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

        .search-section {
            background: white;
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            margin-bottom: 25px;
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .search-input {
            flex: 1;
            padding: 12px 20px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 400;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(127, 29, 29, 0.1);
        }

        .btn-search {
            padding: 12px 30px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-search:hover {
            background-color: var(--primary-light);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(127, 29, 29, 0.3);
        }

        .records-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            overflow: hidden;
        }

        .records-header {
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
            font-size: 14px;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: var(--primary-soft);
        }

        .btn-edit-record {
            padding: 8px 20px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-edit-record:hover {
            background-color: var(--primary-light);
        }

        .no-results {
            padding: 60px 30px;
            text-align: center;
            color: var(--text-gray);
            font-size: 16px;
        }

        .no-results i {
            font-size: 48px;
            opacity: 0.3;
            margin-bottom: 15px;
            display: block;
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
            max-width: 700px;
            max-height: 90vh;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s ease;
            display: flex;
            flex-direction: column;
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
        }

        .modal-header h3 {
            margin: 0;
            font-size: 22px;
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
            overflow-y: auto;
            flex: 1;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
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

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .modal-footer {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .btn-modal {
            padding: 12px 28px;
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

        .btn-modal-save {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-modal-save:hover {
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

            .form-row {
                grid-template-columns: 1fr;
            }

            .search-section {
                flex-direction: column;
            }

            .search-input {
                width: 100%;
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
            <div class="nav-item active" data-page="records">
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
            <h2>Patient Records</h2>
            <p>Digital medical records and consultation history</p>
        </div>

        <div class="search-section">
            <input 
                type="text" 
                class="search-input" 
                id="searchInput"
                placeholder="Search patient records...">
            <button class="btn-search" onclick="filterRecords()">Search</button>
        </div>

        <div class="records-section">
            <div class="records-header">
                Recent Records
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Student Name</th>
                        <th>Consultation Type</th>
                        <th>Diagnosis</th>
                        <th>Treatment</th>
                        <th>Follow-up</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="recordsTableBody">
                    <!-- Records will be populated here -->
                </tbody>
            </table>
        </div>
    </main>

    <!-- Edit Record Modal -->
    <div id="editRecordModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Patient Record</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editRecordForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control" id="editDate" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Student Name</label>
                            <input type="text" class="form-control" id="editStudentName" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Consultation Type</label>
                            <select class="form-control" id="editConsultationType" required>
                                <option value="">Select Type</option>
                                <option value="General Consultation">General Consultation</option>
                                <option value="Medical Clearance">Medical Clearance</option>
                                <option value="Follow-up">Follow-up</option>
                                <option value="Emergency">Emergency</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Follow-up Date</label>
                            <input type="text" class="form-control" id="editFollowUp" placeholder="e.g., Mar. 22, 2024 or N/A">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Diagnosis</label>
                        <textarea class="form-control" id="editDiagnosis" required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Treatment</label>
                        <textarea class="form-control" id="editTreatment" required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Additional Notes</label>
                        <textarea class="form-control" id="editNotes" placeholder="Any additional observations or recommendations..."></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-modal btn-modal-cancel" onclick="closeModal()">Cancel</button>
                        <button type="submit" class="btn-modal btn-modal-save">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sample patient records data
        let records = [
            { 
                id: 1, 
                date: 'Mar. 20, 2024', 
                student: 'Juan Dela Cruz', 
                type: 'General Consultation', 
                diagnosis: 'Common Cold', 
                treatment: 'Rest, Fluids, Paracetamol', 
                followUp: 'Mar. 22, 2024',
                notes: 'Patient advised to rest for 2-3 days'
            },
            { 
                id: 2, 
                date: 'May 20, 2025', 
                student: 'May Reyes', 
                type: 'General Consultation', 
                diagnosis: 'Common Cold', 
                treatment: 'Rest, Fluids, Paracetamol', 
                followUp: 'May 24, 2025',
                notes: 'Monitor temperature regularly'
            },
            { 
                id: 3, 
                date: 'Apr. 2, 2025', 
                student: 'John Garcia', 
                type: 'Medical Clearance', 
                diagnosis: 'Fit for Activities', 
                treatment: 'None Required', 
                followUp: 'N/A',
                notes: 'Annual medical clearance completed'
            },
            { 
                id: 4, 
                date: 'Apr. 5, 2025', 
                student: 'Mark Cruz', 
                type: 'Medical Clearance', 
                diagnosis: 'Fit for Activities', 
                treatment: 'None Required', 
                followUp: 'N/A',
                notes: 'No health concerns noted'
            },
            { 
                id: 5, 
                date: 'Mar. 10, 2025', 
                student: 'Maria Santos', 
                type: 'Follow-up', 
                diagnosis: 'Allergic Reaction - Resolved', 
                treatment: 'Antihistamine continued', 
                followUp: 'N/A',
                notes: 'Symptoms have fully resolved'
            },
            { 
                id: 6, 
                date: 'Feb. 28, 2025', 
                student: 'Grace Lopez', 
                type: 'General Consultation', 
                diagnosis: 'Headache, Tension Type', 
                treatment: 'Pain reliever, Rest', 
                followUp: 'Mar. 5, 2025',
                notes: 'Advised to manage stress and get adequate sleep'
            }
        ];

        let allRecords = [...records];
        let currentEditId = null;

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

        // Render records table
        function renderRecords(recordsToRender = records) {
            const tbody = document.getElementById('recordsTableBody');
            
            if (recordsToRender.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="no-results">
                            <i class="bi bi-search"></i>
                            No records found matching your search.
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = recordsToRender.map(record => `
                <tr>
                    <td>${record.date}</td>
                    <td>${record.student}</td>
                    <td>${record.type}</td>
                    <td>${record.diagnosis}</td>
                    <td>${record.treatment}</td>
                    <td>${record.followUp}</td>
                    <td>
                        <button class="btn-edit-record" onclick="openEditModal(${record.id})">
                            Edit Record
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        // Filter records based on search
        function filterRecords() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            
            if (!searchTerm) {
                records = [...allRecords];
            } else {
                records = allRecords.filter(record => {
                    return record.student.toLowerCase().includes(searchTerm) ||
                           record.diagnosis.toLowerCase().includes(searchTerm) ||
                           record.type.toLowerCase().includes(searchTerm) ||
                           record.date.toLowerCase().includes(searchTerm);
                });
            }
            
            renderRecords(records);
        }

        // Search on Enter key
        document.getElementById('searchInput').addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                filterRecords();
            }
        });

        // Open edit modal
        function openEditModal(id) {
            currentEditId = id;
            const record = allRecords.find(r => r.id === id);
            
            if (record) {
                // Convert date to YYYY-MM-DD format for input
                const dateParts = record.date.split(' ');
                const months = ['Jan.', 'Feb.', 'Mar.', 'Apr.', 'May', 'June', 'July', 'Aug.', 'Sept.', 'Oct.', 'Nov.', 'Dec.'];
                const monthIndex = months.indexOf(dateParts[0]) + 1;
                const day = dateParts[1].replace(',', '').padStart(2, '0');
                const formattedDate = `${dateParts[2]}-${monthIndex.toString().padStart(2, '0')}-${day}`;
                
                document.getElementById('editDate').value = formattedDate;
                document.getElementById('editStudentName').value = record.student;
                document.getElementById('editConsultationType').value = record.type;
                document.getElementById('editDiagnosis').value = record.diagnosis;
                document.getElementById('editTreatment').value = record.treatment;
                document.getElementById('editFollowUp').value = record.followUp;
                document.getElementById('editNotes').value = record.notes || '';
                
                document.getElementById('editRecordModal').classList.add('show');
                document.body.style.overflow = 'hidden';
            }
        }

        // Close modal
        function closeModal() {
            document.getElementById('editRecordModal').classList.remove('show');
            document.body.style.overflow = 'auto';
            currentEditId = null;
        }

        // Handle form submission
        document.getElementById('editRecordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const record = allRecords.find(r => r.id === currentEditId);
            
            if (record) {
                // Convert date back to display format
                const dateInput = document.getElementById('editDate').value;
                const dateObj = new Date(dateInput);
                const months = ['Jan.', 'Feb.', 'Mar.', 'Apr.', 'May', 'June', 'July', 'Aug.', 'Sept.', 'Oct.', 'Nov.', 'Dec.'];
                const formattedDate = `${months[dateObj.getMonth()]} ${dateObj.getDate()}, ${dateObj.getFullYear()}`;
                
                record.date = formattedDate;
                record.student = document.getElementById('editStudentName').value;
                record.type = document.getElementById('editConsultationType').value;
                record.diagnosis = document.getElementById('editDiagnosis').value;
                record.treatment = document.getElementById('editTreatment').value;
                record.followUp = document.getElementById('editFollowUp').value;
                record.notes = document.getElementById('editNotes').value;
                
                // Update the filtered records as well
                const filteredRecord = records.find(r => r.id === currentEditId);
                if (filteredRecord) {
                    Object.assign(filteredRecord, record);
                }
                
                renderRecords(records);
                closeModal();
                alert('Record updated successfully!');
            }
        });

        // Close modal when clicking outside
        document.getElementById('editRecordModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

        // Initial render
        renderRecords();
    </script>
</body>
</html>