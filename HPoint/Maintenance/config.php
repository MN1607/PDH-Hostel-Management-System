<?php
$servername = "localhost"; // Change if needed
$username = "root"; // Default for XAMPP
$password = ""; // Default for XAMPP
$dbname = "student_admin_login"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
