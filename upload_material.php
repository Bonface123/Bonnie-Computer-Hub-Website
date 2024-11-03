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
    $course_id = $_POST['id'];
    $material = $_FILES['material'];

    // Save the file in the server
    $upload_dir = 'uploads/';
    $upload_file = $upload_dir . basename($material['name']);

    if (move_uploaded_file($material['tmp_name'], $upload_file)) {
        $sql = "INSERT INTO course_materials (course_id, material_file) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('is', $id, $upload_file);
        if ($stmt->execute()) {
            $statusMessage = "Material uploaded successfully!";
        } else {
            $statusMessage = "Error: " . $stmt->error;
        }
    } else {
        $statusMessage = "File upload failed!";
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
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 20px;
}

.container {
    max-width: 600px;
    margin: auto;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    color: #333;
}

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
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

.status {
    margin: 10px 0;
    padding: 10px;
    border-radius: 4px;
}

.success {
    background-color: #dff0d8;
    color: #3c763d;
}

.error {
    background-color: #f2dede;
    color: #a94442;
}

.btn {
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.btn:hover {
    background-color: #0056b3;
}

.back-link {
    display: block;
    text-align: center;
    margin-top: 15px;
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
        <h2>Upload Course Material</h2>

        <?php if ($statusMessage): ?>
            <p class="status <?php echo strpos($statusMessage, 'Error') !== false ? 'error' : 'success'; ?>">
                <?php echo $statusMessage; ?>
            </p>
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
