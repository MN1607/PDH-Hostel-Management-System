<?php
require 'config.php'; // Database connection

$response = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name = $_POST['student_name'];
    $email = $_POST['email'];
    $issue_description = $_POST['issue_description'];
    $items_needed = $_POST['items_needed'];

    // Insert request into database
    $stmt = $conn->prepare("INSERT INTO maintenance_requests (secretary_name, email, issue_description, items_to_buy) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $student_name, $email, $issue_description, $items_needed);

    if ($stmt->execute()) {
        $response = "Request sent successfully!";
    } else {
        $response = "Error sending request: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Request</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #2C3E50, #4CA1AF);
            color: white;
            text-align: center;
            margin: 0;
            padding: 20px;
        }

        h2 {
            animation: fadeIn 1.5s ease-in-out;
        }

        .container {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            width: 50%;
            margin: auto;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            animation: slideIn 1s ease-in-out;
        }

        label {
            display: block;
            font-weight: bold;
            margin-top: 10px;
        }

        input, textarea {
            width: 90%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: none;
            font-size: 16px;
            transition: box-shadow 0.3s ease-in-out;
        }

        input:focus, textarea:focus {
            box-shadow: 0px 0px 8px rgba(255, 255, 255, 0.6);
            outline: none;
        }

        button {
            padding: 10px;
            border: none;
            background: linear-gradient(90deg, #ff8c00, #ff5e62);
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background 0.3s ease-in-out, transform 0.2s;
            margin-top: 15px;
        }

        button:hover {
            background: linear-gradient(90deg, #ff5e62, #ff8c00);
            transform: scale(1.05);
        }

        #admin_responses {
            background: rgba(0, 0, 0, 0.3);
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            font-weight: bold;
            color: #00ff00;
        }

        /* Style for Go Back button */
        .go-back-btn {
            background: linear-gradient(90deg, #3498db, #2980b9);
        }

        .go-back-btn:hover {
            background: linear-gradient(90deg, #2980b9, #3498db);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }
    </style>
</head>
<body>

    <h2>Hostel Maintenance Request</h2>

    <?php if ($response) echo "<p style='color: green; font-weight: bold;'>$response</p>"; ?>

    <div class="container">
        <form action="" method="post">
            <label>Student Name:</label>
            <input type="text" name="student_name" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Issue Description:</label>
            <textarea name="issue_description" required></textarea>

            <label>Items Needed:</label>
            <textarea name="items_needed"></textarea>

            <button type="submit">Send Request</button>
            <button type="button" class="go-back-btn" onclick="window.location.href='http://localhost/Hpoint/Index/index2.php'">Go Back</button>
        </form>

        <h3>View Admin Response</h3>
        <button onclick="fetchResponses()">View Response</button>
        <p id="admin_responses"></p>
    </div>

    <script>
        function fetchResponses() {
            let email = document.querySelector("input[name='email']").value;
            if (!email) {
                alert("Enter your email to view responses.");
                return;
            }

            fetch("view_maintenance_responses.php?email=" + email)
                .then(response => response.text())
                .then(data => {
                    document.getElementById("admin_responses").innerHTML = data ? data : "No response yet.";
                });
        }
    </script>

</body>
</html>