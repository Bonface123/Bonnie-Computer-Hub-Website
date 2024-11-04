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

// Delete the assignment from the database
$sql = "DELETE FROM assignments WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $assignment_id);

if ($stmt->execute()) {
    header('Location: teacher_dashboard.php?message=Assignment deleted successfully');
} else {
    echo "Error deleting assignment: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
