<?php
include 'config.php';

// Handle admin response
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_response'])) {
    $message_id = $_POST['message_id'];
    $response = $conn->real_escape_string($_POST['response']);
    $sql = "UPDATE messages SET admin_reply = '$response', status = 'Replied' WHERE id = $message_id";
    $conn->query($sql);
}

// Fetch all messages
$sql = "SELECT * FROM messages ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Messages</title>
    <link rel="stylesheet" href="admin_style.css">

</head>
<body>

<h2>Student Messages</h2>
<table border="1">
    <thead>
        <tr>
            <th>Student</th>
            <th>Message</th>
            <th>Status</th>
            <th>Admin Reply</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['student_name']) ?></td>
                <td><?= htmlspecialchars($row['message']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td><?= htmlspecialchars($row['admin_reply'] ?? 'No reply yet') ?></td>
                <td>
    <form method="POST">
        <input type="hidden" name="message_id" value="<?= $row['id'] ?>">
        <input type="text" name="response" required placeholder="Write response">
        <button type="submit" name="send_response" 
            style="background-color: <?= $row['status'] === 'Replied' ? '#28a745' : '#dc3545' ?>; color: white;">
            <?= $row['status'] === 'Replied' ? 'Replied' : 'Reply' ?>
        </button>
    </form>
</td>

            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
