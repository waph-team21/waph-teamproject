<?php
require "session_auth.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>WAPH-Change Password page</title>
  <style>
    /* Resetting default styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Body styles */
    body {
        font-family: "Roboto", sans-serif;
        background-color: #f0f0f0;
        margin: 0;
        padding: 0;
    }

    /* Container styles */
    .container {
        max-width: 800px;
        margin: 50px auto;
        padding: 30px;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    /* Heading styles */
    h2 {
        color: #333;
        text-align: center;
        margin-bottom: 30px;
        font-size: 28px;
    }

    /* Link styles */
    a {
        display: inline-block;
        width: 100%;
        padding: 14px;
        margin-bottom: 20px;
        background-color: #4CAF50;
        color: #fff;
        text-align: center;
        text-decoration: none;
        border-radius: 6px;
        transition: background-color 0.3s ease;
    }

    a:hover {
        background-color: #45a049;
    }

    /* Primary button styles */
    .btn-primary {
        background-color: #4CAF50;
        color: #fff;
        border: none;
        padding: 12px 24px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-block;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #45a049;
    }

    /* Danger button styles */
    .btn-danger {
        background-color: #f44336;
        color: #fff;
        border: none;
        padding: 12px 24px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-block;
        transition: background-color 0.3s ease;
    }

    .btn-danger:hover {
        background-color: #c62828;
    }
</style>

  <script type="text/javascript">
      function displayTime() {
        document.getElementById('digit-clock').innerHTML = "Current time:" + new Date();
      }
      setInterval(displayTime,500);
  </script>
</head>
<body>
  <div class="container">
    <h1>Change password, WAPH</h1>
    <div id="digit-clock"></div>
    <?php
      //some code here
      echo "Visited time: " . date("Y-m-d h:i:sa");
      $rand = bin2hex(openssl_random_pseudo_bytes(16));
      $_SESSION['nocsrftoken'] = $rand;
    ?>
    <form action="changepassword.php" method="POST" class="form login">
      <label for="newpassword">Username:</label>
      <?php echo htmlentities($_SESSION['username']); ?> <br>
      <label for="newpassword">Password:</label>
      <input type="password" class="text_field" name="newpassword" />
      <input type="hidden" class="text_field" name="nocsrftoken" value="<?php echo $rand; ?>" /> <br>
      <button class="button" type="submit">Change password</button>
    </form>
  </div>
</body>
</html>
