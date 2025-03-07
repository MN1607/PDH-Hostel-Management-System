<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "student_admin_login");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Student Addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_student'])) {
    $student_id = isset($_POST['student_id']) ? trim($_POST['student_id']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $year = isset($_POST['year']) ? intval($_POST['year']) : 0;

    if (empty($student_id) || empty($username) || empty($name) || $year == 0) {
        echo "<script>alert('All fields are required!');</script>";
    } else {
        $check_sql = "SELECT student_id FROM students WHERE student_id = ? OR username = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ss", $student_id, $username);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            echo "<script>alert('Student ID or Username already exists!');</script>";
        } else {
            $insert_sql = "INSERT INTO students (student_id, username, name, year) VALUES (?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("sssi", $student_id, $username, $name, $year);

            if ($insert_stmt->execute()) {
                echo "<script>alert('Student Added Successfully'); window.location.href='admin_attendance.php?year=$year';</script>";
            } else {
                echo "<script>alert('Error Adding Student: " . $conn->error . "');</script>";
            }
        }
    }
}

// Handle Attendance Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_attendance'])) {
    $date = isset($_POST['date']) ? $_POST['date'] : '';

    if (empty($date)) {
        echo "<script>alert('Please select a date!');</script>";
    } else {
        foreach ($_POST['attendance'] as $student_id => $status) {
            // Check if attendance already exists for the same date
            $check_attendance = "SELECT * FROM attendance WHERE student_id = ? AND date = ?";
            $check_stmt = $conn->prepare($check_attendance);
            $check_stmt->bind_param("ss", $student_id, $date);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows == 0) {
                // Insert new attendance record
                $insert_attendance = "INSERT INTO attendance (student_id, date, status) VALUES (?, ?, ?)";
                $insert_stmt = $conn->prepare($insert_attendance);
                $insert_stmt->bind_param("sss", $student_id, $date, $status);
                $insert_stmt->execute();
            }
        }
        $year_filter = isset($_GET['year']) ? intval($_GET['year']) : 1;
        echo "<script>alert('Attendance Recorded Successfully'); window.location.href='admin_attendance.php?year=$year_filter';</script>";
    }
}

// Default year filter
$year_filter = isset($_GET['year']) ? intval($_GET['year']) : 1;

// Fetch students based on the selected year
$sql = "SELECT student_id, username, name, year FROM students WHERE year = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $year_filter);
$stmt->execute();
$students = $stmt->get_result();

