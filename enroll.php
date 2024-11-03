<?php
// enroll.php

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

// Check if the form data is set
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $course = $conn->real_escape_string($_POST['course']); // Assuming course is coursename
    $payment_method = $conn->real_escape_string($_POST['payment-method']);

    // Assuming you have a description, start_date, and end_date. You can set them as needed.
    $description = ''; // Placeholder, adjust based on your requirements
    $start_date = date('Y-m-d'); // Example: current date, modify as needed
    $end_date = date('Y-m-d', strtotime('+1 month')); // Example: one month later, modify as needed
    $teacher_id = 1; // Example: fixed teacher ID, modify as needed or retrieve from another source

    // SQL insert statement
    $sql = "INSERT INTO courses (course_name, description, start_date, end_date, teacher_id) 
            VALUES ('$course', '$description', '$start_date', '$end_date', '$teacher_id')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to confirmation page after successful enrollment
        header("Location: confirmation.html");
        exit();
    } else {
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll in Course | Bonnie Computer Hub</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav class="container">
          <div class="logo">
            <a href="index.html">BONNIE COMPUTER HUB - BCH</a>
          </div>
          <ul class="nav-links">
            <li><a href="course-details.php">Home</a></li>
            <li><a href="courses.html" class="active">Courses</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
          <a href="enroll.html" class="cta-btn" aria-label="Enroll Now">Enroll Now</a>
        </nav>
    </header>

    <section class="enroll">
        <div class="container">
            <h2>Enroll in Course</h2>
            <p>Please fill in the details below to enroll in the course.</p>
            <?php if (isset($error_message)): ?>
                <div class="error"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form id="enrollment-form" method="POST">
                <div class="form-group">
                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="course">Select Course:</label>
                    <select id="course" name="course" required>
                        <option value="HTML Basics">HTML Basics</option>
                        <option value="CSS Fundamentals">CSS Fundamentals</option>
                        <option value="JavaScript Essentials">JavaScript Essentials</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="payment-method">Payment Method:</label>
                    <select id="payment-method" name="payment-method" required>
                        <option value="credit-card">Credit Card</option>
                        <option value="paypal">PayPal</option>
                        <option value="bank-transfer">Bank Transfer</option>
                    </select>
                </div>
                <button type="submit" class="cta-btn">Enroll Now</button>
            </form>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2024 Bonnie Computer Hub. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
