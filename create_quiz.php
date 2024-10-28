<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin'])) {
    header('Location: teacher_login.php');
    exit;
}

require 'db.php'; // Include the database connection file

// Initialize variables for error messages and status
$titleError = $descriptionError = $dateError = "";
$statusMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize user input
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $due_date = trim($_POST['due_date']);
    $course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : null;

    // Validate the inputs
    $isValid = true;

    if (empty($title)) {
        $titleError = "Quiz title is required.";
        $isValid = false;
    }

    if (empty($description)) {
        $descriptionError = "Description is required.";
        $isValid = false;
    }

    if (empty($due_date)) {
        $dateError = "Due date is required.";
        $isValid = false;
    }

    // Insert data into database if valid
    if ($isValid) {
        $sql = "INSERT INTO quizzes (title, description, due_date, course_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $title, $description, $due_date, $course_id);

        if ($stmt->execute()) {
            $statusMessage = "Quiz created successfully!";
        } else {
            $statusMessage = "Error creating quiz: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quiz</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        * {
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 20px;
}

.container {
    max-width: 600px;
    margin: auto;
    padding: 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    color: #333;
}

.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

input[type="text"],
input[type="date"],
input[type="number"],
textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
}

input[type="text"]:focus,
input[type="date"]:focus,
input[type="number"]:focus,
textarea:focus {
    border-color: #007BFF;
    outline: none;
}

.error {
    color: red;
    font-size: 12px;
}

button {
    width: 100%;
    padding: 10px;
    background-color: #007BFF;
    border: none;
    color: white;
    font-size: 16px;
    cursor: pointer;
    border-radius: 4px;
}

button:hover {
    background-color: #0056b3;
}

.status {
    text-align: center;
    color: green;
    margin-bottom: 20px;
}

.back-link {
    display: inline-block;
    margin-top: 20px;
    text-align: center;
    color: #007BFF;
    text-decoration: none;
}

.back-link:hover {
    text-decoration: underline;
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
                <span class="error"><?php echo $titleError; ?></span>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="5" required></textarea>
                <span class="error"><?php echo $descriptionError; ?></span>
            </div>

            <div class="form-group">
                <label for="due_date">Due Date</label>
                <input type="date" id="due_date" name="due_date" required>
                <span class="error"><?php echo $dateError; ?></span>
            </div>

            <div class="form-group">
                <label for="course_id">Course ID</label>
                <input type="number" id="course_id" name="course_id" required>
            </div>

            <button type="submit">Create Quiz</button>
        </form>

        <a href="teacher_dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
