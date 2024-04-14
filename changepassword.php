<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Password Change</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      max-width: 400px;
      padding: 20px;
      background-color: #fff;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    p {
      color: navy;
    }

    a {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background-color: dodgerblue;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      transition: background-color 0.3s ease;
    }

    a:hover {
      background-color: deepskyblue;
    }
  </style>
</head>
<body>
  <div class="container">
    <?php
      require "session_auth.php";
      require "database.php";
      $token = $_POST['nocsrftoken'];
      if(!isset($token) or $token!=$_SESSION['nocsrftoken']){
        echo "CSRF attack detected";
        die();
      }
      $username = $_SESSION["username"];
      $password = $_REQUEST["newpassword"];
      if(isset($username) and isset($password)){
        if(changepassword($username, $password)){
          echo "<p>Password has been changed!</p> <a href='form.php'>Login</a>";
        }
        else{
          echo "<p>Change password failed!</p>";
        }
      }
      else{
        echo "<p>No username/password provided!</p>";
      }
    ?>
  </div>
</body>
</html>
