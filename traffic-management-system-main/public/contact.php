<?php
// public/contact.php
include_once '../includes/header.php';
include_once '../includes/config.php'; // Database connection

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Insert the contact message into the database
    $query = "INSERT INTO inquiries (name, email, message, submitted_at) VALUES (:name, :email, :message, NOW())";
    $stmt = $pdo->prepare($query);
    
    // Bind values to prevent SQL injection
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':message', $message);

    if ($stmt->execute()) {
        $response = "Thank you! Your message has been sent.";
    } else {
        $response = "Failed to send your message. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact and Support</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
<main>
    <h2>Contact and Support</h2>
    
    <!-- Display response message after form submission -->
    <?php if (isset($response)) { echo "<p>$response</p>"; } ?>
    
    <!-- Contact Form -->
    <section class="contact-form">
        <h3>Send Us a Message</h3>
        <form method="POST" action="contact.php">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="4" required></textarea>

            <button type="submit">Send Message</button>
        </form>
    </section>

    <!-- FAQ Section -->
    <section class="faq">
        <h3>Frequently Asked Questions</h3>
        <div class="faq-item">
            <h4>How do I report a traffic incident?</h4>
            <p>You can report an incident through the "Incident Reporting" page by providing location, type, and a brief description.</p>
        </div>
        <div class="faq-item">
            <h4>How often is traffic data updated?</h4>
            <p>Traffic data is updated every hour to ensure the latest information is available.</p>
        </div>
        <!-- Additional FAQs can be added here -->
    </section>
</main>

<?php include_once '../includes/footer.php'; ?>
</body>
</html>
