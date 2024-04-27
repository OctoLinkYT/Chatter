<?php
session_start();

// Check if user is authenticated as admin

if (!isset($_SESSION["authenticated"]) || !$_SESSION["authenticated"]) {
    header("Location: login.php");
    exit;
}
if (isset($_SESSION["username"]) && $_SESSION["username"] == "wholeworldcoding") {

} else {
    header("Location: logout.php");
    exit;
}
// Admin-specific actions (e.g., managing bans, clearing chat history) can be implemented here
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
</head>
<body>
    <h1>Admin Panel</h1>
    <ul>
        <li><a href="ban.php">Manage Bans</a></li>
        <li><a href="index.php/?clear=true">Clear Chat History</a></li>
        <!-- Add more admin features here -->
    </ul>
    <a href="logout.php">Logout</a> <!-- Add logout option -->
</body>
</html>
