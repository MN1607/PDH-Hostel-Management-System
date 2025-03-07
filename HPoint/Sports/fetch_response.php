<?php
require 'config.php';

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    $stmt = $conn->prepare("SELECT response FROM sports_requests WHERE email = ? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($response);
    $stmt->fetch();

    echo $response ?: "No response yet";
}
?>