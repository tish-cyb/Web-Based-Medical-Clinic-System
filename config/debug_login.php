<?php
/**
 * debug_login.php — AiCare System Login Debugger
 * ─────────────────────────────────────────────────
 * DROP this file in the SAME folder as process_login.php.
 * Open it in your browser: http://localhost/your-project/debug_login.php
 * DELETE it immediately after fixing the issue.
 * ─────────────────────────────────────────────────
 */

// ── 1. DB CONNECTION ──────────────────────────────
$host = "localhost";
$user = "root";
$pass = "";
$db   = "aicaresystem";   // all lowercase — must match exactly

echo "<h2>Step 1 — Database Connection</h2>";
$c = mysqli_connect($host, $user, $pass, $db);

if (!$c) {
    echo "<p style='color:red'>❌ CONNECTION FAILED: " . mysqli_connect_error() . "</p>";
    echo "<p>Common causes:</p><ul>
            <li>Wrong database name — check phpMyAdmin, it must be <b>aicaresystem</b> (all lowercase)</li>
            <li>MySQL not running — start it in XAMPP Control Panel</li>
            <li>Wrong username/password</li>
          </ul>";
    exit();
}
echo "<p style='color:green'>✅ Connected to <b>$db</b> successfully.</p>";

// ── 2. CHECK DATABASE EXISTS & TABLES ─────────────
echo "<h2>Step 2 — Tables in '$db'</h2>";
$tables = mysqli_query($c, "SHOW TABLES");
$found  = [];
while ($row = mysqli_fetch_row($tables)) {
    $found[] = $row[0];
    echo "• " . $row[0] . "<br>";
}
$required = ['users','students','employees','medical_staff'];
foreach ($required as $t) {
    if (!in_array($t, $found)) {
        echo "<p style='color:red'>❌ Table <b>$t</b> is MISSING — did you fully import aicaresystem.sql?</p>";
    }
}

// ── 3. CHECK USERS TABLE COLUMNS ──────────────────
echo "<h2>Step 3 — Columns in 'users' table</h2>";
$cols = mysqli_query($c, "DESCRIBE users");
if (!$cols) {
    echo "<p style='color:red'>❌ Cannot read users table: " . mysqli_error($c) . "</p>";
} else {
    $colNames = [];
    while ($col = mysqli_fetch_assoc($cols)) {
        $colNames[] = $col['Field'];
        echo "• <b>{$col['Field']}</b> — {$col['Type']}<br>";
    }
    $needCols = ['user_id','email','password_hash','password_plain','role','is_active'];
    foreach ($needCols as $cn) {
        if (!in_array($cn, $colNames)) {
            echo "<p style='color:red'>❌ Column <b>$cn</b> is MISSING in users table.</p>";
        }
    }
}

