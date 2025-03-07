<?php
include 'config.php';
session_start();
$student_name = $_SESSION['username'] ?? 'John Doe';

$message_sent = false; // Variable to track message submission

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $student_name = $conn->real_escape_string($_POST['student_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $year = $conn->real_escape_string($_POST['year']);
    $department = $conn->real_escape_string($_POST['department']);
    $message = $conn->real_escape_string($_POST['message']);

    $sql = "INSERT INTO messages (student_name, email, year, department, message) 
            VALUES ('$student_name', '$email', '$year', '$department', '$message')";
    if ($conn->query($sql)) {
        $message_sent = true; // Set flag to show success message
    }

    // Prevent duplicate submission on refresh
    header("Location: student_messages.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Messages</title>
    <link rel="stylesheet" href="student_style.css"> <!-- External CSS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .success-message {
            display: none;
            background: #d4edda;
            color: #155724;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
        }
        .back-button {
            display: block;
            margin-bottom: 15px;
            padding: 10px 15px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-align: center;
        }
        .back-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="container">


    <h2>Send a Message to Admin</h2>

    <!-- Success message -->
    <?php if (isset($_GET['success'])): ?>
        <div class="success-message" id="successMessage">Message sent successfully!</div>
    <?php endif; ?>

    <form method="POST" class="message-form">
        <label for="student_name">Your Name:</label>
        <input type="text" name="student_name" value="<?= htmlspecialchars($student_name) ?>">

        <label for="email">Your Email:</label>
        <input type="email" name="email" id="email" required placeholder="Enter your email">

        <label for="year">Year:</label>
        <select name="year" required>
            <option value="1st Year">1st Year</option>
            <option value="2nd Year">2nd Year</option>
            <option value="3rd Year">3rd Year</option>
        </select>

        <label for="department">Department:</label>
        <select name="department" required>
            <option value="Computer Science">Computer Science</option>
            <option value="Information Technology">Information Technology</option>
            <option value="Electronics">Electronics</option>
            <option value="Commerce">Commerce</option>
        </select>

        <label for="message">Message:</label>
        <textarea name="message" required placeholder="Type your message..."></textarea>

        <button type="submit" name="send_message">Send Message</button>
    </form>

    <h2>Your Messages</h2>

    <label for="view_email">Enter Email to View Messages:</label>
    <input type="email" id="view_email" placeholder="Enter your email">
    <button id="view_messages">View Messages</button>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Year</th>
                <th>Department</th>
                <th>Message</th>
                <th>Status</th>
                <th>Admin Reply</th>
            </tr>
        </thead>
        <tbody id="messageTable">
            <tr><td colspan="6">Enter your email and click "View Messages" to see your messages.</td></tr>
            
        </tbody>
        
    </table>
    
</div>
<div class="go-back-btn-container">
    <button class="go-back-btn" onclick="window.location.href='http://localhost/Hpoint/Index/index2.php';">← Go Back</button>
</div>



<script>
$(document).ready(function() {
    // Show success message if exists
    if ($('#successMessage').length) {
        $('#successMessage').fadeIn().delay(3000).fadeOut(); // Hide after 3 sec
    }

    // Fetch messages when student enters email and clicks "View Messages"
    $('#view_messages').click(function() {
        var email = $('#view_email').val();
        if (email === '') {
            alert('Please enter your email!');
            return;
        }

        $.ajax({
            url: 'fetch_messages.php',
            method: 'POST',
            data: { email: email },
            success: function(data) {
                $('#messageTable').html(data);
            }
        });
    });
});

// Function to navigate back to index2.php
function goBack() {
    window.location.href = 'index2.php';
}
</script>



</body>
</html>
