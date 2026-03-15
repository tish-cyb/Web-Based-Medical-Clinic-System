<?php
// ============================================================
//  certificates.php — Employee Portal
//  Pulls Medical Clearances + Medical Certificates from DB
//  for the logged-in employee via vw_employee_certificates.
// ============================================================
session_start();
require_once "../config/db.php";

// ── GUARD ────────────────────────────────────────────────────
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header("Location: ../index.php");
    exit();
}

$employee_id = (int) $_SESSION['profile_id'];

// ── EMPLOYEE PROFILE (for cert document name field) ──────────
$q = mysqli_prepare($c,
    "SELECT UPPER(CONCAT(last_name, ', ', first_name,
             IF(middle_name IS NOT NULL AND middle_name <> '',
                CONCAT(' ', LEFT(middle_name,1), '.'), ''))) AS display_name,
            employee_number, department, position_designation
     FROM   employees WHERE employee_id = ? LIMIT 1");
mysqli_stmt_bind_param($q, "i", $employee_id);
mysqli_stmt_execute($q);
$profile = mysqli_fetch_assoc(mysqli_stmt_get_result($q)) ?? [];

// ── CERTIFICATES via view ────────────────────────────────────
//    Returns both medical_clearances AND medical_certificates
//    unified into a single result set for the employee.
$q2 = mysqli_prepare($c,
    "SELECT v.cert_id, v.certificate_type, v.issue_date,
            v.valid_until, v.purpose, v.status_detail,
            v.physician_name, v.license_number, v.source_table,
            ms.full_name AS staff_name
     FROM   vw_employee_certificates v
     LEFT JOIN (
         SELECT mc.staff_id, mc.clearance_id AS sid, 'medical_clearances' AS tbl
         FROM medical_clearances mc WHERE mc.employee_id = ?
         UNION ALL
         SELECT cert.staff_id, cert.certificate_id, 'medical_certificates'
         FROM medical_certificates cert WHERE cert.employee_id = ?
     ) src ON src.sid = v.cert_id AND src.tbl = v.source_table
     LEFT JOIN medical_staff ms ON ms.staff_id = src.staff_id
     WHERE  v.patient_id = ?
     ORDER  BY v.issue_date DESC");
mysqli_stmt_bind_param($q2, "iii", $employee_id, $employee_id, $employee_id);
mysqli_stmt_execute($q2);
$cert_result = mysqli_stmt_get_result($q2);
$certs = [];
while ($row = mysqli_fetch_assoc($cert_result)) {
    $certs[] = $row;
}

// ── HELPERS ──────────────────────────────────────────────────
function fmt_date(?string $d): string {
    if (!$d) return '—';
    return date('F j, Y', strtotime($d));
}
function esc(?string $s): string {
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

function cert_status(string $source, string $detail): array {
    if ($source === 'medical_clearances') {
        $map = [
            'Physically Fit'      => ['Valid',           'status-valid'],
            'Fit with Conditions' => ['With Conditions', 'status-conditional'],
            'Unfit'               => ['Unfit',           'status-expired'],
        ];
        return $map[$detail] ?? ['Valid', 'status-valid'];
    }
    $map = [
        'Active'  => ['Active',  'status-valid'],
        'Expired' => ['Expired', 'status-expired'],
        'Revoked' => ['Revoked', 'status-expired'],
    ];
    return $map[$detail] ?? ['Active', 'status-valid'];
}

function cert_filename(string $type): string {
    return preg_replace('/[^A-Za-z0-9_\-]/', '_', str_replace(' ', '_', $type));
}

$display_name    = $profile['display_name']      ?? 'EMPLOYEE';
$employee_number = $profile['employee_number']   ?? '—';
$department      = $profile['department']        ?? '—';
$total           = count($certs);

// Encode for JS
$js_certs = [];
foreach ($certs as $cert) {
    [$statusLabel] = cert_status($cert['source_table'], $cert['status_detail'] ?? '');
    $js_certs[] = [
        'type'      => $cert['certificate_type'],
        'filename'  => cert_filename($cert['certificate_type']),
        'date'      => fmt_date($cert['issue_date']),
        'validUntil'=> fmt_date($cert['valid_until']),
        'name'      => $display_name,
        'purpose'   => $cert['purpose'] ?? '',
        'fitStatus' => $cert['status_detail'] ?? '',
        'status'    => $statusLabel,
        'physician' => $cert['physician_name'] ?? '',
        'license'   => $cert['license_number'] ?? '',
        'source'    => $cert['source_table'],
    ];
}
$js_certs_json = json_encode($js_certs, JSON_HEX_TAG | JSON_HEX_AMP);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Portal — Medical Certificates</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary:       #7f1d1d;
            --primary-light: #b91c1c;
            --primary-soft:  #fef2f2;
            --grad-s:        #7f1d1d;
            --grad-e:        #ef4444;
            --text-dark:     #1f2937;
            --text-gray:     #6b7280;
            --bg:            #f3f4f6;
            --border:        #e5e7eb;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: var(--bg); min-height: 100vh; }

        /* ── Sidebar ── */
        .sidebar {
            width: 275px;
            background: linear-gradient(180deg, #860303 3%, #B21414 79%, #940000 97%);
            color: white; position: fixed; height: 100vh; left: 0; top: 0;
            display: flex; flex-direction: column;
            box-shadow: 4px 0 10px rgba(0,0,0,.1); z-index: 1000;
        }
        .sidebar-header { padding: 35px 25px; background: linear-gradient(180deg, #860303, #6B0000); }
        .sidebar-header h1 { font-size: 28px; font-weight: 700; margin-bottom: 5px; letter-spacing: -.5px; }
        .sidebar-header p  { font-size: 14px; opacity: .9; margin: 0; }
        .sidebar-nav { flex: 1; padding-top: 20px; }
        .nav-item {
            padding: 16px 32px; transition: all .3s; font-size: 15px; font-weight: 500;
            border-left: 4px solid transparent; display: flex; align-items: center; gap: 12px;
            color: rgba(255,255,255,.9); text-decoration: none;
        }
        .nav-item i { font-size: 18px; }
        .nav-item:hover  { background: rgba(255,255,255,.1); color: white; }
        .nav-item.active { background: rgba(0,0,0,.2); border-left-color: white; color: white; }
        .sidebar-footer { padding: 20px; display: flex; justify-content: center; }
        .chatbot-toggle {
            width: 60px; height: 60px; background: white; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 28px; box-shadow: 0 4px 8px rgba(0,0,0,.2); cursor: pointer; transition: transform .3s;
        }
        .chatbot-toggle:hover { transform: scale(1.1); }

        /* ── Chatbot ── */
        .chatbot-container {
            position: fixed; bottom: 20px; left: 295px;
            width: 380px; height: 550px; background: white;
            border-radius: 16px; box-shadow: 0 8px 32px rgba(0,0,0,.15);
            display: none; flex-direction: column; z-index: 1001; overflow: hidden;
        }
        .chatbot-container.show { display: flex; }
        .chatbot-header {
            background: linear-gradient(90deg, var(--grad-s), var(--grad-e));
            color: white; padding: 20px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .chatbot-header h3 { margin: 0; font-size: 18px; font-weight: 600; display: flex; align-items: center; gap: 10px; }
        .chatbot-close {
            background: none; border: none; color: white; font-size: 24px; cursor: pointer;
            width: 30px; height: 30px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; transition: background .3s;
        }
        .chatbot-close:hover { background: rgba(255,255,255,.2); }
        .quick-actions { padding: 12px 20px; display: flex; gap: 8px; flex-wrap: wrap; background: var(--bg); }
        .quick-action-btn {
            padding: 8px 16px; background: white; border: 2px solid var(--primary);
            color: var(--primary); border-radius: 20px; font-size: 12px; font-weight: 500;
            cursor: pointer; transition: all .3s; font-family: 'Poppins', sans-serif;
        }
        .quick-action-btn:hover { background: var(--primary); color: white; }
        .chatbot-messages { flex: 1; padding: 20px; overflow-y: auto; background: var(--bg); }
        .message { margin-bottom: 16px; display: flex; gap: 10px; animation: slideIn .3s ease; }
        @keyframes slideIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
        .message.user { flex-direction: row-reverse; }
        .message-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;
        }
        .message.bot  .message-avatar { background: var(--primary-soft); }
        .message.user .message-avatar { background: var(--primary); color: white; }
        .message-content { max-width: 70%; padding: 12px 16px; border-radius: 12px; font-size: 14px; line-height: 1.5; }
        .message.bot  .message-content { background: white; color: var(--text-dark); border-bottom-left-radius: 4px; }
        .message.user .message-content { background: var(--primary); color: white; border-bottom-right-radius: 4px; }
        .chatbot-input-area { padding: 16px; background: white; border-top: 1px solid var(--border); }
        .chatbot-input-wrapper { display: flex; gap: 10px; }
        .chatbot-input {
            flex: 1; padding: 12px 16px; border: 2px solid var(--border); border-radius: 24px;
            font-size: 14px; font-family: 'Poppins', sans-serif; outline: none; transition: border-color .3s;
        }
        .chatbot-input:focus { border-color: var(--primary); }
        .chatbot-send {
            width: 44px; height: 44px; background: var(--primary); color: white;
            border: none; border-radius: 50%; cursor: pointer;
            display: flex; align-items: center; justify-content: center; font-size: 18px; transition: background .3s;
        }
        .chatbot-send:hover    { background: var(--primary-light); }
        .chatbot-send:disabled { background: #d1d5db; cursor: not-allowed; }
        .typing-indicator { display: flex; gap: 4px; padding: 12px 16px; }
        .typing-indicator span { width: 8px; height: 8px; background: var(--text-gray); border-radius: 50%; animation: typing 1.4s infinite; }
        .typing-indicator span:nth-child(2) { animation-delay: .2s; }
        .typing-indicator span:nth-child(3) { animation-delay: .4s; }
        @keyframes typing { 0%,60%,100%{transform:translateY(0);opacity:.7} 30%{transform:translateY(-10px);opacity:1} }

        /* ── Main ── */
        .main-content { margin-left: 275px; padding: 40px 50px; }
        .page-header  { margin-bottom: 30px; }
        .page-header h2 { color: var(--primary); font-size: 34px; font-weight: 700; margin-bottom: 6px; }
        .page-header p  { color: var(--text-gray); font-size: 15px; }

        /* ── Table card ── */
        .certificates-section { background: white; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,.06); overflow: hidden; }
        .section-header {
            background: linear-gradient(90deg, var(--grad-s), var(--grad-e));
            color: white; padding: 18px 28px; font-size: 17px; font-weight: 600;
            display: flex; align-items: center; justify-content: space-between;
        }
        .section-header .cert-count {
            background: rgba(255,255,255,.25); font-size: 13px;
            padding: 4px 14px; border-radius: 20px; font-weight: 600;
        }

        /* Empty state */
        .empty-state { padding: 60px 30px; text-align: center; color: var(--text-gray); }
        .empty-state i { font-size: 56px; opacity: .3; margin-bottom: 16px; display: block; }
        .empty-state p { font-size: 15px; }
        .empty-state a { color: var(--primary); font-weight: 600; text-decoration: none; }
        .empty-state a:hover { text-decoration: underline; }

        .table { margin-bottom: 0; }
        .table thead th {
            background: var(--primary-soft); padding: 14px 24px;
            font-weight: 700; color: var(--text-dark); font-size: 12px;
            text-transform: uppercase; letter-spacing: .5px; border: none;
        }
        .table tbody td {
            padding: 16px 24px; color: var(--text-dark); font-weight: 500;
            vertical-align: middle; border-color: #f0f0f0; font-size: 14px;
        }
        .table tbody tr:hover { background: var(--primary-soft); }

        /* Status badges */
        .status-badge { padding: 5px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block; }
        .status-valid       { background: #d1fae5; color: #065f46; }
        .status-conditional { background: #fef3c7; color: #92400e; }
        .status-expired     { background: #fee2e2; color: #991b1b; }

        /* Type badge */
        .type-badge { display: inline-flex; align-items: center; gap: 6px; font-weight: 600; font-size: 13px; }
        .type-badge i { font-size: 15px; }

        /* View Details button */
        .btn-view-details {
            background: var(--primary); color: white;
            padding: 8px 20px; border-radius: 7px; font-size: 13px;
            font-weight: 600; border: none; cursor: pointer;
            transition: background .25s; font-family: 'Poppins', sans-serif;
        }
        .btn-view-details:hover { background: var(--primary-light); }

        /* ── Modal ── */
        .modal-overlay {
            display: none; position: fixed; z-index: 2000; inset: 0;
            background: rgba(0,0,0,.55); backdrop-filter: blur(5px);
            align-items: center; justify-content: center; padding: 20px;
        }
        .modal-overlay.show { display: flex; animation: mfade .25s ease; }
        @keyframes mfade  { from{opacity:0} to{opacity:1} }
        .modal-box {
            background: white; border-radius: 18px; width: 100%; max-width: 640px; max-height: 92vh;
            overflow: hidden; display: flex; flex-direction: column;
            box-shadow: 0 24px 80px rgba(0,0,0,.25); animation: mslide .3s ease;
        }
        @keyframes mslide { from{transform:translateY(40px);opacity:0} to{transform:translateY(0);opacity:1} }
        .modal-head {
            background: linear-gradient(90deg, var(--grad-s), var(--grad-e));
            color: white; padding: 22px 28px;
            display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;
        }
        .modal-head-title { font-size: 18px; font-weight: 700; }
        .modal-head-sub   { font-size: 12px; opacity: .85; margin-top: 2px; }
        .modal-close-btn {
            background: rgba(255,255,255,.2); border: none; color: white;
            width: 32px; height: 32px; border-radius: 50%; font-size: 18px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all .2s; flex-shrink: 0;
        }
        .modal-close-btn:hover { background: rgba(255,255,255,.35); transform: rotate(90deg); }
        .modal-scroll { overflow-y: auto; flex: 1; padding: 28px; background: var(--bg); }

        /* ── Certificate document ── */
        .cert-document {
            background: white; border-radius: 8px; padding: 32px 40px;
            font-family: 'Times New Roman', serif;
            box-shadow: 0 2px 12px rgba(0,0,0,.08);
        }
        .cert-doc-header {
            display: flex; align-items: center; gap: 14px;
            margin-bottom: 12px; padding-bottom: 12px; border-bottom: 2.5px solid var(--primary);
        }
        .cert-doc-header-text { flex: 1; text-align: center; }
        .cert-doc-header-text .rep  { font-size: 10px; color: #555; font-style: italic; }
        .cert-doc-header-text .univ { font-size: 13px; font-weight: bold; color: var(--text-dark); }
        .cert-doc-header-text .loc  { font-size: 10px; color: #555; }
        .cert-doc-title { text-align: center; margin: 18px 0 4px; }
        .cert-doc-title .main-t { font-size: 20px; font-weight: bold; color: var(--primary); letter-spacing: 1px; text-transform: uppercase; }
        .cert-doc-title .sub-t  { font-size: 12px; font-weight: bold; color: var(--primary); letter-spacing: 3px; margin-top: 2px; }
        .cert-doc-body { font-size: 13px; line-height: 2.4; color: var(--text-dark); margin-top: 16px; }
        .cert-doc-uline {
            display: inline-block; border-bottom: 1.5px solid var(--text-dark);
            min-width: 180px; text-align: center; font-weight: bold; color: var(--primary); padding: 0 4px;
        }
        .cert-doc-uline.full { display: block; width: 100%; margin: 4px 0; }
        .cert-doc-sig { margin-top: 44px; text-align: center; }
        .cert-doc-sig-line { border-top: 2px solid var(--text-dark); width: 240px; margin: 0 auto; padding-top: 6px; font-weight: bold; font-size: 13px; color: var(--text-dark); }
        .cert-doc-sig-detail { font-size: 11px; color: #555; margin-top: 3px; }
        .cert-doc-ref { font-size: 10px; color: #aaa; margin-top: 18px; text-align: right; border-top: 1px solid var(--border); padding-top: 6px; }

        /* ── Modal footer — Close (white) + Download PDF (red) ── */
        .modal-footer-bar {
            padding: 16px 28px; background: #f9fafb; border-top: 1px solid var(--border);
            display: flex; justify-content: flex-end; gap: 10px; flex-shrink: 0;
        }
        .btn-modal-close {
            padding: 10px 24px; border-radius: 8px;
            border: 2px solid var(--border); background: white; color: var(--text-dark);
            font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 600;
            cursor: pointer; transition: all .2s;
            display: inline-flex; align-items: center; gap: 7px;
        }
        .btn-modal-close:hover { border-color: var(--primary); color: var(--primary); background: var(--primary-soft); }
        .btn-modal-download {
            padding: 10px 24px; border-radius: 8px; border: none;
            background: var(--primary); color: white;
            font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 600;
            cursor: pointer; transition: background .2s;
            display: inline-flex; align-items: center; gap: 7px;
        }
        .btn-modal-download:hover    { background: var(--primary-light); }
        .btn-modal-download:disabled { background: #9ca3af; cursor: not-allowed; }

        .dl-spin {
            display: none; width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,.4);
            border-top-color: white; border-radius: 50%;
            animation: spin .7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .sidebar { width: 200px; }
            .main-content { margin-left: 200px; padding: 30px 20px; }
            .table { font-size: 13px; }
        }
    </style>
</head>
<body>

<!-- ── Sidebar ── -->
<div class="sidebar">
    <div class="sidebar-header">
        <h1>Employee Portal</h1>
        <p>Medical Services</p>
    </div>
    <nav class="sidebar-nav">
        <a href="book_appointment.php" class="nav-item">
            <i class="bi bi-calendar-plus"></i><span>Book Appointment</span>
        </a>
        <a href="medical_history.php" class="nav-item">
            <i class="bi bi-clock-history"></i><span>Medical History</span>
        </a>
        <a href="certificates.php" class="nav-item active">
            <i class="bi bi-file-earmark-medical"></i><span>Medical Certificates</span>
        </a>
        <a href="profile.php" class="nav-item">
            <i class="bi bi-person"></i><span>Profile</span>
        </a>
    </nav>
    <div class="sidebar-footer">
        <div class="chatbot-toggle" id="chatbotToggle">🤖</div>
    </div>
</div>

<!-- ── Chatbot ── -->
<div class="chatbot-container" id="chatbotContainer">
    <div class="chatbot-header">
        <h3><i class="bi bi-robot"></i> Medical Assistant</h3>
        <button class="chatbot-close" id="chatbotClose">&times;</button>
    </div>
    <div class="quick-actions">
        <button class="quick-action-btn" data-message="Available certificates">My Certificates</button>
        <button class="quick-action-btn" data-message="How to download">Download Help</button>
        <button class="quick-action-btn" data-message="Request new certificate">Request New</button>
    </div>
    <div class="chatbot-messages" id="chatbotMessages">
        <div class="message bot">
            <div class="message-avatar">🤖</div>
            <div class="message-content">Hello! I can help you with your medical certificates. What do you need?</div>
        </div>
    </div>
    <div class="chatbot-input-area">
        <div class="chatbot-input-wrapper">
            <input type="text" class="chatbot-input" id="chatbotInput" placeholder="Type your message..." autocomplete="off">
            <button class="chatbot-send" id="chatbotSend"><i class="bi bi-send-fill"></i></button>
        </div>
    </div>
</div>

<!-- ── Main ── -->
<main class="main-content">
    <div class="page-header">
        <h2>Medical Certificates</h2>
        <p>Download and view your official certificates for <strong><?= esc($display_name) ?></strong> &nbsp;·&nbsp; <?= esc($employee_number) ?> &nbsp;·&nbsp; <?= esc($department) ?></p>
    </div>

    <div class="certificates-section">
        <div class="section-header">
            <span>Available Certificates</span>
            <span class="cert-count"><?= $total ?> document<?= $total !== 1 ? 's' : '' ?></span>
        </div>

        <?php if (empty($certs)): ?>
        <div class="empty-state">
            <i class="bi bi-file-earmark-x"></i>
            <p>No certificates on file yet.</p>
            <p style="font-size:13px;margin-top:8px;">
                Certificates will appear here after a clinic visit.<br>
                <a href="book_appointment.php">Book an appointment →</a>
            </p>
        </div>
        <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Certificate Type</th>
                    <th>Issue Date</th>
                    <th>Valid Until</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($certs as $idx => $cert):
                [$statusLabel, $statusClass] = cert_status($cert['source_table'], $cert['status_detail'] ?? '');
                $isShield  = ($cert['source_table'] === 'medical_clearances');
                $typeColor = $isShield ? '#4c1d95' : '#9d174d';
            ?>
            <tr>
                <td>
                    <span class="type-badge" style="color:<?= $typeColor ?>;">
                        <i class="bi <?= $typeIcon ?>"></i>
                        <?= esc($cert['certificate_type']) ?>
                    </span>
                </td>
                <td><?= fmt_date($cert['issue_date']) ?></td>
                <td>
                    <?php if ($cert['valid_until']): ?>
                        <?= fmt_date($cert['valid_until']) ?>
                    <?php else: ?>
                        <span style="color:var(--text-gray);">—</span>
                    <?php endif; ?>
                </td>
                <td><span class="status-badge <?= $statusClass ?>"><?= esc($statusLabel) ?></span></td>
                <td>
                    <button class="btn-view-details" onclick="viewCert(<?= $idx ?>)">
                        View Details
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</main>

<!-- ── View Certificate Modal ── -->
<div class="modal-overlay" id="certModal">
    <div class="modal-box">
        <div class="modal-head">
            <div>
                <div class="modal-head-title" id="modal-cert-type">Medical Certificate</div>
                <div class="modal-head-sub">PUP AiCare Clinic — Medical Services Department</div>
            </div>
            <button class="modal-close-btn" onclick="closeCertModal()">&times;</button>
        </div>
        <div class="modal-scroll">
            <div class="cert-document" id="cert-modal-preview"></div>
        </div>
        <!-- Footer: Close (white) + Download PDF (red) -->
        <div class="modal-footer-bar">
            <button class="btn-modal-close" onclick="closeCertModal()">
                <i class="bi bi-x-circle"></i> Close
            </button>
            <button class="btn-modal-download" id="modal-download-btn" onclick="downloadCertFromModal()">
                <div class="dl-spin" id="modal-dl-spin"></div>
                <i class="bi bi-download" id="modal-dl-icon"></i> Download PDF
            </button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
const certData = <?= $js_certs_json ?>;
let currentCertIdx = 0;

function esc(s) {
    if (!s) return '';
    return String(s)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function certTitles(c) {
    if (c.source === 'medical_clearances') {
        return { main: 'MEDICAL CLEARANCE', sub: esc(c.fitStatus).toUpperCase() || 'PHYSICALLY FIT' };
    }
    const subMap = {
        'Sick Leave':        'SICK LEAVE CERTIFICATE',
        'Fitness to Study':  'FITNESS TO STUDY',
        'Fitness to Work':   'FITNESS TO WORK',
        'Medical Clearance': 'PHYSICALLY FIT',
        'General Purpose':   'MEDICAL CERTIFICATE',
    };
    return { main: esc(c.type).toUpperCase(), sub: subMap[c.type] || 'MEDICAL CERTIFICATE' };
}

function buildCertHTML(c, forPdf = false) {
    const { main, sub } = certTitles(c);
    const physician = c.physician || 'CLINIC PHYSICIAN';
    const license   = c.license  || '—';
    const purpose   = esc(c.purpose) || '&nbsp;';
    const name      = esc(c.name);
    const date      = esc(c.date);

    const bodyText = c.source === 'medical_clearances'
        ? `has been examined by the undersigned and found to be <strong>physically fit</strong> at the time of examination.`
        : `has been examined and issued this <strong>${esc(c.type)}</strong> upon request.`;

    if (!forPdf) {
        return `
        <div class="cert-doc-header">
            <img src="../assets/img/pup_logo.png" alt="PUP Logo" style="width:58px;height:58px;object-fit:contain;flex-shrink:0;">
            <div class="cert-doc-header-text">
                <div class="rep">Republic of the Philippines</div>
                <div class="univ">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</div>
                <div class="loc">Manila</div>
            </div>
        </div>
        <div class="cert-doc-title">
            <div class="main-t">${main}</div>
            <div class="sub-t">${sub}</div>
        </div>
        <div class="cert-doc-body">
            <p>Date &nbsp;<span class="cert-doc-uline" style="min-width:180px;">${date}</span></p>
            <br><p>To Whom It May Concern:</p><br>
            <p>This is to certify that</p>
            <div class="cert-doc-uline full">${name}</div>
            <p>${bodyText}</p>
            <br><p>This certification is issued upon his/her request for</p>
            <div class="cert-doc-uline full">${purpose}</div>
            <div class="cert-doc-uline full" style="min-height:22px;">&nbsp;</div>
            <p>purpose but not for medico-legal reason.</p>
        </div>
        <div class="cert-doc-sig">
            <div class="cert-doc-sig-line">${esc(physician)}, M.D.</div>
            <div class="cert-doc-sig-detail">Lic No. ${esc(license)}</div>
        </div>
        <div class="cert-doc-ref">Medical 03 · Rev 1 · PUP-LAFO-6-MEDS · Medical Services Department</div>`;
    }

    const pRed = '#7f1d1d', pDark = '#1f2937';
    return `
    <div style="font-family:'Times New Roman',serif;background:white;color:${pDark};">
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:12px;padding-bottom:12px;border-bottom:2.5px solid ${pRed};">
            <img src="../assets/pup_logo.png" alt="PUP Logo" style="width:60px;height:60px;object-fit:contain;flex-shrink:0;">
            <div style="flex:1;text-align:center;">
                <div style="font-size:10px;color:#555;font-style:italic;">Republic of the Philippines</div>
                <div style="font-size:13px;font-weight:bold;color:${pDark};">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</div>
                <div style="font-size:10px;color:#555;">Manila</div>
            </div>
        </div>
        <div style="text-align:center;margin:18px 0 4px;">
            <div style="font-size:22px;font-weight:bold;color:${pRed};letter-spacing:1px;">${main}</div>
            <div style="font-size:12px;font-weight:bold;color:${pRed};letter-spacing:3px;margin-top:3px;">${sub}</div>
        </div>
        <div style="font-size:13px;line-height:2.4;color:${pDark};margin-top:18px;">
            <p>Date &nbsp;<span style="display:inline-block;border-bottom:1.5px solid ${pDark};min-width:180px;text-align:center;font-weight:bold;color:${pRed};padding:0 4px;">${date}</span></p>
            <br><p>To Whom It May Concern:</p><br>
            <p>This is to certify that</p>
            <div style="display:block;width:100%;border-bottom:1.5px solid ${pDark};text-align:center;font-weight:bold;color:${pRed};padding:2px 4px;margin:6px 0;">${name}</div>
            <p>${bodyText}</p>
            <br><p>This certification is issued upon his/her request for</p>
            <div style="display:block;width:100%;border-bottom:1.5px solid ${pDark};text-align:center;font-weight:bold;color:${pRed};padding:2px 4px;margin:6px 0;">${purpose}</div>
            <div style="display:block;width:100%;border-bottom:1.5px solid ${pDark};min-height:22px;margin:0 0 4px;">&nbsp;</div>
            <p>purpose but not for medico-legal reason.</p>
        </div>
        <div style="margin-top:50px;text-align:center;">
            <div style="border-top:2px solid ${pDark};width:240px;margin:0 auto;padding-top:7px;font-weight:bold;font-size:13px;color:${pDark};">${esc(physician)}, M.D.</div>
            <div style="font-size:11px;color:#555;margin-top:3px;">Lic No. ${esc(license)}</div>
        </div>
        <div style="font-size:10px;color:#aaa;margin-top:22px;text-align:right;border-top:1px solid #e5e7eb;padding-top:6px;">Medical 03 · Rev 1 · PUP-LAFO-6-MEDS · Medical Services Department</div>
    </div>`;
}

// ── Open modal ─────────────────────────────────────────────
function viewCert(idx) {
    currentCertIdx = idx;
    const c = certData[idx];
    document.getElementById('modal-cert-type').textContent  = c.type;
    document.getElementById('cert-modal-preview').innerHTML = buildCertHTML(c, false);
    document.getElementById('certModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeCertModal() {
    document.getElementById('certModal').classList.remove('show');
    document.body.style.overflow = 'auto';
}
document.getElementById('certModal').addEventListener('click', function (e) {
    if (e.target === this) closeCertModal();
});
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeCertModal(); });

// ── Download PDF from inside modal ────────────────────────
async function downloadCertFromModal() {
    const btn  = document.getElementById('modal-download-btn');
    const spin = document.getElementById('modal-dl-spin');
    const icon = document.getElementById('modal-dl-icon');
    btn.disabled = true;
    spin.style.display = 'block';
    icon.style.display = 'none';
    try {
        const c      = certData[currentCertIdx];
        const target = document.createElement('div');
        target.style.cssText = 'position:fixed;left:-9999px;top:0;width:680px;background:white;padding:44px 52px;font-family:Times New Roman,serif;';
        target.innerHTML = buildCertHTML(c, true);
        document.body.appendChild(target);
        await new Promise(r => setTimeout(r, 320));
        const canvas = await html2canvas(target, { scale: 2, useCORS: true, backgroundColor: '#ffffff', logging: false });
        document.body.removeChild(target);
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });
        const pw = pdf.internal.pageSize.getWidth();
        const m  = 14, iw = pw - m * 2;
        const ih = (canvas.height * iw) / canvas.width;
        pdf.addImage(canvas.toDataURL('image/jpeg', 0.96), 'JPEG', m, m, iw, Math.min(ih, pdf.internal.pageSize.getHeight() - m * 2));
        pdf.save(c.filename + '.pdf');
    } catch (err) {
        alert('PDF generation failed. Please try again.');
        console.error(err);
    } finally {
        btn.disabled = false;
        spin.style.display = 'none';
        icon.style.display = '';
    }
}

// ── Chatbot ────────────────────────────────────────────────
const totalCerts = <?= $total ?>;

const chatbotToggle    = document.getElementById('chatbotToggle');
const chatbotContainer = document.getElementById('chatbotContainer');
const chatbotClose     = document.getElementById('chatbotClose');
const chatbotMessages  = document.getElementById('chatbotMessages');
const chatbotInput     = document.getElementById('chatbotInput');
const chatbotSend      = document.getElementById('chatbotSend');

chatbotToggle.addEventListener('click', () => chatbotContainer.classList.toggle('show'));
chatbotClose.addEventListener('click',  () => chatbotContainer.classList.remove('show'));
document.querySelectorAll('.quick-action-btn').forEach(btn =>
    btn.addEventListener('click', () => sendMessage(btn.getAttribute('data-message')))
);

function sendMessage(messageText = null) {
    const text = messageText || chatbotInput.value.trim();
    if (!text) return;
    addMessage(text, 'user');
    chatbotInput.value = '';
    showTypingIndicator();
    setTimeout(() => { hideTypingIndicator(); addMessage(getBotResponse(text), 'bot'); }, 900 + Math.random() * 800);
}
function addMessage(text, sender) {
    const div = document.createElement('div');
    div.className = `message ${sender}`;
    div.innerHTML = `<div class="message-avatar">${sender === 'bot' ? '🤖' : '👤'}</div><div class="message-content">${text}</div>`;
    chatbotMessages.appendChild(div);
    chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
}
function showTypingIndicator() {
    const div = document.createElement('div');
    div.className = 'message bot'; div.id = 'typingIndicator';
    div.innerHTML = `<div class="message-avatar">🤖</div><div class="message-content typing-indicator"><span></span><span></span><span></span></div>`;
    chatbotMessages.appendChild(div);
    chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
}
function hideTypingIndicator() {
    const el = document.getElementById('typingIndicator');
    if (el) el.remove();
}
function getBotResponse(message) {
    const m = message.toLowerCase();
    if (m.includes('certificate') || m.includes('available')) {
        if (totalCerts === 0) return "You don't have any certificates on file yet. Book an appointment at the clinic to get started.";
        const list = certData.map(c => `• ${c.type} — issued ${c.date}${c.validUntil !== '—' ? ', valid until ' + c.validUntil : ''}`).join('\n');
        return `You have <strong>${totalCerts}</strong> certificate${totalCerts !== 1 ? 's' : ''} on file:\n${list}`;
    }
    if (m.includes('download') || m.includes('pdf'))
        return "Click <strong>View Details</strong> next to any certificate, then use the <strong>Download PDF</strong> button inside the preview.";
    if (m.includes('request') || m.includes('new') || m.includes('apply'))
        return "To get a new certificate: <a href='book_appointment.php' style='color:var(--primary);font-weight:600;'>book a Medical Clearance appointment</a>. After your clinic visit, the document will appear here automatically.";
    if (m.includes('valid') || m.includes('expire'))
        return totalCerts > 0
            ? `Your validity dates are shown in the <strong>Valid Until</strong> column of the table.`
            : "No certificates yet — validity dates will show here once you have documents on file.";
    if (m.includes('print'))
        return "Download the PDF first using <strong>View Details</strong>, then print it from your PDF viewer.";
    if (m.includes('hello') || m.includes('hi'))
        return "Hello! I can help you view, download, and understand your medical certificates. What do you need?";
    return `I can help you with viewing, downloading, and requesting certificates. You currently have <strong>${totalCerts}</strong> document${totalCerts !== 1 ? 's' : ''} on file.`;
}

chatbotSend.addEventListener('click', () => sendMessage());
chatbotInput.addEventListener('keypress', e => { if (e.key === 'Enter') sendMessage(); });
</script>
</body>
</html>