<?php
require 'config.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

$request_id = filter_input(INPUT_POST, 'request_id', FILTER_VALIDATE_INT);
$response = filter_input(INPUT_POST, 'response', FILTER_SANITIZE_STRING);

if (!$request_id || !$response) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

try {
    $stmt = $conn->prepare("UPDATE reading_requests SET admin_response = ? WHERE id = ?");
    $stmt->bind_param("si", $response, $request_id);
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        throw new Exception("No rows updated or request not found.");
    }
    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
$conn->close();
?>