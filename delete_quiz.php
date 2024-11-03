<?php
session_start();
require 'db.php';

/// Check if the user is logged in
if (!isset($_SESSION['loggedin'])) {
    header('Location: teacher_login.php');
    exit;
}

$teacher_id = $_SESSION['id'] ?? null;

$statusMessage = "";

$teacher_id = $_SESSION['id'];
$quiz_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($quiz_id > 0) {
    $sql = "DELETE FROM quizzes WHERE id = ? AND teacher_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $quiz_id, $teacher_id);

    if ($stmt->execute()) {
        $_SESSION['statusMessage'] = "Quiz deleted successfully!";
    } else {
        $_SESSION['statusMessage'] = "Error deleting quiz: " . $conn->error;
    }
}

header("Location: create_quiz.php");
exit;
?>
