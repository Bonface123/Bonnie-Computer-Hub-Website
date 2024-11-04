<?php
session_start();
require 'db.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['loggedin'])) {
    header('Location: teacher_login.php');
    exit;
}

// Get the quiz ID from the URL
$quiz_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the quiz details
$sql = "SELECT * FROM quizzes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$result = $stmt->get_result();
$quiz = $result->fetch_assoc();

if (!$quiz) {
    echo "<p class='error'>Quiz not found!</p>";
    exit;
}

// Handle form submission for editing the quiz
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $course_id = $_POST['course_id'];

    $update_sql = "UPDATE quizzes SET title = ?, description = ?, due_date = ?, course_id = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssii", $title, $description, $due_date, $course_id, $quiz_id);

    if ($update_stmt->execute()) {
        header('Location: view_quiz.php?id=' . $quiz_id);
        exit;
    } else {
        echo "<p class='error'>Error updating quiz. Please try again.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Quiz</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            text-align: center;
        }

        input[type="text"],
        input[type="date"],
        textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .btn {
            display: inline-block;
            margin: 10px 0;
            padding: 10px 15px;
            color: #fff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            text-align: center;
            font-weight: bold;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #6c757d;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Quiz</h2>
        <form method="POST">
            <input type="text" name="title" value="<?php echo htmlspecialchars($quiz['title']); ?>" required placeholder="Quiz Title">
            <textarea name="description" required placeholder="Quiz Description"><?php echo htmlspecialchars($quiz['description']); ?></textarea>
            <input type="date" name="due_date" value="<?php echo htmlspecialchars($quiz['due_date']); ?>" required>
            <input type="text" name="course_id" value="<?php echo htmlspecialchars($quiz['course_id']); ?>" required placeholder="Course ID">
            <button type="submit" class="btn">Update Quiz</button>
            <a href="view_quiz.php?id=<?php echo $quiz_id; ?>" class="back-link">Cancel</a>
        </form>
    </div>
</body>
</html>
