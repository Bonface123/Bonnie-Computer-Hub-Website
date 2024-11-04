<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin'])) {
    header('Location: teacher_login.php');
    exit;
}

require 'db.php'; // Include the database connection file

// Check if assignment ID is provided
if (!isset($_GET['id'])) {
    echo "Assignment ID not provided!";
    exit;
}

$assignment_id = intval($_GET['id']);

// Fetch the assignment details
$sql = "SELECT title, description, due_date, course_name FROM assignments WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $assignment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Assignment not found!";
    exit;
}

$assignment = $result->fetch_assoc();

// Initialize variables for error messages and status
$titleError = $descriptionError = $dateError = "";
$statusMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize user input
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $due_date = trim($_POST['due_date']);
    $course_name = trim($_POST['course_name']);

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

    // Update the database if valid
    if ($isValid) {
        $sql = "UPDATE assignments SET title = ?, description = ?, due_date = ?, course_name = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $title, $description, $due_date, $course_name, $assignment_id);

        if ($stmt->execute()) {
            $statusMessage = "Assignment updated successfully!";
        } else {
            $statusMessage = "Error updating assignment: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Assignment</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 20px;
}

.container {
    max-width: 600px;
    margin: 0 auto;
    background-color: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #333;
    text-align: center;
}

/* Form Styles */
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
textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type="text"]:focus,
input[type="date"]:focus,
textarea:focus {
    border-color: #007bff;
    outline: none;
}

/* Button Styles */
.btn {
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 10px 15px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #0056b3;
}

/* Error and Status Messages */
.status {
    text-align: center;
    margin: 15px 0;
}

.success {
    color: green;
}

.error {
    color: red;
}

/* Back Link Styles */
.back-link {
    display: inline-block;
    margin-top: 15px;
    color: #007bff;
    text-decoration: none;
}

.back-link:hover {
    text-decoration: underline;
}

/* Error Message Styles */
.error {
    color: red;
    font-size: 0.9em;
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Assignment</h2>

        <?php if ($statusMessage): ?>
            <p class="status <?php echo strpos($statusMessage, 'Error') !== false ? 'error' : 'success'; ?>">
                <?php echo $statusMessage; ?>
            </p>
        <?php endif; ?>

        <form action="edit_assignment.php?id=<?php echo $assignment_id; ?>" method="post">
            <div class="form-group">
                <label for="title">Assignment Title</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($assignment['title']); ?>" required>
                <span class="error"><?php echo $titleError; ?></span>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($assignment['description']); ?></textarea>
                <span class="error"><?php echo $descriptionError; ?></span>
            </div>

            <div class="form-group">
                <label for="due_date">Due Date</label>
                <input type="date" id="due_date" name="due_date" value="<?php echo htmlspecialchars($assignment['due_date']); ?>" required>
                <span class="error"><?php echo $dateError; ?></span>
            </div>

            <div class="form-group">
                <label for="course_name">Course Name</label>
                <input type="text" id="course_name" name="course_name" value="<?php echo htmlspecialchars($assignment['course_name']); ?>" required>
            </div>

            <button type="submit" class="btn">Update Assignment</button>
        </form>

        <a href="view_assignment.php?id=<?php echo $assignment_id; ?>" class="back-link">Back to Assignment</a>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
