<?php
require 'config.php'; // Database connection (assumes $conn is mysqli object)
session_start();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$response = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_request'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $response = "Security error: Invalid CSRF token.";
    } else {
        $leader_name = filter_input(INPUT_POST, 'leader_name', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $items = filter_input(INPUT_POST, 'items', FILTER_SANITIZE_STRING);
        $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

        if (!$leader_name || !$email || !$items) {
            $response = "All required fields must be filled.";
        } else {
            $stmt = $conn->prepare("INSERT INTO sports_requests (leader_name, email, items, message, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param("ssss", $leader_name, $email, $items, $message);
            if ($stmt->execute()) {
                $response = "Request submitted successfully!";
            } else {
                $response = "Error submitting request: " . $conn->error;
            }
            $stmt->close();
        }
    }
}

// View admin responses
$admin_responses = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['view_response'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    if ($email) {
        $stmt = $conn->prepare("
            SELECT sr.items, sr.message, sr.created_at, ar.response, ar.response_date
            FROM sports_requests sr
            LEFT JOIN admin_responses ar ON sr.id = ar.request_id
            WHERE sr.email = ?
            ORDER BY sr.created_at DESC
        ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $admin_responses .= "<h3>Your Requests & Responses</h3>";
            while ($row = $result->fetch_assoc()) {
                $response_date = $row['response_date'] ? htmlspecialchars($row['response_date']) : 'Pending';
                $admin_responses .= sprintf(
                    "<div class='card mb-3'><div class='card-body'>
                        <p><strong>Requested:</strong> %s<br>
                        <strong>Items:</strong> %s<br>
                        <strong>Message:</strong> %s<br>
                        <strong>Admin Response:</strong> %s<br>
                        <strong>Response Date:</strong> %s</p>
                    </div></div>",
                    htmlspecialchars($row['created_at']),
                    htmlspecialchars($row['items']),
                    htmlspecialchars($row['message'] ?? 'None'),
                    htmlspecialchars($row['response'] ?? 'No response yet'),
                    $response_date
                );
            }
        } else {
            $admin_responses = "<div class='alert alert-info'>No requests found for this email.</div>";
        }
        $stmt->close();
    } else {
        $admin_responses = "<div class='alert alert-warning'>Please enter a valid email.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sports Equipment Request Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #e6f0fa, #d0e0f0);
            min-height: 100vh;
            padding: 2rem;
        }
        .container {
            max-width: 900px;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .header h2 {
            color: #003087;
            font-weight: 700;
        }
        .subtitle {
            color: #666;
        }
        .form-control:focus {
            border-color: #0047ab;
            box-shadow: 0 0 0 0.2rem rgba(0, 71, 171, 0.25);
        }
        .btn-primary {
            background-color: #003087;
            border-color: #003087;
        }
        .btn-primary:hover {
            background-color: #0047ab;
            border-color: #0047ab;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        .response-section {
            margin-top: 3rem;
        }
        .card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Sports Equipment Request Portal</h2>
            <p class="subtitle">Submit and track your equipment requests</p>
        </div>

        <?php if ($response): ?>
            <div class="alert alert-<?php echo strpos($response, 'success') !== false ? 'success' : 'danger'; ?>">
                <?php echo $response; ?>
            </div>
        <?php endif; ?>

        <form action="" method="post" class="mb-4">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="mb-3">
                <label for="leader_name" class="form-label">Leader Name</label>
                <input type="text" class="form-control" id="leader_name" name="leader_name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="items" class="form-label">Items Needed</label>
                <textarea class="form-control" id="items" name="items" required rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Additional Message (Optional)</label>
                <textarea class="form-control" id="message" name="message" rows="3"></textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" name="submit_request" class="btn btn-primary">Submit Request</button>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='http://localhost/Hpoint/Index/index2.php'">Go Back</button>
            </div>
        </form>

        <section class="response-section">
            <h3>View Admin Responses</h3>
            <form action="" method="post" class="mb-3">
                <div class="mb-3">
                    <label for="response_email" class="form-label">Your Email</label>
                    <input type="email" class="form-control" id="response_email" name="email" required>
                </div>
                <button type="submit" name="view_response" class="btn btn-info">Check Responses</button>
            </form>
            <div id="admin_responses"><?php echo $admin_responses; ?></div>
        </section>
    </div>
</body>
</html>
<?php $conn->close(); ?>