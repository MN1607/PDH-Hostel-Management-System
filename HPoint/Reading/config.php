<?php
$host = "localhost";  // Ensure this is "localhost"
$user = "root";       // Default XAMPP user is "root"
$pass = "";           // No password by default in XAMPP, leave it empty
$dbname = "student_admin_login";  // Ensure this matches your database name

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
