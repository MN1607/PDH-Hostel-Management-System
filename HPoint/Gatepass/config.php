<?php
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'student_admin_login';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
