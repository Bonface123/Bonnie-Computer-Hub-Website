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

    

<header>
  <nav class="container">
    <div class="logo">
      <!-- Include a logo image if available -->
      <img src="images/BchLogo.jpg" alt="Bonnie Computer Hub Logo" style="height: 40px; vertical-align: middle;">
      <a href="index.html">BONNIE COMPUTER HUB - BCH <br></a>
      <span style="color: goldenrod; font-size: 14px; margin-left: 1px;">Empowering Through Technology</span>
    </div>
    
    <ul class="nav-links">
        <li><a href="view_students.php">View Students</a></li>
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

    <!-- Link to view all course materials -->
    <a href="view_materials.php" class="btn">View Course Materials</a>

    <!-- Optional: You can also include a link to upload new materials -->
    <a href="upload_material.php" class="btn">Upload New Material</a>
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
