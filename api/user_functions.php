<?php
require 'connection.php'; // Include your connection function

/**
 * Registers a new user.
 *
 * @param string $username The username of the new user.
 * @param string $email The email of the new user.
 * @param string $password The password of the new user.
 * @return bool|string True on success, or an error message on failure.
 */
function registerUser($username, $email, $password) {
    $conn = connectDatabase(); // Get the database connection
    $password_hash = password_hash($password, PASSWORD_DEFAULT); // Hash the password

    // Prepare the SQL statement
    $stmt = $conn->prepare("CALL RegisterUser(?, ?, ?)");
    if ($stmt === false) {
        return "Prepare failed: " . $conn->error;
    }

    $stmt->bind_param("sss", $username, $email, $password_hash);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return true; // Registration successful
    } else {
        $error = $stmt->error;
        $stmt->close();
        $conn->close();
        return "Registration failed: " . $error;
    }
}

/**
 * Checks user login credentials.
 *
 * @param string $username The username of the user trying to log in.
 * @param string $password The password of the user trying to log in.
 * @return array|null The user data if successful, or null if login fails.
 */
function checkUserLogin($username, $password) {
    $conn = connectDatabase(); // Get the database connection

    // Prepare the SQL statement
    $stmt = $conn->prepare("CALL CheckUserLogin(?, ?)");
    if ($stmt === false) {
        return "Prepare failed: " . $conn->error;
    }

    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $stmt->close();
    $conn->close();

    return $user ? $user : null; // Return user data or null if login fails
}

/**
 * Validates the CSRF token.
 *
 * @param string $token The CSRF token to validate.
 * @return bool True if valid, false otherwise.
 */
function validateCsrfToken($token) {
    return isset($_SESSION['token']) && hash_equals($_SESSION['token'], $token);
}
?>
