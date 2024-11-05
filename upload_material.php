<?php
// Ensure the user is logged in. If not, redirect to the login page.
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: teacher_login.php');
    exit;
}

require 'db.php';

$statusMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = $_POST['course_id'];
    $material = $_FILES['material'];

    // Save the file on the server
    $upload_dir = 'uploads/';
    $upload_file = $upload_dir . basename($material['name']);
    $material_name = basename($material['name']); // Get the name of the material

    if (move_uploaded_file($material['tmp_name'], $upload_file)) {
        // Corrected SQL statement to include course_id, material_name, and file_path
        $sql = "INSERT INTO course_materials (course_id, material_name, file_path) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iss', $course_id, $material_name, $upload_file);

        if ($stmt->execute()) {
            $statusMessage = "Material uploaded successfully!";
            $success = true; // Flag to indicate success
        } else {
            $statusMessage = "Error: " . $stmt->error;
            $success = false;
        }
    } else {
        $statusMessage = "File upload failed!";
        $success = false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Course Material</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General page styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    color: #333;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

.container {
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
    max-width: 500px;
    width: 100%;
    box-sizing: border-box;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #007bff;
}

/* Status message styling */
.status {
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 5px;
    text-align: center;
}

.status.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.status.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* Form styling */
.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

input[type="file"],
select {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border-radius: 4px;
    border: 1px solid #ccc;
}

button.btn {
    display: block;
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: #ffffff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

button.btn:hover {
    background-color: #0056b3;
}

.back-link {
    display: block;
    margin-top: 10px;
    text-align: center;
    color: #007bff;
    text-decoration: none;
}

.back-link:hover {
    text-decoration: underline;
}

/* Additional action buttons */
.actions {
    margin-top: 20px;
    text-align: center;
}

.actions p {
    margin-bottom: 10px;
}

.actions a.btn {
    margin: 5px 0;
    display: inline-block;
    width: auto;
    padding: 8px 15px;
    background-color: #28a745;
    color: #ffffff;
    border-radius: 5px;
    text-decoration: none;
}

.actions a.btn:hover {
    background-color: #218838;
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Upload Course Material</h2>

        <?php if ($statusMessage): ?>
            <p class="status <?php echo strpos($statusMessage, 'Error') !== false ? 'error' : 'success'; ?>">
                <?php echo $statusMessage; ?>
            </p>
        <?php endif; ?>

        <?php if (isset($success) && $success): ?>
            <div class="actions">
                <p>What would you like to do next?</p>
                <a href="view_materials.php" class="btn">View Uploaded Materials</a>
                <a href="upload_material.php" class="btn">Upload More Materials</a>
                <a href="course_dashboard.php" class="btn">Go to Course Dashboard</a>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="course_id">Select Course:</label>
                <select id="course_id" name="course_id" required>
                    <?php
                    $sql = "SELECT * FROM courses";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['course_name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="material">Upload Material:</label>
                <input type="file" id="material" name="material" required>
            </div>
            <button type="submit" class="btn">Upload Material</button>
        </form>

        <a href="teacher_dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
