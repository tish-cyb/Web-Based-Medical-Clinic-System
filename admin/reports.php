<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Reports - Admin Portal</title>
    
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
            margin-bottom: 30px;
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
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

        .generate-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            overflow: hidden;
            margin-bottom: 40px;
        }

        .generate-header {
            background: linear-gradient(90deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
            color: white;
            padding: 22px 30px;
            font-size: 20px;
            font-weight: 600;
        }

        .generate-body {
            padding: 35px 30px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            color: var(--text-dark);
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .form-group input {
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .btn-generate {
            background: linear-gradient(90deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
            color: white;
            padding: 12px 32px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
        }

        .btn-generate:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(127, 29, 29, 0.3);
        }

        .results-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            overflow: hidden;
            display: none;
        }

        .results-section.show {
            display: block;
        }

        .results-header {
            background: linear-gradient(90deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
            color: white;
            padding: 22px 30px;
            font-size: 20px;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .export-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-export {
            background: white;
            color: var(--primary-color);
            padding: 8px 20px;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-export:hover {
            background: var(--primary-soft);
            transform: translateY(-1px);
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

        .status-completed {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .no-results {
            padding: 60px 30px;
            text-align: center;
            color: var(--text-gray);
            font-size: 16px;
        }

        @media (max-width: 1400px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
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

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .nav-item span {
                display: none;
            }

            .table {
                font-size: 13px;
            }

            .table thead th,
            .table tbody td {
                padding: 12px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h1>Admin Portal</h1>
            <p>System Management</p>
        </div>
        
        <nav class="sidebar-nav">
            <a href="admin_dashboard.php" class="nav-item" style="text-decoration: none; color: inherit;">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
            <a href="user_management.php" class="nav-item" style="text-decoration: none; color: inherit;">
                <i class="bi bi-people"></i>
                <span>User Management</span>
            </a>
            <a href="reports.php" class="nav-item active" style="text-decoration: none; color: inherit;">
                <i class="bi bi-file-earmark-text"></i>
                <span>Reports</span>
            </a>
            <a href="system_settings.php" class="nav-item" style="text-decoration: none; color: inherit;">
                <i class="bi bi-gear"></i>
                <span>System Settings</span>
            </a>
            <a href="profile.php" class="nav-item" style="text-decoration: none; color: inherit;">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </a>
        </nav>
    </div>

    <main class="main-content">
        <div class="page-header">
            <h2>System Reports</h2>
            <p>Analytics and performance reports</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">342</div>
                <div class="stat-label">Monthly Appointments</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">156</div>
                <div class="stat-label">Certificates Issued</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">89%</div>
                <div class="stat-label">Appointment Rate</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">12min</div>
                <div class="stat-label">Average Wait Time</div>
            </div>
        </div>

        <div class="generate-section">
            <div class="generate-header">
                Generate Reports
            </div>
            <div class="generate-body">
                <form id="reportForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="studentName">Student Name</label>
                            <input type="text" id="studentName" placeholder="Enter Student Name">
                        </div>
                        <div class="form-group">
                            <label for="studentNumber">Student Number</label>
                            <input type="text" id="studentNumber" placeholder="e.g., 2021-12345-MN-0">
                        </div>
                        <div class="form-group">
                            <label for="consultationDate">Consultation Date</label>
                            <input type="date" id="consultationDate">
                        </div>
                    </div>
                    <button type="button" class="btn-generate" id="generateBtn">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        Generate Report
                    </button>
                </form>
            </div>
        </div>

        <div class="results-section" id="resultsSection">
            <div class="results-header">
                <span>Report Results</span>
                <div class="export-buttons">
                    <button class="btn-export" id="exportExcelBtn">
                        <i class="bi bi-file-earmark-excel"></i> Excel
                    </button>
                    <button class="btn-export" id="exportPdfBtn">
                        <i class="bi bi-file-earmark-pdf"></i> PDF
                    </button>
                    <button class="btn-export" id="exportCsvBtn">
                        <i class="bi bi-filetype-csv"></i> CSV
                    </button>
                    <button class="btn-export" id="printBtn">
                        <i class="bi bi-printer"></i> Print
                    </button>
                </div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Student Number</th>
                        <th>Consultation Date</th>
                        <th>Diagnosis</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="resultsBody">
                    <!-- Results will be inserted here -->
                </tbody>
            </table>
        </div>
    </main>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <!-- jsPDF for PDF generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <!-- jsPDF AutoTable for tables in PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
    <!-- SheetJS for Excel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    
    <script>
        // Sample report data
        const reportData = [
            {
                name: "Juan Dela Cruz",
                number: "2021-12345-MN-0",
                date: "2024-03-20",
                diagnosis: "Annual Physical Checkup",
                status: "Completed"
            },
            {
                name: "Maria Garcia",
                number: "2021-54321-MN-0",
                date: "2024-03-19",
                diagnosis: "Medical Clearance",
                status: "Completed"
            },
            {
                name: "Carlos Mendoza",
                number: "2022-67890-MN-0",
                date: "2024-03-18",
                diagnosis: "Consultation",
                status: "Pending"
            },
            {
                name: "Ana Reyes",
                number: "2021-11111-MN-0",
                date: "2024-03-17",
                diagnosis: "Follow-up Checkup",
                status: "Completed"
            },
            {
                name: "Pedro Martinez",
                number: "2023-22222-MN-0",
                date: "2024-03-16",
                diagnosis: "Medical Certificate",
                status: "Completed"
            }
        ];

        let currentFilteredData = [];

        // Format date for display
        function formatDate(dateString) {
            const date = new Date(dateString);
            const months = ['January', 'February', 'March', 'April', 'May', 'June', 
                          'July', 'August', 'September', 'October', 'November', 'December'];
            return `${months[date.getMonth()]} ${date.getDate()}, ${date.getFullYear()}`;
        }

        // Generate report
        document.getElementById('generateBtn').addEventListener('click', function() {
            const studentName = document.getElementById('studentName').value.trim().toLowerCase();
            const studentNumber = document.getElementById('studentNumber').value.trim();
            const consultationDate = document.getElementById('consultationDate').value;

            // Filter results
            currentFilteredData = reportData.filter(record => {
                const matchesName = !studentName || record.name.toLowerCase().includes(studentName);
                const matchesNumber = !studentNumber || record.number.includes(studentNumber);
                const matchesDate = !consultationDate || record.date === consultationDate;
                
                return matchesName && matchesNumber && matchesDate;
            });

            // Display results
            const resultsSection = document.getElementById('resultsSection');
            const resultsBody = document.getElementById('resultsBody');

            if (currentFilteredData.length === 0) {
                resultsBody.innerHTML = '<tr><td colspan="5" class="no-results">No records found matching your criteria</td></tr>';
            } else {
                resultsBody.innerHTML = currentFilteredData.map(record => `
                    <tr>
                        <td>${record.name}</td>
                        <td>${record.number}</td>
                        <td>${formatDate(record.date)}</td>
                        <td>${record.diagnosis}</td>
                        <td><span class="status-badge status-${record.status.toLowerCase()}">${record.status}</span></td>
                    </tr>
                `).join('');
            }

            resultsSection.classList.add('show');
            resultsSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });

        // Export to Excel
        document.getElementById('exportExcelBtn').addEventListener('click', function() {
            if (currentFilteredData.length === 0) {
                alert('No data to export. Please generate a report first.');
                return;
            }

            // Prepare data for Excel
            const excelData = currentFilteredData.map(record => ({
                'Student Name': record.name,
                'Student Number': record.number,
                'Consultation Date': formatDate(record.date),
                'Diagnosis': record.diagnosis,
                'Status': record.status
            }));

            // Create workbook and worksheet
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.json_to_sheet(excelData);

            // Set column widths
            ws['!cols'] = [
                { wch: 25 },
                { wch: 20 },
                { wch: 20 },
                { wch: 30 },
                { wch: 15 }
            ];

            // Add worksheet to workbook
            XLSX.utils.book_append_sheet(wb, ws, 'Medical Reports');

            // Generate filename with current date
            const filename = `Medical_Reports_${new Date().toISOString().split('T')[0]}.xlsx`;

            // Save file
            XLSX.writeFile(wb, filename);
        });

        // Export to PDF
        document.getElementById('exportPdfBtn').addEventListener('click', function() {
            if (currentFilteredData.length === 0) {
                alert('No data to export. Please generate a report first.');
                return;
            }

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Add title
            doc.setFontSize(18);
            doc.setTextColor(127, 29, 29);
            doc.text('Medical Reports', 14, 22);

            // Add date
            doc.setFontSize(10);
            doc.setTextColor(107, 114, 128);
            doc.text(`Generated on: ${formatDate(new Date().toISOString().split('T')[0])}`, 14, 30);

            // Prepare table data
            const tableData = currentFilteredData.map(record => [
                record.name,
                record.number,
                formatDate(record.date),
                record.diagnosis,
                record.status
            ]);

            // Add table
            doc.autoTable({
                head: [['Student Name', 'Student Number', 'Consultation Date', 'Diagnosis', 'Status']],
                body: tableData,
                startY: 35,
                theme: 'grid',
                headStyles: {
                    fillColor: [127, 29, 29],
                    textColor: 255,
                    fontStyle: 'bold'
                },
                alternateRowStyles: {
                    fillColor: [254, 242, 242]
                },
                margin: { top: 35 }
            });

            // Save PDF
            const filename = `Medical_Reports_${new Date().toISOString().split('T')[0]}.pdf`;
            doc.save(filename);
        });

        // Export to CSV
        document.getElementById('exportCsvBtn').addEventListener('click', function() {
            if (currentFilteredData.length === 0) {
                alert('No data to export. Please generate a report first.');
                return;
            }

            // Prepare CSV content
            const headers = ['Student Name', 'Student Number', 'Consultation Date', 'Diagnosis', 'Status'];
            const csvRows = [headers.join(',')];

            currentFilteredData.forEach(record => {
                const row = [
                    `"${record.name}"`,
                    `"${record.number}"`,
                    `"${formatDate(record.date)}"`,
                    `"${record.diagnosis}"`,
                    `"${record.status}"`
                ];
                csvRows.push(row.join(','));
            });

            const csvContent = csvRows.join('\n');

            // Create blob and download
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            
            link.setAttribute('href', url);
            link.setAttribute('download', `Medical_Reports_${new Date().toISOString().split('T')[0]}.csv`);
            link.style.visibility = 'hidden';
            
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });

        // Print functionality
        document.getElementById('printBtn').addEventListener('click', function() {
            if (currentFilteredData.length === 0) {
                alert('No data to print. Please generate a report first.');
                return;
            }

            // Create print window content
            const printWindow = window.open('', '', 'height=600,width=800');
            
            printWindow.document.write('<html><head><title>Medical Reports</title>');
            printWindow.document.write('<style>');
            printWindow.document.write('body { font-family: Arial, sans-serif; padding: 20px; }');
            printWindow.document.write('h1 { color: #7f1d1d; margin-bottom: 10px; }');
            printWindow.document.write('.date { color: #6b7280; margin-bottom: 20px; }');
            printWindow.document.write('table { width: 100%; border-collapse: collapse; margin-top: 20px; }');
            printWindow.document.write('th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }');
            printWindow.document.write('th { background-color: #7f1d1d; color: white; font-weight: bold; }');
            printWindow.document.write('tr:nth-child(even) { background-color: #fef2f2; }');
            printWindow.document.write('</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write('<h1>Medical Reports</h1>');
            printWindow.document.write(`<p class="date">Generated on: ${formatDate(new Date().toISOString().split('T')[0])}</p>`);
            printWindow.document.write('<table>');
            printWindow.document.write('<thead><tr>');
            printWindow.document.write('<th>Student Name</th><th>Student Number</th><th>Consultation Date</th><th>Diagnosis</th><th>Status</th>');
            printWindow.document.write('</tr></thead><tbody>');
            
            currentFilteredData.forEach(record => {
                printWindow.document.write('<tr>');
                printWindow.document.write(`<td>${record.name}</td>`);
                printWindow.document.write(`<td>${record.number}</td>`);
                printWindow.document.write(`<td>${formatDate(record.date)}</td>`);
                printWindow.document.write(`<td>${record.diagnosis}</td>`);
                printWindow.document.write(`<td>${record.status}</td>`);
                printWindow.document.write('</tr>');
            });
            
            printWindow.document.write('</tbody></table>');
            printWindow.document.write('</body></html>');
            
            printWindow.document.close();
            printWindow.focus();
            
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 250);
        });

        // Set max date to today
        document.getElementById('consultationDate').max = new Date().toISOString().split('T')[0];
    </script>
</body>
</html>