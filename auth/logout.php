<?php
session_start();

// Destroy session
session_unset();
session_destroy();

// Redirect back to index
header("Location: ../index.php");
exit();
?>
