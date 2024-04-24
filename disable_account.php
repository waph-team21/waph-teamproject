<?php
session_start();

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    echo "Not authorized. Please login first.";
    exit;
}

$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];

    // Verify the password before disabling the account
    $mysqli = new mysqli('localhost', 'team21', 'Pa$$w0rd', 'waph_team21');
    if ($mysqli->connect_errno) {
        echo "Database connection failed: " . $mysqli->connect_error;
        exit;
    }

    $sql = "SELECT * FROM users WHERE username = ? AND password = md5(?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Password is correct, disable the account
        disableAccount($username);
        session_destroy();
        header("Location: index.php"); // Redirect to homepage
        exit;
    } else {
        echo "Incorrect password. Account could not be disabled.";
    }

    $stmt->close();
    $mysqli->close();
}

function disableAccount($username) {
    $mysqli = new mysqli('localhost', 'team21', 'Pa$$w0rd', 'waph_team21');
    if ($mysqli->connect_errno) {
        echo "Database connection failed: " . $mysqli->connect_error;
        exit;
    }

    $sql = "UPDATE users SET is_enabled = 0 WHERE username = ?";
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
    <title>Disable Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        button[type="submit"] {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Disable Account</h1>
        <p>To disable your account, enter your password below:</p>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Disable Account</button>
            <a class="button" href="index.php">Go to Homepage</a>

        </form>
    </div>
</body>
</html>
