<?php
// delete_course.php

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

// Check if the course ID is set
if (isset($_GET['id'])) {
    $course_id = $_GET['id'];
    
    // Prepare and execute the delete statement
    $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->bind_param("i", $course_id);
    
    if ($stmt->execute()) {
        echo "Course deleted successfully";
        header("Location: view_courses.php"); // Redirect back to the view courses page
        exit;
    } else {
        echo "Error deleting course: " . $stmt->error;
    }
    
    $stmt->close();
} else {
    echo "Invalid course ID.";
}

$conn->close(); // Close the database connection
?>
