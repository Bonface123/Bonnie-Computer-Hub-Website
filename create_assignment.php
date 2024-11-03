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
        $titleError = "Title is required.";
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
        $sql = "INSERT INTO assignments (title, description, due_date, course_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $title, $description, $due_date, $course_id);

        if ($stmt->execute()) {
            $statusMessage = "Assignment created successfully!";
        } else {
            $statusMessage = "Error creating assignment: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Assignment</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 20px;
}

.container {
    max-width: 600px;
    margin: auto;
    background: white;
    padding: 20px;
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
    box-sizing: border-box;
}

textarea {
    resize: vertical;
}

.error {
    color: red;
    font-size: 0.9em;
}

.status {
    margin: 10px 0;
    padding: 10px;
    border-radius: 4px;
}

.success {
    background-color: #dff0d8;
    color: #3c763d;
}

.error {
    background-color: #f2dede;
    color: #a94442;
}

.btn {
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.btn:hover {
    background-color: #0056b3;
}

.back-link {
    display: block;
    text-align: center;
    margin-top: 15px;
    color: #007bff;
    text-decoration: none;
}

.back-link:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Create a New Assignment</h2>

        <?php if ($statusMessage): ?>
            <p class="status <?php echo strpos($statusMessage, 'Error') !== false ? 'error' : 'success'; ?>">
                <?php echo $statusMessage; ?>
            </p>
        <?php endif; ?>

        <form action="create_assignment.php" method="post">
            <div class="form-group">
                <label for="title">Assignment Title</label>
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

            <button type="submit" class="btn">Create Assignment</button>
        </form>

        <a href="teacher_dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
