<?php
session_start();

// Check if the user is logged in and if the session has not been hijacked
if (!isset($_SESSION["authenticated"]) || $_SESSION["authenticated"] !== true || $_SESSION["browser"] !== $_SERVER["HTTP_USER_AGENT"]) {
    header("Location: form.php");
    exit();
}


$mysqli = new mysqli('localhost', 'team21', 'Pa$$w0rd', 'waph_team21');

if (!isset($_GET['post_ID']) || empty($_GET['post_ID'])) {
    echo "Invalid request";
    exit();
}

$post_ID = intval($_GET['post_ID']);

// Ensure the post belongs to the user
$stmt = $mysqli->prepare("SELECT owner FROM posts WHERE post_ID = ?");
$stmt->bind_param("i", $post_ID);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo "No such post found.";
    exit();
}

$row = $result->fetch_assoc();
if ($row['owner'] !== $_SESSION['username']) {
    echo "You do not have permission to delete this post.";
    exit();
}

// Delete the post
$delete_stmt = $mysqli->prepare("DELETE FROM posts WHERE post_ID = ?");
$delete_stmt->bind_param("i", $post_ID);
$delete_stmt->execute();

if ($delete_stmt->affected_rows === 1) {
    echo "Post deleted successfully.";
} else {
    echo "Failed to delete the post.";
}

$delete_stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Post</title>
</head>
<body>
    <p>Post has been deleted. <a href="index.php">Return to home page</a></p>
</body>
</html>