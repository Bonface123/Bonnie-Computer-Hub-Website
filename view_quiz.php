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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Quiz</title>
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

        p {
            font-size: 16px;
            line-height: 1.5;
            margin: 10px 0;
        }

        strong {
            color: #555;
        }

        .btn, .back-link {
            display: inline-block;
            margin: 10px 0;
            padding: 10px 15px;
            color: #fff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn:hover, .back-link:hover {
            background-color: #0056b3;
        }

        .delete {
            background-color: #dc3545;
        }

        .delete:hover {
            background-color: #c82333;
        }

        .back-link {
            background-color: #6c757d;
        }

        .back-link:hover {
            background-color: #5a6268;
        }

        .error {
            color: red;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><?php echo htmlspecialchars($quiz['title']); ?></h2>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($quiz['description']); ?></p>
        <p><strong>Due Date:</strong> <?php echo htmlspecialchars($quiz['due_date']); ?></p>
        <p><strong>Course ID:</strong> <?php echo htmlspecialchars($quiz['course_id']); ?></p>

        <a href="edit_quiz.php?id=<?php echo $quiz_id; ?>" class="btn">Edit Quiz</a>
        <a href="delete_quiz.php?id=<?php echo $quiz_id; ?>" class="btn delete">Delete Quiz</a>
        <a href="create_quiz.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
