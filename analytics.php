<?php
// Ensure the user is logged in. If not, redirect to login page.
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: teacher_login.php');
    exit;
}

require 'db.php';

$sql = "SELECT c.course_name, a.average_grade, a.completion_rate
        FROM analytics a
        JOIN courses c ON a.id = c.id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Analytics</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Course Analytics</h2>
    <table>
        <tr>
            <th>Course Name</th>
            <th>Average Grade</th>
            <th>Completion Rate</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['course_name']; ?></td>
                <td><?php echo $row['average_grade']; ?></td>
                <td><?php echo $row['completion_rate']; ?>%</td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="teacher_dashboard.php">Back to Dashboard</a>
</body>
</html>
