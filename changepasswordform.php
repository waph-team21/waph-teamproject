<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Change Password - WAPH</title>
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
  <script type="text/javascript">
      function displayTime() {
        document.getElementById('digit-clock').innerHTML = "Current time: " + new Date().toLocaleTimeString();
      }
      setInterval(displayTime, 1000);
  </script>
</head>
<body>
  <div class="container">
    <h1>Change Password, A MINI FACEBOOK WAPH</h1>
    <h2>TEAM 21</h2>
    <div id="digit-clock"></div>
<?php
  session_start(); // Start the session to access session variables
  // Check if the user is logged in
  echo "<p>Visited time: " . date("Y-m-d h:i:sa") . "</p>";
  if(isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === TRUE && isset($_SESSION['username'])) {
      echo "<p>Logged in as: <strong>" . htmlentities($_SESSION['username']) . "</strong></p>";
  } else {
      echo "<p>Not logged in</p>";
  }

  // Process form submission for changing password
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection details
    $hostname = 'localhost';
    $dbUsername = 'team21'; // Replace with your actual database username
    $dbPassword = 'Pa$$w0rd'; // Replace with your actual database password
    $dbName = 'waph_team21'; // Replace with your actual database name

    // Retrieve user input
    $newUsername = $_SESSION["username"];
    $newPassword = md5($_POST["password"]); // Use md5 hashing for password security

    // Validate user input
    if(empty($newPassword)) {
        echo "Please provide a new password.";
    } else {
        // Attempt to update password
        $dbConnection = new mysqli($hostname, $dbUsername, $dbPassword, $dbName);
        if ($dbConnection->connect_errno) {
            printf("Database connection failed: %s\n", $dbConnection->connect_error);
        } else {
            // Prepare SQL statement for updating password
            $updateQuery = "UPDATE users SET password = ? WHERE username = ?";
            $statement = $dbConnection->prepare($updateQuery);
            $statement->bind_param("ss", $newPassword, $newUsername);

            // Execute the SQL statement
            if ($statement->execute()) {
                echo "Password changed successfully! <br>";
                echo "<a href='form.php'>Login again</a>"; // Display link to login again
                exit(); // Stop further execution
            } else {
                echo "Password change failed. Please try again later.";
            }

            // Close database connection
            $dbConnection->close();
        }
    }
  }
?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="form login">
      New Password: <input type="password" class="text_field" name="password" required /> <br>
      <button class="button" type="submit">Reset Password</button>
    </form>
  </div>
</body>
</html>