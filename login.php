<?php
session_start();

// Function to read user data from the file
function readUserData($filename) {
    $users = array();
    if (file_exists($filename)) {
        $data = file_get_contents($filename);
        $users = unserialize($data);
    }
    return $users;
}

// Function to write user data to the file
function writeUserData($filename, $users) {
    $data = serialize($users);
    file_put_contents($filename, $data);
}

// File to store user data
$filename = "users.txt";

// Check if form is submitted for registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    // Get username and password from form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Read existing user data
    $users = readUserData($filename);

    // Check if username already exists
    if(isset($users[$username])) {
        // If username exists, show error
        header("Location: login.php?error=1");
        exit;
    } else {
        // Add new user to the users array
        $users[$username] = $password;
        // Write updated users data to the file
        writeUserData($filename, $users);
        // Set session variable for authentication
        $_SESSION["authenticated"] = true;
        $_SESSION["username"] = $username; // Store username in session
        // Redirect to chat page
        header("Location: index.php");
        exit;
    }
}

// Check if form is submitted for login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    // Get username and password from form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Read existing user data
    $users = readUserData($filename);

    // Check if username and password match
    if(isset($users[$username]) && $users[$username] === $password) {
        // Authentication successful, set session variables
        $_SESSION["authenticated"] = true;
        $_SESSION["username"] = $username; // Store username in session
        // Redirect to chat page
        header("Location: index.php");
        exit;
    } else {
        // Authentication failed, redirect back to login page with error
        header("Location: login.php?error=1");
        exit;
    }
}
?>
    <style>
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            background-color: #f5f8fa;
            margin: 0;
            padding: 0;
        }

        #container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #chatbox {
            width: 100%;
            max-height: 500px;
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
        }

        .message-content {
            margin-left: 10px;
        }

        form {
            margin-bottom: 20px;
        }

        input,
        textarea {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #e1e8ed;
            border-radius: 8px;
            box-sizing: border-box;
            resize: none;
        }

        #button {
            padding: 8px 10px;
            background-color: #1da1f2;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #button:hover {
            background-color: #0d91e1;
        }
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            background-color: #f5f8fa;
            margin: 0;
            padding: 0;
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login or Register</title>
</head>
<body>
    <div id="container">
        <h2>Login or Register</h2>
        <?php
        // Display error message if authentication failed
        if (isset($_GET["error"]) && $_GET["error"] == 1) {
            echo "<p style='color: red;'>Invalid username or password.</p>";
        }
        ?>
        <h3>Login</h3>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="username" placeholder="Username" required>
            <br>
            <input type="password" name="password" placeholder="Password" required>
            <br>
            <input id="button" type="submit" name="login" value="Login">
        </form>

        <h3>Register</h3>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="username" placeholder="Username" required>
            <br>
            <input type="password" name="password" placeholder="Password" required>
            <br>
            <input  type="submit" name="register" value="Register">
        </form>
      <p>
You can also <a href="https://xsucks.glitch.me/guest.php">click here</a> to enter guest mode.
</p>
    </div>
</body>

</html>
