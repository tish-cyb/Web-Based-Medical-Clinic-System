<?php
session_start();
require_once "config/db.php"; 

$user = [
    '202313028MN0' => ['password' => 'student123', 'role' => 'Student', 'portal' => 'student_portal.php'],
    'PRC123456' => ['password' => 'staff123', 'role' => 'Staff', 'portal' => 'staff_portal.php'],
    'admin.itech@pup.edu.ph' => ['password' => 'admin123', 'role' => 'Administrator', 'portal' => 'admin_portal.php'],
];

// ONLY run this logic if the form was actually submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_btn'])) {
    
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // If both fields are empty, don't show an error, just stop
    if (empty($username) || empty($password)) {
        header("Location: index.php");
        exit();
    }

    // --- STEP 1: VALIDATION ---
    if (isset($user[$username]) && $user[$username]['password'] === $password) {
        
        $role = $user[$username]['role'];
        $portal = $user[$username]['portal'];

        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        
        // --- STEP 2: SAVE TO DATABASE ---
        $q = "INSERT INTO user (username, password) VALUES (?, ?)";
        $stmt = mysqli_prepare($c, $q);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $username, $password);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        header("Location: " . $portal);
        exit();

    } else {
        // This ONLY shows if the user actually typed something wrong and hit submit
        echo "<script>alert('Invalid credentials. Please try again.'); window.location.href = 'index.php';</script>";
    }
}
?>