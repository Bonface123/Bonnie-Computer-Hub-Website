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

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $delete_sql = "DELETE FROM quizzes WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $quiz_id);

    if ($delete_stmt->execute()) {
        header('Location: teacher_dashboard.php');
        exit;
    } else {
        echo "<p class='error'>Error deleting quiz. Please try again.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Quiz</title>
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
            text-align: center;
        }

        h2 {
            color: #333;
        }

        .btn {
            display: inline-block;
            margin: 10px 0;
            padding: 10px 15px;
            color: #fff;
            background-color: #dc3545;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #c82333;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Delete Quiz</h2>
        <p>Are you sure you want to delete the quiz titled "<strong><?php echo htmlspecialchars($quiz['title']); ?></strong>"?</p>
        <form method="POST">
            <button type="submit" class="btn">Delete Quiz</button>
            <a href="view_quiz.php?id=<?php echo $quiz_id; ?>" class="back-link">Cancel</a>
        </form>
    </div>
</body>
</html>
