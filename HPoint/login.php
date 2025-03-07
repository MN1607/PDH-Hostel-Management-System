<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "student_admin_login";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = ""; // Variable to store error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Fetch user from database
    $stmt = $conn->prepare("SELECT student_id, username, password FROM students WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify password
        if ($password === $row['password']) { 
            $_SESSION['student_id'] = $row['student_id'];
            $_SESSION['username'] = $row['username'];

            header("Location: Index/index2.php"); // Redirect to main page
            exit();
        } else {
            $error = "Invalid username or password!";
        }
    } else {
        $error = "Invalid username or password!";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login - PDH Hostel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            background: url('hostel.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
            position: relative;
        }
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 20, 60, 0.6); /* Darker overlay for professionalism */
            z-index: 1;
        }
        .login-container {
            background: #ffffff; /* White background for a clean, professional look */
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 360px;
            text-align: center;
            position: relative;
            z-index: 2;
            animation: fadeUp 0.7s ease-out forwards;
        }
        @keyframes fadeUp {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        h2 {
            margin-bottom: 25px;
            color: #1a2a44; /* Dark navy for a professional tone */
            font-size: 24px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
        }
        h2::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: #007bff; /* Blue accent for professionalism */
            border-radius: 2px;
        }
        input {
            width: 100%;
            padding: 12px 16px;
            margin: 12px 0;
            border: 1px solid #d1d9e0; /* Light gray border */
            border-radius: 8px;
            background: #fafbfc; /* Soft off-white background */
            color: #1a2a44;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        input::placeholder {
            color: #7a8ba3; /* Muted blue-gray for placeholders */
        }
        input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.2);
            background: #ffffff;
        }
        button {
            width: 100%;
            padding: 14px;
            background: #007bff; /* Solid blue for a professional feel */
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }
        button:hover {
            background: #0056b3; /* Darker blue on hover */
            box-shadow: 0 6px 16px rgba(0, 123, 255, 0.4);
            transform: translateY(-2px);
        }
        .error {
            color: #d32f2f; /* Professional red for errors */
            margin-top: 15px;
            font-size: 14px;
            font-weight: 600;
            transition: opacity 0.3s ease;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Student Login</h2>
    <?php if (!empty($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="login.php">
        <input type="text" name="username" placeholder="Enter Username" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>