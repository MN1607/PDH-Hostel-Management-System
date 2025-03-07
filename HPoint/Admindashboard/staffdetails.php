<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'C:/xampp/htdocs/HPoint/Admindashboard/error.log');

// Database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "student_admin_login";
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die("Database connection failed. Please contact support.");
}

// Initialize $staff_data as an empty array
$staff_data = [];
$total_staff = 0;

// Insert new staff with prepared statement
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_staff'])) {
    $name = trim($_POST['name']);
    $role = trim($_POST['role']);
    $contact = trim($_POST['contact']);
    $phone = trim($_POST['phone']);

    // Client-side validation already handled, but add server-side check
    if (empty($name) || empty($role) || empty($contact) || empty($phone)) {
        echo "<script>alert('All fields are required!');</script>";
    } elseif (!filter_var($contact, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Please enter a valid email address!');</script>";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        echo "<script>alert('Phone number must be exactly 10 digits!');</script>";
    } else {
        $check_sql = "SELECT id FROM staff WHERE contact = ? OR phone = ?";
        $stmt_check = $conn->prepare($check_sql);
        $stmt_check->bind_param("ss", $contact, $phone);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            echo "<script>alert('Email or phone already exists! Please use a different one.');</script>";
        } else {
            $sql = "INSERT INTO staff (name, role, contact, phone) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $name, $role, $contact, $phone);

            if ($stmt->execute()) {
                echo "<script>alert('Staff added successfully!'); window.location.href='staffdetails.php';</script>";
            } else {
                error_log("Error adding staff: " . $conn->error);
                echo "<script>alert('Error adding staff. Please try again or contact support.');</script>";
            }
            $stmt->close();
        }
        $stmt_check->close();
    }
}

// Update staff with prepared statement
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_staff'])) {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $role = trim($_POST['role']);
    $contact = trim($_POST['contact']);
    $phone = trim($_POST['phone']);

    $sql = "UPDATE staff SET name=?, role=?, contact=?, phone=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $name, $role, $contact, $phone, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Staff updated successfully!'); window.location.href='staffdetails.php';</script>";
    } else {
        error_log("Error updating staff: " . $conn->error);
        echo "<script>alert('Error updating staff. Please try again or contact support.');</script>";
    }
    $stmt->close();
}

// Delete staff with prepared statement
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_staff'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM staff WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Staff deleted successfully!'); window.location.href='staffdetails.php';</script>";
    } else {
        error_log("Error deleting staff: " . $conn->error);
        echo "<script>alert('Error deleting staff. Please try again or contact support.');</script>";
    }
    $stmt->close();
}

