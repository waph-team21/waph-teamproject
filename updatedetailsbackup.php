<?php
session_set_cookie_params(15*60, "/", "waph-team21.minifacebook.com", true, true);
session_start();

if (!isset($_SESSION["authenticated"]) || $_SESSION["authenticated"] !== true) {
    echo "<script>alert('Not authorized. Please login first.'); window.location='form.php';</script>";
    exit;
}

$userProfile = getUserProfile($_SESSION['username']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newUsername = $_POST["new_username"];
    $newEmail = $_POST["new_email"];

    if (updateUserDetails($_SESSION['username'], $newUsername, $newEmail)) {
        $_SESSION['username'] = $newUsername;
        $userProfile = getUserProfile($_SESSION['username']);
        echo "<script>alert('User details updated successfully. Check the updates.');</script>";
    } else {
        echo "<script>alert('Failed to update user details. Try again!');</script>";
    }
}

function getUserProfile($username) {
    $mysqli = new mysqli('localhost', 'team21', 'Pa$$w0rd', 'waph_team21');
    if ($mysqli->connect_errno) {
        echo "Database connection failed: " . $mysqli->connect_error;
        exit();
    }

    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function updateUserDetails($username, $newUsername, $newEmail) {
    $mysqli = new mysqli('localhost', 'team21', 'Pa$$w0rd', 'waph_team21');
    if ($mysqli->connect_errno) {
        echo "Database connection failed: " . $mysqli->connect_error;
        return false;
    }

    $sql = "UPDATE users SET username=?, email=? WHERE username=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sss", $newUsername, $newEmail, $username);
    return $stmt->execute();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Details</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #e6f7ff;
      margin: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      text-align: center;
    }
    h2 {
      color: #0056b3;
      margin-bottom: 20px;
    }
    form {
      background-color: #fff;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 123, 255, 0.2);
      width: 100%;
      max-width: 340px;
    }
    label {
      display: block;
      margin-top: 20px;
      color: #333;
    }
    input[type="text"], input[type="email"] {
      width: calc(100% - 24px);
      padding: 12px;
      margin-top: 5px;
      border: 1px solid #b3d7ff;
      border-radius: 25px;
      box-shadow: none;
    }
    input[type="submit"] {
      width: calc(100% - 24px);
      padding: 15px;
      margin-top: 30px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 25px;
      cursor: pointer;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }
    input[type="submit"]:hover {
      background-color: #0056b3;
      transform: translateY(-3px);
    }
    button[type="button"] {
      width: calc(100% - 24px);
      padding: 15px;
      margin-top: 30px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 25px;
      cursor: pointer;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }
    button[type="button"]:hover {
      background-color: #0056b3;
      transform: translateY(-3px);
    }
  </style>
</head>
<body>
  <h2>Update Details</h2>
  <form action="updatedetails.php" method="POST">
    <label for="new_username">New Username:</label>
    <input type="text" id="new_username" name="new_username" value="<?php echo htmlentities($userProfile['username']); ?>" required>
    
    <label for="new_email">New Email:</label>
    <input type="email" id="new_email" name="new_email" value="<?php echo htmlentities($userProfile['email']); ?>" required>

    <input type="submit" value="Update Details">
    <button type="button" onclick="window.location.href='form.php'">Back to Login Page</button>
  </form>
</body>
</html>