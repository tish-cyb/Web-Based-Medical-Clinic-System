<?php
session_start();
require_once "config/db.php"; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_btn'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        header("Location: index.php");
        exit();
    }

    // --- STEP 1: FETCH USER FROM DATABASE ---
    $q = "SELECT username, password, role FROM users WHERE username = ?";
    $stmt = mysqli_prepare($c, $q);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // --- STEP 2: VERIFY PASSWORD ---
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            // --- STEP 3: REDIRECT BASED ON ROLE ---
            switch ($row['role']) {
                case 'Student':
                    header("Location: student_portal.php");
                    break;
                case 'Staff':
                    header("Location: staff_portal.php");
                    break;
                case 'Administrator':
                    header("Location: admin_portal.php");
                    break;
                default:
                    header("Location: index.php");
            }
            exit();
        }
    }

    // --- INVALID LOGIN ---
    echo "<script>alert('Invalid credentials. Please try again.'); window.location.href = 'index.php';</script>";
}
?>
