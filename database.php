<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Database Connection Failure</title>
  <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    h1 {
        color: navy;
        margin-bottom: 20px;
    }

    p.error-message {
        color: red;
    }
  </style>
</head>
<body>
  <div class="container">
    <?php
        $mysqli = new mysqli('localhost', 'team21', 'Pa$$w0rd', 'waph_team21');
        if ($mysqli->connect_errno) {
            echo "<h1>Database connection failed</h1>";
            printf("<p class='error-message'>Error: %s</p>", $mysqli->connect_error);
            exit();
        }

        function sanitize_input($input) {
            $input = trim($input);
            $input = stripslashes($input);
            $input = htmlspecialchars($input);
            return $input;
        }

        function addnewuser($username, $password, $fullname, $mail) {
            global $mysqli;
            $username = sanitize_input($username);
            $password = sanitize_input($password);
            $fullname = sanitize_input($fullname);
            $mail = sanitize_input($mail);

            $prepared_sql = "INSERT INTO users (username, password, fullname, mail) VALUES (?, md5(?), ?, ?);";
            $stmt = $mysqli->prepare($prepared_sql);
            $stmt->bind_param("ssss", $username, $password, $fullname, $mail);

            if ($stmt->execute())
                return TRUE;
            return FALSE;
        }

        function checklogin_mysql($username, $password) {
            global $mysqli;
            $username = sanitize_input($username);
            $password = sanitize_input($password);

            $prepared_sql = "SELECT * FROM users WHERE username = ? AND password = md5(?)";
            $stmt = $mysqli->prepare($prepared_sql);
            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1)
                return TRUE;
            return FALSE;
        }

        function editprofile($username, $fullname, $mail) {
            global $mysqli;
            $username = sanitize_input($username);
            $fullname = sanitize_input($fullname);
            $mail = sanitize_input($mail);

            $prepared_sql = "UPDATE users SET fullname = ?, mail = ? WHERE username = ?;";
            $stmt = $mysqli->prepare($prepared_sql);
            $stmt->bind_param("sss", $fullname, $mail, $username);

            if ($stmt->execute())
                return TRUE;
            return FALSE;
        }

        function changepassword($username, $password) {
            global $mysqli;
            $username = sanitize_input($username);
            $password = sanitize_input($password);

            $prepared_sql = "UPDATE users SET password = md5(?) WHERE username = ?;";
            $stmt = $mysqli->prepare($prepared_sql);
            $stmt->bind_param("ss", $password, $username);

            if ($stmt->execute())
                return TRUE;
            return FALSE;
        }

        function display_posts() {
            global $mysqli;

            // Query to fetch all posts
            $query = "SELECT title, content, post_time, owner FROM posts";
            $result = $mysqli->query($query);

            $htmlContent = "";

            if ($result && $result->num_rows > 0) {
                // Loop through each row of the result set
                while ($row = $result->fetch_assoc()) {
                    // Format the output: title, post_time, owner in bold on one line, content on the next line
                    $htmlContent .= "<div><strong>Title:</strong> " . sanitize_input($row['title']) . ", <strong>Post Time:</strong> " . sanitize_input($row['post_time']) . ", <strong>Owner:</strong> " . sanitize_input($row['owner']) . "</div>";
                    $htmlContent .= "<div><strong>Content:</strong> " . sanitize_input($row['content']) . "</div> <hr>";
                }
            } else {
                $htmlContent = "No posts found";
            }

            echo $htmlContent;
        }
        
        function deletePost($postID) {
        $mysqli = new mysqli('localhost', 'team21', 'Pa$$w0rd', 'waph_team21');
        if ($mysqli->connect_errno) {
            printf("Database connection failed: %s\n", $mysqli->connect_errno);
            return FALSE;
        }
        $prepared_sql = "DELETE FROM posts WHERE postID = ?;";
        $stmt = $mysqli->prepare($prepared_sql);
        $stmt->bind_param("i", $postID);
        if ($stmt->execute()) return TRUE;
        return FALSE;
    }
    ?>
  </div>
</body>
</html>
