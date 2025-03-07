<?php
// Database configuration
$host = 'localhost';      // Database host (usually 'localhost')
$username = 'root';       // Database username
$password = '';           // Database password
$database = 'student_admin_login';  // Database name

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $database);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set the charset to UTF-8 (optional but recommended)
$conn->set_charset("utf8");

// You can now use the $conn variable to interact with the database
?>