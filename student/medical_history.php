<?php
// ============================================================
//  medical_history.php  — Student Portal
//  Pulls ALL medical history from the DB for the logged-in
//  student using the vw_student_medical_history view, then
//  fetches full detail rows on demand per record type.
// ============================================================
session_start();
require_once "../config/db.php";   // provides $c (mysqli connection)

// ── GUARD ────────────────────────────────────────────────────
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}

$student_id = (int) $_SESSION['profile_id'];

// ── 1. STUDENT PROFILE (for name / ID display) ───────────────
$q = mysqli_prepare($c,
    "SELECT CONCAT(last_name, ', ', first_name, ' ', IFNULL(middle_name,'')) AS full_name,
            student_number, college, program, year_level
     FROM   students WHERE student_id = ? LIMIT 1");
mysqli_stmt_bind_param($q, "i", $student_id);
mysqli_stmt_execute($q);
$profile = mysqli_fetch_assoc(mysqli_stmt_get_result($q)) ?? [];

// ── 2. ALL HISTORY ROWS (view) ───────────────────────────────
$q2 = mysqli_prepare($c,
    "SELECT record_date, record_type, sub_type, diagnosis_purpose,
            follow_up_date, source_id, source_table
     FROM   vw_student_medical_history
     WHERE  student_id = ?
     ORDER  BY record_date DESC, source_id DESC");
mysqli_stmt_bind_param($q2, "i", $student_id);
mysqli_stmt_execute($q2);
$history_result = mysqli_stmt_get_result($q2);
$history_rows   = [];
while ($row = mysqli_fetch_assoc($history_result)) {
    $history_rows[] = $row;
}

// ── 3. DETAIL RECORDS — fetch full rows per source table ─────
$details = [];   // keyed as "table:id"

// ── 3a. consultation_records ─────────────────────────────────
$q3 = mysqli_prepare($c,
    "SELECT cr.record_id, cr.consultation_date, cr.consultation_time,
            cr.consultation_type, cr.college_department,
            cr.blood_pressure, cr.temperature_c, cr.heart_rate_bpm,
            cr.respiratory_rate, cr.height_cm, cr.weight_kg, cr.bmi,
            cr.chief_complaint, cr.pe_findings, cr.diagnosis,
            cr.treatment, cr.medications_given, cr.additional_notes,
            cr.follow_up_date, cr.days_of_rest,
            ms.full_name AS staff_name, ms.position AS staff_position
     FROM   consultation_records cr
     LEFT JOIN medical_staff ms ON ms.staff_id = cr.staff_id
     WHERE  cr.student_id = ? AND cr.patient_type = 'student'");
mysqli_stmt_bind_param($q3, "i", $student_id);
mysqli_stmt_execute($q3);
$r3 = mysqli_stmt_get_result($q3);
while ($row = mysqli_fetch_assoc($r3)) {
    $details['consultation_records:' . $row['record_id']] = $row;
}

// ── 3b. health_exam_records ──────────────────────────────────
$q4 = mysqli_prepare($c,
    "SELECT he.exam_id, he.exam_date, he.school_year_semester,
            he.blood_pressure, he.temperature_c, he.heart_rate_bpm,
            he.respiratory_rate, he.height_cm, he.weight_kg, he.bmi,
            he.general_condition, he.allergies, he.current_medications,
            he.physical_findings, he.working_impression,
            he.fit_status, he.for_workup, he.follow_up_date, he.remarks,
            he.childhood_illness, he.operations_surgeries,
            he.previous_hospitalizations,
            ms.full_name AS staff_name, ms.position AS staff_position
     FROM   health_exam_records he
     LEFT JOIN medical_staff ms ON ms.staff_id = he.staff_id
     WHERE  he.student_id = ? AND he.patient_type = 'student'");
mysqli_stmt_bind_param($q4, "i", $student_id);
mysqli_stmt_execute($q4);
$r4 = mysqli_stmt_get_result($q4);
while ($row = mysqli_fetch_assoc($r4)) {
    $details['health_exam_records:' . $row['exam_id']] = $row;
}

// ── 3c. lab_requests ─────────────────────────────────────────
$q5 = mysqli_prepare($c,
    "SELECT lr.lab_id, lr.request_date, lr.status,
            lr.chest_xray_pa, lr.chest_xray_aplat, lr.ecg,
            lr.urinalysis, lr.fecalysis, lr.drug_test,
            lr.cbc, lr.fbs, lr.bun, lr.creatinine,
            lr.total_cholesterol, lr.triglycerides,
            lr.hdl, lr.ldl, lr.uric_acid, lr.sgpt, lr.hepatitis_b,
            lr.other_tests, lr.physician_notes,
            ms.full_name AS staff_name, ms.position AS staff_position
     FROM   lab_requests lr
     LEFT JOIN medical_staff ms ON ms.staff_id = lr.staff_id
     WHERE  lr.student_id = ? AND lr.patient_type = 'student'");
mysqli_stmt_bind_param($q5, "i", $student_id);
mysqli_stmt_execute($q5);
$r5 = mysqli_stmt_get_result($q5);
while ($row = mysqli_fetch_assoc($r5)) {
    $details['lab_requests:' . $row['lab_id']] = $row;
}

// ── 3d. medical_clearances ───────────────────────────────────
$q6 = mysqli_prepare($c,
    "SELECT mc.clearance_id, mc.issue_date, mc.valid_until,
            mc.purpose_of_clearance, mc.fit_status,
            mc.conditions_noted, mc.school_year,
            mc.physician_name, mc.license_number, mc.remarks,
            ms.full_name AS staff_name, ms.position AS staff_position
     FROM   medical_clearances mc
     LEFT JOIN medical_staff ms ON ms.staff_id = mc.staff_id
     WHERE  mc.student_id = ? AND mc.patient_type = 'student'");
mysqli_stmt_bind_param($q6, "i", $student_id);
mysqli_stmt_execute($q6);
$r6 = mysqli_stmt_get_result($q6);
while ($row = mysqli_fetch_assoc($r6)) {
    $details['medical_clearances:' . $row['clearance_id']] = $row;
}

// ── 3e. medical_certificates ─────────────────────────────────
$q7 = mysqli_prepare($c,
    "SELECT cert.certificate_id, cert.certificate_type,
            cert.issue_date, cert.valid_until, cert.purpose,
            cert.diagnosis, cert.days_of_rest,
            cert.physician_name, cert.license_number,
            cert.remarks, cert.status,
            ms.full_name AS staff_name, ms.position AS staff_position
     FROM   medical_certificates cert
     LEFT JOIN medical_staff ms ON ms.staff_id = cert.staff_id
     WHERE  cert.student_id = ? AND cert.patient_type = 'student'");
mysqli_stmt_bind_param($q7, "i", $student_id);
mysqli_stmt_execute($q7);
$r7 = mysqli_stmt_get_result($q7);
while ($row = mysqli_fetch_assoc($r7)) {
    $details['medical_certificates:' . $row['certificate_id']] = $row;
}

// ── HELPERS ──────────────────────────────────────────────────
function fmt_date(?string $d): string {
    if (!$d) return '—';
    return date('M j, Y', strtotime($d));
}
function fmt_time(?string $t): string {
    if (!$t) return '—';
    return date('g:i A', strtotime($t));
}
function esc(?string $s): string {
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

// Badge style per record_type — no icons
function type_badge(string $type): string {
    $map = [
        'Consultation'        => ['bg' => '#dbeafe', 'color' => '#1e40af'],
        'Health Exam'         => ['bg' => '#d1fae5', 'color' => '#065f46'],
        'Laboratory Request'  => ['bg' => '#fef3c7', 'color' => '#92400e'],
        'Medical Clearance'   => ['bg' => '#ede9fe', 'color' => '#4c1d95'],
        'Medical Certificate' => ['bg' => '#fce7f3', 'color' => '#9d174d'],
    ];
    $m = $map[$type] ?? ['bg' => '#f3f4f6', 'color' => '#374151'];
    return sprintf(
        '<span style="display:inline-flex;align-items:center;background:%s;color:%s;padding:5px 12px;border-radius:20px;font-size:12px;font-weight:600;">%s</span>',
        $m['bg'], $m['color'], esc($type)
    );
}

// Build the JS `records` array from PHP detail data
function build_js_record(array $history, array $details): string {
    $key  = $history['source_table'] . ':' . $history['source_id'];
    $d    = $details[$key] ?? [];
    $type = $history['record_type'];

    $base = [
        'key'          => $key,
        'type'         => $type,
        'subType'      => $history['sub_type']         ?? '',
        'recordDate'   => $history['record_date']       ?? '',
        'followUpDate' => $history['follow_up_date']    ?? '',
        'staffName'    => $d['staff_name']              ?? '',
        'staffPos'     => $d['staff_position']          ?? '',
    ];

    switch ($history['source_table']) {
        case 'consultation_records':
            return json_encode(array_merge($base, [
                'date'        => fmt_date($d['consultation_date'] ?? null),
                'time'        => fmt_time($d['consultation_time'] ?? null),
                'consultType' => $d['consultation_type']   ?? '',
                'department'  => $d['college_department']  ?? '',
                'bp'          => $d['blood_pressure']      ?? '',
                'temp'        => $d['temperature_c']       ?? '',
                'hr'          => $d['heart_rate_bpm']      ?? '',
                'rr'          => $d['respiratory_rate']    ?? '',
                'ht'          => $d['height_cm']           ?? '',
                'wt'          => $d['weight_kg']           ?? '',
                'bmi'         => $d['bmi']                 ?? '',
                'complaint'   => $d['chief_complaint']     ?? '',
                'findings'    => $d['pe_findings']         ?? '',
                'diagnosis'   => $d['diagnosis']           ?? '',
                'treatment'   => $d['treatment']           ?? '',
                'medications' => $d['medications_given']   ?? '',
                'notes'       => $d['additional_notes']    ?? '',
                'daysOfRest'  => $d['days_of_rest']        ?? '',
                'followUp'    => fmt_date($d['follow_up_date'] ?? null),
            ]));

        case 'health_exam_records':
            return json_encode(array_merge($base, [
                'date'          => fmt_date($d['exam_date'] ?? null),
                'time'          => '—',
                'consultType'   => 'Health Examination',
                'department'    => '',
                'schoolYear'    => $d['school_year_semester']       ?? '',
                'bp'            => $d['blood_pressure']             ?? '',
                'temp'          => $d['temperature_c']              ?? '',
                'hr'            => $d['heart_rate_bpm']             ?? '',
                'rr'            => $d['respiratory_rate']           ?? '',
                'ht'            => $d['height_cm']                  ?? '',
                'wt'            => $d['weight_kg']                  ?? '',
                'bmi'           => $d['bmi']                        ?? '',
                'generalCond'   => $d['general_condition']          ?? '',
                'allergies'     => $d['allergies']                  ?? '',
                'medications'   => $d['current_medications']        ?? '',
                'findings'      => $d['physical_findings']          ?? '',
                'diagnosis'     => $d['working_impression']         ?? '',
                'fitStatus'     => $d['fit_status']                 ?? '',
                'forWorkup'     => $d['for_workup']                 ?? '',
                'notes'         => $d['remarks']                    ?? '',
                'followUp'      => fmt_date($d['follow_up_date']    ?? null),
                'pastIllness'   => $d['childhood_illness']          ?? '',
                'surgeries'     => $d['operations_surgeries']       ?? '',
                'hospitalizations' => $d['previous_hospitalizations'] ?? '',
            ]));

        case 'lab_requests':
            $tests = [];
            $labMap = [
                'chest_xray_pa'   => 'Chest X-Ray (PA)',
                'chest_xray_aplat'=> 'Chest X-Ray (AP/Lat)',
                'ecg'             => 'ECG',
                'urinalysis'      => 'Urinalysis',
                'fecalysis'       => 'Fecalysis',
                'drug_test'       => 'Drug Test',
                'cbc'             => 'CBC',
                'fbs'             => 'FBS',
                'bun'             => 'BUN',
                'creatinine'      => 'Creatinine',
                'total_cholesterol'=> 'Total Cholesterol',
                'triglycerides'   => 'Triglycerides',
                'hdl'             => 'HDL',
                'ldl'             => 'LDL',
                'uric_acid'       => 'Uric Acid',
                'sgpt'            => 'SGPT',
                'hepatitis_b'     => 'Hepatitis B',
            ];
            foreach ($labMap as $col => $label) {
                if (!empty($d[$col])) $tests[] = $label;
            }
            if (!empty($d['other_tests'])) $tests[] = 'Other: ' . $d['other_tests'];

            return json_encode(array_merge($base, [
                'date'        => fmt_date($d['request_date'] ?? null),
                'time'        => '—',
                'consultType' => 'Laboratory Request',
                'department'  => '',
                'status'      => $d['status'] ?? '',
                'tests'       => implode(', ', $tests),
                'notes'       => $d['physician_notes'] ?? '',
            ]));

        case 'medical_clearances':
            return json_encode(array_merge($base, [
                'date'        => fmt_date($d['issue_date']          ?? null),
                'time'        => '—',
                'consultType' => 'Medical Clearance',
                'department'  => '',
                'validUntil'  => fmt_date($d['valid_until']         ?? null),
                'purpose'     => $d['purpose_of_clearance']         ?? '',
                'fitStatus'   => $d['fit_status']                   ?? '',
                'conditions'  => $d['conditions_noted']             ?? '',
                'schoolYear'  => $d['school_year']                  ?? '',
                'physician'   => $d['physician_name']               ?? '',
                'license'     => $d['license_number']               ?? '',
                'notes'       => $d['remarks']                      ?? '',
            ]));

        case 'medical_certificates':
            return json_encode(array_merge($base, [
                'date'        => fmt_date($d['issue_date']          ?? null),
                'time'        => '—',
                'consultType' => $d['certificate_type']             ?? 'Medical Certificate',
                'department'  => '',
                'validUntil'  => fmt_date($d['valid_until']         ?? null),
                'purpose'     => $d['purpose']                      ?? '',
                'diagnosis'   => $d['diagnosis']                    ?? '',
                'daysOfRest'  => $d['days_of_rest']                 ?? '',
                'physician'   => $d['physician_name']               ?? '',
                'license'     => $d['license_number']               ?? '',
                'certStatus'  => $d['status']                       ?? '',
                'notes'       => $d['remarks']                      ?? '',
            ]));

        default:
            return json_encode($base);
    }
}

// Encode all detail records for JS
$js_records = [];
foreach ($history_rows as $i => $row) {
    $js_records[] = build_js_record($row, $details);
}
$js_records_json = '[' . implode(',', $js_records) . ']';

$total_records   = count($history_rows);
$full_name       = trim($profile['full_name']     ?? 'Student');
$student_number  = $profile['student_number']     ?? '—';
$college         = $profile['college']            ?? '—';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal — Medical History</title>

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
        .chatbot-container {
            position:fixed; bottom:20px; left:295px;
            width:380px; height:550px; background:white;
            border-radius:16px; box-shadow:0 8px 32px rgba(0,0,0,.15);
            display:none; flex-direction:column; z-index:1001; overflow:hidden;
        }
        .chatbot-container.show { display:flex; }
        .chatbot-header { background:linear-gradient(90deg,var(--grad-s),var(--grad-e)); color:white; padding:20px; display:flex; justify-content:space-between; align-items:center; }
        .chatbot-header h3 { margin:0; font-size:18px; font-weight:600; display:flex; align-items:center; gap:10px; }
        .chatbot-close { background:none; border:none; color:white; font-size:24px; cursor:pointer; width:30px; height:30px; border-radius:50%; display:flex; align-items:center; justify-content:center; transition:background .3s; }
        .chatbot-close:hover { background:rgba(255,255,255,.2); }
        .quick-actions { padding:12px 20px; display:flex; gap:8px; flex-wrap:wrap; background:var(--bg); }
        .quick-action-btn { padding:8px 16px; background:white; border:2px solid var(--primary); color:var(--primary); border-radius:20px; font-size:12px; font-weight:500; cursor:pointer; transition:all .3s; font-family:'Poppins',sans-serif; }
        .quick-action-btn:hover { background:var(--primary); color:white; }
        .chatbot-messages { flex:1; padding:20px; overflow-y:auto; background:var(--bg); }
        .message { margin-bottom:16px; display:flex; gap:10px; }
        .message.user { flex-direction:row-reverse; }
        .message-avatar { width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0; }
        .message.bot .message-avatar  { background:var(--primary-soft); }
        .message.user .message-avatar { background:var(--primary); color:white; }
        .message-content { max-width:70%; padding:12px 16px; border-radius:12px; font-size:14px; line-height:1.5; }
        .message.bot .message-content  { background:white; color:var(--text-dark); border-bottom-left-radius:4px; }
        .message.user .message-content { background:var(--primary); color:white; border-bottom-right-radius:4px; }
        .chatbot-input-area { padding:16px; background:white; border-top:1px solid var(--border); }
        .chatbot-input-wrapper { display:flex; gap:10px; }
        .chatbot-input { flex:1; padding:12px 16px; border:2px solid var(--border); border-radius:24px; font-size:14px; font-family:'Poppins',sans-serif; outline:none; transition:border-color .3s; }
        .chatbot-input:focus { border-color:var(--primary); }
        .chatbot-send { width:44px; height:44px; background:var(--primary); color:white; border:none; border-radius:50%; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:18px; transition:background .3s; font-weight:700; }
        .chatbot-send:hover { background:var(--primary-light); }
        .typing-indicator { display:flex; gap:4px; padding:12px 16px; }
        .typing-indicator span { width:8px; height:8px; background:var(--text-gray); border-radius:50%; animation:typing 1.4s infinite; }
        .typing-indicator span:nth-child(2) { animation-delay:.2s; }
        .typing-indicator span:nth-child(3) { animation-delay:.4s; }
        @keyframes typing { 0%,60%,100%{transform:translateY(0);opacity:.7} 30%{transform:translateY(-10px);opacity:1} }

        /* ── Main ── */
        .main-content { margin-left:275px; padding:40px 50px; }
        .page-header  { margin-bottom:28px; }
        .page-header h2 { color:var(--primary); font-size:34px; font-weight:700; margin-bottom:6px; }
        .page-header p  { color:var(--text-gray); font-size:15px; }

        /* ── Summary Cards — Dashboard Style ── */
        .summary-row {
            display:grid;
            grid-template-columns:repeat(4,1fr);
            gap:16px;
            margin-bottom:28px;
        }
        .sum-card {
            background:white;
            border-radius:10px;
            padding:24px 26px;
            box-shadow:0 2px 8px rgba(0,0,0,.06);
            border-left:5px solid var(--primary);
            display:flex;
            flex-direction:column;
            gap:6px;
        }
        .sum-card .sum-num {
            font-size:36px;
            font-weight:700;
            color:var(--primary);
            line-height:1;
        }
        .sum-card .sum-label {
            font-size:13px;
            color:var(--text-gray);
            font-weight:500;
        }

        /* ── History Table Card ── */
        .history-section { background:white; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,.06); overflow:hidden; }
        .section-header {
            background:linear-gradient(90deg,var(--grad-s),var(--grad-e));
            color:white; padding:18px 28px;
            font-size:17px; font-weight:600;
            display:flex; align-items:center; justify-content:space-between;
        }
        .section-header .rec-count {
            background:rgba(255,255,255,.25); font-size:13px; font-weight:600;
            padding:4px 14px; border-radius:20px;
        }

        /* Filter bar */
        .filter-bar {
            padding:14px 28px; background:#fafafa;
            border-bottom:1px solid var(--border);
            display:flex; gap:10px; flex-wrap:wrap; align-items:center;
        }
        .filter-bar label { font-size:13px; font-weight:600; color:var(--text-gray); }
        .filter-btn {
            padding:6px 16px; border-radius:20px; border:1.5px solid var(--border);
            background:white; font-size:12px; font-weight:600;
            color:var(--text-gray); cursor:pointer; transition:all .2s;
            font-family:'Poppins',sans-serif;
        }
        .filter-btn.active, .filter-btn:hover {
            border-color:var(--primary); color:var(--primary); background:var(--primary-soft);
        }

        /* Empty state */
        .empty-state { padding:60px 30px; text-align:center; color:var(--text-gray); }
        .empty-state p { font-size:15px; }

        .table { margin-bottom:0; }
        .table thead th {
            background:var(--primary-soft); padding:14px 24px;
            font-weight:700; color:var(--text-dark); font-size:12px;
            text-transform:uppercase; letter-spacing:.5px; border:none;
        }
        .table tbody td {
            padding:16px 24px; color:var(--text-dark); font-weight:500;
            vertical-align:middle; border-color:#f0f0f0;
            font-size:14px;
        }
        .table tbody tr:hover { background:var(--primary-soft); }
        .date-cell { font-size:13px; color:var(--text-gray); white-space:nowrap; }
        .diag-cell { max-width:260px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; font-size:13px; }
        .btn-details {
            background:var(--primary); color:white;
            padding:7px 18px; border-radius:6px; font-size:12px;
            font-weight:600; border:none; cursor:pointer; transition:all .25s;
            font-family:'Poppins',sans-serif;
        }
        .btn-details:hover { background:var(--primary-light); }

        /* ── View Details Modal ── */
        .modal-overlay {
            display:none; position:fixed; z-index:2000;
            inset:0; background:rgba(0,0,0,.55);
            backdrop-filter:blur(4px);
            align-items:center; justify-content:center; padding:20px;
        }
        .modal-overlay.show { display:flex; animation:fadeIn .25s ease; }
        @keyframes fadeIn { from{opacity:0} to{opacity:1} }
        .modal-box {
            background:white; border-radius:18px;
            width:100%; max-width:800px; max-height:92vh;
            overflow:hidden; display:flex; flex-direction:column;
            box-shadow:0 24px 80px rgba(0,0,0,.25);
            animation:slideUp .3s ease;
        }
        @keyframes slideUp { from{transform:translateY(40px);opacity:0} to{transform:translateY(0);opacity:1} }

        .modal-head {
            background:linear-gradient(90deg,var(--grad-s),var(--grad-e));
            color:white; padding:22px 28px;
            display:flex; justify-content:space-between; align-items:flex-start;
            flex-shrink:0;
        }
        .modal-head-title { font-size:19px; font-weight:700; margin-bottom:3px; }
        .modal-head-sub   { font-size:12px; opacity:.85; }
        .modal-close-btn {
            background:rgba(255,255,255,.2); border:none; color:white;
            width:34px; height:34px; border-radius:50%; font-size:20px;
            display:flex; align-items:center; justify-content:center;
            cursor:pointer; transition:all .2s; flex-shrink:0;
        }
        .modal-close-btn:hover { background:rgba(255,255,255,.35); transform:rotate(90deg); }

        .record-meta {
            display:flex; flex-shrink:0;
            border-bottom:1px solid var(--border); background:#fafafa;
        }
        .meta-item {
            flex:1; padding:14px 20px;
            border-right:1px solid var(--border);
            display:flex; flex-direction:column; gap:3px;
        }
        .meta-item:last-child { border-right:none; }
        .meta-label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:var(--text-gray); }
        .meta-value { font-size:13px; font-weight:600; color:var(--text-dark); }
        .meta-value.highlight { color:var(--primary); }

        .modal-scroll { overflow-y:auto; flex:1; padding:26px 28px; }

        .d-section-title {
            font-size:12px; font-weight:700; text-transform:uppercase;
            letter-spacing:.6px; color:var(--primary);
            padding-bottom:8px; border-bottom:2px solid var(--primary-soft);
            margin:22px 0 14px;
        }
        .d-section-title:first-child { margin-top:0; }

        .vitals-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-bottom:4px; }
        .vital-card {
            background:#f9fafb; border:1px solid var(--border);
            border-radius:10px; padding:12px 14px;
        }
        .vital-card .v-label { font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.4px; color:var(--text-gray); margin-bottom:4px; }
        .vital-card .v-value { font-size:20px; font-weight:700; color:var(--text-dark); line-height:1; }
        .vital-card .v-unit  { font-size:11px; color:var(--text-gray); margin-top:2px; }
        .vital-card.has-value { border-color:#fca5a5; background:var(--primary-soft); }
        .vital-card.has-value .v-value { color:var(--primary); }

        .d-grid-2 { display:grid; grid-template-columns:repeat(2,1fr); gap:12px; margin-bottom:12px; }
        .d-grid-1 { display:grid; grid-template-columns:1fr; gap:12px; margin-bottom:12px; }
        .d-field  { display:flex; flex-direction:column; gap:4px; }
        .d-label  { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.4px; color:var(--text-gray); }
        .d-value  {
            font-size:13px; font-weight:500; color:var(--text-dark);
            background:#f9fafb; border:1px solid var(--border);
            border-radius:8px; padding:10px 14px; line-height:1.6; min-height:40px;
        }
        .d-value.multiline { min-height:68px; white-space:pre-wrap; }
        .d-value.empty     { color:#9ca3af; font-style:italic; }

        /* lab tests tag list */
        .lab-tags { display:flex; flex-wrap:wrap; gap:8px; padding:10px 0; }
        .lab-tag  {
            background:#dbeafe; color:#1e40af;
            padding:5px 14px; border-radius:20px;
            font-size:12px; font-weight:600;
        }

        .status-pill { display:inline-flex; align-items:center; gap:6px; padding:5px 14px; border-radius:999px; font-size:12px; font-weight:600; }
        .pill-completed { background:#d1fae5; color:#065f46; }
        .pill-active    { background:#dbeafe; color:#1e40af; }
        .pill-follow-up { background:#fef3c7; color:#92400e; }

        .followup-alert {
            background:#fff7ed; border:1.5px solid #fed7aa;
            border-radius:10px; padding:14px 18px;
            display:flex; align-items:center; gap:12px; margin-top:4px;
        }
        .followup-alert .fa-text { font-size:13px; color:#92400e; font-weight:500; }
        .followup-alert .fa-date { font-weight:700; }

        .modal-footer-bar {
            padding:16px 28px; background:#f9fafb;
            border-top:1px solid var(--border);
            display:flex; justify-content:flex-end; gap:10px; flex-shrink:0;
        }
        .btn-close-modal {
            padding:10px 28px; border-radius:8px; border:none;
            background:var(--primary); color:white;
            font-family:'Poppins',sans-serif; font-size:14px; font-weight:600;
            cursor:pointer; transition:all .2s;
        }
        .btn-close-modal:hover { background:var(--primary-light); }

        /* ── Responsive ── */
        @media (max-width:1100px) { .summary-row { grid-template-columns:repeat(2,1fr); } }
        @media (max-width:768px)  {
            .sidebar { width:200px; }
            .main-content { margin-left:200px; padding:30px 20px; }
            .vitals-grid  { grid-template-columns:repeat(2,1fr); }
            .d-grid-2     { grid-template-columns:1fr; }
            .record-meta  { flex-wrap:wrap; }
            .meta-item    { min-width:50%; border-right:none; border-bottom:1px solid var(--border); }
            .summary-row  { grid-template-columns:1fr 1fr; }
        }
    </style>
</head>
<body>

<!-- ── Sidebar ── -->
<div class="sidebar">
    <div class="sidebar-header">
        <h1>Student Portal</h1>
        <p>Medical Services</p>
    </div>
    <nav class="sidebar-nav">
        <a href="book_appointment.php" class="nav-item">
            <i class="bi bi-calendar-plus"></i><span>Book Appointment</span>
        </a>
        <a href="medical_history.php" class="nav-item active">
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
        <h3>Medical Assistant</h3>
        <button class="chatbot-close" id="chatbotClose">&times;</button>
    </div>
    <div class="quick-actions">
        <button class="quick-action-btn" data-message="Show my records">My Records</button>
        <button class="quick-action-btn" data-message="Latest consultation">Latest Visit</button>
        <button class="quick-action-btn" data-message="Download records">Download</button>
    </div>
    <div class="chatbot-messages" id="chatbotMessages">
        <div class="message bot">
            <div class="message-avatar">🤖</div>
            <div class="message-content">Hello! I can help you with your medical history. What would you like to know?</div>
        </div>
    </div>
    <div class="chatbot-input-area">
        <div class="chatbot-input-wrapper">
            <input type="text" class="chatbot-input" id="chatbotInput" placeholder="Type your message..." autocomplete="off">
            <button class="chatbot-send" id="chatbotSend">&#9658;</button>
        </div>
    </div>
</div>

<!-- ── Main ── -->
<main class="main-content">
    <div class="page-header">
        <h2>Medical History</h2>
        <p>All medical records for <strong><?= esc($full_name) ?></strong> &nbsp;&middot;&nbsp; <?= esc($student_number) ?> &nbsp;&middot;&nbsp; <?= esc($college) ?></p>
    </div>

    <!-- ── Summary Cards — Dashboard Style ── -->
    <?php
    $counts = ['Consultation' => 0, 'Health Exam' => 0, 'Laboratory Request' => 0, 'Medical Clearance' => 0, 'Medical Certificate' => 0];
    foreach ($history_rows as $r) {
        if (isset($counts[$r['record_type']])) $counts[$r['record_type']]++;
    }
    $card_cfg = [
        ['label' => 'Consultations', 'key' => 'Consultation'],
        ['label' => 'Health Exams',  'key' => 'Health Exam'],
        ['label' => 'Lab Requests',  'key' => 'Laboratory Request'],
        ['label' => 'Certificates',  'key' => 'Medical Certificate'],
    ];
    ?>
    <div class="summary-row">
        <?php foreach ($card_cfg as $cfg): ?>
        <div class="sum-card">
            <div class="sum-num">
                <?= $counts[$cfg['key']] + ($cfg['key'] === 'Medical Certificate' ? ($counts['Medical Clearance'] ?? 0) : 0) ?>
            </div>
            <div class="sum-label"><?= $cfg['label'] ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- ── History Table ── -->
    <div class="history-section">
        <div class="section-header">
            <span>All Medical Records</span>
            <span class="rec-count"><?= $total_records ?> record<?= $total_records !== 1 ? 's' : '' ?></span>
        </div>

        <!-- Filter buttons -->
        <div class="filter-bar">
            <label>Filter:</label>
            <button class="filter-btn active" data-filter="all">All</button>
            <button class="filter-btn" data-filter="Consultation">Consultations</button>
            <button class="filter-btn" data-filter="Health Exam">Health Exams</button>
            <button class="filter-btn" data-filter="Laboratory Request">Lab Requests</button>
            <button class="filter-btn" data-filter="Medical Clearance">Clearances</button>
            <button class="filter-btn" data-filter="Medical Certificate">Certificates</button>
        </div>

        <?php if (empty($history_rows)): ?>
        <div class="empty-state">
            <p>No medical records found for your account.</p>
            <p style="font-size:13px;margin-top:8px;">Records will appear here after your first clinic visit.</p>
        </div>
        <?php else: ?>
        <table class="table" id="historyTable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Record Type</th>
                    <th>Sub-type / Status</th>
                    <th>Diagnosis / Purpose</th>
                    <th>Follow-up</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($history_rows as $idx => $row): ?>
                <tr data-type="<?= esc($row['record_type']) ?>">
                    <td class="date-cell"><?= fmt_date($row['record_date']) ?></td>
                    <td><?= type_badge($row['record_type']) ?></td>
                    <td style="font-size:13px;"><?= esc($row['sub_type'] ?? '—') ?></td>
                    <td class="diag-cell" title="<?= esc($row['diagnosis_purpose'] ?? '') ?>">
                        <?= esc($row['diagnosis_purpose'] ? (strlen($row['diagnosis_purpose']) > 60 ? substr($row['diagnosis_purpose'], 0, 60) . '…' : $row['diagnosis_purpose']) : '—') ?>
                    </td>
                    <td class="date-cell">
                        <?php if ($row['follow_up_date']): ?>
                            <span style="color:#c2410c;font-weight:600;">
                                <?= fmt_date($row['follow_up_date']) ?>
                            </span>
                        <?php else: ?>
                            <span style="color:#9ca3af;">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="btn-details" onclick="viewDetails(<?= $idx ?>)">View Details</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</main>

<!-- ══════════════════════════════════════════════════════════
     VIEW DETAILS MODAL
══════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="detailsModal">
    <div class="modal-box">

        <!-- Header -->
        <div class="modal-head">
            <div>
                <div class="modal-head-title" id="modal-type-title">Record Details</div>
                <div class="modal-head-sub">PUP AiCare Clinic — Medical Services Department</div>
            </div>
            <button class="modal-close-btn" onclick="closeDetails()">&times;</button>
        </div>

        <!-- Meta strip -->
        <div class="record-meta">
            <div class="meta-item">
                <span class="meta-label">Date</span>
                <span class="meta-value" id="d-meta-date">—</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Time</span>
                <span class="meta-value" id="d-meta-time">—</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Healthcare Provider</span>
                <span class="meta-value highlight" id="d-meta-provider">—</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Sub-type</span>
                <span class="meta-value" id="d-meta-subtype">—</span>
            </div>
        </div>

        <!-- Scrollable body -->
        <div class="modal-scroll" id="modal-body-content">
            <!-- Content injected by JS based on record type -->
        </div>

        <!-- Footer -->
        <div class="modal-footer-bar">
            <button class="btn-close-modal" onclick="closeDetails()">Close</button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script>
// ── Records from PHP ───────────────────────────────────────
const records = <?= $js_records_json ?>;

// ── Filter buttons ─────────────────────────────────────────
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const f = this.getAttribute('data-filter');
        document.querySelectorAll('#historyTable tbody tr').forEach(tr => {
            tr.style.display = (f === 'all' || tr.getAttribute('data-type') === f) ? '' : 'none';
        });
    });
});

// ── Helpers ────────────────────────────────────────────────
function dval(v, cls = '') {
    const empty = !v || v === '—';
    return `<span class="d-value${cls ? ' ' + cls : ''}${empty ? ' empty' : ''}">${empty ? 'Not recorded' : escHtml(v)}</span>`;
}
function escHtml(s) {
    if (!s) return '';
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function field(label, value, cls = '') {
    return `<div class="d-field"><span class="d-label">${label}</span>${dval(value, cls)}</div>`;
}
function statusPill(s) {
    const map = { 'Completed':'pill-completed', 'Active':'pill-active', 'Requested':'pill-follow-up', 'Released':'pill-active' };
    const cls = map[s] || 'pill-completed';
    return `<span class="status-pill ${cls}">${escHtml(s)}</span>`;
}
function vitalCard(id, label, val, unit) {
    const has = val && val !== '—';
    return `<div class="vital-card${has ? ' has-value' : ''}">
        <div class="v-label">${label}</div>
        <div class="v-value">${escHtml(val) || '—'}</div>
        <div class="v-unit">${unit}</div>
    </div>`;
}
function vitalsBlock(r) {
    return `<div class="d-section-title">Vital Signs</div>
    <div class="vitals-grid">
        ${vitalCard('bp',  'Blood Pressure', r.bp,   'mmHg')}
        ${vitalCard('temp','Temperature',    r.temp, '°C')}
        ${vitalCard('hr',  'Heart Rate',     r.hr,   'bpm')}
        ${vitalCard('rr',  'Respiratory Rate',r.rr,  '/min')}
        ${vitalCard('ht',  'Height',         r.ht,   'cm')}
        ${vitalCard('wt',  'Weight',         r.wt,   'kg')}
    </div>`;
}
function followUpBlock(dateStr) {
    if (!dateStr || dateStr === '—') return '';
    return `<div class="d-section-title">Follow-up</div>
    <div class="followup-alert">
        <div class="fa-text">Your follow-up appointment is scheduled on
            <span class="fa-date">${escHtml(dateStr)}</span>.
            Please visit the clinic or book an appointment on or before this date.
        </div>
    </div>`;
}

// ── Build modal body per record type ──────────────────────
function buildBody(r) {
    switch (r.type) {

        case 'Consultation':
            return `
            <div class="d-section-title">Patient Information</div>
            <div class="d-grid-2">
                ${field('Consultation Type', r.consultType)}
                ${field('College / Department', r.department)}
                ${r.daysOfRest ? field('Days of Rest', r.daysOfRest + ' day(s)') : ''}
            </div>
            ${vitalsBlock(r)}
            <div class="d-section-title">Clinical Information</div>
            <div class="d-grid-1">${field('Chief Complaint / Symptoms', r.complaint, 'multiline')}</div>
            <div class="d-grid-2">
                ${field('Physical Examination Findings', r.findings, 'multiline')}
                ${field('Diagnosis / Assessment', r.diagnosis, 'multiline')}
            </div>
            <div class="d-grid-1">${field('Treatment & Recommendations', r.treatment, 'multiline')}</div>
            <div class="d-grid-2">
                ${field('Medications Given', r.medications, 'multiline')}
                ${field('Additional Notes', r.notes, 'multiline')}
            </div>
            ${followUpBlock(r.followUp)}`;

        case 'Health Exam':
            return `
            <div class="d-section-title">Exam Information</div>
            <div class="d-grid-2">
                ${field('School Year / Semester', r.schoolYear)}
                ${field('Fit Status', r.fitStatus)}
                ${field('General Condition', r.generalCond)}
            </div>
            ${vitalsBlock(r)}
            <div class="d-section-title">Medical History</div>
            <div class="d-grid-2">
                ${field('Known Allergies', r.allergies)}
                ${field('Current Medications', r.medications)}
                ${field('Past Illnesses', r.pastIllness, 'multiline')}
                ${field('Surgeries / Operations', r.surgeries, 'multiline')}
                ${field('Previous Hospitalizations', r.hospitalizations, 'multiline')}
            </div>
            <div class="d-section-title">Clinical Findings</div>
            <div class="d-grid-2">
                ${field('Physical Findings', r.findings, 'multiline')}
                ${field('Working Impression', r.diagnosis, 'multiline')}
            </div>
            <div class="d-grid-2">
                ${r.forWorkup ? field('For Workup', r.forWorkup, 'multiline') : ''}
                ${field('Remarks', r.notes, 'multiline')}
            </div>
            ${followUpBlock(r.followUp)}`;

        case 'Laboratory Request':
            const tags = r.tests
                ? r.tests.split(', ').map(t => `<span class="lab-tag">${escHtml(t)}</span>`).join('')
                : '<span style="color:#9ca3af;font-style:italic;">No tests recorded</span>';
            return `
            <div class="d-section-title">Requested Tests</div>
            <div class="lab-tags">${tags}</div>
            <div class="d-section-title">Details</div>
            <div class="d-grid-2">
                ${field('Request Status', r.status)}
            </div>
            <div class="d-grid-1">${field('Physician Notes', r.notes, 'multiline')}</div>`;

        case 'Medical Clearance':
            return `
            <div class="d-section-title">Clearance Details</div>
            <div class="d-grid-2">
                ${field('Purpose', r.purpose)}
                ${field('Fit Status', r.fitStatus)}
                ${field('Valid Until', r.validUntil)}
                ${field('School Year', r.schoolYear)}
                ${field('Issuing Physician', r.physician)}
                ${field('License No.', r.license)}
            </div>
            <div class="d-grid-1">
                ${r.conditions ? field('Conditions Noted', r.conditions, 'multiline') : ''}
                ${field('Remarks', r.notes, 'multiline')}
            </div>`;

        case 'Medical Certificate':
            return `
            <div class="d-section-title">Certificate Details</div>
            <div class="d-grid-2">
                ${field('Certificate Type', r.consultType)}
                ${field('Status', r.certStatus)}
                ${field('Valid Until', r.validUntil)}
                ${r.daysOfRest ? field('Days of Rest', r.daysOfRest + ' day(s)') : ''}
                ${field('Issuing Physician', r.physician)}
                ${field('License No.', r.license)}
            </div>
            <div class="d-grid-1">
                ${field('Purpose', r.purpose, 'multiline')}
                ${r.diagnosis ? field('Diagnosis', r.diagnosis, 'multiline') : ''}
                ${field('Remarks', r.notes, 'multiline')}
            </div>`;

        default:
            return `<p style="padding:20px;color:var(--text-gray);">No detail view available for this record type.</p>`;
    }
}

// ── Open modal ─────────────────────────────────────────────
function viewDetails(index) {
    const r = records[index];

    document.getElementById('modal-type-title').textContent = r.type;

    document.getElementById('d-meta-date').textContent    = r.date     || '—';
    document.getElementById('d-meta-time').textContent    = r.time     || '—';
    document.getElementById('d-meta-provider').textContent =
        r.staffName ? r.staffName + (r.staffPos ? ' (' + r.staffPos + ')' : '') : '—';
    document.getElementById('d-meta-subtype').textContent = r.subType  || r.consultType || '—';

    document.getElementById('modal-body-content').innerHTML = buildBody(r);

    document.getElementById('detailsModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeDetails() {
    document.getElementById('detailsModal').classList.remove('show');
    document.body.style.overflow = 'auto';
}

document.getElementById('detailsModal').addEventListener('click', function (e) {
    if (e.target === this) closeDetails();
});
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDetails(); });

// ── Chatbot ────────────────────────────────────────────────
const totalRec  = <?= $total_records ?>;
const latestRec = records.length > 0 ? records[0] : null;

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
    setTimeout(() => { hideTypingIndicator(); addMessage(getBotResponse(text), 'bot'); }, 900 + Math.random() * 700);
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
    if (m.includes('record') || m.includes('history'))
        return `You have <strong>${totalRec} medical record${totalRec !== 1 ? 's' : ''}</strong> on file. Click <em>View</em> on any row to see the full details.`;
    if (m.includes('latest') || m.includes('recent') || m.includes('last'))
        return latestRec
            ? `Your most recent record is a <strong>${escHtml(latestRec.type)}</strong> dated <strong>${escHtml(latestRec.date)}</strong>.`
            : 'No records found yet. Your records will appear here after a clinic visit.';
    if (m.includes('download') || m.includes('print'))
        return "To print a record, open it using <em>View</em> and use your browser's print function (Ctrl+P / Cmd+P).";
    if (m.includes('diagnosis') || m.includes('treatment'))
        return "Click <em>View</em> next to any consultation record to see the full diagnosis, treatment, and medications prescribed.";
    if (m.includes('follow') || m.includes('followup'))
        return "Follow-up dates are shown in the <strong>Follow-up</strong> column. Dates marked in orange require your attention.";
    if (m.includes('hello') || m.includes('hi'))
        return "Hello! I can help you view and understand your medical history. What would you like to know?";
    return "I can help you view past consultations, diagnoses, lab requests, and certificates. What do you need?";
}

chatbotSend.addEventListener('click', () => sendMessage());
chatbotInput.addEventListener('keypress', e => { if (e.key === 'Enter') sendMessage(); });

function escHtml(s) {
    if (!s) return '';
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>
</body>
</html>