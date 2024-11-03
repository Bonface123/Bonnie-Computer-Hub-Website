<?php
// Include database connection
require_once 'db.php';
session_start();

$message = ""; // To store success or error messages

// Assuming you have a form that submits email and password
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Fetch user from database
    $sql = "SELECT * FROM users WHERE email = ? AND role = 'student'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Assuming password verification is handled
        if (password_verify($password, $user['password'])) {
            // Store user data in session
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $user['id']; // This should be your student ID
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            // Optional: store profile picture
            $_SESSION['profile_picture'] = $user['profile_picture'] ?? 'uploads/default_profile.png';
            
            header('Location: student_dashboard.php'); // Redirect to dashboard
            exit;
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that email.";
    }
}

// Handle Student Registration
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "Email already registered.";
    } else {
        $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'student')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $password);
        if ($stmt->execute()) {
            $message = "Registration successful. You can now log in.";
        } else {
            $message = "Error: " . $stmt->error;
        }
    }
}

// Handle Password Reset
if (isset($_POST['reset_password'])) {
    $email = $_POST['reset_email'];

    $sql = "SELECT * FROM users WHERE email = ? AND role = 'student'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "A password reset link has been sent to your email.";
    } else {
        $message = "No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Portal | Login, Register, Forgot Password</title>
    <style>
        .active { display: block; }
        .form { display: none; }

        /* Basic Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #007bff;
        }
        form {
            display: none;
        }
        form.active {
            display: block;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .btn {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            margin-top: 10px;
            cursor: pointer;
        }
        .link {
            text-align: center;
            margin-top: 15px;
        }
        .link a {
            color: #007bff;
            cursor: pointer;
            text-decoration: none;
        }
        .message {
            text-align: center;
            color: red;
            margin-bottom: 15px;
        }
    </style>
    </style>
    <script>
        function showForm(formId) {
            document.getElementById('login-form').classList.remove('active');
            document.getElementById('register-form').classList.remove('active');
            document.getElementById('reset-password-form').classList.remove('active');

            document.getElementById(formId).classList.add('active');
        }

        window.onload = function() {
            showForm('login-form'); // Show login form by default
        };
    </script>
</head>
<body>
    <div class="container">
        <h2>Student Portal</h2>

        <div class="message"><?= $message ?></div>

        <!-- Login Form -->
        <form id="login-form" method="post" class="form active">
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <input type="submit" class="btn" name="login" value="Login">
        </form>

        <!-- Register Form -->
        <form id="register-form" method="post" class="form">
            <label for="name">Name:</label>
            <input type="text" name="name" required>
            
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <input type="submit" class="btn" name="register" value="Register">
        </form>

        <!-- Forgot Password Form -->
        <form id="reset-password-form" method="post" class="form">
            <label for="reset_email">Email:</label>
            <input type="email" name="reset_email" required>

            <input type="submit" class="btn" name="reset_password" value="Reset Password">
        </form>

        <!-- Links to switch forms -->
        <div class="link">
            <a onclick="showForm('login-form')">Login</a> | 
            <a onclick="showForm('register-form')">Register</a> | 
            <a onclick="showForm('reset-password-form')">Forgot Password?</a>
        </div>
    </div>
</body>
</html>
