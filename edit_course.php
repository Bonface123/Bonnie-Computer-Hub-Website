<?php
// edit_course.php

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

// Fetch course details for editing
if (isset($_GET['id'])) {
    $course_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $course = $result->fetch_assoc();
    $stmt->close();
}

// Handle form submission for updating course
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $coursename = $_POST['coursename'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $teacher_id = $_POST['teacher_id'];

    // Update course in the database
    $stmt = $conn->prepare("UPDATE courses SET course_name = ?, description = ?, start_date = ?, end_date = ?, teacher_id = ? WHERE id = ?");
    $stmt->bind_param("ssssii", $coursename, $description, $start_date, $end_date, $teacher_id, $course_id);

    if ($stmt->execute()) {
        echo "Course updated successfully";
        header("Location: view_courses.php"); // Redirect after successful update
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course - Bonnie Computer Hub</title>
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
        </nav>
    </header>

    <main>
        <section class="edit-course">
            <h2>Edit Course</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="coursename">Course Name:</label>
                    <input type="text" id="coursename" name="coursename" value="<?php echo htmlspecialchars($course['course_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required><?php echo htmlspecialchars($course['description']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date:</label>
                    <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($course['start_date']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="end_date">End Date:</label>
                    <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($course['end_date']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="teacher_id">Teacher ID:</label>
                    <input type="number" id="teacher_id" name="teacher_id" value="<?php echo htmlspecialchars($course['teacher_id']); ?>" required>
                </div>
                <button type="submit" class="cta-btn">Edit Course</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Bonnie Computer Hub. All rights reserved.</p>
    </footer>
</body>
</html>
