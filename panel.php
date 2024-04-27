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
<style>
  * { font-family: monospace; }

</style>
<h1>
  ADMIN PANEL
</h1>
<p>
Enter MODERATION mode or enter CHAT mode?
</p>
<a href="https://xsucks.glitch.me/admin.php">MODERATION</a><br>
<a href="https://xsucks.glitch.me/index.php">CHAT</a><br>