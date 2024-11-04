<?php
session_start();
require 'db.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['loggedin'])) {
    header('Location: teacher_login.php');
    exit;
}

// Fetch assignments created by the teacher
$sql = "SELECT * FROM assignments WHERE teacher_id = ?"; // Assuming you have a teacher_id field
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['id']); // Assuming the user ID is stored in the session
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Assignments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Assignments</h2>
        <table>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Due Date</th>
                <th>Actions</th>
            </tr>
            <?php while ($assignment = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($assignment['title']); ?></td>
                <td><?php echo htmlspecialchars($assignment['description']); ?></td>
                <td><?php echo htmlspecialchars($assignment['due_date']); ?></td>
                <td>
                    <a href="edit_assignment.php?id=<?php echo $assignment['id']; ?>" class="action-link">Edit</a>
                    <a href="delete_assignment.php?id=<?php echo $assignment['id']; ?>" class="action-link delete">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <a href="teacher_dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
