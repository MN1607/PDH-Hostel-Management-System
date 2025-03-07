<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $conn->real_escape_string($_POST['email']);

    $sql = "SELECT * FROM messages WHERE email = '$email' ORDER BY id DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['student_name']) ?></td>
                <td><?= htmlspecialchars($row['year']) ?></td>
                <td><?= htmlspecialchars($row['department']) ?></td>
                <td><?= htmlspecialchars($row['message']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td><?= !empty($row['admin_reply']) ? htmlspecialchars($row['admin_reply']) : 'No reply yet' ?></td>
            </tr>
        <?php endwhile;
    } else {
        echo "<tr><td colspan='6'>No messages found for this email.</td></tr>";
    }
}
?>
