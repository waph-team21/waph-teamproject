<?php
session_start();

// Check if the user is logged in and if the session has not been hijacked
if (!isset($_SESSION["authenticated"]) || $_SESSION["authenticated"] !== true || $_SESSION["browser"] !== $_SERVER["HTTP_USER_AGENT"]) {
    header("Location: form.php");
    exit();
}

$mysqli = new mysqli('localhost', 'team21', 'Pa$$w0rd', 'waph_team21');

// Validate the post ID is in the query string
if (!isset($_GET['post_ID']) || empty($_GET['post_ID'])) {
    echo "Invalid request";
    exit();
}

$post_ID = intval($_GET['post_ID']);

// Fetch the post to edit
$stmt = $mysqli->prepare("SELECT * FROM posts WHERE post_ID = ?");
$stmt->bind_param("i", $post_ID);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

// Check if the post belongs to the logged-in user
if ($post['owner'] !== $_SESSION['username']) {
    echo "You do not have permission to edit this post.";
    exit();
}

// Proceed with the form for editing if POST request is not made
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'] ?? '';
    $update_stmt = $mysqli->prepare("UPDATE posts SET content = ? WHERE post_ID = ?");
    $update_stmt->bind_param("si", $content, $post_ID);
    $update_stmt->execute();

    if ($update_stmt->affected_rows === 1) {
        echo "Post updated successfully.";
    } else {
        echo "Failed to update the post.";
    }
    $update_stmt->close();
    $mysqli->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Post</title>
</head>
<body>
    <form action="edit_post.php?post_ID=<?= $post_ID ?>" method="post">
        <textarea name="content" rows="4" cols="50"><?= htmlentities($post['content']) ?></textarea><br>
        <input type="submit" value="Update Post">
    </form>
</body>
</html>