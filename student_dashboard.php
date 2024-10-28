<?php
// Ensure the user is logged in. If not, redirect to login page.
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

// Get student details from session or database
$studentName = $_SESSION['name']; // Example from session
$studentEmail = $_SESSION['email']; // Example email from session
$profile_picture = isset($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : 'uploads/default_profile.png';

$studentCourses = [
    ['course_name' => 'Full Stack Web Development', 'progress' => '75%', 'certificate' => 'certificate-web-dev.pdf'],
    ['course_name' => 'Python for Data Science', 'progress' => '40%', 'certificate' => null],
];

$upcomingAssignments = [
    ['title' => 'Build a Portfolio Website', 'due_date' => '2024-10-31'],
    ['title' => 'Python Data Analysis Project', 'due_date' => '2024-11-05'],
];

$messagesFromInstructors = [
    ['message' => 'Don’t forget to submit your web development project by the end of this month.', 'date' => '2024-10-24'],
    ['message' => 'Join the Python Q&A session tomorrow at 10 AM.', 'date' => '2024-10-23'],
];

$courseMaterials = [
    ['course_name' => 'Full Stack Web Development', 'syllabus' => 'web-development-syllabus.pdf', 'materials' => ['HTML Basics.pdf', 'CSS Fundamentals.pdf', 'JavaScript Essentials.pdf']],
    ['course_name' => 'Python for Data Science', 'syllabus' => 'python-data-science-syllabus.pdf', 'materials' => ['Python Basics.pdf', 'Pandas Tutorial.pdf']],
];

$assessments = [
    ['course_name' => 'Full Stack Web Development', 'quiz_title' => 'HTML & CSS Quiz', 'status' => 'Completed'],
    ['course_name' => 'Python for Data Science', 'quiz_title' => 'Intro to Python Quiz', 'status' => 'Pending'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | Bonnie Computer Hub</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }
        header {
            background: #000;
            color: white;
            padding: 15px 0;
            text-align: center;
        }
        header h1 {
            margin: 0;
        }
        nav {
            margin-top: 15px;
            text-align: right;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
        }
        .dashboard {
            margin-top: 50px;
        }
        .dashboard h2 {
            color: #007bff;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .courses, .assignments, .messages, .materials, .assessments, .profile, .certificates {
            margin-bottom: 50px;
        }
        .course-card, .assignment-card, .message-card, .material-card, .assessment-card, .certificate-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .course-card h3, .assignment-card h3, .message-card h3, .material-card h3, .assessment-card h3, .certificate-card h3 {
            color: goldenrod;
            font-size: 20px;
        }
        .progress-bar {
            height: 10px;
            background: #f4f4f4;
            border-radius: 5px;
            overflow: hidden;
        }
        .progress-bar span {
            display: block;
            height: 100%;
            background-color: #007bff;
        }
        footer {
            text-align: center;
            margin-top: 50px;
            padding: 10px 0;
            background-color: #000;
            color: white;
        }
        .download-link {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s;
        }
        .download-link:hover {
            text-decoration: underline;
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

<!-- Dashboard Section -->
<section class="dashboard">
    <div class="container">

       <!-- Student Profile -->
        <div class="profile">
            <h2>Student Profile</h2>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($studentName); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($studentEmail); ?></p>

            <!-- Display Profile Picture -->
            <?php if (!empty($profile_picture)): ?>
                <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" style="width: 100px; height: 100px; border-radius: 50%;">
            <?php else: ?>
                <img src="uploads/default_profile.png" alt="Default Profile Picture" style="width: 100px; height: 100px; border-radius: 50%;">
            <?php endif; ?>

            <p><a href="update_profile.php" class="download-link">Update Profile</a></p>
        </div>

        <!-- Courses Section -->
        <div class="courses">
            <h2>Your Courses</h2>
            <?php foreach ($studentCourses as $course): ?>
                <div class="course-card">
                    <h3><?php echo $course['course_name']; ?></h3>
                    <p>Progress: <?php echo $course['progress']; ?></p>
                    <div class="progress-bar">
                        <span style="width: <?php echo $course['progress']; ?>;"></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Assessments Section -->
        <div class="assessments">
            <h2>Assessments</h2>
            <?php foreach ($assessments as $assessment): ?>
                <div class="assessment-card">
                    <h3><?php echo $assessment['quiz_title']; ?></h3>
                    <p>Course: <?php echo $assessment['course_name']; ?></p>
                    <p>Status: <?php echo $assessment['status']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Upcoming Assignments/Lessons -->
        <div class="assignments">
            <h2>Upcoming Assignments/Lessons</h2>
            <?php foreach ($upcomingAssignments as $assignment): ?>
                <div class="assignment-card">
                    <h3><?php echo $assignment['title']; ?></h3>
                    <p>Due Date: <?php echo $assignment['due_date']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Messages from Instructors -->
        <div class="messages">
            <h2>Messages from Instructors</h2>
            <?php foreach ($messagesFromInstructors as $message): ?>
                <div class="message-card">
                    <h3>Message</h3>
                    <p><?php echo $message['message']; ?></p>
                    <p><small><?php echo $message['date']; ?></small></p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Course Materials -->
        <div class="materials">
            <h2>Course Syllabus & Materials</h2>
            <?php foreach ($courseMaterials as $material): ?>
                <div class="material-card">
                    <h3><?php echo $material['course_name']; ?></h3>
                    <p><a href="<?php echo $material['syllabus']; ?>" class="download-link">Download Syllabus</a></p>
                    <ul>
                        <?php foreach ($material['materials'] as $file): ?>
                            <li><a href="<?php echo $file; ?>" class="download-link"><?php echo $file; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Certificates Section -->
        <div class="certificates">
            <h2>Certificates</h2>
            <?php foreach ($studentCourses as $course): ?>
                <?php if ($course['certificate']): ?>
                    <div class="certificate-card">
                        <h3><?php echo $course['course_name']; ?> Certificate</h3>
                        <p><a href="<?php echo $course['certificate']; ?>" class="download-link">Download Certificate</a></p>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Footer Section -->
<footer>
    <p>Bonnie Computer Hub © 2024. All rights reserved.</p>
</footer>

</body>
</html>
