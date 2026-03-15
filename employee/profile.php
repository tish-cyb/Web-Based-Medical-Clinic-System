<?php
// ============================================================
//  profile.php — Employee Portal
// ============================================================
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header("Location: ../index.php");
    exit();
}

$employee_id = (int) $_SESSION['profile_id'];
$user_id     = (int) $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_update'])) {
    header('Content-Type: application/json');
    $errors = [];

    $email          = trim($_POST['email']          ?? '');
    $contact_number = trim($_POST['contact_number'] ?? '');
    $address        = trim($_POST['address']        ?? '');
    $civil_status   = trim($_POST['civil_status']   ?? '');
    $ec_name        = trim($_POST['ec_name']        ?? '');
    $ec_number      = trim($_POST['ec_number']      ?? '');

    if (empty($email))          $errors[] = "Email address is required.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Enter a valid email address.";
    if (empty($contact_number)) $errors[] = "Contact number is required.";
    elseif (!preg_match('/^[0-9+\-\s]{7,15}$/', $contact_number)) $errors[] = "Enter a valid contact number.";
    if (empty($ec_name))        $errors[] = "Emergency contact name is required.";
    if (empty($ec_number))      $errors[] = "Emergency contact number is required.";
    elseif (!preg_match('/^[0-9+\-\s]{7,15}$/', $ec_number)) $errors[] = "Enter a valid emergency contact number.";

    $allowed_civil = ['Single','Married','Widowed','Separated',''];
    if (!in_array($civil_status, $allowed_civil)) $errors[] = "Invalid civil status.";

    if (!empty($errors)) { echo json_encode(['success' => false, 'errors' => $errors]); exit(); }

    $upd = mysqli_prepare($c,
        "UPDATE employees SET email=?, contact_number=?, address=?, civil_status=?,
         emergency_contact_name=?, emergency_contact_number=?, updated_at=NOW()
         WHERE employee_id=?");
    mysqli_stmt_bind_param($upd, "ssssssi",
        $email, $contact_number, $address, $civil_status, $ec_name, $ec_number, $employee_id);

    if (mysqli_stmt_execute($upd)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'errors' => ['Database error: ' . mysqli_error($c)]]);
    }
    exit();
}

