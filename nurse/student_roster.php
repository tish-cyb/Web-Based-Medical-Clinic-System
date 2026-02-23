<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurse Portal - Student Roster</title>
    
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

        .filter-section {
            background: white;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            margin-bottom: 25px;
        }

        .filter-row {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-input {
            flex: 1;
            min-width: 250px;
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

        .filter-select {
            padding: 12px 20px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            background-color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 150px;
        }

        .filter-select:focus {
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

        .student-list-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            overflow: hidden;
        }

        .student-list-header {
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

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: var(--primary-soft);
        }

        .btn-view-records {
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

        .btn-view-records:hover {
            background-color: var(--primary-light);
        }

        .no-results {
            padding: 60px 30px;
            text-align: center;
            color: var(--text-gray);
            font-size: 16px;
        }

        .results-count {
            padding: 15px 30px;
            background-color: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            color: var(--text-gray);
            font-size: 14px;
            font-weight: 500;
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
            max-width: 900px;
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
            font-size: 24px;
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

        .student-info {
            background-color: var(--primary-soft);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .records-section {
            margin-top: 20px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            font-size: 20px;
        }

        .record-card {
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .record-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(127, 29, 29, 0.1);
        }

        .record-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .record-date {
            font-size: 14px;
            font-weight: 600;
            color: var(--primary-color);
        }

        .record-type {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            background-color: #dbeafe;
            color: #1e40af;
        }

        .record-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-top: 10px;
        }

        .record-detail {
            font-size: 13px;
            color: var(--text-dark);
        }

        .record-detail strong {
            color: var(--text-gray);
            font-weight: 600;
        }

        .no-records {
            text-align: center;
            padding: 40px;
            color: var(--text-gray);
        }

        .no-records i {
            font-size: 48px;
            opacity: 0.3;
            margin-bottom: 15px;
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

            .filter-row {
                flex-direction: column;
            }

            .search-input, .filter-select {
                width: 100%;
            }

            .table {
                font-size: 12px;
            }

            .table thead th,
            .table tbody td {
                padding: 12px 15px;
            }

            .modal-content {
                width: 95%;
                max-height: 95vh;
            }

            .student-info {
                grid-template-columns: 1fr;
            }

            .record-details {
                grid-template-columns: 1fr;
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
            <div class="nav-item active" data-page="roster">
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
            <div class="nav-item" data-page="profile">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </div>
        </nav>
    </div>

    <main class="main-content">
        <div class="page-header">
            <h2>Student Roster</h2>
            <p>Manage student information and records</p>
        </div>

        <div class="filter-section">
            <div class="filter-row">
                <input 
                    type="text" 
                    class="search-input" 
                    id="searchInput"
                    placeholder="Search by name or student number">
                
                <select class="filter-select" id="yearFilter">
                    <option value="">All Year Levels</option>
                    <option value="1st">1st Year</option>
                    <option value="2nd">2nd Year</option>
                    <option value="3rd">3rd Year</option>
                    <option value="4th">4th Year</option>
                </select>
                
                <select class="filter-select" id="genderFilter">
                    <option value="">All Genders</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
                
                <select class="filter-select" id="courseFilter">
                    <option value="">All Courses</option>
                    <option value="DIT">DIT</option>
                    <option value="DOMT">DOMT</option>
                    <option value="BSIT">BSIT</option>
                    <option value="BSCS">BSCS</option>
                </select>
                
                <button class="btn-search" id="searchBtn">Search</button>
            </div>
        </div>

        <div class="student-list-section">
            <div class="student-list-header">
                Student List
            </div>
            <div class="results-count" id="resultsCount">
                Showing <span id="resultNumber">12</span> students
            </div>
            <div id="tableContainer">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Student No.</th>
                            <th>Name</th>
                            <th>Program</th>
                            <th>Year</th>
                            <th>Gender</th>
                            <th>Last Visit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="studentTableBody">
                        <!-- Students will be populated here -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Student Records Modal -->
    <div id="studentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Student Medical Records</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="student-info" id="studentInfo">
                    <!-- Student info will be populated here -->
                </div>
                
                <div class="records-section">
                    <div class="section-title">
                        <i class="bi bi-clock-history"></i>
                        Medical History
                    </div>
                    <div id="recordsList">
                        <!-- Records will be populated here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sample student data with medical records
        const students = [
            { 
                id: 1, 
                studentNo: '2023-12345-MN-0', 
                name: 'Juan Dela Cruz', 
                program: 'DIT', 
                year: '3rd', 
                gender: 'Male', 
                lastVisit: 'Mar. 15, 2025',
                records: [
                    { date: 'Mar. 15, 2025', type: 'General Consultation', complaint: 'Headache', diagnosis: 'Tension Headache', treatment: 'Paracetamol 500mg', notes: 'Advised to rest' },
                    { date: 'Jan. 10, 2025', type: 'Medical Clearance', complaint: 'None', diagnosis: 'Fit for Physical Activities', treatment: 'None', notes: 'Annual medical clearance' },
                    { date: 'Nov. 5, 2024', type: 'General Consultation', complaint: 'Cough and Colds', diagnosis: 'Upper Respiratory Tract Infection', treatment: 'Cetirizine, Vitamin C', notes: 'Follow-up if symptoms persist' }
                ]
            },
            { 
                id: 2, 
                studentNo: '2023-56789-MN-0', 
                name: 'Maria Santos', 
                program: 'DOMT', 
                year: '3rd', 
                gender: 'Female', 
                lastVisit: 'May 20, 2025',
                records: [
                    { date: 'May 20, 2025', type: 'General Consultation', complaint: 'Fever', diagnosis: 'Viral Fever', treatment: 'Paracetamol, Rest', notes: 'Monitor temperature' },
                    { date: 'Feb. 12, 2025', type: 'Injury Report', complaint: 'Sprained Ankle', diagnosis: 'Mild Ankle Sprain', treatment: 'Cold compress, Elastic bandage', notes: 'Avoid physical activities for 3 days' }
                ]
            },
            { 
                id: 3, 
                studentNo: '2023-76543-MN-0', 
                name: 'John Garcia', 
                program: 'DOMT', 
                year: '1st', 
                gender: 'Male', 
                lastVisit: 'Aug. 1, 2025',
                records: [
                    { date: 'Aug. 1, 2025', type: 'Medical Clearance', complaint: 'None', diagnosis: 'Fit for Physical Activities', treatment: 'None', notes: 'First-year medical clearance' }
                ]
            },
            { 
                id: 4, 
                studentNo: '2023-11123-MN-0', 
                name: 'Grace Cruz', 
                program: 'DIT', 
                year: '2nd', 
                gender: 'Female', 
                lastVisit: 'Sept. 25, 2025',
                records: [
                    { date: 'Sept. 25, 2025', type: 'General Consultation', complaint: 'Stomach Pain', diagnosis: 'Gastritis', treatment: 'Antacid, Light diet', notes: 'Avoid spicy foods' },
                    { date: 'July 15, 2025', type: 'General Consultation', complaint: 'Dizziness', diagnosis: 'Low Blood Pressure', treatment: 'Advised to increase fluid intake', notes: 'Monitor blood pressure' }
                ]
            },
            { id: 5, studentNo: '2023-22334-MN-0', name: 'Pedro Reyes', program: 'BSIT', year: '4th', gender: 'Male', lastVisit: 'Jan. 5, 2026', records: [{ date: 'Jan. 5, 2026', type: 'Medical Clearance', complaint: 'None', diagnosis: 'Fit for Graduation', treatment: 'None', notes: 'Final year clearance' }] },
            { id: 6, studentNo: '2023-33445-MN-0', name: 'Ana Mendoza', program: 'BSCS', year: '1st', gender: 'Female', lastVisit: 'Dec. 10, 2025', records: [{ date: 'Dec. 10, 2025', type: 'General Consultation', complaint: 'Migraine', diagnosis: 'Migraine', treatment: 'Pain reliever', notes: 'Rest in dark room' }] },
            { id: 7, studentNo: '2023-44556-MN-0', name: 'Carlos Ramos', program: 'DIT', year: '2nd', gender: 'Male', lastVisit: 'Nov. 18, 2025', records: [{ date: 'Nov. 18, 2025', type: 'General Consultation', complaint: 'Allergic Reaction', diagnosis: 'Skin Allergy', treatment: 'Antihistamine', notes: 'Avoid allergen' }] },
            { id: 8, studentNo: '2023-55667-MN-0', name: 'Sofia Lopez', program: 'DOMT', year: '3rd', gender: 'Female', lastVisit: 'Oct. 22, 2025', records: [{ date: 'Oct. 22, 2025', type: 'Medical Clearance', complaint: 'None', diagnosis: 'Fit for Physical Activities', treatment: 'None', notes: 'Annual clearance' }] },
            { id: 9, studentNo: '2023-66778-MN-0', name: 'Miguel Torres', program: 'BSIT', year: '1st', gender: 'Male', lastVisit: 'Sept. 8, 2025', records: [{ date: 'Sept. 8, 2025', type: 'Injury Report', complaint: 'Cut on hand', diagnosis: 'Minor Laceration', treatment: 'Cleaned and bandaged', notes: 'Keep wound clean and dry' }] },
            { id: 10, studentNo: '2023-77889-MN-0', name: 'Isabella Flores', program: 'BSCS', year: '4th', gender: 'Female', lastVisit: 'Aug. 15, 2025', records: [{ date: 'Aug. 15, 2025', type: 'General Consultation', complaint: 'Back Pain', diagnosis: 'Muscle Strain', treatment: 'Pain reliever, Hot compress', notes: 'Improve posture' }] },
            { id: 11, studentNo: '2023-88990-MN-0', name: 'Rafael Diaz', program: 'DIT', year: '2nd', gender: 'Male', lastVisit: 'July 30, 2025', records: [{ date: 'July 30, 2025', type: 'General Consultation', complaint: 'Sore Throat', diagnosis: 'Pharyngitis', treatment: 'Throat lozenges, Antibiotics', notes: 'Complete antibiotic course' }] },
            { id: 12, studentNo: '2023-99001-MN-0', name: 'Carmen Rivera', program: 'DOMT', year: '3rd', gender: 'Female', lastVisit: 'June 12, 2025', records: [{ date: 'June 12, 2025', type: 'Medical Clearance', complaint: 'None', diagnosis: 'Fit for Physical Activities', treatment: 'None', notes: 'Semi-annual clearance' }] }
        ];

        let filteredStudents = [...students];

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
                    // Navigate to the corresponding PHP file
                    window.location.href = filename;
                }
            });
        });

        // Render students table
        function renderStudents(studentsToRender) {
            const tbody = document.getElementById('studentTableBody');
            const resultNumber = document.getElementById('resultNumber');
            
            if (studentsToRender.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="no-results">
                            <i class="bi bi-search" style="font-size: 48px; opacity: 0.3; display: block; margin-bottom: 15px;"></i>
                            No students found matching your search criteria.
                        </td>
                    </tr>
                `;
                resultNumber.textContent = '0';
                return;
            }

            tbody.innerHTML = studentsToRender.map(student => `
                <tr>
                    <td>${student.studentNo}</td>
                    <td>${student.name}</td>
                    <td>${student.program}</td>
                    <td>${student.year}</td>
                    <td>${student.gender}</td>
                    <td>${student.lastVisit}</td>
                    <td>
                        <button class="btn-view-records" onclick="viewRecords('${student.name}', '${student.studentNo}')">
                            View Records
                        </button>
                    </td>
                </tr>
            `).join('');

            resultNumber.textContent = studentsToRender.length;
        }

        // Filter function
        function filterStudents() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const yearFilter = document.getElementById('yearFilter').value;
            const genderFilter = document.getElementById('genderFilter').value;
            const courseFilter = document.getElementById('courseFilter').value;

            filteredStudents = students.filter(student => {
                const matchesSearch = student.name.toLowerCase().includes(searchTerm) || 
                                    student.studentNo.toLowerCase().includes(searchTerm);
                const matchesYear = !yearFilter || student.year === yearFilter;
                const matchesGender = !genderFilter || student.gender === genderFilter;
                const matchesCourse = !courseFilter || student.program === courseFilter;

                return matchesSearch && matchesYear && matchesGender && matchesCourse;
            });

            renderStudents(filteredStudents);
        }

        // Search button click
        document.getElementById('searchBtn').addEventListener('click', filterStudents);

        // Real-time search on Enter key
        document.getElementById('searchInput').addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                filterStudents();
            }
        });

        // Filter change listeners
        document.getElementById('yearFilter').addEventListener('change', filterStudents);
        document.getElementById('genderFilter').addEventListener('change', filterStudents);
        document.getElementById('courseFilter').addEventListener('change', filterStudents);

        // View records function - Open Modal
        function viewRecords(name, studentNo) {
            const student = students.find(s => s.studentNo === studentNo);
            if (!student) return;

            // Populate student info
            const studentInfo = document.getElementById('studentInfo');
            studentInfo.innerHTML = `
                <div class="info-item">
                    <span class="info-label">Student Number</span>
                    <span class="info-value">${student.studentNo}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Full Name</span>
                    <span class="info-value">${student.name}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Program</span>
                    <span class="info-value">${student.program}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Year Level</span>
                    <span class="info-value">${student.year}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Gender</span>
                    <span class="info-value">${student.gender}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Last Visit</span>
                    <span class="info-value">${student.lastVisit}</span>
                </div>
            `;

            // Populate medical records
            const recordsList = document.getElementById('recordsList');
            if (student.records && student.records.length > 0) {
                recordsList.innerHTML = student.records.map(record => `
                    <div class="record-card">
                        <div class="record-header">
                            <span class="record-date"><i class="bi bi-calendar3"></i> ${record.date}</span>
                            <span class="record-type">${record.type}</span>
                        </div>
                        <div class="record-details">
                            <div class="record-detail">
                                <strong>Complaint:</strong> ${record.complaint}
                            </div>
                            <div class="record-detail">
                                <strong>Diagnosis:</strong> ${record.diagnosis}
                            </div>
                            <div class="record-detail">
                                <strong>Treatment:</strong> ${record.treatment}
                            </div>
                            <div class="record-detail">
                                <strong>Notes:</strong> ${record.notes}
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                recordsList.innerHTML = `
                    <div class="no-records">
                        <i class="bi bi-inbox"></i>
                        <p>No medical records found for this student.</p>
                    </div>
                `;
            }

            // Show modal
            document.getElementById('studentModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        // Close modal function
        function closeModal() {
            document.getElementById('studentModal').classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.getElementById('studentModal').addEventListener('click', function(e) {
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
        renderStudents(students);
    </script>
</body>
</html>