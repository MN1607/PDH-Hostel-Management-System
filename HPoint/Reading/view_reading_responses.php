<?php
require 'config.php';

$email = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_EMAIL);
if (!$email) {
    echo "Please provide a valid email.";
    exit;
}

$stmt = $conn->prepare("SELECT reason, admin_response FROM reading_requests WHERE email = ? ORDER BY id DESC");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $response = $row['admin_response'] ? htmlspecialchars($row['admin_response']) : "No response yet.";
        echo "<p><strong>Reason:</strong> " . htmlspecialchars($row['reason']) . "<br><strong>Response:</strong> " . $response . "</p><hr>";
    }
} else {
    echo "No requests found for this email.";
}

$stmt->close();
$conn->close();
?>