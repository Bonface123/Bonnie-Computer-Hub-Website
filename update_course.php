<?php
// update_course.php

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
$course_id = $_GET['id'] ?? null;

if ($course_id) {
    $stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $course = $result->fetch_assoc();
    $stmt->close();
    
    // Check if course exists
    if (!$course) {
        echo "Course not found.";
        exit; // Stop the script if the course doesn't exist
    }
} else {
    echo "Invalid course ID.";
    exit; // Stop the script if no ID is provided
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
    $stmt->bind_param("ssiiii", $coursename, $description, $start_date, $end_date, $teacher_id, $course_id);

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
    <title>Update Course - Bonnie Computer Hub</title>
  
    <style>
        /* styles.css */

/* General Styles */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f9f9f9;
    color: #333;
}

/* Header Styles */
header {
    background-color: #0044cc; /* BCH blue */
    color: #fff;
    padding: 15px 0;
}

.logo {
    font-size: 1.8em;
    font-weight: bold;
    text-align: center;
    color: #ffd700; /* BCH golden */
}

nav {
    display: flex;
    justify-content: center;
    align-items: center;
}

.nav-links {
    list-style: none;
    display: flex;
    gap: 20px;
    padding: 0;
}

.nav-links li {
    margin: 0;
}

.nav-links a {
    color: #fff;
    text-decoration: none;
    font-weight: 600;
    padding: 5px 10px;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.nav-links a:hover {
    background-color: #003399; /* Darker blue for hover */
}

/* Main Content */
main {
    max-width: 900px;
    margin: 40px auto;
    padding: 20px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    color: #0044cc; /* BCH blue */
    margin-bottom: 20px;
}

.update-course form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #333;
}

input[type="text"],
input[type="date"],
input[type="number"],
textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 16px;
}

textarea {
    resize: vertical;
}

input:focus,
textarea:focus {
    outline: none;
    border-color: #0044cc; /* Focus color */
}

.cta-btn {
    background-color: #ffd700; /* BCH golden */
    color: #0044cc; /* BCH blue */
    font-weight: bold;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 18px;
    transition: background-color 0.3s, color 0.3s;
}

.cta-btn:hover {
    background-color: #ffcc00; /* Slightly darker golden */
    color: #003399; /* Darker blue */
}

/* Footer */
footer {
    text-align: center;
    padding: 15px 0;
    background-color: #0044cc; /* BCH blue */
    color: #fff;
    margin-top: 40px;
}

    </style>
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
        <section class="update-course">
            <h2>Update Course</h2>
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
                <button type="submit" class="cta-btn">Update Course</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Bonnie Computer Hub. All rights reserved.</p>
    </footer>
</body>
</html>
