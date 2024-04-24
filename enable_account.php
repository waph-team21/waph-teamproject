<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verify the disabled credentials before enabling the account
    $mysqli = new mysqli('localhost', 'team21', 'Pa$$w0rd', 'waph_team21');
    if ($mysqli->connect_errno) {
        echo "Database connection failed: " . $mysqli->connect_error;
        exit;
    }

    $sql = "SELECT * FROM users WHERE username = ? AND is_enabled = 0 AND password = md5(?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Password is correct and account is disabled, enable the account
        enableAccount($username);
        echo '<script>alert("Your account has been enabled successfully. Please log in again."); window.location.href = "form.php";</script>';
        exit;
    } else {
        echo "Incorrect username or password. Account could not be enabled.";
    }

    $stmt->close();
    $mysqli->close();
}

function enableAccount($username) {
    $mysqli = new mysqli('localhost', 'team21', 'Pa$$w0rd', 'waph_team21');
    if ($mysqli->connect_errno) {
        echo "Database connection failed: " . $mysqli->connect_error;
        exit;
    }

    $sql = "UPDATE users SET is_enabled = 1 WHERE username = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Enable Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            color: #2196f3;
        }
        label {
            display: block;
            margin-bottom: 10px;
            color: #333;
        }
        input[type="text"],
        input[type="password"] {
            width: calc(100% - 24px);
            padding: 12px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 25px;
            box-shadow: none;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #2196f3;
        }
        button[type="submit"] {
            display: inline-block;
            padding: 15px 30px;
            margin-top: 20px;
            background-color: #2196f3;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        button[type="submit"]:hover {
            background-color: #0c7cd5;
            transform: translateY(-3px);
        }
        .message {
            color: #ff0000;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Enable Account</h1>
        <p>To enable your account, enter your previously disabled credentials below:</p>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
            <button type="submit">Enable Account</button>
        </form>
        <div class="message">
            <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if ($result->num_rows != 1) {
                        echo "Incorrect username or password. Account could not be enabled.";
                    }
                }
            ?>
        </div>
    </div>
</body>
</html>
