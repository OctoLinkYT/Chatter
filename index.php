<?php
session_start();

// Initialize $room
$room = isset($_GET['room']) ? $_GET['room'] : 'public'; // Get room from URL parameter, default to 'public'

// Check if user is not authenticated, redirect to login page
if (!isset($_SESSION["authenticated"]) || !$_SESSION["authenticated"]) {
    header("Location: login.php");
    exit;
}

// Get the logged-in username
$username = $_SESSION["username"];

// Check if the user is the creator of the room
$isRoomCreator = false;
if (!file_exists("chat_$room.txt")) {
    $isRoomCreator = true;
    file_put_contents("chat_$room.txt", "");
    file_put_contents("first_user_$room.txt", strtolower($username));
} else {
    $firstUser = strtolower(trim(file_get_contents("first_user_$room.txt")));
    if ($firstUser === strtolower($username)) {
        $isRoomCreator = true;
    }
}

// Function to clear chat for room creator
function clearChat($username) {
    if ($username === strtolower($_SESSION["username"])) {
        global $room; // Include $room from global scope
        $chatFile = "chat_$room.txt"; // Chat file specific to the room
        // Clear the chat by emptying the file
        file_put_contents($chatFile, "");
    }
}

// Check if the clear command is requested
if (isset($_GET['clear']) && $_GET['clear'] === 'true') {
    clearChat($username);
    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF'] . "?room=$room");
    exit;
}

// Function to extract and execute HTML code from the message
function executeHTMLCode($message) {
    $matches = [];
    preg_match('/\.HTML\{(.*)\}/', $message, $matches); // Extract HTML code between ".HTML{" and "}"
    return $message;
}

// Handle editing chat content
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_chat']) && $_POST['edit_chat'] === 'true') {
    if ($isRoomCreator) {
        $editedContent = $_POST['edited_content'];
        $chatFile = "chat_$room.txt"; // Chat file specific to the room
        file_put_contents($chatFile, $editedContent);
        // Redirect to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF'] . "?room=$room");
        exit;
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatter v1.0</title>
    <style>
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            background-color: #f5f8fa;
            margin: 0;
            padding: 0;
        }

        .hidden {
            display: none;
        }

        #container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #chatbox {
            width: 100%;
            max-height: 300px;
            overflow-y: auto;
            border-top: 1px solid #e1e8ed;
            border-bottom: 1px solid #e1e8ed;
            padding: 10px;
            margin-bottom: 20px;
        }

        .message {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 8px;
            background-color: #f5f8fa;
        }

        .username {
            font-weight: bold;
            color: #1da1f2;
            length: 50%;
        }

        .message-content {
            margin-left: 10px;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"],
        textarea {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #e1e8ed;
            border-radius: 8px;
            box-sizing: border-box;
            resize: none;
        }

        button[type="submit"] {
            padding: 10px 20px;
            background-color: #1da1f2;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #0d91e1;
        }

        #reload-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #1da1f2;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 20px;
            cursor: pointer;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #reload-button:hover {
            background-color: #0d91e1;
        }
    </style>
</head>
<body>
    <div id="container">
        <form id="mainsend" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?room=' . urlencode($room); ?>">
            <input id="usrname" type="text" name="username" placeholder="" readonly>
            <textarea id="message" name="message" placeholder="What's on your mind? Press enter to send." required></textarea>
        </form>
      <p style="">
        <a href="media.php">Click here</a> to upload media
      </p>
        <div id="chatbox">
            <?php
            // Check if form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $username = $_POST["username"];
                $message = $_POST["message"];

                if (!empty($username) && !empty($message)) {
                    global $room; // Include $room from global scope
                    $chatFile = "chat_$room.txt"; // Chat file specific to the room

                    // Check if user is banned
                    $bannedUser = strtolower($username);
                    if (file_exists("banned_users.txt")) {
                        $bannedUsers = file("banned_users.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                        if (in_array($bannedUser, $bannedUsers)) {
                            echo "<div class='message'>You are banned from this chat.</div>";
                            exit;
                        }
                    }

                    // Append the new message to the file
                    $message = "<div class='message'><span class='username'>$username:</span><div class='message-content'>$message</div></div>";
                    file_put_contents($chatFile, $message . PHP_EOL, FILE_APPEND);
                    // Redirect to prevent form resubmission
                    header("Location: " . $_SERVER['PHP_SELF'] . "?room=$room");
                    exit;
                }
            }

            // Load chat messages from file
            $chatFile = "chat_$room.txt"; // Chat file specific to the room

            $messages = file($chatFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($messages as $message) {
                // Execute HTML code if present in the message
                echo executeHTMLCode($message);
            }
            ?>
        </div>

        <button id="reload-button" onclick="location.reload();">&#8634;</button>
        <form method="post" action="logout.php">
            <button type="submit" name="logout">Sign Out</button>
        </form>
        <script>
            var username = "<?php echo $username; ?>";
            document.getElementById("usrname").value = username;
            document.getElementById("message").addEventListener("keydown", function(event) {
                if (event.key === "Enter" && !event.shiftKey) {
                    event.preventDefault();
                    document.getElementById("mainsend").submit();
                }
            });
        </script>
        <?php if ($isRoomCreator): ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?room=' . urlencode($room); ?>">
                <input type="hidden" name="edit_chat" value="true">
                <textarea name="edited_content"><?php echo file_get_contents("chat_$room.txt"); ?></textarea>
                <button type="submit">Save Changes</button>
            </form>
        <?php endif; ?>

    </div>
</body>
<p style="position: fixed; bottom: 1px; left: 10px;">
Chatter v1.0
</p>
<a id="roomLink" style="position: fixed; text-decoration: none;  font-size: 12px; bottom: 0px; left: 10px; color: red;" href="#">Click here to change room. You are currently in <span id="currentRoom">[room should go here]</span>.</a>

<script>
    // Function to prompt the user to enter the new room code
    function promptRoomChange() {
        var newRoom = prompt("Enter the new room code:");
        if (newRoom !== null) {
            changeRoom(newRoom);
        }
    }
    
    // Function to change the room and update the URL
    function changeRoom(newRoom) {
        // Get the current room from the URL parameter
        var urlParams = new URLSearchParams(window.location.search);
        var currentRoom = urlParams.get('room');
        
        // Update the URL parameter
        urlParams.set('room', newRoom);
        
        // Update the link text with the new room value
        document.getElementById('currentRoom').textContent = newRoom;
        
        // Update the URL in the browser without reloading the page
        history.pushState(null, '', '?' + urlParams.toString());
    }
    
    // Add event listener to the link
    document.getElementById('roomLink').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default action of following the link
        
        // Call the function to prompt the user to enter the new room code
        promptRoomChange();
    });
    
    // Get the current room from the URL parameter and display it
    window.addEventListener('DOMContentLoaded', function() {
        var urlParams = new URLSearchParams(window.location.search);
        var currentRoom = urlParams.get('room');
        if (currentRoom !== null) {
            document.getElementById('currentRoom').textContent = currentRoom;
        }
    });
          function toggleEditor() {
            var editorForm = document.getElementById('editor-form');
            if (editorForm.classList.contains('hidden')) {
                editorForm.classList.remove('hidden');
            } else {
                editorForm.classList.add('hidden');
            }
        }
</script>

</html>
