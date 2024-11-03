<?php
session_start();
require 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin'])) {
    header('Location: teacher_login.php');
    exit;
}

$teacher_id = $_SESSION['id'] ?? null;
$statusMessage = "";

// Insert quiz if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $due_date = trim($_POST['due_date']);
    $due_time = trim($_POST['due_time']);
    $course_id = intval($_POST['course_id']);

    // Combine due_date and due_time into a single datetime string
    $due_datetime = $due_date . ' ' . $due_time;

    // Update SQL to insert due_datetime
    $sql = "INSERT INTO quizzes (title, description, due_date, course_id, teacher_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $title, $description, $due_datetime, $course_id, $teacher_id);

    if ($stmt->execute()) {
        $statusMessage = "Quiz created successfully!";
    } else {
        $statusMessage = "Error creating quiz: " . $conn->error;
    }
    $_SESSION['statusMessage'] = $statusMessage;
    header("Location: create_quiz.php");
    exit;
}

// Retrieve quizzes for this teacher
$sql = "SELECT * FROM quizzes WHERE teacher_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if quizzes are being retrieved
$quizzes = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $quizzes[] = $row;
    }
} else {
    $statusMessage = "No quizzes found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Quiz</title>
    <link rel="stylesheet" href="styles.css">
    <style>

        /* Basic Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    color: #333;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

/* Container */
.container {
    width: 90%;
    max-width: 800px;
    background-color: #fff;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Headings */
h2 {
    font-size: 24px;
    color: #333;
    text-align: center;
    margin-bottom: 1.5rem;
}

h3 {
    font-size: 20px;
    margin: 1.5rem 0;
    color: #555;
}

/* Status Message */
.status {
    color: #4CAF50;
    font-weight: bold;
    text-align: center;
    margin-bottom: 1rem;
}

/* Form Styles */
form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

label {
    font-weight: bold;
    margin-bottom: 0.5rem;
    color: #333;
}

input[type="text"],
input[type="date"],
input[type="time"],
input[type="number"],
textarea {
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    width: 100%;
    outline: none;
}

textarea {
    resize: vertical;
}

/* Button */
button[type="submit"] {
    padding: 0.75rem;
    font-size: 16px;
    color: #fff;
    background-color: #007bff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
    width: 100%;
}

button[type="submit"]:hover {
    background-color: #0056b3;
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
}

thead th {
    background-color: #007bff;
    color: #fff;
    padding: 0.75rem;
    text-align: left;
    font-size: 16px;
}

tbody td {
    padding: 0.75rem;
    border-bottom: 1px solid #ddd;
}

tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

/* Links */
a {
    color: #007bff;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

a:visited {
    color: #0056b3;
}

/* Back to Dashboard Link */
a[href="teacher_dashboard.php"] {
    display: inline-block;
    margin-top: 1.5rem;
    font-size: 16px;
    color: #333;
}
    </style>
</head>
<body>
    <div class="container">
        <h2>Create a New Quiz</h2>

        <?php if ($statusMessage): ?>
            <p class="status"><?php echo $statusMessage; ?></p>
        <?php endif; ?>

        <form action="create_quiz.php" method="post">
            <div class="form-group">
                <label for="title">Quiz Title</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="5" required></textarea>
            </div>

            <div class="form-group">
                <label for="due_date">Due Date</label>
                <input type="date" id="due_date" name="due_date" required>
            </div>

            <div class="form-group">
                <label for="due_time">Due Time</label>
                <input type="time" id="due_time" name="due_time" required>
            </div>

            <div class="form-group">
                <label for="course_id">Course ID</label>
                <input type="number" id="course_id" name="course_id" required>
            </div>

            <button type="submit">Create Quiz</button>
        </form>

        <h3>Your Quizzes</h3>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Due Date and Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($quizzes) > 0): ?>
                    <?php foreach ($quizzes as $quiz): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($quiz['title']); ?></td>
                            <td><?php echo htmlspecialchars($quiz['description']); ?></td>
                            <td><?php echo htmlspecialchars(date("Y-m-d H:i", strtotime($quiz['due_date']))); ?></td>
                            <td>
                                <a href="edit_quiz.php?id=<?php echo $quiz['id']; ?>">Edit</a>
                                <a href="delete_quiz.php?id=<?php echo $quiz['id']; ?>" onclick="return confirm('Are you sure you want to delete this quiz?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4">No quizzes created yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="teacher_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
