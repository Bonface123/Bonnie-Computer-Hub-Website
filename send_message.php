<?php
// Ensure the user is logged in. If not, redirect to the login page.
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: teacher_login.php');
    exit;
}

require 'db.php';

$messageSent = false; // Flag to check if message was sent

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    $student_id = $_POST['student_id']; // Get student ID from a dropdown or similar

    $sql = "INSERT INTO messages (student_id, message) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $student_id, $message);
    
    if ($stmt->execute()) {
        $messageSent = true; // Set flag if successful
    } else {
        $error = "Error: " . $stmt->error; // Capture error message
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Message</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa; /* Light background color */
    margin: 0;
    padding: 20px;
}

.container {
    max-width: 600px; /* Set a max width for the form */
    margin: 0 auto; /* Center the form */
    padding: 20px;
    background-color: white; /* White background for the form */
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

h2 {
    color: goldenrod; /* Heading color */
    margin-bottom: 20px;
}

label {
    display: block;
    margin: 10px 0 5px;
    font-weight: bold; /* Bold labels */
}

select, textarea {
    width: 100%; /* Full-width inputs */
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-bottom: 15px; /* Space below inputs */
}

textarea {
    resize: vertical; /* Allow vertical resizing only */
}

.submit-button {
    background-color: #007bff; /* Button color */
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.submit-button:hover {
    background-color: #0056b3; /* Darker on hover */
}

.success-message, .error-message {
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 20px;
}

.success-message {
    background-color: #d4edda; /* Green background for success */
    color: #155724; /* Dark green text */
}

.error-message {
    background-color: #f8d7da; /* Red background for error */
    color: #721c24; /* Dark red text */
}

.back-link {
    display: inline-block;
    margin-top: 20px;
    color: #007bff; /* Link color */
    text-decoration: none;
}

.back-link:hover {
    text-decoration: underline; /* Underline on hover */
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Send a Message to Students</h2>
        
        <?php if (isset($messageSent) && $messageSent): ?>
            <div class="success-message">Message sent successfully!</div>
        <?php elseif (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form action="" method="POST" class="message-form">
            <label for="student_id">Select Student:</label>
            <select id="student_id" name="student_id" required>
                <option value="">-- Select a Student --</option>
                <?php
                $sql = "SELECT * FROM users where role = 'student'";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                <?php endwhile; ?>
            </select>

            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="4" required></textarea>

            <button type="submit" class="submit-button">Send Message</button>
        </form>
        
        <a href="teacher_dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
