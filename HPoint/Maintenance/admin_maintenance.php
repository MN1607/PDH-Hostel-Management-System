<?php
require 'config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = $_POST['request_id'];
    $response = $_POST['response'];
    $sql = "INSERT INTO maintenance_responses (request_id, response) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $request_id, $response);
    $stmt->execute();
    header("Location: admin_maintenance.php"); // Redirect back to dashboard
    exit;
}
require 'config.php';

// Fetch all maintenance requests
$sql = "SELECT mr.*, r.response AS admin_response 
        FROM maintenance_requests mr 
        LEFT JOIN maintenance_responses r ON mr.id = r.request_id 
        ORDER BY mr.id DESC";
$result = $conn->query($sql);

// Check for query errors (optional debugging)
if (!$result) {
    die("Query failed: " . $conn->error);
}   

$total_requests = $result->num_rows;
$pending_responses = 0;
while ($row = $result->fetch_assoc()) {
    if (empty($row['admin_response'])) {
        $pending_responses++;
    }
    $requests[] = $row; // Store requests for later use
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Maintenance Requests</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #ffffff;
            padding: 20px;
            line-height: 1.6;
            overflow-x: hidden;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header Styling */
        .header {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            animation: fadeIn 0.8s ease-in-out;
        }

        .header h1 {
            font-size: 24px;
            color: #2a5298;
            margin: 0;
        }

        .stats {
            font-size: 16px;
            color: #555;
        }

        .stats span {
            font-weight: 700;
            color: #e74c3c;
        }

        /* Request Card Styling */
        .request-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: slideIn 0.6s ease-out forwards;
        }

        .request-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
        }

        .request-card p {
            margin: 10px 0;
            font-size: 16px;
        }

        .request-card strong {
            color: #ffd700;
        }

        /* Status Indicators */
        .status-pending,
        .status-replied {
            padding: 8px 12px;
            border-radius: 20px;
            font-weight: 500;
            display: inline-block;
            margin-top: 10px;
        }

        .status-pending {
            background: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
        }

        .status-replied {
            background: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
        }

        /* Form Styling */
        .response-form {
            margin-top: 15px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        textarea {
            width: 100%;
            min-height: 100px;
            padding: 12px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
            font-size: 16px;
            resize: vertical;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        textarea:focus {
            border-color: #ffd700;
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.4);
            outline: none;
        }

        button {
            padding: 12px;
            background: linear-gradient(90deg, #3498db, #2980b9);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background: linear-gradient(90deg, #2980b9, #1f618d);
            transform: scale(1.03);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .request-card {
                padding: 15px;
            }

            .header {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }

            .dashboard-container {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="header">
            <h1>Admin Dashboard - Maintenance Requests</h1>
            <div class="stats">
                Total Requests: <span><?php echo $total_requests; ?></span> | 
                Pending: <span><?php echo $pending_responses; ?></span>
            </div>
        </div>

        <?php if ($total_requests === 0): ?>
            <p style="text-align: center; font-size: 18px;">No maintenance requests found.</p>
        <?php else: ?>
            <?php foreach ($requests as $index => $row): ?>
                <div class="request-card" style="animation-delay: <?php echo $index * 0.1; ?>s;">
                    <p><strong>Student:</strong> <?php echo htmlspecialchars($row['secretary_name']); ?> (<?php echo htmlspecialchars($row['email']); ?>)</p>
                    <p><strong>Issue:</strong> <?php echo htmlspecialchars($row['issue_description']); ?></p>
                    <p><strong>Items Needed:</strong> <?php echo htmlspecialchars($row['items_to_buy']); ?></p>

                    <?php if (!empty($row['admin_response'])): ?>
                        <p class="status-replied">Response: <?php echo htmlspecialchars($row['admin_response']); ?></p>
                    <?php else: ?>
                        <p class="status-pending">Pending Response</p>
                        <form class="response-form" action="send_maintenance_response.php" method="post">
                            <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                            <textarea name="response" placeholder="Enter your response" required></textarea>
                            <button type="submit">Submit Response</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html> 