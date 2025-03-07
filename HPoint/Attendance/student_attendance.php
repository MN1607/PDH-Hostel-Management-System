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

// Ensure student is logged in
if (!isset($_SESSION['student_id'])) {
    die("Unauthorized access. Please login first.");
}

$student_id = $_SESSION['student_id']; // Current logged-in student ID
$attendanceData = [];
$selected_student_id = $student_id; // Default to logged-in student
$student_name = "";
$selected_month = date("Y-m"); // Default to current month
$working_days = 0;
$present_days = 0;
$absent_days = 0;
$not_marked_days = 0;
$percentage = 0;
$showOverview = false;

// Fetch attendance if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['student_id']) && isset($_POST['month'])) {
        $selected_student_id = trim($_POST['student_id']);
        $selected_month = trim($_POST['month']);

        // Verify student ID and fetch name
        $stmt = $conn->prepare("SELECT name FROM students WHERE student_id = ?");
        $stmt->bind_param("s", $selected_student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $student_name = $row['name'];
        } else {
            echo "<script>alert('Invalid student ID. Please enter a valid ID.');</script>";
            $student_name = "Unknown";
        }
        $stmt->close();

        // Fetch attendance for the selected student and month
        $stmt = $conn->prepare("SELECT date, status FROM attendance WHERE student_id = ? AND DATE_FORMAT(date, '%Y-%m') = ?");
        $stmt->bind_param("ss", $selected_student_id, $selected_month);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $attendanceData[$row['date']] = $row['status'];
            $working_days++;
            if ($row['status'] == 'Present') $present_days++;
            elseif ($row['status'] == 'Absent') $absent_days++;
            else $not_marked_days++;
        }

        $percentage = $working_days > 0 ? round(($present_days / $working_days) * 100, 2) : 0;
        $showOverview = true;
    } elseif (isset($_POST['show_overview'])) {
        $showOverview = true; // Trigger overview display
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Overview</title>
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
        }
        .input-section {
            display: flex;
            gap: 20px;
            align-items: flex-end;
            flex-wrap: wrap;
            animation: slideInLeft 1s ease-out;
        }
        .input-group {
            flex: 1;
            min-width: 200px;
        }
        .input-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #1a252f;
        }
        .input-group input {
            width: 100%;
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }
        .input-group input:focus {
            border-color: #3498db;
            outline: none;
        }
        .overview-btn {
            padding: 12px 30px;
            font-size: 16px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.3s ease;
            align-self: flex-end;
        }
        .overview-btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        .overview-section {
            background-color: #ecf0f1;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            display: <?= $showOverview ? 'block' : 'none' ?>;
            animation: fadeInUp 1s ease-out;
        }
        .overview-section h3 {
            font-size: 1.5em;
            margin-bottom: 15px;
            color: #1a252f;
            animation: slideInRight 1s ease-out;
        }
        .overview-stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 15px;
            animation: zoomIn 1s ease-out;
        }
        .overview-stats div {
            font-weight: 500;
            color: #1a252f;
            padding: 10px 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .calendar-section {
            margin-top: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            animation: slideInUp 1s ease-out;
        }
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            background-color: #ecf0f1;
            padding: 10px;
            border-radius: 5px;
        }
        .calendar-header span {
            font-weight: 500;
            color: #1a252f;
        }
        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            text-align: center;
        }
        .calendar-day {
            padding: 10px;
            border-radius: 50%;
            font-weight: 500;
            transition: transform 0.3s ease, background-color 0.3s ease;
            cursor: default;
        }
        .calendar-day.holiday {
            background-color: #8e44ad;
            color: white;
        }
        .calendar-day.half-day {
            background-color: #e74c3c;
            color: white;
        }
        .calendar-day.present {
            background-color: #2ecc71;
            color: white;
        }
        .calendar-day.absent {
            background-color: #e67e22;
            color: white;
        }
        .calendar-day.not-marked {
            background-color: #95a5a6;
            color: white;
        }
        .calendar-day.empty {
            background-color: transparent;
            color: #7f8c8d;
        }
        .calendar-day:hover {
            transform: scale(1.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            animation: fadeIn 1s ease-out;
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
        .back-btn {
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
        .back-btn:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(20px); }
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
            .input-section {
                flex-direction: column;
                gap: 15px;
            }
            .input-group {
                min-width: 100%;
            }
            .overview-stats {
                flex-direction: column;
                gap: 10px;
            }
            .calendar-days {
                grid-template-columns: repeat(7, 1fr);
            }
            .calendar-day {
                padding: 8px;
                font-size: 0.9em;
            }
            table th, table td {
                padding: 8px;
                font-size: 0.9em;
            }
            header h2 {
                font-size: 2.2em;
            }
        }
    </style>
</head>
<body>

<header>
    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Attendance Background">
    <h2>Attendance Overview</h2>
</header>

<div class="container">
    <div class="input-section">
        <form method="POST" action="student_attendance.php">
            <div class="input-group">
                <label for="student_id">Enter Student ID:</label>
                <input type="text" name="student_id" value="<?= htmlspecialchars($selected_student_id) ?>" required>
            </div>
            <div class="input-group">
                <label for="month">Select Month:</label>
                <input type="month" name="month" value="<?= htmlspecialchars($selected_month) ?>" required>
            </div>
            <button type="submit" class="overview-btn" name="show_overview">Show Overview</button>
        </form>
    </div>

    <?php if ($showOverview): ?>
        <div class="overview-section">
            <h3>Attendance Summary for <?= htmlspecialchars($student_name) ?> (ID: <?= htmlspecialchars($selected_student_id) ?>)</h3>
            <div class="overview-stats">
                <div>Working Days: <?= $working_days ?></div>
                <div>Not Marked: <?= $not_marked_days ?></div>
                <div>Absent: <?= $absent_days ?></div>
                <div>Present: <?= $present_days ?></div>
                <div>Percentage: <?= $percentage ?>%</div>
            </div>
        </div>

        <div class="calendar-section">
            <div class="calendar-header">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="calendar-days">
                <div class="calendar-day empty">Su</div>
                <div class="calendar-day empty">Mo</div>
                <div class="calendar-day empty">Tu</div>
                <div class="calendar-day empty">We</div>
                <div class="calendar-day empty">Th</div>
                <div class="calendar-day empty">Fr</div>
                <div class="calendar-day empty">Sa</div>
                <?php
                $days_in_month = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($selected_month)), date('Y', strtotime($selected_month)));
                $first_day = date('w', strtotime($selected_month . '-01'));
                for ($i = 0; $i < $first_day; $i++) {
                    echo '<div class="calendar-day empty"></div>';
                }
                for ($day = 1; $day <= $days_in_month; $day++) {
                    $date = date('Y-m-d', strtotime($selected_month . '-' . $day));
                    $status = $attendanceData[$date] ?? 'not-marked';
                    $class = match ($status) {
                        'Holiday' => 'holiday',
                        'Half Day' => 'half-day',
                        'Present' => 'present',
                        'Absent' => 'absent',
                        default => 'not-marked'
                    };
                    echo "<div class='calendar-day $class'>$day</div>";
                }
                ?>
            </div>
        </div>

        <table>
            <tr>
                <th>Date</th>
                <th>Hours</th>
                <th>Status</th>
            </tr>
            <?php
            foreach ($attendanceData as $date => $status) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($date) . "</td>";
                echo "<td>?????</td>"; // Placeholder for hours
                echo "<td>" . htmlspecialchars($status) . "</td>";
                echo "</tr>";
            }
            if (empty($attendanceData) && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['show_overview'])) {
                echo "<tr><td colspan='3' style='text-align: center; color: #e74c3c;'>No attendance records found for this student.</td></tr>";
            }
            ?>
        </table>
    <?php endif; ?>
</div>

<button class="back-btn" onclick="window.location.href='http://localhost/Hpoint/Index/index2.php'">Back to Dashboard</button>

</body>
</html>