$q = mysqli_prepare($c,
    "SELECT e.employee_number, e.last_name, e.first_name, e.middle_name,
            e.sex, e.date_of_birth, e.civil_status, e.contact_number,
            e.email, e.address, e.campus, e.department,
            e.position_designation, e.employment_type,
            e.emergency_contact_name, e.emergency_contact_number,
            u.email AS login_email
     FROM employees e JOIN users u ON u.user_id = e.user_id
     WHERE e.employee_id = ? LIMIT 1");
mysqli_stmt_bind_param($q, "i", $employee_id);
mysqli_stmt_execute($q);
$p = mysqli_fetch_assoc(mysqli_stmt_get_result($q)) ?? [];

function esc(?string $s): string { return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
function val(?string $s): string { return esc($s ?? ''); }

$full_name = trim(
    ($p['last_name'] ?? '') . ', ' . ($p['first_name'] ?? '') .
    (isset($p['middle_name']) && $p['middle_name'] !== '' ? ' ' . $p['middle_name'] : '')
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Portal — Profile</title>
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
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Poppins',sans-serif; background:var(--bg); min-height:100vh; }

        /* ── Sidebar ── */
        .sidebar {
            width:275px; background:linear-gradient(180deg,#860303 3%,#B21414 79%,#940000 97%);
            color:white; position:fixed; height:100vh; left:0; top:0;
            display:flex; flex-direction:column; box-shadow:4px 0 10px rgba(0,0,0,.1); z-index:1000;
        }
        .sidebar-header { padding:35px 25px; background:linear-gradient(180deg,#860303,#6B0000); }
        .sidebar-header h1 { font-size:28px; font-weight:700; margin-bottom:5px; letter-spacing:-.5px; }
        .sidebar-header p  { font-size:14px; opacity:.9; margin:0; }
        .sidebar-nav { flex:1; padding-top:20px; }
        .nav-item {
            padding:16px 32px; cursor:pointer; transition:all .3s;
            font-size:15px; font-weight:500; border-left:4px solid transparent;
            display:flex; align-items:center; gap:12px;
            color:rgba(255,255,255,.9); text-decoration:none;
        }
        .nav-item i { font-size:18px; }
        .nav-item:hover  { background:rgba(255,255,255,.1); color:white; }
        .nav-item.active { background:rgba(0,0,0,.2); border-left-color:white; color:white; }
        .sidebar-footer { padding:20px; display:flex; justify-content:center; }
        .chatbot-toggle {
            width:60px; height:60px; background:white; border-radius:50%;
            display:flex; align-items:center; justify-content:center;
            font-size:28px; box-shadow:0 4px 8px rgba(0,0,0,.2);
            cursor:pointer; transition:transform .3s;
        }
        .chatbot-toggle:hover { transform:scale(1.1); }

        /* ── Chatbot ── */
        .chatbot-container { position:fixed; bottom:20px; left:295px; width:380px; height:550px; background:white; border-radius:16px; box-shadow:0 8px 32px rgba(0,0,0,.15); display:none; flex-direction:column; z-index:1001; overflow:hidden; }
        .chatbot-container.show { display:flex; }
        .chatbot-header { background:linear-gradient(90deg,var(--grad-s),var(--grad-e)); color:white; padding:20px; display:flex; justify-content:space-between; align-items:center; }
        .chatbot-header h3 { margin:0; font-size:18px; font-weight:600; display:flex; align-items:center; gap:10px; }
        .chatbot-close { background:none; border:none; color:white; font-size:24px; cursor:pointer; width:30px; height:30px; border-radius:50%; display:flex; align-items:center; justify-content:center; transition:background .3s; }
        .chatbot-close:hover { background:rgba(255,255,255,.2); }
        .quick-actions { padding:12px 20px; display:flex; gap:8px; flex-wrap:wrap; background:var(--bg); }
        .quick-action-btn { padding:8px 16px; background:white; border:2px solid var(--primary); color:var(--primary); border-radius:20px; font-size:12px; font-weight:500; cursor:pointer; transition:all .3s; font-family:'Poppins',sans-serif; }
        .quick-action-btn:hover { background:var(--primary); color:white; }
        .chatbot-messages { flex:1; padding:20px; overflow-y:auto; background:var(--bg); }
        .message { margin-bottom:16px; display:flex; gap:10px; animation:slideIn .3s ease; }
        @keyframes slideIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
        .message.user { flex-direction:row-reverse; }
        .message-avatar { width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0; }
        .message.bot  .message-avatar { background:var(--primary-soft); }
        .message.user .message-avatar { background:var(--primary); color:white; }
        .message-content { max-width:70%; padding:12px 16px; border-radius:12px; font-size:14px; line-height:1.5; }
        .message.bot  .message-content { background:white; color:var(--text-dark); border-bottom-left-radius:4px; }
        .message.user .message-content { background:var(--primary); color:white; border-bottom-right-radius:4px; }
        .chatbot-input-area { padding:16px; background:white; border-top:1px solid var(--border); }
        .chatbot-input-wrapper { display:flex; gap:10px; }
        .chatbot-input { flex:1; padding:12px 16px; border:2px solid var(--border); border-radius:24px; font-size:14px; font-family:'Poppins',sans-serif; outline:none; transition:border-color .3s; }
        .chatbot-input:focus { border-color:var(--primary); }
        .chatbot-send { width:44px; height:44px; background:var(--primary); color:white; border:none; border-radius:50%; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:18px; transition:background .3s; }
        .chatbot-send:hover { background:var(--primary-light); }
        .typing-indicator { display:flex; gap:4px; padding:12px 16px; }
        .typing-indicator span { width:8px; height:8px; background:var(--text-gray); border-radius:50%; animation:typing 1.4s infinite; }
        .typing-indicator span:nth-child(2) { animation-delay:.2s; }
        .typing-indicator span:nth-child(3) { animation-delay:.4s; }
        @keyframes typing { 0%,60%,100%{transform:translateY(0);opacity:.7} 30%{transform:translateY(-10px);opacity:1} }

        /* ── Main ── */
        .main-content { margin-left:275px; padding:40px 50px; }
        .page-header { margin-bottom:30px; }
        .page-header h2 { color:var(--primary); font-size:34px; font-weight:700; margin-bottom:6px; }
        .page-header p  { color:var(--text-gray); font-size:15px; }

        /* ── Profile card ── */
        .profile-card { background:white; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,.06); overflow:hidden; margin-bottom:24px; }
        .section-header { background:linear-gradient(90deg,var(--grad-s),var(--grad-e)); color:white; padding:14px 28px; font-size:15px; font-weight:600; display:flex; align-items:center; gap:10px; }
        .section-header small { font-size:11px; font-weight:400; opacity:.85; margin-left:6px; }
        .section-body { padding:24px 28px; }
        .section-divider { border:none; border-top:1px solid var(--border); margin:0; }

        /* ── Form grid ── */
        .form-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; }
        .form-grid.cols-2 { grid-template-columns:repeat(2,1fr); }
        .form-group { display:flex; flex-direction:column; }
        .form-group label { font-size:12px; font-weight:600; color:var(--text-dark); margin-bottom:6px; text-transform:uppercase; letter-spacing:.4px; }
        .form-group label .req { color:#dc2626; margin-left:2px; }
        .form-group label .lock-icon { font-size:10px; color:var(--text-gray); margin-left:4px; vertical-align:middle; }
        .form-group input, .form-group select, .form-group textarea { padding:10px 13px; border:1.5px solid var(--border); border-radius:8px; font-size:14px; font-family:'Poppins',sans-serif; color:var(--text-dark); transition:border-color .2s,background .2s,box-shadow .2s; outline:none; }

        /* Locked fields */
        .form-group input.locked { background:#f3f4f6; color:var(--text-gray); cursor:not-allowed; border-color:var(--border); }

        /* Read-only (before edit mode) */
        .form-group input[readonly]:not(.locked), .form-group textarea[readonly], .form-group select:disabled { background:#f9fafb; border-color:var(--border); color:var(--text-dark); cursor:default; }
        .form-group select:disabled { color:var(--text-dark); }

        /* Active editable */
        .form-group input:not([readonly]):not(.locked):focus, .form-group select:not(:disabled):focus, .form-group textarea:not([readonly]):focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(30,58,95,.07); background:white; }
        .form-group input:not([readonly]):not(.locked), .form-group select:not(:disabled), .form-group textarea:not([readonly]) { background:white; border-color:#d1d5db; }

        .field-error { font-size:11px; color:#dc2626; margin-top:4px; display:flex; align-items:center; gap:3px; font-weight:500; }
        .form-group input.is-invalid, .form-group select.is-invalid { border-color:#dc2626 !important; background:#fff5f5 !important; }

        .emergency-banner { display:flex; align-items:center; gap:10px; padding:10px 14px; background:var(--primary-soft); border:1px solid #93c5fd; border-radius:8px; margin-bottom:16px; }
        .emergency-banner i { color:var(--primary); font-size:16px; flex-shrink:0; }
        .emergency-banner span { font-size:12px; font-weight:600; color:var(--primary); }
        .emergency-banner .badge-req { margin-left:auto; background:var(--primary); color:white; font-size:10px; font-weight:600; padding:2px 9px; border-radius:20px; white-space:nowrap; }

        /* ── Action buttons ── */
        .profile-actions { display:flex; justify-content:flex-end; gap:12px; padding:20px 28px; border-top:1px solid var(--border); background:#fafafa; }
        .btn-profile { padding:10px 26px; border-radius:8px; font-size:14px; font-weight:600; border:none; cursor:pointer; transition:all .25s; font-family:'Poppins',sans-serif; display:inline-flex; align-items:center; gap:7px; text-decoration:none; }
        .btn-edit    { background:white; color:var(--primary); border:2px solid var(--primary); }
        .btn-edit:hover { background:var(--primary-soft); }

        /* Cancel — dark style */
        .btn-cancel  { background:#374151; color:white; border:2px solid #374151; }
        .btn-cancel:hover { background:#1f2937; border-color:#1f2937; }

        /* Save — green style */
        .btn-save    { background:#059669; color:white; border:2px solid #059669; }
        .btn-save:hover { background:#047857; border-color:#047857; }
        .btn-save:disabled { background:#9ca3af; border-color:#9ca3af; cursor:not-allowed; }

        .btn-logout  { background:var(--primary); color:white; }
        .btn-logout:hover { background:var(--primary-light); color:white; }
        .btn-hidden  { display:none !important; }

        .spin { width:14px; height:14px; border:2px solid rgba(255,255,255,.4); border-top-color:white; border-radius:50%; animation:sp .7s linear infinite; display:none; }
        @keyframes sp { to{transform:rotate(360deg)} }

        /* ── Logout modal ── */
        .modal-overlay { display:none; position:fixed; inset:0; z-index:2000; background:rgba(0,0,0,.5); backdrop-filter:blur(4px); align-items:center; justify-content:center; }
        .modal-overlay.show { display:flex; animation:mfade .25s ease; }
        @keyframes mfade { from{opacity:0} to{opacity:1} }
        .modal-box { background:white; border-radius:20px; max-width:440px; width:90%; text-align:center; box-shadow:0 24px 64px rgba(0,0,0,.2); animation:mpop .35s cubic-bezier(.34,1.56,.64,1); overflow:hidden; }
        @keyframes mpop { from{transform:scale(.85);opacity:0} to{transform:scale(1);opacity:1} }
        .logout-modal-header { background:linear-gradient(90deg,var(--grad-s),var(--grad-e)); color:white; padding:22px 30px; font-size:18px; font-weight:700; display:flex; align-items:center; gap:10px; }
        .logout-modal-body { padding:28px 32px 32px; }
        .logout-modal-body p { font-size:14px; color:var(--text-gray); line-height:1.7; margin-bottom:28px; }
        .logout-modal-actions { display:flex; justify-content:center; gap:14px; }
        .btn-logout-cancel { padding:10px 28px; border-radius:8px; font-size:14px; font-weight:600; border:2px solid var(--border); background:#f3f4f6; color:var(--text-dark); cursor:pointer; transition:all .25s; font-family:'Poppins',sans-serif; }
        .btn-logout-cancel:hover { background:#e5e7eb; }
        .btn-logout-confirm { padding:10px 28px; border-radius:8px; font-size:14px; font-weight:600; border:none; background:var(--primary); color:white; cursor:pointer; transition:background .25s; font-family:'Poppins',sans-serif; text-decoration:none; display:inline-flex; align-items:center; gap:7px; }
        .btn-logout-confirm:hover { background:var(--primary-light); color:white; }

        /* ── Success Toast — top right, green, like screenshot ── */
        .toast-success {
            position: fixed;
            top: 28px;
            right: 32px;
            z-index: 9999;
            background: #059669;
            color: white;
            padding: 18px 24px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 8px 32px rgba(0,0,0,.18);
            font-family: 'Poppins', sans-serif;
            min-width: 300px;
            max-width: 380px;
            transform: translateX(calc(100% + 50px));
            transition: transform .45s cubic-bezier(.34,1.56,.64,1);
        }
        .toast-success.show { transform: translateX(0); }
        .toast-icon { font-size: 26px; flex-shrink: 0; }
        .toast-text-title { font-size: 15px; font-weight: 700; }
        .toast-text-sub   { font-size: 12px; opacity: .9; margin-top: 2px; }

        @media (max-width:1200px) { .form-grid { grid-template-columns:repeat(2,1fr); } }
        @media (max-width:768px) {
            .sidebar { width:200px; }
            .main-content { margin-left:200px; padding:30px 20px; }
            .form-grid, .form-grid.cols-2 { grid-template-columns:1fr; }
            .section-body { padding:18px; }
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
        <a href="book_appointment.php" class="nav-item"><i class="bi bi-calendar-plus"></i><span>Book Appointment</span></a>
        <a href="medical_history.php"  class="nav-item"><i class="bi bi-clock-history"></i><span>Medical History</span></a>
        <a href="certificates.php"     class="nav-item"><i class="bi bi-file-earmark-medical"></i><span>Medical Certificates</span></a>
        <a href="profile.php"          class="nav-item active"><i class="bi bi-person"></i><span>Profile</span></a>
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
        <button class="quick-action-btn" data-message="How to edit profile">✏️ Edit Info</button>
        <button class="quick-action-btn" data-message="Update emergency contact">🚨 Emergency</button>
        <button class="quick-action-btn" data-message="What fields can I change">🔒 Locked Fields</button>
    </div>
    <div class="chatbot-messages" id="chatbotMessages">
        <div class="message bot">
            <div class="message-avatar">🤖</div>
            <div class="message-content">Hello! I can help you manage your profile. What do you need?</div>
        </div>
    </div>
    <div class="chatbot-input-area">
        <div class="chatbot-input-wrapper">
            <input type="text" class="chatbot-input" id="chatbotInput" placeholder="Type your message..." autocomplete="off">
            <button class="chatbot-send" id="chatbotSend"><i class="bi bi-send-fill"></i></button>
        </div>
    </div>
</div>

<!-- ── Success Toast ── -->
<div class="toast-success" id="toastSuccess">
    <div class="toast-icon"><i class="bi bi-check-circle-fill"></i></div>
    <div>
        <div class="toast-text-title">Profile Updated!</div>
        <div class="toast-text-sub">Your profile information has been successfully saved.</div>
    </div>
</div>

<!-- ── Main ── -->
<main class="main-content">
    <div class="page-header">
        <h2><i style="font-size:30px;vertical-align:middle;"></i>Employee Profile</h2>
        <p>Manage your personal information and emergency contact</p>
    </div>

    <form id="profileForm" novalidate>
        <div class="profile-card">

            <!-- Personal Information -->
            <div class="section-header">
                Personal Information
            </div>
            <div class="section-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Full Name <i class="bi bi-lock-fill lock-icon"></i></label>
                        <input type="text" value="<?= val($full_name) ?>" class="locked" readonly tabindex="-1">
                    </div>
                    <div class="form-group">
                        <label>Employee Number <i class="bi bi-lock-fill lock-icon"></i></label>
                        <input type="text" value="<?= val($p['employee_number'] ?? '') ?>" class="locked" readonly tabindex="-1">
                    </div>
                    <div class="form-group">
                        <label>Sex <i class="bi bi-lock-fill lock-icon"></i></label>
                        <input type="text" value="<?= val($p['sex'] ?? '') ?>" class="locked" readonly tabindex="-1">
                    </div>
                    <div class="form-group">
                        <label>Date of Birth <i class="bi bi-lock-fill lock-icon"></i></label>
                        <input type="text" value="<?= $p['date_of_birth'] ? date('F j, Y', strtotime($p['date_of_birth'])) : '' ?>" class="locked" readonly tabindex="-1">
                    </div>
                    <div class="form-group">
                        <label for="f_civil">Civil Status <span class="req">*</span></label>
                        <select id="f_civil" name="civil_status" disabled>
                            <?php foreach (['Single','Married','Widowed','Separated'] as $opt): ?>
                            <option value="<?= esc($opt) ?>" <?= ($p['civil_status'] ?? '') === $opt ? 'selected' : '' ?>><?= esc($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Campus <i class="bi bi-lock-fill lock-icon"></i></label>
                        <input type="text" value="<?= val($p['campus'] ?? '') ?>" class="locked" readonly tabindex="-1">
                    </div>
                    <div class="form-group">
                        <label>Department <i class="bi bi-lock-fill lock-icon"></i></label>
                        <input type="text" value="<?= val($p['department'] ?? '') ?>" class="locked" readonly tabindex="-1">
                    </div>
                    <div class="form-group">
                        <label>Position / Designation <i class="bi bi-lock-fill lock-icon"></i></label>
                        <input type="text" value="<?= val($p['position_designation'] ?? '') ?>" class="locked" readonly tabindex="-1">
                    </div>
                    <div class="form-group">
                        <label>Employment Type <i class="bi bi-lock-fill lock-icon"></i></label>
                        <input type="text" value="<?= val($p['employment_type'] ?? '') ?>" class="locked" readonly tabindex="-1">
                    </div>
                </div>
            </div>

            <hr class="section-divider">

            <!-- Contact Information -->
            <div class="section-header">
                Contact Information
                <small>Fields marked * can be updated</small>
            </div>
            <div class="section-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="f_email">Email Address <span class="req">*</span></label>
                        <input type="email" id="f_email" name="email" value="<?= val($p['email'] ?? '') ?>" placeholder="your@email.com" readonly>
                    </div>
                    <div class="form-group">
                        <label for="f_contact">Contact Number <span class="req">*</span></label>
                        <input type="tel" id="f_contact" name="contact_number" value="<?= val($p['contact_number'] ?? '') ?>" placeholder="09XX-XXX-XXXX" readonly>
                    </div>
                    <div class="form-group">
                        <label for="f_address">Address</label>
                        <input type="text" id="f_address" name="address" value="<?= val($p['address'] ?? '') ?>" placeholder="Street, City, Province" readonly>
                    </div>
                </div>
            </div>

            <hr class="section-divider">

            <!-- Emergency Contact -->
            <div class="section-header">
                Emergency Contact
            </div>
            <div class="section-body">
                <div class="emergency-banner">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>This information is critical in case of medical emergencies. Keep it accurate and up to date.</span>
                    <span class="badge-req">Required</span>
                </div>
                <div class="form-grid cols-2">
                    <div class="form-group">
                        <label for="f_ec_name">Emergency Contact Name <span class="req">*</span></label>
                        <input type="text" id="f_ec_name" name="ec_name" value="<?= val($p['emergency_contact_name'] ?? '') ?>" placeholder="Full name" readonly>
                    </div>
                    <div class="form-group">
                        <label for="f_ec_number">Emergency Contact Number <span class="req">*</span></label>
                        <input type="tel" id="f_ec_number" name="ec_number" value="<?= val($p['emergency_contact_number'] ?? '') ?>" placeholder="+63 9XX XXX XXXX" readonly>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="profile-actions">
                <!-- Cancel & Save hidden until Edit is clicked -->
                <button type="button" class="btn-profile btn-cancel btn-hidden" id="btnCancel">
                    <i class="bi bi-x-circle"></i> Cancel
                </button>
                <button type="button" class="btn-profile btn-save btn-hidden" id="btnSave">
                    <div class="spin" id="saveSpin"></div>
                    <i class="bi bi-check2-circle" id="saveIcon"></i> Save Changes
                </button>
                <!-- Edit Profile shown by default -->
                <button type="button" class="btn-profile btn-edit" id="btnEdit">
                    <i class="bi bi-pencil"></i> Edit Profile
                </button>
                <button type="button" class="btn-profile btn-logout" id="btnLogout">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </div>

        </div>
    </form>
</main>

<!-- ── Logout Confirmation Modal ── -->
<div class="modal-overlay" id="logoutModal">
    <div class="modal-box">
        <div class="logout-modal-header">
            <i class="bi bi-box-arrow-right"></i> Confirm Logout
        </div>
        <div class="logout-modal-body">
            <p>Are you sure you want to logout? You will be redirected to the login page.</p>
            <div class="logout-modal-actions">
                <button class="btn-logout-cancel" id="btnLogoutCancel">Cancel</button>
                <a href="../index.php" class="btn-logout-confirm">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script>
const editableInputIds  = ['f_email','f_contact','f_address','f_ec_name','f_ec_number'];
const editableSelectIds = ['f_civil'];
let originalValues = {};

const btnEdit   = document.getElementById('btnEdit');
const btnCancel = document.getElementById('btnCancel');
const btnSave   = document.getElementById('btnSave');

// ── Enter edit mode ────────────────────────────────────────
function enterEditMode() {
    editableInputIds.forEach(id => {
        const el = document.getElementById(id);
        if (el) { originalValues[id] = el.value; el.removeAttribute('readonly'); }
    });
    editableSelectIds.forEach(id => {
        const el = document.getElementById(id);
        if (el) { originalValues[id] = el.value; el.removeAttribute('disabled'); }
    });
    btnEdit.classList.add('btn-hidden');
    btnCancel.classList.remove('btn-hidden');
    btnSave.classList.remove('btn-hidden');
    document.getElementById('f_email').focus();
}

// ── Exit edit mode ─────────────────────────────────────────
function exitEditMode() {
    editableInputIds.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.setAttribute('readonly','');
            el.classList.remove('is-invalid');
            const err = el.parentElement.querySelector('.field-error');
            if (err) err.remove();
        }
    });
    editableSelectIds.forEach(id => {
        const el = document.getElementById(id);
        if (el) { el.setAttribute('disabled',''); el.classList.remove('is-invalid'); }
    });
    btnEdit.classList.remove('btn-hidden');
    btnCancel.classList.add('btn-hidden');
    btnSave.classList.add('btn-hidden');
}

btnEdit.addEventListener('click', enterEditMode);

btnCancel.addEventListener('click', () => {
    editableInputIds.forEach(id => {
        const el = document.getElementById(id);
        if (el && originalValues[id] !== undefined) el.value = originalValues[id];
    });
    editableSelectIds.forEach(id => {
        const el = document.getElementById(id);
        if (el && originalValues[id] !== undefined) el.value = originalValues[id];
    });
    exitEditMode();
});

// ── Validation ─────────────────────────────────────────────
function showFieldError(id, msg) {
    const el = document.getElementById(id); if (!el) return;
    el.classList.add('is-invalid');
    let err = el.parentElement.querySelector('.field-error');
    if (!err) { err = document.createElement('div'); err.className = 'field-error'; el.parentElement.appendChild(err); }
    err.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${msg}`;
}
function clearFieldError(id) {
    const el = document.getElementById(id); if (!el) return;
    el.classList.remove('is-invalid');
    const err = el.parentElement.querySelector('.field-error'); if (err) err.remove();
}
['f_email','f_contact','f_ec_name','f_ec_number'].forEach(id => {
    const el = document.getElementById(id); if (!el) return;
    el.addEventListener('input', () => { if (el.classList.contains('is-invalid')) validateField(id); });
    el.addEventListener('blur',  () => validateField(id));
});
function validateField(id) {
    const el = document.getElementById(id); if (!el) return true;
    const v = el.value.trim();
    if (id === 'f_email')    { if (!v) { showFieldError(id,'Email is required.'); return false; } if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) { showFieldError(id,'Enter a valid email.'); return false; } }
    if (id === 'f_contact')  { if (!v) { showFieldError(id,'Contact number is required.'); return false; } if (!/^[0-9+\-\s]{7,15}$/.test(v)) { showFieldError(id,'Enter a valid contact number.'); return false; } }
    if (id === 'f_ec_name')  { if (!v) { showFieldError(id,'Emergency contact name is required.'); return false; } }
    if (id === 'f_ec_number'){ if (!v) { showFieldError(id,'Emergency contact number is required.'); return false; } if (!/^[0-9+\-\s]{7,15}$/.test(v)) { showFieldError(id,'Enter a valid number.'); return false; } }
    clearFieldError(id); return true;
}
function validateAll() {
    let ok = true;
    ['f_email','f_contact','f_ec_name','f_ec_number'].forEach(id => { if (!validateField(id)) ok = false; });
    return ok;
}

// ── Save — direct AJAX, no popup ──────────────────────────
btnSave.addEventListener('click', async () => {
    if (!validateAll()) {
        const first = document.querySelector('.is-invalid');
        if (first) first.scrollIntoView({ behavior:'smooth', block:'center' });
        return;
    }

    btnSave.disabled = true;
    document.getElementById('saveSpin').style.display = 'block';
    document.getElementById('saveIcon').style.display = 'none';

    const data = new FormData();
    data.append('ajax_update',    '1');
    data.append('email',          document.getElementById('f_email').value.trim());
    data.append('contact_number', document.getElementById('f_contact').value.trim());
    data.append('address',        document.getElementById('f_address').value.trim());
    data.append('civil_status',   document.getElementById('f_civil').value);
    data.append('ec_name',        document.getElementById('f_ec_name').value.trim());
    data.append('ec_number',      document.getElementById('f_ec_number').value.trim());

    try {
        const resp = await fetch('profile.php', { method:'POST', body:data });
        const json = await resp.json();

        if (json.success) {
            exitEditMode();
            showToast(); // ← green top-right toast, no popup
        } else {
            (json.errors || ['An unexpected error occurred.']).forEach((msg, i) => {
                const fieldMap = {0:'f_email',1:'f_contact',2:'f_ec_name',3:'f_ec_number'};
                if (fieldMap[i]) showFieldError(fieldMap[i], msg);
            });
        }
    } catch (e) {
        alert('Network error. Please check your connection and try again.');
        console.error(e);
    } finally {
        btnSave.disabled = false;
        document.getElementById('saveSpin').style.display = 'none';
        document.getElementById('saveIcon').style.display = '';
    }
});

// ── Toast notification ─────────────────────────────────────
function showToast() {
    const toast = document.getElementById('toastSuccess');
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 4000);
}

// ── Logout Modal ───────────────────────────────────────────
document.getElementById('btnLogout').addEventListener('click', () => {
    document.getElementById('logoutModal').classList.add('show');
    document.body.style.overflow = 'hidden';
});
document.getElementById('btnLogoutCancel').addEventListener('click', () => {
    document.getElementById('logoutModal').classList.remove('show');
    document.body.style.overflow = '';
});
document.getElementById('logoutModal').addEventListener('click', function(e) {
    if (e.target === this) { this.classList.remove('show'); document.body.style.overflow = ''; }
});

// ── Chatbot ────────────────────────────────────────────────
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
    const text = messageText || chatbotInput.value.trim(); if (!text) return;
    addMessage(text,'user'); chatbotInput.value = '';
    showTypingIndicator();
    setTimeout(() => { hideTypingIndicator(); addMessage(getBotResponse(text),'bot'); }, 900 + Math.random() * 800);
}
function addMessage(text, sender) {
    const div = document.createElement('div'); div.className = `message ${sender}`;
    div.innerHTML = `<div class="message-avatar">${sender==='bot'?'🤖':'👤'}</div><div class="message-content">${text}</div>`;
    chatbotMessages.appendChild(div); chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
}
function showTypingIndicator() {
    const div = document.createElement('div'); div.className = 'message bot'; div.id = 'typingIndicator';
    div.innerHTML = `<div class="message-avatar">🤖</div><div class="message-content typing-indicator"><span></span><span></span><span></span></div>`;
    chatbotMessages.appendChild(div); chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
}
function hideTypingIndicator() { const el = document.getElementById('typingIndicator'); if (el) el.remove(); }
function getBotResponse(message) {
    const m = message.toLowerCase();
    if (m.includes('edit')||m.includes('update')||m.includes('change'))
        return "Click <strong>Edit Profile</strong> to unlock editable fields. Click <strong>Save Changes</strong> when done.";
    if (m.includes('field')||m.includes('lock')||m.includes('editable'))
        return "Fields marked 🔒 are locked. You can update: <strong>civil status, email, contact, address, and emergency contact</strong>.";
    if (m.includes('emergency')||m.includes('contact'))
        return "Your emergency contact is at the bottom. Click <strong>Edit Profile</strong> to update it.";
    if (m.includes('save'))
        return "Click <strong>Save Changes</strong> to save. A green notification will appear at the top right when done.";
    if (m.includes('hello')||m.includes('hi'))
        return "Hello! I can help you manage your profile. What would you like to know?";
    return "I can help with editing profile fields or understanding which fields can be changed. What do you need?";
}
chatbotSend.addEventListener('click', () => sendMessage());
chatbotInput.addEventListener('keypress', e => { if (e.key === 'Enter') sendMessage(); });
</script>
</body>
</html>