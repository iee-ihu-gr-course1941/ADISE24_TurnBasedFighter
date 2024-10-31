<?php
session_start();
require 'db/connection.php'; // Include your connection function

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = connectDatabase(); // Get the database connection

    if (isset($_POST['register'])) {
        // Registration logic
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("CALL RegisterUser(?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);
        if ($stmt->execute()) {
            $_SESSION['username'] = $username;
            header("Location: welcome.php");
            exit();
        } else {
            $error = "Registration failed: " . $stmt->error;
        }
        $stmt->close();
    } elseif (isset($_POST['login'])) {
        // Login logic
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("CALL CheckUserLogin(?, ?)");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $_SESSION['username'] = $user['username'];
            header("Location: welcome.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
        $stmt->close();
    }
    $conn->close(); // Close the connection
}
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
    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="register">Register</button>
    </form>

    <h1>Login</h1>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>

    <?php if (isset($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error); ?></p>
    <?php endif; ?>
</body>
</html>
