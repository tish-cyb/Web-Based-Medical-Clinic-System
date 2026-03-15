<?php
// ============================================================
//  book_appointment.php  — Student & Employee Portal
//  Saves a new appointment to the `appointments` table.
//  patient_type is determined from $_SESSION['role'].
// ============================================================
session_start();
require_once "../config/db.php";   // provides $c (mysqli connection)

// ── GUARD: must be logged in as student or employee ──────────
if (!isset($_SESSION['user_id']) ||
    !in_array($_SESSION['role'], ['student', 'employee'])) {
    header("Location: ../index.php");
    exit();
}

$role       = $_SESSION['role'];          // 'student' or 'employee'
$profile_id = $_SESSION['profile_id'];    // student_id or employee_id
$user_id    = $_SESSION['user_id'];

// ── PRE-FILL: load profile data to auto-fill the form ────────
$prefill = [];

if ($role === 'student') {
    $q = mysqli_prepare($c,
        "SELECT CONCAT(last_name, ', ', first_name, ' ', IFNULL(middle_name,'')) AS full_name,
                student_number, email, contact_number, program, year_level
         FROM   students WHERE student_id = ? LIMIT 1");
    mysqli_stmt_bind_param($q, "i", $profile_id);
} else {
    $q = mysqli_prepare($c,
        "SELECT CONCAT(last_name, ', ', first_name, ' ', IFNULL(middle_name,'')) AS full_name,
                employee_number AS student_number, email, contact_number,
                position_designation AS program, department AS year_level
         FROM   employees WHERE employee_id = ? LIMIT 1");
    mysqli_stmt_bind_param($q, "i", $profile_id);
}
mysqli_stmt_execute($q);
$prefill = mysqli_fetch_assoc(mysqli_stmt_get_result($q)) ?? [];

// ── HANDLE FORM SUBMISSION ───────────────────────────────────
$save_ok          = isset($_GET['success']) && $_GET['success'] === '1';
$new_id           = (int)($_GET['ref']  ?? 0);
$service_category = $_GET['cat']  ?? '';
$appointment_date = $_GET['date'] ?? '';
$appointment_time = $_GET['time'] ?? '';
$full_name        = $_GET['name'] ?? trim($prefill['full_name'] ?? '');
$error_msg        = '';
$field_errors     = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_appointment'])) {

    // 1. Read and sanitise inputs
    $service_category  = trim($_POST['service_category']  ?? '');
    $appointment_date  = trim($_POST['appointment_date']  ?? '');
    $appointment_time  = trim($_POST['appointment_time']  ?? '');
    $reason_for_visit  = trim($_POST['reason_for_visit']  ?? '');
    $consent_given     = isset($_POST['terms']) ? 1 : 0;

    $full_name        = trim($_POST['full_name']       ?? $prefill['full_name']       ?? '');
    $id_number        = trim($_POST['id_number']       ?? $prefill['student_number']  ?? '');
    $email_input      = trim($_POST['email_input']     ?? $prefill['email']           ?? '');
    $contact_number   = trim($_POST['contact_number']  ?? $prefill['contact_number']  ?? '');
    $program_input    = trim($_POST['program_input']   ?? $prefill['program']         ?? '');
    $level_input      = trim($_POST['level_input']     ?? $prefill['year_level']      ?? '');

    // 2. Server-side field-level validation
    $allowed_categories = [
        'General Consultation', 'First Aid / Injury Care',
        'Medical Clearance', 'Follow-Up Checkup', 'Health Counseling'
    ];

    if (!in_array($service_category, $allowed_categories))
        $field_errors['service_category'] = "Please select a valid service category.";

    if (empty($appointment_date))
        $field_errors['appointment_date'] = "Preferred date is required.";
    elseif ($appointment_date < date('Y-m-d'))
        $field_errors['appointment_date'] = "Appointment date cannot be in the past.";

    if (empty($appointment_time))
        $field_errors['appointment_time'] = "Preferred time is required.";
    else {
        // Enforce clinic hours: 08:00–17:00
        $t = strtotime($appointment_time);
        if ($t < strtotime('08:00') || $t > strtotime('17:00'))
            $field_errors['appointment_time'] = "Please choose a time between 8:00 AM and 9:00 PM.";
    }

    if (empty($reason_for_visit))
        $field_errors['reason_for_visit'] = "Reason for visit is required.";
    elseif (strlen($reason_for_visit) < 10)
        $field_errors['reason_for_visit'] = "Please provide at least 10 characters describing your reason.";

    if (empty($full_name))
        $field_errors['full_name'] = "Full name is required.";

    if (empty($id_number))
        $field_errors['id_number'] = ($role === 'student' ? "Student" : "Employee") . " number is required.";

    if (empty($email_input))
        $field_errors['email_input'] = "Email address is required.";
    elseif (!filter_var($email_input, FILTER_VALIDATE_EMAIL))
        $field_errors['email_input'] = "Please enter a valid email address.";

    if (empty($contact_number))
        $field_errors['contact_number'] = "Contact number is required.";
    elseif (!preg_match('/^[0-9+\-\s]{7,15}$/', $contact_number))
        $field_errors['contact_number'] = "Enter a valid contact number (e.g., 09171234567).";

    if (!$consent_given)
        $field_errors['terms'] = "You must agree to the Privacy Notice and Terms.";

    // 3. Insert if no errors
    if (empty($field_errors)) {
        $bind_student  = ($role === 'student')  ? (int)$profile_id : null;
        $bind_employee = ($role === 'employee') ? (int)$profile_id : null;
        $status        = 'Pending';
        $patient_type  = $role;

        $ins = mysqli_prepare($c, "
            INSERT INTO appointments
                (student_id, employee_id, staff_id, patient_type,
                 service_category, appointment_date, appointment_time,
                 reason_for_visit, status, consent_given, created_at)
            VALUES
                (?, ?, NULL, ?,
                 ?, ?, ?,
                 ?, ?, ?, NOW())
        ");

        // Use "s" for nullable int FKs so NULL passes cleanly across PHP versions
        mysqli_stmt_bind_param($ins, "ssssssssi",
            $bind_student,
            $bind_employee,
            $patient_type,
            $service_category,
            $appointment_date,
            $appointment_time,
            $reason_for_visit,
            $status,
            $consent_given
        );

        if (mysqli_stmt_execute($ins)) {
            $new_id = (int)mysqli_insert_id($c);
            // POST-Redirect-GET pattern: clears form on reload
            header('Location: book_appointment.php'
                . '?success=1'
                . '&ref='  . $new_id
                . '&cat='  . urlencode($service_category)
                . '&date=' . urlencode($appointment_date)
                . '&time=' . urlencode($appointment_time)
                . '&name=' . urlencode($full_name)
            );
            exit();
        } else {
            $error_msg = "Database error: " . mysqli_error($c) . " — please try again.";
        }
    } else {
        $error_msg = "Please fix the errors below before submitting.";
    }
}

// ── HELPERS ──────────────────────────────────────────────────
$P = $_POST ?? [];

function fval(string $key, array $post, array $prefill, string $prefill_key = ''): string {
    if (isset($post[$key]) && $post[$key] !== '') return htmlspecialchars($post[$key]);
    $pk = $prefill_key ?: $key;
    if (!empty($prefill[$pk])) return htmlspecialchars($prefill[$pk]);
    return '';
}

function ferr(string $key, array $errs): string {
    if (!isset($errs[$key])) return '';
    return '<div class="field-error"><i class="bi bi-exclamation-circle"></i> ' . htmlspecialchars($errs[$key]) . '</div>';
}

function fclass(string $key, array $errs): string {
    return isset($errs[$key]) ? ' is-invalid' : '';
}

$portal_title   = $role === 'student' ? 'Student Portal'  : 'Employee Portal';
$id_label       = $role === 'student' ? 'Student Number'  : 'Employee Number';
$id_placeholder = $role === 'student' ? 'e.g., 2023-12345-MN-0' : 'e.g., EMP-00123';
$prog_label     = $role === 'student' ? 'Program / Course' : 'Position / Designation';
$level_label    = $role === 'student' ? 'Year Level'       : 'Department';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($portal_title) ?> — Book Appointment</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary:         #7f1d1d;
            --primary-light:   #b91c1c;
            --primary-soft:    #fef2f2;
            --primary-grad-s:  #7f1d1d;
            --primary-grad-e:  #ef4444;
            --text-dark:       #1f2937;
            --text-gray:       #6b7280;
            --bg-body:         #f3f4f6;
            --success:         #059669;
            --danger:          #dc2626;
            --warning:         #d97706;
            --border:          #e5e7eb;
            --radius:          8px;
            --shadow-card:     0 2px 12px rgba(0,0,0,.07);
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-body);
            min-height: 100vh;
            color: var(--text-dark);
        }

        /* ─── Sidebar ─────────────────────────────────── */
        .sidebar {
            width: 275px;
            background: linear-gradient(180deg, #860303 3%, #B21414 79%, #940000 97%);
            color: white;
            position: fixed;
            height: 100vh;
            left: 0; top: 0;
            display: flex; flex-direction: column;
            box-shadow: 4px 0 10px rgba(0,0,0,.12);
            z-index: 1000;
        }
        .sidebar-header { padding: 35px 25px; background: linear-gradient(180deg,#860303,#6B0000); }
        .sidebar-header h1 { font-size: 28px; font-weight: 700; margin-bottom: 5px; letter-spacing: -.5px; }
        .sidebar-header p  { font-size: 14px; opacity: .9; margin: 0; }
        .sidebar-nav { flex: 1; padding-top: 20px; }
        .nav-item {
            padding: 16px 32px; cursor: pointer;
            transition: all .25s; font-size: 15px; font-weight: 500;
            border-left: 4px solid transparent;
            display: flex; align-items: center; gap: 12px;
            color: rgba(255,255,255,.9); text-decoration: none;
        }
        .nav-item i { font-size: 18px; }
        .nav-item:hover  { background: rgba(255,255,255,.1); color: white; }
        .nav-item.active { background: rgba(0,0,0,.2); border-left-color: white; color: white; }
        .sidebar-footer { padding: 20px; display: flex; justify-content: center; }
        .chatbot-toggle {
            width: 60px; height: 60px; background: white; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 28px; box-shadow: 0 4px 8px rgba(0,0,0,.2);
            cursor: pointer; transition: transform .3s;
        }
        .chatbot-toggle:hover { transform: scale(1.1); }

        /* ─── Chatbot ─────────────────────────────────── */
        .chatbot-container {
            position: fixed; bottom: 20px; left: 295px;
            width: 380px; height: 550px;
            background: white; border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,.15);
            display: none; flex-direction: column;
            z-index: 1001; overflow: hidden;
        }
        .chatbot-container.show { display: flex; }
        .chatbot-header {
            background: linear-gradient(90deg,var(--primary-grad-s),var(--primary-grad-e));
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
        .quick-actions { padding: 12px 20px; display: flex; gap: 8px; flex-wrap: wrap; background: var(--bg-body); }
        .quick-action-btn {
            padding: 8px 16px; background: white;
            border: 2px solid var(--primary); color: var(--primary);
            border-radius: 20px; font-size: 12px; font-weight: 500;
            cursor: pointer; transition: all .3s; font-family: 'Poppins',sans-serif;
        }
        .quick-action-btn:hover { background: var(--primary); color: white; }
        .chatbot-messages { flex: 1; padding: 20px; overflow-y: auto; background: var(--bg-body); }
        .message { margin-bottom: 16px; display: flex; gap: 10px; }
        .message.user { flex-direction: row-reverse; }
        .message-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; flex-shrink: 0;
        }
        .message.bot  .message-avatar { background: var(--primary-soft); }
        .message.user .message-avatar { background: var(--primary); color: white; }
        .message-content {
            max-width: 70%; padding: 12px 16px; border-radius: 12px;
            font-size: 14px; line-height: 1.5;
        }
        .message.bot  .message-content { background: white; color: var(--text-dark); border-bottom-left-radius: 4px; }
        .message.user .message-content { background: var(--primary); color: white; border-bottom-right-radius: 4px; }
        .chatbot-input-area { padding: 16px; background: white; border-top: 1px solid var(--border); }
        .chatbot-input-wrapper { display: flex; gap: 10px; }
        .chatbot-input {
            flex: 1; padding: 12px 16px;
            border: 2px solid var(--border); border-radius: 24px;
            font-size: 14px; font-family: 'Poppins',sans-serif;
            outline: none; transition: border-color .3s;
        }
        .chatbot-input:focus { border-color: var(--primary); }
        .chatbot-send {
            width: 44px; height: 44px; background: var(--primary);
            color: white; border: none; border-radius: 50%;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            font-size: 18px; transition: background .3s;
        }
        .chatbot-send:hover { background: var(--primary-light); }
        .typing-indicator { display: flex; gap: 4px; padding: 12px 16px; }
        .typing-indicator span {
            width: 8px; height: 8px; background: var(--text-gray);
            border-radius: 50%; animation: typing 1.4s infinite;
        }
        .typing-indicator span:nth-child(2) { animation-delay: .2s; }
        .typing-indicator span:nth-child(3) { animation-delay: .4s; }
        @keyframes typing {
            0%, 60%, 100% { transform: translateY(0); opacity: .7; }
            30%            { transform: translateY(-10px); opacity: 1; }
        }

        /* ─── Main Content ────────────────────────────── */
        .main-content { margin-left: 275px; padding: 40px 50px; }
        .page-header { margin-bottom: 30px; }
        .page-header h2 { color: var(--primary); font-size: 34px; font-weight: 700; margin-bottom: 6px; }
        .page-header p  { color: var(--text-gray); font-size: 15px; }

        /* ─── Card ────────────────────────────────────── */
        .form-card { background: white; border-radius: 14px; box-shadow: var(--shadow-card); overflow: hidden; }
        .form-card-header {
            background: linear-gradient(90deg,var(--primary-grad-s),var(--primary-grad-e));
            color: white; padding: 22px 30px;
            font-size: 20px; font-weight: 600;
            display: flex; align-items: center; gap: 10px;
        }
        .form-card-body { padding: 36px; }

        /* ─── Section Divider ─────────────────────────── */
        .section-divider {
            font-size: 12px; font-weight: 700;
            color: var(--primary);
            text-transform: uppercase; letter-spacing: 1px;
            border-bottom: 2px solid var(--primary-soft);
            padding-bottom: 9px;
            margin: 32px 0 22px;
        }
        .section-divider:first-child { margin-top: 0; }

        /* ─── Form Grid ───────────────────────────────── */
        .form-grid-3 { display: grid; grid-template-columns: repeat(3,1fr); gap: 20px; }
        .form-grid-2 { display: grid; grid-template-columns: repeat(2,1fr); gap: 20px; }
        .form-group  { display: flex; flex-direction: column; }
        .form-group label {
            font-size: 13px; font-weight: 600;
            color: var(--text-dark); margin-bottom: 7px;
        }
        .form-group label .req { color: var(--primary-light); margin-left: 2px; }

        /* ─── Inputs ──────────────────────────────────── */
        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 11px 14px;
            border: 1.5px solid var(--border); border-radius: var(--radius);
            font-size: 14px; font-family: 'Poppins',sans-serif;
            color: var(--text-dark); background: #fafafa;
            transition: border-color .2s, background .2s, box-shadow .2s;
            outline: none;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 3px rgba(127,29,29,.07);
        }
        .form-group select  { cursor: pointer; }
        .form-group textarea{ resize: vertical; min-height: 110px; }

        /* Invalid state */
        .form-group input.is-invalid,
        .form-group select.is-invalid,
        .form-group textarea.is-invalid {
            border-color: var(--danger) !important;
            background: #fff5f5;
        }
        .field-error {
            font-size: 12px; color: var(--danger);
            margin-top: 5px; display: flex; align-items: center; gap: 4px;
            font-weight: 500;
        }
        .field-error i { font-size: 13px; flex-shrink: 0; }

        /* ─── Consent ─────────────────────────────────── */
        .consent-group {
            display: flex; align-items: flex-start; gap: 12px;
            background: var(--primary-soft);
            border: 1px solid #fca5a5;
            border-radius: var(--radius); padding: 16px 18px;
            margin-top: 10px; transition: border-color .2s;
        }
        .consent-group.invalid { border-color: var(--danger); background: #fff5f5; }
        .consent-group input[type="checkbox"] {
            margin-top: 2px; width: 17px; height: 17px;
            accent-color: var(--primary); cursor: pointer; flex-shrink: 0;
        }
        .consent-group label {
            font-size: 13px; color: var(--text-gray); line-height: 1.6; cursor: pointer;
        }

        /* ─── Alerts ──────────────────────────────────── */
        .alert {
            padding: 14px 18px; border-radius: var(--radius);
            font-size: 14px; margin-bottom: 24px;
            display: flex; align-items: flex-start; gap: 10px;
        }
        .alert i { font-size: 18px; flex-shrink: 0; margin-top: 1px; }
        .alert-danger  { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        /* ─── Submit Button ───────────────────────────── */
        .form-actions {
            display: flex; justify-content: flex-end; gap: 12px;
            padding-top: 28px; margin-top: 10px;
            border-top: 1px solid #f0f0f0;
        }
        .btn-submit {
            background: var(--primary); color: white;
            padding: 13px 40px; border-radius: var(--radius);
            font-size: 15px; font-weight: 600; border: none;
            cursor: pointer; transition: background .25s, transform .15s;
            display: inline-flex; align-items: center; gap: 8px;
            font-family: 'Poppins',sans-serif;
        }
        .btn-submit:hover  { background: var(--primary-light); transform: translateY(-1px); }
        .btn-submit:active { transform: translateY(0); }
        .btn-submit:disabled { background: #9ca3af; cursor: not-allowed; transform: none; }

        /* ─── Progress Steps ──────────────────────────── */
        .progress-steps {
            display: flex; align-items: center;
            margin-bottom: 32px; gap: 0;
        }
        .step {
            display: flex; align-items: center; gap: 8px;
            font-size: 13px; font-weight: 600;
            color: var(--text-gray);
        }
        .step-num {
            width: 28px; height: 28px; border-radius: 50%;
            background: #e5e7eb; color: var(--text-gray);
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; flex-shrink: 0;
            transition: all .3s;
        }
        .step.active .step-num { background: var(--primary); color: white; }
        .step.active           { color: var(--primary); }
        .step.done .step-num   { background: var(--success); color: white; }
        .step-line {
            flex: 1; height: 2px; background: #e5e7eb;
            margin: 0 12px; transition: background .3s;
        }
        .step-line.active { background: var(--primary); }

        /* ─── Confirmation Modal ──────────────────────── */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,.55);
            display: flex; align-items: center; justify-content: center;
            z-index: 2000;
            opacity: 0; visibility: hidden;
            transition: opacity .3s;
        }
        .modal-overlay.show { opacity: 1; visibility: visible; }
        .modal-box {
            background: white; border-radius: 20px;
            padding: 0;
            max-width: 520px; width: 92%;
            box-shadow: 0 24px 64px rgba(0,0,0,.22);
            transform: translateY(28px) scale(.97);
            transition: transform .35s cubic-bezier(.34,1.56,.64,1);
            overflow: hidden;
        }
        .modal-overlay.show .modal-box { transform: translateY(0) scale(1); }

        /* Confirm modal */
        .confirm-modal-header {
            background: linear-gradient(135deg,var(--primary-grad-s),var(--primary-grad-e));
            padding: 28px 32px 22px;
            color: white; text-align: center;
        }
        .confirm-modal-header .icon-wrap {
            width: 64px; height: 64px; border-radius: 50%;
            background: rgba(255,255,255,.2);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px; font-size: 30px;
        }
        .confirm-modal-header h3 { font-size: 20px; font-weight: 700; margin: 0 0 6px; }
        .confirm-modal-header p  { font-size: 13px; opacity: .85; margin: 0; }
        .confirm-modal-body { padding: 28px 32px; }
        .confirm-grid {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 14px; margin-bottom: 20px;
        }
        .confirm-item label {
            font-size: 11px; font-weight: 700;
            color: var(--text-gray); text-transform: uppercase;
            letter-spacing: .7px; display: block; margin-bottom: 4px;
        }
        .confirm-item span {
            font-size: 14px; font-weight: 600; color: var(--text-dark);
        }
        .confirm-reason-box {
            background: var(--bg-body); border-radius: var(--radius);
            padding: 12px 16px; margin-bottom: 20px;
        }
        .confirm-reason-box label {
            font-size: 11px; font-weight: 700;
            color: var(--text-gray); text-transform: uppercase;
            letter-spacing: .7px; display: block; margin-bottom: 6px;
        }
        .confirm-reason-box p {
            font-size: 13px; color: var(--text-dark);
            line-height: 1.55; margin: 0;
        }
        .confirm-notice {
            background: #fffbeb; border: 1px solid #fde68a;
            border-radius: var(--radius); padding: 11px 14px;
            font-size: 12px; color: #92400e;
            display: flex; align-items: flex-start; gap: 8px;
            margin-bottom: 24px;
        }
        .confirm-notice i { flex-shrink: 0; font-size: 14px; }
        .confirm-actions { display: flex; gap: 12px; }
        .btn-confirm {
            flex: 1; padding: 13px;
            background: var(--primary); color: white;
            border: none; border-radius: var(--radius);
            font-size: 15px; font-weight: 600; cursor: pointer;
            font-family: 'Poppins',sans-serif;
            transition: background .25s; display: flex; align-items: center;
            justify-content: center; gap: 8px;
        }
        .btn-confirm:hover { background: var(--primary-light); }
        .btn-back {
            flex: 0 0 auto; padding: 13px 22px;
            background: #f3f4f6; color: var(--text-dark);
            border: none; border-radius: var(--radius);
            font-size: 14px; font-weight: 600; cursor: pointer;
            font-family: 'Poppins',sans-serif; transition: background .25s;
        }
        .btn-back:hover { background: #e5e7eb; }

        /* Success Modal */
        .success-modal-body { padding: 40px 36px; text-align: center; }
        .success-icon-wrap {
            width: 82px; height: 82px; border-radius: 50%;
            background: linear-gradient(135deg,#d1fae5,#a7f3d0);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 22px;
            animation: popIn .5s cubic-bezier(.34,1.56,.64,1);
        }
        @keyframes popIn {
            from { transform: scale(.4); opacity: 0; }
            to   { transform: scale(1); opacity: 1; }
        }
        .success-icon-wrap i { font-size: 40px; color: var(--success); }
        .success-modal-body .modal-title {
            font-size: 22px; font-weight: 700; color: var(--text-dark); margin-bottom: 8px;
        }
        .success-modal-body .modal-subtitle {
            font-size: 14px; color: var(--text-gray); line-height: 1.6; margin-bottom: 20px;
        }
        .modal-ref {
            display: inline-block;
            background: var(--primary-soft); border: 1px solid #fca5a5;
            color: var(--primary); font-size: 15px; font-weight: 700;
            padding: 9px 28px; border-radius: 50px; margin-bottom: 24px;
            letter-spacing: .5px;
        }
        .modal-details {
            background: #f9fafb; border-radius: 12px;
            padding: 16px 20px; margin-bottom: 24px; text-align: left;
        }
        .detail-row {
            display: flex; justify-content: space-between; align-items: center;
            font-size: 13px; padding: 7px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: var(--text-gray); font-weight: 500; }
        .detail-value { color: var(--text-dark); font-weight: 600; }
        .modal-status-badge {
            background: #fff3cd; color: #856404;
            padding: 3px 12px; border-radius: 20px;
            font-size: 12px; font-weight: 600;
        }
        .modal-actions { display: flex; gap: 12px; justify-content: center; }
        .btn-modal-primary {
            background: var(--primary); color: white;
            padding: 12px 28px; border-radius: var(--radius);
            font-size: 14px; font-weight: 600; border: none;
            cursor: pointer; transition: background .25s;
            font-family: 'Poppins',sans-serif;
        }
        .btn-modal-primary:hover { background: var(--primary-light); }
        .btn-modal-secondary {
            background: #f3f4f6; color: var(--text-dark);
            padding: 12px 28px; border-radius: var(--radius);
            font-size: 14px; font-weight: 600; border: none;
            cursor: pointer; transition: background .25s;
            text-decoration: none; display: inline-flex; align-items: center;
            font-family: 'Poppins',sans-serif;
        }
        .btn-modal-secondary:hover { background: #e5e7eb; }

        /* ─── Responsive ──────────────────────────────── */
        @media (max-width: 1100px) { .form-grid-3 { grid-template-columns: repeat(2,1fr); } }
        @media (max-width: 768px) {
            .sidebar { width: 200px; }
            .main-content { margin-left: 200px; padding: 30px 20px; }
            .form-grid-3, .form-grid-2 { grid-template-columns: 1fr; }
            .form-card-body { padding: 24px; }
            .confirm-grid { grid-template-columns: 1fr; }
            .confirm-modal-body { padding: 22px; }
        }
    </style>
</head>
<body>

<!-- ── Sidebar ── -->
<div class="sidebar">
    <div class="sidebar-header">
        <h1><?= htmlspecialchars($portal_title) ?></h1>
        <p>Medical Services</p>
    </div>
    <nav class="sidebar-nav">
        <a href="book_appointment.php" class="nav-item active">
            <i class="bi bi-calendar-plus"></i><span>Book Appointment</span>
        </a>
        <a href="medical_history.php" class="nav-item">
            <i class="bi bi-clock-history"></i><span>Medical History</span>
        </a>
        <a href="certificates.php" class="nav-item">
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
        <button class="quick-action-btn" data-message="Help with booking">📅 Booking Help</button>
        <button class="quick-action-btn" data-message="Available time slots">⏰ Time Slots</button>
        <button class="quick-action-btn" data-message="What documents do I need">📄 Requirements</button>
    </div>
    <div class="chatbot-messages" id="chatbotMessages">
        <div class="message bot">
            <div class="message-avatar">🤖</div>
            <div class="message-content">Hello! I'm here to help you book an appointment. What would you like to know?</div>
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
        <h2><i style="font-size:30px;vertical-align:middle;"></i>Book Appointment</h2>
        <p>Schedule your medical consultation or clearance at the AiCare Clinic</p>
    </div>

    <!-- Progress Steps -->
    <div class="progress-steps" id="progressSteps">
        <div class="step active" id="step1">
            <div class="step-num">1</div>
            <span>Fill Form</span>
        </div>
        <div class="step-line" id="line1"></div>
        <div class="step" id="step2">
            <div class="step-num">2</div>
            <span>Review</span>
        </div>
        <div class="step-line" id="line2"></div>
        <div class="step" id="step3">
            <div class="step-num">3</div>
            <span>Confirmed</span>
        </div>
    </div>

    <!-- PHP error banner (server-side) -->
    <?php if ($error_msg && empty($field_errors)): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span><?= $error_msg ?></span>
        </div>
    <?php elseif ($error_msg && !empty($field_errors)): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span><?= $error_msg ?></span>
        </div>
    <?php endif; ?>

    <!-- PHP → JS data bridge for success modal -->
    <div id="appt-result"
         data-save="<?php echo $save_ok ? '1' : '0'; ?>"
         data-ref="<?php echo $save_ok ? 'APT-' . str_pad($new_id, 5, '0', STR_PAD_LEFT) : ''; ?>"
         data-category="<?php echo htmlspecialchars($service_category); ?>"
         data-date="<?php echo htmlspecialchars($appointment_date); ?>"
         data-time="<?php echo htmlspecialchars($appointment_time); ?>"
         data-name="<?php echo htmlspecialchars($full_name); ?>"
         style="display:none;"></div>

    <div class="form-card">
        <div class="form-card-header">
            Appointment Request Form
        </div>
        <div class="form-card-body">

            <form method="POST" action="book_appointment.php" id="appointmentForm" novalidate>

                <!-- ── Section 1: Appointment Details ── -->
                <div class="section-divider">Appointment Details</div>

                <div class="form-grid-3">
                    <!-- Service Category -->
                    <div class="form-group">
                        <label for="service_category">Service Category <span class="req">*</span></label>
                        <select name="service_category" id="service_category"
                                class="<?= fclass('service_category', $field_errors) ?>">
                            <option value="" disabled <?= empty($P['service_category']) ? 'selected' : '' ?>>Select category</option>
                            <?php
                            $cats = ['General Consultation','First Aid / Injury Care','Medical Clearance','Follow-Up Checkup','Health Counseling'];
                            foreach ($cats as $cat):
                                $sel = (($P['service_category'] ?? '') === $cat) ? 'selected' : '';
                            ?>
                            <option value="<?= htmlspecialchars($cat) ?>" <?= $sel ?>><?= htmlspecialchars($cat) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= ferr('service_category', $field_errors) ?>
                    </div>

                    <!-- Preferred Date -->
                    <div class="form-group">
                        <label for="appointment_date">Preferred Date <span class="req">*</span></label>
                        <input type="date" name="appointment_date" id="appointment_date"
                               value="<?= htmlspecialchars($P['appointment_date'] ?? '') ?>"
                               min="<?= date('Y-m-d') ?>"
                               class="<?= fclass('appointment_date', $field_errors) ?>">
                        <?= ferr('appointment_date', $field_errors) ?>
                    </div>

                    <!-- Preferred Time -->
                    <div class="form-group">
                        <label for="appointment_time">Preferred Time <span class="req">*</span></label>
                        <input type="time" name="appointment_time" id="appointment_time"
                               value="<?= htmlspecialchars($P['appointment_time'] ?? '') ?>"
                               min="08:00" max="17:00"
                               class="<?= fclass('appointment_time', $field_errors) ?>">
                        <?= ferr('appointment_time', $field_errors) ?>
                        <span style="font-size:11px;color:var(--text-gray);margin-top:4px;">
                            <i class="bi bi-clock"></i> Clinic hours: 8:00 AM – 9:00 PM, Mon–Fri
                        </span>
                    </div>
                </div>

                <div class="form-group" style="margin-top:20px;">
                    <label for="reason_for_visit">Reason for Visit <span class="req">*</span></label>
                    <textarea name="reason_for_visit" id="reason_for_visit"
                              placeholder="Please describe your symptoms or reason for this appointment (minimum 10 characters)"
                              class="<?= fclass('reason_for_visit', $field_errors) ?>"><?= htmlspecialchars($P['reason_for_visit'] ?? '') ?></textarea>
                    <?= ferr('reason_for_visit', $field_errors) ?>
                    <span style="font-size:11px;color:var(--text-gray);margin-top:4px;" id="reasonCount">
                        <i class="bi bi-pencil"></i> <span id="charCount">0</span> characters
                    </span>
                </div>

                <!-- ── Section 2: Patient Information ── -->
                <div class="section-divider">Patient Information</div>
                <p style="font-size:12px;color:var(--text-gray);margin-bottom:18px;">
                    <i class="bi bi-info-circle"></i>
                    Pre-filled from your profile — verify and update if needed before submitting.
                </p>

                <div class="form-grid-3">
                    <!-- Full Name -->
                    <div class="form-group">
                        <label for="full_name">Full Name <span class="req">*</span></label>
                        <input type="text" name="full_name" id="full_name"
                               value="<?= fval('full_name', $P, $prefill, 'full_name') ?>"
                               placeholder="Last, First Middle Name"
                               class="<?= fclass('full_name', $field_errors) ?>">
                        <?= ferr('full_name', $field_errors) ?>
                    </div>

                    <!-- ID Number -->
                    <div class="form-group">
                        <label for="id_number"><?= $id_label ?> <span class="req">*</span></label>
                        <input type="text" name="id_number" id="id_number"
                               value="<?= fval('id_number', $P, $prefill, 'student_number') ?>"
                               placeholder="<?= htmlspecialchars($id_placeholder) ?>"
                               class="<?= fclass('id_number', $field_errors) ?>">
                        <?= ferr('id_number', $field_errors) ?>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email_input">Email Address <span class="req">*</span></label>
                        <input type="email" name="email_input" id="email_input"
                               value="<?= fval('email_input', $P, $prefill, 'email') ?>"
                               placeholder="juan@iskolar.pup.edu.ph"
                               class="<?= fclass('email_input', $field_errors) ?>">
                        <?= ferr('email_input', $field_errors) ?>
                    </div>
                </div>

                <div class="form-grid-3" style="margin-top:20px;">
                    <!-- Contact Number -->
                    <div class="form-group">
                        <label for="contact_number">Contact Number <span class="req">*</span></label>
                        <input type="tel" name="contact_number" id="contact_number"
                               value="<?= fval('contact_number', $P, $prefill, 'contact_number') ?>"
                               placeholder="09XX-XXX-XXXX"
                               class="<?= fclass('contact_number', $field_errors) ?>">
                        <?= ferr('contact_number', $field_errors) ?>
                    </div>

                    <!-- Program / Position -->
                    <div class="form-group">
                        <label for="program_input"><?= htmlspecialchars($prog_label) ?></label>
                        <input type="text" name="program_input" id="program_input"
                               value="<?= fval('program_input', $P, $prefill, 'program') ?>"
                               placeholder="e.g., BSIT">
                    </div>

                    <!-- Year Level / Department -->
                    <div class="form-group">
                        <label for="level_input"><?= htmlspecialchars($level_label) ?></label>
                        <?php if ($role === 'student'): ?>
                        <select name="level_input" id="level_input">
                            <option value="">Select year</option>
                            <?php
                            $years = ['1st Year','2nd Year','3rd Year','4th Year','5th Year','Graduate'];
                            $cur   = $P['level_input'] ?? ($prefill['year_level'] ?? '');
                            foreach ($years as $yr):
                                echo '<option value="' . htmlspecialchars($yr) . '" ' . ($cur === $yr ? 'selected' : '') . '>' . htmlspecialchars($yr) . '</option>';
                            endforeach; ?>
                        </select>
                        <?php else: ?>
                        <input type="text" name="level_input" id="level_input"
                               value="<?= fval('level_input', $P, $prefill, 'year_level') ?>"
                               placeholder="e.g., CCS">
                        <?php endif; ?>
                    </div>
                </div>

                <!-- ── Privacy Consent ── -->
                <div class="section-divider">Privacy Consent</div>
                <div class="consent-group <?= isset($field_errors['terms']) ? 'invalid' : '' ?>" id="consentGroup">
                    <input type="checkbox" name="terms" id="terms"
                           <?= isset($P['terms']) ? 'checked' : '' ?>>
                    <label for="terms">
                        By submitting this form, I confirm that I have read and agree to
                        <strong>AiCare's Privacy Notice and Terms and Conditions</strong>, and give
                        my explicit consent for the processing, use, and sharing of my personal
                        data as outlined in the Privacy Notice.
                        <span class="req">*</span>
                    </label>
                </div>
                <?= ferr('terms', $field_errors) ?>

                <!-- ── Actions ── -->
                <div class="form-actions">
                    <button type="button" id="reviewBtn" class="btn-submit">
                     Review & Submit
                    </button>
                </div>

            </form>

        </div><!-- end form-card-body -->
    </div><!-- end form-card -->
</main>

<!-- ══════════════════════════════════════════════════════════
     CONFIRMATION MODAL
══════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="confirmModal" role="dialog" aria-modal="true" aria-labelledby="confirmTitle">
    <div class="modal-box">
        <div class="confirm-modal-header">
            <div class="icon-wrap"><i class="bi bi-calendar-check"></i></div>
            <h3 id="confirmTitle">Review Your Appointment</h3>
            <p>Please confirm the details below before submitting</p>
        </div>
        <div class="confirm-modal-body">
            <div class="confirm-grid">
                <div class="confirm-item">
                    <label>Service Category</label>
                    <span id="cService">—</span>
                </div>
                <div class="confirm-item">
                    <label>Patient Name</label>
                    <span id="cName">—</span>
                </div>
                <div class="confirm-item">
                    <label>Preferred Date</label>
                    <span id="cDate">—</span>
                </div>
                <div class="confirm-item">
                    <label>Preferred Time</label>
                    <span id="cTime">—</span>
                </div>
                <div class="confirm-item">
                    <label>Email Address</label>
                    <span id="cEmail">—</span>
                </div>
                <div class="confirm-item">
                    <label>Contact Number</label>
                    <span id="cContact">—</span>
                </div>
            </div>
            <div class="confirm-reason-box">
                <label>Reason for Visit</label>
                <p id="cReason">—</p>
            </div>
            <div class="confirm-notice">
                <i class="bi bi-info-circle-fill"></i>
                <span>Your appointment will be marked as <strong>Pending</strong> until the clinic confirms your schedule. You will be notified via email.</span>
            </div>
            <div class="confirm-actions">
                <button class="btn-back" id="backBtn">
                    <i class="bi bi-arrow-left"></i> Edit
                </button>
                <button class="btn-confirm" id="confirmSubmitBtn">
                    <i class="bi bi-check2-circle"></i> Confirm & Submit
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     SUCCESS MODAL
══════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="successModal" role="dialog" aria-modal="true" aria-labelledby="successTitle">
    <div class="modal-box">
        <div class="success-modal-body">
            <div class="success-icon-wrap">
                <i class="bi bi-check-lg"></i>
            </div>
            <div class="modal-title" id="successTitle">Appointment Submitted!</div>
            <div class="modal-subtitle">
                Your appointment request has been received.<br>
                The clinic will confirm your schedule shortly.
            </div>
            <div class="modal-ref" id="sModalRef"></div>
            <div class="modal-details">
                <div class="detail-row">
                    <span class="detail-label">Service</span>
                    <span class="detail-value" id="sModalCategory"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date</span>
                    <span class="detail-value" id="sModalDate"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Time</span>
                    <span class="detail-value" id="sModalTime"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Name</span>
                    <span class="detail-value" id="sModalName"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value"><span class="modal-status-badge">Pending</span></span>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn-modal-primary" onclick="closeSuccessModal()">
                    <i class="bi bi-calendar-plus me-1"></i> Book Another
                </button>
                <a href="medical_history.php" class="btn-modal-secondary">
                    <i class="bi bi-clock-history me-1"></i> View History
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script>
// ══════════════════════════════════════════════════════════
//  CLIENT-SIDE VALIDATION + CONFIRM MODAL + SUCCESS MODAL
// ══════════════════════════════════════════════════════════

// ── Character counter for reason textarea ─────────────────
const reasonTA = document.getElementById('reason_for_visit');
const charCount = document.getElementById('charCount');
if (reasonTA && charCount) {
    const updateCount = () => {
        const n = reasonTA.value.length;
        charCount.textContent = n;
        charCount.style.color = n < 10 ? 'var(--danger)' : 'var(--text-gray)';
    };
    reasonTA.addEventListener('input', updateCount);
    updateCount();
}

// ── Live inline validation helpers ───────────────────────
function showError(fieldId, msg) {
    const el = document.getElementById(fieldId);
    if (!el) return;
    el.classList.add('is-invalid');
    const existing = el.parentElement.querySelector('.field-error-live');
    if (!existing) {
        const div = document.createElement('div');
        div.className = 'field-error field-error-live';
        div.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${msg}`;
        el.parentElement.appendChild(div);
    } else {
        existing.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${msg}`;
    }
}
function clearError(fieldId) {
    const el = document.getElementById(fieldId);
    if (!el) return;
    el.classList.remove('is-invalid');
    const existing = el.parentElement.querySelector('.field-error-live');
    if (existing) existing.remove();
}

// Live validation on blur
['service_category','appointment_date','appointment_time','reason_for_visit',
 'full_name','id_number','email_input','contact_number'].forEach(id => {
    const el = document.getElementById(id);
    if (!el) return;
    el.addEventListener('blur', () => validateField(id));
    el.addEventListener('input', () => {
        if (el.classList.contains('is-invalid')) validateField(id);
    });
    el.addEventListener('change', () => validateField(id));
});

function validateField(id) {
    const el = document.getElementById(id);
    if (!el) return true;
    const val = el.value.trim();
    const today = new Date().toISOString().split('T')[0];

    switch (id) {
        case 'service_category':
            if (!val) { showError(id, 'Please select a service category.'); return false; }
            break;
        case 'appointment_date':
            if (!val) { showError(id, 'Preferred date is required.'); return false; }
            if (val < today) { showError(id, 'Date cannot be in the past.'); return false; }
            break;
        case 'appointment_time':
            if (!val) { showError(id, 'Preferred time is required.'); return false; }
            if (val < '08:00' || val > '17:00') {
                showError(id, 'Please choose between 8:00 AM and 9:00 PM.'); return false;
            }
            break;
        case 'reason_for_visit':
            if (!val) { showError(id, 'Reason for visit is required.'); return false; }
            if (val.length < 10) { showError(id, 'Please provide at least 10 characters.'); return false; }
            break;
        case 'full_name':
            if (!val) { showError(id, 'Full name is required.'); return false; }
            break;
        case 'id_number':
            if (!val) {
                const label = '<?= addslashes($id_label) ?>';
                showError(id, label + ' is required.');
                return false;
            }
            break;
        case 'email_input':
            if (!val) { showError(id, 'Email address is required.'); return false; }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
                showError(id, 'Please enter a valid email address.'); return false;
            }
            break;
        case 'contact_number':
            if (!val) { showError(id, 'Contact number is required.'); return false; }
            if (!/^[0-9+\-\s]{7,15}$/.test(val)) {
                showError(id, 'Enter a valid contact number (e.g., 09171234567).'); return false;
            }
            break;
    }
    clearError(id);
    return true;
}

// ── Full form validation ───────────────────────────────────
function validateAllFields() {
    const fields = ['service_category','appointment_date','appointment_time',
                    'reason_for_visit','full_name','id_number','email_input','contact_number'];
    let valid = true;
    fields.forEach(id => { if (!validateField(id)) valid = false; });

    // Consent checkbox
    const terms = document.getElementById('terms');
    const cg    = document.getElementById('consentGroup');
    const cErr  = document.getElementById('terms-error-live');

    if (!terms.checked) {
        cg.classList.add('invalid');
        if (!cErr) {
            const div = document.createElement('div');
            div.id = 'terms-error-live';
            div.className = 'field-error field-error-live';
            div.style.marginTop = '8px';
            div.innerHTML = '<i class="bi bi-exclamation-circle"></i> You must agree to the Privacy Notice and Terms.';
            cg.parentElement.insertBefore(div, cg.nextSibling);
        }
        valid = false;
    } else {
        cg.classList.remove('invalid');
        const existing = document.getElementById('terms-error-live');
        if (existing) existing.remove();
    }

    return valid;
}

// ── Format helpers ─────────────────────────────────────────
function formatDate(dateStr) {
    if (!dateStr) return '—';
    const d = new Date(dateStr + 'T00:00:00');
    return d.toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' });
}
function formatTime(timeStr) {
    if (!timeStr) return '—';
    const [h, m] = timeStr.split(':');
    const hour   = parseInt(h);
    const suffix = hour >= 12 ? 'PM' : 'AM';
    return ((hour % 12) || 12) + ':' + m + ' ' + suffix;
}

// ── Confirmation Modal ─────────────────────────────────────
function openConfirmModal() {
    document.getElementById('cService').textContent  = document.getElementById('service_category').value  || '—';
    document.getElementById('cName').textContent     = document.getElementById('full_name').value.trim()  || '—';
    document.getElementById('cDate').textContent     = formatDate(document.getElementById('appointment_date').value);
    document.getElementById('cTime').textContent     = formatTime(document.getElementById('appointment_time').value);
    document.getElementById('cEmail').textContent    = document.getElementById('email_input').value.trim() || '—';
    document.getElementById('cContact').textContent  = document.getElementById('contact_number').value.trim() || '—';
    document.getElementById('cReason').textContent   = document.getElementById('reason_for_visit').value.trim() || '—';

    document.getElementById('confirmModal').classList.add('show');
    document.body.style.overflow = 'hidden';

    // Progress: step 1 done → step 2 active
    document.getElementById('step1').classList.remove('active');
    document.getElementById('step1').classList.add('done');
    document.getElementById('line1').classList.add('active');
    document.getElementById('step2').classList.add('active');
}
function closeConfirmModal() {
    document.getElementById('confirmModal').classList.remove('show');
    document.body.style.overflow = '';
    // Revert progress
    document.getElementById('step1').classList.add('active');
    document.getElementById('step1').classList.remove('done');
    document.getElementById('line1').classList.remove('active');
    document.getElementById('step2').classList.remove('active');
}

// Review button — validate first, then open confirm
document.getElementById('reviewBtn').addEventListener('click', function () {
    if (validateAllFields()) {
        openConfirmModal();
    } else {
        // Scroll to first error
        const firstErr = document.querySelector('.is-invalid, .consent-group.invalid');
        if (firstErr) firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});

// Back button
document.getElementById('backBtn').addEventListener('click', closeConfirmModal);

// Close confirm modal on overlay click
document.getElementById('confirmModal').addEventListener('click', function (e) {
    if (e.target === this) closeConfirmModal();
});

// Confirm & Submit — actually submit the form
document.getElementById('confirmSubmitBtn').addEventListener('click', function () {
    // Progress: step 2 done → step 3 active
    document.getElementById('step2').classList.remove('active');
    document.getElementById('step2').classList.add('done');
    document.getElementById('line2').classList.add('active');
    document.getElementById('step3').classList.add('active');

    this.disabled = true;
    this.innerHTML = '<i class="bi bi-hourglass-split"></i> Submitting...';

    // Add hidden submit field and submit the form
    const hiddenInput = document.createElement('input');
    hiddenInput.type  = 'hidden';
    hiddenInput.name  = 'submit_appointment';
    hiddenInput.value = '1';
    document.getElementById('appointmentForm').appendChild(hiddenInput);
    document.getElementById('appointmentForm').submit();
});

// ── Success Modal ──────────────────────────────────────────
function openSuccessModal(data) {
    document.getElementById('sModalRef').textContent      = data.ref;
    document.getElementById('sModalCategory').textContent = data.category;
    document.getElementById('sModalName').textContent     = data.name;
    document.getElementById('sModalDate').textContent     = formatDate(data.date);
    document.getElementById('sModalTime').textContent     = formatTime(data.time);

    document.getElementById('successModal').classList.add('show');
    document.body.style.overflow = 'hidden';

    // Mark all steps done
    ['step1','step2','step3'].forEach(s => {
        const el = document.getElementById(s);
        el.classList.remove('active');
        el.classList.add('done');
    });
    ['line1','line2'].forEach(l => document.getElementById(l).classList.add('active'));
}

function closeSuccessModal() {
    document.getElementById('successModal').classList.remove('show');
    document.body.style.overflow = '';
    document.getElementById('appointmentForm').reset();
    charCount && (charCount.textContent = '0');
    // Reset steps
    ['step1','step2','step3'].forEach(s => {
        document.getElementById(s).classList.remove('done','active');
    });
    document.getElementById('step1').classList.add('active');
    ['line1','line2'].forEach(l => document.getElementById(l).classList.remove('active'));
}

// Close success modal on overlay click
document.getElementById('successModal').addEventListener('click', function (e) {
    if (e.target === this) closeSuccessModal();
});

// ── Auto-open success modal (after PHP redirect) ───────────
window.addEventListener('load', function () {
    const el = document.getElementById('appt-result');
    if (el && el.getAttribute('data-save') === '1') {
        openSuccessModal({
            ref:      el.getAttribute('data-ref'),
            category: el.getAttribute('data-category'),
            date:     el.getAttribute('data-date'),
            time:     el.getAttribute('data-time'),
            name:     el.getAttribute('data-name')
        });
    }
    // Scroll to first server-side error
    const errAlert = document.querySelector('.alert-danger');
    if (errAlert) errAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
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

document.querySelectorAll('.quick-action-btn').forEach(btn => {
    btn.addEventListener('click', () => sendMessage(btn.getAttribute('data-message')));
});

function sendMessage(messageText = null) {
    const text = messageText || chatbotInput.value.trim();
    if (!text) return;
    addMessage(text, 'user');
    chatbotInput.value = '';
    showTypingIndicator();
    setTimeout(() => { hideTypingIndicator(); addMessage(getBotResponse(text), 'bot'); }, 900 + Math.random() * 600);
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
    if (m.includes('category') || m.includes('type'))
        return "We offer 5 categories: <strong>General Consultation, First Aid/Injury Care, Medical Clearance, Follow-Up Checkup,</strong> and <strong>Health Counseling</strong>. Select one from the dropdown.";
    if (m.includes('time') || m.includes('slot'))
        return "Clinic hours are <strong>8:00 AM – 9:00 PM, Monday to Friday</strong>. Use the time field to pick your preferred slot.";
    if (m.includes('requirement') || m.includes('document') || m.includes('need'))
        return "You'll need your <strong>ID number, a valid email, contact number</strong>, and a clear reason for your visit. All marked fields are required.";
    if (m.includes('cancel') || m.includes('reschedule'))
        return "To cancel or reschedule, visit your <a href='medical_history.php' style='color:var(--primary);'>Medical History</a> page or contact the clinic directly.";
    if (m.includes('hello') || m.includes('hi'))
        return "Hello! I'm here to help you book your appointment. Fill in the form and click <strong>Review & Submit</strong>!";
    if (m.includes('confirm') || m.includes('review'))
        return "After clicking <strong>Review & Submit</strong>, a confirmation window will show your appointment details. Click <strong>Confirm & Submit</strong> to finalize it.";
    return "I can help with booking, time slots, and requirements. What would you like to know?";
}

chatbotSend.addEventListener('click', () => sendMessage());
chatbotInput.addEventListener('keypress', e => { if (e.key === 'Enter') sendMessage(); });
</script>
</body>
</html>