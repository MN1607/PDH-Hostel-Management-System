<?php
// Database configuration
define('DB_SERVER', 'localhost');       // Database server (e.g., localhost)
define('DB_USERNAME', 'root');          // Database username (replace with your username)
define('DB_PASSWORD', '');              // Database password (replace with your password)
define('DB_NAME', 'hostel_attendance'); // Database name

// Create a database connection
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    // Log the error (do not expose it to the user)
    error_log("Database connection failed: " . $conn->connect_error);

    // Display a generic error message to the user
    die("An error occurred while connecting to the database. Please try again later.");
}
?>