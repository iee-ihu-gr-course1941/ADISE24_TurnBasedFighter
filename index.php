<?php
session_start();
require 'api/user_functions.php'; // Include your SQL functions file

// Generate a CSRF token if one doesn't exist
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32)); // Generates a secure random token
}

$error = null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        $error = "Invalid CSRF token.";
    } else {
        if (isset($_POST['register'])) {
            // Registration logic
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $error = registerUser($username, $email, $password);
            if ($error === true) {
                $_SESSION['username'] = $username;
                header("Location: welcome.php");
                exit();
            }
        } elseif (isset($_POST['login'])) {
            // Login logic
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = checkUserLogin($username, $password);
            if ($user) {
                $_SESSION['username'] = $user['username'];
                header("Location: welcome.php");
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        }
    }
}

// Display registration and login forms
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Registration/Login</title>
</head>
<body>
    <h1>Register</h1>
    <form method="post" id="registerForm">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token']); ?>">
        <button type="submit" name="register">Register</button>
    </form>

    <h1>Login</h1>
    <form method="post" id="loginForm">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token']); ?>">
        <button type="submit" name="login">Login</button>
    </form>

    <?php if (isset($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <script src="index.js"></script> <!-- Include your JavaScript file for AJAX handling -->
</body>
</html>
