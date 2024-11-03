<?php
// course_catalog.php

// Database connection
$host = 'localhost'; // Change if your database is hosted elsewhere
$db = 'student_portal'; // Change to your database name
$user = 'root'; // Change to your database username
$pass = ''; // Change to your database password

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch courses from the database
$sql = "SELECT id, course_name, description FROM courses";
$result = $conn->query($sql);

// Store the courses in an array
$courses = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row; // Add each course to the array
    }
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Course Catalog - Bonnie Computer Hub</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">BCH</div>
            <ul class="nav-links">
                <li><a href="#about">About</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="login_register.html">Login / Register</a></li>
            </ul>
            <a href="#enroll" class="cta-btn">Enroll Now</a>
        </nav>
    </header>

    <main>
        <section class="course-catalog">
            <h2>Full-Stack Web Development Program</h2>
            <p>This program is divided into three modules, each lasting 2 months. Enroll in one or all modules to master full-stack development.</p>
            
            <div class="course-grid">
                <?php if (!empty($courses)): ?>
                    <?php foreach ($courses as $course): ?>
                        <div class="course-card">
                            <h3><?php echo htmlspecialchars($course['course_name']); ?></h3>
                            <p><?php echo htmlspecialchars($course['description']); ?></p>
                            <a href="course_details.php?id=<?php echo $course['id']; ?>" class="cta-btn">View Details</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No courses available at the moment.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Bonnie Computer Hub. All rights reserved.</p>
    </footer>
</body>
</html>