// ── 4. CHECK ALL USERS IN DB ──────────────────────
echo "<h2>Step 4 — All users in the database</h2>";
$rows = mysqli_query($c, "
    SELECT u.user_id, u.email, u.password_plain, u.role, u.is_active,
           s.student_number, e.employee_number, ms.license_number
    FROM   users u
    LEFT JOIN students      s  ON s.user_id  = u.user_id
    LEFT JOIN employees     e  ON e.user_id  = u.user_id
    LEFT JOIN medical_staff ms ON ms.user_id = u.user_id
    ORDER BY u.user_id
");
if (!$rows) {
    echo "<p style='color:red'>❌ Query failed: " . mysqli_error($c) . "</p>";
} else {
    echo "<table border='1' cellpadding='6' style='border-collapse:collapse;font-size:13px'>
            <tr style='background:#eee'>
                <th>user_id</th><th>email</th><th>plain password</th>
                <th>role</th><th>active</th>
                <th>student_no</th><th>employee_no</th><th>license_no</th>
            </tr>";
    $count = 0;
    while ($r = mysqli_fetch_assoc($rows)) {
        $count++;
        echo "<tr>
                <td>{$r['user_id']}</td>
                <td>{$r['email']}</td>
                <td style='color:darkorange'>{$r['password_plain']}</td>
                <td><b>{$r['role']}</b></td>
                <td>" . ($r['is_active'] ? '✅' : '❌ INACTIVE') . "</td>
                <td>" . ($r['student_number']  ?? '—') . "</td>
                <td>" . ($r['employee_number'] ?? '—') . "</td>
                <td>" . ($r['license_number']  ?? '—') . "</td>
              </tr>";
    }
    echo "</table>";
    if ($count === 0) {
        echo "<p style='color:red'>❌ NO USERS FOUND — the sample data was not inserted.
              Re-import aicaresystem.sql or run only the INSERT INTO users block.</p>";
    } else {
        echo "<p style='color:green'>✅ Found <b>$count</b> user(s).</p>";
    }
}

// ── 5. SIMULATE A LOGIN ATTEMPT ───────────────────
echo "<h2>Step 5 — Simulate login attempt</h2>";
echo "<form method='POST'>
        <label>Identifier (email / student no / employee no / license):</label><br>
        <input type='text' name='test_identifier' style='width:300px;padding:5px' value='admin@aicare.pup.edu.ph'><br><br>
        <label>Password:</label><br>
        <input type='password' name='test_password' style='width:300px;padding:5px' value=''><br><br>
        <button type='submit' name='run_test' style='padding:8px 20px'>Test Login</button>
      </form>";

if (isset($_POST['run_test'])) {
    $id  = trim($_POST['test_identifier']);
    $pwd = trim($_POST['test_password']);

    echo "<hr><h3>Results for identifier: <i>$id</i></h3>";

    $q = "
        SELECT
            u.user_id, u.email, u.password_hash, u.role, u.is_active,
            s.student_id,  s.student_number,
            e.employee_id, e.employee_number,
            ms.staff_id,   ms.license_number
        FROM users u
        LEFT JOIN students      s  ON s.user_id  = u.user_id
        LEFT JOIN employees     e  ON e.user_id  = u.user_id
        LEFT JOIN medical_staff ms ON ms.user_id = u.user_id
        WHERE u.email           = ?
           OR s.student_number  = ?
           OR e.employee_number = ?
           OR ms.license_number = ?
        LIMIT 1
    ";
    $stmt = mysqli_prepare($c, $q);
    mysqli_stmt_bind_param($stmt, "ssss", $id, $id, $id, $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);

    if (!$row) {
        echo "<p style='color:red'>❌ USER NOT FOUND — no row matched that identifier in any table.</p>";
        echo "<p>Things to check:</p><ul>
                <li>Did you type the email exactly? (case-sensitive)</li>
                <li>Check Step 4 above — copy an email directly from that table</li>
                <li>Did the JOIN to students/employees/medical_staff work? Check Step 2.</li>
              </ul>";
    } else {
        echo "<p style='color:green'>✅ User row found:</p>";
        echo "<pre>" . print_r($row, true) . "</pre>";

        if (!$row['is_active']) {
            echo "<p style='color:red'>❌ Account is INACTIVE (is_active = 0).</p>";
        } else {
            echo "<p style='color:green'>✅ Account is active.</p>";
        }

        if (empty($pwd)) {
            echo "<p style='color:orange'>⚠️  No password entered — skipping verification.</p>";
        } elseif (password_verify($pwd, $row['password_hash'])) {
            echo "<p style='color:green'>✅ Password CORRECT — login would succeed.</p>";
            echo "<p>Would redirect to role: <b>{$row['role']}</b></p>";
        } else {
            echo "<p style='color:red'>❌ Password WRONG — hash does not match.</p>";
            echo "<p>Hash in DB: <code>" . htmlspecialchars($row['password_hash']) . "</code></p>";
            echo "<p>Common causes:</p><ul>
                    <li>Wrong password typed</li>
                    <li>Hash was inserted incorrectly — try regenerating with:
                        <code>echo password_hash('Admin@2025!', PASSWORD_BCRYPT);</code></li>
                  </ul>";
        }
    }
}

mysqli_close($c);
echo "<hr><p style='color:gray;font-size:12px'>⚠️ DELETE debug_login.php after use — it exposes plain-text passwords.</p>";
?>