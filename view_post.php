<?php
session_start();

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    echo "<script>alert('Not authorized. Please login first.'); window.location='form.php';</script>";
    exit;
}

$userId = $_SESSION["username"];

// Fetch all posts made by the user
$userPosts = getUserPosts($userId);

function getUserPosts($userId) {
    $mysqli = new mysqli('localhost', 'team21', 'Pa$$w0rd', 'waph_team21');
    if ($mysqli->connect_errno) {
        echo "Database connection failed: " . $mysqli->connect_error;
        exit;
    }

    $sql = "SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
    $stmt->close();
    $mysqli->close();
    return $posts;
}

function getPostComments($postId) {
    $mysqli = new mysqli('localhost', 'team21', 'Pa$$w0rd', 'waph_team21');
    if ($mysqli->connect_errno) {
        echo "Database connection failed: " . $mysqli->connect_error;
        exit;
    }

    $sql = "SELECT * FROM comments WHERE post_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
    $stmt->close();
    $mysqli->close();
    return $comments;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Posts</title>
    <style>
        /* Your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"],
        button[type="button"] {
            padding: 10px 20px;
            background-color: #2196f3;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover,
        button[type="button"]:hover {
            background-color: #0c7cd5;
        }
        .post {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
        }

        .comments {
            margin-top: 10px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            margin: 10px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            text-decoration: none;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>View Posts</h2>
        <a class="button" href="index.php">Go To Homepage</a>

        <?php foreach ($userPosts as $post) : ?>
            <div class="post">
                <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                <p><?php echo htmlspecialchars($post['content']); ?></p>
                <button onclick="showCommentForm(<?php echo $post['id']; ?>)">Add Comment</button>
                <div id="commentForm_<?php echo $post['id']; ?>" style="display: none;">
                    <form action="add_comment.php" method="POST">
                        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                        <textarea name="comment_content" required></textarea>
                        <input type="submit" value="Submit Comment">
                    </form>
                </div>
                <hr>
                <div class="comments">
                    <h4>Comments:</h4>
                    <?php
                    // Fetch comments for this post
                    $comments = getPostComments($post['id']);
                    foreach ($comments as $comment) {
                        echo "<p>" . htmlspecialchars($comment['content']) . "</p>";
                    }
                    ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }

        function showCommentForm(postId) {
            var commentForm = document.getElementById("commentForm_" + postId);
            if (commentForm.style.display === "none") {
                commentForm.style.display = "block";
            } else {
                commentForm.style.display = "none";
            }
        }
    </script>
</body>
</html>
