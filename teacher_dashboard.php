<?php
// Ensure the user is logged in. If not, redirect to login page.
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: teacher_login.php');
    exit;
}

// Assuming the teacher's name and email are already set in the session
$teacherName = $_SESSION['name']; // Example from session
$teacherEmail = $_SESSION['email']; // Example email from session
$profile_picture = isset($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : 'uploads/default_profile.png';

$assignedCourses = [
    ['course_name' => 'Full Stack Web Development', 'students' => 25],
    ['course_name' => 'Python for Data Science', 'students' => 30],
];

// Example data for pending assignments to grade
$pendingAssignments = [
    ['title' => 'Build a Portfolio Website', 'student' => 'John Doe', 'submitted_on' => '2024-10-20'],
    ['title' => 'Data Analysis Project', 'student' => 'Jane Doe', 'submitted_on' => '2024-10-22'],
];

// Example data for course materials
$courseMaterials = [
    ['course_name' => 'Full Stack Web Development', 'materials' => ['HTML Basics.pdf', 'CSS Advanced Techniques.pdf']],
    ['course_name' => 'Python for Data Science', 'materials' => ['Pandas Introduction.pdf', 'Data Cleaning Techniques.pdf']],
];

// Example student progress data (can be fetched from database)
$studentProgress = [
    ['student_name' => 'John Doe', 'progress' => 80],
    ['student_name' => 'Jane Doe', 'progress' => 65],
];

// Example analytics data
$analyticsData = [
    'Full Stack Web Development' => ['avg_grade' => 85, 'completion_rate' => 90],
    'Python for Data Science' => ['avg_grade' => 78, 'completion_rate' => 75],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard | Bonnie Computer Hub</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Header Section Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f8f9fa; /* Light background for contrast */
        }

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

<!-- Header Section -->
<header>
  <nav class="container">
    <div class="logo">
      <a href="index.html">BONNIE COMPUTER HUB - BCH</a>
    </div>
    
    <ul class="nav-links">
<li><a href="view-students.php?course=<?php echo urlencode($course['course_name']); ?>" class="action-link">View Students</a></li>
        <li><a href="create_quiz.php">Create Quiz</a></li>
      
        <li><a href="upload_material.php">Upload Course Material</a></li>
        <li><a href="analytics.php">View Course Analytics</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
  </nav>
</header>

<body>
    <h1>Welcome to Your Teacher Dashboard, <?php echo $teacherName; ?>!</h1>

    <!-- Dashboard Section -->
    <section class="dashboard">
        <div class="container">

            <!-- Teacher Profile -->
            <div class="section profile">
                <h2>Your Profile</h2>
                <p><strong>Name:</strong> <?php echo $teacherName; ?></p>
                <p><strong>Email:</strong> <?php echo $teacherEmail; ?></p>
                <p><a href="update-profile.php" class="action-link">Update Profile</a></p>
            </div>

            <!-- Courses Managed Section -->
            <div class="section courses">
                <h2>Courses You Manage</h2>
                <?php foreach ($assignedCourses as $course): ?>
                    <div class="course-card">
                        <h3><?php echo $course['course_name']; ?></h3>
                        <p>Number of Students: <?php echo $course['students']; ?></p>
                        <p><a href="view_students.php?course=<?php echo urlencode($course['course_name']); ?>" class="action-link">View Students</a></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pending Assignments to Grade -->
            <div class="section assignments">
                <h2>Pending Assignments to Grade</h2>
                <?php foreach ($pendingAssignments as $assignment): ?>
                    <div class="assignment-card">
                        <h3><?php echo $assignment['title']; ?></h3>
                        <p>Submitted by: <?php echo $assignment['student']; ?></p>
                        <p>Submitted on: <?php echo $assignment['submitted_on']; ?></p>
                        <p><a href="grade_assignments.php?assignment=<?php echo urlencode($assignment['title']); ?>&student=<?php echo urlencode($assignment['student']); ?>" class="action-link">Grade Assignment</a></p>
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
</div>



<!-- Course Materials Section -->
<div class="materials">
    <h2>Course Materials</h2>
    <?php foreach ($courseMaterials as $material): ?>
        <div class="material-card">
            <h3><?php echo $material['course_name']; ?></h3>
            <p>Materials Available:</p>
            <ul>
                <?php foreach ($material['materials'] as $file): ?>
                    <li><a href="<?php echo $file; ?>" class="action-link"><?php echo $file; ?></a></li>
                <?php endforeach; ?>
            </ul>
            <p><a href="upload_material.php?course=<?php echo urlencode($material['course_name']); ?>" class="action-link">Upload New Material</a></p>
        </div>
    <?php endforeach; ?>
</div>

            <!-- Student Progress Section -->
            <div class="section progress">
                <h2>Student Progress</h2>
                <?php foreach ($studentProgress as $student): ?>
                    <div class="progress-card">
                        <h3><?php echo $student['student_name']; ?></h3>
                        <div class="progress-bar">
                            <span style="width: <?php echo $student['progress']; ?>%;"></span>
                        </div>
                        <p>Progress: <?php echo $student['progress']; ?>%</p>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Course Analytics Section -->
            <div class="section analytics">
                <h2>Course Analytics</h2>
                <?php foreach ($analyticsData as $course => $data): ?>
                    <div class="analytics-card">
                        <h3><?php echo $course; ?></h3>
                        <p>Average Grade: <?php echo $data['avg_grade']; ?></p>
                        <p>Completion Rate: <?php echo $data['completion_rate']; ?>%</p>
                    </div>
                <?php endforeach; ?>
            </div>


            <!-- Send Messages to Students -->
<div class="messages">
    <h2>Send a Message</h2>
    <div class="message-card">
        <p><a href="send_message.php" class="action-link">Send a Message to Your Students</a></p>
    </div>
</div>



<!-- Footer -->
<footer class="footer">
    <div class="footer-content">
        <p>&copy; 2024 Bonnie Computer Hub. All Rights Reserved.</p>
        <ul class="footer-links">
            <li><a href="privacy-policy.php">Privacy Policy</a></li>
            <li><a href="terms-of-service.php">Terms of Service</a></li>
            <li><a href="contact.php">Contact Us</a></li>
        </ul>
    </div>
</footer>

</body>
</html>
