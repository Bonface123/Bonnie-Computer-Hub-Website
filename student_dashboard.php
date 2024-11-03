<?php
session_start();
require 'db.php'; // Database connection file

// Check if the user is logged in
if (!isset($_SESSION['loggedin'])) {
    header('Location: teacher_login.php');
    exit;
}

// Get student details from the session
$studentId = $_SESSION['id'];
$studentName = $_SESSION['name'];
$studentEmail = $_SESSION['email'];

// Fetch the updated profile picture path directly from the database
$sql = "SELECT profile_picture FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentId);
$stmt->execute();
$stmt->bind_result($profile_picture);
$stmt->fetch();
$stmt->close();

// Set a default profile picture if none is uploaded
if (empty($profile_picture)) {
    $profile_picture = 'uploads/default_profile.png';
}

// Function to fetch enrolled courses
function getEnrolledCourses($conn, $studentId) {
    $sql = "
        SELECT c.id AS course_id, c.course_name, description, e.progress
        FROM enrollments e
        JOIN courses c ON e.course_id = c.id
        WHERE e.student_id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Function to fetch available courses
function getAvailableCourses($conn, $studentId) {
    $sql = "
        SELECT id, course_name 
        FROM courses 
        WHERE id NOT IN (SELECT course_id FROM enrollments WHERE student_id = ?)
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Function to enroll in a course
function enrollInCourse($conn, $studentId, $courseId) {
    $sql = "INSERT INTO enrollments (student_id, course_id, progress) VALUES (?, ?, '0%')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $studentId, $courseId);
    return $stmt->execute();
}

// Handle course enrollment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enroll'])) {
    $course_id_to_enroll = (int)$_POST['course_id'];
    if (enrollInCourse($conn, $studentId, $course_id_to_enroll)) {
        header('Location: student_dashboard.php'); // Redirect to see updated courses
        exit;
    } else {
        $enrollmentError = "Enrollment failed. Please try again.";
    }
}

// Fetch current data
$studentCourses = getEnrolledCourses($conn, $studentId);
$availableCourses = getAvailableCourses($conn, $studentId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | Bonnie Computer Hub</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Reset some default styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
}

header {
    background-color: #0056b3;
    color: white;
    padding: 10px 0;
}

header .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: auto;
}

.logo a {
    color: white;
    text-decoration: none;
    font-size: 24px;
    font-weight: bold;
}

.nav-links {
    list-style: none;
}

.nav-links li {
    display: inline;
    margin-left: 20px;
}

.nav-links a {
    color: white;
    text-decoration: none;
}

.nav-links a.active {
    text-decoration: underline;
}

h1 {
    color: #ffa500;
    text-align: center;
    margin: 20px 0;
}

.container {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
}

.dashboard {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.profile, .courses, .enrollment, .assessments, .assignments, .messages, .materials {
    background-color: white;
    border-radius: 8px;
    padding: 20px;
    margin: 20px 0;
    width: 100%;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

h2 {
    margin-bottom: 10px;
    color: #333;
}

.course-card {
    background-color: #e9ecef;
    border-radius: 5px;
    padding: 15px;
    margin: 10px 0;
}

.progress-bar {
    background-color: #ddd;
    border-radius: 5px;
    height: 10px;
    overflow: hidden;
}

.progress-bar span {
    display: block;
    height: 100%;
    background-color: #5cb85c; /* Green color for progress */
}

.download-link {
    color: #0056b3;
    text-decoration: none;
}

.download-link:hover {
    text-decoration: underline;
}

button {
    background-color: #0056b3;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background-color: #004494;
}

footer {
    text-align: center;
    padding: 15px 0;
    background-color: #0056b3;
    color: white;
    position: relative;
    bottom: 0;
    width: 100%;
}

@media (max-width: 768px) {
    .dashboard {
        flex-direction: column;
    }

    .nav-links li {
        display: block;
        margin: 10px 0;
    }
}
    </style>
    <style>
        .profile-picture {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>

<header>
    <nav class="container">
        <div class="logo">
            <a href="index.html">BONNIE COMPUTER HUB - BCH</a>
        </div>
        <ul class="nav-links">
            <li><a href="index.html">Home</a></li>
            <li><a href="courses.html" class="active">Courses</a></li>
            <li><a href="#contact">Contact</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>
<h1 style="color: #ffa500; text-align: center;">Welcome to Your Dashboard, <?php echo htmlspecialchars($studentName); ?>!</h1>

<section class="dashboard">
    <div class="container">
        <div class="profile">
            <h2>Your Profile</h2>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($studentName); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($studentEmail); ?></p>
            <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="profile-picture">
            <p><a href="update_profile.php" class="download-link">Update Profile</a></p>
        </div>
        
        <div class="courses">
            <h2>Your Courses</h2>
            <?php if (!empty($studentCourses)): ?>
                <?php foreach ($studentCourses as $course): ?>
                    <div class="course-card">
                        <h3><?php echo htmlspecialchars($course['course_name']); ?></h3>
                        <p>Progress: <?php echo htmlspecialchars($course['progress']); ?></p>
                        <div class="progress-bar">
                            <span style="width: <?php echo htmlspecialchars($course['progress']); ?>;"></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>You are not enrolled in any courses yet.</p>
            <?php endif; ?>
        </div>

        <div class="enrollment">
            <h2>Enroll in New Courses</h2>
            <form method="POST" action="">
                <select name="course_id" required>
                    <option value="">Select a Course</option>
                    <?php foreach ($availableCourses as $availableCourse): ?>
                        <option value="<?php echo $availableCourse['id']; ?>"><?php echo htmlspecialchars($availableCourse['course_name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="enroll">Enroll</button>
                <?php if (isset($enrollmentError)): ?>
                    <p style="color: red;"><?php echo htmlspecialchars($enrollmentError); ?></p>
                <?php endif; ?>
            </form>
        </div>

        <div class="assessments">
            <h2>Your Assessments</h2>
            <!-- Future implementation of assessment logic -->
        </div>

        <div class="assignments">
            <h2>Upcoming Assignments</h2>
            <!-- Future implementation of assignment logic -->
        </div>

        <div class="messages">
            <h2>Messages from Instructors</h2>
            <!-- Future implementation of messaging logic -->
        </div>

        <div class="materials">
            <h2>Your Course Materials</h2>
            <!-- Future implementation of materials logic -->
        </div>
    </div>
</section>

<footer>
    <p>&copy; 2024 Bonnie Computer Hub. All rights reserved.</p>
</footer>
</body>
</html>
