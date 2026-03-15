<?php
session_start();
require_once "config/db.php";   // provides $c (mysqli connection)

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_btn'])) {

    // ----------------------------------------------------------------
    // 1. READ & SANITISE INPUT
    //    login.php uses name="identifier" (email / student no / license)
    //    and name="password". Fixed from the old name="username".
    // ----------------------------------------------------------------
    $identifier = trim($_POST['identifier']);   // ← was $_POST['username']
    $password   = trim($_POST['password']);

    if (empty($identifier) || empty($password)) {
        header("Location: index.php?error=empty");
        exit();
    }

    // ----------------------------------------------------------------
    // 2. LOOK UP THE USER
    //    The users table uses the 'email' column as the login identifier.
    //    We also support student_number (students table) and
    //    employee_number (employees table) and license_number (medical_staff).
    //    One query handles all four cases with a LEFT JOIN approach.
    //
    //    Columns fetched:
    //      u.user_id       – stored in session for all queries
    //      u.email         – stored in session
    //      u.password_hash – verified with password_verify()
    //      u.role          – drives the redirect switch below
    //      u.is_active     – account must be active
    //
    //    Profile IDs fetched so we never need a second query at login:
    //      s.student_id
    //      e.employee_id
    //      ms.staff_id
    // ----------------------------------------------------------------
    $q = "
        SELECT
            u.user_id,
            u.email,
            u.password_hash,
            u.role,
            u.is_active,
            s.student_id,
            s.student_number,
            e.employee_id,
            e.employee_number,
            ms.staff_id,
            ms.license_number
        FROM users u
        LEFT JOIN students     s  ON s.user_id  = u.user_id
        LEFT JOIN employees    e  ON e.user_id  = u.user_id
        LEFT JOIN medical_staff ms ON ms.user_id = u.user_id
        WHERE
            u.email            = ?   -- email login (all roles)
         OR s.student_number   = ?   -- student number login
         OR e.employee_number  = ?   -- employee number login
         OR ms.license_number  = ?   -- medical staff license login
        LIMIT 1
    ";

    $stmt = mysqli_prepare($c, $q);
    mysqli_stmt_bind_param($stmt, "ssss",
        $identifier, $identifier, $identifier, $identifier
    );
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row    = mysqli_fetch_assoc($result);

    // ----------------------------------------------------------------
    // 3. VALIDATE — user exists, account active, password correct
    // ----------------------------------------------------------------
    if (!$row || !$row['is_active']) {
        echo "<script>alert('Invalid credentials. Please try again.'); window.location.href = 'index.php';</script>";
        exit();
    }

    // password_verify() checks the input against the bcrypt hash in the DB
    if (!password_verify($password, $row['password_hash'])) {   // ← was $row['password']
        echo "<script>alert('Invalid credentials. Please try again.'); window.location.href = 'index.php';</script>";
        exit();
    }

    // ----------------------------------------------------------------
    // 4. POPULATE SESSION
    //    Store only the safe, non-sensitive fields your portal pages need.
    //    profile_id is the PK of the role-specific profile table —
    //    use this in every WHERE clause on your portal pages.
    // ----------------------------------------------------------------
    session_regenerate_id(true);    // prevent session fixation attacks

    $_SESSION['user_id'] = $row['user_id'];
    $_SESSION['email']   = $row['email'];
    $_SESSION['role']    = $row['role'];

    switch ($row['role']) {
        case 'student':
            $_SESSION['profile_id']    = $row['student_id'];
            $_SESSION['id_number']     = $row['student_number'];
            break;
        case 'employee':
            $_SESSION['profile_id']    = $row['employee_id'];
            $_SESSION['id_number']     = $row['employee_number'];
            break;
        case 'medical_staff':
        case 'admin':
            $_SESSION['profile_id']    = $row['staff_id'];
            $_SESSION['id_number']     = $row['license_number'];
            break;
    }

    // Update last_login timestamp
    $upd = mysqli_prepare($c, "UPDATE users SET last_login = NOW() WHERE user_id = ?");
    mysqli_stmt_bind_param($upd, "i", $row['user_id']);
    mysqli_stmt_execute($upd);

    // ----------------------------------------------------------------
    // 5. REDIRECT BASED ON ROLE
    //    Role values in the DB (users.role ENUM):
    //      'student'       → Student Portal
    //      'employee'      → Employee Portal
    //      'medical_staff' → Medical Portal
    //      'admin'         → Medical Portal (with admin privileges)
    //
    //    ⚠️  Update the paths below to match your actual folder structure.
    // ----------------------------------------------------------------
    switch ($row['role']) {
        case 'student':
            header("Location: student/book_appointment.php");
            break;
        case 'employee':
            header("Location: employee/book_appointment.php");
            break;
        case 'medical_staff':
            header("Location: medical/medical_dashboard.php");
            break;
        case 'admin':
            header("Location: medical/medical_dashboard.php");
            break;
        default:
            header("Location: index.php");
    }
    exit();
}
?>