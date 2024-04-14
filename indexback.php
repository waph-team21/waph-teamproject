<?php
// Start the session
session_set_cookie_params(15*60,"/","localhost",TRUE,TRUE);
session_start();

// Check if the username and password are posted
if(isset($_POST["username"]) && isset($_POST["password"])) {
    // Validate login credentials
    if (checklogin_mysql($_POST["username"],$_POST["password"])) {
        // Set session variables
        $_SESSION["authenticated"] = TRUE;
        $_SESSION["username"] = $_POST["username"];
        $_SESSION["browser"] = $_SERVER["HTTP_USER_AGENT"];
    } else {
        // Invalid credentials, destroy session and redirect
        session_destroy();
        echo "<script>alert('Invalid username/password');window.location='form.php';</script>";
        die();
    }
}

// Check authentication status
if(!isset($_SESSION["authenticated"]) || $_SESSION["authenticated"] !== TRUE) {
    // Not authenticated, destroy session and redirect
    session_destroy();
    echo "<script>alert('You have not logged in. Please login first!');</script>";
    header("Refresh: 0; url=form.php");
    die();
}

// Check for session hijacking
if(isset($_SESSION["browser"]) && isset($_SERVER["HTTP_USER_AGENT"]) && $_SESSION["browser"] !== $_SERVER["HTTP_USER_AGENT"]) {
    echo "<script>alert('Session hijacking is detected!');</script>";
    header("Refresh: 0; url=form.php");
    die();
}

// Function to validate login credentials against MySQL
function checklogin_mysql($username, $password) {
    $mysqli = new mysqli('localhost', 'team21', 'Pa$$w0rd', 'waph_team21');
    if ($mysqli->connect_errno) {
        printf("Database connection failed: %s\n", $mysqli->connect_errno);
        exit();
    }

    $prepared_sql = "SELECT * FROM users WHERE username = ? AND password = md5(?)";
    $stmt = $mysqli->prepare($prepared_sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1)
        return TRUE;
    return FALSE;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Welcome</title>
  <style>
  body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}

a {
    display: block;
    width: 100%;
    padding: 12px;
    margin-bottom: 10px;
    background-color: #007bff;
    color: #fff;
    text-align: center;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

a:hover {
    background-color: #0056b3;
}

.btn-primary {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-danger {
    background-color: #dc3545;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.btn-danger:hover {
    background-color: #c82333;
}
</style>
</head>
<body>
  <div class="container">
    <h2>Welcome <?php echo isset($_SESSION['username']) ? htmlentities($_SESSION['username']) : "Guest"; ?>!</h2>
    <a href="logout.php">Logout</a>
    <a href="changepasswordform.php">Change Password</a>
    <a href="profileform.php">Edit Profile</a>
  </div>
</body>
</html>
