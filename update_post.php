<?php
session_start();

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    echo "<script>alert('Not authorized. Please login first.'); window.location='form.php';</script>";
    exit;
}

$userId = $_SESSION["username"];

// Fetch the user's posts from the database
$userPosts = getUserPosts($userId);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postId = $_POST["post_id"];
    $newContent = $_POST["new_content"];

    if (updatePost($postId, $newContent)) {
        echo "<script>alert('Post updated successfully.');</script>";
    } else {
        echo "<script>alert('Failed to update post. Try again!');</script>";
    }
}

function getUserPosts($userId) {
    $mysqli = new mysqli('localhost', 'team21', 'Pa$$w0rd', 'waph_team21');
    if ($mysqli->connect_errno) {
        echo "Database connection failed: " . $mysqli->connect_error;
        exit;
    }

    $sql = "SELECT id, title FROM posts WHERE user_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $posts[$row['id']] = $row['title'];
    }
    $stmt->close();
    $mysqli->close();
    return $posts;
}

function updatePost($postId, $newContent) {
    $mysqli = new mysqli('localhost', 'team21', 'Pa$$w0rd', 'waph_team21');
    if ($mysqli->connect_errno) {
        echo "Database connection failed: " . $mysqli->connect_error;
        return false;
    }

    $sql = "UPDATE posts SET content = ? WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("si", $newContent, $postId);

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
<!DOCTYPE html>
<html>
<head>
    <title>Update Post</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Update Post</h2>
        <form action="update_post.php" method="POST">
            <label for="post_id">Select Post:</label>
            <select id="post_id" name="post_id" required>
                <?php foreach ($userPosts as $id => $title) : ?>
                    <option value="<?php echo $id; ?>"><?php echo $title; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="new_content">New Content:</label>
            <textarea id="new_content" name="new_content" required></textarea>

            <input type="submit" value="Update Post">
            <button type="button" onclick="window.location.href='index.php'">Back to Home</button>
        </form>
    </div>
</body>
</html>
