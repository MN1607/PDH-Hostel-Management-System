<?php
session_start();
$conn = new mysqli("localhost", "root", "", "student_admin_login");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL statement
    $stmt = $conn->prepare("SELECT password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_password);
        $stmt->fetch();

        // Compare plain text password
        if ($password === $db_password) {
            $_SESSION['admin'] = $username;
            header("Location: http://localhost/Hpoint/Admindashboard/admindashboard.html"); // Redirect to dashboard
            exit();
        } else {
            echo "<script>alert('Invalid password!');</script>";
        }
    } else {
        echo "<script>alert('No user found with this username!');</script>";
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
    <title>Admin Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url('pdh.jpg');
            background-size: cover;
            background-position: center;
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 20, 60, 0.7), rgba(0, 100, 200, 0.5));
            z-index: 1;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 420px;
            text-align: center;
            position: relative;
            z-index: 2;
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: floatIn 1s ease-out forwards;
        }
        @keyframes floatIn {
            0% { opacity: 0; transform: translateY(50px) scale(0.95); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }
        h2 {
            margin-bottom: 30px;
            color: #001433;
            font-size: 28px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
        }
        h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #0064c8, #00aaff);
            border-radius: 2px;
        }
        input {
            width: 100%;
            padding: 14px 16px;
            margin: 12px 0;
            border: 1px solid #ccd6e0;
            border-radius: 10px;
            background: #f9fbfd;
            color: #1a2a44;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        input::placeholder {
            color: #7a8ba3;
        }
        input:focus {
            outline: none;
            border-color: #0064c8;
            box-shadow: 0 0 12px rgba(0, 100, 200, 0.4);
            background: #ffffff;
        }
        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(90deg, #0064c8, #00aaff);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.4s ease;
            box-shadow: 0 5px 15px rgba(0, 100, 200, 0.4);
            position: relative;
            overflow: hidden;
        }
        button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s ease, height 0.6s ease;
        }
        button:hover::before {
            width: 300px;
            height: 300px;
        }
        button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 100, 200, 0.6);
        }
        .dashboard-btn {
            margin-top: 15px;
            display: inline-block;
            text-decoration: none;
            background: linear-gradient(90deg, #28a745, #4cd964);
            color: white;
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            text-transform: uppercase;
            transition: all 0.4s ease;
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }
        .dashboard-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(40, 167, 69, 0.6);
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Admin Login</h2>
    <form action="admin_login.php" method="post">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>