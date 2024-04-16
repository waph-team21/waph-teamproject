<?php
session_set_cookie_params(15*60, "/", "waph-team21.minifacebook.com", TRUE, TRUE);
session_start();

// Check if the user is authenticated
if (!isset($_SESSION["authenticated"]) || $_SESSION["authenticated"] !== TRUE) {
    echo "<script>alert('Not authorized. Please login first.'); window.location='form.php';</script>";
    exit; // Stop further execution
}

// Check if the form data is submitted
if(isset($_POST["current_password"]) && isset($_POST["new_password"]) && isset($_POST["confirm_password"])) {
    // Retrieve username and form data
    $username = $_SESSION["username"];
    $currentPassword = $_POST["current_password"];
    $newPassword = $_POST["new_password"];
    $confirmPassword = $_POST["confirm_password"];

    // Check if new password matches confirm password
    if($newPassword != $confirmPassword) {
        echo "<script>alert('New password and confirm password do not match');</script>";
    } else {
        // Change password and update database
        if(changePassword($username, $currentPassword, $newPassword)) {
            echo "<script>alert('Your password has been changed successfully');</script>";
            // Redirect to form.php for login
            echo "<script>window.location='form.php';</script>";
            exit;
        } else {
            echo "<script>alert('Failed to change password, please try again.');</script>";
        }
    }
}

// Function to change password
function changePassword($username, $currentPassword, $newPassword) {
    $mysqli = new mysqli('localhost', 'team21', 'Pa$$w0rd', 'waph_team21');
    if($mysqli->connect_errno){
        printf("Database connection failed: %s\n", $mysqli->connect_error);
        return FALSE;
    }

    // Update password in the database
    $sql = "UPDATE users SET password = MD5(?) WHERE username = ? AND password = MD5(?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sss", $newPassword, $username, $currentPassword);
    if($stmt->execute() && $stmt->affected_rows > 0) {
        return TRUE;
    } else {
        return FALSE;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Password Changed</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #e6f7ff;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      text-align: center;
    }
    .container {
      max-width: 350px;
      padding: 40px;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 123, 255, 0.2);
      text-align: center;
    }
    h2 {
      color: #0056b3;
      margin-bottom: 20px;
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
  <div class="container">
    <h2>Password Changed</h2>
    <button type="button" onclick="window.location='form.php';">Login Again</button>
  </div>
</body>
</html>