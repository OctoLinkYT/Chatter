<?php
if (!isset($_SESSION["authenticated"]) || !$_SESSION["authenticated"]) {
    header("Location: login.php");
    exit;
}
if (isset($_SESSION["username"]) && $_SESSION["username"] == "wholeworldcoding") {

} else {
    header("Location: logout.php");
    exit;
}


    if (isset($_GET['usr'])) {
        $action = $_GET['usr'];
        if ($action == "CLEAR") {
            // Clear the chat file
            file_put_contents($chatFile, "");
            header("Location: logout.php");
            exit;
        } elseif (strpos($action, "BAN") === 0) {
            // Ban a user
            $parts = explode(" ", $action);
            if (count($parts) == 2) {
                $bannedUser = trim($parts[1]);
                $bannedUser = preg_replace('/[^A-Za-z0-9\-]/', '', $bannedUser); // Sanitize username
                $bannedUser = strtolower($bannedUser);
                // Check if user is banned already
                if (!file_exists("banned_users.txt")) {
                    file_put_contents("banned_users.txt", "");
                }
                $bannedUsers = file("banned_users.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                if (!in_array($bannedUser, $bannedUsers)) {
                    // Add user to banned list
                    file_put_contents("banned_users.txt", $bannedUser . PHP_EOL, FILE_APPEND);
                }
            }
            header("Location: logout.php");
            exit;
        }
    }
}
?>