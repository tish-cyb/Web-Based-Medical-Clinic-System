<?php
// ── Auth guard ────────────────────────────────────────────────
session_start();
if (!isset($_SESSION['user_id']) ||
    !in_array($_SESSION['role'], ['medical_staff', 'admin'])) {
    header('Location: ../medical/medical_dashboard.php');
    exit();
}

require_once '../config/db.php';   // provides $c (mysqli connection)

// ── Fetch dashboard stats from vw_dashboard_stats ─────────────
$stats = [
    'pending_appointments' => 0,
    'monthly_appointments' => 0,
    'annual_appointments'  => 0,
    'total_students'       => 0,
    'total_employees'      => 0,
];

$res = mysqli_query($c, 'SELECT * FROM vw_dashboard_stats LIMIT 1');
if ($res && $row = mysqli_fetch_assoc($res)) {
    $stats = $row;
}

// ── Fetch today's schedule from vw_todays_schedule ────────────
$schedule = [];
$res2 = mysqli_query($c, 'SELECT * FROM vw_todays_schedule');
if ($res2) {
    while ($row = mysqli_fetch_assoc($res2)) {
        $schedule[] = $row;
    }
}

// ── Logged-in staff name ──────────────────────────────────────
$staff_name = $_SESSION['username'] ?? 'Staff';
$res3 = mysqli_query($c,
    "SELECT full_name FROM medical_staff
     WHERE user_id = " . (int)$_SESSION['user_id'] . " LIMIT 1");
if ($res3 && $row3 = mysqli_fetch_assoc($res3)) {
    $staff_name = htmlspecialchars($row3['full_name']);
}

// ── Today's date display ──────────────────────────────────────
$today_label = date('l, F j, Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Portal – Dashboard</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-body);
            min-height: 100vh;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: 275px;
            background: linear-gradient(180deg, #860303 3%, #B21414 79%, #940000 97%);
            color: white;
            position: fixed;
            height: 100vh; left: 0; top: 0;
            display: flex; flex-direction: column;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        .sidebar-header {
            padding: 35px 25px;
            background: linear-gradient(180deg, #860303 0%, #6B0000 100%);
        }
        .sidebar-header h1 { font-size: 28px; font-weight: 700; margin-bottom: 5px; letter-spacing: -0.5px; }
        .sidebar-header p  { font-size: 14px; opacity: 0.9; margin-bottom: 0; }
        .sidebar-nav { flex: 1; padding-top: 20px; }
        .nav-item {
            padding: 16px 32px; cursor: pointer; transition: all 0.3s ease;
            font-size: 15px; font-weight: 500; border-left: 4px solid transparent;
            display: flex; align-items: center; gap: 12px; color: rgba(255,255,255,0.9);
        }
        .nav-item i { font-size: 18px; }
        .nav-item:hover { background-color: rgba(255,255,255,0.1); color: white; }
        .nav-item.active { background-color: rgba(0,0,0,0.2); border-left-color: white; color: white; }

        /* ── MAIN ── */
        .main-content { margin-left: 275px; padding: 40px 50px; }

        /* ── HEADER BAR ── */
        .header-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 16px;
        }
        .header-bar-left h2 {
            color: var(--primary-color); font-size: 34px;
            font-weight: 700; margin-bottom: 4px;
        }
        .header-bar-left .date-label {
            color: var(--text-gray); font-size: 14px;
        }

        /* ── SCHEDULE HEADER PILLS ── */
        .schedule-header {
            background: linear-gradient(90deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
            color: white; padding: 20px 28px;
            font-size: 20px; font-weight: 600;
            display: flex; align-items: center; justify-content: space-between; gap: 16px;
            flex-wrap: wrap;
        }
        .schedule-header-left {
            display: flex; align-items: center; gap: 10px;
        }
        .schedule-header-pills {
            display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
        }
        .header-pill {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,0.18);
            border-radius: 20px;
            padding: 5px 14px;
            font-size: 13px; font-weight: 600; color: white;
            white-space: nowrap;
        }
        .header-pill .pill-count {
            font-size: 15px; font-weight: 700;
        }
        .header-pill.pill-pending {
            background: rgba(255,255,255,0.28);
            border: 1px solid rgba(255,255,255,0.45);
        }

        /* ── TODAY'S SCHEDULE ── */
        .schedule-section {
            background: white; border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            overflow: hidden;
        }
        .table { margin-bottom: 0; }
        .table thead th {
            background-color: var(--primary-soft);
            padding: 16px 24px; font-weight: 700;
            color: var(--text-dark); font-size: 12px;
            text-transform: uppercase; letter-spacing: 0.5px; border: none;
        }
        .table tbody td {
            padding: 18px 24px; color: var(--text-dark);
            font-weight: 500; vertical-align: middle;
            border-color: #f0f0f0; font-size: 14px;
        }
        .table tbody tr:hover { background-color: var(--primary-soft); }

        /* Status badges */
        .status-badge {
            padding: 5px 16px; border-radius: 20px;
            font-size: 12px; font-weight: 600; display: inline-block;
        }
        .status-Confirmed   { background: #d1fae5; color: #065f46; }
        .status-Waiting     { background: #fef3c7; color: #92400e; }
        .status-Pending     { background: #e0f2fe; color: #0369a1; }
        .status-In-Progress { background: #ede9fe; color: #5b21b6; }
        .status-Completed   { background: #f0fdf4; color: #166534; }

        /* Patient type pill */
        .type-pill {
            display: inline-block; padding: 3px 10px;
            border-radius: 12px; font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.4px;
        }
        .type-student  { background: #dbeafe; color: #1e40af; }
        .type-employee { background: #fce7f3; color: #9d174d; }

        /* Empty state */
        .no-schedule {
            padding: 60px 30px; text-align: center; color: var(--text-gray);
        }
        .no-schedule i { font-size: 48px; opacity: 0.25; display: block; margin-bottom: 14px; }

        /* ── RESPONSIVE ── */
        @media (max-width: 1400px) {
            .stats-grid { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 1024px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .sidebar { width: 200px; }
            .main-content { margin-left: 200px; padding: 28px 20px; }
            .stats-grid { grid-template-columns: 1fr; }
            .nav-item span { display: none; }
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="sidebar-header">
        <h1>Medical Portal</h1>
        <p>Clinical Management</p>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-item active" data-page="dashboard">
            <i class="bi bi-speedometer2"></i><span>Dashboard</span>
        </div>
        <div class="nav-item" data-page="roster">
            <i class="bi bi-people"></i><span>Roster</span>
        </div>
        <div class="nav-item" data-page="appointments">
            <i class="bi bi-calendar-check"></i><span>Appointments</span>
        </div>
        <div class="nav-item" data-page="records">
            <i class="bi bi-folder2-open"></i><span>Patient Records</span>
        </div>
        <div class="nav-item" data-page="consultations">
            <i class="bi bi-chat-dots"></i><span>Consultations</span>
        </div>
        <div class="nav-item" data-page="profile">
            <i class="bi bi-person"></i><span>Profile</span>
        </div>
    </nav>
</div>

<!-- MAIN -->
<main class="main-content">

    <!-- Header bar -->
    <div class="header-bar">
        <div class="header-bar-left">
            <h2>Welcome, <span class="staff-name"><?= $staff_name ?>!</span></h2>
            <p class="date-label"><i class="bi bi-calendar3 me-1"></i><?= $today_label ?></p>
        </div>
    </div>

    <!-- Today's Schedule -->
    <div class="schedule-section">
        <div class="schedule-header">
            <div class="schedule-header-left">
                <i class="bi bi-calendar-day"></i> Today's Schedule
            </div>
            <div class="schedule-header-pills">
                <div class="header-pill pill-pending">
                    <i class="bi bi-clock-history"></i>
                    <span class="pill-count"><?= (int)$stats['pending_appointments'] ?></span>
                    pending
                </div>
            </div>
        </div>

        <?php if (empty($schedule)): ?>
        <div class="no-schedule">
            <i class="bi bi-calendar-x"></i>
            No appointments scheduled for today.
        </div>
        <?php else: ?>
        <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Patient Name</th>
                    <th>ID Number</th>
                    <th>Type</th>
                    <th>Purpose</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($schedule as $row): ?>
                <?php
                    $status_class = 'status-' . str_replace(' ', '-', htmlspecialchars($row['status']));
                    $type_class   = 'type-' . htmlspecialchars($row['patient_type']);
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['appt_time']) ?></td>
                    <td><?= htmlspecialchars($row['patient_name']) ?></td>
                    <td><?= htmlspecialchars($row['id_number']) ?></td>
                    <td><span class="type-pill <?= $type_class ?>"><?= htmlspecialchars($row['patient_type']) ?></span></td>
                    <td><?= htmlspecialchars($row['purpose']) ?></td>
                    <td><span class="status-badge <?= $status_class ?>"><?= htmlspecialchars($row['status']) ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php endif; ?>
    </div>

</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script>
    const pageFiles = {
        'dashboard':     'medical_dashboard.php',
        'roster':        'roster.php',
        'appointments':  'appointments.php',
        'records':       'patient_records.php',
        'consultations': 'consultations.php',
        'profile':       'profile.php'
    };

    document.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', function () {
            const page = this.getAttribute('data-page');
            if (pageFiles[page]) window.location.href = pageFiles[page];
        });
    });
</script>
</body>
</html>