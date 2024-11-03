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

// Initialize error message variable
$error_message = '';

// Check if the form data is set
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and prepare input data
    $name = $conn->real_escape_string(trim($_POST['name']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $course = $conn->real_escape_string(trim($_POST['course']));
    $payment_method = $conn->real_escape_string(trim($_POST['payment-method']));

    // Step 1: Insert user into the users table
    $sql_user = "INSERT INTO users (name, email, role) VALUES ('$name', '$email', 'student')";
    
    if ($conn->query($sql_user) === TRUE) {
        $user_id = $conn->insert_id; // Get the last inserted user ID

        // Step 2: Insert course enrollment into enrollments table
        $sql_course = "INSERT INTO enrollments (student_id, course_id, enrollment_date) 
                       VALUES ('$user_id', (SELECT id FROM courses WHERE course_name='$course'), CURRENT_DATE)";
        
        if ($conn->query($sql_course) === TRUE) {
            // Redirect to confirmation page after successful enrollment
            header("Location: confirmation.html");
            exit();
        } else {
            $error_message = "Error enrolling in course: " . $conn->error;
        }
    } else {
        $error_message = "Error creating user: " . $conn->error;
    }
}

// Fetch courses for the dropdown
$courses = [];
$sql_courses = "SELECT course_name FROM courses";
$result_courses = $conn->query($sql_courses);
if ($result_courses->num_rows > 0) {
    while ($row = $result_courses->fetch_assoc()) {
        $courses[] = $row['course_name'];
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
            <?php if (!empty($error_message)): ?>
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
                        <option value="">-- Select a Course --</option>
                        <?php foreach ($courses as $course_name): ?>
                            <option value="<?php echo $course_name; ?>"><?php echo $course_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="payment-method">Payment Method:</label>
                    <select id="payment-method" name="payment-method" required>
                        <option value="credit-card">M-Pesa</option>
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
