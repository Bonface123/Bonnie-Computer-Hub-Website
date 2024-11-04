<?php
session_start();
require 'db.php'; // Database connection file

// Check if the user is logged in
if (!isset($_SESSION['loggedin'])) {
    header('Location: teacher_login.php');
    exit;
}

// Get teacher details from the session
$teacherId = $_SESSION['id'];
$teacherName = $_SESSION['name'];
$teacherEmail = $_SESSION['email'];
// Database connection
$mysqli = new mysqli("localhost", "root", "", "student_portal"); // Update with your DB credentials

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch assigned courses
$coursesQuery = "SELECT c.course_name, COUNT(u.id) as students 
                 FROM courses c 
                 LEFT JOIN users u ON c.id = u.id AND u.role = 'student' 
                 GROUP BY c.course_name";
$coursesResult = $mysqli->query($coursesQuery);

$assignedCourses = [];
if ($coursesResult) {
    while ($row = $coursesResult->fetch_assoc()) {
        $assignedCourses[] = $row;
    }
}

// Fetch pending assignments to grade
$assignmentsQuery = "SELECT title, u.name as student_name, submitted_on 
                     FROM assignments a 
                     JOIN users u ON a.id = u.id 
                     WHERE u.role = 'student' AND a.teacher_id = ?";
$stmt = $mysqli->prepare($assignmentsQuery);
$stmt->bind_param("i", $_SESSION['teacher_id']); // assuming teacher_id is stored in session
$stmt->execute();
$pendingAssignmentsResult = $stmt->get_result();

$pendingAssignments = [];
while ($assignment = $pendingAssignmentsResult->fetch_assoc()) {
    $pendingAssignments[] = $assignment;
}

// Fetch course materials
$materialsQuery = "SELECT course_name, material_name FROM course_materials";
$materialsResult = $mysqli->query($materialsQuery);

$courseMaterials = [];
if ($materialsResult) {
    while ($material = $materialsResult->fetch_assoc()) {
        $courseMaterials[$material['course_name']][] = $material['material_name'];
    }
}

// Fetch student progress data
$progressQuery = "SELECT u.name as student_name, sp.progress 
                  FROM student_progress sp 
                  JOIN users u ON sp.student_id = u.id 
                  WHERE u.role = 'student' AND sp.id = ?";
$stmt = $mysqli->prepare($progressQuery);
$stmt->bind_param("i", $_SESSION['teacher_id']);
$stmt->execute();
$studentProgressResult = $stmt->get_result();

$studentProgress = [];
while ($progress = $studentProgressResult->fetch_assoc()) {
    $studentProgress[] = $progress;
}

// Fetch analytics data
$analyticsQuery = "SELECT c.course_name, AVG(a.grade) as avg_grade, AVG(a.completion_rate) as completion_rate 
                   FROM analytics a 
                   JOIN courses c ON a.course_id = c.id 
                   GROUP BY c.course_name";
$analyticsResult = $mysqli->query($analyticsQuery);

$analyticsData = [];
if ($analyticsResult) {
    while ($data = $analyticsResult->fetch_assoc()) {
        $analyticsData[$data['course_name']] = [
            'avg_grade' => round($data['avg_grade'], 2),
            'completion_rate' => round($data['completion_rate'], 2)
        ];
    }
}

// Close the database connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard | Bonnie Computer Hub</title>

    <style>
        /* Header Section Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f8f9fa; /* Light background for contrast */
        }

/* Header Styling */
header {
    background-color: #1e1e1e; /* Black */
    color: #ffffff; /* White */
    padding: 15px 0;
    position: sticky;
    top: 0;
    width: 100%;
    z-index: 1000; /* Ensures it stays above other content */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Optional shadow for better visibility */
}

header nav.container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Logo Styling */
.logo a {
    font-size: 24px;
    font-weight: bold;
    text-decoration: none;
    color: white; /* Brand color for the logo */
    transition: color 0.3s;
}

