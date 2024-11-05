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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        td {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

    </style>
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
                <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                <td><?php echo htmlspecialchars($row['average_grade']); ?></td>
                <td><?php echo htmlspecialchars($row['completion_rate']); ?>%</td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="teacher_dashboard.php">Back to Dashboard</a>
</body>
</html>