// Fetch staff data
$result = $conn->query("SELECT * FROM staff");
if ($result) {
    $total_staff = $result->num_rows;
    while ($row = $result->fetch_assoc()) {
        $staff_data[] = $row;
    }
} else {
    error_log("Error fetching staff data: " . $conn->error);
    $staff_data = [];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Details - Hostel Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1a2a6c, #b21f1f, #fdbb2d);
            color: #333;
            overflow-x: hidden;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://www.transparenttextures.com/patterns/stardust.png');
            opacity: 0.1;
            z-index: -1;
        }

        /* Header Styling */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 15px 30px;
            border-radius: 12px 12px 0 0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin: 20px auto 0;
            max-width: 1100px;
            animation: slideDown 0.8s ease;
        }

        @keyframes slideDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .header .profile-card {
            display: flex;
            align-items: center;
        }

        .header .logo img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
            border: 2px solid #fdbb2d;
        }

        .header .admin-info {
            font-size: 18px;
            font-weight: 600;
            color: #1a2a6c;
        }

        .header .logout-btn {
            padding: 10px 20px;
            background: linear-gradient(90deg, #ff6f61, #ff8a65);
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .header .logout-btn:hover {
            background: linear-gradient(90deg, #e65a50, #e6735a);
            transform: translateY(-2px);
        }

        /* Main Content Styling */
        main {
            padding: 30px;
            animation: fadeIn 1s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        .status-dashboard {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            display: flex;
            justify-content: center; /* Center the single card */
            animation: slideUp 0.8s ease forwards;
            max-width: 1100px;
            margin-left: auto;
            margin-right: auto;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .status-card {
            text-align: center;
        }

        .status-card h3 {
            color: #1a2a6c;
            font-size: 18px;
            margin: 0 0 10px;
        }

        .status-card p {
            font-size: 24px;
            color: #fdbb2d;
            font-weight: 600;
            margin: 0;
        }

        .search-container {
            text-align: right;
            margin-bottom: 20px;
            max-width: 1100px;
            margin-left: auto;
            margin-right: auto;
            position: relative;
        }

        .search-bar {
            padding: 12px 50px 12px 20px;
            width: 70%; /* Wider search bar */
            border: 2px solid #ddd;
            border-radius: 25px;
            font-size: 15px;
            background: #f8f9fa;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .search-bar:focus {
            border-color: #1a2a6c;
            box-shadow: 0 0 10px rgba(26, 42, 108, 0.3);
            outline: none;
        }

        .search-icon {
            position: absolute;
            top: 50%;
            right: 25px;
            transform: translateY(-50%);
            color: #1a2a6c;
            font-size: 16px;
        }

        .add-btn-container {
            text-align: right;
            margin-bottom: 20px;
            max-width: 1100px;
            margin-left: auto;
            margin-right: auto;
        }

        .add-btn {
            background: linear-gradient(90deg, #1a2a6c, #2a5298);
            color: white;
            border: none;
            padding: 12px 25px;
            cursor: pointer;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 500;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: bounce 1.5s ease infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .add-btn:hover {
            transform: scale(1.05) translateY(-5px);
            box-shadow: 0 5px 15px rgba(26, 42, 108, 0.3);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            animation: fadeInTable 1s ease forwards;
            max-width: 1100px;
            margin-left: auto;
            margin-right: auto;
        }

        @keyframes fadeInTable {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        th, td {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        th {
            background: linear-gradient(90deg, #1a2a6c, #2a5298);
            color: white;
            font-size: 16px;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background: #f8f9fa;
        }

        tr:hover {
            background: #e9ecef;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        td input[type="text"], td input[type="email"] {
            width: 90%;
            padding: 8px;
            border: 2px solid #ddd;
            border-radius: 4px;
            text-align: center;
            transition: border-color 0.3s ease;
        }

        td input[type="text"]:focus, td input[type="email"]:focus {
            border-color: #1a2a6c;
            outline: none;
        }

        .edit-btn, .delete-btn {
            padding: 8px 12px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
            transition: transform 0.3s ease, color 0.3s ease;
            background: none;
        }

        .edit-btn {
            color: #28a745;
        }

        .delete-btn {
            color: #dc3545;
        }

        .edit-btn:hover {
            color: #218838;
            transform: scale(1.1);
        }

        .delete-btn:hover {
            color: #c82333;
            transform: scale(1.1);
        }

        .add-staff-form {
            display: none;
            padding: 25px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            animation: slideUp 0.8s ease forwards;
            max-width: 1100px;
            margin-left: auto;
            margin-right: auto;
        }

        .add-staff-form h2 {
            text-align: center;
            color: #1a2a6c;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-group input:focus {
            border-color: #1a2a6c;
            box-shadow: 0 0 10px rgba(26, 42, 108, 0.3);
            outline: none;
        }

        .form-group button {
            width: 100%;
            background: linear-gradient(90deg, #1a2a6c, #2a5298);
            color: white;
            padding: 12px;
            border: none;
            cursor: pointer;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 500;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .form-group button:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(26, 42, 108, 0.3);
        }

        #exportBtn {
            padding: 12px 30px;
            background: linear-gradient(90deg, #1a2a6c, #2a5298);
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-top: 20px;
            display: none;
            max-width: 1100px;
            margin-left: auto;
            margin-right: auto;
        }

        #exportBtn:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(26, 42, 108, 0.3);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="profile-card">
            <div class="logo">
                <img src="admin.jpg" alt="Admin Profile">
            </div>
            <div class="admin-info">Welcome, Admin</div>
        </div>
        <button class="logout-btn" id="logoutBtn" href="">GO BACK</button>
    </div>

    <!-- Main Content -->
    <main>
        <!-- Status Dashboard (Only Total Staff) -->
        <div class="status-dashboard">
            <div class="status-card">
                <h3>Total Staff</h3>
                <p><?php echo $total_staff; ?></p>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="search-container">
            <input type="text" class="search-bar" id="searchBar" placeholder="Search staff..." onkeyup="searchTable()">
            <span class="search-icon"><i class="fas fa-search"></i></span>
        </div>

        <!-- Add Staff Button (Above Table) -->
        <div class="add-btn-container">
            <button class="add-btn" onclick="toggleAddForm()">+ Add New Staff</button>
        </div>

        <!-- Add Staff Form -->
        <div class="add-staff-form" id="addStaffForm">
            <h2>Add New Staff</h2>
            <form method="POST" onsubmit="return validateForm()">
                <div class="form-group">
                    <input type="text" name="name" id="name" placeholder="Full Name" required>
                </div>
                <div class="form-group">
                    <input type="text" name="role" id="role" placeholder="Role" required>
                </div>
                <div class="form-group">
                    <input type="email" name="contact" id="contact" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="text" name="phone" id="phone" placeholder="Phone Number" required pattern="[0-9]{10}" title="10-digit phone number">
                </div>
                <div class="form-group">
                    <button type="submit" name="add_staff">Add Staff</button>
                </div>
            </form>
        </div>

        <!-- Staff Table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Contact</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($staff_data)): ?>
                    <?php foreach ($staff_data as $staff): ?>
                        <tr>
                            <form method="POST">
                                <td><?php echo htmlspecialchars($staff['id']); ?></td>
                                <td><input type="text" name="name" value="<?php echo htmlspecialchars($staff['name']); ?>" required></td>
                                <td><input type="text" name="role" value="<?php echo htmlspecialchars($staff['role']); ?>" required></td>
                                <td><input type="email" name="contact" value="<?php echo htmlspecialchars($staff['contact']); ?>" required></td>
                                <td><input type="text" name="phone" value="<?php echo htmlspecialchars($staff['phone']); ?>" required pattern="[0-9]{10}" title="10-digit phone number"></td>
                                <td>
                                    <input type="hidden" name="id" value="<?php echo $staff['id']; ?>">
                                    <button type="submit" name="update_staff" class="edit-btn" title="Update">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="submit" name="delete_staff" class="delete-btn" title="Delete" onclick="return confirm('Are you sure you want to delete this staff?');">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #dc3545;">No staff data found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Export Button -->
        <button id="exportBtn" onclick="exportTable()">Export to CSV</button>
    </main>

    <script>
        // Toggle Add Staff Form
        function toggleAddForm() {
            var form = document.getElementById("addStaffForm");
            form.style.display = (form.style.display === "none" || form.style.display === "") ? "block" : "none";
        }

        // Client-side Form Validation
        function validateForm() {
            var name = document.getElementById("name").value.trim();
            var role = document.getElementById("role").value.trim();
            var contact = document.getElementById("contact").value.trim();
            var phone = document.getElementById("phone").value.trim();

            if (!name || !role || !contact || !phone) {
                alert("All fields are required!");
                return false;
            }
            // Allow letters, spaces, hyphens, and apostrophes for names
            if (!/^[a-zA-Z\s'-]+$/.test(name)) {
                alert("Name should contain only letters, spaces, hyphens, or apostrophes!");
                return false;
            }
            if (!/^[a-zA-Z\s'-]+$/.test(role)) {
                alert("Role should contain only letters, spaces, hyphens, or apostrophes!");
                return false;
            }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(contact)) {
                alert("Please enter a valid email address!");
                return false;
            }
            if (!/^[0-9]{10}$/.test(phone)) {
                alert("Phone number must be exactly 10 digits!");
                return false;
            }
            return true;
        }

        // Logout Functionality
        document.getElementById("logoutBtn").addEventListener("click", function () {
            if (confirm("Are you sure you want to GO BACK?")) {
                window.location.href = "http://localhost/Hpoint/Admindashboard/admindashboard.html";
            }
        });

        // Search Table Functionality (Improved to handle input fields)
        function searchTable() {
            var input = document.getElementById("searchBar").value.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
            var table = document.querySelector("table tbody");
            var rows = table.getElementsByTagName("tr");

            for (var i = 0; i < rows.length; i++) {
                var cells = rows[i].getElementsByTagName("td");
                var match = false;
                for (var j = 0; j < cells.length - 1; j++) {
                    var cellContent = cells[j].querySelector("input") ? 
                        cells[j].querySelector("input").value.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase() : 
                        cells[j].textContent.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
                    if (cellContent.indexOf(input) > -1) {
                        match = true;
                        break;
                    }
                }
                rows[i].style.display = match ? "" : "none";
            }
            document.getElementById("exportBtn").style.display = input ? "block" : "none";
        }

        // Export Table to CSV with Confirmation
        function exportTable() {
            if (confirm("Are you sure you want to export staff details to CSV?")) {
                var csv = [];
                var rows = document.querySelectorAll("table tr");

                for (var i = 0; i < rows.length; i++) {
                    var row = [], cols = rows[i].querySelectorAll("td, th");
                    for (var j = 0; j < cols.length; j++) {
                        var cellContent = cols[j].querySelector("input") ? 
                            cols[j].querySelector("input").value : cols[j].textContent;
                        row.push(cellContent.replace(/(\r\n|\n|\r)/gm, ""));
                    }
                    csv.push(row.join(","));
                }

                var csvFile = new Blob([csv.join("\n")], { type: "text/csv" });
                var downloadLink = document.createElement("a");
                downloadLink.download = "staff_details_" + new Date().toISOString().slice(0, 10) + ".csv";
                downloadLink.href = window.URL.createObjectURL(csvFile);
                downloadLink.style.display = "none";
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
            }
        }
    </script>
</body>
</html>