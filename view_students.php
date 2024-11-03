<?php
// Ensure the user is logged in. If not, redirect to login page.
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: teacher_login.php');
    exit;
}

require 'db.php';

$course_name = $_GET['course']; // Get course name from URL
$sql = "SELECT s.id, s.name, s.email, p.completion_percentage
        FROM users s
        JOIN student_progress p ON s.id = p.id
        JOIN courses c ON p.id = c.id
        WHERE c.course_name = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $course_name);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Students in <?php echo htmlspecialchars($course_name); ?></title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Container Styling */
        body {
            background-color: #f4f4f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
            font-size: 24px;
        }

        /* Table Styling */
        table {
            width: 100%;
            max-width: 800px;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e0e7ff;
        }

        /* Progress Bar Styling */
        .progress-bar {
            background-color: #e0e7ff;
            border-radius: 5px;
            height: 20px;
            width: 100%;
            position: relative;
        }

        .progress-bar-fill {
            background-color: #007bff;
            height: 100%;
            border-radius: 5px;
            text-align: center;
            color: white;
            font-size: 12px;
            line-height: 20px;
        }

        /* Back Link */
        a.back-link {
            text-decoration: none;
            color: #007bff;
            padding: 8px 12px;
            border: 1px solid #007bff;
            border-radius: 4px;
            transition: 0.3s;
        }

        a.back-link:hover {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <h2>Students Enrolled in <?php echo htmlspecialchars($course_name); ?></h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Completion Percentage</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td>
                    <div class="progress-bar">
                        <div class="progress-bar-fill" style="width: <?php echo htmlspecialchars($row['completion_percentage']); ?>%;">
                            <?php echo htmlspecialchars($row['completion_percentage']); ?>%
                        </div>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="teacher_dashboard.php" class="back-link">Back to Dashboard</a>
</body>
</html>
