<?php
session_start();

// Check if the teacher is logged in
if (!isset($_SESSION['loggedin'])) {
    header('Location: teacher_login.php');
    exit;
}

require 'db.php'; // Database connection file

// Retrieve pending assignments (those with no grade yet)
$sql = "SELECT a.id AS assignment_id, a.title, u.name AS student, s.submitted_on 
        FROM submissions s
        JOIN assignments a ON s.assignment_id = a.id
        JOIN users u ON s.student_id = u.id
        WHERE s.grade IS NULL";

$result = $conn->query($sql);
$pendingAssignments = [];

// Fetch each pending assignment
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pendingAssignments[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Assignments</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9; /* Light background color */
            margin: 0;
            padding: 20px;
            color: #333; /* Dark text color */
        }

        h2 {
            color: #0056b3; /* Primary brand color */
            margin-bottom: 20px;
        }

        .section {
            background-color: #fff; /* White background for the assignments section */
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow effect */
            padding: 20px;
            margin-bottom: 20px;
        }

        .assignment-card {
            border: 1px solid #ddd; /* Light border for assignment cards */
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px; /* Space between assignment cards */
            transition: box-shadow 0.3s; /* Transition effect */
        }

        .assignment-card:hover {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Shadow on hover */
        }

        .action-link {
            text-decoration: none;
            color: #007acc; /* Link color */
            font-weight: bold;
            border: 2px solid #007acc; /* Button border */
            border-radius: 5px; /* Rounded corners */
            padding: 10px 15px; /* Padding around the button */
            display: inline-block; /* Block level for padding */
            margin-top: 10px; /* Space above the link */
            transition: background-color 0.3s, color 0.3s; /* Smooth transition */
        }

        .action-link:hover {
            background-color: #007acc; /* Change background on hover */
            color: #ffffff; /* Change text color to white on hover */
        }

        a.back-link {
            display: inline-block; /* Block level for padding */
            margin-top: 20px; /* Space above the link */
            text-decoration: none;
            color: #0056b3; /* Brand color */
            font-weight: bold; /* Bold text */
            padding: 10px 15px; /* Padding around the link */
            border: 2px solid #0056b3; /* Blue border */
            border-radius: 5px; /* Rounded corners */
            transition: background-color 0.3s, color 0.3s; /* Smooth transition */
        }

        a.back-link:hover {
            background-color: #0056b3; /* Change background color on hover */
            color: #ffffff; /* Change text color to white on hover */
        }
    </style>
</head>
<body>
    <h2>Pending Assignments to Grade</h2>
    <div class="section assignments">
        <?php if (empty($pendingAssignments)): ?>
            <p>No pending assignments to grade.</p>
        <?php else: ?>
            <?php foreach ($pendingAssignments as $assignment): ?>
                <div class="assignment-card">
                    <h3><?php echo htmlspecialchars($assignment['title']); ?></h3>
                    <p>Submitted by: <?php echo htmlspecialchars($assignment['student']); ?></p>
                    <p>Submitted on: <?php echo htmlspecialchars($assignment['submitted_on']); ?></p>
                    <p>
                        <a href="grade-assignment.php?assignment_id=<?php echo urlencode($assignment['assignment_id']); ?>&student=<?php echo urlencode($assignment['student']); ?>" class="action-link">
                            Grade Assignment
                        </a>
                    </p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <a href="teacher_dashboard.php" class="back-link">Back to Dashboard</a>
</body>
</html>
