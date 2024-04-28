<?php
// It is suggested that you change this
$username = "admin";
$password = "admin";

// Check if the user is authenticated
if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] !== $username || $_SERVER['PHP_AUTH_PW'] !== $password) {
    header('WWW-Authenticate: Basic realm="Admin Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Unauthorized access';
    exit;
}

$directory = __DIR__; // Current directory

// Function to get all files in a directory
function getFiles($dir) {
    $files = [];
    foreach (scandir($dir) as $file) {
        if ($file !== '.' && $file !== '..') {
            $files[] = $file;
        }
    }
    return $files;
}

// Function to delete a file
function deleteFile($file) {
    if (file_exists($file)) {
        unlink($file);
        return true;
    }
    return false;
}

// Get all files in the directory
$files = getFiles($directory);

// Check if a file is requested for editing, deletion, or creation
if (isset($_GET['file']) && in_array($_GET['file'], $files)) {
    $file = $_GET['file'];
    $filePath = $directory . '/' . $file;
    // Check if file exists and is writable
    if (file_exists($filePath) && is_writable($filePath)) {
        // If delete action is requested
        if (isset($_GET['action']) && $_GET['action'] === 'delete') {
            if (deleteFile($filePath)) {
                echo "<p>File $file deleted successfully.</p>";
            } else {
                echo "<p>Failed to delete file $file.</p>";
            }
        } else {
            // Read the file content
            $content = file_get_contents($filePath);
            // Output form for editing the file
            echo "<h2>Edit File: $file</h2>";
            echo "<form method='post'>";
            echo "<textarea name='content' style='width: 100%; height: 300px;'>$content</textarea><br>";
            echo "<input type='submit' value='Save'>";
            echo "</form>";
            // Save the edited content back to the file
            if (isset($_POST['content'])) {
                file_put_contents($filePath, $_POST['content']);
                echo "<p>File saved successfully.</p>";
            }
        }
    } else {
        echo "<p>File does not exist or is not writable.</p>";
    }
} elseif (isset($_POST['new_file_name'])) {
    // If a new file is requested to be created
    $newFileName = $_POST['new_file_name'];
    $newFilePath = $directory . '/' . $newFileName;
    // Check if file already exists
    if (file_exists($newFilePath)) {
        echo "<p>File $newFileName already exists.</p>";
    } else {
        // Create the new file with initial content
        if (file_put_contents($newFilePath, $_POST['content'])) {
            echo "<p>File $newFileName created successfully.</p>";
        } else {
            echo "<p>Failed to create file $newFileName.</p>";
        }
    }
    // Display the list of files with edit and delete links
    echo "<h2>Files:</h2>";
    foreach ($files as $file) {
        echo "<a href='?file=$file'>$file</a> | ";
        echo "<a href='?file=$file&action=delete' onclick='return confirm(\"Are you sure you want to delete $file?\")'>Delete</a><br>";
    }
    // Output form for creating new files
    echo "<h2>Create New File:</h2>";
    echo "<form method='post'>";
    echo "File Name: <input type='text' name='new_file_name'><br>";
    echo "Content: <textarea name='content' style='width: 100%; height: 300px;'></textarea><br>";
    echo "<input type='submit' value='Create'>";
    echo "</form>";
} else {
    // Display the list of files with edit and delete links
    echo "<h2>Files:</h2>";
    foreach ($files as $file) {
        echo "<a href='?file=$file'>$file</a> | ";
        echo "<a href='?file=$file&action=delete' onclick='return confirm(\"Are you sure you want to delete $file?\")'>Delete</a><br>";
    }
    // Output form for creating new files
    echo "<h2>Create New File:</h2>";
    echo "<form method='post'>";
    echo "File Name: <input type='text' name='new_file_name'><br>";
    echo "Content: <textarea name='content' style='width: 100%; height: 300px;'></textarea><br>";
    echo "<input type='submit' value='Create'>";
    echo "</form>";
}
?>
