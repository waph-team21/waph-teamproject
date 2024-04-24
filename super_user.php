<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check if the submitted username and password match the super user credentials
    if ($username === "ramiseja" && $password === "Thor@123") {
        // Set session variables to indicate authentication
        $_SESSION['authenticated'] = true;
        $_SESSION['username'] = $username;

        // Redirect to a page after successful login (e.g., dashboard.php)
        header("Location: dashboard.php");
        exit;
    } else {
        $loginError = "Invalid username or password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Super User Login</title>
    <style>
        /* Your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 400px;
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
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #2196f3;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0c7cd5;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Super User Login</h2>
        <?php if (isset($loginError)) : ?>
            <div class="error"><?php echo $loginError; ?></div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
