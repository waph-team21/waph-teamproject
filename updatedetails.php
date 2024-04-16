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
    $newName = $_POST["new_name"];
    $newPhone = $_POST["new_phone"];

    if (updateUserDetails($_SESSION['username'], $newUsername, $newEmail, $newName, $newPhone)) {
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

function updateUserDetails($username, $newUsername, $newEmail, $newName, $newPhone) {
    $mysqli = new mysqli('localhost', 'team21', 'Pa$$w0rd', 'waph_team21');
    if ($mysqli->connect_errno) {
        echo "Database connection failed: " . $mysqli->connect_error;
        return false;
    }

    $sql = "UPDATE users SET username=?, email=?, name=?, phone=? WHERE username=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sssss", $newUsername, $newEmail, $newName, $newPhone, $username);
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
      font-family: 'Roboto', sans-serif;
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
    h2 {
      color: #333;
      margin-bottom: 20px;
    }
    #digit-clock {
      font-size: 16px;
      color: #888;
      margin-bottom: 20px;
    }
    .form {
      margin-top: 20px;
    }
    .text_field {
      width: calc(100% - 24px);
      padding: 12px;
      margin-top: 10px;
      border: 1px solid #ccc;
      border-radius: 25px;
      box-shadow: none;
      transition: border-color 0.3s ease;
    }
    .text_field:focus {
      border-color: #2196f3;
    }
    .button {
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
    .button:hover {
      background-color: #0c7cd5;
      transform: translateY(-3px);
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Update Details</h1>
    <form action="updatedetails.php" method="POST">
      <div class="form-group">
        <label for="new_username">New Username:</label>
        <input type="text" id="new_username" name="new_username" class="text_field" value="<?php echo htmlentities($userProfile['username']); ?>" required>
      </div>
      <div class="form-group">
        <label for="new_email">New Email:</label>
        <input type="email" id="new_email" name="new_email" class="text_field" value="<?php echo htmlentities($userProfile['email']); ?>" required>
      </div>
      <div class="form-group">
        <label for="new_name">New Name:</label>
        <input type="text" id="new_name" name="new_name" class="text_field" value="<?php echo htmlentities($userProfile['name']); ?>" required>
      </div>
      <div class="form-group">
        <label for="new_phone">New Phone:</label>
        <input type="text" id="new_phone" name="new_phone" class="text_field" value="<?php echo htmlentities($userProfile['phone']); ?>" required>
      </div>
      <input type="submit" value="Update Details" class="button">
      <button type="button" class="button" onclick="window.location.href='form.php'">Back to Login Page</button>
    </form>
  </div>
</body>
</html>