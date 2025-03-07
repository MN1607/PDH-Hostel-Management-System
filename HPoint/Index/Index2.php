<?php
session_start();
include 'db_connection.php'; // Ensure this file exists and connects to your database
// Redirect to login if session is missing
if (!isset($_SESSION['student_id'])) {
    header("Location: http://localhost/Hpoint/login.php");
    exit;
}

$username = $_SESSION['username'];

// Fetch student's full name based on username
$query = "SELECT name FROM students WHERE username = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$student_name = $student ? $student['name'] : 'Student';
$stmt->close();

// Fetch messages for the logged-in student
$msg_query = "SELECT id, message, is_read FROM message WHERE username = ? ORDER BY created_at DESC";
$msg_stmt = $conn->prepare($msg_query);
if (!$msg_stmt) {
    die("Error preparing statement: " . $conn->error);
}
$msg_stmt->bind_param("s", $username);
$msg_stmt->execute();
$msg_result = $msg_stmt->get_result();
$messages = [];
while ($row = $msg_result->fetch_assoc()) {
    $messages[] = $row;
}
$msg_stmt->close();

// Mark messages as read when the page is loaded
if (!empty($messages)) {
    $unread_ids = array_column(array_filter($messages, function($msg) {
        return $msg['is_read'] == 0;
    }), 'id');

    if (!empty($unread_ids)) {
        $update_query = "UPDATE message SET is_read = 1 WHERE id IN (" . implode(',', $unread_ids) . ")";
        $conn->query($update_query);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo htmlspecialchars($student_name); ?></title>
    <link rel="stylesheet" href="index2.css">
    <style>
        /* Additional CSS for better clarity and social media styling */
        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .college-name {
            font-size: 1.5em;
            font-weight: bold;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            line-height: 1.2;
        }
        .college-name span {
            display: block;
            font-size: 0.8em;
            font-weight: normal;
            color: #ffd700;
        }
        .social-media a {
            position: relative;
            margin: 0 10px;
            text-decoration: none;
        }
        .social-media a:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: #fff;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
        }
        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 20px;
            color: #fff;
            transition: transform 0.3s;
        }
        .social-icon:hover {
            transform: scale(1.1);
        }
        .facebook { background: #3b5998; }
        .twitter { background: #1da1f2; }
        .instagram { background: #e1306c; }
        .linkedin { background: #0077b5; }
    </style>
</head>
<body>
    <div class="background-animation"></div>

    <header>
        <div class="logo-container">
            <img src="dbcy.jpg" alt="Don Bosco College Logo" class="logo">
            <span class="college-name">Don Bosco College <span>(Co-Ed), Yelagiri Hills</span></span>
        </div>
        <nav>
            <a href="/Hpoint/About/about.html"><span class="menu-icon">ℹ️</span> About</a>
            <a href="/Hpoint/Gatepass/student_messages.php"><span class="menu-icon">🚪</span> Gate Pass</a>
            <a href="http://localhost/Hpoint/Fee/studentfees.php"><span class="menu-icon">💰</span> Hostel Fee</a>
            <a href="http://localhost/Hpoint/Attendance/student_attendance.php"><span class="menu-icon">📅</span> Attendance</a>
            <a href="/Hpoint/Maintenance/Maintenance.php"><span class="menu-icon">🔧</span> Maintenance</a>
            <a href="/Hpoint/Sports/Sports.php"><span class="menu-icon">⚽</span> Sports</a>
            <a href="/Hpoint/Reading/reading.php"><span class="menu-icon">📚</span> Reading</a>
            <a href="http://localhost/Hpoint/Mess/studentmess.php"><span class="menu-icon">🍽️</span> Mess</a>
        </nav>
        <a href="admin_login.php" class="admin">Admin</a>
    </header>

    <main>
        <div class="student-info">
            Welcome, <?php echo htmlspecialchars($student_name); ?>! 🎉
        </div>

        <div class="search-bar">
            <div class="search-wrapper">
                <input type="text" id="searchInput" placeholder="Search content on this page..." onkeyup="searchContent()">
                <span class="search-icon">🔍</span>
            </div>
        </div>

        <div class="marquee">
            <marquee behavior="scroll" direction="left" scrollamount="5">
                📢 05-03-2025 is the last date to pay the <a href="http://localhost/Hpoint/Mess/studentmess.php" class="marquee-link">mess fee</a> | 
                Kindly pay your <a href="http://localhost/Hpoint/Fee/studentfees.php" class="marquee-link">hostel fee</a> | 
                Friday: Outing from 5:15 to 5:45 PM | 
                Sunday: Outing from 3:00 to 5:30 PM
            </marquee>
        </div>

        <!-- Full-Screen Slideshow -->
        <div class="slideshow-container">
            <div class="slides fade">
                <img src="pdh.jpg" alt="PDH Hostel">
            </div>
            <div class="slides fade">
                <img src="hp4.jpg" alt="Campus View">
            </div>
            <div class="slides fade">
                <img src="hp3.jpg" alt="Student Life">
            </div>
            <a class="prev" onclick="plusSlides(-1)">❮</a>
            <a class="next" onclick="plusSlides(1)">❯</a>
        </div>

        <!-- Full-Screen Content Sections -->
        <div class="content-container">
            <!-- Updated College Info Section -->
            <div class="college-info">
                <div class="college-info-content">
                    <div class="college-text">
                        <h3>Welcome to Don Bosco College, Yelagiri Hills</h3>
                        <p>The Don Bosco community envisioned that quality professional education in computing as a realistic strategy to guarantee the employability of rural youth. A non-formal computer training and software development center (named as BICS InfoTech) was begun in 1998 to provide professional education. Sixty students were admitted per year for the one-year and three-year academic programs. BICS signed memorandum of understanding with CDAC-Pune and IGNOU to conduct diploma and degree programs.</p>
                        <p>The institute functioned under three wings: Bosco Institute of Information Technology (BIIT), the Professional Education wing that prepared the students to become employable and obtain degree from IGNOU; Bosco InfoTech Services (BOSCO ITS) for software development and training for the graduates who passed out of BIIT; Arivagam (in 2006) as Knowledge Resource Centre. While the students pursued BCA Degree from IGNOU, a rigorous Postgraduate Diploma in Information Technology (PGDIT) was offered for the students. BIIT educated more than 1000 rural students in a span of a decade, as professionals in Information Technology. Most of them are placed in the industry or service sector as developers, testers, graphic artists, technical writers, hardware technicians and teachers...</p>
                        <a href="/Hpoint/About/about.html" class="read-more">Read More</a>
                    </div>
                    <div class="college-image">
                        <img src="c.png" alt="Don Bosco Statue">
                    </div>
                </div>
            </div>

            <div class="contact-about">
                <h3>Contact & Location</h3>
                <p>Email: <a href="mailto:info@dbcyelagiri.edu.in" class="contact-link">info@dbcyelagiri.edu.in</a></p>
                <p>Phone: <a href="tel:+91 4179 295443" class="contact-link">+91 4179 295443</a></p>
                <p>Address: Don Bosco College, Guezou Nagar, Athanavoor, Yelagiri Hills, Tirupattur, Tamil Nadu - 635853, India</p>
            </div>

            <!-- Location Map Section -->
            <div class="location-map">
                <h3>Location Map</h3>
                <a href="https://www.google.com/maps/place/Don+Bosco+College+%2F+Centre/@12.5825,78.6325,14z/data=!4m10!1m2!2m1!1sDon+Bosco+College,+Yelagiri+Hills!3m6!1s0x3bae147b6c6358a5:0x4a4e6d8b7e8f3b!8m2!3d12.5825!4d78.6325!15sChlEb24gQm9zY28gQ29sbGVnZSwgWWVsYWdpcmkgSGlsbHNawgEAZAGCAkQ8JGG!16s%2Fg%2F11c5t7j7tf?entry=ttu" target="_blank">
                    <img src="location.png" alt="Don Bosco College Location Map" class="map-image">
                </a>
                <p>Click the map to view on Google Maps!</p>
            </div>

            <!-- Quick Contact Section -->
            <div class="quick-contact">
                <h3>Quick Contact</h3>
                <div class="contact-items">
                    <div class="contact-item">
                        <span class="contact-icon">📞</span>
                        <p><strong>+91 4179 295443</strong><br>Phone</p>
                    </div>
                    <div class="contact-item">
                        <span class="contact-icon">✉️</span>
                        <p><strong>info@dbcyelagiri.edu.in</strong><br>Email</p>
                    </div>
                    <div class="contact-item">
                        <span class="contact-icon">🏢</span>
                        <p><strong>Yelagiri Hills</strong><br>Headquarters</p>
                    </div>
                </div>
                <h4>Follow Us on Social Media!</h4>
                <div class="social-media">
                    <a href="https://www.facebook.com/dbcyelagiri" target="_blank" class="social-icon facebook" data-tooltip="Follow us on Facebook"><span class="icon-text">f</span></a>
                    <a href="https://twitter.com/dbcyelagiri" target="_blank" class="social-icon twitter" data-tooltip="Follow us on Twitter"><span class="icon-text">t</span></a>
                    <a href="https://www.instagram.com/dbcyelagiri" target="_blank" class="social-icon instagram" data-tooltip="Follow us on Instagram"><span class="icon-text">i</span></a>
                    <a href="https://www.linkedin.com/school/don-bosco-college-yelagiri-hills" target="_blank" class="social-icon linkedin" data-tooltip="Connect on LinkedIn"><span class="icon-text">in</span></a>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>© Nishanth Clinton A - 3CS | Don Bosco College, Yelagiri Hills</p>
    </footer>

    <script>
        // Slideshow Script
        let slideIndex = 1;
        showSlides(slideIndex);

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function showSlides(n) {
            let i;
            const slides = document.getElementsByClassName("slides");
            if (n > slides.length) { slideIndex = 1; }
            if (n < 1) { slideIndex = slides.length; }
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slides[slideIndex - 1].style.display = "block";
        }

        // Auto-slide every 5 seconds
        setInterval(() => {
            plusSlides(1);
        }, 5000);

        // Enhanced Search Functionality (Highlight specific words)
        function searchContent() {
            const input = document.getElementById("searchInput").value.toLowerCase().trim();
            const content = document.querySelectorAll("p, h3, a");

            // Clear previous highlights
            content.forEach(element => {
                const originalText = element.dataset.originalText || element.textContent;
                element.innerHTML = originalText;
                element.dataset.originalText = originalText; // Store original text
            });

            if (input === "") return; // Exit if search input is empty

            content.forEach(element => {
                const text = element.textContent.toLowerCase();
                if (text.includes(input)) {
                    const regex = new RegExp(`(${input})`, 'gi');
                    const newText = element.textContent.replace(regex, '<span class="highlight">$1</span>');
                    element.innerHTML = newText;
                    element.scrollIntoView({ behavior: "smooth", block: "center" });
                }
            });
        }
    </script>
</body>
</html>