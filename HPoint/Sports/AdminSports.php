<?php
require 'config.php'; // Database connection
session_start();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!$conn) {
    http_response_code(500);
    die(json_encode(['error' => 'Database connection failed: ' . mysqli_connect_error()]));
}

try {
    $stmt = $conn->prepare("
        SELECT sr.*, ar.response, ar.response_date
        FROM sports_requests sr
        LEFT JOIN admin_responses ar ON sr.id = ar.request_id
        ORDER BY sr.created_at DESC
    ");
    $stmt->execute();
    $result = $stmt->get_result();
} catch (Exception $e) {
    http_response_code(500);
    die(json_encode(['error' => 'Query failed: ' . $e->getMessage()]));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Sports Request Management">
    <title>Admin Dashboard - Sports Request Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        :root {
            --primary-color: #003087;
            --secondary-color: #0047ab;
            --accent-color: #ff4500;
            --success-color: #28a745;
            --warning-color: #dc3545;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            min-height: 100vh;
            padding: 2rem;
        }
        .dashboard-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: var(--primary-color);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        .header h1 {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .status-indicator {
            padding: 0.3rem 0.6rem;
            border-radius: 1rem;
            font-size: 0.875rem;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-responded { background: var(--success-color); color: white; }
        .action-btn {
            background: var(--accent-color);
            border: none;
            transition: all 0.3s ease;
        }
        .action-btn:hover { background: #e03e00; }
        .response-textarea { resize: vertical; min-height: 100px; }
        .error-message { display: none; color: var(--warning-color); margin-top: 0.5rem; }
        .table th, .table td { vertical-align: middle; }
    </style>
</head>
<body>
    <div class="container dashboard-container">
        <div class="header">
            <h1>Sports Request Management Dashboard</h1>
            <small class="text-light">Admin Panel - <?php echo date('F d, Y'); ?></small>
        </div>

        <div class="table-responsive m-3">
            <?php if ($result->num_rows === 0): ?>
                <div class="alert alert-info" role="alert">No sports requests found.</div>
            <?php else: ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Leader Name</th>
                            <th>Email</th>
                            <th>Items</th>
                            <th>Message</th>
                            <th>Requested</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr data-request-id="<?php echo $row['id']; ?>">
                                <td><?php echo htmlspecialchars($row['leader_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['items']); ?></td>
                                <td><?php echo htmlspecialchars($row['message'] ?? 'None'); ?></td>
                                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                <td>
                                    <span class="status-indicator status-<?php echo $row['response'] ? 'responded' : 'pending'; ?>">
                                        <?php echo $row['response'] ? 'Responded' : 'Pending'; ?>
                                    </span>
                                </td>
                                <td>
                                    <textarea class="form-control response-textarea" id="response_<?php echo $row['id']; ?>" 
                                        placeholder="Enter your response..." required <?php echo $row['response'] ? 'disabled' : ''; ?>>
                                        <?php echo $row['response'] ? htmlspecialchars($row['response']) : ''; ?>
                                    </textarea>
                                    <div class="error-message" id="error_<?php echo $row['id']; ?>"></div>
                                    <button class="btn action-btn mt-2 w-100" onclick="sendResponse(<?php echo $row['id']; ?>)"
                                        <?php echo $row['response'] ? 'disabled' : ''; ?>>
                                        <span class="btn-text">Submit Response</span>
                                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <script>
        async function sendResponse(id) {
            const button = document.querySelector(`button[onclick="sendResponse(${id})"]`);
            const textarea = document.getElementById(`response_${id}`);
            const errorDiv = document.getElementById(`error_${id}`);
            const response = textarea.value.trim();

            if (!response) {
                errorDiv.textContent = 'Response cannot be empty';
                errorDiv.style.display = 'block';
                return;
            }

            button.disabled = true;
            button.querySelector('.btn-text').classList.add('d-none');
            button.querySelector('.spinner-border').classList.remove('d-none');

            try {
                const formData = new FormData();
                formData.append('response', response);
                formData.append('id', id);
                formData.append('csrf_token', '<?php echo $_SESSION['csrf_token']; ?>');

                const res = await fetch('send_response.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Server error');

                if (data.success) {
                    const statusElement = button.closest('tr').querySelector('.status-indicator');
                    statusElement.textContent = 'Responded';
                    statusElement.classList.remove('status-pending');
                    statusElement.classList.add('status-responded');
                    textarea.disabled = true;
                    button.disabled = true;
                    errorDiv.style.display = 'none';
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                errorDiv.textContent = error.message || 'Failed to send response';
                errorDiv.style.display = 'block';
                button.disabled = false;
            } finally {
                button.querySelector('.btn-text').classList.remove('d-none');
                button.querySelector('.spinner-border').classList.add('d-none');
            }
        }
    </script>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>