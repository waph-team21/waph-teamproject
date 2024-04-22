<?php
session_start();

// Check if the user is logged in and if the session has not been hijacked
if (!isset($_SESSION["authenticated"]) || $_SESSION["authenticated"] !== true || $_SESSION["browser"] !== $_SERVER["HTTP_USER_AGENT"]) {
    header("Location: form.php");
    exit();
}

 $mysqli = new mysqli('localhost', 'team21', 'Pa$$w0rd', 'waph_team21');
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check for valid POST request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment']) && !empty($_POST['comment'])) {
    if (isset($_POST['post_ID']) && ctype_digit($_POST['post_ID'])) {
        $post_ID = $_POST['post_ID'];
        $comment = trim($_POST['comment']);
        $username = $_SESSION['username'];  // Username of the logged-in user
        $timestamp = date('Y-m-d H:i:s');   // Current timestamp

        // Prepare and bind
        $stmt = $mysqli->prepare("INSERT INTO comments (post_ID, username, comment, timestamp) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $post_ID, $username, $comment, $timestamp);
        
        // Execute and check errors
        if ($stmt->execute()) {
            echo "Comment added successfully.";
        } else {
            echo "Error adding comment: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid post ID.";
    }
} else {
    echo "Please fill all the required fields.";
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Comment</title>
</head>
<body>
    <p>Comment has been added. <a href="index.php">Return to home page</a></p>
</body>
</html>