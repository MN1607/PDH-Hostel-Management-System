<?php
// Database connection configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "student_admin_login";

// Establish database connection
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize success/error messages
$success_message = "";
$error_message = "";

// Handle form submission to add a new student
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_student'])) {
    $student_id = $_POST['student_id'];
    $name = $_POST['name'];
    $department = $_POST['department'];
    $year = $_POST['year'];
    $batch = $_POST['batch'];

    // Check if student_id already exists
    $check_sql = "SELECT student_id FROM Students_details WHERE student_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $student_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $error_message = "Error: Student ID '$student_id' already exists. Please use a unique ID (e.g., B22102).";
    } else {
        $sql = "INSERT INTO Students_details (student_id, name, department, year, batch) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $student_id, $name, $department, $year, $batch);

        if ($stmt->execute()) {
            $success_message = "Student added successfully!";
        } else {
            $error_message = "Error adding student: " . $conn->error;
        }
        $stmt->close();
    }
    $check_stmt->close();
}

// Handle edit student submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_student'])) {
    $student_id = $_POST['edit_student_id'];
    $name = $_POST['edit_name'];
    $department = $_POST['edit_department'];
    $year = $_POST['edit_year'];
    $batch = $_POST['edit_batch'];

    $sql = "UPDATE Students_details SET name = ?, department = ?, year = ?, batch = ? WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $department, $year, $batch, $student_id);

    if ($stmt->execute()) {
        $success_message = "Student updated successfully!";
    } else {
        $error_message = "Error updating student: " . $conn->error;
    }
    $stmt->close();
}

// Handle delete student
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_student'])) {
    $student_id = $_POST['delete_student_id'];

    $sql = "DELETE FROM Students_details WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_id);

    if ($stmt->execute()) {
        $success_message = "Student deleted successfully!";
    } else {
        $error_message = "Error deleting student: " . $conn->error;
    }
    $stmt->close();
}

// Handle AJAX request for fetching students (previously in get_students.php)
if (isset($_GET['ajax']) && $_GET['ajax'] === 'fetch_students') {
    header('Content-Type: application/json');

    $year = $_GET['year'] ?? '';
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $search = $_GET['search'] ?? '';
    $sort_by = $_GET['sort_by'] ?? 'student_id';
    $sort_order = $_GET['sort_order'] ?? 'ASC';
    $records_per_page = 10;
    $offset = ($page - 1) * $records_per_page;

    if (empty($year)) {
        echo json_encode(['error' => 'Year parameter is missing']);
        exit;
    }

    // Validate sort parameters
    $valid_sort_columns = ['student_id', 'name', 'department', 'year', 'batch'];
    $sort_by = in_array($sort_by, $valid_sort_columns) ? $sort_by : 'student_id';
    $sort_order = strtoupper($sort_order) === 'DESC' ? 'DESC' : 'ASC';

    // Count total records for pagination
    $count_sql = "SELECT COUNT(*) as total FROM Students_details WHERE year = ?";
    $search_condition = $search ? " AND (student_id LIKE ? OR name LIKE ? OR department LIKE ?)" : "";
    $count_sql .= $search_condition;
    $count_stmt = $conn->prepare($count_sql);
    if ($search) {
        $search_param = "%$search%";
        $count_stmt->bind_param("ssss", $year, $search_param, $search_param, $search_param);
    } else {
        $count_stmt->bind_param("s", $year);
    }
    $count_stmt->execute();
    $total_records = $count_stmt->get_result()->fetch_assoc()['total'];
    $total_pages = ceil($total_records / $records_per_page);
    $count_stmt->close();

    // Fetch students with pagination, search, and sorting
    $sql = "SELECT * FROM Students_details WHERE year = ? $search_condition ORDER BY $sort_by $sort_order LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    if ($search) {
        $stmt->bind_param("ssssii", $year, $search_param, $search_param, $search_param, $records_per_page, $offset);
    } else {
        $stmt->bind_param("sii", $year, $records_per_page, $offset);
    }

    if (!$stmt->execute()) {
        echo json_encode(['error' => 'Query execution failed: ' . $conn->error]);
        exit;
    }

    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);

    $response = [
        'data' => $data,
        'total_pages' => $total_pages,
        'total_records' => $total_records
    ];
    echo json_encode($response);

    $stmt->close();
    $conn->close();
    exit;
}

