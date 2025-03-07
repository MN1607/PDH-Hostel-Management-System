<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_id = $_POST['request_id'];
    $response = $_POST['response'];

    // Insert response into database
    $stmt = $conn->prepare("INSERT INTO maintenance_responses (request_id, response) VALUES (?, ?)");
    $stmt->bind_param("is", $request_id, $response);

    if ($stmt->execute()) {
        echo "Response sent successfully!";
    } else {
        echo "Error sending response: " . $conn->error;
    }
}
?>