if (!$students) {
    die("Error fetching students: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Attendance Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        header {
            background-color: #1a252f;
            color: white;
            text-align: center;
            padding: 30px 0;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        header img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            opacity: 0.4;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 0;
            transition: opacity 0.5s ease;
        }
        header h2 {
            position: relative;
            z-index: 1;
            font-size: 2.8em;
            margin: 0;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.4);
            animation: fadeInDown 1s ease-out;
        }
        .container {
            width: 85%;
            margin: 40px auto;
            background-color: white;
            padding: 30px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 25px;
            animation: fadeIn 1s ease-out;
        }
        .btn-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 20px;
            animation: slideInLeft 1s ease-out;
        }
        .btn {
            padding: 12px 25px;
            font-size: 16px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        .btn.active {
            background-color: #1a252f;
        }
        .add-student-btn {
            padding: 12px 30px;
            font-size: 16px;
            background-color: #2ecc71;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.3s ease;
            align-self: center;
        }
        .add-student-btn i {
            margin-right: 5px;
        }
        .add-student-btn:hover {
            background-color: #27ae60;
            transform: translateY(-2px);
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            position: relative;
            animation: zoomIn 0.5s ease-out;
        }
        .modal-content h3 {
            margin-bottom: 20px;
            color: #1a252f;
            font-size: 1.5em;
            text-align: center;
        }
        .modal-content label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #1a252f;
        }
        .modal-content input,
        .modal-content select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 1em;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }
        .modal-content input:focus,
        .modal-content select:focus {
            border-color: #3498db;
            outline: none;
        }
        .modal-content button[type="submit"] {
            padding: 12px 25px;
            font-size: 16px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.3s ease;
            display: block;
            margin: 0 auto;
        }
        .modal-content button[type="submit"]:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        .close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 24px;
            color: #e74c3c;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        .close-btn:hover {
            color: #c0392b;
        }
        form {
            margin-top: 20px;
        }
        form label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #1a252f;
        }
        form input[type="date"] {
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            animation: slideInUp 1s ease-out;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            font-size: 1em;
        }
        table th {
            background-color: #1a252f;
            color: white;
            font-weight: 600;
        }
        table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        table tr:hover {
            background-color: #e9ecef;
            transition: background-color 0.3s ease;
        }
        table select {
            padding: 5px;
            font-size: 1em;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .submit-btn {
            padding: 12px 30px;
            font-size: 16px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.3s ease;
            margin-top: 20px;
            align-self: center;
        }
        .submit-btn i {
            margin-right: 5px;
        }
        .submit-btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        .go-back-btn {
            padding: 12px 30px;
            font-size: 16px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.3s ease;
            align-self: center;
            margin-top: 20px;
        }
        .go-back-btn i {
            margin-right: 5px;
        }
        .go-back-btn:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes zoomIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        @media (max-width: 768px) {
            .container {
                width: 90%;
                padding: 20px;
            }
            .btn-container {
                flex-direction: column;
                gap: 10px;
            }
            table th, table td {
                padding: 8px;
                font-size: 0.9em;
            }
            header h2 {
                font-size: 2.2em;
            }
            .modal-content {
                width: 95%;
                padding: 15px;
            }
        }
    </style>
</head>
<body>

<header>
    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Attendance Background">
    <h2>Admin Attendance Management</h2>
</header>

<div class="container">
    <div class="btn-container">
        <button class="btn <?= $year_filter == 1 ? 'active' : '' ?>" onclick="filterYear(1)">1st Year</button>
        <button class="btn <?= $year_filter == 2 ? 'active' : '' ?>" onclick="filterYear(2)">2nd Year</button>
        <button class="btn <?= $year_filter == 3 ? 'active' : '' ?>" onclick="filterYear(3)">3rd Year</button>
    </div>

    <button class="add-student-btn" onclick="showStudentForm()"><i class="fas fa-user-plus"></i> Add Student</button>

    <div class="modal" id="studentForm">
        <div class="modal-content">
            <span class="close-btn" onclick="hideStudentForm()">&times;</span>
            <h3>Add New Student</h3>
            <form method="POST">
                <label>Student ID:</label>
                <input type="text" name="student_id" required>
                <label>Username:</label>
                <input type="text" name="username" required>
                <label>Name:</label>
                <input type="text" name="name" required>
                <label>Year:</label>
                <select name="year" required>
                    <option value="1">1st Year</option>
                    <option value="2">2nd Year</option>
                    <option value="3">3rd Year</option>
                </select>
                <button type="submit" name="add_student">Save Student</button>
            </form>
        </div>
    </div>

    <form method="POST">
        <label><i class="fas fa-calendar-alt"></i> Select Date:</label>
        <input type="date" name="date" required>
        <table>
            <tr>
                <th>Student ID</th>
                <th>Username</th>
                <th>Name</th>
                <th>Year</th>
                <th>Status</th>
            </tr>
            <?php if ($students->num_rows > 0) { ?>
                <?php while ($row = $students->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['student_id']); ?></td>
                        <td><?= htmlspecialchars($row['username']); ?></td>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td><?= htmlspecialchars($row['year']); ?></td>
                        <td>
                            <select name="attendance[<?= htmlspecialchars($row['student_id']); ?>]">
                                <option value="Present">Present</option>
                                <option value="Absent">Absent</option>
                            </select>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: #e74c3c;">No students found for the selected year.</td>
                </tr>
            <?php } ?>
        </table>
        <button type="submit" class="submit-btn" name="submit_attendance"><i class="fas fa-check"></i> Submit Attendance</button>
    </form>

    <button class="go-back-btn" onclick="goBack()"><i class="fas fa-arrow-left"></i> Go Back</button>
</div>

<script>
    function filterYear(year) {
        window.location.href = 'admin_attendance.php?year=' + year;
    }

    function showStudentForm() {
        document.getElementById('studentForm').style.display = 'flex';
    }

    function hideStudentForm() {
        document.getElementById('studentForm').style.display = 'none';
    }

    function goBack() {
        window.location.href = 'http://localhost/Hpoint/Admindashboard/admindashboard.html';
    }
</script>

</body>
</html>