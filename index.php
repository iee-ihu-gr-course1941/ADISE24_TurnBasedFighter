<?php
session_start();
include "db/config.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p>Please log in to play.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turn-Based Fighting Game</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Turn-Based Fighting Game</h1>
    <div id="game-container">
        <button id="hostGame">Host Game</button>
        <button id="joinGame">Join Game</button>
        <!-- Game interface will be expanded here -->
    </div>

    <!-- Include JS files -->
    <script src="js/ajax_helpers.js"></script>
    <script src="js/game.js"></script>
</body>
</html>
