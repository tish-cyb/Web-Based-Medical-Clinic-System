<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurse Portal - Medical Certificate</title>
    
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

        .certificate-form-section {
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
            min-height: 100px;
        }

        select.form-control {
            cursor: pointer;
        }

        .btn-generate {
            padding: 14px 40px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-left: auto;
        }

        .btn-generate:hover {
            background-color: var(--primary-light);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(127, 29, 29, 0.3);
        }

        .required {
            color: #ef4444;
            margin-left: 4px;
        }

        /* Certificate Preview Modal */
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
            max-width: 800px;
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

        .certificate-preview {
            border: 2px solid #e5e7eb;
            padding: 40px;
            border-radius: 8px;
            background-color: white;
            font-family: 'Times New Roman', serif;
        }

        .cert-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid var(--primary-color);
            padding-bottom: 20px;
        }

        .cert-title {
            font-size: 28px;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .cert-clinic {
            font-size: 18px;
            color: var(--text-dark);
            font-weight: 600;
        }

        .cert-body {
            margin: 30px 0;
            line-height: 2;
            font-size: 15px;
            color: var(--text-dark);
        }

        .cert-field {
            font-weight: bold;
            color: var(--primary-color);
        }

        .cert-footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .signature-section {
            text-align: center;
        }

        .signature-line {
            border-top: 2px solid var(--text-dark);
            width: 250px;
            margin-top: 50px;
            padding-top: 8px;
            font-weight: 600;
        }

        .modal-footer {
            padding: 20px 30px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .btn-modal {
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

        .btn-modal-secondary {
            background-color: #e5e7eb;
            color: var(--text-dark);
        }

        .btn-modal-secondary:hover {
            background-color: #d1d5db;
        }

        .btn-modal-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-modal-primary:hover {
            background-color: var(--primary-light);
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

            .certificate-preview {
                padding: 20px;
            }

            .cert-title {
                font-size: 22px;
            }

            .cert-clinic {
                font-size: 16px;
            }

            .cert-footer {
                flex-direction: column;
                gap: 30px;
            }

            .modal-footer {
                flex-direction: column;
            }

            .btn-modal {
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
            <div class="nav-item active" data-page="certificate">
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
            <h2>Medical Certificate</h2>
            <p>Fill up and generate medical certificate information</p>
        </div>

        <div class="certificate-form-section">
            <div class="form-header">
                Medical Certificate Information
            </div>
            <div class="form-body">
                <form id="certificateForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Issue Date<span class="required">*</span></label>
                            <input type="date" class="form-control" id="issueDate" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Student Name<span class="required">*</span></label>
                            <input type="text" class="form-control" id="studentName" placeholder="Enter Student Name" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Clinic Name</label>
                            <input type="text" class="form-control" id="clinicName" value="PUP Ttech Clinic" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Diagnosis<span class="required">*</span></label>
                        <textarea class="form-control" id="diagnosis" placeholder="Enter diagnosis or assessment" required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Remarks</label>
                        <textarea class="form-control" id="remarks" placeholder="Describe the patient's main complaint or symptoms"></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Purpose<span class="required">*</span></label>
                            <select class="form-control" id="purpose" required>
                                <option value="">Select Purpose</option>
                                <option value="Sick Leave">Sick Leave</option>
                                <option value="Medical Clearance">Medical Clearance</option>
                                <option value="Fitness to Work/Study">Fitness to Work/Study</option>
                                <option value="Excuse from PE/Activities">Excuse from PE/Activities</option>
                                <option value="Legal/Court Requirements">Legal/Court Requirements</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Clinic Physician<span class="required">*</span></label>
                            <input type="text" class="form-control" id="clinicPhysician" placeholder="Dr. Juan Cruz MD" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">License Number<span class="required">*</span></label>
                            <input type="text" class="form-control" id="licenseNumber" placeholder="1234-1234-MD" required>
                        </div>
                    </div>

                    <div class="form-row two-col">
                        <div class="form-group">
                            <label class="form-label">Days of Rest (if applicable)</label>
                            <input type="number" class="form-control" id="daysOfRest" placeholder="Number of days" min="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Valid Until</label>
                            <input type="date" class="form-control" id="validUntil">
                        </div>
                    </div>

                    <button type="submit" class="btn-generate">
                        <i class="bi bi-file-earmark-plus"></i>
                        Generate Medical Certificate
                    </button>
                </form>
            </div>
        </div>
    </main>

    <!-- Certificate Preview Modal -->
    <div id="certificateModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Medical Certificate Preview</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="certificate-preview" id="certificatePreview">
                    <!-- Certificate content will be generated here -->
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-modal btn-modal-secondary" onclick="closeModal()">
                    <i class="bi bi-x-circle"></i>
                    Close
                </button>
                <button class="btn-modal btn-modal-primary" onclick="printCertificate()">
                    <i class="bi bi-printer"></i>
                    Print Certificate
                </button>
                <button class="btn-modal btn-modal-primary" onclick="downloadCertificate()">
                    <i class="bi bi-download"></i>
                    Download PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Success Alert -->
    <div id="successAlert" class="success-alert">
        <i class="bi bi-check-circle-fill"></i>
        <div class="success-content">
            <div class="success-title">Certificate Generated!</div>
            <div class="success-message">Medical certificate has been successfully created.</div>
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
        document.getElementById('issueDate').valueAsDate = new Date();

        // Form submission
        document.getElementById('certificateForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            const formData = {
                issueDate: document.getElementById('issueDate').value,
                studentName: document.getElementById('studentName').value,
                clinicName: document.getElementById('clinicName').value,
                diagnosis: document.getElementById('diagnosis').value,
                remarks: document.getElementById('remarks').value,
                purpose: document.getElementById('purpose').value,
                clinicPhysician: document.getElementById('clinicPhysician').value,
                licenseNumber: document.getElementById('licenseNumber').value,
                daysOfRest: document.getElementById('daysOfRest').value,
                validUntil: document.getElementById('validUntil').value
            };

            // Validate required fields
            if (!formData.issueDate || !formData.studentName || !formData.diagnosis || 
                !formData.purpose || !formData.clinicPhysician || !formData.licenseNumber) {
                alert('Please fill in all required fields.');
                return;
            }

            // Generate certificate preview
            generateCertificate(formData);
        });

        function generateCertificate(data) {
            const issueDate = new Date(data.issueDate);
            const formattedDate = issueDate.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });

            let restStatement = '';
            if (data.daysOfRest && data.daysOfRest > 0) {
                restStatement = ` and is advised to rest for <span class="cert-field">${data.daysOfRest} day(s)</span>`;
            }

            let validStatement = '';
            if (data.validUntil) {
                const validDate = new Date(data.validUntil);
                const formattedValidDate = validDate.toLocaleDateString('en-US', { 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
                validStatement = `<p><strong>Valid Until:</strong> ${formattedValidDate}</p>`;
            }

            const certificateHTML = `
                <div class="cert-header">
                    <div class="cert-title">MEDICAL CERTIFICATE</div>
                    <div class="cert-clinic">${data.clinicName}</div>
                </div>
                
                <div class="cert-body">
                    <p><strong>Date:</strong> ${formattedDate}</p>
                    <br>
                    <p>TO WHOM IT MAY CONCERN:</p>
                    <br>
                    <p>This is to certify that <span class="cert-field">${data.studentName}</span> was examined and treated at this clinic on <span class="cert-field">${formattedDate}</span>.</p>
                    <br>
                    <p><strong>Diagnosis:</strong> <span class="cert-field">${data.diagnosis}</span></p>
                    ${data.remarks ? `<p><strong>Remarks:</strong> ${data.remarks}</p>` : ''}
                    <br>
                    <p>This certificate is issued for the purpose of <span class="cert-field">${data.purpose}</span>${restStatement}.</p>
                    <br>
                    ${validStatement}
                </div>

                <div class="cert-footer">
                    <div class="signature-section">
                        <div class="signature-line">
                            ${data.clinicPhysician}
                        </div>
                        <div style="margin-top: 5px; font-size: 12px;">Attending Physician</div>
                        <div style="margin-top: 3px; font-size: 12px;">License No: ${data.licenseNumber}</div>
                    </div>
                </div>
            `;

            document.getElementById('certificatePreview').innerHTML = certificateHTML;
            document.getElementById('certificateModal').classList.add('show');
            document.body.style.overflow = 'hidden';
            
            showSuccessAlert();
        }

        function closeModal() {
            document.getElementById('certificateModal').classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        function printCertificate() {
            const printContent = document.getElementById('certificatePreview').innerHTML;
            const originalContent = document.body.innerHTML;
            
            document.body.innerHTML = `
                <html>
                <head>
                    <title>Medical Certificate</title>
                    <style>
                        body { 
                            font-family: 'Times New Roman', serif; 
                            padding: 40px;
                        }
                        .cert-header {
                            text-align: center;
                            margin-bottom: 30px;
                            border-bottom: 3px solid #7f1d1d;
                            padding-bottom: 20px;
                        }
                        .cert-title {
                            font-size: 28px;
                            font-weight: bold;
                            color: #7f1d1d;
                            margin-bottom: 10px;
                        }
                        .cert-clinic {
                            font-size: 18px;
                            font-weight: 600;
                        }
                        .cert-body {
                            margin: 30px 0;
                            line-height: 2;
                            font-size: 15px;
                        }
                        .cert-field {
                            font-weight: bold;
                            color: #7f1d1d;
                        }
                        .cert-footer {
                            margin-top: 80px;
                            display: flex;
                            justify-content: flex-end;
                        }
                        .signature-section {
                            text-align: center;
                        }
                        .signature-line {
                            border-top: 2px solid black;
                            width: 250px;
                            margin-top: 50px;
                            padding-top: 8px;
                            font-weight: 600;
                        }
                    </style>
                </head>
                <body>${printContent}</body>
                </html>
            `;
            
            window.print();
            document.body.innerHTML = originalContent;
            location.reload();
        }

        function downloadCertificate() {
            alert('PDF download functionality would be implemented here using a PDF library like jsPDF or html2pdf.js');
        }

        function showSuccessAlert() {
            const alert = document.getElementById('successAlert');
            alert.classList.add('show');
            
            setTimeout(() => {
                alert.classList.remove('show');
            }, 3000);
        }

        // Close modal when clicking outside
        document.getElementById('certificateModal').addEventListener('click', function(e) {
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

        // Auto-calculate valid until date based on days of rest
        document.getElementById('daysOfRest').addEventListener('input', function() {
            const days = parseInt(this.value);
            if (days > 0) {
                const issueDate = new Date(document.getElementById('issueDate').value);
                const validUntil = new Date(issueDate);
                validUntil.setDate(validUntil.getDate() + days);
                document.getElementById('validUntil').valueAsDate = validUntil;
            }
        });
    </script>
</body>
</html>