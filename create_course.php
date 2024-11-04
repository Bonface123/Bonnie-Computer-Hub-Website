<?php
// Start session
session_start();

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

// Fetch student ID from session (assumes you store it during login)
$studentId = $_SESSION['student_id']; // Make sure to set this during login

// Function to get quizzes
function getQuizzes($conn, $studentId) {
    $sql = "
        SELECT q.id AS quiz_id, q.quiz_name, q.description, q.due_date, q.quiz_date
        FROM quizzes q
        JOIN course_quizzes cq ON q.id = cq.quiz_id
        JOIN enrollments e ON cq.course_id = e.course_id
        WHERE e.student_id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Get quizzes for the student
$quizzes = getQuizzes($conn, $studentId);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Bonnie Computer Hub</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Styles */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    color: #333;
}

header {
    background-color: #007bff;
    color: #fff;
    padding: 1rem;
    text-align: center;
}

.logo {
    font-size: 1.5rem;
    font-weight: bold;
}

.nav-links {
    list-style: none;
    padding: 0;
}

.nav-links li {
    display: inline;
    margin: 0 1rem;
}

.nav-links a {
    color: #fff;
    text-decoration: none;
    transition: color 0.3s;
}

.nav-links a:hover {
    color: #ffeb3b;
}

main {
    padding: 2rem;
    max-width: 800px;
    margin: 0 auto;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

h2 {
    color: #007bff;
    margin-bottom: 1.5rem;
}

.create-course, .manage-courses {
    margin-bottom: 2rem;
}

.form-group {
    margin-bottom: 1.2rem;
}

label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: bold;
}

input[type="text"],
input[type="date"],
input[type="number"],
textarea,
select {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 1rem;
}

textarea {
    resize: vertical;
}

.cta-btn {
    background-color: #007bff;
    color: #fff;
    padding: 0.7rem 1.5rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s;
}

.cta-btn:hover {
    background-color: #0056b3;
}

footer {
    text-align: center;
    padding: 1rem;
    background-color: #007bff;
    color: white;
    position: relative;
    bottom: 0;
    width: 100%;
}

    </style>
</head>
<body>
    <header>
        <h1>Welcome to Your Dashboard</h1>
    </header>

    <main>
        <section>
            <h2>Your Quizzes</h2>
            <table class="quiz-table">
                <thead>
                    <tr>
                        <th>Quiz Name</th>
                        <th>Description</th>
                        <th>Due Date</th>
                        <th>Quiz Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($quizzes)): ?>
                        <tr>
                            <td colspan="4">No quizzes available.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($quizzes as $quiz): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($quiz['quiz_name']); ?></td>
                                <td><?php echo htmlspecialchars($quiz['description']); ?></td>
                                <td><?php echo htmlspecialchars($quiz['due_date']); ?></td>
                                <td><?php echo htmlspecialchars($quiz['quiz_date']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Bonnie Computer Hub. All rights reserved.</p>
    </footer>
</body>
</html>
