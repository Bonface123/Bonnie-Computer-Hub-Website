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

// Fetch courses to populate the dropdown
$courses = [];
$sql = "SELECT id, course_name FROM courses";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}

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
        $sql = "INSERT INTO quizzes (title, description, due_date, course_id, created_at) VALUES (?, ?, ?, ?, NOW())";
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
        <style>
        /* Styling (unchanged from previous code) */
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        /* Container styling */
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Form heading */
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        /* Form group styling */
        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"],
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        /* Button styling */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            margin-top: 10px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        /* Success and error messages */
        .status {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Back link */
        .back-link {
            display: block;
            margin-top: 15px;
            text-align: center;
            color: #007bff;
            text-decoration: none;
        }

        .back-link:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create a New Quiz</h2>

        <?php if ($statusMessage): ?>
            <p class="status <?php echo strpos($statusMessage, 'Error') !== false ? 'error' : 'success'; ?>">
                <?php echo $statusMessage; ?>
            </p>
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
                <label for="course_id">Course Name</label>
                <select id="course_id" name="course_id" required>
                    <option value="">Select Course</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?php echo $course['id']; ?>"><?php echo $course['course_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn">Create Quiz</button>
        </form>

        <!-- Additional Actions after Quiz Creation -->
        <?php if ($statusMessage && strpos($statusMessage, 'success') !== false): ?>
            <div class="actions">
                <h3>What would you like to do next?</h3>
                <a href="view_quiz.php?id=<?php echo $stmt->insert_id; ?>" class="btn">View Quiz</a>
                <a href="edit_quiz.php?id=<?php echo $stmt->insert_id; ?>" class="btn">Edit Quiz</a>
                <a href="delete_quiz.php?id=<?php echo $stmt->insert_id; ?>" class="btn delete">Delete Quiz</a>
            </div>
        <?php endif; ?>

        <a href="teacher_dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