.logo a:hover {
    color: goldenrod; /* Hover effect on logo */
}

.logo img {
    margin-right: 10px; /* Space between image and text */
    vertical-align: middle; /* Align image with text */
}

/* Navigation Links Styling */
.nav-links {
    list-style: none;
    display: flex;
    gap: 20px; /* Space between menu items */
    margin: 0;
    padding: 0;
}

.nav-links li {
    margin: 0;
}

.nav-links a {
    text-decoration: none;
    font-size: 16px;
    color: white; /* Default color for links */
    padding: 8px 15px;
    transition: color 0.3s, background-color 0.3s;
    text-align: center;
}

.nav-links a:hover {
    color: #ffa500; /* Golden/Orange */
}

.nav-links a.active {
    color: goldenrod; /* Active page indicator */
    font-weight: bold;
}

/* Profile Menu Styling */
.profile-menu {
    position: relative;
    display: inline-block;
}

.profile-menu .dropdown {
    display: none;
    position: absolute;
    background-color: white;
    min-width: 160px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    z-index: 1;
    border-radius: 8px;
    right: 0; /* Align dropdown to the right */
}

.profile-menu:hover .dropdown {
    display: block;
}

.dropdown li {
    padding: 12px 16px;
    text-align: left;
}

.dropdown li a {
    color: #007bff; /* Link color in dropdown */
    text-decoration: none;
    display: block;
    font-size: 14px;
}

.dropdown li a:hover {
    background-color: #f1f1f1;
}

/* Notifications Icon Styling */
.nav-links li a[title="Notifications"] {
    font-size: 20px;
    position: relative;
}

.nav-links li a[title="Notifications"]::after {
    content: '•'; /* Add a dot to indicate notifications */
    color: red;
    font-size: 16px;
    position: absolute;
    top: -5px;
    right: -5px;
}

