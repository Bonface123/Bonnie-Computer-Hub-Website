<?php
session_start();
require 'db.php'; // Include your database connection file

// Assume you have logic to fetch the assignment details based on the provided ID
if (isset($_GET['id'])) {
    $assignment_id = intval($_GET['id']);
    $sql = "SELECT * FROM assignments WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $assignment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $assignment = $result->fetch_assoc();

    if (!$assignment) {
        die("Assignment not found.");
    }
} else {
    die("No assignment ID provided.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Assignment</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include your CSS file for styling -->
    <style>
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .assignment-details {
            margin-bottom: 20px;
        }

        .assignment-details p {
            font-size: 1rem;
            line-height: 1.6;
            margin: 5px 0;
        }

        .actions {
            text-align: center;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            margin-top: 10px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn.delete {
            background-color: #dc3545;
        }

        .btn.delete:hover {
            background-color: #c82333;
        }

        .btn.complete {
            background-color: #28a745;
        }

        .btn.complete:hover {
            background-color: #218838;
        }

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
        <h2>Assignment Details</h2>

        <div class="assignment-details">
            <p><strong>Title:</strong> <?php echo htmlspecialchars($assignment['title']); ?></p>
            <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($assignment['description'])); ?></p>
            <p><strong>Due Date:</strong> <?php echo htmlspecialchars($assignment['due_date']); ?></p>
            <p><strong>Course Name:</strong> <?php echo htmlspecialchars($assignment['course_name']); ?></p>
            <p><strong>Created At:</strong> <?php echo htmlspecialchars($assignment['created_at']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($assignment['status']); ?></p>
        </div>

        <div class="actions">
            <a href="edit_assignment.php?id=<?php echo $assignment_id; ?>" class="btn">Edit</a>
            <a href="delete_assignment.php?id=<?php echo $assignment_id; ?>" class="btn delete" onclick="return confirm('Are you sure you want to delete this assignment?');">Delete</a>
            <form action="mark_complete.php" method="post" style="display:inline;">
                <input type="hidden" name="assignment_id" value="<?php echo $assignment['id']; ?>">
                <button type="submit" class="btn complete">Mark as Complete</button>
            </form>
        </div>

        <a href="teacher_dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
