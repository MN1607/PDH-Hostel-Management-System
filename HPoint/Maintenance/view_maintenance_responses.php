<?php
require 'config.php';

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    // Fetch requests and responses for the given email
    $stmt = $conn->prepare("
        SELECT mr.issue_description, mr.items_to_buy, res.response
        FROM maintenance_requests mr
        LEFT JOIN maintenance_responses res ON mr.id = res.request_id
        WHERE mr.email = ?
        ORDER BY mr.id DESC
    ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<p><strong>Issue:</strong> " . $row['issue_description'] . "</p>";
        echo "<p><strong>Items Needed:</strong> " . $row['items_to_buy'] . "</p>";
        echo "<p><strong>Admin Response:</strong> " . ($row['response'] ? $row['response'] : "No response yet.") . "</p><hr>";
    }
}
?>
