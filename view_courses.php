<?php
// view_courses.php

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

// Fetch courses
$sql = "SELECT * FROM courses";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Courses - Bonnie Computer Hub</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* styles.css */

/* General styles */
body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4; /* Light background for the entire page */
}

header {
    background: #007BFF; /* Blue header background */
    color: #fff;
    padding: 10px 0;
    text-align: center;
}

header .logo {
    font-size: 24px;
    font-weight: bold;
}

nav {
    display: flex;
    justify-content: center;
    align-items: center;
}

nav ul {
    list-style: none;
    padding: 0;
}

nav ul li {
    display: inline;
    margin: 0 15px;
}

nav ul li a {
    color: #fff;
    text-decoration: none;
}

/* Main section styling */
main {
    padding: 20px;
}

.view-courses {
    background: #fff; /* White background for the course table section */
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Light shadow effect */
    padding: 20px;
}

/* Table styling */
table {
    width: 100%; /* Full width */
    border-collapse: collapse; /* Remove gaps between cells */
    margin: 20px 0;
}

th, td {
    padding: 12px; /* Padding inside table cells */
    text-align: left; /* Align text to the left */
    border-bottom: 1px solid #ddd; /* Border between rows */
}

th {
    background-color: #007BFF; /* Header background color */
    color: white; /* Header text color */
    font-weight: bold;
}

tr:hover {
    background-color: #f1f1f1; /* Change row background on hover */
}

a {
    color: #007BFF; /* Link color */
    text-decoration: none; /* Remove underline from links */
}

a:hover {
    text-decoration: underline; /* Underline on hover */
}

/* Footer styling */
footer {
    text-align: center;
    padding: 10px 0;
    background: #007BFF; /* Blue footer background */
    color: white;
    position: relative;
    bottom: 0;
    width: 100%;
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
        <section class="view-courses">
            <h2>Available Courses</h2>
            <table>
                <thead>
                    <tr>
                        <th>Course Name</th>
                        <th>Description</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Teacher ID</th>
                        <th>Actions</th> <!-- New Actions Column -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['course_name']}</td>
                                    <td>{$row['description']}</td>
                                    <td>{$row['start_date']}</td>
                                    <td>{$row['end_date']}</td>
                                    <td>{$row['teacher_id']}</td>
                                    <td>
                                        <a href='update_course.php?id={$row['id']}'>Edit</a> | 
                                        <a href='delete_course.php?id={$row['id']}'>Delete</a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No courses available</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Bonnie Computer Hub. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
