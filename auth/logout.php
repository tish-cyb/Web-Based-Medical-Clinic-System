<?php
session_start();

// Destroy session completely
session_unset();
session_destroy();

// Redirect to login page
// ⚠️  Adjust path to match your folder structure.
//     If process_login.php is inside a subfolder (e.g. /auth/logout.php),
//     use the path below. If it sits at the root, use "index.php" directly.
header("Location: ../index.php");
exit();
?>