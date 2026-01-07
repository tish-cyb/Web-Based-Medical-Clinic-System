<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurse Portal - Clinical Management</title>
    
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

        .schedule-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            overflow: hidden;
        }

        .schedule-header {
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

        .status-waiting {
            background-color: #fff3cd;
            color: #856404;
        }

        .btn-action {
            padding: 8px 20px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-start {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-start:hover {
            background-color: var(--primary-light);
        }

        .btn-details {
            background-color: #e5e7eb;
            color: var(--text-dark);
        }

        .btn-details:hover {
            background-color: #d1d5db;
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
            <h1>Nurse Portal</h1>
            <p>Clinical Management</p>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-item active">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </div>
            <div class="nav-item">
                <i class="bi bi-people"></i>
                <span>Student Roster</span>
            </div>
            <div class="nav-item">
                <i class="bi bi-calendar-check"></i>
                <span>Appointments</span>
            </div>
            <div class="nav-item">
                <i class="bi bi-folder2-open"></i>
                <span>Patient Records</span>
            </div>
            <div class="nav-item">
                <i class="bi bi-chat-dots"></i>
                <span>Consultations</span>
            </div>
            <div class="nav-item">
                <i class="bi bi-file-earmark-medical"></i>
                <span>Medical Certificate</span>
            </div>
            <div class="nav-item">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </div>
        </nav>
    </div>

    <main class="main-content">
        <div class="welcome-section">
            <h2>Welcome to Dashboard!</h2>
            <p>Clinical Operations Overview</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">18</div>
                <div class="stat-label">Monthly Appointments</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">1,247</div>
                <div class="stat-label">Total Students</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">42</div>
                <div class="stat-label">Pending Records</div>
            </div>
        </div>

        <div class="schedule-section">
            <div class="schedule-header">
                Today's Schedule
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Student Name</th>
                        <th>Student Number</th>
                        <th>Purpose</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>9:00 AM</td>
                        <td>Juan Dela Cruz</td>
                        <td>2023-12345-MN-0</td>
                        <td>Medical Clearance</td>
                        <td><span class="status-badge status-confirmed">Confirmed</span></td>
                        <td><button class="btn-action btn-start">Start Consultation</button></td>
                    </tr>
                    <tr>
                        <td>10:00 AM</td>
                        <td>Maria Santos</td>
                        <td>2023-56789-MN-0</td>
                        <td>General Consultation</td>
                        <td><span class="status-badge status-waiting">Waiting</span></td>
                        <td><button class="btn-action btn-details">View Details</button></td>
                    </tr>
                    <tr>
                        <td>10:30 AM</td>
                        <td>John Garcia</td>
                        <td>2023-76543-MN-0</td>
                        <td>General Consultation</td>
                        <td><span class="status-badge status-waiting">Waiting</span></td>
                        <td><button class="btn-action btn-details">View Details</button></td>
                    </tr>
                    <tr>
                        <td>11:30 AM</td>
                        <td>Grace Cruz</td>
                        <td>2023-11123-MN-0</td>
                        <td>General Consultation</td>
                        <td><span class="status-badge status-waiting">Waiting</span></td>
                        <td><button class="btn-action btn-details">View Details</button></td>
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

        // Button interactions
        document.querySelectorAll('.btn-action').forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                const studentName = row.cells[1].textContent;
                const action = this.classList.contains('btn-start') ? 'consultation' : 'details';
                
                if (action === 'consultation') {
                    alert(`Starting consultation for ${studentName}`);
                } else {
                    alert(`Viewing details for ${studentName}`);
                }
            });
        });
    </script>
</body>
</html>