// Define years for student modules
$years = ['1st Year', '2nd Year', '3rd Year'];
$students_by_year = [];
$records_per_page = 10;

// Initialize data structure for each year
foreach ($years as $year) {
    $year_key = str_replace(' ', '_', $year);
    $students_by_year[$year] = [
        'data' => [],
        'total_pages' => 1,
        'current_page' => 1,
        'search' => '',
        'total_records' => 0
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students Details - Hostel Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* General Body Styling */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #333;
            overflow-x: hidden;
        }

        /* Main Container */
        .container {
            max-width: 1400px;
            margin: 40px auto;
            padding: 30px;
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Hero Header Styling */
        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h2 {
            font-size: 36px;
            color: #1e3c72;
            margin: 0;
            position: relative;
            display: inline-block;
            animation: slideIn 1s ease-in-out;
        }

        .header h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 50%;
            height: 4px;
            background: linear-gradient(to right, #1e3c72, #2a5298);
            border-radius: 2px;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Module Styling for Year Sections */
        .module {
            margin-bottom: 40px;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .module:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .module-header {
            padding: 20px 30px;
            background: linear-gradient(90deg, #1e3c72, #2a5298);
            color: white;
            font-size: 20px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background 0.3s ease;
        }

        .module-header:hover {
            background: linear-gradient(90deg, #17315f, #234680);
        }

        .module-header i {
            transition: transform 0.3s ease;
        }

        .module-header.collapsed i {
            transform: rotate(180deg);
        }

        .module-content {
            padding: 25px;
            display: none;
            animation: slideDown 0.5s ease forwards;
        }

        .module-content.active {
            display: block;
        }

        @keyframes slideDown {
            from { max-height: 0; opacity: 0; }
            to { max-height: 600px; opacity: 1; }
        }

        /* Total Students Count Styling */
        .total-count {
            margin-bottom: 15px;
            font-size: 16px;
            color: #1e3c72;
            font-weight: 500;
        }

        /* Search Bar Styling */
        .search-bar {
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            position: relative;
        }

        .search-bar form {
            position: relative;
            width: 350px;
        }

        .search-bar input {
            padding: 12px 40px 12px 15px;
            width: 100%;
            border: 2px solid #ddd;
            border-radius: 25px;
            font-size: 15px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            background: #f8f9fa;
        }

        .search-bar input:focus {
            border-color: #1e3c72;
            box-shadow: 0 0 10px rgba(30, 60, 114, 0.3);
            outline: none;
        }

        .search-bar .search-icon {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            color: #1e3c72;
            font-size: 16px;
        }

        .search-bar button {
            padding: 12px 25px;
            background: linear-gradient(90deg, #1e3c72, #2a5298);
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .search-bar button:hover {
            background: linear-gradient(90deg, #17315f, #234680);
            transform: translateY(-2px);
        }

        .search-bar .clear-btn {
            padding: 12px 25px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .search-bar .clear-btn:hover {
            background: #c82333;
            transform: translateY(-2px);
        }

        /* Table Styling */
        .table-container {
            max-height: 450px;
            overflow-y: auto;
            position: relative;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            background: #fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: linear-gradient(90deg, #1e3c72, #2a5298);
            color: white;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        th .sort-btn {
            color: white;
            margin-left: 8px;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        th .sort-btn:hover {
            color: #f0f0f0;
        }

        tr {
            transition: background 0.3s ease, transform 0.3s ease;
        }

        tr:nth-child(even) {
            background: #f8f9fa;
        }

        tr:hover {
            background: #e9ecef;
            transform: translateY(-2px);
        }

        .action-btn {
            color: #1e3c72;
            margin-right: 15px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            transition: color 0.3s ease, transform 0.3s ease;
            position: relative;
        }

        .action-btn:hover {
            color: #2a5298;
            transform: scale(1.2);
        }

        .action-btn::after {
            content: attr(title);
            position: absolute;
            bottom: -25px;
            left: 50%;
            transform: translateX(-50%);
            background: #2c3e50;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .action-btn:hover::after {
            opacity: 1;
            visibility: visible;
        }

        /* Pagination Styling */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 25px;
            gap: 12px;
        }

        .pagination button {
            padding: 10px 16px;
            background: #f8f9fa;
            color: #2c3e50;
            text-decoration: none;
            border-radius: 50px;
            font-size: 14px;
            transition: background 0.3s ease, color 0.3s ease, transform 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: none;
            cursor: pointer;
        }

        .pagination button.active, .pagination button:hover {
            background: linear-gradient(90deg, #1e3c72, #2a5298);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .pagination .loading {
            padding: 10px 16px;
            background: #e9ecef;
            color: #2c3e50;
            border-radius: 50px;
            font-size: 14px;
        }

        /* Form Styling for Add/Edit Student */
        .add-student-form, .edit-student-form {
            padding: 30px;
            background: #fff;
            border-radius: 12px;
            margin-bottom: 40px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            animation: slideUp 0.8s ease;
            position: relative;
            overflow: hidden;
        }

        .add-student-form::before, .edit-student-form::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #1e3c72, #2a5298);
        }

        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .add-student-form h3, .edit-student-form h3 {
            margin: 0 0 20px;
            color: #1e3c72;
            font-size: 22px;
            font-weight: 600;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .form-row input {
            flex: 1;
            min-width: 220px;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            background: #f8f9fa;
        }

        .form-row input:focus {
            border-color: #1e3c72;
            box-shadow: 0 0 10px rgba(30, 60, 114, 0.3);
            outline: none;
        }

        .add-student-form button, .edit-student-form button {
            padding: 12px 30px;
            background: linear-gradient(90deg, #1e3c72, #2a5298);
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            transition: background 0.3s ease, transform 0.3s ease;
            margin-top: 10px;
        }

        .add-student-form button:hover, .edit-student-form button:hover {
            background: linear-gradient(90deg, #17315f, #234680);
            transform: translateY(-2px);
        }

        /* Back Button Styling */
        .back-btn {
            display: inline-flex;
            align-items: center;
            margin-bottom: 30px;
            padding: 12px 25px;
            background: linear-gradient(90deg, #ff6f61, #ff8a65);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-size: 15px;
            font-weight: 500;
            transition: background 0.3s ease, transform 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .back-btn i {
            margin-right: 8px;
        }

        .back-btn:hover {
            background: linear-gradient(90deg, #e65a50, #e6735a);
            transform: translateY(-2px);
        }

        /* Success/Error Message Styling */
        .message {
            padding: 15px;
            margin-bottom: 30px;
            border-radius: 8px;
            font-size: 15px;
            animation: fadeIn 0.5s ease;
        }

        .success {
            background: #d4edda;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
        }

        /* Modal Styling for Edit Student */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease;
        }

        .modal-content {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 550px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            animation: slideUpModal 0.5s ease;
            position: relative;
        }

        @keyframes slideUpModal {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .close-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
            color: #1e3c72;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .close-btn:hover {
            color: #2a5298;
            transform: rotate(90deg);
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Back to Dashboard Button -->
        <a href="http://localhost/Hpoint/Admindashboard/admindashboard.html" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>

        <!-- Hero Header -->
        <div class="header">
            <h2>Students Details</h2>
        </div>

        <!-- Display Success/Error Message -->
        <?php if ($success_message) { ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php } elseif ($error_message) { ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php } ?>

        <!-- Add New Student Form -->
        <div class="add-student-form">
            <h3>Add New Student</h3>
            <form action="" method="POST">
                <div class="form-row">
                    <input type="text" name="student_id" placeholder="Student ID (e.g., B22102)" required>
                    <input type="text" name="name" placeholder="Name" required>
                    <input type="text" name="department" placeholder="Department (e.g., CS)" required>
                    <input type="text" name="year" placeholder="Year (e.g., 3rd Year)" required>
                    <input type="text" name="batch" placeholder="Batch (e.g., 2022-2025)" required>
                </div>
                <button type="submit" name="add_student">Add Student</button>
            </form>
        </div>

        <!-- Year Modules for 1st, 2nd, and 3rd Year Students -->
        <?php foreach ($years as $year) { 
            $year_key = str_replace(' ', '_', $year);
        ?>
            <div class="module">
                <div class="module-header" onclick="toggleModule(this)">
                    <span><?php echo $year; ?> Students</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="module-content active">
                    <!-- Total Students Count -->
                    <div class="total-count" id="total_count_<?php echo $year_key; ?>">Total Students: 0</div>

                    <!-- Search Bar -->
                    <div class="search-bar">
                        <form onsubmit="searchStudents('<?php echo $year_key; ?>', '<?php echo $year; ?>'); return false;">
                            <input type="text" id="search_<?php echo $year_key; ?>" placeholder="Search by ID, Name, or Department">
                            <span class="search-icon"><i class="fas fa-search"></i></span>
                            <button type="submit">Search</button>
                            <button type="button" class="clear-btn" onclick="clearSearch('<?php echo $year_key; ?>', '<?php echo $year; ?>')">Clear</button>
                        </form>
                    </div>

                    <!-- Students Table -->
                    <div class="table-container">
                        <table id="table_<?php echo $year_key; ?>">
                            <thead>
                                <tr>
                                    <th>
                                        Student ID
                                        <span class="sort-btn" onclick="sortStudents('<?php echo $year_key; ?>', '<?php echo $year; ?>', 'student_id')">
                                            <i class="fas fa-sort"></i>
                                        </span>
                                    </th>
                                    <th>
                                        Name
                                        <span class="sort-btn" onclick="sortStudents('<?php echo $year_key; ?>', '<?php echo $year; ?>', 'name')">
                                            <i class="fas fa-sort"></i>
                                        </span>
                                    </th>
                                    <th>
                                        Department
                                        <span class="sort-btn" onclick="sortStudents('<?php echo $year_key; ?>', '<?php echo $year; ?>', 'department')">
                                            <i class="fas fa-sort"></i>
                                        </span>
                                    </th>
                                    <th>Year</th>
                                    <th>Batch</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_<?php echo $year_key; ?>">
                                <tr><td colspan="6">Loading data...</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="pagination" id="pagination_<?php echo $year_key; ?>">
                        <button class="loading">Loading...</button>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <!-- Edit Student Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">×</span>
            <div class="edit-student-form">
                <h3>Edit Student</h3>
                <form action="" method="POST">
                    <input type="hidden" name="edit_student_id" id="edit_student_id">
                    <div class="form-row">
                        <input type="text" name="edit_name" id="edit_name" placeholder="Name" required>
                        <input type="text" name="edit_department" id="edit_department" placeholder="Department" required>
                        <input type="text" name="edit_year" id="edit_year" placeholder="Year (e.g., 3rd Year)" required>
                        <input type="text" name="edit_batch" id="edit_batch" placeholder="Batch (e.g., 2022-2025)" required>
                    </div>
                    <button type="submit" name="edit_student">Update Student</button>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript Section -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Edit Modal Functionality
        const editModal = document.getElementById('editModal');
        const closeBtn = document.querySelector('.close-btn');
        const editBtns = document.querySelectorAll('.edit-btn');

        editBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const studentId = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const dept = this.getAttribute('data-dept');
                const year = this.getAttribute('data-year');
                const batch = this.getAttribute('data-batch');

                document.getElementById('edit_student_id').value = studentId;
                document.getElementById('edit_name').value = name;
                document.getElementById('edit_department').value = dept;
                document.getElementById('edit_year').value = year;
                document.getElementById('edit_batch').value = batch;

                editModal.style.display = 'flex';
            });
        });

        closeBtn.addEventListener('click', function() {
            editModal.style.display = 'none';
        });

        window.addEventListener('click', function(event) {
            if (event.target === editModal) {
                editModal.style.display = 'none';
            }
        });

        // Module Toggle Functionality
        function toggleModule(header) {
            const content = header.nextElementSibling;
            const icon = header.querySelector('i');
            content.classList.toggle('active');
            header.classList.toggle('collapsed');
        }

        // Initially open all modules
        document.querySelectorAll('.module-header').forEach(header => {
            header.click();
            header.click();
        });

        // Track sort states for each year module
        let sortStates = {
            <?php foreach ($years as $year) {
                $year_key = str_replace(' ', '_', $year);
                echo "'$year_key': { column: 'student_id', order: 'ASC' },";
            } ?>
        };

        // Load students for a specific year via AJAX
        function loadPage(yearKey, year, page, searchTerm = '', sortBy = 'student_id', sortOrder = 'ASC') {
            const tbody = document.getElementById(`tbody_${yearKey}`);
            const pagination = document.getElementById(`pagination_${yearKey}`);
            const totalCount = document.getElementById(`total_count_${yearKey}`);
            tbody.innerHTML = '<tr><td colspan="6">Loading data...</td></tr>';
            pagination.innerHTML = '<button class="loading">Loading...</button>';

            $.ajax({
                url: '?ajax=fetch_students',
                type: 'GET',
                data: { year: year, page: page, search: searchTerm, sort_by: sortBy, sort_order: sortOrder },
                dataType: 'json',
                success: function(response) {
                    tbody.innerHTML = '';
                    if (response.error) {
                        tbody.innerHTML = `<tr><td colspan="6">Error: ${response.error}</td></tr>`;
                        pagination.innerHTML = '';
                        totalCount.textContent = 'Total Students: 0';
                        return;
                    }

                    if (response.data && response.data.length > 0) {
                        response.data.forEach(row => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td>${row.student_id}</td>
                                <td>${row.name}</td>
                                <td>${row.department}</td>
                                <td>${row.year}</td>
                                <td>${row.batch}</td>
                                <td>
                                    <span class="action-btn edit-btn" data-id="${row.student_id}" data-name="${row.name}" data-dept="${row.department}" data-year="${row.year}" data-batch="${row.batch}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </span>
                                    <form action="" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                        <input type="hidden" name="delete_student_id" value="${row.student_id}">
                                        <button type="submit" name="delete_student" class="action-btn" style="background:none;border:none;padding:0;" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            `;
                            tbody.appendChild(tr);
                        });
                    } else {
                        tbody.innerHTML = `<tr><td colspan="6">No students found for ${year}.</td></tr>`;
                    }

                    // Update total count
                    totalCount.textContent = `Total Students: ${response.total_records}`;

                    // Update pagination
                    pagination.innerHTML = '';
                    for (let i = 1; i <= response.total_pages; i++) {
                        pagination.innerHTML += `<button class="${page === i ? 'active' : ''}" onclick="loadPage('${yearKey}', '${year}', ${i}, '${searchTerm}', '${sortBy}', '${sortOrder}')">${i}</button>`;
                    }

                    // Rebind edit buttons after table update
                    bindEditButtons();
                },
                error: function(xhr, status, error) {
                    console.log('AJAX Error: ', error);
                    tbody.innerHTML = `<tr><td colspan="6">Error loading data: ${error}. Please try again.</td></tr>`;
                    pagination.innerHTML = '';
                    totalCount.textContent = 'Total Students: 0';
                }
            });
        }

        // Search students for a specific year
        function searchStudents(yearKey, year) {
            const searchTerm = document.getElementById(`search_${yearKey}`).value;
            const { column, order } = sortStates[yearKey];
            loadPage(yearKey, year, 1, searchTerm, column, order);
        }

        // Clear search term for a specific year
        function clearSearch(yearKey, year) {
            document.getElementById(`search_${yearKey}`).value = '';
            const { column, order } = sortStates[yearKey];
            loadPage(yearKey, year, 1, '', column, order);
        }

        // Sort students for a specific year
        function sortStudents(yearKey, year, column) {
            let { column: currentColumn, order } = sortStates[yearKey];
            if (currentColumn === column) {
                order = order === 'ASC' ? 'DESC' : 'ASC';
            } else {
                order = 'ASC';
            }
            sortStates[yearKey] = { column, order };

            const searchTerm = document.getElementById(`search_${yearKey}`).value;
            loadPage(yearKey, year, 1, searchTerm, column, order);

            // Update sort icons
            document.querySelectorAll(`#table_${yearKey} th i`).forEach(icon => {
                icon.className = 'fas fa-sort';
            });
            const sortIcon = document.querySelector(`#table_${yearKey} th [onclick="sortStudents('${yearKey}', '${year}', '${column}')"] i`);
            sortIcon.className = `fas fa-sort-${order === 'ASC' ? 'up' : 'down'}`;
        }

        // Bind edit buttons after table updates
        function bindEditButtons() {
            const editBtns = document.querySelectorAll('.edit-btn');
            editBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const studentId = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const dept = this.getAttribute('data-dept');
                    const year = this.getAttribute('data-year');
                    const batch = this.getAttribute('data-batch');

                    document.getElementById('edit_student_id').value = studentId;
                    document.getElementById('edit_name').value = name;
                    document.getElementById('edit_department').value = dept;
                    document.getElementById('edit_year').value = year;
                    document.getElementById('edit_batch').value = batch;

                    editModal.style.display = 'flex';
                });
            });
        }

        // Load initial data for all years on page load
        document.addEventListener('DOMContentLoaded', function() {
            <?php foreach ($years as $year) { 
                $year_key = str_replace(' ', '_', $year);
                echo "loadPage('{$year_key}', '{$year}', 1, '', 'student_id', 'ASC');";
            } ?>
        });
    </script>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>