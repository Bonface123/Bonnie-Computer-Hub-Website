<?php
session_start();
require 'db.php'; // Database connection file

// Check if the user is logged in
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

// Get student details from the session
$studentId = $_SESSION['id'];
$studentName = $_SESSION['name'];
$studentEmail = $_SESSION['email'];


// Database connection details
$host = 'localhost'; // Change if your database is hosted elsewhere
$db = 'student_portal'; // Change to your database name
$user = 'root'; // Change to your database username
$pass = ''; // Change to your database password

// Create a connection to the database
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Fetch enrolled courses for the logged-in user
$sql = "SELECT c.course_name, c.description, e.enrollment_date 
        FROM enrollments e 
        JOIN courses c ON e.course_id = c.id 
        WHERE e.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Start HTML output
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | Bonnie Computer Hub</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav class="container">
            <div class="logo">
                <a href="index.html">BONNIE COMPUTER HUB - BCH</a>
            </div>
            <ul class="nav-links">
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="courses.php">Courses</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section class="dashboard">
        <div class="container">
            <h2>Welcome to Your Dashboard</h2>
            <h3>Your Enrolled Courses:</h3>

            <?php if ($result->num_rows > 0): ?>
                <ul>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($row['course_name']); ?></strong><br>
                            <?php echo htmlspecialchars($row['description']); ?><br>
                            Enrolled on: <?php echo htmlspecialchars($row['enrollment_date']); ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>You have not enrolled in any courses yet.</p>
            <?php endif; ?>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2024 Bonnie Computer Hub. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
