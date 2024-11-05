<?php
// Include database connection
include 'db_connection.php'; // Ensure you have a db_connection.php file for your database connection

// Check if the teacher's ID is set in the URL
if (isset($_GET['teacher_id'])) {
    $teacher_id = $_GET['teacher_id'];

    // Prepare and execute SQL query to fetch teacher's details
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ? AND role = 'teacher'");
    $stmt->bind_param("i", $teacher_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the teacher exists
    if ($result->num_rows > 0) {
        $teacher = $result->fetch_assoc();
    } else {
        // Redirect if no teacher found
        header("Location: error.php"); // Change this to your error page
        exit();
    }
} else {
    // Redirect if no teacher ID is provided
    header("Location: error.php");
    exit();
}

// Close database connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($teacher['name']); ?> - Teacher Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #007BFF;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
        .teacher-profile {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .teacher-profile h2 {
            color: #333;
        }
        .teacher-profile p {
            color: #555;
            line-height: 1.6;
        }
        .action-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .action-link:hover {
            background-color: #0056b3;
        }
        footer {
            text-align: center;
            padding: 10px 0;
            background-color: #333;
            color: white;
            position: absolute;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Teacher Profile</h1>
    </header>

    <div class="teacher-profile">
        <h2><?php echo htmlspecialchars($teacher['name']); ?></h2>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($teacher['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($teacher['phone']); ?></p>
        <p><strong>Biography:</strong> <?php echo nl2br(htmlspecialchars($teacher['biography'])); ?></p>
        <p><strong>Subjects:</strong> <?php echo htmlspecialchars($teacher['subjects']); ?></p>
        <p><strong>Years of Experience:</strong> <?php echo htmlspecialchars($teacher['experience']); ?></p>
        <p><strong>Qualifications:</strong> <?php echo htmlspecialchars($teacher['qualifications']); ?></p>
        
        <a href="teachers_list.php" class="action-link">Back to Teachers List</a> <!-- Link back to the teachers list -->
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Your School Name. All rights reserved.</p>
    </footer>
</body>
</html>
