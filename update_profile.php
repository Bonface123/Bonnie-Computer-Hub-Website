<?php
// Ensure the user is logged in. If not, redirect to login page.
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'student_portal');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Use `id` from the session for the logged-in user
$user_id = $_SESSION['id'];

// Fetch user data from the database
$stmt = $conn->prepare("SELECT name, email, phone, address, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $phone, $address, $profile_picture);
$stmt->fetch();
$stmt->close();

// Handle profile update if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_name = $_POST['name'] ?? null;
    $new_email = $_POST['email'] ?? null;
    $new_phone = $_POST['phone'] ?? null;
    $new_address = $_POST['address'] ?? null;

    // Initialize $update_stmt to avoid undefined variable issues
    $update_stmt = null;

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the uploads directory exists, if not, create it
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Check file size (limit to 2MB) and allowed file types
        if ($_FILES["profile_picture"]["size"] > 2000000) {
            echo "<div class='message error'>Sorry, your file is too large.</div>";
        } elseif (!in_array($imageFileType, ['jpg', 'jpeg', 'png'])) {
            echo "<div class='message error'>Sorry, only JPG, JPEG & PNG files are allowed.</div>";
        } elseif (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            // Confirm successful upload
            echo "<div class='message success'>File uploaded successfully.</div>";

            // Update profile picture path in the database
            $update_stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, address = ?, profile_picture = ? WHERE id = ?");
            $update_stmt->bind_param("sssssi", $new_name, $new_email, $new_phone, $new_address, $target_file, $user_id);
        } else {
            echo "<div class='message error'>Sorry, there was an error uploading your file.</div>";
        }
    }

    // If no new file uploaded or $update_stmt not set, only update name, email, phone, and address
    if (!$update_stmt) {
        $update_stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
        $update_stmt->bind_param("ssssi", $new_name, $new_email, $new_phone, $new_address, $user_id);
    }

    // Execute update statement
    if ($update_stmt->execute()) {
        echo "<div class='message success'>Profile updated successfully!</div>";
        // Comment out during testing to view success message
        // header('Location: student_dashboard.php');
        exit;
    } else {
        echo "<div class='message error'>Error updating profile: " . $update_stmt->error . "</div>";
    }
    $update_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Profile | Bonnie Computer Hub</title>
    <style>
        /* Base Reset */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        /* Body Styling */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f3f4f6;
            color: #333;
        }

        /* Container Styling */
        .container {
            width: 100%;
            max-width: 500px;
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Form Header */
        h2 { text-align: center; margin-bottom: 20px; color: #333; }

        /* Form Styling */
        form { display: flex; flex-direction: column; gap: 15px; }

        /* Label Styling */
        label {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
            color: #555;
        }

        /* Input Field Styling */
        input[type="text"],
        input[type="email"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus {
            border-color: #007bff;
            outline: none;
        }

        /* Button Styling */
        .btn {
            padding: 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover { background-color: #0056b3; }

        /* Message Box Styling */
        .message {
            padding: 12px;
            margin-top: 15px;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
        }

        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }

        /* Profile Picture Display */
        img {
            max-width: 100px;
            height: auto;
            display: block;
            margin: 10px auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update Profile</h2>

        <form method="POST" enctype="multipart/form-data">
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

            <label>Phone:</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>">

            <label>Address:</label>
            <input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>">

            <label>Current Profile Picture:</label>
            <?php if ($profile_picture): ?>
                <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture">
            <?php endif; ?>

            <label>Profile Picture:</label>
            <input type="file" name="profile_picture" accept="image/*"><br>

            <button type="submit" class="btn">Save Changes</button>
        </form>
    </div>
</body>
</html>
