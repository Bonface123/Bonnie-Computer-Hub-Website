<?php
// Include database connection
require 'db.php';

// Check if the course parameter is set in the URL
if (isset($_GET['course'])) {
    $courseName = $_GET['course'];

    // Prepare and execute the query to get students enrolled in the specified course
    $query = "SELECT * FROM users WHERE course_id = (SELECT id FROM courses WHERE course_name = ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $courseName);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch all students
    $students = $result->fetch_all(MYSQLI_ASSOC);
} else {
    // Redirect if no course is specified
    header('Location: teacher_dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students - <?php echo htmlspecialchars($courseName); ?></title>
    <link rel="stylesheet" href="styles.css"> <!-- Add your stylesheet here -->
    <style>
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 0;
            color: #fff;
            background-color: #007bff;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1>Students Enrolled in <?php echo htmlspecialchars($courseName); ?></h1>
    </header>

    <div class="container">
        <a href="create_course.php" class="btn">Create Course</a>
        <a href="teacher_dashboard.php" class="btn">Back to Dashboard</a>

        <?php if (count($students) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Enrollment Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['id']); ?></td>
                            <td><?php echo htmlspecialchars($student['name']); ?></td>
                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                            <td><?php echo htmlspecialchars($student['enrollment_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No students are enrolled in this course.</p>
        <?php endif; ?>
    </div>
</body>
</html>
