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
$titleError = $descriptionError = $dateError = $statusMessage = "";

// Fetch existing quiz data
if ($quiz_id > 0) {
    $sql = "SELECT * FROM quizzes WHERE id = ? AND teacher_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $quiz_id, $teacher_id);
    $stmt->execute();
    $quiz = $stmt->get_result()->fetch_assoc();
}

// Handle form submission for updating quiz
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $due_date = trim($_POST['due_date']);

    if ($title && $description && $due_date) {
        $sql = "UPDATE quizzes SET title = ?, description = ?, due_date = ? WHERE id = ? AND teacher_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssii", $title, $description, $due_date, $quiz_id, $teacher_id);

        if ($stmt->execute()) {
            $statusMessage = "Quiz updated successfully!";
            header('Location: create_quiz.php');
        } else {
            $statusMessage = "Error updating quiz: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Quiz</title>
</head>
<body>
    <h2>Edit Quiz</h2>
    <?php if ($statusMessage): ?>
        <p><?php echo $statusMessage; ?></p>
    <?php endif; ?>

    <form action="edit_quiz.php?id=<?php echo $quiz_id; ?>" method="post">
        <label>Title</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($quiz['title']); ?>" required>

        <label>Description</label>
        <textarea name="description" required><?php echo htmlspecialchars($quiz['description']); ?></textarea>

        <label>Due Date</label>
        <input type="date" name="due_date" value="<?php echo htmlspecialchars($quiz['due_date']); ?>" required>

        <button type="submit">Update Quiz</button>
    </form>
</body>
</html>
