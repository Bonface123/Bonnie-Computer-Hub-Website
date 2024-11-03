<?php
// create_course.php

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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $coursename = $_POST['coursename'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $teacher_id = $_POST['teacher_id']; // Assuming you have teacher ID

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO courses (course_name, description, start_date, end_date, teacher_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $coursename, $description, $start_date, $end_date, $teacher_id);

    if ($stmt->execute()) {
        echo "New course created successfully";
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
    <title>Create Course - Bonnie Computer Hub</title>
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
        <section class="create-course">
            <h2>Create a New Course</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="coursename">Course Name:</label>
                    <input type="text" id="coursename" name="coursename" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date:</label>
                    <input type="date" id="start_date" name="start_date" required>
                </div>
                <div class="form-group">
                    <label for="end_date">End Date:</label>
                    <input type="date" id="end_date" name="end_date" required>
                </div>
                <div class="form-group">
                    <label for="teacher_id">Teacher ID:</label>
                    <input type="number" id="teacher_id" name="teacher_id" required>
                </div>
                <button type="submit" class="cta-btn">Create Course</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Bonnie Computer Hub. All rights reserved.</p>
    </footer>
</body>
</html>