/* Responsive Styling */
@media (max-width: 768px) {
    header nav.container {
        flex-direction: column;
        align-items: flex-start;
    }
    .nav-links {
        flex-direction: column;
        gap: 10px;
        width: 100%;
    }
}

        .dashboard {
            margin: 50px auto; /* Center the dashboard */
            max-width: 1200px; /* Limit width */
            padding: 20px;
            background-color: white; /* White background for dashboard */
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        h1 {
            text-align: center;
            color: #007bff; /* Brand color */
            margin-bottom: 20px;
        }

        .profile-menu {
    position: relative;
    display: inline-block;
}

.profile-menu .dropdown {
    display: none;
    position: absolute;
    background-color: white;
    min-width: 160px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    z-index: 1;
    border-radius: 8px;
    right: 0;
}

.profile-menu:hover .dropdown {
    display: block;
}

.dropdown li {
    padding: 12px 16px;
    text-align: left;
}

.dropdown li a {
    color: #007bff;
    text-decoration: none;
    display: block;
}

.dropdown li a:hover {
    background-color: #f1f1f1;
}


        h2 {
            color: #007bff; /* Brand color */
            font-size: 22px;
            margin-bottom: 15px;
        }

        .section {
            margin-bottom: 50px;
        }

        .course-card, .assignment-card, .material-card, .progress-card, .analytics-card{
            background: #f4f4f4; /* Light gray background */
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .course-card h3, .assignment-card h3, .material-card h3, .progress-card h3, .analytics-card h3 {
            color: goldenrod;
            font-size: 18px;
        }

        .progress-bar {
            height: 10px;
            background: #e9ecef; /* Lighter gray for progress bar background */
            border-radius: 5px;
            overflow: hidden;
        }

        .progress-bar span {
            display: block;
            height: 100%;
            background-color: #007bff; /* Progress color */
        }

        .action-link {
            text-decoration: none;
            color: #007bff; /* Brand color for links */
            font-weight: bold;
        }

        .action-link:hover {
            color: goldenrod; /* Hover effect */
        }
        /* Quizzes Section */
.quizzes {
    margin-bottom: 50px;
}

.quiz-card {
    background: #f4f4f4; /* Light gray background */
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.quiz-card h3 {
    color: goldenrod; /* Match with other headings */
    font-size: 18px;
}

/* Course Materials Section */
.materials {
    margin-bottom: 50px;
}

.material-card {
    background: #f4f4f4; /* Light gray background */
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.material-card h3 {
    color: goldenrod; /* Match with other headings */
    font-size: 18px;
}

.material-card ul {
    list-style-type: disc; /* Use bullet points for list items */
    padding-left: 20px; /* Indent the list */
}

.material-card ul li {
    margin: 5px 0; /* Space out list items */
}


/* Send Messages Section */
.messages {
    margin-bottom: 50px;
}

.message-card {
    background: #f4f4f4; /* Light gray background */
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.messages h2 {
    color: goldenrod; /* Match with other headings */
    font-size: 24px; /* Adjust heading size */
}

.action-link {
    text-decoration: none;
    color: #007bff; /* Link color */
    font-weight: bold; /* Bold for emphasis */
}

.action-link:hover {
    text-decoration: underline; /* Underline on hover */
}

.footer {
    background-color:#000; /* Dark blue background */
    color: #ffffff; /* White text */
    padding: 20px 0; /* Vertical padding */
    text-align: center; /* Center-align text */
}

.footer-content {
    max-width: 1200px; /* Max width for content */
    margin: 0 auto; /* Center the content */
    padding: 0 15px; /* Horizontal padding */
}

.footer p {
    margin: 0; /* Remove default margin */
    font-size: 14px; /* Font size for copyright */
}

.footer-links {
    list-style-type: none; /* Remove bullet points */
    padding: 0; /* Remove default padding */
    margin: 10px 0 0; /* Margin on top */
}

.footer-links li {
    display: inline; /* Inline list items */
    margin: 0 15px; /* Space between links */
}

.footer-links a {
    color: #ffcc00; /* Golden link color */
    text-decoration: none; /* Remove underline */
}

.footer-links a:hover {
    text-decoration: underline; /* Underline on hover */
    color: #ffffff; /* Change link color on hover */
}


    </style>
</head>

<header>
  <nav class="container">
    <div class="logo">
      <!-- Include a logo image if available -->
      <img src="images/BchLogo.jpg" alt="Bonnie Computer Hub Logo" style="height: 40px; vertical-align: middle;">
      <a href="index.html">BONNIE COMPUTER HUB - BCH</a>
      <span style="color: goldenrod; font-size: 14px; margin-left: 10px;">Empowering Through Technology</span>
    </div>
    
    <ul class="nav-links">
        <li><a href="view_students.php" class="action-link">View Students</a></li>
        <li><a href="create_quiz.php">Create Quiz</a></li>
        <li><a href="upload_material.php">Upload Course Material</a></li>
        <li><a href="analytics.php">View Analytics</a></li>
        <!-- Notification Bell -->
        <li>
            <a href="notifications.php" title="Notifications">
                <span style="font-size: 20px;">🔔</span>
            </a>
        </li>
        <!-- Profile Menu -->
        <li class="profile-menu">
            <a href="teacher_profile.php" class="action-link">
                <span><?php echo htmlspecialchars($teacherName); ?> ▼</span>
            </a>
            <ul class="dropdown">
                <li><a href="update_profile.php">Profile Settings</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </li>
    </ul>
  </nav>
</header>


<body>
    <h1>Welcome to Your Teacher Dashboard, <?php echo htmlspecialchars($teacherName); ?>!</h1>

    <!-- Dashboard Section -->
    <section class="dashboard">
        <div class="container">

            <!-- Teacher Profile -->
            <div class="profile">
            <h2>Your Profile</h2>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($teacherName); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($teacherEmail); ?></p>
            <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="profile-picture">
            <p><a href="update_profile.php" class="download-link">Update Profile</a></p>
        </div>

            <!-- Courses Managed Section -->
            <div class="section courses">
                <h2>Courses You Manage</h2>
                <?php foreach ($assignedCourses as $course): ?>
                    <div class="course-card">
                        <h3><?php echo htmlspecialchars($course['course_name']); ?></h3>
                        <p>Number of Students: <?php echo htmlspecialchars($course['students']); ?></p>
                        <p><a href="view_students.php?course=<?php echo urlencode($course['course_name']); ?>" class="action-link">View Students</a></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pending Assignments to Grade -->
            <div class="section assignments">
                <h2>Pending Assignments to Grade</h2>
                <?php foreach ($pendingAssignments as $assignment): ?>
                    <div class="assignment-card">
                        <h3><?php echo htmlspecialchars($assignment['title']); ?></h3>
                        <p>Submitted by: <?php echo htmlspecialchars($assignment['student_name']); ?></p>
                        <p>Submitted on: <?php echo htmlspecialchars($assignment['submitted_on']); ?></p>
                        <p><a href="grade_assignments.php?assignment=<?php echo urlencode($assignment['title']); ?>&student=<?php echo urlencode($assignment['student_name']); ?>" class="action-link">Grade Assignment</a></p>
                    </div>
                <?php endforeach; ?>
            </div>
<!-- Create Quizzes and Assignments -->
<div class="section quizzes">
    <h2>Create Quizzes and Assignments</h2>
    <div class="quiz-card">
        <h3>Create New Quiz</h3>
        <p><a href="create_quiz.php" class="action-link">Start Creating Quiz</a></p>
    </div>
    <div class="assignment-card">
        <h3>Create New Assignment</h3>
        <p><a href="create_assignment.php" class="action-link">Start Creating Assignment</a></p>
    </div>
    <div class="view-assignments-card">
        <h3>View All Assignments</h3>
        <p><a href="view_assignments.php" class="action-link">See All Assignments</a></p>
    </div>
</div>

<!-- Course Materials Section -->
<div class="materials">
            <h2>Course Materials</h2>
            <?php if (!empty($courseMaterials)): ?>
                <?php foreach ($courseMaterials as $course => $materials): ?>
                    <div class="material-card">
                        <h3><?php echo htmlspecialchars($course); ?></h3>
                        <p>Materials Available:</p>
                        <ul>
                            <?php foreach ($materials as $file): ?>
                                <li>
                                    <a href="<?php echo htmlspecialchars($file); ?>" class="action-link">
                                        <?php echo htmlspecialchars(basename($file)); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <p>
                            <a href="upload_material.php?course=<?php echo urlencode($course); ?>" class="action-link">
                                Upload New Material
                            </a>
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No materials available for any course.</p>
            <?php endif; ?>
        </div>


            <!-- Student Progress Section -->
            <div class="section progress">
                <h2>Student Progress</h2>
                <?php foreach ($studentProgress as $student): ?>
                    <div class="progress-card">
                        <h3><?php echo htmlspecialchars($student['student_name']); ?></h3>
                        <div class="progress-bar">
                            <span style="width: <?php echo htmlspecialchars($student['progress']); ?>%;"></span>
                        </div>
                        <p>Progress: <?php echo htmlspecialchars($student['progress']); ?>%</p>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Course Analytics Section -->
            <div class="section analytics">
                <h2>Course Analytics</h2>
                <?php foreach ($analyticsData as $course => $data): ?>
                    <div class="analytics-card">
                        <h3><?php echo htmlspecialchars($course); ?></h3>
                        <p>Average Grade: <?php echo htmlspecialchars($data['avg_grade']); ?></p>
                        <p>Completion Rate: <?php echo htmlspecialchars($data['completion_rate']); ?>%</p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Bonnie Computer Hub. All rights reserved.</p>
    </footer>
</body>
</html>
