<?php
session_start();
require 'db.php'; // Include your database connection file

// Check if the request method is POST and assignment_id is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assignment_id'])) {
    $assignment_id = intval($_POST['assignment_id']); // Get and sanitize the assignment ID

    // Prepare SQL statement to update the assignment status to completed
    $sql = "UPDATE assignments SET status = 'completed' WHERE id = ?";
    $stmt = $conn->prepare($sql);

    // Bind the assignment ID to the SQL statement
    $stmt->bind_param("i", $assignment_id);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Assignment marked as complete successfully!"; // Set success message
    } else {
        $_SESSION['error_message'] = "Error marking assignment complete: " . $stmt->error; // Set error message
    }

    // Redirect to view_assignment.php with the assignment ID
    header("Location: view_assignment.php?id=" . $assignment_id);
    exit; // Ensure no further code is executed after the redirect
} else {
    // Redirect if accessed directly without POST data
    header("Location: teacher_dashboard.php");
    exit;
}
?>
