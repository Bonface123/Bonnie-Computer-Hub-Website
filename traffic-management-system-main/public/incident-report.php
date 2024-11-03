<?php
// public/incident-report.php
include_once '../includes/header.php';
include_once '../includes/config.php'; // Database connection

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $location = $_POST['location'];
    $incident_type = $_POST['incident_type'];
    $description = $_POST['description'];

    // Prepare SQL to insert the incident report into the database
    $query = "INSERT INTO incidents (location, incident_type, description, report_time) VALUES (:location, :incident_type, :description, NOW())";
    $stmt = $pdo->prepare($query);

    // Bind values to prevent SQL injection
    $stmt->bindParam(':location', $location);
    $stmt->bindParam(':incident_type', $incident_type);
    $stmt->bindParam(':description', $description);

    if ($stmt->execute()) {
        $message = "Incident reported successfully!";
    } else {
        $message = "Failed to report the incident. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report an Incident</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        /* Reset some basic styles */
body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
}

/* Main container */
main {
    max-width: 600px; /* Adjusted max width for smaller screens */
    margin: 20px auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* Heading styles */
h2 {
    text-align: center;
    color: #333;
}

/* Form styles */
.incident-form {
    display: flex;
    flex-direction: column;
    gap: 15px; /* Space between form elements */
}

.incident-form label {
    font-weight: bold;
    color: #555;
}

.incident-form input,
.incident-form select,
.incident-form textarea {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
}

.incident-form button {
    padding: 10px;
    border: none;
    border-radius: 4px;
    background-color: #007bff; /* Blue background */
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.incident-form button:hover {
    background-color: #0056b3; /* Darker blue on hover */
}

/* Message styles */
p {
    text-align: center;
    color: #28a745; /* Green for success messages */
    font-weight: bold;
}

/* Responsive styles */
@media (max-width: 600px) {
    main {
        padding: 15px; /* Less padding on small screens */
    }

    .incident-form input,
    .incident-form select,
    .incident-form textarea,
    .incident-form button {
        width: 100%; /* Full width on small screens */
    }
}

    </style>
</head>
<body>
<main>
    <h2>Report a Traffic Incident</h2>
    
    <!-- Display message if form submitted -->
    <?php if (isset($message)) { echo "<p>$message</p>"; } ?>
    
    <!-- Incident Report Form -->
    <form method="POST" action="incident-report.php" class="incident-form">
        <label for="location">Location:</label>
        <input type="text" id="location" name="location" required placeholder="Enter location">

        <label for="incident_type">Type of Incident:</label>
        <select id="incident_type" name="incident_type" required>
            <option value="Accident">Accident</option>
            <option value="Road Block">Road Block</option>
            <option value="Construction">Construction</option>
            <option value="Other">Other</option>
        </select>

        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" required placeholder="Describe the incident"></textarea>

        <button type="submit">Submit Incident Report</button>
    </form>
</main>

<?php include_once '../includes/footer.php'; ?>
</body>
</html>
