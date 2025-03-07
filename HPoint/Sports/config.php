<?php
$conn = new mysqli("localhost", "root", "", "student_admin_login");
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>
