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
        $student_name = filter_input(INPUT_POST, 'student_name', FILTER_SANITIZE_STRING);
        $hostel = filter_input(INPUT_POST, 'hostel', FILTER_SANITIZE_STRING);
        $reason = filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

        if (!$student_name || !$hostel || !$reason || !$email) {
            $response = "All fields are required.";
        } else {
            $stmt = $conn->prepare("INSERT INTO reading_requests (student_name, hostel, reason, email) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $student_name, $hostel, $reason, $email);
            if ($stmt->execute()) {
                $response = "Your request has been submitted successfully!";
            } else {
                $response = "Error submitting request: " . $conn->error;
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reading Module Request Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #1f1c2c, #928dab);
            min-height: 100vh;
            padding: 2rem;
            color: white;
        }
        .container {
            max-width: 800px;
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .header h2 {
            color: #1f1c2c;
            font-weight: 700;
        }
        .form-control:focus {
            border-color: #ff5e62;
            box-shadow: 0 0 0 0.2rem rgba(255, 94, 98, 0.25);
        }
        .btn-primary {
            background-color: #ff5e62;
            border-color: #ff5e62;
        }
        .btn-primary:hover {
            background-color: #e04e52;
            border-color: #e04e52;
        }
        .btn-secondary {
            background-color: #0085f9;
            border-color: #0085f9;
        }
        .btn-secondary:hover {
            background-color: #00bdf7;
            border-color: #00bdf7;
        }
        .response-section {
            margin-top: 2rem;
        }
        .response-box {
            margin-top: 1rem;
            padding: 1rem;
            background: #f8d7da;
            border-radius: 5px;
            color: #721c24;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Reading Module Request Portal</h2>
            <p>Submit your reading space request below</p>
        </div>

        <?php if ($response): ?>
            <div class="alert alert-<?php echo strpos($response, 'success') !== false ? 'success' : 'danger'; ?>">
                <?php echo $response; ?>
            </div>
        <?php endif; ?>

        <form action="" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="mb-3">
                <label for="student_name" class="form-label">Your Name</label>
                <input type="text" class="form-control" id="student_name" name="student_name" required>
            </div>
            <div class="mb-3">
                <label for="hostel" class="form-label">Hostel (Boys/Girls)</label>
                <input type="text" class="form-control" id="hostel" name="hostel" required>
            </div>
            <div class="mb-3">
                <label for="reason" class="form-label">Reason for Reading</label>
                <textarea class="form-control" id="reason" name="reason" required rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Your Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" name="submit_request" class="btn btn-primary">Send Request</button>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='http://localhost/Hpoint/Index/index2.php'">Go Back</button>
            </div>
        </form>

        <section class="response-section">
            <h3>Check Admin Response</h3>
            <div class="mb-3">
                <label for="student_email" class="form-label">Your Email</label>
                <input type="email" class="form-control" id="student_email" placeholder="Enter your email">
            </div>
            <button class="btn btn-info" onclick="fetchResponse()">Check Response</button>
            <div class="response-box" id="admin_response"></div>
        </section>
    </div>

    <script>
        async function fetchResponse() {
            const email = document.getElementById("student_email").value;
            const responseBox = document.getElementById("admin_response");
            if (!email) {
                responseBox.innerHTML = "Please enter your email.";
                responseBox.style.display = "block";
                return;
            }

            try {
                const res = await fetch(`view_reading_responses.php?email=${encodeURIComponent(email)}`);
                const data = await res.text();
                responseBox.innerHTML = data || "No response yet.";
                responseBox.style.display = "block";
            } catch (error) {
                responseBox.innerHTML = "Error fetching response: " + error.message;
                responseBox.style.display = "block";
            }
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>