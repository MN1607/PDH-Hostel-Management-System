<?php
require 'config.php'; // Database connection
session_start();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$stmt = $conn->prepare("SELECT * FROM reading_requests ORDER BY id DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Reading Requests Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(45deg, #ff9a9e, #fad0c4, #ffdde1);
            min-height: 100vh;
            padding: 2rem;
        }
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            padding: 2rem;
        }
        .header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .header h2 {
            color: #ff4b5c;
            font-weight: 700;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-responded { background: #28a745; color: white; }
        .status-indicator {
            padding: 0.3rem 0.6rem;
            border-radius: 1rem;
            font-size: 0.875rem;
        }
        .action-btn {
            background: #ff758c;
            border: none;
            transition: all 0.3s ease;
        }
        .action-btn:hover { background: #ff4b5c; }
        .response-textarea { resize: vertical; min-height: 100px; }
        .error-message { display: none; color: #dc3545; margin-top: 0.5rem; }
        .dashboard-btn {
            background: #ff5722;
            border: none;
            width: 250px;
            margin: 2rem auto 0;
            display: block;
        }
        .dashboard-btn:hover { background: #e64a19; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Admin - Reading Requests Dashboard</h2>
            <small class="text-muted"><?php echo date('F d, Y'); ?></small>
        </div>

        <?php if ($result->num_rows === 0): ?>
            <div class="alert alert-info">No reading requests found.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Email</th>
                            <th>Hostel</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr data-request-id="<?php echo $row['id']; ?>">
                                <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['hostel']); ?></td>
                                <td><?php echo htmlspecialchars($row['reason']); ?></td>
                                <td>
                                    <span class="status-indicator status-<?php echo $row['admin_response'] ? 'responded' : 'pending'; ?>">
                                        <?php echo $row['admin_response'] ? 'Responded' : 'Pending'; ?>
                                    </span>
                                </td>
                                <td>
                                    <textarea class="form-control response-textarea" id="response_<?php echo $row['id']; ?>" 
                                        placeholder="Enter your response..." required <?php echo $row['admin_response'] ? 'disabled' : ''; ?>>
                                        <?php echo $row['admin_response'] ? htmlspecialchars($row['admin_response']) : ''; ?>
                                    </textarea>
                                    <div class="error-message" id="error_<?php echo $row['id']; ?>"></div>
                                    <button class="btn action-btn mt-2 w-100" onclick="sendResponse(<?php echo $row['id']; ?>)"
                                        <?php echo $row['admin_response'] ? 'disabled' : ''; ?>>
                                        <span class="btn-text">Send Response</span>
                                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <a href="http://localhost/Hpoint/Admindashboard/admindashboard.html" class="btn dashboard-btn">Go Back to Dashboard</a>
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
                formData.append('request_id', id);
                formData.append('response', response);
                formData.append('csrf_token', '<?php echo $_SESSION['csrf_token']; ?>');

                const res = await fetch('send_reading_response.php', {
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