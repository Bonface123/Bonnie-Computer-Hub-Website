<?php
// Ensure the user is logged in. If not, redirect to the login page.
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: teacher_login.php');
    exit;
}

require 'db.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Course Materials</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 800px;
    margin: 50px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

/* Table styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
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

/* Button styles */
.btn {
    display: inline-block;
    padding: 8px 16px;
    background-color: #28a745;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #218838;
}

/* Back link */
.back-link {
    display: inline-block;
    margin-top: 20px;
    color: #007bff;
    text-decoration: none;
    font-weight: bold;
}

.back-link:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Uploaded Course Materials</h2>

        <table>
            <thead>
                <tr>
                    <th>Material Name</th>
                    <th>Course</th>
                    <th>Uploaded On</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT cm.id, cm.material_name, cm.file_path, cm.created_at, c.course_name
                        FROM course_materials cm
                        JOIN courses c ON cm.course_id = c.id";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['material_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['course_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                        echo "<td><a href='" . htmlspecialchars($row['file_path']) . "' download class='btn'>Download</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No materials have been uploaded yet.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <a href="teacher_dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>