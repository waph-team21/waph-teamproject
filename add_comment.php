<?php
session_start();

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    echo "<script>alert('Not authorized. Please login first.'); window.location='form.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postId = $_POST["post_id"];
    $commentContent = $_POST["comment_content"];

    // Validate comment content (e.g., check for empty content, length, etc.)
    if (empty($commentContent)) {
        echo "<script>alert('Comment content is required.'); window.history.back();</script>";
        exit;
    }

    // Add comment to the database
    if (addComment($postId, $_SESSION["username"], $commentContent)) {
        echo "<script>alert('Comment added successfully.'); window.location='view_post.php';</script>";
    } else {
        echo "<script>alert('Failed to add comment. Please try again.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.history.back();</script>";
}

function addComment($postId, $userId, $commentContent) {
    $mysqli = new mysqli('localhost', 'team21', 'Pa$$w0rd', 'waph_team21');
    if ($mysqli->connect_errno) {
        echo "Database connection failed: " . $mysqli->connect_error;
        return false;
    }

    $sql = "INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("iss", $postId, $userId, $commentContent);

    if ($stmt->execute()) {
        $stmt->close();
        $mysqli->close();
        return true;
    } else {
        $stmt->close();
        $mysqli->close();
        return false;
    }
}
?>
