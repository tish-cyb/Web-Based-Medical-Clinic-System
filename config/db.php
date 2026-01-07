<?php
// MySQL connection
$host = "localhost";          // usually localhost
$user = "root";               // your MySQL username
$pass = "";                   // your MySQL password (empty by default in XAMPP)
$db   = "pup_itechcare";      // your database name

$c = mysqli_connect($host, $user, $pass, $db);

// Check connection
if (!$c) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
